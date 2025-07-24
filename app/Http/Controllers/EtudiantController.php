<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Filiere;
use App\Models\User;
use App\Models\Inscription;
use App\Models\Student;
use App\Models\Reclamation;
use App\Models\Validation;
use App\Models\Secretary;
use App\Models\Ue;
use App\Models\Ecue;
use App\Models\UeAssignment;
use App\Models\UeEcueAssignment;
use App\Models\Note;
use App\Models\EcueResult;
use App\Models\UeResult;
use App\Models\Notification;
use App\Models\Log;
use App\Models\Year;
use App\Models\Semester;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    // Tableau de bord de l'étudiant
    public function dashboard()
    {  $student = auth()->user()->student;

    // Récupérer toutes ses inscriptions
    $inscriptions = $student->inscriptions()->pluck('id');

    //  Notes validées (ECUE)
    $notesValidees = EcueResult::whereIn('inscription_id', $inscriptions)
        ->where('validated', true)
        ->count();

    //  Réclamations
    $reclamationsCount = Reclamation::whereIn('inscription_id', $inscriptions)->count();

    //  UEs validées
    $uesValidees = UeResult::whereIn('inscription_id', $inscriptions)
        ->where('validated', true)
        ->count();

    //  Moyenne générale (dernière validation par exemple)
    $moyenneGenerale = Validation::whereIn('inscription_id', $inscriptions)
        ->orderByDesc('created_at')
        ->value('general_average');

    //  Graphique : Notes par ECUE
    $ecueResults = EcueResult::with('ecue')
        ->whereIn('inscription_id', $inscriptions)
        ->get();

    //  Graphique : UEs validées
    $ueResults = UeResult::with('ue')
        ->whereIn('inscription_id', $inscriptions)
        ->get();

        // Log de la consultation du tableau de bord
    $this->logAction('Consultation', 'Tableau de bord consulté par l\'étudiant ' . auth()->user()->name);
    // Notification de succès
    $this->createNotification('Tableau de bord consulté avec succès.', 'success');

    return view('etudiants.dashboard', [
        'notesValidees' => $notesValidees,
        'reclamationsCount' => $reclamationsCount,
        'uesValidees' => $uesValidees,
        'moyenneGenerale' => $moyenneGenerale ?? 0,
        'ecueResults' => $ecueResults,
        'ueResults' => $ueResults,
    ]);
    }

    // Consulter les notes
    public function notes()
    {
   // Récupère l'utilisateur connecté
    $user = auth()->user();

    // Récupère le modèle étudiant lié à l'utilisateur
    $student = $user->student;

    // Récupère les ID des inscriptions de l'étudiant (au cas où il en aurait plusieurs)
    $inscriptions = Inscription::where('student_id', $student->id)->pluck('id');
    
    // Récupère la dernière filière dans laquelle il est inscrit
    $filiereId = Inscription::where('student_id', $student->id)->latest()->value('filiere_id');

    // Récupère les UE affectées à cette filière avec leurs ECUE
    $assignedUes = UeAssignment::where('filiere_id', $filiereId)->with('ue.ecues')->get();

    $ues = []; // Contiendra les UE + leurs ECUEs et notes

    foreach ($assignedUes as $assignment) {
        $ue = $assignment->ue;
        $ecues = $ue->ecues;

        // Récupère le résultat global (moyenne) pour l'UE
        $ueResult = UeResult::where('ue_id', $ue->id)
            ->whereIn('inscription_id', $inscriptions)
            ->latest()
            ->first();

        $ueAverage = $ueResult?->final_average;
        $sessionUe = $ueResult?->session;

        // Récupère les notes globales associées à l’UE (hors ECUE)
        $ueNotes = Note::where('ue_id', $ue->id)
            ->whereNull('ecue_id')
            ->whereIn('inscription_id', $inscriptions)
              ->whereIn('session', ['normale', 'rattrapage']) // Vérifie si la session est 'normale' ou 'rattrapage'
            ->orderBy('session') // Affiche d'abord session 1 puis 2
            ->get();

  

         
        $ueArray = [
            'name' => $ue->name,
            'moyenne_ue' => $ueAverage,
            'session' => $sessionUe,
            'notes' => $ueNotes->map(function ($note) {
                return [
                    'type' => $note->type,     // Type de note : CC, TP, EX...
                    'valeur' => $note->value,  // Valeur de la note
                    'session' => $note->session, // Session 1 ou 2
                    'retenue' => null,         // On ne marque pas la note retenue pour les UE directement ici
                ];
            }),
            'ecues' => []
        ];

        // Pour chaque ECUE de cette UE
        foreach ($ecues as $ecue) {
            // Récupère le dernier résultat pour cette ECUE
            $ecueResult = EcueResult::where('ecue_id', $ecue->id)
                ->whereIn('inscription_id', $inscriptions)
                ->latest()
                ->first();

            // Récupère toutes les notes liées à cette ECUE
            $ecueNotes = Note::where('ecue_id', $ecue->id)
                ->whereIn('inscription_id', $inscriptions)
                ->orderBy('session')
                ->get();

            // Détermine la note retenue :
            // - Priorité à la session 2 si elle existe
            // - Sinon prend la note de session 1
            $noteRetenue = $ecueNotes->where('session', 2)->sortByDesc('type')->first()
                            ?? $ecueNotes->where('session', 1)->sortByDesc('type')->first();

            $ueArray['ecues'][] = [
                'nom' => $ecue->name,
                'moyenne_ecue' => $ecueResult?->final_grade,
                'session' => $ecueResult?->session,
                'note_retenue' => $noteRetenue?->value,
                'notes' => $ecueNotes->map(function ($note) use ($noteRetenue) {
                    return [
                        'type' => $note->type,
                        'valeur' => $note->value,
                        'session' => $note->session,
                        'retenue' => $noteRetenue && $noteRetenue->id === $note->id, // coche la note retenue
                    ];
                }),
            ];
        }
       

        // Ajoute les infos de l'UE dans le tableau final
        $ues[] = $ueArray;
    }

    // Journalisation de l'action
    $this->logAction('Consultation', 'Consultation des notes par l\'étudiant ' . $user->name);

    // Notification de succès
    $this->createNotification('Vos notes ont été consultées avec succès.', 'success');
 // Affiche les détails du premier élément

    // Envoie des données à la vue
    return view('etudiants.notes', compact('ues'));
}
    // Voir les validations
    public function validation()
    {
     $user = auth()->user();
    $student = $user->student;

    // Inscriptions de l'étudiant
    $inscriptions = Inscription::where('student_id', $student->id)->pluck('id');

    // Dernière inscription pour récupérer la filière
    $lastInscription = Inscription::where('student_id', $student->id)->latest()->first();
    $filiereId = $lastInscription->filiere_id;

    // Récupérer les UEs assignées à cette filière avec leur semestre
    $assignments = UeAssignment::where('filiere_id', $filiereId)->with('ue')->get();

    // Définir les conteneurs
    $uesParSemestre = [
        'Semestre 1' => [],
        'Semestre 2' => [],
    ];

    $moyennesSemestrielles = [
        1 => ['somme' => 0, 'nb' => 0],
        2 => ['somme' => 0, 'nb' => 0],
    ];

    $totalUes = 0;
    $uesValidees = 0;

    foreach ($assignments as $assignment) {
        $ue = $assignment->ue;
        $semestre = $ue->semester_id;

        // Chercher le résultat de l'étudiant pour cette UE
        $result = UeResult::where('ue_id', $ue->id)
            ->whereIn('inscription_id', $inscriptions)
            ->latest()
            ->first();

        $moyenne = $result ? $result->final_average : null;

        // Si la note de rattrapage existe, la retenir
        if ($result && $result->session == 2) {
            $moyenne = $result->final_average; // On garde la note de rattrapage
        }

        // Ajouter à la bonne clé de semestre
        if ($semestre == 1 || $semestre == 2) {
            $libelle = 'Semestre ' . $semestre;

            $uesParSemestre[$libelle][] = [
                'nom' => $ue->name,
                'moyenne' => $moyenne,
            ];

            $totalUes++;

            if (!is_null($moyenne)) {
                $moyennesSemestrielles[$semestre]['somme'] += $moyenne;
                $moyennesSemestrielles[$semestre]['nb']++;

                if ($moyenne >= 12) {
                    $uesValidees++;
                }
            }
        }
    }

    // Calcul des moyennes par semestre
    $validationsSemestrielles = [];
    $sommeMoyennes = 0;
    $nbSemestresValides = 0;

    foreach ([1, 2] as $semestre) {
        $nb = $moyennesSemestrielles[$semestre]['nb'];
        $somme = $moyennesSemestrielles[$semestre]['somme'];
        $moyenne = $nb > 0 ? $somme / $nb : null;
        $valide = $moyenne !== null && $moyenne >= 12;

        $validationsSemestrielles[$semestre] = [
            'nom' => "Semestre $semestre - Validation",
            'moyenne' => $moyenne,
            'valide' => $valide,
        ];

        if (!is_null($moyenne)) {
            $sommeMoyennes += $moyenne;
        }

        if ($valide) {
            $nbSemestresValides++;
        }
    }

    // Moyenne annuelle (si au moins un semestre a une moyenne)
    $moyenneAnnuelle = ($moyennesSemestrielles[1]['nb'] + $moyennesSemestrielles[2]['nb']) > 0
        ? $sommeMoyennes / ($nbSemestresValides ?: 1)
        : null;

    // Validation annuelle : si les deux semestres sont validés
    $validationAnnuelle = $validationsSemestrielles[1]['valide'] && $validationsSemestrielles[2]['valide'];

    // Taux de validation
    $tauxValidation = $totalUes > 0
        ? round(($uesValidees / $totalUes) * 100)
        : 0;

    // Année académique actuelle (ex: 2024-2025)
    $anneeAcademique = Carbon::now()->month >= 9
        ? Carbon::now()->year . '-' . (Carbon::now()->year + 1)
        : (Carbon::now()->year - 1) . '-' . Carbon::now()->year;

    return view('etudiants.validation', [
        'anneeAcademique' => $anneeAcademique,
        'validationAnnuelle' => $validationAnnuelle,
        'moyenneAnnuelle' => $moyenneAnnuelle,
        'tauxValidation' => $tauxValidation,
        'validationsSemestrielles' => $validationsSemestrielles,
        'uesParSemestre' => $uesParSemestre,
    ]);
}

// Voir le profil
    public function profil()
    { 
        $etudiant = auth()->user()->etudiant; // Récupérer l'étudiant connecté
         $filieres = Filiere::all();
        return view('etudiants.profil', compact('etudiant', 'filieres'));
    }

    //Modifier le profil
    
    public function update(Request $request)
    {

         $request->validate([
        'name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'email' => 'required|email',
        'filiere_id' => 'required|exists:filieres,id',
    ]);

    $user = Auth::user();

    // Mise à jour du user
    $user->update([
        'name' => $request->name,
        'first_name' => $request->first_name,
        'email' => $request->email,
    ]);

    //  Mise à jour de la filière via l'inscription
    $etudiant = $user->etudiant;
    if ($etudiant) {
        $inscription = $etudiant->inscriptions()->latest()->first();
        if ($inscription) {
            $inscription->update([
                'filiere_name' => $request->filiere_name,
            ]);
        }
    }

    return redirect()->route('etudiants.profil')->with('success', 'Profil mis à jour avec succès.');
    }   


    //Voir les résultats
   public function resultats()
 
{
    $user = auth()->user();
    $student = $user->student;

    // Inscriptions de l'étudiant
    $inscriptions = Inscription::where('student_id', $student->id)->pluck('id');

    // Dernière filière de l'étudiant
    $filiereId = Inscription::where('student_id', $student->id)->latest()->value('filiere_id');

    // UEs assignées à cette filière
    $assignedUes = UeAssignment::where('filiere_id', $filiereId)->with('ue.ecues')->get();

    $ues = [];
    $validUes = 0;
    $invalidUes = 0;

    foreach ($assignedUes as $assignment) {
        $ue = $assignment->ue;
        $ecues = $ue->ecues;

        $ueResult = UeResult::where('ue_id', $ue->id)
            ->whereIn('inscription_id', $inscriptions)
            ->latest()->first();

        $ueAverage = $ueResult?->final_average;

        if ($ueAverage !== null) {
            if ($ueAverage >= 10) $validUes++;
            else $invalidUes++;
        }

        // Notes directes sur l’UE
        $notesUe = Note::where('ue_id', $ue->id)
            ->whereNull('ecue_id')
            ->whereIn('inscription_id', $inscriptions)
            ->get();

        $ueNotesData = $notesUe->map(function ($note) {
            return [
                'type' => $note->type,
                'session' => $note->session,
                'valeur' => $note->value,
            ];
        });

        $noteNormaleUe = $notesUe->firstWhere('session', 'normale');
        $noteRattrapageUe = $notesUe->firstWhere('session', 'rattrapage');
        $noteRetenueUe = $noteRattrapageUe?->value ?? $noteNormaleUe?->value;

        $ueArray = [
            'nom' => $ue->name,
            'moyenne_ue' => $ueAverage,
            'note_normale' => $noteNormaleUe?->value,
            'note_rattrapage' => $noteRattrapageUe?->value,
            'note_retenue' => $noteRetenueUe,
            'notes' => $ueNotesData,
            'ecues' => [],
        ];

        foreach ($ecues as $ecue) {
            $ecueResult = EcueResult::where('ecue_id', $ecue->id)
                ->whereIn('inscription_id', $inscriptions)
                ->latest()->first();

            $notesEcue = Note::where('ecue_id', $ecue->id)
                ->whereIn('inscription_id', $inscriptions)
                ->get();

            $ecueNotesData = $notesEcue->map(function ($note) {
                return [
                    'type' => $note->type,
                    'session' => $note->session,
                    'valeur' => $note->value,
                ];
            });

            $noteNormaleEcue = $notesEcue->firstWhere('session', 'normale');
            $noteRattrapageEcue = $notesEcue->firstWhere('session', 'rattrapage');
            $noteRetenueEcue = $noteRattrapageEcue?->value ?? $noteNormaleEcue?->value;

            $ueArray['ecues'][] = [
                'nom' => $ecue->name,
                'moyenne_ecue' => $ecueResult?->final_grade,
                'note_normale' => $noteNormaleEcue?->value,
                'note_rattrapage' => $noteRattrapageEcue?->value,
                'note_retenue' => $noteRetenueEcue,
                'notes' => $ecueNotesData,
            ];
        }

        $ues[] = $ueArray;
    }

    $totalUes = $validUes + $invalidUes;
    $pourcentage = $totalUes > 0 ? ($validUes / $totalUes) * 100 : 0;

    $this->logAction('Consultation', 'Consultation des résultats par l\'étudiant ' . $user->name);
    $this->createNotification('Vos résultats ont été consultés avec succès.', 'success');

    return view('etudiants.resultats', compact('ues', 'validUes', 'invalidUes', 'pourcentage'));
}

      // Méthode pour afficher le formulaire de réclamation
    public function faireReclamation()
    {
        return view('etudiants.reclamation-form'); // Vue pour faire une réclamation
    }

    // Méthode pour traiter la réclamation soumise
    public function storeReclamation(Request $request)
    {
        // Validation des données
        $request->validate([
            'type_reclamation' => 'required|string',
            'description' => 'required|string',
            'documents' => 'nullable|file|mimes:pdf,jpeg,png,jpg',
        ]);

        // Enregistrement de la réclamation
        $reclamation = new Reclamation();
        $reclamation->type = $request->input('type_reclamation');
        $reclamation->description = $request->input('description');

        if ($request->hasFile('documents')) {
            // Sauvegarder le fichier téléchargé (ex: dans le dossier 'reclamations')
            $reclamation->document_path = $request->file('documents')->store('reclamations');
        }

        $reclamation->save();

        // Rediriger avec un message de succès
        return redirect()->route('etudiants.dashboard')->with('success', 'Réclamation soumise avec succès');
    }


}
