<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DevisController extends Controller
{
    public function index()
    {
        $devis = Devis::all();
        return view('administration.pages.devis.index', compact('devis'));

    }

    public function create()
    {
        $clients = Client::all();
        $designations = Designation::all();
        return view('administration.pages.devis.create', compact('clients','designations'));

    }

    public function recap(Request $request)
    {
        // Valider les données du formulaire avant le récapitulatif
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date_emission' => 'required|date',
            'date_echeance' => 'required|date|after_or_equal:date_emission',
            'numero_bc' => 'required|string|max:50',
            'designations' => 'required|array',
            'designations.*.description' => 'required|string',
            'designations.*.quantite' => 'required|integer|min:1',
            'designations.*.pu' => 'required|numeric|min:0',
            'remise' => 'nullable|numeric|min:0',
            'accompte' => 'nullable|numeric|min:0',
        ]);

        // Sauvegarder temporairement les données en session
        session(['devis_data' => $validatedData]);

        // Afficher la page de récapitulatif
        return view('administration.pages.devis.recap', compact('validatedData'));
    }

    public function store(Request $request)
    {
        // Vérifier si les données existent en session
        $devisData = session('devis_data');

        if (!$devisData) {
            return redirect()->route('dashboard.devis.create')->with('error', 'Aucune donnée à enregistrer.');
        }

        // Enregistrement de la facture en base de données
        // $devis = Devis::create([
        //     'client_id' => $devisData['client_id'],
        //     'date_emission' => $devisData['date_emission'],
        //     'date_echeance' => $devisData['date_echeance'],
        //     'numero_bc' => $devisData['numero_bc'],
        //     'remise' => $devisData['remise'] ?? 0,
        //     'accompte' => $devisData['accompte'] ?? 0,
        //     'user_id' => 1,
        //     'banque_id' => 1,
        //     'ref_designation' => 'REF-001',
        //     'description_designation' => 'Description de test',
        //     'qte_designation' => 10,
        //     'prixUnitaire_designation' => 100,
        //     'total_designation' => 1000,
        //     'totall_ht' => 2000,
        //     'tva' => 18,
        //     'total_ttc' => 5000,
        //     'accompte' => 1000,
        //     'solde' => 3500,
        // ]);

        $devis = Devis::create([
            'client_id' => $devisData['client_id'],
            'date_emission' => $devisData['date_emission'],
            'date_echeance' => $devisData['date_echeance'],
            'numero_bc' => $devisData['numero_bc'],
            'remise' => $devisData['remise'] ?? 0,
            'accompte' => $devisData['accompte'] ?? 0,
            'user_id' => auth()->id(),  // Utiliser l'ID de l'utilisateur connecté
            'banque_id' => 1,  // Banque par défaut (modifier si nécessaire)
            'totall_ht' => $devisData['total_ht'] ?? 0,
            'tva' => $devisData['tva'] ?? 0,
            'total_ttc' => $devisData['total_ttc'] ?? 0,
            'solde' => $devisData['solde'] ?? 0,
            'ref_designation' => 'REF-001',
            'description_designation' => 'Description de test',
            'qte_designation' => 10,
            'prixUnitaire_designation' => 100,
            'total_designation' => 1000,

        ]);

        // Enregistrement des désignations
       // Enregistrement des désignations et association avec le devis
       foreach ($devisData['designations'] as $designation) {
        $devis->designations()->create([
            'description' => $designation['description'],
            'quantite' => $designation['quantite'],
            'prix_unitaire' => $designation['pu'],
            'total' => $designation['quantite'] * $designation['pu'],
            'reference' => 'REF-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT), // Générer une référence unique

        ]);
        }

       

        // Générer le PDF
        $pdf = Pdf::loadView('frontend.pdf.devis', compact('devis'));

        // Retourner le PDF en téléchargement
        return $pdf->download('devis-' . $devis->id . '.pdf');
 // Nettoyer la session avant de procéder au téléchargement du PDF
 session()->forget('devis_data');

        return redirect()->route('dashboard.devis.create')->with('success', 'Facture enregistrée avec succès.');
    }

    public function storeRecap(Request $request)
{
    // Stocker les données en session
    session(['devis_data' => $request->all()]);

    // Redirection vers la page de récapitulatif
    return redirect()->route('dashboard.devis.recap');
}


    // public function store(Request $request)
    // {

    //     // Valider les données du formulaire
    //     // $request->validate([
    //     //     'user_id' => 'required|exists:users,id',
    //     //     'banque_id' => 'required|exists:banques,id',
    //     //     'client_id' => 'required|exists:clients,id',
    //     //     // 'date_emission' => 'required|date',
    //     //     // 'date_echeance' => 'required|date',
    //     //     'num_proforma' => 'nullable|string',
    //     //     'num_bc' => 'nullable|string',
    //     //     'num_rap' => 'nullable|string',
    //     //     'num_bl' => 'nullable|string',
    //     //     'ref_designation' => 'required|string',
    //     //     'description_designation' => 'required|string',
    //     //     'qte_designation' => 'required|integer',
    //     //     'prixUnitaire_designation' => 'required|numeric',
    //     //     'total_designation' => 'required|numeric',
    //     //     'remise_speciale' => 'nullable|numeric',
    //     //     'totall_ht' => 'required|numeric',
    //     //     'tva' => 'required|numeric',
    //     //     'total_ttc' => 'required|numeric',
    //     //     'accompte' => 'nullable|numeric',
    //     //     'solde' => 'required|numeric',
    //     // ]);
    //     //dd($request->all());

    //     // Enregistrer les données dans la table `devis`
    //    // $devis = Devis::create($request->all());

    //     $devis = Devis::create([
    //         'user_id' => 1,
    //         'banque_id' => 1,
    //         'client_id' => $request->client_id,
    //         'date_emmision' => '2023-10-01',
    //         'date_echeance' => '2023-11-01',
    //         'num_proforma' => 'PROF-001',
    //         'num_bc' => 'BC-001',
    //         'num_rap' => 'RAP-001',
    //         'num_bl' => 'BL-001',
    //         'ref_designation' => 'REF-001',
    //         'description_designation' => 'Description de test',
    //         'qte_designation' => 10,
    //         'prixUnitaire_designation' => 100,
    //         'total_designation' => 1000,
    //         'remise_speciale' => 200,
    //         'totall_ht' => 2000,
    //         'tva' => 18,
    //         'total_ttc' => 5000,
    //         'accompte' => 1000,
    //         'solde' => 3500,
    //     ]);


    //     // Générer le PDF
    //     $pdf = Pdf::loadView('frontend.pdf.devis', compact('devis'));

    //     // Retourner le PDF en téléchargement
    //     return $pdf->download('devis-' . $devis->id . '.pdf');

        
    // }


}
