<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Symfony\Component\HttpFoundation\Response;

class SecretaireMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       
        if (Auth::check() && Auth::user()->role === 'secretaire') {
            return $next($request);
        }

        return redirect()->route('login.secretaire')->withErrors(['access' => 'Accès réservé au secrétaire.']);
    }
}
