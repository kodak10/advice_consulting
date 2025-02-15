<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;




class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user = auth()->user(); // Récupère l'utilisateur connecté

        return view('administration.pages.index', compact('user'));
    }

    public function createUser()
    {
        
    }

    
}
