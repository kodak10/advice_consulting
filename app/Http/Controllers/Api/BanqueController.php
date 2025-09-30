<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banque;
use Illuminate\Validation\Rule;

class BanqueController extends Controller
{
    public function index()
    {
        $banques = Banque::all();
        return response()->json($banques);
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'num_compte' => 'string|regex:/^[A-Z0-9 ]+$/|unique:banques,num_compte',
        ]);

        $banque = Banque::create([
            'name' => $validated['name'],
            'num_compte' => $validated['num_compte'],
        ]);

        return response()->json(['message' => 'Banque créé avec succès']);
    }

    public function update(Request $request, $id)
    {
        $banque = Banque::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'num_compte' => 'required|string|regex:/^[A-Z0-9 ]+$/|unique:banques,num_compte,' . $banque->id,
        ]);

        $banque->update([
            'name' => $validated['name'],
            'num_compte' => $validated['num_compte'],
        ]);


        return response()->json(['message' => 'Banque mis à jour avec succès']);
    }

    public function destroy(Banque $banque)
    {
        $banque->delete();

        return response()->json([
            'message' => 'Banque supprimée avec succès'
        ]);
    }
}
