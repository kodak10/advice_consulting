<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\User;




class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $users = User::all();
        $userTotal = User::count();
        $userActif = User::where('status', 'Actif')->count();
        $userInactif = User::where('status', 'Inactif')->count();

        return view('administration.pages.index-admin', compact('users', 'userTotal', 'userActif', 'userInactif'));
    }

    public function createUser()
    {
        
    }

    
}
