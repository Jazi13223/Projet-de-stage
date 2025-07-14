@extends('adminlte::page')

@section('title', 'Ajouter un Étudiant')

@section('content_header')
    <h1><i class="fas fa-user-plus text-success"></i> Ajouter un Étudiant</h1>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="#" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="matricule" class="form-label">Matricule</label>
                    <input type="text" id="matricule" name="matricule" class="form-control" placeholder="Ex: 21A567" required>
                </div>

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom complet</label>
                    <input type="text" id="nom" name="nom" class="form-control" placeholder="Ex: Koffi Marc" required>
                </div>

                <div class="mb-3">
                    <label for="filiere" class="form-label">Filière</label>
                    <select id="filiere" name="filiere" class="form-select" required>
                        <option value="">-- Sélectionner une filière --</option>
                        <option value="Informatique">Informatique</option>
                        <option value="Statistique">Statistique</option>
                        <option value="Economie">Économie</option>
                        <!-- Ajoute ici d'autres filières -->
                    </select>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-circle"></i> Enregistrer
                </button>
                <a href="{{ route('secretaire.gerer-etudiant') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </form>
        </div>
    </div>
@endsection
