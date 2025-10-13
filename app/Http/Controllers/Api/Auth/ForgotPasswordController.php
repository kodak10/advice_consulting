<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validation de l'email
        $request->validate([
            'email' => 'required|email',
        ]);

        // Chercher l'utilisateur avec l'email fourni
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Générer un token pour réinitialiser le mot de passe
            $token = Password::createToken($user);

            // Envoyer la notification avec le token
            $user->notify(new ResetPasswordNotification($token));
        }

        return back()->with('status', 'Nous avons envoyé un lien de réinitialisation par email.');
    }
}
