<?php

namespace App\Http\Controllers;

use App\Models\circuit_organe;
use App\Models\circuit_organe_user;
use App\Models\organe_validateur;
use App\Models\direction;
use App\Models\filliale;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\TracksUserActions;

class CircuitOrganeController extends Controller
{
    use TracksUserActions;
    /**
     * Display a listing of the resource.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        // dd($request);
        $direction = direction::all();
        $filliale = filliale::all();
        $user = User::all();
        $order = circuit_organe_user::where('circuit_organe_id', $request->classes)->count();
        // dd($order);
        return view($request->view, [
            'user'=>$user,
            'direction'=>$direction,
            'filliale' => $filliale,
            'order'=>$order+1,
            'circuit_organe_id'=>$request->classes,
        ]);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        // dd($request, is_int((int)($request->label)), (int)($request->label));
        $user = User::with('userProfile')->find(auth()->user()->id);
        $request['user_id'] = auth()->user()->id;
 
          
        if((int)($request->label) == 0)
        {
            $validated  = $request->validate
            ([
                'label' => 'string',
            ]);
            $circuitdirection = direction::create($validated);
            circuit_organe::create(['label' => $circuitdirection->id, 'filliale_id' => $request->filliale_id]);
        }

        else
        {
            $validated  = $request->validate
            ([
                'label' => 'string',
                'filliale_id' => 'integer',
            ]);
            $circuit = circuit_organe::create($validated);
            // dd($circuit);
        }
    }

    public function store_user_circuit(Request $request)
    {

        
        // $user = User::with('userProfile')->find(auth()->user()->id);
        // $request['user_id'] = auth()->user()->id;
        $validated = $request->validate
        ([
            'order' => 'integer',
            'circuit_organe_id' => 'integer',
            'user_id' => 'integer'
        ]);
        $circuit = circuit_organe_user::create($validated);
    }
    /**
     * Display the specified resource.
     */
    public function show(circuit_organe $circuit_organe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(circuit_organe $Circuit_organe)
    {
        // 
        // dd($Circuit_organe); 
            return view('edit.circuit_validateur',
            [
                'id' => $Circuit_organe->id,
                // premiere recupere les elements de dem_objet_g et dem_objet
                'selectcircuitva'=> circuit_organe::where('id', $Circuit_organe->id)->with('direction')->with('filliale')->get(),
                'directions'=> direction::all(),
                'filliales' => filliale::all(),
                // 'dem_objet'=> circuit_organe::with('objets')->get(), 
            ]);
        // return response()->json([
        //    "idDemande"=>$id, 
        // ]);
    }

    public function edit_circuit_user(circuit_organe_user $Circuit_organe_user)
    {
        // 
        // dd($Circuit_organe_user); 
            return view('edit.circuit_user',
            [
                'id' => $Circuit_organe_user->id,
                // premiere recupere les elements de dem_objet_g et dem_objet
                'selectcircuit'=> Circuit_organe_user::where('id', $Circuit_organe_user->id)->with('user')->get(),
                'user'=> User::all(),
                // 'label'=> $Circuit_organe_user->label,
                'order'=> $Circuit_organe_user->order,
                'circuit' => $Circuit_organe_user->circuit_organe_id
                // 'dem_objet'=> circuit_organe::with('objets')->get(), 
            ]);
        // return response()->json([
        //    "idDemande"=>$id, 
        // ]);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, circuit_organe $Circuit_organe)
    {
        //
        $user = User::with('userProfile')->find(auth()->user()->id);
        $validated  = $request->validate
        ([
            'label' => 'string',
            'filliale_id' => 'integer',
        ]); 

        // dd($Demande);

        $Circuit_organe->update($validated);
    }

    public function update_circuit_user(Request $request, circuit_organe_user $Circuit_organe_user)
    {
        //
        $user = User::with('userProfile')->find(auth()->user()->id);
        $validated  = $request->validate
        ([
            'order' => 'integer',
            'circuit_organe_id' => 'integer',
            'user_id' => 'integer'
        ]); 

        // dd($Demande);

        $Circuit_organe_user->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(circuit_organe $circuit_organe)
    {
        //
        $circuit_organe->delete();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_user(circuit_organe_user $circuit_organe_user)
    {
        // Récupérez l'ordre de l'enregistrement à supprimer
        // $orderToDelete = $circuit_organe_user->order;
    
        // Supprimez l'enregistrement
        $circuit_organe_user->delete();
    
        // Réorganisez les ordres pour les enregistrements restants
        $remainingRecords = circuit_organe_user::where('circuit_organe_id', $circuit_organe_user->circuit_organe_id)
            ->orderBy('order')
            ->get();

            $neworder=0;
        foreach ($remainingRecords as $record) {
            $neworder += 1;
            $record->order = $neworder;
            $record->save();
        }
    }

    // public function ajouterDirection(Request $request)
    // {
    //     // Ajoutez le nouvel élément à la table direction
    //     $direction = direction::create([
    //         'label' => $request->label
    //     ]);

    //     return response()->json(['id' => $direction->id]);
    // }

    // public function mettreAJourCircuitOrgane(Request $request)
    // {
    //     // Mettez à jour la table circuit_organe avec l'ID du nouvel élément direction
    //     circuit_organe::create([
    //         'label' => $request->directionId
    //     ]);

    //     return response()->json(['success' => true]);
    // }
}
