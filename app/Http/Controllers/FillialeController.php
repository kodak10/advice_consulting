<?php

namespace App\Http\Controllers;

use App\Models\filliale;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Validator;
use App\Traits\TracksUserActions;

class FillialeController extends Controller
{
    use TracksUserActions;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $filliales = filliale::all();
        // dd($filliales);
        return view($request->view, [
            'filliales'=>$filliales]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd($request);
        $user = User::with('userProfile')->find(auth()->user()->id);
        $request['user_id'] = auth()->user()->id;

        $validated  = $request->validate
            ([
                'label' => 'string'
            ]); 
            $filliales = filliale::create($validated);

            // filliale::create([
            //     'filliales_id' => $request['filliale_id'],
            // ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(filliale $filliale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(filliale $filliale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, filliale $filliale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(filliale $filliale)
    {
        //
    }
}
