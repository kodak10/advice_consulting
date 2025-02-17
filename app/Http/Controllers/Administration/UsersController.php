<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

    // Méthode pour Activer un utilisateur
    public function activate($id)
    {
        // Récupérer l'utilisateur
        $user = User::findOrFail($id);

        // Mettre à jour le statut en "Actif"
        $user->status = 'Actif';
        $user->save();

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Utilisateur activé avec succès.');
    }


    public function show($id)
    {
        $user = User::findOrFail($id); // Recherche l'utilisateur avec l'ID fourni
        return view('administration.pages.users.profil', compact('user')); // Renvoie la vue avec les données de l'utilisateur
    }
    

    public function profile()
    {
        $user = auth()->user(); // Récupère l'utilisateur connecté

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
        return view('administration.pages.users.profil', compact('user'));

    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);

        $user = auth()->user();

        // Supprimer l'ancienne image si elle existe dans le stockage
        if ($user->image && Storage::exists('images/profil/' . $user->image)) {
            Storage::delete('images/profil/' . $user->image);
        }

        // Générer un nom unique pour l'image
        $imageName = time() . '.' . $request->image->extension();

        // Sauvegarder la nouvelle image dans le stockage
        $request->image->storeAs('images/profil', $imageName, 'public');

        // Mettre à jour le champ image de l'utilisateur
        $user->update(['image' => 'storage/images/profil/' . $imageName]);

        return back()->with('success', 'Photo de profil mise à jour avec succès !');
    }



    public function resetProfileImage()
    {
        $user = auth()->user();

        // Supprimer l'ancienne image si elle existe
        if ($user->image && Storage::exists('public/profiles/' . $user->image)) {
            Storage::delete('public/profiles/' . $user->image);
        }
        
        // Réinitialiser à l'image par défaut
        $user->image ='storage/images/user.jpg';
        $user->save();
        
        return back()->with('success', 'Photo de profil réinitialisée avec succès !');
        
    }

    public function updateInformation(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ]);

        // Mettre à jour les informations de l'utilisateur
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'adresse' => $request->adresse,
        ]);

        // Rediriger avec un message de succès
        return back()->with('success', 'Infomation du profil mis à jour avec succès !');
    }

    public function updatePassword(Request $request)
    {
        // Validation des données
        $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        // Vérifier que le mot de passe actuel est correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Rediriger avec un message de succès
        return back()->with('success', 'Mot de passe mis à jour avec succès !');
    }

}
