<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SecretaireController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Auth\CustomRegisteredUserController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// Routes d'authentification

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Autres routes personnalisées pour l'admin, le secrétaire et l'étudiant

// Routes pour l'administrateur
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'manageUsers'])->name('admin.users');
     Route::post('/admin/users', [AdminController::class, 'storeEtudiant'])->name('admin.etudiants.store');

    Route::put('/admin/users/{id}', [AdminController::class, 'updateEtudiant'])->name('admin.etudiants.update');

    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteEtudiant'])->name('admin.etudiants.delete');
    Route::get('/admin/manage-secretaire', [AdminController::class, 'manageSecretaire'])->name('admin.manage-secretaire');
     Route::post('/admin/manage-secretaire', [AdminController::class, 'addSecretaire'])->name('admin.addSecretaire');
     Route::delete('/admin/manage-secretaire/{id}', [AdminController::class, 'deleteSecretaire'])->name('admin.secretaires.delete');
     Route::post('/admin/secretaires/update/{id}', [AdminController::class, 'updateSecretaire'])->name('admin.updateSecretaire');
    Route::get('/admin/filiere', [AdminController::class, 'manageFilieres'])->name('admin.filiere');
     Route::post('admin/filiere', [AdminController::class, 'storeFiliere'])->name('admin.filieres.store');
    Route::put('admin/filiere/update', [AdminController::class, 'updateFiliere'])->name('admin.filieres.update');
    Route::delete('admin/filieres/{id}', [AdminController::class, 'destroyFiliere'])->name('admin.filieres.destroy');
    Route::post('/filieres/{id}/assign-ue', [AdminController::class, 'assignUeToFiliere'])->name('admin.filieres.assignUe');
    Route::get('/admin/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
     Route::get('/admin/profil', [AdminController::class, 'profil'])->name('admin.profil');
});

// Routes pour le secrétaire
Route::middleware(['auth', 'secretaire'])->group(function () {
    Route::get('/secretaire/dashboard', [SecretaireController::class, 'dashboard'])->name('secretaire.dashboard');
    Route::get('/secretaire/note', [SecretaireController::class, 'manageNotes'])->name('secretaire.note');
    Route::post('/secretaire/note', [SecretaireController::class, 'ajouterNote'])->name('secretaire.ajouterNote');
    Route::put('/secretaire/modifier-note/{id}', [SecretaireController::class, 'modifierNote'])->name('secretaire.modifierNote');

    Route::get('/secretaire/manage-reclamation', [SecretaireController::class, 'manageReclamations'])->name('secretaire.manage-reclamation');
    Route::get('/secretaire/ues', [SecretaireController::class, 'manageUes'])->name('secretaire.ues');
     // Route pour ajouter une UE et ECUE
     Route::post('/secretaire/ajouter-ue-ecue', [SecretaireController::class, 'ajouterUeEcue'])->name('secretaire.ajouter-ue-ecue');
    Route::put('/secretaire/ue/{id}', [SecretaireController::class, 'updateUe'])->name('secretaire.update-ue');
    Route::put('/secretaire/ecue/{id}', [SecretaireController::class, 'updateEcue'])->name('secretaire.update-ecue');
    // Suppression UE
    Route::delete('/secretaire/ue/{id}', [SecretaireController::class, 'deleteUe'])->name('secretaire.delete-ue');

    // Suppression ECUE
    Route::delete('/secretaire/ecue/{id}', [SecretaireController::class, 'deleteEcue'])->name('secretaire.delete-ecue');

     Route::post('/secretaire/modifier-ue-ecue/{id}', [SecretaireController::class, 'modifierUeEcue'])->name('secretaire.modifier-ue-ecue');
     Route::get('/secretaire/gerer-etudiant', [SecretaireController::class, 'manageEtudiants'])->name('secretaire.gerer-etudiant');
    Route::post('/secretaire/ajouter-etudiant', [SecretaireController::class, 'ajouterEtudiant'])->name('secretaire.ajouter-etudiant');
    Route::put('/modifier-etudiant/{id}', [SecretaireController::class, 'modifierEtudiant'])->name('secretaire.modifier-etudiant');
    Route::get('/secretaire/profil', [SecretaireController::class, 'profil'])->name('secretaire.profil');
       Route::put('/secretaire/profil', [SecretaireController::class, 'update'])->name('profile.update');
    // Route GET pour afficher la page de traitement de la réclamation
    Route::get('/secretaire/traiter-reclamation/{id}/{mode}', [SecretaireController::class, 'afficherTraitementReclamation'])
    ->name('secretaire.traiter-reclamation');
    // Route POST pour soumettre le formulaire de traitement
    Route::post('/secretaire/traiter-reclamation/{id}', [SecretaireController::class, 'traiterReclamation'])
    ->name('secretaire.update-reclamation');
    Route::post('/secretaire/enregistrer-etudiant', [SecretaireController::class, 'store'])->name('secretaire.enregistrer-etudiant');
    Route::post('/secretaire/enregistrer-etudiant', [SecretaireController::class, 'store'])->name('secretaire.enregistrer-etudiant');
    Route::put('/secretaire/modifier-etudiant', [SecretaireController::class, 'update'])->name('secretaire.modifier-etudiant');


   

});

// Routes pour les étudiants
Route::middleware(['auth', 'etudiants'])->group(function () {
    Route::get('/etudiants/dashboard', [EtudiantController::class, 'dashboard'])->name('etudiants.dashboard');
    Route::get('/etudiants/notes', [EtudiantController::class, 'notes'])->name('etudiants.notes');
    Route::get('/etudiants/validation', [EtudiantController::class, 'validation'])->name('etudiants.validation');
    Route::get('/etudiants/profil', [EtudiantController::class, 'profil'])->name('etudiants.profil');
    Route::post('/etudiants/profil', [EtudiantController::class, 'update'])->name('etudiants.update');
    Route::get('/etudiants/reclamation-form', [EtudiantController::class, 'faireReclamation'])->name('etudiants.reclamation-form');
    Route::post('/etudiants/reclamation-form', [EtudiantController::class, ' storeReclamation'])->name('etudiants.reclamation-form');

    Route::get('/etudiants/resultats', [EtudiantController::class, 'resultats'])->name('etudiants.resultats');
});


Route::get('/accueil', function () {
    return view('accueil');
});

Route::get('/auth/register-etudiant', [CustomRegisteredUserController::class, 'createEtudiant'])->name('auth.register.etudiant');
Route::post('/auth/register-etudiant', [CustomRegisteredUserController::class, 'storeEtudiant'])->name('register.etudiant');

Route::get('/auth/register-secretaire', [CustomRegisteredUserController::class, 'createSecretaire'])->name('auth.register.secretaire');
Route::post('/auth/register-secretaire', [CustomRegisteredUserController::class, 'storeSecretaire'])->name('register.secretaire');

Route::get('/auth/register-admin', [CustomRegisteredUserController::class, 'createAdmin'])->name('auth.register.admin');
Route::post('/auth/register-admin', [CustomRegisteredUserController::class, 'storeAdmin'])->name('register.admin');

// Affichage des formulaires de connexion
Route::get('/auth/login-etudiant', [CustomLoginController::class, 'showLoginEtudiant'])->name('login.etudiant');
Route::get('/auth/login-secretaire', [CustomLoginController::class, 'showLoginSecretaire'])->name('login.secretaire');
Route::get('/auth/login-admin', [CustomLoginController::class, 'showLoginAdmin'])->name('login.admin');

// Traitement des formulaires de connexion
Route::post('/auth/login-etudiant', [CustomLoginController::class, 'login'])->name('login.etudiant.submit');
Route::post('/auth/login-secretaire', [CustomLoginController::class, 'login'])->name('login.secretaire.submit');
Route::post('/auth/login-admin', [CustomLoginController::class, 'login'])->name('login.admin.submit');

Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');

