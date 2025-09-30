<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('nom', 'asc')->get();
        return response()->json($clients);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'numero_cc' => 'required|string|regex:/^[A-Z0-9 ]+$/|unique:clients,numero_cc',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'attn' => 'nullable|string|max:100',
        ]);

        $client = Client::create([
            'nom' => $validated['nom'],
            'numero_cc' => $validated['numero_cc'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'adresse' => $validated['adresse'],
            'ville' => $validated['ville'],
            'attn' => $validated['attn'],
            'created_by' => auth()->id() ?? 1,
        ]);

        return response()->json(['message' => 'Client créé avec succès']);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'numero_cc' => 'required|string|regex:/^[A-Z0-9 ]+$/|unique:clients,numero_cc,' . $client->id,
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'attn' => 'nullable|string|max:100',
        ]);

        $client->update($validated);

        return response()->json(['message' => 'Client mis à jour avec succès']);
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(['message' => 'Client supprimé avec succès']);
    }
}
