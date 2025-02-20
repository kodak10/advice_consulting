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
   

    public function __construct()
    {
        // Bloquer l'accès aux méthodes sauf 'create', 'refuse', 'store' pour Daf
        // MAIS laisser le Comptable accéder à tout
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->hasRole('Comptable')) {
                return $next($request); // Comptable a accès à tout
            }

            // Bloquer toutes les méthodes sauf certaines pour Daf
            $this->middleware('role:Daf')->except(['index', 'refuse']);

            return $next($request);
        });
    }

    
    public function index()
    {
        $devis = Devis::where('pays_id', Auth::user()->pays_id)
        ->where('user_id', Auth::user()->id)
        ->where('status', '!=', 'En attente')
        ->get();

        $factures = Facture::where('pays_id', Auth::user()->pays_id)
        ->where('user_id', Auth::user()->id)
        ->get();

        // dd($factures);
        return view('administration.pages.factures.index', compact('devis', 'factures'));

    } 

    public function refuse($id)
    {
        $devis = Devis::findOrFail($id);

        if ($devis->status !== 'Approuvé') {
            return redirect()->back()->with('error', 'Vous ne pouvez supprimer cete Proforma que si son statut est "Approuvé".');
        }

        // Mettre à jour le statut en "inactif"
        $devis->status = 'Réfusé';
        $devis->save();

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Proforma Réfusée avec succès.');
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

    public function generateCustomNumber()
    {
        $user = Auth::user(); // Récupérer l'utilisateur connecté
        $month = date('m'); // Mois
        $year = date('y'); // Année (2 chiffres)
        $day = date('d/m/Y'); // Date complète
        $initials = strtoupper(substr($user->name, 0, 2)); // Initiales

        // Récupérer le dernier numéro généré aujourd'hui
        $lastFacture = Facture::whereDate('created_at', today())->latest()->first();
        $counter = $lastFacture ? (intval(substr($lastFacture->numero, 5, 3)) + 1) : 1;
        $counter = str_pad($counter, 3, '0', STR_PAD_LEFT); // Format 3 chiffres

        return "{$month}{$year}-{$counter}{$initials} du {$day}";
    }

    public function store(Request $request)
    {
        // Valider les données du formulaire
        $validated = $request->validate([
            'devis_id' => 'required|exists:devis,id',
            'num_bc' => 'required|string',
            'num_rap' => 'required|string',
            'num_bl' => 'required|string',
            'remise_speciale' => 'required|string',

        ]);

        // Récupérer le devis
        $devis = Devis::findOrFail($validated['devis_id']);

        // Mettre à jour le statut du devis en "Terminé"
        $devis->status = 'Terminé';
        $devis->save();

        $customNumber = $this->generateCustomNumber(); // Générer le numéro

        // Créer la facture et y ajouter les informations nécessaires
        $facture = new Facture();
        $facture->devis_id = $validated['devis_id'];
        $facture->num_bc = $validated['num_bc'];
        $facture->num_rap = $validated['num_rap'];
        $facture->num_bl = $validated['num_bl'];
        $facture->user_id = Auth::id();
        $facture->remise_speciale = $validated['remise_speciale']; 

        $facture->numero = $customNumber;
        $facture->pays_id = Auth::user()->pays_id;


        $facture->save();

        // Rediriger avec un message de succès
        return redirect()->route('dashboard.factures.index')->with('success', 'Facture enregistrée avec succès.');
    }

}
