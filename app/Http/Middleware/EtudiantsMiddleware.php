<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Symfony\Component\HttpFoundation\Response;

class EtudiantsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est authentifié et a le rôle "etudiant"
        if (Auth::check() && Auth::user()->role === 'etudiant') {
            return $next($request); // L'utilisateur peut accéder à la page
        }

        // Si l'utilisateur n'est pas un étudiant, redirige vers la page de connexion
        return redirect()->route('login.etudiant');
    }
}
