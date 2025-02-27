<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;


class UsersController extends Controller
{

    public function __construct()
    {
        // Bloquer uniquement l'accès aux méthodes create et store pour les non "Daf" ou "Comptable"
        $this->middleware('role:Administrateur')->only(['index', 'storeUser', 'disable', 'activate', 'disable']);
    }

    public function index()
    {
        $users = User::all();
        return view('administration.pages.users.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|regex:/^[^\d]*$/',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|regex:/^[0-9]+$/|max:10',
            'adresse' => 'nullable|string|max:150',
            'role' => 'required|in:Administrateur,Daf,Commercial,Comptable',
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
            'pays_id' => $request->pays_id,
        ]);

        // Assigner le rôle
        $user->assignRole($request->role);

        $user->notify(new VerifyEmailNotification());

        return redirect()->route('dashboard.users.index')->with('success', 'Utilisateur ajouté avec succès. Un e-mail de vérification a été envoyé.');
    }

    public function disable($id)
    {
        $user = User::findOrFail($id);

        $user->status = 'Inactif';
        $user->save();

        return redirect()->back()->with('success', 'Utilisateur désactivé avec succès.');
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);

        $user->status = 'Actif';
        $user->save();

        return redirect()->back()->with('success', 'Utilisateur activé avec succès.');
    }


    public function show($id)
    {
        $user = User::findOrFail($id); 
        return view('administration.pages.users.profil', compact('user'));
    }
    

    public function profile()
    {
        $user = auth()->user();

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
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'adresse' => $request->adresse,
        ]);

        return back()->with('success', 'Infomation du profil mis à jour avec succès !');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Mot de passe mis à jour avec succès !');
    }

    public function exportCsv()
{
    $fileName = 'users_export.csv';
    $users = User::with(['pays', 'roles'])->get();

    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    return response()->stream(function () use ($users) {
        $handle = fopen('php://output', 'w');

        if ($handle === false) {
            throw new \Exception('Impossible d\'ouvrir php://output pour l\'écriture');
        }

        // Entêtes du fichier CSV
        fputcsv($handle, ['Pays', 'Nom', 'Email', 'Téléphone', 'Rôle', 'Statut']);

        // Ajout des données
        foreach ($users as $user) {
            fputcsv($handle, [
                $user->pays->name ?? 'Non renseigné',
                $user->name,
                $user->email,
                $user->phone ?? 'Non renseigné',
                $user->roles->first()->name ?? 'Aucun rôle',
                $user->status ?? 'Non renseigné'
            ]);
        }

        fclose($handle);
    }, 200, $headers);
}


}
