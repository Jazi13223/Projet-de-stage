<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Secretary;


class CustomLoginController extends Controller
{
    // Formulaire de connexion étudiant
    public function showLoginEtudiant()
    {
        return view('auth.login-etudiant');
    }

    // Formulaire de connexion secrétaire
    public function showLoginSecretaire()
    {
        return view('auth.login-secretaire');
    }

    // Formulaire de connexion administrateur
    public function showLoginAdmin()
    {
        return view('auth.login-admin');
    }

    // Traitement de la connexion
   public function login(Request $request)
{
    // Étudiant : connexion avec matricule
    if ($request->has('matricule')) {
        $request->validate([
            'matricule' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

      $student = Student::where('matricule', $request->matricule)->first();

        if ($student && $student->user && Hash::check($request->password, $student->user->password) && $student->user->role === 'etudiant') {
            Auth::login($student->user);
            return redirect()->route('etudiants.dashboard');
        }

        return back()->withErrors(['login' => 'Matricule ou mot de passe incorrect.']);
    }

    // Autres rôles : connexion avec email
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        Auth::login($user);

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'secretaire':
                return redirect()->route('secretaire.dashboard');
            default:
                Auth::logout();
                return back()->withErrors(['login' => 'Accès non autorisé pour ce type d’utilisateur.']);
        }
    }

    return back()->withErrors(['login' => 'Email ou mot de passe incorrect.']);
}


    // Déconnexion
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
