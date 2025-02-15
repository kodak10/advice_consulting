<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;


class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('administration.pages.users.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'role' => 'required|in:Administrateur,Commercial,Comptable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'adresse' => $request->adresse,
            'password' => Hash::make('password'),
            'email_verified_at' => null,
            'status' => 'Actif',
        ]);

        // Assigner le rôle
        $user->assignRole($request->role);

        // Envoyer l'email de vérification
        //event(new Registered($user));
        $user->notify(new VerifyEmailNotification());

        return redirect()->route('dashboard.users.index')->with('success', 'Utilisateur ajouté avec succès. Un e-mail de vérification a été envoyé.');
    }

  
    // Méthode pour désactiver un utilisateur
    public function disable($id)
    {
        // Récupérer l'utilisateur
        $user = User::findOrFail($id);

        // Mettre à jour le statut en "inactif"
        $user->status = 'Inactif';
        $user->save();

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Utilisateur désactivé avec succès.');
    }


    public function show($id)
    {
        $user = User::findOrFail($id); // Recherche l'utilisateur avec l'ID fourni
        return view('administration.pages.users.profil', compact('user')); // Renvoie la vue avec les données de l'utilisateur
    }
    

    public function profile($id)
    {
        $user = User::findOrFail($id); // Recherche l'utilisateur avec l'ID fourni
        return view('administration.pages.users.profil', compact('user'));

    }
}
