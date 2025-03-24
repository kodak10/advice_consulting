<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Mail\FactureMail;
use App\Models\Banque;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
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

    
    public function index(Request $request)
    {
        $payss = Pays::get();
        $user = Auth::user();

        $all_devis = Devis::whereIn('status', ['Facturé', 'En Attente du Daf'])->get();

        $devis_pays = Devis::where('pays_id', Auth::user()->pays_id)
        ->where('status',  'Facturé')
        ->get();

        $facturesQuery = Facture::query();

        $facturesQuery->whereHas('devis', function ($query) {
            $query->where('status', 'Soldé');
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

        return view('administration.pages.factures.index', compact('all_devis', 'devis_pays', 'all_factures', 'factureCommercials', 'payss'));

    } 

    // public function refuse($id, Request $request)
    // {
    //     $devis = Devis::findOrFail($id);
    
    //     $validated = $request->validate([
    //         'message' => 'required|string|min:10', // Minimum 10 caractères pour le message
    //     ]);

    //     if ($devis->status !== 'Facturé') {
    //         return redirect()->back()->with('error', 'Vous ne pouvez supprimer cette Proforma que si son statut est "Facturé".');
    //     }
    
    //     $creator = $devis->user;  // L'utilisateur qui a créé le devis (ou tout autre destinataire)
        
    //     // Envoi de la notification par broadcast
    //     // Vous pouvez utiliser une notification broadcast pour l'interface en temps réel
    //    Notification::send($creator, new DevisRefusedNotification($devis));
    
       
    //     // Changer le statut du devis à "Refusé"
    //     $devis->status = 'Réfusé';
    //     $devis->message = $validated['message'];

    //     $devis->save();

    //     return redirect()->back()->with('success', 'Proforma Réfusée avec succès.');
    // }

    public function refuse($id, Request $request)
{
    $factures = Facture::findOrFail($id);

    $validated = $request->validate([
        'message' => 'required|string|min:10', 
    ]);

    // Vérification du statut de la facture avant la mise à jour
    if ($factures->status === "Soldé") {
        return redirect()->route('dashboard.factures.index')->with('error', 'Vous ne pouvez refuser cette Facture que si son statut est "Facturé ou en Attente du Daf"');
    }

    // Changer le statut du devis à "Réfusé"
    $factures->status = 'Réfusé';
    $factures->devis->status = 'Facturé';

    $factures->message = $validated['message'];
    $factures->save();
    $factures->devis->save();

    // Notification de refus envoyée à l'utilisateur créateur
    $creator = $factures->user;  // L'utilisateur qui a créé la facture
    Notification::send($creator, new FactureRefusedNotification($factures));

    // Retourner à la page de liste avec un message de succès
    return redirect()->route('dashboard.factures.index')->with('success', 'Proforma Réfusée avec succès.');
}


    public function create($id)
    {
        $devis = Devis::with('client', 'banque', 'details.designation')->findOrFail($id);

        $factures = Facture::where('devis_id', $devis->id)->first();

      
        
        
        // if ($devis->status === 'Réfusé') {
        //     return redirect()->back()->with('error', "Cette proforma a déjà été refusée.");
        // }


        if ($factures && $factures->status === 'Soldé') {
            return redirect()->back()->with('error', "Cette proforma a déjà l'objet de facture.<br> Vous pouvez la facturé ou la réfusée.");
        }
        
        $client = $devis->client;
        $banque = $devis->banque;
        $designations = $devis->details; // Dépend de ta relation avec DevisDetail
        
        return view('administration.pages.factures.create', compact('client', 'banque', 'designations', 'devis', 'factures'));
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
        try {
            $validated = $request->validate([
                'devis_id' => 'required|exists:devis,id',
                'banque_id' => 'required|exists:banques,id',
                'client_id' => 'required|exists:clients,id',
                'num_bc' => 'required|string',
                'num_rap' => 'required|string',
                'num_bl' => 'required|string',
                'remise_speciale' => 'required|string',
            ]);



            $devis = Devis::findOrFail($validated['devis_id']);
            $client = Client::findOrFail($validated['client_id']);
            $banque = Banque::findOrFail($validated['banque_id']);


            $facture = Facture::where('devis_id', $devis->id)->first();

            if ($facture) {
                if ($facture->status === 'En Attente du Daf' || $facture->status === 'Soldé') {
                    return redirect()->back()->with('error', "Cette proforma a déjà une facture en cours ou soldée.");
                }
            }
            


            $devis->status = 'En Attente du Daf';
            $devis->save();

            $customNumber = $this->generateCustomNumber();

            $facture = new Facture();
            $facture->devis_id = $validated['devis_id'];
            $facture->num_bc = $validated['num_bc'];
            $facture->num_rap = $validated['num_rap'];
            $facture->num_bl = $validated['num_bl'];
            $facture->user_id = Auth::id();
            $facture->remise_speciale = $validated['remise_speciale'];
            $facture->numero = $customNumber;
            $facture->pays_id = Auth::user()->pays_id;
            $facture->status = 'En Attente du Daf';

            $facture->save();

            $creator = $facture->devis->user;
            Notification::send($creator, new FactureCreatedNotification($facture));

            // Génération du PDF
            $pdf = PDF::loadView('frontend.pdf.facture', compact('devis', 'client', 'banque', 'facture'));
            $pdfOutput = $pdf->output();

            $imageName = 'facture-' . $facture->id . '.pdf';
            $directory = 'pdf/factures';

            // Vérifier et créer le dossier si nécessaire
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $imagePath = $directory . '/' . $imageName;
            Storage::disk('public')->put($imagePath, $pdfOutput);

            $facture->pdf_path = $imagePath;
            $facture->save();


            $daf = User::role(['DG', 'Daf'])->get(); // Récupère tous les utilisateurs ayant le rôle "Daf" et DG

            // Vérifie si des utilisateurs Daf existent
            if ($daf->count() > 0) {
                Log::info('Envoi de la notification de facture pour approbation.', [
                    'facture_id' => $facture->id,
                    'comptables_count' => $daf->count(),
                    'facture_num' => $facture->numero,
                ]);

                // Envoie la notification
                // Notification::send($daf, new FactureApprovalNotification($facture));

                // Log après envoi
                Log::info('Notification envoyée avec succès aux utilisateurs Daf.', [
                    'facture_id' => $facture->id,
                    'daf' => $daf->pluck('name')->toArray(), // Les noms des utilisateurs Daf
                ]);
            } else {
                Log::warning('Aucun utilisateur Daf ou DG trouvé pour la notification.', [
                    'facture_id' => $facture->id,
                ]);
            }

            if(Auth::user()->hasRole(['DG', 'Daf'])){
                $this->approuve($facture->id);

            }

    

            // Télécharger le fichier PDF
            return redirect()->route('dashboard.factures.index')
            ->with('pdf_path', $imagePath)
            ->with('success', 'Facture enregsitré avec succès.');



            // return redirect()->route('dashboard.factures.index')->with('success', 'Facture enregistrée avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation échouée', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de la facture', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la facture.')->withInput();
        }
    }


    
    // public function approuve($id)
    // {
    //     // Log: Début du traitement
    //     Log::info('Début de la validation de la facture', ['facture_id' => $id]);
    
    //     $facture = Facture::findOrFail($id);
    
    //     if ($facture->devis->status !== 'En Attente du Daf') {            
    //         return redirect()->back()->with('error', 'La facture à déjà été validée');
    //     }

    //     if ($facture->devis->status === 'Réfusé') {            
    //         return redirect()->back()->with('error', 'La facture à été réfusé');
    //     }
    
    //     // Vérifier si le PDF existe
    //     $pdfPath = storage_path('app/public/' . $facture->pdf_path);
    //     if (!file_exists($pdfPath)) {
    //         // Log: Erreur, le fichier PDF n'existe pas
    //         Log::error('Le fichier PDF n\'existe pas', ['pdf_path' => $pdfPath]);
            
    //         return redirect()->back()->with('error', 'Le fichier PDF n\'existe pas.');
    //     }
    
    //     // Log: PDF trouvé
    //     Log::info('Le fichier PDF existe', ['pdf_path' => $pdfPath]);
    
    //     // Récupérer l'utilisateur ayant créé la facture
    //     $creator = $facture->user;
    //     $userCreator =$facture->devis->user;
    //     Log::info('Utilisateur ayant créé la facture', ['user_id' => $creator->id, 'user_name' => $creator->name]);
    
    //     // Modifier le statut de la facture
    //     $facture->devis->status = 'Soldé';
    //     $facture->devis->save();
    //     $facture->status = 'Soldé';
    //     $facture->save();
    //     // Log: Statut mis à jour
    //     Log::info('Statut du devis mis à jour', ['facture_id' => $id, 'new_status' => 'Soldé']);
    
    //     // Envoyer la notification via base de données
    //     $creator->notify(new FactureApprovedNotification($facture));
    //     $userCreator->notify(new FactureApprovedNotification($facture));

    //     // Log: Notification envoyée
    //     Log::info('Notification envoyée à l\'utilisateur', ['user_id' => $creator->id]);
    
    //     return redirect()->back()->with('success', 'Facture Approuvée avec succès.');
    // }

    public function approuve($id)
{
    // Log: Début du traitement
    Log::info('Début de la validation de la facture', ['facture_id' => $id]);

    $facture = Facture::findOrFail($id);

    if ($facture->devis->status !== 'En Attente du Daf') {
        return redirect()->back()->with('error', 'La facture a déjà été validée');
    }

    if ($facture->devis->status === 'Réfusé') {
        return redirect()->back()->with('error', 'La facture a été refusée');
    }

    // Vérifier si le PDF existe
    $pdfPath = storage_path('app/public/' . $facture->pdf_path);
    if (!file_exists($pdfPath)) {
        // Log: Erreur, le fichier PDF n'existe pas
        Log::error('Le fichier PDF n\'existe pas', ['pdf_path' => $pdfPath]);

        return redirect()->back()->with('error', 'Le fichier PDF n\'existe pas.');
    }

    // Log: PDF trouvé
    Log::info('Le fichier PDF existe', ['pdf_path' => $pdfPath]);

    // Récupérer l'utilisateur ayant créé la facture
    $creator = $facture->user;
    $userCreator = $facture->devis->user;
    Log::info('Utilisateur ayant créé la facture', ['user_id' => $creator->id, 'user_name' => $creator->name]);

    // Modifier le statut de la facture
    $facture->devis->status = 'Soldé';
    $facture->devis->save();
    $facture->status = 'Soldé';
    $facture->save();
    // Log: Statut mis à jour
    Log::info('Statut du devis mis à jour', ['facture_id' => $id, 'new_status' => 'Soldé']);

    // Récupérer les emails du client
    $client = $facture->devis->client;
    $clientEmails = [];

    // Ajouter les emails du client (email_01 et email_02)
    if (!empty($client->email)) {
        $clientEmails[] = $client->email;
    }
    
    // Ajouter les emails fixes (comptables et directeur)
    $ccEmails = ['comptable@example.com', 'directeur@example.com']; // Remplacez par les emails réels

    // Fusionner les emails du client et ceux fixes
    $allEmails = array_merge($clientEmails, $ccEmails);

    // Récupérer l'email de l'utilisateur connecté
    $userEmail = Auth::user()->email;
    $userName = Auth::user()->name;

    // Envoyer la facture par email avec l'email de l'utilisateur connecté comme expéditeur
    // Mail::to($allEmails)
    //     ->from($userEmail, $userName) // Utiliser l'email de l'utilisateur connecté
    //     ->send(new FactureMail($facture->devis, $pdfPath, $userName));

    // Envoyer la notification via base de données
    $creator->notify(new FactureApprovedNotification($facture));
    $userCreator->notify(new FactureApprovedNotification($facture));

    // Log: Notification envoyée
    Log::info('Notification envoyée à l\'utilisateur', ['user_id' => $creator->id]);

    return redirect()->back()->with('success', 'Facture Approuvée avec succès et envoyée par email.');
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


    


    

}
