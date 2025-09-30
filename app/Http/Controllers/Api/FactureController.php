<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        $devisList = Devis::with(['client', 'facture'])->get();

        $devisList->transform(function ($d) {
            return [
                'id' => $d->id,
                'client_name' => $d->client->name ?? $d->client_id,
                'date_emission' => $d->date_emission,
                'date_echeance' => $d->date_echeance,
                'total_ttc' => $d->total_ttc,
                'status' => $d->status,
                'facture_type' => $d->facture->type_facture ?? null,
                'facture_status' => $d->facture->status ?? null,
                'has_facture' => $d->facture ? true : false,
                'pdf_path' => $d->pdf_path ?? null,
            ];
        });

        return response()->json(['devis' => $devisList]);
    }

}
