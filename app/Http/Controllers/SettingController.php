<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\circuit_organe;
use App\Models\circuit_organe_user;
use App\Models\organe_validateur;
use App\Models\direction;
use App\Models\User;
use App\Models\dem_objet_g;
use App\Models\dem_objet_sg;
use App\Models\dem_objet;
use App\Models\filliale;
use App\Traits\TracksUserActions;

class SettingController extends Controller
{
    use TracksUserActions;
    //
    public function menu_parametre(Request $request, $vue)
    {
        //
        // dd($request);
        $parametres = filliale::with('circuit')->orderBy('created_at', 'desc')->get();
        // dd($parametres);
        $organes = filliale::with('organes')->orderBy('created_at', 'desc')->get();
        $objet_demande = dem_objet_g::with('dem_objet_sgs')->orderBy('created_at', 'desc')->get();

        $user = User::with('userProfile')->find(auth()->user()->id);
        // $parametres = UserProfile::with('directions_id')->find(auth()->user()->directions_id);

        if($request->ajax()){
            return response()-> json([
                'parametres' => $parametres,
                'organes' => $organes,
                'objet_demande' => $objet_demande,
            ]);
        }
        return view('pages.parametres_component.'.$vue, ['parametres'=>$parametres, 'organes'=>$organes]);
    }
    public function index_parametre(Request $request)
    {
        //

        $parametres = circuit_organe::with('users', 'direction', 'filliale')->orderBy('created_at', 'desc')->get();
        // dd($parametres);

        $user = User::with('userProfile')->find(auth()->user()->id);
        // $parametres = UserProfile::with('directions_id')->find(auth()->user()->directions_id);

        if($request->ajax()){
            return response()-> json([
                'parametres' => $parametres,
            ]);
        }
        return view('pages.parametres');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_objet_g(Request $request)
    {
        //

        // dd($request);
        $user = User::with('userProfile')->find(auth()->user()->id);
        $request['user_id'] = auth()->user()->id;

        $validated  = $request->validate
            ([
                'label' => 'string',
                'user_id' => 'integer'
            ]); 
            $dem = dem_objet_g::create($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_sousobjet(Request $request)
    {
        //

        // dd($request);
        $user = User::with('userProfile')->find(auth()->user()->id);
        $request['user_id'] = auth()->user()->id;

        $validated  = $request->validate
            ([
                'label' => 'string',
                'dem_objet_g_id' => 'integer',
                'user_id' => 'integer'
            ]); 
            $dem = dem_objet_sg::create($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_objet(Request $request)
    {
        //

        // dd($request);
        $user = User::with('userProfile')->find(auth()->user()->id);
        $request['user_id'] = auth()->user()->id;

        $validated  = $request->validate
            ([
                'label' => 'string',
                'dem_objet_g_id' => 'integer',
                'dem_objet_sg_id' => 'integer',
                'user_id' => 'integer'
            ]); 
            $dem = dem_objet::create($validated);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        // dd($request);

        $dem_objet_g = dem_objet_g::all();
        $dem_objet = dem_objet::all();
        $dem_objet_sg = dem_objet_sg::all();
        // $dateDepart = $request->date_depart;
        // dd($dateDepart);
        return view($request->view, [
            'dem_objet_gs'=>$dem_objet_g,
            'dem_objets'=>$dem_objet,
            'dem_objet_g_id'=>$request->classes,
            'dem_objet_id'=>$request->classes,
            'dem_objet_sg_id'=>$request->id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_titre(dem_objet_g $titres)
    {
        // 
        // dd($titres); 
        $user = User::with('userProfile')->find(auth()->user()->id);
        $request['user_id'] = auth()->user()->id;

            return view('edit.objet_demande_g',
            [
                'id' => $titres->id,
                'label' => $titres->label,
                // 'dem_objet'=> circuit_organe::with('objets')->get(), 
            ]);
        // return response()->json([
        //    "idDemande"=>$id, 
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_sous_objet(dem_objet_sg $sousobjet)
    {
        // 
        // dd($sousobjet); 
        $user = User::with('userProfile')->find(auth()->user()->id);
        $request['user_id'] = auth()->user()->id;

            return view('edit.objet_demande_sousobjet',
            [
                'id' => $sousobjet->id,
                'label' => $sousobjet->label,
                'dem_objet_g_id' => $sousobjet->dem_objet_g_id,
                'user_id' => $user,
                // 'dem_objet'=> circuit_organe::with('objets')->get(), 
            ]);
        // return response()->json([
        //    "idDemande"=>$id, 
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_objet(dem_objet $objet)
    {
        // 
        // dd($Circuit_organe); 
        $user = User::with('userProfile')->find(auth()->user()->id);
        $request['user_id'] = auth()->user()->id;

            return view('edit.objet_demande_objet',
            [
                'id' => $objet->id,
                'label' => $objet->label,
                'dem_objet_g_id' => $objet->dem_objet_g_id,
                'dem_objet_sg_id' => $objet->dem_objet_sg_id,
                'user_id' => $user
                // 'dem_objet'=> circuit_organe::with('objets')->get(), 
            ]);
        // return response()->json([
        //    "idDemande"=>$id, 
        // ]);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update_titre(Request $request, dem_objet_g $id)
    {
        //
        $user = User::with('userProfile')->find(auth()->user()->id);
        $validated  = $request->validate
        ([
            'label' => 'string',
            'user_id' => 'integer'
        ]); 

        // dd($Demande);

        $id->update($validated);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update_sous_objet(Request $request, dem_objet_sg $id)
    {
        //
        $user = User::with('userProfile')->find(auth()->user()->id);
        $validated  = $request->validate
        ([
            'label' => 'string',
            'dem_objet_g_id' => 'integer',
            'user_id' => 'integer',
        ]); 

        // dd($Demande);

        $id->update($validated);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update_objet(Request $request, dem_objet $id)
    {
        //
        $user = User::with('userProfile')->find(auth()->user()->id);
        $validated  = $request->validate
        ([
            'label' => 'string',
            'dem_objet_g_id' => 'integer',
            'dem_objet_sg_id' => 'integer',
            'user_id' => 'integer',
        ]); 

        // dd($Demande);

        $id->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_objet_g(dem_objet_g $id)
    {
        //
        $id->delete();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_objet_sg(dem_objet_sg $id)
    {
        //
        $id->delete();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_objet(dem_objet $id)
    {
        //
        $id->delete();
    }
}
