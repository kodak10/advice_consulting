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


    public function index(Request $request)
    {
        $user = Auth::user();
    
        if ($user->hasRole('Administrateur')) {
            $users = User::all();
            $userTotal = User::count();
            $userActif = User::where('status', 'Actif')->count();
            $userInactif = User::where('status', 'Inactif')->count();
    
            return view('administration.pages.index-admin', compact('users', 'userTotal', 'userActif', 'userInactif'));
        } 

        
        elseif ($user->hasRole(['DG', 'Daf'])) {
            $devisQuery = Devis::where('status', '!=', 'En Attente de validation');
        
            // Filtre par période
            if ($request->has('start2') && $request->start2 != "") {
                $devisQuery->where('created_at', '>=', $request->start2);
            }
        
            if ($request->has('end2') && $request->end2 != "") {
                $devisQuery->where('created_at', '<=', $request->end2);
            }
        
            // Si une recherche est effectuée, appliquer la pagination, sinon limiter à 10
            if ($request->has('start2') || $request->has('end2')) {
                $devis = $devisQuery->orderBy('created_at', 'desc')->get();
            } else {
                $devis = $devisQuery->orderBy('created_at', 'desc')->limit(10)->get();

            }
        
            $facturesQuery = Facture::query();
        
            // Filtre par comptable
            if ($request->has('comptable') && $request->comptable != "") {
                $facturesQuery->whereHas('user', function($query) use ($request) {
                    $query->where('name', $request->comptable);
                });
            }
        
            // Filtre par date de début
            if ($request->has('start') && $request->start != "") {
                $facturesQuery->where('created_at', '>=', $request->start);
            }
        
            // Filtre par date de fin
            if ($request->has('end') && $request->end != "") {
                $endDate = $request->end . ' 23:59:59';
                $facturesQuery->where('created_at', '<=', $endDate);
            }
        
            // Si une recherche est effectuée, appliquer la pagination, sinon limiter à 10
            if ($request->has('comptable') || $request->has('start') || $request->has('end')) {
                $factures = $facturesQuery->orderBy('created_at', 'desc')->get();
            } else {
                $factures = $facturesQuery->orderBy('created_at', 'desc')->limit(10)->get();
            }
        
            $comptables = User::role('Comptable')->get();
        
            return view('administration.pages.index-daf', compact('devis', 'factures', 'comptables'));
        }
        
        
        
        
        
        elseif ($user->hasRole('Comptable')) {
            // $factures = Facture::where('pays_id', $user->pays_id)->get();

            // Requête de base pour les factures
            $facturesQuery = Facture::where('pays_id', $user->pays_id);
            
            // Filtre par période
            if ($request->has('start') && $request->start != "") {
                $facturesQuery->where('created_at', '>=', $request->start);
            }

            if ($request->has('end') && $request->end != "") {
                $facturesQuery->where('created_at', '<=', $request->end);
            }

            // Appliquer la pagination ou la récupération sans limite
            if ($request->has('start') || $request->has('end')) {
                $factures = $facturesQuery->get();
            } else {
                $factures = $facturesQuery->limit(10)->get();  // Limiter à 10 si pas de filtre
            }


            $devisQuery = Devis::where('pays_id', $user->pays_id)
                ->where('status', '!=', 'En Attente de validation');
        
            // Filtre par période
            if ($request->has('start') && $request->start != "") {
                $devisQuery->where('created_at', '>=', $request->start);
            }
        
            if ($request->has('end') && $request->end != "") {
                $devisQuery->where('created_at', '<=', $request->end);
            }
        
            // Si une recherche est effectuée, appliquer la pagination, sinon limiter à 10
            if ($request->has('start') || $request->has('end')) {
                $devis = $devisQuery->get();
            } else {
                $devis = $devisQuery->limit(10)->get();
            }
    
            return view('administration.pages.index-comptable', compact('devis', 'factures'));
        } 
        
        elseif ($user->hasRole('Commercial')) {

            // Requête de base pour les devis du commercial
            $devisQuery = Devis::where('pays_id', $user->pays_id)
                            ->where('user_id', $user->id);  // Seul les devis du commercial connecté

            // Filtre par période (date de création)
            if ($request->has('start') && $request->start != "") {
                $devisQuery->where('created_at', '>=', $request->start);
            }

            if ($request->has('end') && $request->end != "") {
                $devisQuery->where('created_at', '<=', $request->end);
            }

            // Appliquer la pagination ou la récupération sans limite
            if ($request->has('start') || $request->has('end')) {
                $devis = $devisQuery->get();
            } else {
                $devis = $devisQuery->limit(10)->get();  // Limiter à 10 si pas de filtre
            }

    
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

    
}
