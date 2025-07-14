<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Filiere;
use App\Models\User;
use App\Models\Inscription;
use App\Models\Student;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    // Tableau de bord de l'étudiant
    public function dashboard()
    {
        return view('etudiants.dashboard');
    }

    // Consulter les notes
    public function notes()
    {
       
    // Exemple simulé (en attendant la logique dynamique plus tard)
    $ues = [
        [
            'nom' => 'UE1 - Mathématiques',
            'moyenne_ue' => 14,
            'ecues' => [
                [
                    'nom' => 'Algèbre',
                    'moyenne_ecue' => 13,
                    'notes' => [
                        ['type' => 'Devoir', 'valeur' => 12],
                        ['type' => 'Examen', 'valeur' => 14],
                    ],
                ],
                [
                    'nom' => 'Analyse',
                    'moyenne_ecue' => 15,
                    'notes' => [
                        ['type' => 'Projet', 'valeur' => 15],
                    ],
                ],
            ],
        ],
        [
            'nom' => 'UE2 - Informatique',
            'moyenne_ue' => 12,
            'ecues' => [],
            'notes' => [
                ['type' => 'Examen', 'valeur' => 12],
            ],
        ],
    ];

    return view('etudiants.notes', compact('ues'));
    }

    // Voir les validations
    public function validation()
    {
        return view('etudiants.validation');
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
                'filiere_id' => $request->filiere_id,
            ]);
        }
    }

    return redirect()->route('etudiants.profil')->with('success', 'Profil mis à jour avec succès.');
    }   


    //Voir les résultats
   public function resultats()
    {
    // Données fictives, remplace par ta logique réelle
    $validUes = 5;
    $totalUes = 8;
    $invalidUes = $totalUes - $validUes;

    // On évite la division par zéro
    $pourcentage = $totalUes > 0 ? ($validUes / $totalUes) * 100 : 0;

    // Exemple d'UEs avec leurs notes associées
    $ues = [
        [
            'nom' => 'Mathématiques',
            'moyenne_ue' => 12.5,
            'notes' => [
                ['type' => 'Devoir', 'valeur' => 14],
                ['type' => 'Examen', 'valeur' => 11],
            ],
        ],
        [
            'nom' => 'Informatique',
            'moyenne_ue' => 9.3,
            'notes' => [
                ['type' => 'Devoir', 'valeur' => 10],
                ['type' => 'Projet', 'valeur' => 8.5],
            ],
        ],
        [
            'nom' => 'Physique',
            'moyenne_ue' => 7.8,
            'notes' => [], // UE sans notes
        ],
    ];

    return view('etudiants.resultats', [
        'validUes' => $validUes,
        'invalidUes' => $invalidUes,
        'pourcentage' => $pourcentage,
        'ues' => $ues,
    ]);
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
