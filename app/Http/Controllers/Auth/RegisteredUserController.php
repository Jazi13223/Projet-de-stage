<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student; 

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // Validation des données envoyées par le formulaire
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'matricule' => ['required', 'string', 'max:255'],
            'filiere' => ['required', 'string', 'max:255'],  // Validation du champ 'filiere'
        ]);

        // Création de l'utilisateur avec les informations validées
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'matricule' => $validated['matricule'],
            'filiere' => $validated['filiere'],  // Ajoute 'filiere'
            'role' => 'etudiant',  // Par défaut, on attribue le rôle 'etudiant'
        ]);

        // Connexion automatique de l'utilisateur après l'inscription
        auth()->login($user);

        // Redirection vers la page de tableau de bord
        return redirect()->route('dashboard');
    }
}