<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DesignationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Comptable|Commercial|DG');
    }
    public function index()
    {
        $designations = Designation::orderBy('description', 'asc')->get();

        return view('administration.pages.designations.index', compact('designations'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'reference'       => 'required|string|unique:designations,reference|max:20',
                'description' => 'required|string|max:150',
                'prix_unitaire' => 'required|string|max:10',
            ]);

            $designation = new Designation([
                'reference'       => $validatedData['reference'],
                'description' => $validatedData['description'],
                'prix_unitaire' => $validatedData['prix_unitaire'],
                
            ]);

            $designation->save();

            session()->flash('success', 'Désignation ajouté avec succès !');

            return response()->json([
                'success' => true,
                'message' => 'Designation ajouté avec succès!'
            ]);

        } catch (ValidationException $e) {
            
            $errors = $e->errors();
        
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

    public function update(Request $request, Designation $designation)
{
    // Validation
    $validatedData = $request->validate([
        'reference' => 'required|string|max:255',
        'description' => 'required|string|unique:designations,description,' . $designation->id,
        'prix_unitaire' => 'nullable|string',
    ]);

    // Mise à jour du designation
    try {
        $designation->update([
            'description' => $request->description,
            'reference' => $request->reference,
            'prix_unitaire' => $request->prix_unitaire,
        ]);

         // Message de succès
         session()->flash('success', 'Désignation mise à jour avec succès!');

         return response()->json([
            'success' => true,
            'message' => 'Désignation mise à jour avec succès!'
        ]);

    } catch (ValidationException $e) {
       
        $errors = $e->errors();
    
        $errorMessages = [];
        foreach ($errors as $fieldErrors) {
            foreach ($fieldErrors as $message) {
                $errorMessages[] = $message;
            }
        }
        $errorString = implode('<br>', $errorMessages);
    
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


    // Supprimer une designation
    public function destroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('dashboard.designations.index')->with('success', 'Désignation supprimé avec succès');
    }

}

