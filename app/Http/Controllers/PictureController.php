<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use Illuminate\Http\Request;
use App\Traits\TracksUserActions;

class PictureController extends Controller
{
    use TracksUserActions;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $images = Picture::all();
        // dd($images);
        return view('profile.edit', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('images.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        //
        // dd($request, $request->file('path'));
        // Valide les données du formulaire
        $request->validate([
            // 'user_id' => 'required',
            'LogoPicture' => 'nullable|image|mimes:jpeg,png,gif',
            'UserPicture' => 'nullable|image|mimes:jpeg,png,gif',
            'caption' => 'nullable|string',
        ]);

        // Gère le téléchargement et l'enregistrement de l'image
        if ($request->hasFile('LogoPicture')) {
            $imageProfilPath=Picture::where('user_id', auth()->user()->id)->value('image_profil_path');
            $imagePath = $request->file('LogoPicture')->store('picture');
        } 
        if ($request->hasFile('UserPicture')) {
            $imagePath = Picture::where('group_id', auth()->user()->company_id)->value('image_path');
            $imageProfilPath = $request->file('UserPicture')->store('Userpicture');
        }

        // dd($imageProfilPath, $imagePath);
        // dd($imagePath);
        // Crée une nouvelle image dans la base de données
        Picture::updateOrcreate(
            [
                'user_id' => auth()->user()->id,
                'group_id' => auth()->user()->company_id,
            ],
            [
                'image_path' => $imagePath,
                'image_profil_path' => $imageProfilPath,
                // 'caption' => $request->input('caption'),
            ]
        );
        return redirect()->route('profile.edit')->with('success', 'L\'image a été ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Picture $picture)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Picture $picture)
    {
        //
        $image = Picture::findOrFail($picture);
        return view('profile.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Picture $picture)
    {
        //
        // Valide les données du formulaire
        $request->validate([
            'caption' => 'nullable|string',
        ]);

        // Trouve l'image existante
        $image = Picture::findOrFail($picture);

        // Met à jour les données de l'image
        $image->update([
            'caption' => $request->input('caption'),
        ]);

        return redirect()->route('images.index')->with('success', 'L\'image a été mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Picture $picture)
    {
        //
        $image = Picture::findOrFail($picture);
        $image->delete();
        return redirect()->route('profile.edit')->with('success', 'L\'image a été supprimée avec succès.');
    }
}
