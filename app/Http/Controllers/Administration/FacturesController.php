<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Mail\FactureApprovalMail;
use App\Mail\FactureMail;
use App\Models\Banque;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
use App\Models\DevisDetail;
use App\Models\Facture;

use App\Models\Pays;
use App\Models\User;
use App\Notifications\DevisRefusedNotification;  // Importation correcte de la notification
use App\Notifications\FactureApprovalNotification;
use App\Notifications\FactureApprovedNotification;
use App\Notifications\FactureCreatedNotification;
use App\Notifications\FactureRefusedNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use Illuminate\Support\Arr;

class FacturesController extends Controller
{
   

    public function __construct()
    {
        // Bloquer l'accès aux méthodes sauf 'create', 'refuse', 'store' pour Daf
        // MAIS laisser le Comptable accéder à tout
        $this->middleware(function ($request, $next) {
            if (Auth::check() && (Auth::user()->hasRole('Comptable') || Auth::user()->hasRole('DG'))) {
                return $next($request); // Comptable a accès à tout
            }

            // Bloquer toutes les méthodes sauf certaines pour Daf
            $this->middleware('role:Daf')->except(['index', 'refuse']);

            return $next($request);
        });
    }


    public function indexTotale(Request $request)
    {
        $payss = Pays::get();
        $user = Auth::user();


        $all_devis = Devis::where('status', ['En Attente de facture', 'En Attente du Daf'])
        ->orWhereHas('facture', function ($query) {
            $query->where('type_facture', 'Totale');
        })->get();
       

       $devis_pays = Devis::where('pays_id', Auth::user()->pays_id)
        ->whereIn('status', ['En Attente de facture', 'En Attente du Daf']) // Filtrer uniquement ces statuts
        ->whereDoesntHave('facture', function ($q) {
            $q->where('type_facture', 'Partielle'); // Exclure les devis avec facture "Partielle"
        })->get();




        $facturesQuery = Facture::query();

        $facturesQuery->whereHas('devis', function ($query) {
            $query->where('status', ['Facturé', 'En Attente du Daf']);
        });

        // Filtre par pays (uniquement pour les Dafs)
        if ($user->hasRole(['Daf', 'DG']) && $request->has('pays') && $request->pays != "") {
            $facturesQuery->where('pays_id', $request->pays);
        } else {
            $facturesQuery->where('pays_id', $user->pays_id);
        }
    
        // Filtre "Mes factures"
        if ($request->has('my') && $request->my != "") {
            $facturesQuery->where('user_id', $user->id);
        }
    
        // Filtre par période
        if ($request->has('start') && $request->start != "") {
            $facturesQuery->where('created_at', '>=', $request->start);
        }
    
        if ($request->has('end') && $request->end != "") {
            $endDate = $request->end . ' 23:59:59'; // Pour inclure toute la journée
            $facturesQuery->where('created_at', '<=', $endDate);
        }
    
        // Gestion de l'affichage initial et de la pagination
        if ($request->has('pays') || $request->has('my') || $request->has('start') || $request->has('end')) {
            $all_factures = $facturesQuery->paginate(10); // Paginer les résultats filtrés
        } else {
            $all_factures = $facturesQuery->limit(10)->get(); // Afficher seulement 10 factures au départ
        }

        $factureCommercials = Facture::with(['devis.client'])
        ->whereHas('devis', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })
        ->get(); 

        

        return view('administration.pages.factures.totale.index', compact('all_devis', 'devis_pays', 'all_factures', 'factureCommercials', 'payss'));

    } 
    
    public function indexPartielle(Request $request)
    {
        $payss = Pays::get();
        $user = Auth::user();

        $all_devis = Devis::where('status', ['En Attente de facture', 'En Attente du Daf'])
        ->orWhereHas('facture', function ($query) {
            $query->where('type_facture', 'Partielle');
        })->get();

       $devis_pays = Devis::where('pays_id', Auth::user()->pays_id)
        ->whereIn('status', ['En Attente de facture', 'En Attente du Daf', 'Facturé']) // Filtrer uniquement ces statuts
        ->whereDoesntHave('facture', function ($q) {
            $q->where('type_facture', 'Totale'); // Exclure les devis avec facture "Totale"
        })->get();

        $facturesQuery = Facture::query();

        $facturesQuery->whereHas('devis', function ($query) {
            $query->where('status', ['Facturé', 'En Attente du Daf']);
        });

        // Filtre par pays (uniquement pour les Dafs)
        if ($user->hasRole(['Daf', 'DG']) && $request->has('pays') && $request->pays != "") {
            $facturesQuery->where('pays_id', $request->pays);
        } else {
            $facturesQuery->where('pays_id', $user->pays_id);
        }
    
        // Filtre "Mes factures"
        if ($request->has('my') && $request->my != "") {
            $facturesQuery->where('user_id', $user->id);
        }
    
        // Filtre par période
        if ($request->has('start') && $request->start != "") {
            $facturesQuery->where('created_at', '>=', $request->start);
        }
    
        if ($request->has('end') && $request->end != "") {
            $endDate = $request->end . ' 23:59:59'; // Pour inclure toute la journée
            $facturesQuery->where('created_at', '<=', $endDate);
        }
    
        // Gestion de l'affichage initial et de la pagination
        if ($request->has('pays') || $request->has('my') || $request->has('start') || $request->has('end')) {
            $all_factures = $facturesQuery->paginate(10); // Paginer les résultats filtrés
        } else {
            $all_factures = $facturesQuery->limit(10)->get(); // Afficher seulement 10 factures au départ
        }

        $factureCommercials = Facture::with(['devis.client'])
        ->whereHas('devis', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->get(); 

        //dd($all_devis);

        return view('administration.pages.factures.partielle.index', compact('all_devis', 'devis_pays', 'all_factures', 'factureCommercials', 'payss'));

    } 

    public function refuse($id, Request $request)
    {
        $factures = Facture::findOrFail($id);

        $validated = $request->validate([
            'message' => 'required|string|min:5', 
        ]);

        // Vérification du statut de la facture avant la mise à jour
        if ($factures->status === "Facturé") {
            return redirect()->route('dashboard.factures.totales.index')->with('error', 'Vous ne pouvez refuser cette Facture que si son statut est "En Attente de facture ou en Attente du Daf"');
        }

        // Changer le statut du devis à "Réfusé"
        $factures->status = 'Réfusé';
        $factures->devis->status = 'En Attente de facture';

        $factures->message = $validated['message'];
        $factures->save();
        $factures->devis->save();

        // Notification de refus envoyée à l'utilisateur créateur
        $creator = $factures->user;  // L'utilisateur qui a créé la facture
        Notification::send($creator, new FactureRefusedNotification($factures));

        // Retourner à la page de liste avec un message de succès
        return redirect()->route('dashboard.factures.totales.index')->with('success', 'Facture Réfusée avec succès.');
    }


    // public function createTotale($id)
    // {
    //     $devis = Devis::with('client', 'banque', 'details.designation')->findOrFail($id);

    //     $factures = Facture::where('devis_id', $devis->id)->first();

      
        
        
    //     // if ($devis->status === 'Réfusé') {
    //     //     return redirect()->back()->with('error', "Cette proforma a déjà été refusée.");
    //     // }


    //     // if ($factures && $factures->status === 'Facturé') {
    //     //     return redirect()->back()->with('error', "Cette proforma a déjà l'objet de facture.<br> Vous pouvez la facturé ou la réfusée.");
    //     // }

    //     // Vérifier si la facture existe ET a un statut "Facturé" ET un type "Totale"
    //     if ($factures && $factures->status === 'Facturé' && $factures->type === 'Totale') {
    //         return redirect()->back()->with('error', "Cette proforma a déjà fait l'objet de facture.<br> Vous pouvez la facturer ou la refuser.");
    //     }


        
    //     $client = $devis->client;
    //     $banque = $devis->banque;
    //     $designations = $devis->details; // Dépend de ta relation avec DevisDetail
        
    //     return view('administration.pages.factures.totale.create', compact('client', 'banque', 'designations', 'devis', 'factures'));
    // }

    public function createTotale($id)
{
    $devis = Devis::with('client', 'banque', 'details.designation')->findOrFail($id);
    $facture = Facture::where('devis_id', $devis->id)->first(); // Changé $factures en $facture

    if ($facture && $facture->status === 'Facturé' && $facture->type === 'Totale') {
        return redirect()->back()->with('error', "Cette proforma a déjà fait l'objet de facture.<br> Vous pouvez la facturer ou la refuser.");
    }

    $client = $devis->client;
    $banque = $devis->banque;
    $designations = $devis->details;
    
    return view('administration.pages.factures.totale.create', compact('client', 'banque', 'designations', 'devis', 'facture'));
}

    public function createPartielle($id)
    {
        $devis = Devis::with('client', 'banque', 'details.designation')->findOrFail($id);

        $facture = Facture::where('devis_id', $devis->id)->first();

      
        
        
        // if ($devis->status === 'Réfusé') {
        //     return redirect()->back()->with('error', "Cette proforma a déjà été refusée.");
        // }


        // if ($factures && $factures->status === 'Facturé') {
        //     return redirect()->back()->with('error', "Cette proforma a déjà l'objet de facture.<br> Vous pouvez la facturé ou la réfusée.");
        // }

        // Vérifier si la facture existe ET a un statut "Facturé" ET un type "Totale"
        if ($facture && $facture->status === 'Facturé' && $facture->type === 'Totale') {
            return redirect()->back()->with('error', "Cette proforma a déjà fait l'objet de facture.<br> Vous pouvez la facturer ou la refuser.");
        }


        
        $client = $devis->client;
        $banque = $devis->banque;
        $designations = $devis->details; // Dépend de ta relation avec DevisDetail
        
        return view('administration.pages.factures.partielle.create', compact('client', 'banque', 'designations', 'devis', 'facture'));
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
//     try {
//         $validated = $request->validate([
//             'devis_id' => 'required|exists:devis,id',
//             'banque_id' => 'required|exists:banques,id',
//             'client_id' => 'required|exists:clients,id',
//             'num_bc' => 'required',
//             'num_rap' => 'nullable|string',
//             'num_bl' => 'nullable|string',
//             'remise_speciale' => 'required|string',
//             'type_facture' => 'required|in:Totale,Partielle',
//             'montant' => $request->type_facture === 'Partielle' ? 'required|numeric|min:0' : 'nullable',
//             'selected_items' => $request->type_facture === 'Partielle' ? 'required|array' : 'nullable',
//             'selected_items.*' => $request->type_facture === 'Partielle' ? 'exists:devis_details,id' : 'nullable',
//         ]);

//         $devis = Devis::findOrFail($validated['devis_id']);
//         $client = Client::findOrFail($validated['client_id']);
//         $banque = Banque::findOrFail($validated['banque_id']);

//         // Récupération sécurisée des éléments sélectionnés
//         $selectedItems = Arr::get($validated, 'selected_items', []);

//         // Vérifier que les éléments sélectionnés appartiennent bien au devis
//         $invalidItems = array_diff($selectedItems, $this->getValidItemIds($validated['devis_id']));
//         if (count($invalidItems) > 0) {
//             return back()->withErrors(['selected_items' => 'Certains éléments sélectionnés ne sont pas valides']);
//         }

//         // Calcul du montant HT
//         $montantHT = DevisDetail::whereIn('id', $selectedItems)->sum('total');
//         $montantTTC = $montantHT * (1 + ($devis->tva / 100));

//         // if (abs($montantTTC - Arr::get($validated, 'montant', 0)) > 0.01) {
//         //     return back()->withErrors(['montant' => 'Le montant ne correspond pas aux éléments sélectionnés']);
//         // }
//         // if (abs($montantTTC - Arr::get($validated, 'montant', 0)) > 0.01) {
//         //     return back()->withErrors(['montant' => 'Le montant ne correspond pas aux éléments sélectionnés']);
//         // }
        

//         // Vérifier si une facture existe déjà
//         $existingFacture = Facture::where('devis_id', $devis->id)->first();

//         if ($validated['type_facture'] === 'Partielle') {
//             // Vérification du cumul des factures partielles
//             $cumulFactures = Facture::where('devis_id', $devis->id)
//                 ->where('type_facture', 'Partielle')
//                 ->sum('montant');

//             $cumulTotal = $cumulFactures + $montantTTC;

//             if ($cumulTotal > $devis->total_ttc) {
//                 return redirect()->back()->with('error', "Le cumul des montants partiels dépasse le montant total du devis.")->withInput();
//             }
//         } else {
//             // Pour une facture totale
//             $montantHT = $devis->total_ht;
//             $montantTTC = $devis->total_ttc;
//         }

//         // Mise à jour du statut du devis
//         $devis->status = 'En Attente du Daf';
//         $devis->save();

//         // Génération du numéro personnalisé
//         $customNumber = $this->generateCustomNumber();

//         // Création de la facture
//         $facture = new Facture();
//         $facture->devis_id = $validated['devis_id'];
//         $facture->num_bc = $validated['num_bc'];
//         $facture->num_rap = $validated['num_rap'];
//         $facture->num_bl = $validated['num_bl'];
//         $facture->user_id = Auth::id();
//         $facture->remise_speciale = $validated['remise_speciale'];
//         $facture->numero = $customNumber;
//         $facture->pays_id = Auth::user()->pays_id;
//         $facture->status = 'En Attente du Daf';
//         $facture->type_facture = $validated['type_facture'];
//         $facture->montant = $montantTTC;
        

//         if ($validated['type_facture'] === 'Partielle') {
//             $facture->selected_items = json_encode($selectedItems);
//         }

        

//         $facture->save();

//         // Envoi de la notification
//         Notification::send($facture->devis->user, new FactureCreatedNotification($facture));

//         // Génération du PDF
//         $pdfData = [
//             'devis' => $devis,
//             'client' => $client,
//             'banque' => $banque,
//             'facture' => $facture,
//             'selectedItems' => $selectedItems
//         ];

//         $pdf = PDF::loadView('frontend.pdf.facture', $pdfData);
//         $pdfOutput = $pdf->output();
//         $imagePath = 'pdf/factures/facture-' . $facture->id . '.pdf';

//         Storage::disk('public')->put($imagePath, $pdfOutput);
//         $facture->pdf_path = $imagePath;
//         $facture->save();

//         // Notification aux utilisateurs Daf/DG
//         if (Auth::user()->hasRole(['DG', 'Daf'])) {
//             $this->approuve($facture->id);
//         }

//         $route = $validated['type_facture'] === 'Totale' 
//             ? 'dashboard.factures.totales.index' 
//             : 'dashboard.factures.partielles.index';

//         return redirect()->route($route)
//             ->with('pdf_path', $imagePath)
//             ->with('success', 'Facture enregistrée avec succès.');

//     } catch (\Illuminate\Validation\ValidationException $e) {
//         return redirect()->back()->withErrors($e->errors())->withInput();
//     } catch (\Exception $e) {
//         Log::error('Erreur lors de l\'enregistrement de la facture : ' . $e->getMessage(), [
//             'exception' => $e
//         ]);

//         return redirect()->back()
//             ->with('error', 'Une erreur est survenue lors de l\'enregistrement de la facture.')
//             ->withInput();
//     }
// }


public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'devis_id' => 'required|exists:devis,id',
            'banque_id' => 'required|exists:banques,id',
            'client_id' => 'required|exists:clients,id',
            'num_bc' => 'required',
            'num_rap' => 'nullable|string',
            'num_bl' => 'nullable|string',
            'remise_speciale' => 'required|string',
            'type_facture' => 'required|in:Totale,Partielle',
            'montant' => $request->type_facture === 'Partielle' ? 'required|numeric|min:0' : 'nullable',
            'selected_items' => $request->type_facture === 'Partielle' ? 'required|array' : 'nullable',
            'selected_items.*' => $request->type_facture === 'Partielle' ? 'exists:devis_details,id' : 'nullable',
        ]);

        $devis = Devis::findOrFail($validated['devis_id']);
        $client = Client::findOrFail($validated['client_id']);
        $banque = Banque::findOrFail($validated['banque_id']);

        // Récupération sécurisée des éléments sélectionnés
        $selectedItems = Arr::get($validated, 'selected_items', []);

        // Vérifier que les éléments sélectionnés appartiennent bien au devis
        $invalidItems = array_diff($selectedItems, $this->getValidItemIds($validated['devis_id']));
        if (count($invalidItems) > 0) {
            return back()->withErrors(['selected_items' => 'Certains éléments sélectionnés ne sont pas valides']);
        }

        // Calcul du montant HT
        $montantHT = DevisDetail::whereIn('id', $selectedItems)->sum('total');
        $montantTTC = $montantHT * (1 + ($devis->tva / 100));

        // Vérifier si une facture existe déjà pour ce devis
        $existingFacture = Facture::where('devis_id', $devis->id)->first();

        if ($existingFacture) {
            // Supprimer l'ancien fichier PDF s'il existe
            if ($existingFacture->pdf_path && Storage::disk('public')->exists($existingFacture->pdf_path)) {
                Storage::disk('public')->delete($existingFacture->pdf_path);
            }

            // Mettre à jour la facture existante
            $facture = $existingFacture;
        } else {
            // Créer une nouvelle facture
            $facture = new Facture();
        }

        if ($validated['type_facture'] === 'Partielle') {
            // Vérification du cumul des factures partielles
            $cumulFactures = Facture::where('devis_id', $devis->id)
                ->where('type_facture', 'Partielle')
                ->where('id', '!=', $facture->id ?? null) // Exclure la facture actuelle si elle existe
                ->sum('montant');

            $cumulTotal = $cumulFactures + $montantTTC;

            if ($cumulTotal > $devis->total_ttc) {
                return redirect()->back()->with('error', "Le cumul des montants partiels dépasse le montant total du devis.")->withInput();
            }
        } else {
            // Pour une facture totale
            $montantHT = $devis->total_ht;
            $montantTTC = $devis->total_ttc;
        }

        // Mise à jour du statut du devis
        $devis->status = 'En Attente du Daf';
        $devis->save();

        // Génération du numéro personnalisé (seulement pour les nouvelles factures)
        if (!$existingFacture) {
            $customNumber = $this->generateCustomNumber();
            $facture->numero = $customNumber;
        }

        // Mise à jour des attributs de la facture
        $facture->devis_id = $validated['devis_id'];
        $facture->num_bc = $validated['num_bc'];
        $facture->num_rap = $validated['num_rap'];
        $facture->num_bl = $validated['num_bl'];
        $facture->user_id = Auth::id();
        $facture->remise_speciale = $validated['remise_speciale'];
        $facture->pays_id = Auth::user()->pays_id;
        $facture->status = 'En Attente du Daf';
        $facture->type_facture = $validated['type_facture'];
        $facture->montant = $montantTTC;

        if ($validated['type_facture'] === 'Partielle') {
            $facture->selected_items = json_encode($selectedItems);
        }

        $facture->save();

        // Envoi de la notification seulement pour les nouvelles factures
        if (!$existingFacture) {
            Notification::send($facture->devis->user, new FactureCreatedNotification($facture));
        }

        // Génération du PDF
        $pdfData = [
            'devis' => $devis,
            'client' => $client,
            'banque' => $banque,
            'facture' => $facture,
            'selectedItems' => $selectedItems
        ];

        $pdf = PDF::loadView('frontend.pdf.facture', $pdfData);
        $pdfOutput = $pdf->output();
        $imagePath = 'pdf/factures/facture-' . $facture->id . '.pdf';

        Storage::disk('public')->put($imagePath, $pdfOutput);
        $facture->pdf_path = $imagePath;
        $facture->save();

        // Notification aux utilisateurs Daf/DG
        if (Auth::user()->hasRole(['DG', 'Daf'])) {
            $this->approuve($facture->id);
        }

        $route = $validated['type_facture'] === 'Totale' 
            ? 'dashboard.factures.totales.index' 
            : 'dashboard.factures.partielles.index';

        return redirect()->route($route)
            ->with('pdf_path', $imagePath)
            ->with('success', $existingFacture ? 'Facture mise à jour avec succès.' : 'Facture enregistrée avec succès.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'enregistrement de la facture : ' . $e->getMessage(), [
            'exception' => $e
        ]);

        return redirect()->back()
            ->with('error', 'Une erreur est survenue lors de l\'enregistrement de la facture.')
            ->withInput();
    }
}

    private function getValidItemIds($devisId)
    {
        return DevisDetail::where('devis_id', $devisId)->pluck('id')->toArray();
    }




public function approuve($id)
{
    try {
        // Charger la facture avec toutes les relations nécessaires
        $facture = Facture::findOrFail($id);
        
        // Validation du statut
        if ($facture->devis->status !== 'En Attente du Daf') {
            return redirect()->back()->with('error', 'La facture a déjà été traitée. Veuillez consulter le message et faire les corrections si nécessaire.');
        }

        if ($facture->devis->status === 'Réfusé') {
            return redirect()->back()->with('error', 'La facture a été refusée');
        }

        // Vérification que le client existe et a un email
        // if (!$facture->devis->client || !$facture->devis->client->email) {
        //     Log::error('Client ou email manquant pour la facture', [
        //         'facture_id' => $facture->id,
        //         'client_id' => $facture->client_id
        //     ]);
        //     return redirect()->back()->with('error', 'Le client associé à cette facture est invalide ou n\'a pas d\'email enregistré.');
        // }
        

        // Vérification du PDF
        $pdfPath = storage_path('app/public/' . $facture->pdf_path);
        if (!file_exists($pdfPath)) {
            Log::error('Fichier PDF manquant pour la facture', [
                'facture_id' => $facture->id,
                'path' => $pdfPath
            ]);
            return redirect()->back()->with('error', 'Le fichier PDF est introuvable.');
        }

        // Envoi de l'email
        // Mail::to($facture->devis->client->email)
        //     ->send(new FactureApprovalMail(
        //         $facture, 
        //         $pdfPath, 
        //         $facture->user->name, 
        //         $facture->devis->client->email, 
        //         $facture->devis->client->nom
        //     ));

        // Mise à jour du statut (décommenter quand tout fonctionne)
        $facture->devis->update(['status' => 'Facturé']);
        $facture->update(['status' => 'Facturé']);

        // Notifications (décommenter quand tout fonctionne)
        $facture->user->notify(new FactureApprovedNotification($facture));
        if ($facture->devis->user_id !== $facture->user_id) {
            $facture->devis->user->notify(new FactureApprovedNotification($facture));
        }

        // Log de l'action (décommenter quand tout fonctionne)
        // ActivityLog::create([
        //     'user_id' => Auth::id(),
        //     'action' => 'approval',
        //     'model_type' => Facture::class,
        //     'model_id' => $facture->id,
        //     'description' => 'Facture approuvée et envoyée au client'
        // ]);

        return redirect()->back()->with('success', 'Facture approuvée avec succès.');

    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'approbation de la facture', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'facture_id' => $id
        ]);
        return redirect()->back()->with('error', 'Une erreur est survenue lors du traitement de la facture: '.$e->getMessage());
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
    // Données à exporter
    $factures = Facture::with(['devis.client', 'user', 'devis.details'])->get();

    // Créer un fichier CSV
    $csvFileName = 'factures_export_' . date('Y-m-d_H-i-s') . '.csv';
    $csvFile = fopen('php://temp', 'w');  // Créer un flux temporaire pour écrire dans le fichier

    // Ajouter l'entête du CSV
    fputcsv($csvFile, [
        'Date de Création',
        'Client',
        'Montant Total',
        'Devise',
        'Établi Par',
        'Statut'
    ]);

    // Remplir les données
    $totalCost = 0;
    foreach ($factures as $facture) {
        $clientName = $facture->devis->client->nom ?? 'Client inconnu';
        $userName = $facture->user->name ?? 'Utilisateur inconnu';
        $cost = $facture->devis->details->sum('total');
        $totalCost += $cost;
        $devise = $facture->devis->devise ?? 'USD';

        fputcsv($csvFile, [
            $facture->created_at->format('d/m/Y H:i:s'),
            $clientName,
            number_format($cost, 2, ',', ' '),
            $devise,
            $userName,
            $facture->devis->status ?? 'Non renseigné'
        ]);
    }

    // Ajouter la ligne du total
    fputcsv($csvFile, [
        'Total',
        '',
        number_format($totalCost, 2, ',', ' ')
    ]);

    // Rewind pour être sûr de lire depuis le début du fichier temporaire
    rewind($csvFile);

    // Retourner le fichier CSV en téléchargement
    return response(stream_get_contents($csvFile), 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
    ]); // Assurez-vous de supprimer le fichier après l'envoi
}



public function updateSolde(Request $request, Facture $facture)
{
    $request->validate([
        'montant_solde' => 'required|numeric|min:0',
    ]);

    $facture->montant_solde = $request->montant_solde;
    $facture->save();

    return redirect()->back()->with('success', 'Montant soldé mis à jour avec succès.');
}

    


    

}
