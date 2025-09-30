<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
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

        $user->syncRoles($validated['roles']);

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
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimée avec succès'
        ]);
    }
}
