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

    public function store(Request $request)
    {

        // Valider les données du formulaire
        // $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'banque_id' => 'required|exists:banques,id',
        //     'client_id' => 'required|exists:clients,id',
        //     // 'date_emission' => 'required|date',
        //     // 'date_echeance' => 'required|date',
        //     'num_proforma' => 'nullable|string',
        //     'num_bc' => 'nullable|string',
        //     'num_rap' => 'nullable|string',
        //     'num_bl' => 'nullable|string',
        //     'ref_designation' => 'required|string',
        //     'description_designation' => 'required|string',
        //     'qte_designation' => 'required|integer',
        //     'prixUnitaire_designation' => 'required|numeric',
        //     'total_designation' => 'required|numeric',
        //     'remise_speciale' => 'nullable|numeric',
        //     'totall_ht' => 'required|numeric',
        //     'tva' => 'required|numeric',
        //     'total_ttc' => 'required|numeric',
        //     'accompte' => 'nullable|numeric',
        //     'solde' => 'required|numeric',
        // ]);
        //dd($request->all());

        // Enregistrer les données dans la table `devis`
       // $devis = Devis::create($request->all());

        $devis = Devis::create([
            'user_id' => 1,
            'banque_id' => 1,
            'client_id' => $request->client_id,
            'date_emmision' => '2023-10-01',
            'date_echeance' => '2023-11-01',
            'num_proforma' => 'PROF-001',
            'num_bc' => 'BC-001',
            'num_rap' => 'RAP-001',
            'num_bl' => 'BL-001',
            'ref_designation' => 'REF-001',
            'description_designation' => 'Description de test',
            'qte_designation' => 10,
            'prixUnitaire_designation' => 100,
            'total_designation' => 1000,
            'remise_speciale' => 200,
            'totall_ht' => $request->totall_ht,
            'tva' => $request->tva,
            'total_ttc' =>$request->total_ttc,
            'accompte' => $request->accompte,
            'solde' => $request->solde,
        ]);


        // Générer le PDF
        $pdf = Pdf::loadView('frontend.pdf.devis', compact('devis'));

        // Retourner le PDF en téléchargement
        return $pdf->download('devis-' . $devis->id . '.pdf');

        
    }


}
