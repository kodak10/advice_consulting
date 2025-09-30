<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banque;

class BanqueController extends Controller
{
    public function index()
    {
        $banques = Banque::all();
        return response()->json($banques);
    }
}
