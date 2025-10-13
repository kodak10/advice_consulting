<?php

namespace App\Helpers;

use App\Models\Historique;

function enregistrerActionHistorique($userId, $action)
{
    // Exemple d'action : création d'un élément
    $item = Item::create($request->all());

    // Enregistrer l'action dans les historiques
    Historique::create([
        'user_id' => auth()->user()->id,
        'action' => 'Création d\'un élément avec l\'ID ' . $item->id,
        'ip_address' => request()->ip(),
        'performed_at' => now(),
    ]);

    return redirect()->route('items.index')->with('success', 'Élément créé avec succès.');
}