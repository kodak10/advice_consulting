<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;




class AdminController extends Controller
{


    public function index()
    {
        $user = Auth::user();
    
        if ($user->hasRole('Administrateur')) {
            $users = User::all();
            $userTotal = User::count();
            $userActif = User::where('status', 'Actif')->count();
            $userInactif = User::where('status', 'Inactif')->count();
    
            return view('administration.pages.index-admin', compact('users', 'userTotal', 'userActif', 'userInactif'));
        } 
        
        elseif ($user->hasRole('Daf')) {
            $devis = Devis::where('pays_id', $user->pays_id)
                ->where('status', 'Approuvé')
                ->get();
            $factures = Facture::where('pays_id', $user->pays_id)->get();
    
            return view('administration.pages.index-daf', compact('devis', 'factures'));
        } 
        
        elseif ($user->hasRole('Comptable')) {
            $myFactures = Facture::where('pays_id', $user->pays_id)->where('user_id', $user->id)->get();
            $devis = Devis::where('pays_id', $user->pays_id)
                ->where('user_id', $user->id)
                ->where('status', 'Approuvé')
                ->get();
    
            return view('administration.pages.index-comptable', compact('devis', 'myFactures'));
        } 
        
        elseif ($user->hasRole('Commercial')) {
            $devis = Devis::where('pays_id', $user->pays_id)
                ->where('user_id', $user->id)
                ->get();
    
            return view('administration.pages.index-commercial', compact('devis'));
        }
    
        return view('administration.pages.maintenance')->with('error', 'Accès refusé.');
    }

   // Marquer toutes les notifications comme lues
   public function markAllAsRead()
   {
       Auth::user()->unreadNotifications->markAsRead();
       return response()->json(['success' => true]);
   }

   // Marquer une notification spécifique comme lue
   public function markAsRead($id)
   {
       $notification = Auth::user()->notifications()->where('id', $id)->first();
       if ($notification) {
           $notification->markAsRead();
           return response()->json(['success' => true]);
       }
       return response()->json(['success' => false], 404);
   }

    

    // public function indexAdmin()
    // {
    //     $users = User::all();
    //     $userTotal = User::count();
    //     $userActif = User::where('status', 'Actif')->count();
    //     $userInactif = User::where('status', 'Inactif')->count();

    //     return view('administration.pages.index-admin', compact('users', 'userTotal', 'userActif', 'userInactif'));
    // }

    // public function indexDaf()
    // {
   
    // $devis = Devis::where('pays_id', Auth::user()->pays_id)
    //     ->where('status', 'Approuvé')
    //     ->get();

    // $factures = Facture::where('pays_id', Auth::user()->pays_id)->get();


    // return view('administration.pages.index-daf', compact('devis','factures'));
    // }

    // public function indexComptable()
    // {
    //     $myFactures = Facture::where('pays_id', Auth::user()->pays_id)
    //     ->get();

    //     $devis = Devis::where('pays_id', Auth::user()->pays_id)
    //     ->where('user_id', Auth::user()->id)
    //     ->where('status', 'Approuvé')
    //     ->get();
    //     return view('administration.pages.index-comptable', compact('devis', 'myFactures'));
    // }


    //  public function indexCommercial()
    // {
   
    // $devis = Devis::where('pays_id', Auth::user()->pays_id)
    //         ->where('user_id', Auth::user()->id)
    //         ->get();



    // return view('administration.pages.index-commercial', compact('devis'));
    // }

    public function createUser()
    {
        
    }

    
}
