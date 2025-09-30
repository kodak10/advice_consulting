<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConfigurationGenerale;
use Illuminate\Support\Facades\Log;

class ConfigurationController extends Controller
{
    public function show()
    {
        $config = ConfigurationGenerale::first();
        return response()->json($config);
    }

    public function update(Request $request)
    {
        Log::info('POST /entreprise reçu', [
            'all()' => $request->all(),
            'files()' => $request->allFiles()
        ]);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'contact' => 'nullable|string|max:50',
            'ncc' => 'nullable|string|max:50',
            'adresse' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:100'
        ]);

        $config = ConfigurationGenerale::first();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logos'), $filename);
            $validated['logo'] = url('uploads/logos/' . $filename);
        }

        $config->update($validated);

        return response()->json([
            'message' => 'Configuration mise à jour avec succès !',
            'config' => $config
        ]);
    }





}
