<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Devis;
use App\Models\DevisDetail;
use App\Models\Client;
use App\Models\Banque;
use App\Models\Designation;
use PDF;
class DevisController extends Controller
{
   public function index()
{
    $devis = Devis::with('client') // charge la relation client
       // ->where('pays_id', Auth::user()->pays_id)
        //->where('status', 'En Attente de facture')
        ->orderBy('created_at')
        ->get();

    // On retourne le tableau avec client_name
    $devis->transform(function ($d) {
        return [
            'id' => $d->id,
            'date' => $d->created_at,
            'client_id' => $d->client_id,
            'client_name' => $d->client->nom,
            'date_emission' => $d->date_emission,
            'date_echeance' => $d->date_echeance,
            'total_ttc' => $d->total_ttc,
            'status' => $d->status,
            'pdf_path' => $d->pdf_path,
            'num_proforma' => $d->num_proforma,
        ];
    });

    return response()->json($devis);
}

public function getTauxChange(Request $request)
{
    $baseCurrency = $request->get('base', 'XOF');
    
    $apiKey = "d4a11ade825bdc9907f23c6a";
    $response = Http::get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/{$baseCurrency}");
    
    if ($response->successful()) {
        $rates = $response->json()['conversion_rates'];
        $convertedRates = [];
        
        foreach ($rates as $devise => $taux) {
            $convertedRates[$devise] = round(1 / $taux, 4);
        }
        
        return response()->json($convertedRates);
    }
    
    return response()->json(['error' => 'Impossible de récupérer les taux'], 500);
}

public function generateNumProforma()
    {
        $yearMonth = date('Ym');
        
        $lastProforma = Devis::where('num_proforma', 'LIKE', 'ADC '.$yearMonth.'%')
                            ->orderBy('num_proforma', 'desc')
                            ->first();

        $increment = 1;
        if ($lastProforma) {
            $lastIncrement = substr($lastProforma->num_proforma, -3);
            $increment = (int)$lastIncrement + 1;
        }

        return 'ADC ' . $yearMonth . str_pad($increment, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Enregistrer un nouveau devis
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Règles de validation conditionnelles pour le délai
            $rules = [
                'client_id' => 'required|exists:clients,id',
                'banque_id' => 'required|exists:banques,id',
                'date_emission' => 'required|date',
                'date_echeance' => 'required|date|after_or_equal:date_emission',
                'commande' => 'required|numeric|min:0|max:100',
                'livraison' => 'required|numeric|min:0|max:100',
                'validite_offre' => 'required|integer|min:1',
                'delai_type' => 'required|in:jours,deja_livre,planning,periode',
                'total_ht' => 'required|numeric|min:0',
                'total_ttc' => 'required|numeric|min:0',
                'acompte' => 'required|numeric|min:0',
                'solde' => 'required|numeric|min:0',
                'tva' => 'required|numeric|min:0',
                'devise' => 'required|string',
                'taux' => 'required|numeric|min:0.0001',
                'lignes' => 'required|array|min:1',
                'lignes.*.designation_id' => 'required|exists:designations,id',
                'lignes.*.quantite' => 'required|numeric|min:1',
                'lignes.*.prix_unitaire' => 'required|numeric|min:0',
                'lignes.*.remise' => 'nullable|numeric|min:0|max:100',
                'lignes.*.prix_net' => 'required|numeric|min:0',
                'lignes.*.total' => 'required|numeric|min:0',
            ];

            // Règles conditionnelles pour le délai
            $delaiType = $request->delai_type;
            
            if ($delaiType === 'jours') {
                $rules['delai_jours'] = 'required|integer|min:1';
            } elseif ($delaiType === 'periode') {
                $rules['delai_de'] = 'required|integer|min:1';
                $rules['delai_a'] = 'required|integer|min:1|gte:delai_de';
            }
            // Pour 'deja_livre' et 'planning', pas de champs supplémentaires requis

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Formatage des montants si la devise est XOF
            if ($request->devise === 'XOF') {
                $validated['total_ht'] = ceil($validated['total_ht']);
                $validated['total_ttc'] = ceil($validated['total_ttc']);
                $validated['acompte'] = ceil($validated['acompte']);
                $validated['solde'] = ceil($validated['solde']);
                
                // Formatage des lignes
                foreach ($validated['lignes'] as &$ligne) {
                    $ligne['prix_unitaire'] = ceil($ligne['prix_unitaire']);
                    $ligne['total'] = ceil($ligne['total']);
                    $ligne['prix_net'] = ceil($ligne['prix_net']);
                }
            }

            // Formater le délai en fonction du type
            $delaiFormatted = $this->formatDelai($validated);

            // Générer le numéro de proforma si demandé
            $numProforma = $request->generate_num_proforma ? $this->generateNumProforma() : null;

            // Créer le devis
            $devis = new Devis();
            $devis->client_id = $validated['client_id'];
            $devis->banque_id = $validated['banque_id'];
            $devis->date_emission = $validated['date_emission'];
            $devis->date_echeance = $validated['date_echeance'];
            $devis->commande = $validated['commande'];
            $devis->livraison = $validated['livraison'];
            $devis->validite = $validated['validite_offre'];
            // $devis->delai = $validated['delai_type'];
            // $devis->delai_jours = $validated['delai_jours'] ?? null;
            // $devis->delai_de = $validated['delai_de'] ?? null;
            // $devis->delai_a = $validated['delai_a'] ?? null;
            $devis->delai = $delaiFormatted; // Champ texte formaté
            $devis->total_ht = $validated['total_ht'];
            $devis->tva = $validated['tva'];
            $devis->total_ttc = $validated['total_ttc'];
            $devis->acompte = $validated['acompte'];
            $devis->solde = $validated['solde'];
            $devis->devise = $validated['devise'];
            $devis->taux = $validated['taux'];
            $devis->num_proforma = $numProforma;
            $devis->status = "En Attente de validation";
            $devis->pays_id = Auth::user()->pays_id ?? 1;
            $devis->user_id = Auth::id() ?? 1;

            
            $devis->save();

            // Enregistrer les détails du devis
            foreach ($validated['lignes'] as $ligneData) {
                $devisDetail = new DevisDetail();
                $devisDetail->devis_id = $devis->id;
                $devisDetail->designation_id = $ligneData['designation_id'];
                $devisDetail->quantite = $ligneData['quantite'];
                $devisDetail->prix_unitaire = $ligneData['prix_unitaire'];
                $devisDetail->remise = $ligneData['remise'] ?? 0;
                $devisDetail->net_price = $ligneData['prix_net'];
                $devisDetail->total = $ligneData['total'];
                $devisDetail->save();
            }

            // Générer le PDF
            $pdfPath = $this->generatePdf($devis);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Devis créé avec succès',
                'devis_id' => $devis->id,
                'pdf_url' => $pdfPath,
                'num_proforma' => $numProforma
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur création devis: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du devis',
                'error' => $e->getMessage()
            ], 500);
        }
    }

// Dans DevisController.php
public function show($id)
{
    try {
        // Récupérer le devis avec les relations
        $devis = Devis::with(['client', 'banque'])->findOrFail($id);
        
        // Récupérer les détails manuellement avec la relation designation
        $details = DevisDetail::where('devis_id', $id)->with('designation')->get();
        
        \Log::info("Détails récupérés manuellement: " . $details->count());

        $formattedDevis = [
            'id' => $devis->id,
            'num_proforma' => $devis->num_proforma,
            'status' => $devis->status,
            'date_emission' => $devis->date_emission,
            'date_echeance' => $devis->date_echeance,
            'client_id' => $devis->client_id,
            'banque_id' => $devis->banque_id,
            'devise' => $devis->devise,
            'taux' => $devis->taux,
            'tva' => $devis->tva,
            'commande' => $devis->commande,
            'livraison' => $devis->livraison,
            'validite' => $devis->validite,
            'delai_type' => $devis->delai_type,
            'delai_jours' => $devis->delai_jours,
            'delai_de' => $devis->delai_de,
            'delai_a' => $devis->delai_a,
            'total_ht' => $devis->total_ht,
            'total_ttc' => $devis->total_ttc,
            'acompte' => $devis->acompte,
            'solde' => $devis->solde,
            'pdf_path' => $devis->pdf_path,
            'pays_id' => $devis->pays_id,
            'user_id' => $devis->user_id,
            'texte' => $devis->texte,
            'message' => $devis->message,
            'created_at' => $devis->created_at,
            'updated_at' => $devis->updated_at,
            
            // Informations des relations
            'client_name' => $devis->client ? $devis->client->nom : null,
            'banque_name' => $devis->banque ? $devis->banque->nom : null,
            
            // Lignes avec toutes les informations
            'lignes' => $details->map(function($detail) {
                return [
                    'id' => $detail->id,
                    'designation_id' => $detail->designation_id,
                    'quantite' => $detail->quantite,
                    'prix_unitaire' => $detail->prix_unitaire,
                    'remise' => $detail->remise,
                    'prix_net' => $detail->net_price,
                    'total' => $detail->total,
                    'designation' => $detail->designation ? [
                        'id' => $detail->designation->id,
                        'libelle' => $detail->designation->libelle,
                        'prix_unitaire' => $detail->designation->prix_unitaire,
                        'description' => $detail->designation->description,
                        'unite' => $detail->designation->unite
                    ] : null
                ];
            })->toArray()
        ];

        \Log::info("Devis ID {$id} chargé avec " . $details->count() . " lignes");

        return response()->json($formattedDevis);

    } catch (\Exception $e) {
        \Log::error('Erreur show devis ID ' . $id . ': ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Devis non trouvé',
            'error' => $e->getMessage()
        ], 404);
    }
}
    /**
     * Mettre à jour un devis existant
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $devis = Devis::findOrFail($id);

            // Vérifier si le devis peut être modifié
            if (!in_array($devis->status, ['En Attente de validation', 'Réfusé'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez modifier ce devis que si son statut est "En Attente de validation" ou "Réfusé"'
                ], 403);
            }

            // Règles de validation conditionnelles pour le délai
            $rules = [
                'client_id' => 'required|exists:clients,id',
                'banque_id' => 'required|exists:banques,id',
                'date_emission' => 'required|date',
                'date_echeance' => 'required|date|after_or_equal:date_emission',
                'commande' => 'required|numeric|min:0|max:100',
                'livraison' => 'required|numeric|min:0|max:100',
                'validite_offre' => 'required|integer|min:1',
                'delai_type' => 'required|in:jours,deja_livre,planning,periode',
                'total_ht' => 'required|numeric|min:0',
                'total_ttc' => 'required|numeric|min:0',
                'acompte' => 'required|numeric|min:0',
                'solde' => 'required|numeric|min:0',
                'tva' => 'required|numeric|min:0',
                'devise' => 'required|string',
                'taux' => 'required|numeric|min:0.0001',
                'lignes' => 'required|array|min:1',
                'lignes.*.designation_id' => 'required|exists:designations,id',
                'lignes.*.quantite' => 'required|numeric|min:1',
                'lignes.*.prix_unitaire' => 'required|numeric|min:0',
                'lignes.*.remise' => 'nullable|numeric|min:0|max:100',
                'lignes.*.prix_net' => 'required|numeric|min:0',
                'lignes.*.total' => 'required|numeric|min:0',
            ];

            // Règles conditionnelles pour le délai
            $delaiType = $request->delai_type;
            
            if ($delaiType === 'jours') {
                $rules['delai_jours'] = 'required|integer|min:1';
            } elseif ($delaiType === 'periode') {
                $rules['delai_de'] = 'required|integer|min:1';
                $rules['delai_a'] = 'required|integer|min:1|gte:delai_de';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Formatage des montants si la devise est XOF
            if ($request->devise === 'XOF') {
                $validated['total_ht'] = ceil($validated['total_ht']);
                $validated['total_ttc'] = ceil($validated['total_ttc']);
                $validated['acompte'] = ceil($validated['acompte']);
                $validated['solde'] = ceil($validated['solde']);
                
                foreach ($validated['lignes'] as &$ligne) {
                    $ligne['prix_unitaire'] = ceil($ligne['prix_unitaire']);
                    $ligne['total'] = ceil($ligne['total']);
                    $ligne['prix_net'] = ceil($ligne['prix_net']);
                }
            }

            // Formater le délai en fonction du type
            $delaiFormatted = $this->formatDelai($validated);

            // Mettre à jour le devis
            $devis->client_id = $validated['client_id'];
            $devis->banque_id = $validated['banque_id'];
            $devis->date_emission = $validated['date_emission'];
            $devis->date_echeance = $validated['date_echeance'];
            $devis->commande = $validated['commande'];
            $devis->livraison = $validated['livraison'];
            $devis->validite = $validated['validite_offre'];
            $devis->delai_type = $validated['delai_type'];
            $devis->delai_jours = $validated['delai_jours'] ?? null;
            $devis->delai_de = $validated['delai_de'] ?? null;
            $devis->delai_a = $validated['delai_a'] ?? null;
            $devis->delai = $delaiFormatted; // Champ texte formaté
            $devis->total_ht = $validated['total_ht'];
            $devis->tva = $validated['tva'];
            $devis->total_ttc = $validated['total_ttc'];
            $devis->acompte = $validated['acompte'];
            $devis->solde = $validated['solde'];
            $devis->devise = $validated['devise'];
            $devis->taux = $validated['taux'];
            
            $devis->save();

            // Supprimer les anciens détails et créer les nouveaux
            DevisDetail::where('devis_id', $devis->id)->delete();
            
            foreach ($validated['lignes'] as $ligneData) {
                $devisDetail = new DevisDetail();
                $devisDetail->devis_id = $devis->id;
                $devisDetail->designation_id = $ligneData['designation_id'];
                $devisDetail->quantite = $ligneData['quantite'];
                $devisDetail->prix_unitaire = $ligneData['prix_unitaire'];
                $devisDetail->remise = $ligneData['remise'] ?? 0;
                $devisDetail->net_price = $ligneData['prix_net'];
                $devisDetail->total = $ligneData['total'];
                $devisDetail->save();
            }

            // Régénérer le PDF
            $pdfPath = $this->generatePdf($devis);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Devis mis à jour avec succès',
                'devis_id' => $devis->id,
                'pdf_url' => $pdfPath
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur mise à jour devis: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du devis',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Formater le délai en fonction du type
     */
    private function formatDelai(array $data): string
    {
        $delaiType = $data['delai_type'];
        
        switch ($delaiType) {
            case 'jours':
                return $data['delai_jours'] . ' jours';
                
            case 'deja_livre':
                return 'Déjà livré';
                
            case 'planning':
                return 'Selon planning du client';
                
            case 'periode':
                return 'De ' . $data['delai_de'] . ' à ' . $data['delai_a'] . ' jours';
                
            default:
                return 'Non spécifié';
        }
    }

    /**
     * Générer le PDF du devis
     */
    private function generatePdf(Devis $devis)
    {
        try {
            $client = Client::find($devis->client_id);
            $banque = Banque::find($devis->banque_id);
            $details = DevisDetail::where('devis_id', $devis->id)->with('designation')->get();

            $pdf = PDF::loadView('frontend.pdf.devis2', compact('devis', 'client', 'banque', 'details'))
                     ->setPaper('a4', 'portrait');

            $pdfOutput = $pdf->output();

            $fileName = 'devis-' . $devis->id . '.pdf';
            $directory = 'pdf/devis';
            
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $filePath = $directory . '/' . $fileName;
            Storage::disk('public')->put($filePath, $pdfOutput);

            // Mettre à jour le chemin du PDF dans la base de données
            $devis->pdf_path = $filePath;
            $devis->save();

            return Storage::disk('public')->url($filePath);

        } catch (\Exception $e) {
            Log::error("Erreur génération PDF: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Récupérer le PDF d'un devis
     */
    public function getPdf($id)
    {
        $devis = Devis::findOrFail($id);
        
        if (!Storage::disk('public')->exists($devis->pdf_path)) {
            return response()->json(['error' => 'PDF non trouvé'], 404);
        }
        
        return response()->file(Storage::disk('public')->path($devis->pdf_path));
    }

}
