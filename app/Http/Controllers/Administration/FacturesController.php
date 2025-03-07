<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Mail\DevisRefusedMail;
use App\Mail\FactureMail;
use App\Models\Banque;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Pays;
use App\Models\User;
use App\Notifications\FactureApprovalNotification;

use Barryvdh\DomPDF\Facade\Pdf;
use DevisRefusedNotification;
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
            if (Auth::check() && Auth::user()->hasRole('Comptable')) {
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

        $all_devis = Devis::whereIn('status', ['Approuvé', 'En Attente du Daf'])->get();

        $devis_pays = Devis::where('pays_id', Auth::user()->pays_id)
        ->where('status',  'Approuvé')
        ->get();

        $facturesQuery = Facture::query();

        $facturesQuery->whereHas('devis', function ($query) {
            $query->where('status', 'Terminé');
        });

        // Filtre par pays (uniquement pour les Dafs)
        if ($user->hasRole('Daf') && $request->has('pays') && $request->pays != "") {
            $facturesQuery->where('pays_id', $request->pays);
        } else {
            $facturesQuery->where('pays_id', $user->pays_id); // Restreindre aux factures du pays de l'utilisateur
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

    public function refuse($id)
    {
        $devis = Devis::findOrFail($id);
    
        if ($devis->status !== 'Approuvé') {
            return redirect()->back()->with('error', 'Vous ne pouvez supprimer cette Proforma que si son statut est "Approuvé".');
        }
    
        // Changer le statut du devis à "Refusé"
        $devis->status = 'Réfusé';
        $devis->save();
    
        // Envoi de la notification par email
        $creator = $devis->user;  // L'utilisateur qui a créé le devis (ou tout autre destinataire)
        Mail::to($creator->email)->send(new DevisRefusedMail($devis));
    
        // Envoi de la notification par broadcast
        // Vous pouvez utiliser une notification broadcast pour l'interface en temps réel
        Notification::send($creator, new DevisRefusedNotification($devis));
    
        return redirect()->back()->with('success', 'Proforma Réfusée avec succès.');
    }

    public function create($id)
    {
        $devis = Devis::with('client', 'banque', 'details.designation')->findOrFail($id);

        if ($devis->status === 'Terminé') {
            return redirect()->back()->with('error', "Cette proforma a déjà fait l'objet d'une facture.");
        }
        
        if ($devis->status === 'Réfusé') {
            return redirect()->back()->with('error', "Cette proforma a déjà été refusée.");
        }

        if ($devis->status === 'En Attente du Daf') {
            return redirect()->back()->with('error', "Cette proforma a déjà l'objet de facture.<br> Vous pouvez l'approuvé ou la réfusée");
        }
        
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

            $facture->save();

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


            $daf = User::role('Daf')->get(); // Récupère tous les utilisateurs ayant le rôle "Daf"

            // Vérifie si des utilisateurs Daf existent
            if ($daf->count() > 0) {
                Log::info('Envoi de la notification de facture pour approbation.', [
                    'facture_id' => $facture->id,
                    'comptables_count' => $daf->count(),
                    'facture_num' => $facture->numero,
                ]);

                // Envoie la notification
                Notification::send($daf, new FactureApprovalNotification($facture));

                // Log après envoi
                Log::info('Notification envoyée avec succès aux utilisateurs Daf.', [
                    'facture_id' => $facture->id,
                    'daf' => $daf->pluck('name')->toArray(), // Les noms des utilisateurs Daf
                ]);
            } else {
                Log::warning('Aucun utilisateur Daf trouvé pour la notification.', [
                    'facture_id' => $facture->id,
                ]);
            }

            if(Auth::user()->hasRole('Daf')){
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


    public function approuve($id)
{
    $facture = Facture::findOrFail($id);

    // dd($facture);
    if ($facture->devis->status !== 'En Attente du Daf') {
        return redirect()->back()->with('error', 'La facture à déjà été validé');
    }

    // Récupérer l'utilisateur qui a créé le devis
    $creator = $facture->devis->user; 
    $creatorEmail = $creator->email; // ✅ Récupérer son email
    $creatorName = $creator->name; // ✅ Récupérer son nom

    // Vérifier si le PDF existe et récupérer le chemin
    $pdfPath = storage_path('app/public/' . $facture->pdf_path);

    if (!file_exists($pdfPath)) {
        return redirect()->back()->with('error', 'Le fichier PDF n\'existe pas.');
    }

    // Récupérer l'email du client
    $clientEmail = $facture->devis->client->email;

    // Envoyer l'e-mail au client avec le fichier PDF en pièce jointe
    //Mail::send(new FactureMail($facture, $pdfPath, $creatorEmail, $creatorName, $clientEmail));
    
    $facture->devis->status = 'Terminé';
    $facture->devis->save();

    return redirect()->back()->with('success', 'Facture envoyée avec succès au client.');
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
        'Coût Total',
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
