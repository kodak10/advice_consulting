<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class ApiAuthController extends Controller
{
    /**
     * 🔹 Connexion API
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['success' => false, 'message' => 'Email ou mot de passe incorrect.'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('API_TOKEN')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie.',
            'user'    => $user,
            'token'   => $token
        ]);
    }

    /**
     * 🔹 Déconnexion API
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.'
        ]);
    }

    /**
     * 🔹 Envoi du lien de réinitialisation
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Un lien de réinitialisation a été envoyé à votre adresse e-mail si elle existe.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __($status)
        ], 400);
    }

    /**
     * 🔹 Réinitialisation du mot de passe
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'token'                 => 'required',
            'password'              => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Mot de passe réinitialisé avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __($status)
        ], 400);
    }
}
