<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Devis;

class DevisController extends Controller
{
   public function index()
{
    $devis = Devis::with('client') // charge la relation client
       // ->where('pays_id', Auth::user()->pays_id)
        //->where('status', 'En Attente de facture')
        ->get();

    // On retourne le tableau avec client_name
    $devis->transform(function ($d) {
        return [
            'id' => $d->id,
            'date' => $d->created_at,
            'client_id' => $d->client_id,
            'client_name' => $d->client->nom,
            'date_emission' => $d->date_emission,
            'date_echeance' => $d->date_echeance,
            'total_ttc' => $d->total_ttc,
            'status' => $d->status,
            'pdf_path' => $d->pdf_path,
            'num_proforma' => $d->num_proforma,
        ];
    });

    return response()->json($devis);
}


}
