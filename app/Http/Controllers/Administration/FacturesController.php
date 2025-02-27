<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $all_devis = Devis::where('status',  'Approuvé')
        ->get();

        $devis_pays = Devis::where('pays_id', Auth::user()->pays_id)
        ->where('status',  'Approuvé')
        ->get();

        $all_factures = Facture::get();


        $factures_pays = Facture::where('pays_id', Auth::user()->pays_id)
        ->get();

        $mes_factures = Facture::where('pays_id', Auth::user()->pays_id)
        ->where('user_id', Auth::user()->id)
        ->get();
        
        return view('administration.pages.factures.index', compact('all_devis', 'devis_pays', 'all_factures', 'factures_pays', 'mes_factures'));

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

        if ($devis->status === 'Terminé') {
            return redirect()->back()->with('error', "Cette proforma a déjà fait l'objet d'une facture.");
        }
        
        if ($devis->status === 'Réfusé') {
            return redirect()->back()->with('error', "Cette proforma a déjà été refusée.");
        }
        

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

    // public function store(Request $request)
    // {
    //     // Valider les données du formulaire
    //     $validated = $request->validate([
    //         'devis_id' => 'required|exists:devis,id',
    //         'banque_id' => 'required|exists:banques,id',
    //         'client_id' => 'required|exists:clients,id',

    //         'num_bc' => 'required|string',
    //         'num_rap' => 'required|string',
    //         'num_bl' => 'required|string',
    //         'remise_speciale' => 'required|string',

    //     ]);

    //     // Récupérer le devis
    //     $devis = Devis::findOrFail($validated['devis_id']);

    //     $client = Client::find($validated['client_id']);
    //     $banque = Banque::find($validated['banque_id']);

    //     // Mettre à jour le statut du devis en "Terminé"
    //     $devis->status = 'Terminé';
    //     $devis->save();

    //     $customNumber = $this->generateCustomNumber(); // Générer le numéro

    //     // Créer la facture et y ajouter les informations nécessaires
    //     $facture = new Facture();
    //     $facture->devis_id = $validated['devis_id'];
    //     $facture->num_bc = $validated['num_bc'];
    //     $facture->num_rap = $validated['num_rap'];
    //     $facture->num_bl = $validated['num_bl'];
    //     $facture->user_id = Auth::id();
    //     $facture->remise_speciale = $validated['remise_speciale']; 

    //     $facture->numero = $customNumber;
    //     $facture->pays_id = Auth::user()->pays_id;


    //     $facture->save();


    //     // Générer le PDF
    //     $pdf = PDF::loadView('frontend.pdf.facture', compact('devis', 'client', 'banque'));
    //     $pdfOutput = $pdf->output();

    //     $imageName = 'facture-' . $facture->id . '.pdf';

    //     // Assurez-vous que le dossier existe
    //     $directory = 'pdf/factures';
    //     if (!Storage::disk('public')->exists($directory)) {
    //         Storage::disk('public')->makeDirectory($directory);
    //     }

    //     // Enregistrer le PDF dans le dossier storage/app/public/pdf/facture
    //     $imagePath = $directory . '/' . $imageName;
    //     Storage::disk('public')->put($imagePath, $pdfOutput);

        
    //     // Enregistrer le chemin dans la base de données
    //     $facture->pdf_path = $imagePath;
    //     $facture->save();

    //     // Rediriger avec un message de succès
    //     return redirect()->route('dashboard.factures.index')->with('success', 'Facture enregistrée avec succès.');
    // }

    public function store(Request $request)
{
    try {
        // Valider les données du formulaire
        $validated = $request->validate([
            'devis_id' => 'required|exists:devis,id',
            'banque_id' => 'required|exists:banques,id',
            'client_id' => 'required|exists:clients,id',
            'num_bc' => 'required|string',
            'num_rap' => 'required|string',
            'num_bl' => 'required|string',
            'remise_speciale' => 'required|string',
        ]);

        // Récupérer les données
        $devis = Devis::findOrFail($validated['devis_id']);
        $client = Client::findOrFail($validated['client_id']);
        $banque = Banque::findOrFail($validated['banque_id']);

        // Mise à jour du statut du devis
        $devis->status = 'Terminé';
        $devis->save();

        // Génération du numéro personnalisé
        $customNumber = $this->generateCustomNumber();

        // Création de la facture
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

        Log::info('Facture créée avec succès', ['facture_id' => $facture->id]);

        // Génération du PDF
        $pdf = PDF::loadView('frontend.pdf.facture', compact('devis', 'client', 'banque', 'facture'));
        $pdfOutput = $pdf->output();

        $imageName = 'facture-' . $facture->id . '.pdf';
        $directory = 'pdf/factures';

        // Vérifier et créer le dossier si nécessaire
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Sauvegarde du fichier PDF
        $imagePath = $directory . '/' . $imageName;
        Storage::disk('public')->put($imagePath, $pdfOutput);

        // Mise à jour de la facture avec le chemin du fichier
        $facture->pdf_path = $imagePath;
        $facture->save();

        Log::info('PDF généré et enregistré', ['facture_id' => $facture->id, 'pdf_path' => $imagePath]);

        // Télécharger le fichier PDF
        return response()->download(storage_path('app/public/' . $imagePath));

        // return redirect()->route('dashboard.factures.index')->with('success', 'Facture enregistrée avec succès.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation échouée', ['errors' => $e->errors()]);
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'enregistrement de la facture', ['message' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la facture.')->withInput();
    }
}

    public function download($id)
    {
        $factures = Facture::findOrFail($id);

        if (!$factures->pdf_path || !Storage::disk('public')->exists($factures->pdf_path)) {
            return back()->with('error', 'Le fichier demandé n\'existe pas.');
        }

        return response()->download(storage_path('app/public/' . $factures->pdf_path));
    }


    public function exportCsv()
{
    $fileName = 'factures_export.csv';
    $factures = Facture::with(['devis.client', 'user', 'devis.details'])->get();

    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    return response()->stream(function () use ($factures) {
        $handle = fopen('php://output', 'w');
        
        if ($handle === false) {
            throw new \Exception('Impossible d\'ouvrir php://output pour l\'écriture');
        }

        // Entête du fichier CSV
        fputcsv($handle, ['Date', 'Client', 'Coût', 'Etabli Par', 'Statut']);

        // Ajout des données
        foreach ($factures as $facture) {
            fputcsv($handle, [
                $facture->created_at,
                $facture->devis->client->nom,
                $facture->devis->details->sum('total') . ' ' . $facture->devis->devise,
                $facture->user->name,
                $facture->devis->status ?? 'Non renseigné'
            ]);
        }

        fclose($handle);
    }, 200, $headers);
}

    


    

}
