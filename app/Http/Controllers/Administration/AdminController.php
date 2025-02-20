<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\User;
use Illuminate\Support\Facades\Auth;




class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('administration.pages.index', );
    }
    
    public function indexAdmin()
    {
        $users = User::all();
        $userTotal = User::count();
        $userActif = User::where('status', 'Actif')->count();
        $userInactif = User::where('status', 'Inactif')->count();

        return view('administration.pages.index-admin', compact('users', 'userTotal', 'userActif', 'userInactif'));
    }

    public function indexDaf()
    {
   
    $devis = Devis::where('pays_id', Auth::user()->pays_id)
        ->where('status', 'Approuvé')
        ->get();

    $factures = Facture::where('pays_id', Auth::user()->pays_id)->get();


    return view('administration.pages.index-daf', compact('devis','factures'));
    }

    public function indexComptable()
    {
        $myFactures = Facture::where('pays_id', Auth::user()->pays_id)
        ->get();

        $devis = Devis::where('pays_id', Auth::user()->pays_id)
        ->where('user_id', Auth::user()->id)
        ->where('status', 'Approuvé')
        ->get();
        return view('administration.pages.index-comptable', compact('devis', 'myFactures'));
    }


     public function indexCommercial()
    {
   
    $devis = Devis::where('pays_id', Auth::user()->pays_id)
            ->where('user_id', Auth::user()->id)
            ->get();



    return view('administration.pages.index-commercial', compact('devis'));
    }

    public function createUser()
    {
        
    }

    
}
