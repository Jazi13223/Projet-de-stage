<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\Note;
use App\Models\Secretary;
use App\Models\Reclamation;
use App\Models\Ue;
use App\Models\EcueResult;
use App\Models\UeResult;
use App\Models\Inscription;
use App\Models\Year;
use App\Models\Semester;
use App\Models\Validation;
use App\Models\Ecue;
use App\Models\Filiere;
use App\Models\UeAssignment;
use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;


class SecretaireController extends Controller
{
    // Tableau de bord du secrétaire
    public function dashboard()
    {
        // Récupérer les statistiques
        $totalEtudiants = Inscription::count(); // Nombre total d'étudiants
        $reclamationsEnAttente = Reclamation::where('status', 'en attente')->count(); // Nombre de réclamations en attente

        // Moyennes par UE
        $ues = UeResult::with('ue')->get(); // Récupère les résultats pour chaque UE

        // Moyennes par ECUE
        $ecues = EcueResult::with('ecue')->get(); // Récupère les résultats pour chaque ECUE

        // Passer les données à la vue
        return view('secretaire.dashboard', [
            'totalEtudiants' => $totalEtudiants,
            'reclamationsEnAttente' => $reclamationsEnAttente,
            'ues' => $ues,
            'ecues' => $ecues,
        ]);
    }


    // Gérer les réclamations
  public function manageReclamations()
{
    $reclamations = Reclamation::with([
        'inscription.etudiant.user',
        'note.ecue.ue',
        'note.ue'
    ])->get();

    $data = [];

    foreach ($reclamations as $reclamation) {
        $etudiant = $reclamation->inscription?->etudiant;
        $user = $etudiant?->user;

        $note = $reclamation->note;

        // Récupération du nom de l'ECUE si elle existe
        $ecue = $note?->ecue?->name;

        // Récupération du nom de l'UE, soit via ECUE->UE, soit via note->UE
        $ue = $note?->ecue?->ue?->name ?? $note?->ue?->name ?? 'UE inconnue';

        $data[] = [
            'id' => $reclamation->id,
            'etudiant' => $user?->name ?? 'Nom inconnu',
            'ue' => $ue,
            'ecue' => $ecue,
            'motif' => $reclamation->motif,
            'statut' => $reclamation->statut,
        ];
    }
     $this->createNotification('Le secrétaire a consulté les réclamations', 'info');
    return view('secretaire.manage-reclamation', [
        'reclamations' => $data
    ]);
}


        // Gérer les UE/ECUE
    public function manageUes()
    {
           // Récupérer toutes les filières et les UE/ECUE
        $filieres = Filiere::all();
        $ues = Ue::with('ecues')->get(); // Charger les UE avec leurs ECUE (si présents)
        $years = Year::all();
        $semesters = Semester::all();

        return view('secretaire.ues', compact('filieres', 'ues', 'years', 'semesters'));
    }


   
    public function ajouterUeEcue(Request $request)
    {
    $request->validate([
        'filiere_id' => 'required|exists:filieres,id',
        'ue_name' => 'required|string',
        'ue_coefficient' => 'required|numeric|min:1',
        'semester_id' => 'required|exists:semesters,id',
        'year_id' => 'required|exists:years,id',

        'ecue1_name' => 'nullable|string',
        'ecue1_coefficient' => 'nullable|numeric|min:1',
        'ecue2_name' => 'nullable|string',
        'ecue2_coefficient' => 'nullable|numeric|min:1',
    ]);

    $secretaire = Secretary::where('user_id', auth()->id())->first();
    if (!$secretaire) {
        return redirect()->back()->with('error', 'Secrétaire non trouvé.');
    }

    // Vérifie cohérence ECUE : tous les champs doivent être remplis ou aucun
    $ecue1Rempli = $request->filled(['ecue1_name', 'ecue1_coefficient']);
    $ecue2Rempli = $request->filled(['ecue2_name', 'ecue2_coefficient']);

    if ($ecue1Rempli xor $ecue2Rempli) {
        return redirect()->back()->with('error', 'Veuillez renseigner exactement deux ECUE ou aucun.');
    }

    try {
        DB::beginTransaction();

        // Création de l'UE
        $ue = Ue::create([
            'name' => $request->ue_name,
            'coefficient' => $request->ue_coefficient,
            'semester_id' => $request->semester_id,
            'year_id' => $request->year_id,
            'secretary_id' => $secretaire->id,
        ]);

        // Liaison UE à filière
        UeAssignment::create([
            'ue_id' => $ue->id,
            'filiere_id' => $request->filiere_id,
            'coefficient' => $request->ue_coefficient,
        ]);

        // Si deux ECUE sont renseignés
        if ($ecue1Rempli && $ecue2Rempli) {
            Ecue::create([
                'name' => $request->ecue1_name,
                'coefficient' => $request->ecue1_coefficient,
                'ue_id' => $ue->id,
            ]);
            Ecue::create([
                'name' => $request->ecue2_name,
                'coefficient' => $request->ecue2_coefficient,
                'ue_id' => $ue->id,
            ]);
        }

    $this->logAction('ajout_ue', 'Le secrétaire a ajouté une UE nommée "' . $request->ue_name . '"');
     $this->createNotification('Une nouvelle UE a été ajoutée : ' . $request->ue_name, 'success');
        DB::commit();
        return redirect()->route('secretaire.ues')->with('success', 'UE ajoutée avec succès' . ($ecue1Rempli ? ' avec 2 ECUE.' : '.'));
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Erreur lors de l’ajout de l’UE : ' . $e->getMessage());
    }
    }

   public function updateUe(Request $request, $id)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'coefficient' => 'required|numeric|min:1',
    ]);

        $ue = \App\Models\Ue::findOrFail($id);
        $ue->name = $request->name;
        $ue->coefficient = $request->coefficient;
        $ue->save();
        $this->logAction('modification_ue', 'Le secrétaire a modifié l\'UE : ' . $ue->name);
         $this->createNotification('L\'UE a été mise à jour : ' . $ue->name, 'success');
        return back()->with('success', 'UE mise à jour avec succès.');
    }

    public function updateEcue(Request $request, $id)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'coefficient' => 'required|numeric|min:1',
    ]);

        $ecue = \App\Models\Ecue::findOrFail($id);
        $ecue->name = $request->name;
        $ecue->coefficient = $request->coefficient;
        $ecue->save();
        $this->logAction('modification_ecue', 'Le secrétaire a modifié l\'ECUE : ' . $ecue->name);
        $this->createNotification('L\'ECUE a été mise à jour : ' . $ecue->name, 'success');
        return back()->with('success', 'ECUE mis à jour avec succès.');
    }


    // Gérer les étudiants
  public function manageEtudiants(Request $request)
{
    $filtreFiliere = $request->get('filiere');

    if ($filtreFiliere) {
       $etudiants = Student::with('user', 'derniereInscription.filiere')
    ->whereHas('derniereInscription.filiere', function ($query) use ($filtreFiliere) {
        $query->where('name', $filtreFiliere);
    })
    ->get();

    } else {
       $etudiants = Student::with('user', 'derniereInscription.filiere')->get();

    }

    $filieres = Filiere::all(); 
    $years = Year::all();
    $semesters = Semester::all();
    return view('secretaire.gerer-etudiant', compact('etudiants', 'filieres', 'years', 'semesters'));
}


    // Ajouter les étudiants
 public function ajouterEtudiant(Request $request)
{
    // 1. Validation des données
    $request->validate([
        'name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'matricule' => 'required|string|max:255|unique:students,matricule',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'filiere_id' => 'required|exists:filieres,id',
        'year_id' => 'required|exists:years,id',
        'semester_id' => 'required|exists:semesters,id',

    ]);

    // 2. Création du compte utilisateur
    $user = User::create([
        'name' => $request->name,
        'first_name' => $request->first_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'etudiant',
    ]);

    // 3. Création de l’étudiant
    $etudiant = Student::create([
        'user_id' => $user->id,
        'matricule' => $request->matricule,
    ]);

    // 4. Création de l’inscription liée à l’étudiant
    Inscription::create([
        'student_id' => $etudiant->id,
        'filiere_id' => $request->filiere_id,
        'year_id' => $request->year_id,
        'semester_id' => $request->semester_id,
        'date_inscription' => now(),
    ]);
    $this->logAction('ajout_etudiant', 'Le secrétaire a ajouté l\'étudiant : ' . $user->name);
     $this->createNotification('Le secrétaire a ajouté un étudiant : ' . $user->name, 'success');
    return redirect()->route('secretaire.gerer-etudiant')->with('success', 'Étudiant ajouté avec succès.');
}


    public function modifierEtudiant(Request $request, $id)
{
    // Validation des données
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'matricule' => 'required|string|max:50|unique:students,matricule,' . $id,
        'email' => 'required|email|unique:students,email,' . $id,
        'filiere_id' => 'required|exists:filieres,id',
    ]);

    // Trouver l'étudiant à modifier
    $student = Student::findOrFail($id);
    $student->name = $validatedData['name'];
    $student->matricule = $validatedData['matricule'];
    $student->email = $validatedData['email'];
    $student->filiere_id = $validatedData['filiere_id'];

    // Si le mot de passe a été changé, on le met à jour
    if ($request->filled('password')) {
        $student->password = Hash::make($request->password);
    }

    $student->save();
    $this->logAction('modification_etudiant', 'Le secrétaire a modifié l\'étudiant : ' . $student->matricule);
     $this->createNotification('Le secrétaire a modifié l\'étudiant : ' . $student->matricule, 'success');
    // Rediriger avec message de succès
    return redirect()->route('secretaire.gerer-etudiant')->with('success', 'Étudiant modifié avec succès');
}


    //Profil du secretaire
    public function profil(){
        return view('secretaire.profil');
    }


       // Modifier le profil
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
           // Rendre les mots de passe facultatifs
        'old_password' => 'nullable|current_password', // Seul si le champ est renseigné
        'new_password' => 'nullable|confirmed|min:8',   // Seul si le champ est renseigné
        'confirm_password' => 'nullable|same:new_password',  // Seul si le champ est renseigné
    ]);

    // Vérification si la validation échoue
    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Mise à jour du nom et de l'email
    $user->name = $request->input('nom');
    $user->email = $request->input('email');

    // Vérifier si un mot de passe a été fourni et mettre à jour si nécessaire
    if ($request->filled('new_password')) {
        // Vérifier si le mot de passe actuel a bien été fourni
        if (!$request->filled('old_password')) {
            return back()->with('error', 'Le mot de passe actuel est requis pour changer le mot de passe.');
        }

        // Mise à jour du mot de passe
        $user->password = Hash::make($request->input('new_password'));
    }

    // Sauvegarde des informations mises à jour
    $user->save();
    $this->logAction('modification_profil', 'Le secrétaire a mis à jour son profil');
    $this->createNotification('Le secrétaire a mis à jour son profil', 'success');

    return back()->with('success', 'Profil mis à jour avec succès.');
    }

    
    // Méthode pour afficher la page de traitement d'une réclamation
    public function afficherTraitementReclamation($id, $mode, Request $request)
{
    // Utilisation de l'ID au lieu de la référence
    $reclamation = Reclamation::findOrFail($id);

    // Chargement des relations nécessaires
    $note = $reclamation->note()->with(['ecue.ue', 'inscription.etudiant.user'])->first();

    // Préparer les données pour la vue
    $data = [
        'reference' => $reclamation->reference,
        'motif' => $reclamation->motif,
        'status' => $reclamation->statut,
        'date_submission' => $reclamation->created_at?->format('d/m/Y H:i'),
        'reponse' => $reclamation->reponse ?? null,
        'etudiant' => $note->inscription->etudiant->user->name ?? 'Nom inconnu',
        'matricule' => $note->inscription->etudiant->matricule ?? null,
        'email' => $note->inscription->etudiant->user->email ?? null,
        'ue' => $note->ecue->ue->name ?? ($note->ue->name ?? 'UE inconnue'),
        'ecue' => $note->ecue->name ?? null,
    ];

    return view('secretaire.traiter-reclamation', [
        'reclamation' => $data,
        'mode' => $mode,
    ]);
}

public function updateReclamation(Request $request)
{
    $request->validate([
        'id' => 'required|exists:reclamations,id', // Remplacer 'reference' par 'id'
        'reponse' => 'required|string|min:5',
        'statut' => 'required|in:en_attente,résolue,refusée',
    ]);

    $reclamation = Reclamation::findOrFail($request->id); // Remplacer 'reference' par 'id'
    $reclamation->reponse = $request->reponse;
    $reclamation->statut = $request->statut;
    $reclamation->save();
    $this->logAction('traitement_reclamation', 'Le secrétaire a mis à jour la réclamation ID : ' . $reclamation->id);
     $this->createNotification('Le secrétaire a mis à jour la réclamation ID : ' . $reclamation->id, 'info');

    return redirect()->route('secretaire.manage-reclamation')->with('success', 'Réclamation mise à jour avec succès.');
}


public function manageNotes(Request $request)
{
    $matricule = $request->query('matricule');

    if ($matricule) {
        // Récupération de l'étudiant par matricule
        $etudiant = Student::where('matricule', $matricule)->with('user')->first();

        if (!$etudiant) {
            return view('secretaire.note')->with('error', 'Aucun étudiant trouvé.');
        }

        // Dernière inscription (avec filière)
        $inscription = $etudiant->inscriptions()->with('filiere')->latest()->first();

        if (!$inscription || !$inscription->filiere) {
            return view('secretaire.note')->with('error', 'Aucune inscription trouvée pour cet étudiant.');
        }

        $filiere = $inscription->filiere;

        // Récupérer les UE de la filière via les affectations, et charger leurs ECUE
        $ueAssignments = $filiere->ue_assignments()->with('ue.ecues')->get();
        
        // Extraire les UE de ces affectations
        $ues = $ueAssignments->map(function ($assignment) {
            return $assignment->ue;
        })->filter(); // supprime les UE nulles si jamais
        
        // Si aucune UE n'est trouvée, retourner un message d'erreur
        if ($ues->isEmpty()) {
            return view('secretaire.note')->with([
                'etudiant' => $etudiant,
                'ues' => [],
                'notesMap' => [],
                'error' => 'Aucune UE liée à cette filière.'
            ]);
        }

        // Récupérer toutes les notes de cette inscription
        $notes = $inscription->notes()->with(['ecue', 'ue'])->get();

        // Organiser les notes sous forme de map : [ue_id][ecue_id] => notes
        $notesMap = [];

        foreach ($notes as $note) {
            $ueId = $note->ue_id;
            $ecueId = $note->ecue_id ?? 'none'; // 'none' pour les UE sans ECUE
            $type = $note->type;

            if ($type) {
                if (!isset($notesMap[$ueId])) {
                    $notesMap[$ueId] = [];
                }

                if (!isset($notesMap[$ueId][$ecueId])) {
                    $notesMap[$ueId][$ecueId] = [];
                }

                $notesMap[$ueId][$ecueId][$type] = $note;
            }
        }

        // Passer les données à la vue
        return view('secretaire.note', [
            'etudiant' => $etudiant,
            'notesMap' => $notesMap,
            'ues' => $ues
        ]);
    }

    // Cas où aucun matricule n’est encore fourni (chargement initial)
    return view('secretaire.note');
}


public function ajouterNote(Request $request)
{
    // Validation des données reçues
    $request->validate([
        'etudiant' => 'required|string', // Le nom ou matricule de l'étudiant
        'ue' => 'required|string', // Le nom de l'UE
        'ecue' => 'nullable|string', // L'ECUE, optionnel
        'type' => 'required|string|in:interro,devoir,examen,projet', // Type de la note
        'note' => 'required|numeric|min:0|max:20', // Valeur de la note entre 0 et 20
    ]);

    // Recherche de l'étudiant par son nom (dans la table users) ou matricule
    $etudiant = Student::where('matricule', $request->etudiant)->first();

    // Si l'étudiant n'est pas trouvé, on redirige avec un message d'erreur
    if (!$etudiant) {
        return back()->with('error', 'Étudiant non trouvé');
    }

    // Recherche de l'UE
    $ue = UE::where('name', 'like', '%' . $request->ue . '%')->first();
    if (!$ue) {
        return back()->with('error', 'UE non trouvée.');
    }

    // Recherche de l'ECUE si renseigné
    $ecue = null;
    if ($request->filled('ecue')) {
        $ecue = ECUE::where('name', 'like', '%' . $request->ecue . '%')->first();
        if (!$ecue) {
            return back()->with('error', 'ECUE non trouvée.');
        }
    }

    // Recherche de l'inscription de l'étudiant dans la filière liée à l'UE
    $inscription = Inscription::where('student_id', $etudiant->id)
                            ->whereHas('filiere.ues', function ($query) use ($ue) {
                                $query->where('ue_id', $ue->id);
                            })
                            ->first();

    // Si l'étudiant n'est pas inscrit dans une filière liée à cette UE
    if (!$inscription) {
        return back()->with('error', 'L’étudiant n’est pas inscrit dans la filière liée à cette UE.');
    }

    // Création de la nouvelle note
    $note = new Note();
    $note->type = $request->type;
    $note->value = $request->note;
    $note->inscription_id = $inscription->id;
    $note->ue_id = $ue->id;
    $note->ecue_id = $ecue ? $ecue->id : null; // Gère le cas où ECUE est optionnel
    $note->save();
    $this->logAction('ajout_note', 'Note ajoutée pour l\'étudiant ' . $etudiant->matricule . ', UE : ' . $ue->name);
    $this->createNotification('Note ajoutée pour l\'étudiant ' . $etudiant->matricule . ', UE : ' . $ue->name, 'success');

    // Redirection avec message de succès
    return redirect()->route('secretaire.note')->with('success', 'Note ajoutée avec succès.');
}



   public function modifierNote(Request $request, $id)
{
    $request->validate([
        'ecue' => 'nullable|string', // Validation pour l'ECUE
        'type_note' => 'required|string',
        'valeur' => 'required|numeric|min:0|max:20',
    ]);

    // Trouver la note existante par ID
    $note = Note::findOrFail($id);

    // Mise à jour de la note
    $note->type = $request->type_note;
    $note->value = $request->valeur;

    // Si l'ECUE est fourni, l'associer à la note
    if ($request->filled('ecue')) {
        $ecue = ECUE::where('name', 'like', '%' . $request->ecue . '%')->first();
        if ($ecue) {
            $note->ecue_id = $ecue->id;
        }
    } else {
        $note->ecue_id = null; // Si pas d'ECUE, on laisse null
    }

    $note->save();
    $this->logAction('modification_note', 'Note modifiée (ID: ' . $note->id . ')');
    $this->createNotification('Note modifiée (ID: ' . $note->id . ')', 'success');
    return redirect()->route('secretaire.note')->with('success', 'Note modifiée avec succès.');
}


public function deleteUe($id)
{
    $ue = UE::findOrFail($id);

    // Empêche la suppression si des ECUE sont liés
    if ($ue->ecues()->count() > 0) {
        return redirect()->back()->with('error', 'Impossible de supprimer cette UE car elle contient des ECUE.');
    }

    $ue->delete();
    $this->logAction('suppression_ue', 'Le secrétaire a supprimé l\'UE : ' . $ue->name);
     $this->createNotification('Le secrétaire a supprimé l\'UE : ' . $ue->name, 'danger');
    return redirect()->back()->with('success', 'UE supprimée avec succès.');
}

public function deleteEcue($id)
{
    $ecue = ECUE::findOrFail($id);
    $ecue->delete();
    $this->logAction('suppression_ecue', 'Le secrétaire a supprimé l\'ECUE : ' . $ecue->name);
     $this->createNotification('Le secrétaire a supprimé l\'ECUE : ' . $ecue->name, 'danger');


    return redirect()->back()->with('success', 'ECUE supprimée avec succès.');
}

}
