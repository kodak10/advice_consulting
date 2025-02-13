<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
use Illuminate\Http\Request;

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
    
//dd($request);
    $validated = $request->validate([
        'client_id' => 1,
        'date_emission' => 'required|date',
        'date_echeance' => 'required|date',
        // 'numero_bc' => 'required|string',
        // 'numero_bap' => 'required|string',
        // 'numero_bl' => 'required|string',
        'designations' => 'required|array',
    ]);

    // Sauvegarder le client, les dates, et les autres informations
    $commande = new Devis();
    $commande->client_id = $validated['client_id'];
    $commande->date_emission = $validated['date_emission'];
    // $commande->date_echeance = $validated['date_echeance'];
    // $commande->numero_bc = $validated['numero_bc'];
    // $commande->numero_bap = $validated['numero_bap'];
    // $commande->numero_bl = $validated['numero_bl'];
    $commande->save();

    // Sauvegarder les désignations (produits)
    foreach ($validated['designations'] as $designation) {
        // Logic for saving each designation
    }

    //return redirect()->route('commandes.index');
    return redirect()->route('dashboard.devis.create')->with('success', 'Article mis à jour avec succès!');

}

}
