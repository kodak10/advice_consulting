<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Vérifie si le statut de l'utilisateur est actif
            if ($user->status !== 'Actif') {
                // Si l'utilisateur n'est pas actif, déconnecte-le et redirige-le
                Auth::logout();
                return redirect()->route('login')->withErrors(['error' => 'Votre compte est désactivé. Veuillez contacter l\'administration.']);
            }
        }

        return $next($request);
    }
}
