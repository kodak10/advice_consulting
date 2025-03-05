<?php

namespace App\Http\Controllers\Administration;

use App\Events\DevisCreated;
use App\Events\TestEvent;
use App\Http\Controllers\Controller;
use App\Mail\DevisApprovalMail;
use App\Models\Banque;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
use App\Models\DevisDetail;
use App\Models\Devise;
use App\Models\User;
use App\Notifications\DevisCreatedNotification;
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


class DevisController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Comptable|Commercial')->except('download', 'exportCsv');
    }

    public function getDeviseRate($deviseCode)
    {
        $devise = Devise::where('code', $deviseCode)->first();

        if ($devise) {
            return response()->json(['taux_conversion' => $devise->taux_conversion]);
        }

        return response()->json(['error' => 'Devise non trouvée'], 404);
    }

    
    public function index()
    {
        $devis = Devis::where('pays_id', Auth::user()->pays_id)
        ->where('status', 'Approuvé')
        ->get();

        $mes_devis = Devis::where('pays_id', Auth::user()->pays_id)
        ->where('user_id', Auth::user()->id)
        ->get();
        

        return view('administration.pages.devis.index', compact('devis', 'mes_devis'));

    } 

    public function create()
    {
        $clients = Client::all();
        $banques = Banque::all();
        $designations = Designation::all();
        $devises = Devise::all();
        return view('administration.pages.devis.create', compact('clients','designations','banques', 'devises'));

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

    public function approuve($id)
    {
        $devis = Devis::findOrFail($id);
        $creator = $devis->user_id;

        $comptables = User::role('Comptable')
        ->where('pays_id', Auth::user()->pays_id)
        ->where('id', '!=', $creator)
        ->get();


        if ($devis->status !== 'En Attente') {
            return redirect()->back()->with('error', 'La Proforma ne peut être approuvé que s\'il est en attente.');
        }

        // Vérifier si le PDF existe et récupérer le chemin
        $pdfPath = storage_path('app/public/' . $devis->pdf_path);


        if (!file_exists($pdfPath)) {
            return redirect()->back()->with('error', 'Le fichier PDF n\'existe pas.');
        }

        // Récupérer l'email du client
        $clientEmail = $devis->client->email;

        // Envoyer l'e-mail au client avec le fichier PDF en pièce jointe
        Mail::send(new DevisApprovalMail($devis, $pdfPath, Auth::user()->name, $clientEmail));



        Notification::send($comptables, new DevisCreatedNotification($devis));


        $devis->status = 'Approuvé';
        $devis->save();

        




        return redirect()->back()->with('success', 'Proforma approuvée avec succès.');
    }


    public function recap(Request $request)
    {
        
        // Valider les données du formulaire
        $validated = $request->validate([
            'devise' => 'required|string',

            'client_id' => 'required|exists:clients,id',  
            'date_emission' => 'required|date',  
            'date_echeance' => 'required|date|after_or_equal:date_emission',  
            
            'commande' => 'required|string',  
            'livraison' => 'required|string',  
            'validite' => 'required|string',  
            'banque_id' => 'required|exists:banques,id',  

            'total-ht' => 'required|numeric|min:0',  
            'tva' => 'required',  
            'total-ttc' => 'required|numeric|min:0',  
            'acompte' => 'required|numeric|min:0',  
            'solde' => 'required|numeric|min:0',  

            'delai' => 'required',
           
            'designations' => 'required|array', 
            'designations.*.id' => 'required',
            'designations.*.description' => 'required',
            'designations.*.quantity' => 'required|numeric|min:1',
            'designations.*.price' => 'required|numeric|min:0', 
            'designations.*.discount' => 'nullable|numeric|min:0', 
            'designations.*.total' => 'required|numeric|min:0', 
        ]);

        $designations = Designation::all();  

        $client = Client::find($validated['client_id']);
        $banque = Banque::find($validated['banque_id']);

        return view('administration.pages.devis.recap', compact('client', 'validated', 'banque', 'designations'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'banque_id' => 'required|exists:banques,id',

                'date_emission' => 'required|date',
                'date_echeance' => 'required|date|after_or_equal:date_emission',
                
                'commande' => 'required|string',
                'livraison' => 'required|string',
                'validite' => 'required|string',
                'delai' => 'required',

                'total-ht' => 'required|numeric|min:0',
                'total-ttc' => 'required|numeric|min:0',
                'acompte' => 'required|numeric|min:0',
                'solde' => 'required|numeric|min:0',
                'tva' => 'required',  

                'designations' => 'required|array',
                'designations.*.id' => 'required|exists:designations,id',
                'designations.*.description' => 'required',
                'designations.*.quantity' => 'required|numeric|min:1',
                'designations.*.price' => 'required|numeric|min:0',
                'designations.*.discount' => 'nullable|numeric|min:0',
                'designations.*.total' => 'required|numeric|min:0',

                'devise' => 'required|string',

            ]);

            $numProforma = $this->generateNumProforma();

            $client = Client::find($validated['client_id']);
            $banque = Banque::find($validated['banque_id']);

            $devis = new Devis();
            $devis->client_id = $validated['client_id'];
            $devis->date_emission = $validated['date_emission'];
            $devis->date_echeance = $validated['date_echeance'];
            $devis->commande = $validated['commande'];
            $devis->livraison = $validated['livraison'];
            $devis->validite = $validated['validite'];
            $devis->banque_id = $validated['banque_id'];
            $devis->total_ht = $validated['total-ht'];
            $devis->tva = $validated['tva'];
            $devis->total_ttc = $validated['total-ttc'];
            $devis->acompte = $validated['acompte'];
            $devis->solde = $validated['solde'];
            $devis->delai = $validated['delai'];
            $devis->user_id = Auth::user()->id;
            $devis->num_proforma = $numProforma;
            $devis->status = "En Attente";
            $devis->pays_id = Auth::user()->pays_id;
            $devis->devise = $validated['devise'];

            $devis->save();

            // Enregistrer les détails du devis (DevisDetail)
            foreach ($validated['designations'] as $designationData) {
                $devisDetail = new DevisDetail();
                $devisDetail->devis_id = $devis->id;
                $devisDetail->designation_id = $designationData['id'];
                $devisDetail->quantite = $designationData['quantity'];
                $devisDetail->prix_unitaire = $designationData['price'];
                $devisDetail->remise = $designationData['discount'];
                $devisDetail->total = $designationData['total'];
                $devisDetail->save();
            }

            // Générer le PDF
            $pdf = PDF::loadView('frontend.pdf.devis2', compact('devis', 'client', 'banque'));
            $pdfOutput = $pdf->output();

            $imageName = 'devis-' . $devis->id . '.pdf';

            $directory = 'pdf/devis';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $imagePath = $directory . '/' . $imageName;
            Storage::disk('public')->put($imagePath, $pdfOutput);

            $devis->pdf_path = $imagePath;
            $devis->save();

            // Nettoyer la session
            $request->session()->forget([
                'client_id', 'date_emission', 'date_echeance', 'commande', 'livraison', 'validite',
                'banque_id', 'total_ht', 'tva', 'total_ttc', 'acompte', 'solde', 'designations'
            ]);

            return redirect()->route('dashboard.devis.index')
            ->with('pdf_path', $imagePath)
            ->with('success', 'Devis enregistré avec succès.');


        } catch (\Exception $e) {
            Log::error("Erreur lors de la génération ou de l'enregistrement du PDF: " . $e->getMessage());
            return back()->withErrors("Une erreur s'est produite lors de la génération du PDF. Veuillez réessayer.");
        }
    }
   
    public function edit($id)
    {
        $devis = Devis::findOrFail($id);
        $clients = Client::all(); 
        $banques = Banque::all(); 
        $designations = Designation::all();


        if ($devis->status !== 'En Attente') {
            return redirect()->back()->with('error', 'Vous ne pouvez modifier cette Proforma que si son statut est "en attente".');
        }

        return view('administration.pages.devis.edit', compact('devis','clients','banques', 'designations'));
    }

    public function recapUpdate(Request $request, $id)
    {
        // dd($request);
        $validated = $request->validate([
            'devise' => 'required|string',  

            'client_id' => 'required|exists:clients,id', 
            'banque_id' => 'required|exists:banques,id',  
 
            'date_emission' => 'required|date',  
            'date_echeance' => 'required|date|after_or_equal:date_emission',  

            'commande' => 'required|string',  
            'livraison' => 'required|string',  
            'validite' => 'required|string', 
            'delai' => 'required',

            'total-ht' => 'required|numeric|min:0',  
            'tva' => 'required',  
            'total-ttc' => 'required|numeric|min:0',  
            'acompte' => 'required|numeric|min:0',  
            'solde' => 'required|numeric|min:0',  
           
            'designations' => 'required|array', 
            'designations.*.id' => 'required',
            'designations.*.description' => 'required|',
            'designations.*.quantity' => 'required|numeric|min:1',
            'designations.*.price' => 'required|numeric|min:0', 
            'designations.*.discount' => 'nullable|numeric|min:0', 
            'designations.*.total' => 'required|numeric|min:0', 
        ]);

        $devis = Devis::findOrFail($id);

        $designations = Designation::all();

        $client = Client::find($validated['client_id']);
        $banque = Banque::find($validated['banque_id']);

        return view('administration.pages.devis.recap-update', compact('client', 'validated', 'banque', 'designations', 'devis'));
    }

    
    public function storeRecap(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',  
                'banque_id' => 'required|exists:banques,id',  

                'date_emission' => 'required|date',  
                'date_echeance' => 'required|date|after_or_equal:date_emission',  

                'commande' => 'required|string',  
                'livraison' => 'required|string',  
                'validite' => 'required|string',  
                'delai' => 'required',

                'total-ht' => 'required|numeric|min:0',  
                'tva' => 'required',  
                'total-ttc' => 'required|numeric|min:0',  
                'acompte' => 'required|numeric|min:0',  
                'solde' => 'required|numeric|min:0', 

                'designations' => 'required|array', 
                'designations.*.id' => 'required|exists:designations,id',
                'designations.*.description' => 'required|exists:designations,description', 
                'designations.*.quantity' => 'required|numeric|min:1',
                'designations.*.price' => 'required|numeric|min:0', 
                'designations.*.discount' => 'nullable|numeric|min:0', 
                'designations.*.total' => 'required|numeric|min:0', 

                'devise' => 'required|string',  
            ]);

            $devis = Devis::findOrFail($id);

            $devis->update([
                'client_id' => $validated['client_id'],
                'banque_id' => $validated['banque_id'],  
                'date_emission' => $validated['date_emission'],
                'date_echeance' => $validated['date_echeance'],
                'commande' => $validated['commande'],
                'livraison' => $validated['livraison'],
                'validite' => $validated['validite'],
                'delai' => $validated['delai'],
                'total_ht' => $validated['total-ht'],
                'tva' => $validated['tva'],
                'total_ttc' => $validated['total-ttc'],
                'acompte' => $validated['acompte'],
                'solde' => $validated['solde'],
                'devise' => $validated['devise'],
            ]);

            foreach ($validated['designations'] as $designationData) {
                DevisDetail::updateOrCreate(
                    ['devis_id' => $devis->id, 'designation_id' => $designationData['id']],
                    [
                        'quantite' => $designationData['quantity'],
                        'prix_unitaire' => $designationData['price'],
                        'remise' => $designationData['discount'],
                        'total' => $designationData['total'],
                    ]
                );
            }

            return redirect()->route('dashboard.devis.index')->with('success', 'Proforma mise à jour avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('dashboard.devis.index')
            ->withErrors($e->errors())  // Envoie les erreurs de validation
            ->withInput();

            // Si c'est une exception de validation, renvoyer les erreurs spécifiques
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Capturer toute autre exception générique et retourner un message d'erreur
            return redirect()->route('dashboard.devis.index')
                ->with('error', 'Une erreur est survenue lors de la mise à jour du devis: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $devis = Devis::findOrFail($id);

        if ($devis->status !== 'En Attente') {
            return redirect()->back()->with('error', 'Vous ne pouvez supprimer cette Proforma que si son statut est "en attente".');
        }

        $devis->delete();

        return redirect()->route('dashboard.devis.index')->with('success', 'Proforma supprimée avec succès.');
    }

    public function download($id)
    {
        $devis = Devis::findOrFail($id);

        if (!$devis->pdf_path || !Storage::disk('public')->exists($devis->pdf_path)) {
            return back()->with('error', 'Le fichier demandé n\'existe pas.');
        }

        return response()->download(storage_path('app/public/' . $devis->pdf_path));
    }

    public function exportCsv(Request $request)
    {
        // Récupérer les paramètres de filtrage (exemple : statut, date, etc.)
        $filters = [
            'status' => $request->input('status'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            // Ajoutez d'autres filtres si nécessaire
        ];
    
        // Appliquer les filtres à la requête
        $query = Devis::with(['client', 'user', 'details']);
    
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }
    
        // Récupérer les données filtrées
        $devis = $query->get();
    
        // Créer un fichier CSV
        $csvFileName = 'devis_export_' . date('Y-m-d_H-i-s') . '.csv';
        $csvFile = fopen('php://temp', 'w');  // Créer un flux temporaire pour écrire dans le fichier
    
        // Ajouter l'entête du CSV
        fputcsv($csvFile, [
            'Date de Création',
            'N° Proforma',
            'Client',
            'Coût Total',
            'Devise',
            'Établi Par',
            'Statut'
        ]);
    
        // Remplir les données
        foreach ($devis as $devi) {
            $clientName = $devi->client ? $devi->client->nom : 'Client inconnu';
            $userName = $devi->user ? $devi->user->name : 'Utilisateur inconnu';
            $cost = $devi->details->sum('total');
            $devise = $devi->devise ?? 'USD';
    
            fputcsv($csvFile, [
                $devi->created_at->format('d/m/Y H:i:s'),
                $devi->num_proforma ?? 'N/A',
                $clientName,
                number_format($cost, 2, ',', ' '),
                $devise,
                $userName,
                $devi->status ?? 'Non renseigné'
            ]);
        }
    
        // Retourner le fichier CSV en téléchargement
        rewind($csvFile);  // Rewind pour être sûr de lire depuis le début
        return response(stream_get_contents($csvFile), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ]); // Assurez-vous de supprimer le fichier après l'envoi
    }
    
    

}
