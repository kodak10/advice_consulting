<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all(); 
        return view('administration.pages.clients.index', compact('clients')); 
    }




public function store(Request $request)
{
    try {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'nom'       => 'required|string|max:255',
            'numero_cc' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'adresse'   => 'required|string|max:255',
            'ville'     => 'required|string|max:255',
            'attn'      => 'nullable|string|max:255',
        ]);

        // Récupérer l'ID de l'utilisateur (ou 1 par défaut)
        $userId = Auth::check() ? Auth::user()->id : 1;

        // Création et enregistrement du client
        $client = new Client([
            'nom'       => $validatedData['nom'],
            'numero_cc' => $validatedData['numero_cc'],
            'telephone' => $validatedData['telephone'],
            'adresse'   => $validatedData['adresse'],
            'ville'     => $validatedData['ville'],
            'attn'      => $validatedData['attn'],
            'created_by'=> $userId,
        ]);

        $client->save();

        // Message de succès
        session()->flash('success', 'Client ajouté avec succès !');

        return response()->json([
            'success' => true,
            'message' => 'Client ajouté avec succès!'
        ]);

    } catch (ValidationException $e) {
        // Récupérer le tableau des erreurs :
        // ex : [ 'nom' => ['Le champ nom est obligatoire.'], 'telephone' => ['Le champ téléphone est obligatoire.'], ... ]
        $errors = $e->errors();
    
        // Transformer le tableau pour obtenir une chaîne avec tous les messages
        $errorMessages = [];
        foreach ($errors as $fieldErrors) {
            foreach ($fieldErrors as $message) {
                $errorMessages[] = $message;
            }
        }
        // Séparer les messages par un retour à la ligne (<br>)
        $errorString = implode('<br>', $errorMessages);
    
        // Stocker le message détaillé dans la session pour l'affichage dans la vue
        session()->flash('error', $errorString);
    
        // Retourner un message générique pour le toast
        return response()->json([
            'success' => false,
            'message' => "Une erreur est survenue lors de l'enregistrement.",
            'errors'  => $errors
        ], 422);
    
    } catch (\Exception $e) {
        // Pour toute autre exception, on stocke le message détaillé en session
        session()->flash('error', 'Une erreur est survenue : ' . $e->getMessage());
    
        // Et on renvoie un message générique pour le toast
        return response()->json([
            'success' => false,
            'message' => "Une erreur est survenue lors de l'enregistrement.",
            'errors'  => $e->getMessage()
        ], 500);
    }
    
}






public function update(Request $request, Client $client)
{
    // Validation
    $validatedData = $request->validate([
        'nom' => 'required|string|max:255',
        'numero_cc' => 'required|string|unique:clients,numero_cc,' . $client->id,
        'telephone' => 'nullable|string',
        'adresse' => 'nullable|string',
        'ville' => 'nullable|string',
        'attn' => 'nullable|string',
    ]);

    // Mise à jour du client
    try {
        $client->update([
            'nom' => $request->nom,
            'numero_cc' => $request->numero_cc,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'attn' => $request->attn,
        ]);

         // Message de succès
         session()->flash('success', 'Client mis à jour avec succès!');

         return response()->json([
            'success' => true,
            'message' => 'Client mis à jour avec succès!'
        ]);

    } catch (ValidationException $e) {
        // Récupérer le tableau des erreurs :
        // ex : [ 'nom' => ['Le champ nom est obligatoire.'], 'telephone' => ['Le champ téléphone est obligatoire.'], ... ]
        $errors = $e->errors();
    
        // Transformer le tableau pour obtenir une chaîne avec tous les messages
        $errorMessages = [];
        foreach ($errors as $fieldErrors) {
            foreach ($fieldErrors as $message) {
                $errorMessages[] = $message;
            }
        }
        // Séparer les messages par un retour à la ligne (<br>)
        $errorString = implode('<br>', $errorMessages);
    
        // Stocker le message détaillé dans la session pour l'affichage dans la vue
        session()->flash('error', $errorString);
    
        // Retourner un message générique pour le toast
        return response()->json([
            'success' => false,
            'message' => "Une erreur est survenue lors de la mise à jour.",
            'errors'  => $errors
        ], 422);
    
    } catch (\Exception $e) {
        // Pour toute autre exception, on stocke le message détaillé en session
        session()->flash('error', 'Une erreur est survenue : ' . $e->getMessage());
    
        // Et on renvoie un message générique pour le toast
        return response()->json([
            'success' => false,
            'message' => "Une erreur est survenue lors de la mise à jour.",
            'errors'  => $e->getMessage()
        ], 500);
    }
}


    // Supprimer un client
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('dashboard.clients.index')->with('success', 'Client supprimé avec succès');
    }

}
