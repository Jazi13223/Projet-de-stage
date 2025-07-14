<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Filiere;
use App\Models\Year;
use App\Models\Semester;
use App\Models\Inscription; 
use App\Models\Secretary;
use App\Models\Admin;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomRegisteredUserController extends Controller
{

    // ÉTUDIANT
    public function createEtudiant()
    {
        $filieres = Filiere::all(); // récupération des filières
        $years = Year::all();
        $semesters = Semester::all();
        return view('auth.register-etudiant', compact('filieres', 'years', 'semesters'));
    }

public function storeEtudiant(Request $request)
{
    // Validation complète
    $request->validate([
        'name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'matricule' => 'required|string|max:255|unique:students',
        'filiere_id' => 'required|exists:filieres,id',
        'year_id' => 'required|exists:years,id',
        'semester_id' => 'required|exists:semesters,id',
    ]);

    // Création de l'utilisateur
    $user = User::create([
        'name' => $request->name,
        'first_name' => $request->first_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'etudiant',
    ]);

    // Création du student
    $student = Student::create([
        'user_id' => $user->id,
        'matricule' => $request->matricule,
    ]);

    // Création de l’inscription
    Inscription::create([
        'student_id' => $student->id,
        'filiere_id' => $request->filiere_id,
        'year_id' => $request->year_id,
        'semester_id' => $request->semester_id,
        'date_inscription' => now()->toDateString(),
    ]);

    return redirect()->route('login.etudiant')->with('success', 'Inscription réussie ! Connectez-vous.');
}


    // ADMINISTRATEUR
    public function createAdmin()
    {
        return view('auth.register-admin');
    }

    public function storeAdmin(Request $request)
    {
        // Validation pour les administrateurs : matricule et filière ne sont pas requis
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        Admin::create(['user_id' => $user->id]);

        return redirect()->route('login.admin')->with('success', 'Inscription réussie ! Connectez-vous.');
    }

    public function createSecretaire()
    {
    return view('auth.register-secretaire');
    }

    public function storeSecretaire(Request $request)
{
    // Validation des champs
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Création de l'utilisateur
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'secretaire',
    ]);

    // Si tu as une table "secretaries", ajoute ceci :
     Secretary::create(['user_id' => $user->id]);

    return redirect()->route('login.secretaire')->with('success', 'Inscription réussie ! Connectez-vous.');
}


}
