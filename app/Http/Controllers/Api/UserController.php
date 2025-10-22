<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        $users = User::with(['roles', 'pays'])->orderBy('name')->paginate($perPage);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string|max:255',
            'pays_id' => 'nullable|exists:pays,id',
            'status' => 'required|string',
            'user_id' => 'nullable|exists:6',
            'phone_number' => 'nullable|string|max:50',
            'date_embauche' => 'nullable|date|max:255',
            'directions_id' => 'nullable|string|max:255',
            'filliale_id' => 'nullable|string|max:255',
            'isEmbauche'=> 'nullable|string|max:255',
            'jour_de_conger' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'roles' => 'required|array',
            'roles.*' => ['string', Rule::exists('roles', 'name')]
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
            'pays_id' => $validated['pays_id'] ?? null,
            'status' => $validated['status'],
            'password' => Hash::make('password')
        ]);

        $profile = UserProfile::create([
            'user_id' => $user->id,
            'phone_number' => $validated['phone'] ?? null,
            'date_embauche' => $validated['date_embauche'] ?? null,
            'directions_id' => $validated['directions_id'] ?? null,
            'filliale_id' => $validated['filliale_id'] ?? 1,
            'pays_id' => $validated['pays_id'] ?? null,
            'isEmbauche'=> 1,
            'jour_de_conger' => 60,
            'ville' => $validated['ville'] ?? null,
        ]);

        $user->syncRoles($validated['roles']);

        $profile->save();

        return response()->json(['message' => 'Utilisateur créé avec succès', 'user' => $user->load('roles')]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string|max:255',
            'pays_id' => 'nullable|exists:pays,id',
            'status' => 'required|string',
            'roles' => 'required|array',
            'roles.*' => ['string', Rule::exists('roles', 'name')]
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
            'pays_id' => $validated['pays_id'] ?? null,
            'status' => $validated['status'],
        ]);

        $user->syncRoles($validated['roles']);

        return response()->json(['message' => 'Utilisateur mis à jour avec succès', 'user' => $user->load('roles')]);
    }

    public function destroy(User $user)
    {
        if ($user->profile) {
            $user->profile->delete();
        }

        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimée avec succès'
        ]);
    }
}
