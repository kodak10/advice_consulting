<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;



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
            $devis = Devis::where('status', '!=', 'En Attente')
            ->where('status', '!=', 'En Attente')
                ->get();
            $factures = Facture::all();
    
            $comptables = User::role('Comptable')->get(); 

            return view('administration.pages.index-daf', compact('devis', 'factures', 'comptables'));
        } 
        
        elseif ($user->hasRole('Comptable')) {
            $factures = Facture::where('pays_id', $user->pays_id)->get();

            $devis = Devis::where('pays_id', $user->pays_id)
                ->where('status', '!=', 'En Attente')
                ->get();

                
    
            return view('administration.pages.index-comptable', compact('devis', 'factures'));
        } 
        
        elseif ($user->hasRole('Commercial')) {
            $devis = Devis::where('pays_id', $user->pays_id)
                ->where('user_id', $user->id)
                ->get();
    
            return view('administration.pages.index-commercial', compact('devis'));
        }
    
        return view('administration.pages.maintenance')->with('error', 'Accès refusé.');
    }

    public function getFactures(Request $request)
{
    dd($request->all()); // Vérifie les données envoyées en AJAX

    $query = Facture::with(['devis.client', 'user']);

    if ($request->comptable) {
        $query->where('user_id', $request->comptable);
    }

    if ($request->start_date && $request->end_date) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    return DataTables::of($query)
        ->addColumn('client', fn($facture) => $facture->devis->client->nom ?? 'N/A')
        ->addColumn('cout', fn($facture) => $facture->devis->details->sum('total') . ' ' . $facture->devis->devise)
        ->addColumn('etabli_par', fn($facture) => $facture->user->name)
        ->addColumn('statut', fn($facture) => $facture->devis->status ?? 'Non renseigné')
        ->addColumn('action', function ($facture) {
            return '<a href="'.route('dashboard.factures.download', $facture->id).'" class="text-primary me-2" title="Télécharger">
                        <i class="ti ti-download fs-5"></i>
                    </a>';
        })
        ->rawColumns(['action'])
        ->make(true);
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
