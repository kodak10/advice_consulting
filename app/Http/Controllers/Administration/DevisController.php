<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
use App\Models\DevisDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DevisController extends Controller
{
    public function index()
    {
        $devis = Devis::where('user_id', Auth::user()->id)->get();
        return view('administration.pages.devis.index', compact('devis'));

    } 

    public function create()
    {
        $clients = Client::all();
        $banques = Banque::all();
        $designations = Designation::all();
        return view('administration.pages.devis.create', compact('clients','designations','banques'));

    }

    public function generateNumProforma()
{
    // Récupérer l'année et le mois actuels
    $yearMonth = date('Ym'); // Format : 202502
    
    // Trouver le dernier numéro de proforma qui commence par "ADC" + année + mois
    $lastProforma = Devis::where('num_proforma', 'LIKE', 'ADC '.$yearMonth.'%')
                         ->orderBy('num_proforma', 'desc')
                         ->first();

    // Initialiser l'incrément (si c'est le premier numéro, on commence à 1)
    $increment = 1;
    if ($lastProforma) {
        // Extraire l'incrément du dernier numéro et l'incrémenter
        $lastIncrement = substr($lastProforma->num_proforma, -3); // Récupérer les trois derniers chiffres
        $increment = (int)$lastIncrement + 1;
    }

    // Générer le numéro de proforma avec le format
    $numProforma = 'ADC ' . $yearMonth . str_pad($increment, 3, '0', STR_PAD_LEFT); // Ajouter des zéros devant si nécessaire

    return $numProforma;
}


    public function recap(Request $request)
    {
        
        // Valider les données du formulaire
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',  
            'date_emission' => 'required|date',  
            'date_echeance' => 'required|date|after_or_equal:date_emission',  
            'commande' => 'required|string',  
            'livraison' => 'required|string',  
            'validite' => 'required|string',  
            'banque_id' => 'required|exists:banques,id',  
            'total_ht' => 'required|numeric|min:0',  
            // 'tva' => 'required|numeric|in:18',  
            'tva' => 'required',  

            'total_ttc' => 'required|numeric|min:0',  
            'acompte' => 'required|numeric|min:0',  
            'solde' => 'required|numeric|min:0',  
            // 'delai' => 'required',
            // 'acompte' => 'required',
           
            'designations' => 'required|array', 
            'designations.*.id' => 'required|exists:designations,id',
            'designations.*.designation' => 'required|exists:designations,id', 
            'designations.*.quantity' => 'required|numeric|min:1',
            'designations.*.price' => 'required|numeric|min:0', 
            'designations.*.discount' => 'nullable|numeric|min:0', 
            'designations.*.total' => 'required|numeric|min:0', 
        ]);

        // dd($request);


        $designations = Designation::all();  

        // Récupérer les données validées
        $client = Client::find($validated['client_id']);
        $banque = Banque::find($validated['banque_id']);

        // Passer les données à la vue
        return view('administration.pages.devis.recap', compact('client', 'validated', 'banque', 'designations'));
    }


    public function store(Request $request)
    {
        // Valider la requête
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',  
            'date_emission' => 'required|date',  
            'date_echeance' => 'required|date|after_or_equal:date_emission',  
            'commande' => 'required|string',  
            'livraison' => 'required|string',  
            'validite' => 'required|string',  
            'banque_id' => 'required|exists:banques,id',  
            'total_ht' => 'required|numeric|min:0',  
            // 'tva' => 'required|numeric|in:18',  
            'total_ttc' => 'required|numeric|min:0',  
            'acompte' => 'required|numeric|min:0',  
            'solde' => 'required|numeric|min:0',
            'designations' => 'required|array', 
            'designations.*.designation' => 'required|exists:designations,id', 
            'designations.*.quantity' => 'required|numeric|min:1',
            'designations.*.price' => 'required|numeric|min:0', 
            'designations.*.discount' => 'nullable|numeric|min:0', 
            'designations.*.total' => 'required|numeric|min:0',       
            // 'num_proforma' => 'required|string|max:255',
 
        ]);

        // Générer le numéro de proforma
        $numProforma = $this->generateNumProforma();

        // Récupérer les objets associés
        $client = Client::find($validated['client_id']);
        $banque = Banque::find($validated['banque_id']);

        // Créer le devis dans la base de données
        $devis = new Devis();
        $devis->client_id = $validated['client_id'];
        $devis->date_emission = $validated['date_emission'];
        $devis->date_echeance = $validated['date_echeance'];
        $devis->commande = $validated['commande'];
        $devis->livraison = $validated['livraison'];
        $devis->validite = $validated['validite'];
        $devis->banque_id = $validated['banque_id'];
        $devis->total_ht = $validated['total_ht'];
        // $devis->tva = $validated['tva'];
        $devis->tva = 1;
        $devis->total_ttc = $validated['total_ttc'];
        $devis->acompte = $validated['acompte'];
        $devis->solde = $validated['solde'];
        $devis->delai = 1;
        $devis->user_id = Auth::user()->id;
        $devis->num_proforma = $numProforma;
        $devis->status = "En Attente";

    
        // Sauvegarder le devis
        $devis->save();

        // Enregistrer les détails du devis (DevisDetail)
        foreach ($validated['designations'] as $designationData) {
            $devisDetail = new DevisDetail();
            $devisDetail->devis_id = $devis->id;
            $devisDetail->designation_id = $designationData['designation']; // ID de la désignation
            $devisDetail->quantite = $designationData['quantity'];
            $devisDetail->prix_unitaire = $designationData['price'];
            $devisDetail->remise = $designationData['discount'];
            $devisDetail->total = $designationData['total'];
        
            // Sauvegarder les détails
            $devisDetail->save();
        }
        



        // Générer le PDF
        $pdf = PDF::loadView('frontend.pdf.devis', compact('devis', 'client', 'banque'));
        $pdfOutput = $pdf->output();

        // Enregistrer le PDF sur le serveur
        $pdfFilePath = storage_path('app/public/devis/' . $devis->id . '.pdf');
        file_put_contents($pdfFilePath, $pdfOutput);

        // Ajouter le chemin du fichier PDF à la base de données (optionnel)
        $devis->pdf_path = $pdfFilePath;
        $devis->save();

        // Nettoyer la session
        $request->session()->forget([
            'client_id', 'date_emission', 'date_echeance', 'commande', 'livraison', 'validite',
            'banque_id', 'total_ht', 'tva', 'total_ttc', 'acompte', 'solde', 'designations'
        ]);

        // Retourner le fichier PDF pour le téléchargement
        return response()->download($pdfFilePath)->deleteFileAfterSend(true);

        //return redirect()->route('dashboard.devis.create')->with('success', 'Devis enregistrée avec succès.');

    }

    // public function show()
    // {
    //     return view('administration.pages.index');
    // }



//     public function store(Request $request)
//     {
//         $request->validate([
//             'client_id' => 'required|exists:clients,id',
//             'date_emission' => 'required|date',
//             'date_echeance' => 'required|date',
//             'designations' => 'required|array',
//             'designations.*.designation_id' => 'required|exists:designations,id',
//             'designations.*.quantite' => 'required|integer|min:1',
//             'designations.*.prix_unitaire' => 'required|numeric|min:0',
//             'designations.*.total' => 'required|numeric|min:0',
//         ]);
//         $emission = $request->date_emission;
//         $echeance = $request->date_echeance;
//         $delai = $emission - $echeance;

//         $devis = Devis::create([
//             'client_id' => $request->client_id,
//             'date_emission' => $request->date_emission,
//             'date_echeance' => $request->date_echeance,
//             'commande' => $request->commande,
//             'livraison' => $request->livraison,
//             'validite' => $request->validite,
//             'delai' => $delai,
//             'banque_id' => $request->banque_id,
//             'total_ht' => collect($request->designations)->sum('total'),
//             'tva' => 0.18,
//             'total_ttc' => collect($request->designations)->sum('total') * 1.18,
//             'acompte' => $request->acompte ?? 0,
//             'solde' => ($request->total_ttc ?? 0) - ($request->acompte ?? 0),
//         ]);

//         foreach ($request->designations as $detail) {
//             DevisDetail::create([
//                 'devis_id' => $devis->id,
//                 'designation_id' => $detail['designation_id'],
//                 'quantite' => $detail['quantite'],
//                 'prix_unitaire' => $detail['prix_unitaire'],
//                 'remise' => $detail['remise'] ?? 0,
//                 'total' => $detail['total'],
//             ]);
//         }

//         // return redirect()->route('dashboard.devis.recap')->with('success', 'Devis créé avec succès !');

       

//         // Générer le PDF
//         $pdf = Pdf::loadView('frontend.pdf.devis', compact('devis'));

//         // Retourner le PDF en téléchargement
//         return $pdf->download('devis-' . $devis->id . '.pdf');
//  // Nettoyer la session avant de procéder au téléchargement du PDF
//  session()->forget('devis_data');

//         return redirect()->route('dashboard.devis.create')->with('success', 'Facture enregistrée avec succès.');
//     }

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
