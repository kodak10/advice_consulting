<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use App\Models\Client;
use App\Models\Devis;
use App\Models\DevisDetail;
use App\Models\Facture;
use App\Models\Paiement;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FactureController extends Controller
{

    public function index(Request $request)
    {
        $query = Facture::with(['devis', 'user', 'pays', 'paiements'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $factures = $query->get()->transform(function ($f) {
            return [
                'id' => $f->id,
                'date' => $f->created_at->format('Y-m-d H:i:s'),
                'devis_id' => $f->devis_id,
                'client_id' => $f->devis?->client_id,
                'client_name' => $f->devis?->client?->nom,
                'user_name' => $f->user?->name,
                'pays_name' => $f->user?->pays?->name,
                'date_emission' => $f->date_emission,
                'date_echeance' => $f->date_echeance,
                'total_ht' => $f->devis?->total_ht,
                'total_ttc' => $f->devis?->total_ttc,
                'status' => $f->status,
                'pdf_path' => $f->pdf_path,
                'num_proforma' => $f->devis?->num_proforma,
                'type_facture' => $f->type_facture,
                // ✅ Récupère les détails via la relation du devis
                'details' => $f->details(),
            ];
        });

        return response()->json($factures);
    }

    public function getPdf($id)
    {
        $facture = Facture::findOrFail($id);
        
        if (!Storage::disk('public')->exists($facture->pdf_path)) {
            return response()->json(['error' => 'PDF non trouvé'], 404);
        }
        
        return response()->file(Storage::disk('public')->path($facture->pdf_path));
    }


    public function store(Request $request)
    {
        try {
            Log::info('Début création facture', $request->all());

            $validated = $request->validate([
                'devis_id' => 'required|exists:devis,id',
                'banque_id' => 'required|exists:banques,id',
                'client_id' => 'required|exists:clients,id',
                'num_bc' => 'required|string',
                'num_rap' => 'nullable|string',
                'num_bl' => 'nullable|string',
                'remise_speciale' => 'required|string',
                'type_facture' => 'required|in:Totale,Partielle',
                'net_a_payer' => 'nullable|numeric|min:0',
                'libelle' => 'required|string',
                'montant' => $request->type_facture === 'Partielle' ? 'required|numeric|min:0' : 'nullable',
                'selected_items' => $request->type_facture === 'Partielle' ? 'required|array' : 'nullable',
                'selected_items.*' => $request->type_facture === 'Partielle' ? 'exists:devis_details,id' : 'nullable',
            ]);

            $devis = Devis::with('details')->findOrFail($validated['devis_id']);
            $client = Client::findOrFail($validated['client_id']);
            $banque = Banque::findOrFail($validated['banque_id']);

            // Récupération sécurisée des éléments sélectionnés
            $selectedItems = Arr::get($validated, 'selected_items', []);

            if ($validated['type_facture'] === 'Partielle') {
                // Vérifier que les éléments sélectionnés appartiennent bien au devis
                $invalidItems = array_diff($selectedItems, $devis->details->pluck('id')->toArray());
                if (count($invalidItems) > 0) {
                    return response()->json([
                        'error' => 'Certains éléments sélectionnés ne sont pas valides'
                    ], 422);
                }

                // Calcul du montant HT pour les éléments sélectionnés
                $montantHT = DevisDetail::whereIn('id', $selectedItems)->sum('total');
                $montantTTC = $montantHT * (1 + ($devis->tva / 100));
                
                // Vérification du cumul des factures partielles
                $cumulFactures = Facture::where('devis_id', $devis->id)
                    ->where('type_facture', 'Partielle')
                    ->sum('montant');

                $cumulTotal = $cumulFactures + $montantTTC;

                if ($cumulTotal > $devis->total_ttc) {
                    return response()->json([
                        'error' => "Le cumul des montants partiels dépasse le montant total du devis."
                    ], 422);
                }
            } else {
                // Pour une facture totale
                $montantHT = $devis->total_ht;
                $montantTTC = $devis->total_ttc;
                $selectedItems = $devis->details->pluck('id')->toArray();
            }

            $netAPayer = $validated['net_a_payer'] ?? $montantTTC;

            if ($netAPayer > $montantTTC) {
                return response()->json([
                    'error' => 'Le montant net à payer ne peut pas dépasser le montant total.'
                ], 422);
            }

            // Vérifier si une facture existe déjà pour ce devis (même type)
            $existingFacture = Facture::where('devis_id', $devis->id)
                ->where('type_facture', $validated['type_facture'])
                ->first();

            if ($existingFacture) {
                // Supprimer l'ancien fichier PDF s'il existe
                if ($existingFacture->pdf_path && Storage::disk('public')->exists($existingFacture->pdf_path)) {
                    Storage::disk('public')->delete($existingFacture->pdf_path);
                }
                $facture = $existingFacture;
            } else {
                $facture = new Facture();
                $facture->numero = $this->generateCustomNumber();
            }

            // Mise à jour des attributs de la facture
            $facture->devis_id = $validated['devis_id'];
            $facture->num_bc = $validated['num_bc'];
            $facture->num_rap = $validated['num_rap'];
            $facture->num_bl = $validated['num_bl'];
            $facture->user_id = Auth::id() ?? 1;
            $facture->remise_speciale = $validated['remise_speciale'];
            $facture->pays_id = Auth::user()->pays_id ?? 1;
            $facture->status = 'Facturé';
            $facture->type_facture = $validated['type_facture'];
            $facture->montant = $montantTTC;
            $facture->net_a_payer = $netAPayer;

            $facture->libelle = $validated['libelle'];

            if ($validated['type_facture'] === 'Partielle') {
                $facture->selected_items = $selectedItems;
            }

            $facture->save();

            // Envoi de la notification seulement pour les nouvelles factures
            // if (!$existingFacture) {
            //     Notification::send($facture->devis->user, new FactureCreatedNotification($facture));
            // }

            // Génération du PDF
            $pdfData = [
                'devis' => $devis,
                'client' => $client,
                'banque' => $banque,
                'facture' => $facture,
                'selectedItems' => $selectedItems,
                'tva' => $devis->tva
            ];

            $pdf = PDF::loadView('frontend.pdf.facture', $pdfData);
            $pdfOutput = $pdf->output();
            $directory = 'pdf/factures';
            $fileName = 'facture-' . $facture->id . '.pdf';
            $filePath = $directory . '/' . $fileName;

            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            Storage::disk('public')->put($filePath, $pdfOutput);

            $facture->pdf_path = $filePath;
            $facture->save();

            // Mise à jour du statut du devis si nécessaire
            if ($devis->status !== 'Facturé') {
                $devis->status = 'Facturé';
                $devis->save();
            }
            $pdfUrl = Storage::disk('public')->url($filePath);


            return response()->json([
                'success' => true,
                'message' => $existingFacture
                    ? 'Facture mise à jour avec succès.'
                    : 'Facture enregistrée avec succès.',
                'facture' => $facture,
                'pdf_url' => $pdfUrl,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation', $e->errors());
            return response()->json([
                'error' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de la facture : ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Une erreur est survenue lors de l\'enregistrement de la facture.'
            ], 500);
        }
    }

    public function storePartielle(Request $request)
    {
        $request->merge(['type_facture' => 'Partielle']);
        return $this->store($request);
    }

    public function storeTotale(Request $request)
    {
        $request->merge(['type_facture' => 'Totale']);
        return $this->store($request);
    }

    private function generateCustomNumber()
    {
        $year = date('Y');
        $lastFacture = Facture::whereYear('created_at', $year)->latest()->first();
        
        $number = $lastFacture ? intval(substr($lastFacture->numero, -4)) + 1 : 1;
        
        return 'FACT-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function show($id)
    {
        $facture = Facture::with(['devis.client', 'user', 'pays', 'devis.details'])->findOrFail($id);
        return response()->json($facture);
    }

    public function update(Request $request, $id)
    {
        try {
            $facture = Facture::findOrFail($id);
            
            $validated = $request->validate([
                'num_bc' => 'sometimes|required|string',
                'num_rap' => 'nullable|string',
                'num_bl' => 'nullable|string',
                'remise_speciale' => 'sometimes|required|string',
                'net_a_payer' => 'nullable|numeric|min:0',
                'status' => 'sometimes|required|string',
            ]);

            $facture->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Facture mise à jour avec succès.',
                'facture' => $facture
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la facture : ' . $e->getMessage());
            return response()->json([
                'error' => 'Une erreur est survenue lors de la mise à jour de la facture.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $facture = Facture::findOrFail($id);
            
            // Supprimer le fichier PDF
            if ($facture->pdf_path && Storage::disk('public')->exists($facture->pdf_path)) {
                Storage::disk('public')->delete($facture->pdf_path);
            }
            
            $facture->delete();

            return response()->json([
                'success' => true,
                'message' => 'Facture supprimée avec succès.'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la facture : ' . $e->getMessage());
            return response()->json([
                'error' => 'Une erreur est survenue lors de la suppression de la facture.'
            ], 500);
        }
    }
   
    public function ajouterPaiement(Request $request, $factureId)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0.01',
        ]);

        $facture = Facture::findOrFail($factureId);

        $paiement = Paiement::create([
            'facture_id' => $facture->id,
            'montant' => $request->montant,
            'user_id' => auth()->id() ?? 1,
        ]);

        // ⚡ Mettre à jour le solde de la facture
        $facture->montant_solde = $facture->montant_solde - $paiement->montant;
        if ($facture->montant_solde <= 0) {
            $facture->status = 'Encaissé'; // ou autre statut
            $facture->montant_solde = 0;
        }
        $facture->save();

        return response()->json([
            'success' => true,
            'message' => 'Paiement enregistré avec succès',
            'paiement' => $paiement,
            'facture' => $facture
        ]);
    }

    public function getHistoriquePaiements($factureId)
    {
        $facture = Facture::findOrFail($factureId);


        $paiements = $facture->paiements()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $paiements
        ]);
    }

    public function validateFacture($id)
{
    Log::info($id);
    try {
        // Charger la facture et ses relations
        $facture = Facture::with(['devis', 'devis.client', 'user', 'devis.user'])->findOrFail($id);

        // Vérification du statut du devis
        if ($facture->devis->status === 'Refusé') {
            return response()->json([
                'success' => false,
                'message' => 'La proforma associée a été refusée. Vous ne pouvez pas traiter une facture pour un devis refusé.'
            ], 400);
        }

        if ($facture->status === 'Refusé') {
            return response()->json([
                'success' => false,
                'message' => 'Cette facture a déjà été refusée. Aucune action supplémentaire n’est possible.'
            ], 400);
        }

        if ($facture->status === 'Approuvé') {
            return response()->json([
                'success' => false,
                'message' => 'Cette facture à déjà été approuvée. Aucune action supplémentaire n’est possible.'
            ], 400);
        }

        if ($facture->status === 'Encaissé') {
            return response()->json([
                'success' => false,
                'message' => 'Cette facture à déjà été encaissé. Aucune action supplémentaire n’est possible.'
            ], 400);
        }

        if ($facture->devis->status !== 'Facturé') {
            return response()->json([
                'success' => false,
                'message' => "La facture ne peut pas être traitée car le devis n'est pas encore au statut 'Facturé'. Vérifiez le statut du devis avant de continuer."
            ], 400);
        }

        // Vérification de l’existence du fichier PDF
        $pdfPath = storage_path('app/public/' . $facture->pdf_path);
        if (!file_exists($pdfPath)) {
            

            return response()->json([
                'success' => false,
                'message' => 'Le fichier PDF est introuvable.'
            ], 404);
        }


        // ✅ Mise à jour du statut
        $facture->update(['status' => 'Approuvé']);

        // ✅ Réponse JSON claire pour l’API
        return response()->json([
            'success' => true,
            'message' => 'Facture approuvée avec succès.',
            'facture' => $facture,
        ], 200);

    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'approbation de la facture', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'facture_id' => $id
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue lors du traitement de la facture.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function refuseFacture(Request $request, $id)
    {
        try {
            // 🔹 Validation du message
            $validated = $request->validate([
                'message' => 'required|string|max:255',
            ]);

            // 🔹 Récupérer le devis
            $facture = Facture::findOrFail($id);

            // 🔹 Vérifier le statut
           if (in_array($facture->status, ['Approuvé', 'Encaissé'])) {
                return response()->json([
                    'success' => false,
                    'message' => "Impossible de refuser une facture déjà {$facture->status}.",
                ], 400);
            }

            // 🔹 Vérifier l’existence du PDF
            $pdfPathFacture = storage_path('app/public/' . $facture->pdf_path);
            if (!file_exists($pdfPathFacture)) {
                return response()->json([
                    'success' => false,
                    'message' => "Le fichier PDF du devis n'existe pas.",
                ], 404);
            }

            // 🔹 Mettre à jour le statut et le message
            $facture->status = 'Refusé';
            $facture->message = $validated['message'];
            $facture->save();

            // 🔹 Retour JSON
            return response()->json([
                'success' => true,
                'message' => 'Facture refusée avec succès.',
                'data' => [
                    'facture_id' => $facture->id,
                    'status' => $facture->status,
                ],
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Erreur refus Facture : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Une erreur est survenue lors du refus de la facture.",
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function suivi()
{
    $suivi = DB::table('devis')
        ->leftJoin('factures', 'factures.devis_id', '=', 'devis.id')
        ->leftJoin('clients', 'clients.id', '=', 'devis.client_id')
        ->leftJoin('users', 'users.id', '=', 'devis.user_id')
        ->select(
            'devis.id',
            'devis.num_proforma',
            'devis.date_emission',
            'devis.date_echeance',
            'devis.total_ttc',
            'devis.devise',
            'clients.nom as client_name',
            'users.name as user_name',
            'pays.name as pays_name',
            DB::raw("
                CASE 
                    WHEN factures.status IS NOT NULL 
                        THEN CONCAT('Facture | ', factures.status)
                    ELSE CONCAT('Proforma | ', devis.status)
                END as etape
            "),
            'factures.numero as facture_num',
            'factures.pdf_path as facture_pdf'
            
        )
        ->where(function ($query) {
            $query->whereIn('factures.status', ['Approuvé', 'Encaissé'])
                  ->orWhereNull('factures.status'); // au cas où certaines proformas n'ont pas encore de facture
        })
        ->where('devis.status', '!=', 'Brouillon')
        ->orderBy('devis.date_emission', 'desc')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $suivi
    ]);
}


}
