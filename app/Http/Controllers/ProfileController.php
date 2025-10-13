<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\direction;
use App\Models\filliale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Helpers\ImageManager;
use App\Models\signature;
use Illuminate\Support\Facades\Hash;
use App\Traits\TracksUserActions;

class ProfileController extends Controller
{
    use TracksUserActions;
    /**
     * Display the user's profile form.
     */

    public function index(Request $request)
    {

        // dd($request->ajax());
        $profiles = UserProfile::with('user')->with('directions')->orderBy('created_at', 'desc')->get();
        $user = User::with('userProfile')->find(auth()->user()->id);
        // dd($profiles, $request);

        if ($request->ajax()) {
            $response = ([
                'profiles' => $profiles,
            ]);

            return response()->json($response);
        }
        return view('pages.profiles', ['profiles'=>$profiles, 'user'=>$user]);
    }

    public function create(Request $request)
    {
        // dd($request);
        $direction = direction::all();
        $filliales = filliale::all();
        $user = User::all();;

        return view($request->view, [
            'user'=>$user,
            'direction'=>$direction,
            'filliales' => $filliales,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'direction_id' => 'required|exists:directions,id',
            'filliale_id' => 'required|exists:filliales,id',
            'date_embauche' => 'required|date',
            'isEmbauche' => 'required|int:2',
            'role' => 'required|in:user,usercomptable,admin,superadmin'
        ]);

        $defaultPassword = 'password';

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'company_id' => 1,
            'password' => Hash::make($defaultPassword),
        ]);

        // Création du profil utilisateur
        UserProfile::create([
            'user_id' => $user->id,
            'phone_number' => $request->phone_number,
            'date_embauche' => $request->date_embauche,
            'directions_id' => $request->direction_id,
            'filliale_id' => $request->filliale_id,
            'isEmbauche'=> $request->isEmbauche,
            'company_id' => 1,
            'jour_de_conger' => 30,
            'pays_id' => 1,
            'ville' => "ABIDJAN",// Par défaut, on met que l'utilisateur est embauché
        ]);

        $user->assignRole($request->role);

        // Envoi de l'e-mail à l'utilisateur
        (new SendEmailController())->NotificationCreateuser($user, $defaultPassword);

        return response()->json(['message' => 'Utilisateur créé avec succès'], 201);
    }

    public function edit(Request $request): View
    {
        $profile = UserProfile::where('user_id', auth()->user()->id)->with('directions')->with('filliale')->with('picture')->first();

        // dd($profile->picture->image_profil_path);
        return view('profile.form', [
            'user' => $request->user(),
            'profile' => $profile,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function signature_update(Request $request)
    {
        // dd($request);


        $path = 'Signature/';

        if($file = $request->file('signture_numerique'))
        {
            $fileData = ImageManager::uploads($file,$path);
            $document = signature::updateOrcreate(
                [
                    'user_id' => auth()->user()->id,
                    'type'=>'Signature',
                    'nom'=>'signture_numerique_'.auth()->user()->id.'_'.date('Y-m-d H:m:s'),
                    'chemin_doc'=>$fileData['filePath'],
                ]
                );
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        // dd($request);
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();
        // dd($request,$user);
        $user->update([
            'password' => Hash::make($request->password),
            'first_login' => false,
        ]);

            // Log temporaire pour vérifier le résultat de la mise à jour
        if ($user->wasChanged()) {
            \Log::info('Password updated successfully. first_login set to false.');
        } else {
            \Log::error('Password update failed or no changes detected.');
        }
        $this->signature_update($request);

        return redirect()->route('demande')->withSuccess('Mot de passe et signature modifiés avec succès!'); // Redirige l'utilisateur après la mise à jour du mot de passe
    }

    public function destroyprofile(User $id)
    {
        //

        $id->userProfile()->delete();
        $id->delete();
    }

    public function statutactiver(User $id)
    {
        $user = User::findOrFail($id->id); // Utilisation correcte de l'ID
        $user->active = 1; // Forcer l'activation uniquement
        $user->save();

        return response()->json([
            'message' => 'L\'utilisateur a été activé avec succès.',
            'new_status' => $user->active
        ]);
    }
    public function statutadesactiver(User $id)
    {
        $user = User::findOrFail($id->id); // Utilisation correcte de l'ID
        $user->active = 0; // Forcer l'activation uniquement
        $user->save();

        return response()->json([
            'message' => 'L\'utilisateur a été desactivé avec succès.',
            'new_status' => $user->active
        ]);
    }
}
