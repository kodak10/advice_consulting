<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use Illuminate\Http\Request;

class FacturesController extends Controller
{
    public function index()
    {
        $factures = Facture::all();
        return view('administration.pages.factures.index.blade', compact('factures'));
    }

    public function create()
    {
        return view('administration.pages.factures.create');
    }
}
