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
        return view('administration.pages.index');
    }

    public function createUser()
    {
        
    }

    
}
