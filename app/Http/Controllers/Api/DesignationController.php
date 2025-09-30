<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::orderBy('description', 'asc')->get();

        return response()->json($designations);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'reference'     => 'required|string|unique:designations,reference|max:20',
                'libelle'        => 'required|string|max:100',
                'description'   => 'required|string|max:150',
                'prix_unitaire' => 'required|numeric|min:0',
            ]);

            $designation = new Designation([
                'reference'     => $validatedData['reference'],
                'libelle'        => $validatedData['libelle'],
                'description'   => $validatedData['description'],
                'prix_unitaire' => $validatedData['prix_unitaire'],
            ]);

            $designation->save();

            return response()->json([
                'success' => true,
                'message' => 'Désignation ajouté avec succès!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => "Une erreur est survenue lors de l'enregistrement.",
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
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
            'libelle' => 'required|string|max:100',
            'description' => 'required|string|max:150',
            'prix_unitaire' => 'required|numeric|min:0',
        ]);

        // Mise à jour du designation
        try {
            $designation->update([
                'reference' => $request->reference,
                'libelle' => $request->libelle,
                'description' => $request->description,
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

        return response()->json([
            'message' => 'Désignation supprimée avec succès'
        ]);
    }


}
