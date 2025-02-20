<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Vérifie si l'utilisateur est connecté et a le bon rôle
        if (!auth()->check() || !auth()->user()->hasRole($role)) {
            abort(403, 'Accès interdit.');
        }

        return $next($request);
    }

}
