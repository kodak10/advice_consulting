<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\absences;
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
use App\Models\bien_et_services;
use App\Models\conger;
use App\Models\demandepermissions;
use App\Models\Designation;
use App\Models\TravelRequest;
use PDF;
use Spatie\Permission\Contracts\Permission;

class TravelRequestController extends Controller
{
   public function index()
{
    $travel = TravelRequest:: // charge la relation client
       // ->where('pays_id', Auth::user()->pays_id)
        //->where('status', 'En Attente de facture')
        orderBy('created_at')
        ->get();

    // On retourne le tableau avec client_name
    $travel->transform(function ($d) {
        return [
            'id' => $d->id,
            'nom_prenom' => $d->nom_prenom,
            'date' => $d->created_at,
            'lieu' => $d->lieu,
            'debut' => $d->debut,
            'fin' => $d->date_emission,
            'motif' => $d->date_echeance,
            'montant_en_chiffre' => $d->total_ttc,
            'montant_en_lettre' => $d->montant_en_lettre,
            'billet_avion' => $d->billet_avion,
            'cheque' => $d->cheque,
            'hebergement_repars' => $d->hebergement_repars,
            'especes' => $d->especes,
            'totale' => $d->totale,
            'status' => $d->status,
            'pdf_path' => $d->pdf_path,
        ];
    });

    return response()->json($travel);
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

    /**
     * Enregistrer un nouveau devis
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Règles de validation conditionnelles pour le délai
            $rules = [
                'nom_prenom' => 'required|string',
                'date' => 'required|date',
                'lieu' => 'required|string',
                'debut' => 'required|date',
                'fin' => 'required|date',
                'motif' => 'required|string',
                'montant_en_chiffre' => 'required|integer',
                'montant_en_lettre' => 'required|string',
                'billet_avion' => 'required|integer',
                'cheque' => 'required|integer',
                'hebergement_repars' => 'required|integer',
                'especes' => 'required|integer',
                'totale' => 'required|integer',
            ];

            // Règles conditionnelles pour le délai
            // $delaiType = $request->delai_type;

            // if ($delaiType === 'jours') {
            //     $rules['delai_jours'] = 'required|integer|min:1';
            // } elseif ($delaiType === 'periode') {
            //     $rules['delai_de'] = 'required|integer|min:1';
            //     $rules['delai_a'] = 'required|integer|min:1|gte:delai_de';
            // }
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
            // if ($request->devise === 'XOF') {
            //     $validated['total_ht'] = ceil($validated['total_ht']);
            //     $validated['total_ttc'] = ceil($validated['total_ttc']);
            //     $validated['acompte'] = ceil($validated['acompte']);
            //     $validated['solde'] = ceil($validated['solde']);

            //     // Formatage des lignes
            //     foreach ($validated['lignes'] as &$ligne) {
            //         $ligne['prix_unitaire'] = ceil($ligne['prix_unitaire']);
            //         $ligne['total'] = ceil($ligne['total']);
            //         $ligne['prix_net'] = ceil($ligne['prix_net']);
            //     }
            // }

            // Formater le délai en fonction du type
            // $delaiFormatted = $this->formatDelai($validated);

            // Générer le numéro de proforma si demandé
            $numProforma = $request->generate_num_proforma ? $this->generateNumProforma() : null;

            // Créer le devis
            $travel = new TravelRequest();
            $travel->nom_prenom = $validated['nom_prenom'];
            $travel->date = $validated['date'];
            $travel->lieu = $validated['lieu'];
            $travel->debut = $validated['debut'];
            $travel->fin = $validated['fin'];
            $travel->motif = $validated['motif'];
            $travel->montant_en_chiffre = $validated['montant_en_chiffre'];
            $travel->montant_en_lettre = $validated['montant_en_lettre'];; // Champ texte formaté
            $travel->billet_avion = $validated['billet_avion'];
            $travel->cheque = $validated['cheque'];
            $travel->status = "En Attente de validation";
            $travel->hebergement_repars = $validated['hebergement_repars'];
            $travel->Especes = $validated['especes'];
            $travel->totale = $validated['totale'];


            $travel->save();

            // Enregistrer les détails du devis
            // foreach ($validated['lignes'] as $ligneData) {
            //     $devisDetail = new DevisDetail();
            //     $devisDetail->devis_id = $devis->id;
            //     $devisDetail->designation_id = $ligneData['designation_id'];
            //     $devisDetail->quantite = $ligneData['quantite'];
            //     $devisDetail->prix_unitaire = $ligneData['prix_unitaire'];
            //     $devisDetail->remise = $ligneData['remise'] ?? 0;
            //     $devisDetail->net_price = $ligneData['prix_net'];
            //     $devisDetail->total = $ligneData['total'];
            //     $devisDetail->save();
            // }

            // Générer le PDF
            $pdfPath = $this->generatePdf($travel);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Devis créé avec succès',
                'devis_id' => $travel->id,
                'pdf_url' => $pdfPath,
                'num_proforma' => $numProforma
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur création travel: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du travel request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

// Dans DevisController.php
    public function show($id)
    {
        try {
            // Récupérer le travel request
            $travel = TravelRequest::findOrFail($id);

            // Retourner les données sous forme JSON
            return response()->json([
                'success' => true,
                'data' => $travel
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Erreur show travel ID ' . $id . ': ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Travel non trouvé',
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
            $travel = TravelRequest::findOrFail($id);

            // ✅ On supprime la restriction de modification (optionnel)
            // Si tu veux garder la condition, décommente la section suivante :
            /*
            if (!in_array($travel->status, ['En Attente de validation', 'Réfusé'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez modifier ce travel que si son statut est "En Attente de validation" ou "Réfusé".'
                ], 403);
            }
            */

            // ✅ Forcer les valeurs numériques à être des entiers pour la validation
            $request->merge([
                'montant_en_chiffre' => (int) $request->montant_en_chiffre,
                'billet_avion' => (int) $request->billet_avion,
                'cheque' => (int) $request->cheque,
                'hebergement_repars' => (int) $request->hebergement_repars,
                'especes' => (int) $request->especes,
                'totale' => (int) $request->totale,
            ]);

            // ✅ Règles de validation
            $rules = [
                'nom_prenom' => 'required|string',
                'date' => 'required|date',
                'lieu' => 'required|string',
                'debut' => 'required|date',
                'fin' => 'required|date',
                'motif' => 'required|string',
                'montant_en_chiffre' => 'required|integer',
                'montant_en_lettre' => 'required|string',
                'billet_avion' => 'required|integer',
                'cheque' => 'required|integer',
                'hebergement_repars' => 'required|integer',
                'especes' => 'required|integer',
                'totale' => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Log::warning('Erreur de validation TravelRequest Update:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // ✅ Mise à jour des données
            $travel->nom_prenom = $validated['nom_prenom'];
            $travel->date = $validated['date'];
            $travel->lieu = $validated['lieu'];
            $travel->debut = $validated['debut'];
            $travel->fin = $validated['fin'];
            $travel->motif = $validated['motif'];
            $travel->montant_en_chiffre = $validated['montant_en_chiffre'];
            $travel->montant_en_lettre = $validated['montant_en_lettre'];
            $travel->billet_avion = $validated['billet_avion'];
            $travel->cheque = $validated['cheque'];
            $travel->hebergement_repars = $validated['hebergement_repars'];
            $travel->especes = $validated['especes'];
            $travel->totale = $validated['totale'];

            $travel->save();

            // ✅ Régénérer le PDF
            $pdfPath = $this->generatePdf($travel);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Travel request mis à jour avec succès',
                'travel_id' => $travel->id,
                'pdf_url' => $pdfPath
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur mise à jour TravelRequest: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du travel request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sendTravel($id)
    {
        try {
            // 🔹 Récupérer le devis
            $travel = TravelRequest::findOrFail($id);
            $creator = $travel->user_id;

            // 🔹 Vérifier le statut
            if ($travel->status !== 'En Attente de validation') {
                return response()->json([
                    'success' => false,
                    'message' => "Le Travel request ne peut être envoyé que s'il est un Brouillon.",
                ], 400);
            }

            // 🔹 Vérifier l’existence du PDF
            $pdfPathTravels = storage_path('app/public/' . $travel->pdf_path);
            if (!file_exists($pdfPathTravels)) {
                return response()->json([
                    'success' => false,
                    'message' => "Le fichier PDF du Travel request n'existe pas.",
                ], 404);
            }

            // 🔹 Récupérer le pays de l'utilisateur connecté
            // $userCountry = Auth::user()->pays_id;

            // 🔹 Rôles et notifications
            // $comptables = User::whereHas('roles', fn($q) => $q->where('name', 'Comptable'))
            //     ->where('pays_id', $userCountry)
            //     ->get();

            // $dafsAndComptables = User::whereHas('roles', fn($q) => $q->whereIn('name', ['DAF', 'DG', 'Comptable']))
            //     ->get();

            // $usersToNotify = $comptables->merge($dafsAndComptables)->unique('id');
            // Notification::send($usersToNotify, new DevisCreatedNotification($devis));

            // 🔹 Mettre à jour le statut
            $travel->status = 'Validé';
            $travel->save();

            // 🔹 Retour JSON vers Angular
            return response()->json([
                'success' => true,
                'message' => 'Travel request Envoyée avec succès.',
                'data' => [
                    'travel_id' => $travel->id,
                    'status' => $travel->status,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erreur approbation Travel request : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Une erreur est survenue lors de l'envoi du Travel request.",
                'error' => $e->getMessage(),
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
    private function generatePdf(TravelRequest $travel)
    {
        try {

            $pdf = PDF::loadView('frontend.pdf.travelrequest', compact('travel'))
                     ->setPaper('a4', 'portrait');

            $pdfOutput = $pdf->output();

            $fileName = 'Travel-' . $travel->id . '.pdf';
            $directory = 'pdf/travels';

            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $filePath = $directory . '/' . $fileName;
            Storage::disk('public')->put($filePath, $pdfOutput);

            // Mettre à jour le chemin du PDF dans la base de données
            $travel->pdf_path = $filePath;
            $travel->save();

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
        $travel = TravelRequest::findOrFail($id);

        if (!Storage::disk('public')->exists($travel->pdf_path)) {
            return response()->json(['error' => 'PDF non trouvé'], 404);
        }

        return response()->file(Storage::disk('public')->path($travel->pdf_path));
    }

    public function destroy($id)
    {
        try {
            $travel = TravelRequest::findOrFail($id);

            // Vérifier le statut
            if ($travel->status !== 'Brouillon') {
                return response()->json([
                    'success' => false,
                    'message' => 'Seuls les proforma au statut "Brouillon" peuvent être supprimées.'
                ], 400);
            }

            $travel->delete();

            return response()->json([
                'success' => true,
                'message' => 'Devis supprimé avec succès.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression du devis.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
