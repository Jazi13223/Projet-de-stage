<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Student;
use App\Models\Filiere;
use App\Models\Inscription;
use App\Models\Admin;
use App\Models\Secretary;
use App\Models\Year;
use App\Models\Log;
use App\Models\Semester;
use App\Models\Ue;
use App\Models\UeAssignment;
use App\Models\Ecue;
use App\Models\Note;
use App\Models\Reclamation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    // Tableau de bord de l'administrateur
  public function manageUsers()
{
    // Charger tous les étudiants avec leurs utilisateurs et inscriptions (filière incluse)
    $etudiants = Student::with(['user', 'derniereInscription.filiere'])->get();

    // Regrouper les étudiants par nom de filière
    $etudiantsGroupes = [];

    foreach ($etudiants as $etudiant) {
        $filiereName = optional($etudiant->derniereInscription->filiere)->name ?? 'Non renseignée';

        if (!isset($etudiantsGroupes[$filiereName])) {
            $etudiantsGroupes[$filiereName] = [];
        }

        $etudiantsGroupes[$filiereName][] = $etudiant;
    }

    // Récupérer les vraies listes
    $filieres = Filiere::all();        // Pour les <select>
    $years = Year::all();
    $semesters = Semester::all();

    // Envoyer toutes les données à la vue
    return view('admin.users', [
        'etudiantsGroupes' => $etudiantsGroupes, // Tableau regroupé
        'filieres' => $filieres,                 // Liste des filières
        'years' => $years,
        'semesters' => $semesters,
    ]);
}


    public function storeEtudiant(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'matricule' => 'required|string|unique:students,matricule',
        'filiere' => 'required|string|exists:filieres,name',
        'password' => 'required|string|min:6|confirmed',
        'year_id' => 'required|exists:years,id',
        'semester_id' => 'required|exists:semesters,id',
    ]);

    DB::beginTransaction();

    try {
        $user = User::create([
            'name' => $request->name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'etudiant',
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'matricule' => $request->matricule,
        ]);

        $filiere = Filiere::where('name', $request->filiere)->first();

        Inscription::create([
            'student_id' => $student->id,
            'filiere_id' => $filiere->id,
            'year_id' => 1, // à adapter
            'semester_id' => 1, // à adapter
            'date_inscription' => now(),
        ]);

        DB::commit();

        return back()->with('success', 'Étudiant ajouté avec succès.');

    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Erreur lors de l’ajout : ' . $e->getMessage());
    }
}
    public function updateEtudiant(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'matricule' => 'required|string|unique:students,matricule,' . $id . ',user_id',
        'filiere' => 'required|string|exists:filieres,name',
    ]);

    DB::beginTransaction();

    try {
        $student = Student::where('user_id', $id)->firstOrFail();
        $user = $student->user;

        $user->update([
            'name' => $request->name,
            'first_name' => $request->first_name,
            'role' => 'etudiant', // Assurez-vous que le rôle est bien déf
            'email' => $request->email,
        ]);

        $student->update([
            'matricule' => $request->matricule,
        ]);

        $filiere = Filiere::where('name', $request->filiere)->first();

        $inscription = $student->derniereInscription;
        if ($inscription) {
            $inscription->update([
                'filiere_id' => $filiere->id,
            ]);
        }

        DB::commit();
        return back()->with('success', 'Étudiant modifié avec succès.');

    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
    }
}


    public function deleteEtudiant($id)
{
    DB::beginTransaction();

    try {
        $student = Student::where('user_id', $id)->firstOrFail();
        $user = $student->user;

        // Supprimer les inscriptions
        $student->inscriptions()->delete();

        // Supprimer student et user
        $student->delete();
        $user->delete();

        DB::commit();

        return back()->with('success', 'Étudiant supprimé avec succès.');
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
    }
}

// Gérer les filières
public function manageFilieres()
{
    // Charger les filières avec leurs UEs et ECUEs associés
    $filieres = Filiere::with(['ue_assignments.ue.ecues'])->get();
    $ues = Ue::with('ecues')->get();

    // Retourner la vue avec les données des filières et des UEs
    return view('admin.filiere', compact('filieres', 'ues'));
}

public function storeFiliere(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Créer une nouvelle filière
    Filiere::create(['name' => $request->name]);
    return back()->with('success', 'Filière ajoutée avec succès.');
}
public function updateFiliere(Request $request)
{
   
    $request->validate([
        'id' => 'required|exists:filieres,id',
        'name' => 'required|string|max:255',
        'ue_name' => 'required|string|max:255',
        'coefficient' => 'required|numeric|min:1',
        'ecue1_name' => 'nullable|string|max:255',
        'ecue1_coefficient' => 'nullable|numeric|min:1',
        'ecue2_name' => 'nullable|string|max:255',
        'ecue2_coefficient' => 'nullable|numeric|min:1',
    ]);

    $filiere = Filiere::findOrFail($request->id);
    $filiere->update(['name' => $request->name]);


    // Créer une nouvelle UE
    $ue = Ue::create([
        'name' => $request->ue_name,
        'coefficient' => $request->coefficient,
    ]);

    $ueAssignment = UeAssignment::create([
        'filiere_id' => $filiere->id,
        'ue_id' => $ue->id,
        'coefficient' => $request->coefficient,
    ]);

    // Créer les deux ECUEs facultatifs
    if ($request->filled('ecue1_name') && $request->filled('ecue1_coefficient')) {
        $ecue1 = Ecue::create([
            'name' => $request->ecue1_name,
            'ue_id' => $ue->id,
            'coefficient' => $request->ecue1_coefficient,
        ]);

        UeEcueAssignment::create([
            'ue_assignment_id' => $ueAssignment->id,
            'ecue_id' => $ecue1->id,
            'coefficient' => $request->ecue1_coefficient,
        ]);
    }

    if ($request->filled('ecue2_name') && $request->filled('ecue2_coefficient')) {
        $ecue2 = Ecue::create([
            'name' => $request->ecue2_name,
            'ue_id' => $ue->id,
            'coefficient' => $request->ecue2_coefficient,
        ]);

        UeEcueAssignment::create([
            'ue_assignment_id' => $ueAssignment->id,
            'ecue_id' => $ecue2->id,
            'coefficient' => $request->ecue2_coefficient,
        ]);
    }

    return back()->with('success', 'Filière mise à jour avec succès.');
}

public function destroyFiliere($id)
{
    try {
        // Trouver la filière et supprimer ses affectations
        $filiere = Filiere::findOrFail($id);
        $filiere->ue_assignments()->delete(); // Supprimer les affectations d'UE
        $filiere->delete(); // Supprimer la filière

        return back()->with('success', 'Filière supprimée avec succès.');
    } catch (\Exception $e) {
        return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
    }
}
public function assignUeToFiliere(Request $request, $filiere_id)
{
    $request->validate([
        'ue_name' => 'required|string|max:255',
        'coefficient' => 'required|numeric|min:1',
        'ecue1_name' => 'nullable|string|max:255',
        'ecue1_coefficient' => 'nullable|numeric|min:1',
        'ecue2_name' => 'nullable|string|max:255',
        'ecue2_coefficient' => 'nullable|numeric|min:1',
    ]);

    // Créer une nouvelle UE
    $ue = Ue::create([
        'name' => $request->ue_name,
    ]);

    // Affecter cette UE à la filière avec le coefficient
    $ueAssignment = UeAssignment::create([
        'filiere_id' => $filiere_id,
        'ue_id' => $ue->id,
        'coefficient' => $request->coefficient,
    ]);

    // Créer les ECUEs facultativement
    if ($request->filled('ecue1_name') && $request->filled('ecue1_coefficient')) {
        $ecue1 = Ecue::create([
            'name' => $request->ecue1_name,
            'ue_id' => $ue->id,
            'coefficient' => $request->ecue1_coefficient,
        ]);

        UeEcueAssignment::create([
            'ue_assignment_id' => $ueAssignment->id,
            'ecue_id' => $ecue1->id,
            'coefficient' => $request->ecue1_coefficient,
        ]);
    }

    if ($request->filled('ecue2_name') && $request->filled('ecue2_coefficient')) {
        $ecue2 = Ecue::create([
            'name' => $request->ecue2_name,
            'ue_id' => $ue->id,
            'coefficient' => $request->ecue2_coefficient,
        ]);

        UeEcueAssignment::create([
            'ue_assignment_id' => $ueAssignment->id,
            'ecue_id' => $ecue2->id,
            'coefficient' => $request->ecue2_coefficient,
        ]);
    }

    return back()->with('success', 'Nouvelle UE assignée à la filière avec succès.');
}


    // Gérer les secrétaires
     public function manageSecretaire()
    {
        return view('admin.manage-secretaire');
    }



    // Statistiques
    public function statistics()
    {
        return view('admin.statistics');
    }

    //Profil de l'administrateur
    public function profil(){
        return view('admin.profil');
    }

     public function index(){
        return view('admin.dashboard');
    }

    

}
