<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BanqueController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrateur|Daf|DG');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banques = Banque::all();
        return view('administration.pages.banques.index', compact('banques'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Valider les données du formulaire
            $validatedData = $request->validate([
                'name'       => 'required|string|max:100',
                'num_compte' => 'required|string|unique:banques,num_compte|min:14|max:35',
            ]);

            $banques = new Banque([
                'name'       => $validatedData['name'],
                'num_compte' => $validatedData['num_compte'],
            ]);

            $banques->save();

            session()->flash('success', 'Banque ajouté avec succès !');

            return response()->json([
                'success' => true,
                'message' => 'Banque ajouté avec succès!'
            ]);

        } catch (ValidationException $e) {
            // Récupérer le tableau des erreurs :
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banque $banque)
{
    // Validation
    $validatedData = $request->validate([
        'name' => 'required|string|max:100',
        'num_compte' => 'required|string|min:14|max:35|unique:banques,num_compte,' . $banque->id,
        
    ]);

    // Mise à jour du client
    try {
        $banque->update([
            'name' => $request->name,
            'num_compte' => $request->num_compte,

        ]);

         // Message de succès
         session()->flash('success', 'Banque mise à jour avec succès!');

         return response()->json([
            'success' => true,
            'message' => 'Banque mise à jour avec succès!'
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banque $banque)
    {
        $banque->delete();
        return redirect()->route('dashboard.banques.index')->with('success', 'Banque supprimé avec succès');
    }
}
