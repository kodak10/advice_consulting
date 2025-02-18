<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacturesController extends Controller
{
    public function index()
    {
        $devis = Devis::where('status', '!=', 'En attente')->get();
        //$factures = Facture::where('user_id', Auth::user()->id)->get();

        $myFactures = Facture::where('user_id', Auth::user()->id)
                       ->with(['devis.client', 'devis.details']) 
                       ->get();
        return view('administration.pages.factures.index', compact('devis', 'myFactures'));

    } 

    public function refuse($id)
    {
        // Récupérer l'utilisateur
        $devis = Devis::findOrFail($id);

        // Vérifier si le devis est en attente avant suppression
        if ($devis->status !== 'Approuvé') {
            return redirect()->back()->with('error', 'Vous ne pouvez supprimer ce devis que si son statut est "Approuvé".');
        }


        // Mettre à jour le statut en "inactif"
        $devis->status = 'Réfusé';
        $devis->save();

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Devis Réfusé avec succès.');
    }

    public function create($id)
    {
        // Récupérer le devis avec l'ID passé
        $devis = Devis::with('client', 'banque', 'details.designation')->findOrFail($id);

        // Vérifie si les données sont bien récupérées
        $client = $devis->client;
        $banque = $devis->banque;
        $designations = $devis->details; // Dépend de ta relation avec DevisDetail
        
        return view('administration.pages.factures.create', compact('client', 'banque', 'designations', 'devis'));
    }

    public function store(Request $request)
    {
        // Valider les données du formulaire
        $validated = $request->validate([
            'devis_id' => 'required|exists:devis,id',
            'num_bc' => 'required|string',
            'num_rap' => 'required|string',
            'num_bl' => 'required|string',
        ]);

        // Récupérer le devis
        $devis = Devis::findOrFail($validated['devis_id']);

        // Mettre à jour le statut du devis en "Terminé"
        $devis->status = 'Terminé';
        $devis->save();

        // Créer la facture et y ajouter les informations nécessaires
        $facture = new Facture();
        $facture->devis_id = $validated['devis_id'];
        $facture->num_bc = $validated['num_bc'];
        $facture->num_rap = $validated['num_rap'];
        $facture->num_bl = $validated['num_bl'];
        $facture->user_id = Auth::id(); // Ajouter l'ID de l'utilisateur authentifié
        $facture->save();

        // Rediriger avec un message de succès
        return redirect()->route('dashboard.factures.index')->with('success', 'Facture enregistrée avec succès.');
    }

}
