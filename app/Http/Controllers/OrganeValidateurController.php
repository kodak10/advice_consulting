<?php

namespace App\Http\Controllers;

use App\Models\organe_validateur;
use App\Models\circuit_organe;
use App\Models\type_organe_validateur;
use App\Models\type_demande;
use App\Models\filliale;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\TracksUserActions;

class OrganeValidateurController extends Controller
{
    use TracksUserActions;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        // dd($request);
        // $organes = organe_validateur::with('types', 'type_demande')->orderBy('created_at', 'desc')->get();
        // // $circuit = type_organe_validateur::with('direction')->orderBy('created_at', 'desc')->get();
        // // dd($organes);

        // $user = User::with('userProfile')->find(auth()->user()->id);
        // // $organes = UserProfile::with('directions_id')->find(auth()->user()->directions_id);

        // if($request->ajax()){
        //     return response()-> json([
        //         'organes' => $organes,
        //         // 'circuit' => $circuit,
        //     ]);
        // }
        // return view('pages.parametres', ['organes'=>$organes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        // dd($request);
        if (!is_null($request->classes)) {
            // code...
            $getFillialeId=explode(',', $request->classes)[1];
            $type = circuit_organe::with('direction')->where('filliale_id',$getFillialeId)->get();
        } else {
            // code...
            $type = circuit_organe::with('direction')->get();
        }
        
        $user = User::all();
        $type_demande = type_demande::all();
        $filliale = filliale::all();
        $order = type_organe_validateur::where('organe_validateur_id', $request->classes)->count();
        // dd($type);
        return view($request->view, [
            'user'=>$user,
            'type'=>$type,
            'filliales'=>$filliale,
            'type_dem' => $type_demande,
            'order'=>$order+1,
            'organe_validateur_id'=>$request->classes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        //  dd($request);
         $user = User::with('userProfile')->find(auth()->user()->id);
         $request['user_id'] = auth()->user()->id;
 
         $validated  = $request->validate
             ([
                 'label' => 'string',
                 'filliale_id' => 'integer'
             ]); 
             $circuit = organe_validateur::create($validated);
    }

    public function store_circuit(Request $request)
    {
        //

        // dd($request);
        //  $user = User::with('userProfile')->find(auth()->user()->id);
        //  $request['user_id'] = auth()->user()->id;
            $tableValue=explode(",",$request->organe_validateur_id);
            $request['organe_validateur_id']= $tableValue[0];
            $request['filliale_id']= $tableValue[1];
            $validated  = $request->validate
                ([
                'order' => 'integer',
                'organe_validateur_id' => 'integer',
                'circuit_organe_id' => 'integer',
                'filliale_id' => 'integer'
                ]);
            $circuit = type_organe_validateur::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(organe_validateur $organe_validateur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(organe_validateur $organe)
    {
        // 
        // dd($circuit_organe); 
            return view('edit.organe_validateur',
            [
                'id' => $organe->id,
                'selectorgane' =>  organe_validateur::where('id', $organe->id)->with('type_demande')->get(),
                'filliales' => filliale::all(),
                'type_demande'=> type_demande::all(),
            ]);
        // return response()->json([
        //    "idDemande"=>$id, 
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_organe(type_organe_validateur $organe_validateur)
    {
        // 
        // dd($organe_validateur); 
        return view('edit.circuit_organe_validateur',
        [
            'id' => $organe_validateur->id,
            'selectorgane'=> type_organe_validateur::where('id', $organe_validateur->id)->with('circuit')->get(),
            'types'=> circuit_organe::with('direction')->get(),
            'filliales' => filliale::all(),
            'order'=> $organe_validateur->order,
            'organe' => $organe_validateur->organe_validateur_id,
        ]);
        
    // return response()->json([
    //    "idDemande"=>$id, 
    // ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, organe_validateur $organe)
    {
        //
        $user = User::with('userProfile')->find(auth()->user()->id);
        $validated  = $request->validate
        ([
            'label' => 'string',
            'filliale_id' => 'integer',
        ]); 

        // dd($Demande);

        $organe->update($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_organe(Request $request, type_organe_validateur $organe_validateur)
    {
        //

        //  dd($request);
        $user = User::with('userProfile')->find(auth()->user()->id);
        $validated  = $request->validate
        ([
            'order' => 'integer',
            'organe_validateur_id' => 'integer',
            'circuit_organe_id' => 'integer'
        ]); 
     
        // dd($validated);
     
        $organe_validateur->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(organe_validateur $organe_validateur)
    {
        //
        $organe_validateur->delete();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_circuit(type_organe_validateur $circuit_organe)
    {
        // Récupérez l'ordre de l'enregistrement à supprimer
        // $orderToDelete = $circuit_organe_user->order;
    
        // Supprimez l'enregistrement
        $circuit_organe->delete();
    
        // Réorganisez les ordres pour les enregistrements restants
        $remainingRecords = type_organe_validateur::where('organe_validateur_id', $circuit_organe->organe_validateur_id)
            ->orderBy('order')
            ->get();

            $neworder=0;
        foreach ($remainingRecords as $record) {
            $neworder += 1;
            $record->order = $neworder;
            $record->save();
        }
    }
}
