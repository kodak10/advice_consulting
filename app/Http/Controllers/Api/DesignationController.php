<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::with('categorie')->orderBy('reference')->get();
        Log::info('designations:', $designations->toArray());
        return response()->json($designations);
    }

    public function getCategories()
    {
        $categories = Categorie::orderBy('nom', 'asc')->get();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'reference'     => 'required|string|unique:designations,reference|max:20',
            'libelle'       => 'required|string|max:100',
            'description'   => 'nullable|string|max:150',
            'prix_unitaire' => 'required|numeric|min:0',
            'categorie_id'  => 'required|exists:categories,id',
        ]);

        $designation = Designation::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Désignation ajoutée avec succès',
            'data'    => $designation
        ]);
    }

    public function update(Request $request, Designation $designation)
    {
        $validatedData = $request->validate([
            'reference'     => 'required|string|max:20|unique:designations,reference,' . $designation->id,
            'libelle'       => 'required|string|max:100',
            'description'   => 'nullable|string|max:150',
            'prix_unitaire' => 'required|numeric|min:0',
            'categorie_id'  => 'required|exists:categories,id',
        ]);

        $designation->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Désignation mise à jour avec succès',
            'data'    => $designation
        ]);
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();

        return response()->json([
            'message' => 'Désignation supprimée avec succès'
        ]);
    }


}
