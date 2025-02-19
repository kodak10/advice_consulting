<?php

namespace App\Http\Controllers\Administration;

use App\Events\DevisCreated;
use App\Http\Controllers\Controller;
use App\Models\Banque;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
use App\Models\DevisDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function approuve($id)
        {
            // Récupérer l'utilisateur
            $devis = Devis::findOrFail($id);

            // Mettre à jour le statut en "inactif"
            $devis->status = 'Approuvé';
            $devis->save();

            // Rediriger avec un message de succès
            return redirect()->back()->with('success', 'Devis Approuvé avec succès.');
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
            'designations.*.id' => 'required',
            'designations.*.description' => 'required|',
            'designations.*.quantity' => 'required|numeric|min:1',
            'designations.*.price' => 'required|numeric|min:0', 
            'designations.*.discount' => 'nullable|numeric|min:0', 
            'designations.*.total' => 'required|numeric|min:0', 
        ]);

        $designations = Designation::all();  

        // Récupérer les données validées
        $client = Client::find($validated['client_id']);
        $banque = Banque::find($validated['banque_id']);

        // Passer les données à la vue
        return view('administration.pages.devis.recap', compact('client', 'validated', 'banque', 'designations'));
    }

    // public function store(Request $request)
    // {
    //     // Valider la requête
    //     $validated = $request->validate([
    //         'client_id' => 'required|exists:clients,id',  
    //         'date_emission' => 'required|date',  
    //         'date_echeance' => 'required|date|after_or_equal:date_emission',  
    //         'commande' => 'required|string',  
    //         'livraison' => 'required|string',  
    //         'validite' => 'required|string',  
    //         'banque_id' => 'required|exists:banques,id',  
    //         'total_ht' => 'required|numeric|min:0',  
    //         // 'tva' => 'required|numeric|in:18',  
    //         'total_ttc' => 'required|numeric|min:0',  
    //         'acompte' => 'required|numeric|min:0',  
    //         'solde' => 'required|numeric|min:0',
    //         'designations' => 'required|array', 
    //         'designations.*.designation' => 'required|exists:designations,id', 
    //         'designations.*.quantity' => 'required|numeric|min:1',
    //         'designations.*.price' => 'required|numeric|min:0', 
    //         'designations.*.discount' => 'nullable|numeric|min:0', 
    //         'designations.*.total' => 'required|numeric|min:0',       
    //         // 'num_proforma' => 'required|string|max:255',
 
    //     ]);

    //     // Générer le numéro de proforma
    //     $numProforma = $this->generateNumProforma();

    //     // Récupérer les objets associés
    //     $client = Client::find($validated['client_id']);
    //     $banque = Banque::find($validated['banque_id']);

    //     // Créer le devis dans la base de données
    //     $devis = new Devis();
    //     $devis->client_id = $validated['client_id'];
    //     $devis->date_emission = $validated['date_emission'];
    //     $devis->date_echeance = $validated['date_echeance'];
    //     $devis->commande = $validated['commande'];
    //     $devis->livraison = $validated['livraison'];
    //     $devis->validite = $validated['validite'];
    //     $devis->banque_id = $validated['banque_id'];
    //     $devis->total_ht = $validated['total_ht'];
    //     // $devis->tva = $validated['tva'];
    //     $devis->tva = 1;
    //     $devis->total_ttc = $validated['total_ttc'];
    //     $devis->acompte = $validated['acompte'];
    //     $devis->solde = $validated['solde'];
    //     $devis->delai = 1;
    //     $devis->user_id = Auth::user()->id;
    //     $devis->num_proforma = $numProforma;
    //     $devis->status = "En Attente";

    //     // Sauvegarder le devis
    //     $devis->save();

    //     // Enregistrer les détails du devis (DevisDetail)
    //     foreach ($validated['designations'] as $designationData) {
    //         $devisDetail = new DevisDetail();
    //         $devisDetail->devis_id = $devis->id;
    //         $devisDetail->designation_id = $designationData['designation']; // ID de la désignation
    //         $devisDetail->quantite = $designationData['quantity'];
    //         $devisDetail->prix_unitaire = $designationData['price'];
    //         $devisDetail->remise = $designationData['discount'];
    //         $devisDetail->total = $designationData['total'];
        
    //         // Sauvegarder les détails
    //         $devisDetail->save();
    //     }

    //      // Générer le PDF
    //      $pdf = PDF::loadView('frontend.pdf.devis', compact('devis', 'client', 'banque'));
    //      $pdfOutput = $pdf->output();
 
    //      // Définir le nom du fichier
    //      $imageName = 'devis-' . $devis->id . '.pdf';
 
    //      // Assurez-vous que le dossier existe
    //      $directory = 'pdf/devis';
    //      if (!Storage::disk('public')->exists($directory)) {
    //          Storage::disk('public')->makeDirectory($directory);
    //      }
 
    //      // Enregistrer le PDF dans le dossier storage/app/public/pdf/devis
    //      $imagePath = $directory . '/' . $imageName;
    //      Storage::disk('public')->put($imagePath, $pdfOutput);

    //      // Vérifiez si le fichier a été enregistré avec succès
    //      if (Storage::disk('public')->exists($imagePath)) {
    //          \Log::info("Le fichier a été enregistré avec succès : " . storage_path('app/public/' . $imagePath));
    //      } else {
    //          \Log::error("Le fichier n'existe pas, problème d'enregistrement !");
    //          throw new \Exception("Erreur lors de l'enregistrement du fichier PDF.");
    //      }
 
    //      // Enregistrer le chemin dans la base de données
    //      $devis->pdf_path = $imagePath;
    //      $devis->save();
 
         
    //      // Nettoyer la session
    //      $request->session()->forget([
    //          'client_id', 'date_emission', 'date_echeance', 'commande', 'livraison', 'validite',
    //          'banque_id', 'total_ht', 'tva', 'total_ttc', 'acompte', 'solde', 'designations'
    //      ]);
 
    //      // Télécharger le fichier PDF
    //      // return response()->download(storage_path('app/public/' . $imagePath))->deleteFileAfterSend(true);
    //      return response()->download(storage_path('app/public/' . $imagePath));

    //     // try {
           

    //     // } catch (\Exception $e) {
    //     //     \Log::error("Erreur lors de la génération ou de l'enregistrement du PDF : " . $e->getMessage());
    //     //     return back()->withErrors("Une erreur s'est produite lors de la génération du PDF. Veuillez réessayer.");
    //     // }
    // }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'date_emission' => 'required|date',
                'date_echeance' => 'required|date|after_or_equal:date_emission',
                'commande' => 'required|string',
                'livraison' => 'required|string',
                'validite' => 'required|string',
                'banque_id' => 'required|exists:banques,id',
                'total_ht' => 'required|numeric|min:0',
                'total_ttc' => 'required|numeric|min:0',
                'acompte' => 'required|numeric|min:0',
                'solde' => 'required|numeric|min:0',
                'designations' => 'required|array',
                'designations.*.id' => 'required|exists:designations,id',
                'designations.*.description' => 'required',
                'designations.*.quantity' => 'required|numeric|min:1',
                'designations.*.price' => 'required|numeric|min:0',
                'designations.*.discount' => 'nullable|numeric|min:0',
                'designations.*.total' => 'required|numeric|min:0',
            ]);

            $numProforma = $this->generateNumProforma();

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
            $devis->tva = 1; // pour des tests
            $devis->total_ttc = $validated['total_ttc'];
            $devis->acompte = $validated['acompte'];
            $devis->solde = $validated['solde'];
            $devis->delai = 1; // pour des tests
            $devis->user_id = Auth::user()->id;
            $devis->num_proforma = $numProforma;
            $devis->status = "En Attente";

            // Sauvegarder le devis
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

            // Diffuser l'événement après avoir créé le devis
            event(new DevisCreated($devis));

            // return response()->json([
            //     'message' => 'Devis créé avec succès et événement envoyé!',
            //     'status' => 'success',
            // ]);

            // Envoyer la notification en base de données ou par d'autres moyens
            // $users = User::role(['comptable', 'administrateur'])->get();
            // foreach ($users as $user) {
            //     $user->notify(new NotificationController($devis));
            // }
            


            // Générer le PDF
            $pdf = PDF::loadView('frontend.pdf.devis', compact('devis', 'client', 'banque'));
            $pdfOutput = $pdf->output();

            $imageName = 'devis-' . $devis->id . '.pdf';

            // Assurez-vous que le dossier existe
            $directory = 'pdf/devis';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Enregistrer le PDF dans le dossier storage/app/public/pdf/devis
            $imagePath = $directory . '/' . $imageName;
            Storage::disk('public')->put($imagePath, $pdfOutput);

            // Vérifiez si le fichier a été enregistré avec succès
            // if (Storage::disk('public')->exists($imagePath)) {
            //     \Log::info("Le fichier a été enregistré avec succès : " . storage_path('app/public/' . $imagePath));
            // } else {
            //     \Log::error("Le fichier n'existe pas, problème d'enregistrement !");
            //     throw new \Exception("Erreur lors de l'enregistrement du fichier PDF.");
            // }

            // Enregistrer le chemin dans la base de données
            $devis->pdf_path = $imagePath;
            $devis->save();

            // Nettoyer la session
            $request->session()->forget([
                'client_id', 'date_emission', 'date_echeance', 'commande', 'livraison', 'validite',
                'banque_id', 'total_ht', 'tva', 'total_ttc', 'acompte', 'solde', 'designations'
            ]);

            // Télécharger le fichier PDF
            return response()->download(storage_path('app/public/' . $imagePath));

        } catch (\Exception $e) {
            \Log::error("Erreur lors de la génération ou de l'enregistrement du PDF : " . $e->getMessage());
            return back()->withErrors("Une erreur s'est produite lors de la génération du PDF. Veuillez réessayer.");
        }
    }
   
    public function edit($id)
    {
        $devis = Devis::findOrFail($id);
        $clients = Client::all(); 
        $banques = Banque::all(); 
        $designations = Designation::all(); // Charger toutes les désignations disponibles pour le formulaire


        // Vérifier si le devis est en attente
        if ($devis->status !== 'En Attente') {
            return redirect()->back()->with('error', 'Vous ne pouvez modifier ce devis que si son statut est "en attente".');
        }

        return view('administration.pages.devis.edit', compact('devis','clients','banques', 'designations'));
    }

    public function recapUpdate(Request $request, $id)
    {

        // dd($request);

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
        $devis = Devis::findOrFail($id);

        $designations = Designation::all();

        // Récupérer les données validées
        $client = Client::find($validated['client_id']);
        $banque = Banque::find($validated['banque_id']);

        // Passer les données à la vue
        return view('administration.pages.devis.recap-update', compact('client', 'validated', 'banque', 'designations', 'devis'));
    }

    // public function storeRecap(Request $request)
    // {
    //     // dd($request);
    //     // Stocker les données en session
    //     session(['devis_data' => $request->all()]);

    //     // Redirection vers la page de récapitulatif
    //     return redirect()->route('dashboard.devis.index');
    // }
    public function storeRecap(Request $request, $id)
    {
        // Valider les données envoyées par le formulaire
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',  
            'date_emission' => 'required|date',  
            'date_echeance' => 'required|date|after_or_equal:date_emission',  
            'commande' => 'required|string',  
            'livraison' => 'required|string',  
            'validite' => 'required|string',  
            'banque_id' => 'required|exists:banques,id',  
            'total_ht' => 'required|numeric|min:0',  
            'tva' => 'required',  
            'total_ttc' => 'required|numeric|min:0',  
            'acompte' => 'required|numeric|min:0',  
            'solde' => 'required|numeric|min:0',  
            'designations' => 'required|array', 
            'designations.*.id' => 'required|exists:designations,id',
            'designations.*.designation' => 'required|exists:designations,id', 
            'designations.*.quantity' => 'required|numeric|min:1',
            'designations.*.price' => 'required|numeric|min:0', 
            'designations.*.discount' => 'nullable|numeric|min:0', 
            'designations.*.total' => 'required|numeric|min:0', 
        ]);

        // Récupérer le devis à mettre à jour
        $devis = Devis::findOrFail($id);

        // Mettre à jour les informations du devis
        $devis->update([
            'client_id' => $validated['client_id'],
            'date_emission' => $validated['date_emission'],
            'date_echeance' => $validated['date_echeance'],
            'commande' => $validated['commande'],
            'livraison' => $validated['livraison'],
            'validite' => $validated['validite'],
            'banque_id' => $validated['banque_id'],
            'total_ht' => $validated['total_ht'],
            'tva' => $validated['tva'],
            'total_ttc' => $validated['total_ttc'],
            'acompte' => $validated['acompte'],
            'solde' => $validated['solde'],
        ]);

        // Mettre à jour ou créer les lignes de devis
        foreach ($validated['designations'] as $designationData) {
            $devisDetail = DevisDetail::updateOrCreate(
                ['devis_id' => $devis->id, 'designation_id' => $designationData['id']],
                [
                    'quantite' => $designationData['quantity'],
                    'prix_unitaire' => $designationData['price'],
                    'remise' => $designationData['discount'],
                    'total' => $designationData['total'],
                ]
            );
        }

        // Redirection après mise à jour
        return redirect()->route('dashboard.devis.index', $devis->id)
            ->with('success', 'Devis mis à jour avec succès.');
    }



    public function destroy($id)
    {
        $devis = Devis::findOrFail($id);

        // Vérifier si le devis est en attente avant suppression
        if ($devis->status !== 'En Attente') {
            return redirect()->back()->with('error', 'Vous ne pouvez supprimer ce devis que si son statut est "en attente".');
        }

        $devis->delete();

        return redirect()->route('dashboard.devis.index')->with('success', 'Devis supprimé avec succès.');
    }

    public function download($id)
    {
        $devis = Devis::findOrFail($id);

        if (!$devis->pdf_path || !Storage::disk('public')->exists($devis->pdf_path)) {
            return back()->with('error', 'Le fichier demandé n\'existe pas.');
        }

        return response()->download(storage_path('app/public/' . $devis->pdf_path));
    }


   


}
