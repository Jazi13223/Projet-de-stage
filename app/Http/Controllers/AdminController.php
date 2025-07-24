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
use App\Models\Notification;
use App\Models\Semester;
use App\Models\Ue;
use App\Models\UeAssignment;
use App\Models\UeEcueAssignment;
use App\Models\Ecue;
use App\Models\Note;
use App\Models\Reclamation;
use App\Models\Validation;
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
        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'etudiant',
        ]);

        // Création de l'étudiant
        $student = Student::create([
            'user_id' => $user->id,
            'matricule' => $request->matricule,
        ]);

        $filiere = Filiere::where('name', $request->filiere)->first();

        // Enregistrement de l'inscription
        Inscription::create([
            'student_id' => $student->id,
            'filiere_id' => $filiere->id,
            'year_id' => 1, // à adapter
            'semester_id' => 1, // à adapter
            'date_inscription' => now(),
        ]);

        DB::commit();

        // Nom complet de l'étudiant
        $fullName = trim($user->name . ' ' . $user->first_name);

        // Log de l'ajout
        $this->logAction('Ajout', "Ajout de l'étudiant {$fullName}");

        // Notification de succès
        $this->createNotification("L'étudiant {$fullName} a été ajouté avec succès.", 'success');

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
        // Récupérer l'étudiant et l'utilisateur
        $student = Student::where('user_id', $id)->firstOrFail();
        $user = $student->user;

        // Mise à jour des informations de l'utilisateur
        $user->update([
            'name' => $request->name,
            'first_name' => $request->first_name,
            'role' => 'etudiant',
            'email' => $request->email,
        ]);

        // Mise à jour de l'étudiant
        $student->update([
            'matricule' => $request->matricule,
        ]);

        $filiere = Filiere::where('name', $request->filiere)->first();

        // Mise à jour de l'inscription de l'étudiant
        $inscription = $student->derniereInscription;
        if ($inscription) {
            $inscription->update([
                'filiere_id' => $filiere->id,
            ]);
        }

        DB::commit();

        // Nom complet de l'étudiant
        $fullName = trim($user->name . ' ' . $user->first_name);

        // Log de la modification
        $this->logAction('Modification', "Modification des informations de l'étudiant {$fullName}");

        // Notification de succès
        $this->createNotification("L'étudiant {$fullName} a été modifié avec succès.", 'info');

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
        // Récupérer l'étudiant et son utilisateur
        $student = Student::where('user_id', $id)->firstOrFail();
        $user = $student->user;

        // Supprimer les inscriptions
        $student->inscriptions()->delete();

        // Supprimer l'étudiant et son utilisateur
        $student->delete();
        $user->delete();

        DB::commit();

        // Nom complet de l'étudiant
        $fullName = trim($user->name . ' ' . $user->first_name);

        // Log de la suppression
        $this->logAction('Suppression', "Suppression de l'étudiant {$fullName}");

        // Notification de suppression
        $this->createNotification("L'étudiant {$fullName} a été supprimé avec succès.", 'danger');

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
    $semesters = Semester::all();
    $years = Year::all();

    // Retourner la vue avec les données des filières et des UEs
    return view('admin.filiere', compact('filieres', 'semesters', 'years'));
}

public function storeFiliere(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Créer une nouvelle filière
    Filiere::create(['name' => $request->name]);
    // Log de l'ajout
    $this->logAction('Ajout', "Ajout de la filière {$request->name}");
    // Notification de succès
    $this->createNotification("Filière {$request->name} ajoutée avec succès.", 'success');
    return back()->with('success', 'Filière ajoutée avec succès.');
}
public function updateFiliere(Request $request)
{
    $request->validate([
        'id' => 'required|exists:filieres,id',
        'assignment_id' => 'nullable|exists:ue_assignments,id',
        'name' => 'required|string|max:255',
        'ue_name' => 'required|string|max:255',
        'coefficient' => 'required|numeric|min:1',
        'ecue1_name' => 'nullable|string|max:255',
        'ecue1_coefficient' => 'nullable|numeric|min:1',
        'ecue2_name' => 'nullable|string|max:255',
        'ecue2_coefficient' => 'nullable|numeric|min:1',
        'semester_id' => 'required|exists:semesters,id',
        'year_id' => 'required|exists:years,id',
    ]);

    $filiere = Filiere::findOrFail($request->id);
    $filiere->update(['name' => $request->name]);

    // Modifier l'UE déjà affectée (si assignment_id transmis)
    if ($request->filled('assignment_id')) {
        $assignment = UeAssignment::findOrFail($request->assignment_id);
        $ue = $assignment->ue;

        $ue->update([
            'name' => $request->ue_name,
            'coefficient' => $request->coefficient,
            'semester_id' => $request->semester_id,
            'year_id' => $request->year_id,
        ]);
 // Mise à jour du coefficient dans l'assignation UE-Filière
    $assignment->update(['coefficient' => $request->coefficient]);

    // On récupère ici l'assignation correctement
    $ueAssignment = $assignment;
        
    } else {
        // Créer ou mettre à jour l’UE par nom
        $ue = Ue::firstOrCreate(
            ['name' => $request->ue_name],
            [
                'coefficient' => $request->coefficient,
                'semester_id' => $request->semester_id,
                'year_id' => $request->year_id,
            ]
        );

        $ueAssignment = UeAssignment::firstOrCreate(
            ['filiere_id' => $filiere->id, 'ue_id' => $ue->id],
            ['coefficient' => $request->coefficient]
        );
    }

    // === ECUE 1 ===
    if ($request->filled('ecue1_name') && $request->filled('ecue1_coefficient')) {
        $ecue1 = Ecue::updateOrCreate(
            ['name' => $request->ecue1_name, 'ue_id' => $ue->id],
            ['coefficient' => $request->ecue1_coefficient]
        );

        // Mettre à jour ou créer l'assignation de l'ECUE
        UeEcueAssignment::updateOrCreate(
            ['ue_assignment_id' => $ueAssignment->id, 'ecue_id' => $ecue1->id],
            ['coefficient' => $request->ecue1_coefficient]
        );
    }

    // === ECUE 2 ===
    if ($request->filled('ecue2_name') && $request->filled('ecue2_coefficient')) {
        $ecue2 = Ecue::updateOrCreate(
            ['name' => $request->ecue2_name, 'ue_id' => $ue->id],
            ['coefficient' => $request->ecue2_coefficient]
        );

        // Mettre à jour ou créer l'assignation de l'ECUE
        UeEcueAssignment::updateOrCreate(
            ['ue_assignment_id' => $ueAssignment->id, 'ecue_id' => $ecue2->id],
            ['coefficient' => $request->ecue2_coefficient]
        );
    }
    // Log de la mise à jour
    $this->logAction('Mise à jour', "Mise à jour de la filière {$filiere->name} et de l'UE {$ue->name}");
    // Notification de succès
    $this->createNotification("Filière {$filiere->name} et UE {$ue->name} mises à jour avec succès.", 'success');
    return back()->with('success', 'Filière et UE mises à jour avec succès.');
}

public function destroyFiliere($id)
{
    try {
        // Trouver la filière et supprimer ses affectations
        $filiere = Filiere::findOrFail($id);
        $filiere->ue_assignments()->delete(); // Supprimer les affectations d'UE
        $filiere->delete(); // Supprimer la filière
        // Log de la suppression
        $this->logAction('Suppression', "Suppression de la filière {$filiere->name}");
        // Notification de succès
        $this->createNotification("Filière {$filiere->name} supprimée avec succès.", 'danger');
        return back()->with('success', 'Filière supprimée avec succès.');
    } catch (\Exception $e) {
        return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
    }
}


public function assignUeToFiliere(Request $request, $filiere_id)
{
    
    $filiere = Filiere::findOrFail($filiere_id);
    // Récupérer l'utilisateur connecté
    $user = auth()->user();
    
    // Vérifier si l'utilisateur existe et est authentifié
    if (!$user) {
        return redirect()->route('login')->with('error', 'Utilisateur non authentifié');
    }


    $request->validate([
        'ue_name' => 'required|string|max:255',
        'coefficient' => 'required|numeric|min:1',
        'ecue1_name' => 'nullable|string|max:255',
        'ecue1_coefficient' => 'nullable|numeric|min:1',
        'ecue2_name' => 'nullable|string|max:255',
        'ecue2_coefficient' => 'nullable|numeric|min:1',
        'semester_id' => 'required|exists:semesters,id',
        'year_id' => 'required|exists:years,id',
    ]);

    // Créer une nouvelle UE
    $ue = Ue::create([
        'name' => $request->ue_name,
        'coefficient' => $request->coefficient,
        'semester_id' => $request->semester_id, // Assurez-vous que le champ
        'year_id' => $request->year_id, // Assurez-vous que le champ existe dans la table Ue
        'admin_id' => $user->role === 'admin' ? $user->id : null, // Remplace 'admin' par la valeur correspondant à l'admin
        'secretary_id' => $user->role === 'secretary' ? $user->id : null, // Remplace 'secretary' par la valeur correspondant au secrétaire
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
            'admin_id' => $user->role === 'admin' ? $user->id : null,  // Si admin, on associe l'ID admin
            'secretary_id' => $user->role === 'secretary' ? $user->id : null,  // Si secrétaire, on associe l'ID secrétaire
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
            'admin_id' => $user->role === 'admin' ? $user->id : null,  // Si admin, on associe l'ID admin
            'secretary_id' => $user->role === 'secretary' ? $user->id : null,  // Si secrétaire, on associe l'ID secrétaire
        
        ]);

        UeEcueAssignment::create([
            'ue_assignment_id' => $ueAssignment->id,
            'ecue_id' => $ecue2->id,
            'coefficient' => $request->ecue2_coefficient,
        ]);
    }
     // Log de l'assignation de l'UE
    $this->logAction('Assignation', "Assignation de l'UE {$ue->name} à la filière {$filiere->name}");

    // Notification de succès
    $this->createNotification("L'UE {$ue->name} a été assignée à la filière {$filiere->name} avec succès.", 'success');

    return back()->with('success', 'Nouvelle UE assignée à la filière avec succès.');
}

public function destroy($id)
{
    $ue = Ue::findOrFail($id);

    // Supprimer l'UE et ses ECUEs associés (si tu veux les supprimer aussi)
    $ue->ecues()->delete();
    $ue->delete();

    
        // Log de la suppression
       $this->logAction( 'Suppression', "Suppression de l'UE {$ue->name}");


        // Notification de suppression
        $this->createNotification( "L'UE {$ue->name} a été supprimée avec succès.", 'danger');

    return redirect()->back()->with( 'UE supprimée avec succès.', 'success');
}



    // Gérer les secrétaires
   // Liste des secrétaires avec leurs notifications et logs
    public function manageSecretaire()
    {
       $secretaires = Secretary::with('user')->get(); 
        $logs = Log::orderByDesc('created_at')->limit(10)->get(); // Dernières 5 actions
        $notifications = Notification::all(); // Tous les types de notifications système

        return view('admin.manage-secretaire', compact('secretaires', 'logs', 'notifications'));
    }

    // Récupérer les logs d'un secrétaire
    public function getSecretaireLogs($id)
    {
        $logs = Log::where('secretary_id', $id)
                   ->orderByDesc('created_at')
                   ->take(30)
                   ->get(['action', 'description', 'created_at']);

        // Formater la date si tu veux
        $logs->transform(function ($log) {
            $log->created_at = $log->created_at->format('d/m/Y H:i');
            return $log;
        });

        return response()->json($logs);
    }

    // Ajouter un secrétaire
   
public function storeSecretaire(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'statut' => 'required|in:Actif,Inactif',
    ]);

    // 1. Création de l'utilisateur
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'secretaire',
    ]);

    // 2. Création du secrétaire lié
    $secretaire = Secretary::create([
        'user_id' => $user->id,
        'statut' => $request->statut,
    ]);

    // 3. Ajout dans les logs
   $this->logAction('Ajout',"Ajout du secrétaire {$user->name}");


    // 4. Notification système
    $this->createNotification("Secrétaire {$user->name} ajouté avec succès.", 'success');

    return redirect()->route('admin.manage-secretaire')->with('success', 'Secrétaire ajouté avec succès.');
}
    // Modifier un secrétaire
   public function updateSecretaire(Request $request)
{  
    

    // Validation des données reçues
    $request->validate([
        'id' => 'required|exists:secretaries,id',  // Vérifie que l'ID du secrétaire existe dans la table 'secretaries'
        'name' => 'required|string|max:255',       // Utilise 'name' au lieu de 'nom'
        'email' => 'required|email',               // Validation de l'email
        'statut' => 'required|in:Actif,Inactif',   // Validation du statut
    ]);

    // Recherche du secrétaire dans la base de données
    $secretaire = Secretary::findOrFail($request->id);
    // Vérification si l'email est déjà utilisé par un autre utilisateur
    $existingUser = User::where('email', $request->email)
        ->where('id', '!=', $secretaire->user_id) // Exclut le secrétaire actuel
        ->first();    
    if ($existingUser) {
        return redirect()->back()->withErrors(['email' => 'Cet email est déjà utilisé par un autre utilisateur.']);
    }  

    // Mise à jour de l'utilisateur lié dans la table 'users'
    $user = $secretaire->user; // Supposons que Secretary a une relation avec 'user'
    if ($user) {
        $user->update([
            'name' => $request->name,   // Mise à jour du nom de l'utilisateur
            'email' => $request->email, // Mise à jour de l'email de l'utilisateur
        ]);


    // Mise à jour des informations du secrétaire
    $secretaire->update([
        'statut' => $request->statut,
    ]);

    }

    // Ajoute un log pour cette action
   $this->logAction('Modification', "Modification des informations du secrétaire {$user->name}");


    // Créer une notification pour l'admin
    $this->createNotification("Secrétaire {$secretaire->user->name} modifié avec succès.", 'info');

    // Retourne à la liste des secrétaires avec un message de succès
    return redirect()->route('admin.manage-secretaire')->with('success', 'Secrétaire modifié avec succès.');
}


    // Supprimer un secrétaire
    public function destroySecretaire($id)
    {
        $secretaire = Secretary::findOrFail($id);

        // Ajoute un log pour cette action
       $this->logAction('Suppression', "Suppression du secrétaire {$secretaire->user->name}");


        // Créer une notification pour l'admin
        $this->createNotification("Secrétaire {$secretaire->user->name} supprimé.", 'danger');

        $secretaire->delete();

        return redirect()->route('admin.manage-secretaire')->with('success', 'Secrétaire supprimé avec succès.');
    }

    // Créer une notification
    public function createNotification($message, $type = 'info')
    {
    $userId = auth()->id(); // Récupérer l'ID de l'utilisateur authentifié
        // Vérifier si l'utilisateur est un étudiant
    $studentId = null;
    if (auth()->user()->role === 'etudiant') {
        $studentId = auth()->user()->student->id;  // Récupérer l'ID de l'étudiant, si l'utilisateur est un étudiant
    }
        // Vérifier si l'utilisateur est un secrétaire
    $secretaryId = null;
    if (auth()->user()->role === 'secretaire') {
        $secretaryId = auth()->id(); // Récupérer l'ID du   secrétaire, si l'utilisateur est un secrétaire
    }
        // Vérifier si l'utilisateur est un admin
    $adminId = null;
    if (auth()->user()->role === 'admin') { 
        $adminId = auth()->id(); // Récupérer l'ID de l'admin, si l'utilisateur est un admin
    }        

        // Créer une nouvelle notification
        Notification::create([
            'user_id' => $userId,
            'admin_id' => $adminId,  // Cette ligne enregistre l'ID de l'admin (si l'utilisateur est un admin)
            'secretary_id' => $secretaryId,  // Cette ligne enregistre l
            'student_id' => $studentId,  // Cette ligne enregistre l'ID de l'étudiant (si l'utilisateur est un étudiant)
            'message' => $message,
            'type' => $type,
            'is_read' => false, // Par défaut, la notification est non lue
        ]);
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

    public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        // Si les mots de passe sont remplis, ils doivent correspondre
        'old_password' => 'nullable|current_password',
        'new_password' => 'nullable|min:8|confirmed',
    ]);

    $user = Auth::user();

    // Mettre à jour le nom et l'email
    $user->name = $request->name;
    $user->email = $request->email;

    // Si un mot de passe est fourni, le mettre à jour
    if ($request->new_password) {
        $user->password = bcrypt($request->new_password);
    }

    $user->save();
    // Log de la mise à jour du profil
    $this->logAction('Mise à jour', "Mise à jour du profil de l'administrateur {$user->name}");
    // Notification de succès   
    $this->createNotification("Profil mis à jour avec succès.", 'success');
    return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
}


     public function index(){
         // Récupérer les statistiques
        $etudiantsCount = Student::count();
        $filieresCount = Filiere::count();
        $secretairesCount = Secretary::count();
        $validationsCount = Validation::count();
        
   // Récupérer le nombre d'étudiants par filière via la table 'inscriptions'
   $etudiantsParFiliere = DB::table('filieres')
    ->leftJoin('inscriptions', 'filieres.id', '=', 'inscriptions.filiere_id')
    ->select('filieres.id', 'filieres.name as nom_filiere', DB::raw('COUNT(inscriptions.student_id) as students_count'))
    ->groupBy('filieres.id', 'filieres.name')
    ->get();

       // Récupérer les notifications système (pagination des dernières 5)
    $notifications = Notification::orderBy('created_at', 'desc')->paginate(5);

    // Récupérer les activités récentes du système (logs) avec pagination
    $activites = Log::with('user') // Inclure l'utilisateur qui a fait l'action
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

        // Retourner la vue avec les statistiques et les notifications

        return view('admin.dashboard', compact('etudiantsCount', 'filieresCount', 'secretairesCount', 'validationsCount', 'notifications', 'activites', 'etudiantsParFiliere'));
    }

    

}
