@extends('adminlte::page')

@section('title', 'Faire une Réclamation')

@section('content_header')
    <h1 class="text-center  font-weight-bold">Faire une Réclamation</h1>
@stop

@section('content')
    <div class="card shadow-lg rounded">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="card-title font-weight-bold">Soumettre une Réclamation</h3>
        </div>
        <div class="card-body">
            <!-- Affichage des messages d'erreur -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Affichage des messages de succès -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Formulaire de réclamation -->
            <form action="{{ route('etudiants.reclamation-form') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Type de réclamation -->
                <div class="form-group mb-4">
                    <label for="type_reclamation" class="font-weight-bold text-muted">Type de Réclamation</label>
                    <select class="form-control select2" id="type_reclamation" name="type_reclamation" required>
                        <option value="">Sélectionnez un type</option>
                        <option value="Note Incorrecte">Note Incorrecte</option>
                        <option value="Matière Manquante">Matière Manquante</option>
                        <option value="Problème d'ECUE">Problème d'ECUE</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>

                <!-- UE concernée -->
                <div class="form-group mb-4">
                    <label for="ue" class="font-weight-bold text-muted">Unité d'Enseignement (UE)</label>
                    <select class="form-control select2" id="ue" name="ue" required>
                        <option value="">Sélectionnez l'UE concernée</option>
                        <option value="1">UE 1 - Mathématiques</option>
                        <option value="2">UE 2 - Informatique</option>
                        <option value="3">UE 3 - Physique</option>
                        <option value="4">UE 4 - Chimie</option>
                    </select>
                </div>

                <!-- ECUE concerné -->
                <div class="form-group mb-4" id="ecue-container">
                    <label for="ecue" class="font-weight-bold text-muted">Élément Constitutif d'UE (ECUE)</label>
                    <select class="form-control select2" id="ecue" name="ecue">
                        <option value="">Sélectionnez l'ECUE concerné</option>
                        <option value="1">ECUE 1 - Algèbre</option>
                        <option value="2">ECUE 2 - Programmation</option>
                        <option value="3">ECUE 3 - Optique</option>
                        <option value="4">ECUE 4 - Thermodynamique</option>
                    </select>
                </div>

                <!-- Type de note -->
                <div class="form-group mb-4">
                    <label for="type_note" class="font-weight-bold text-muted">Type de Note</label>
                    <select class="form-control select2" id="type_note" name="type_note" required>
                        <option value="">Sélectionnez le type de note</option>
                        <option value="Interro">Interro</option>
                        <option value="Devoir">Devoir</option>
                        <option value="Projet">Projet</option>
                        <option value="Examen">Examen</option>
                    </select>
                </div>

                <!-- Note concernée -->
                <div class="form-group mb-4">
                    <label for="note" class="font-weight-bold text-muted">Note concernée (sur 20)</label>
                    <input type="number" class="form-control" id="note" name="note" required min="0" max="20" step="0.1" value="{{ old('note') }}">
                </div>

                <!-- Description de la réclamation -->
                <div class="form-group mb-4">
                    <label for="description" class="font-weight-bold text-muted">Description de la Réclamation</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                </div>

                <!-- Fichier joint -->
                <div class="form-group mb-4">
                    <label for="documents" class="font-weight-bold text-muted">Documents (facultatif)</label>
                    <input type="file" class="form-control-file" id="documents" name="documents">
                </div>

                <!-- Bouton de soumission -->
                <center><button type="submit" class="btn btn-primary ">Soumettre la Réclamation</button></center>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        // Initialisation des éléments select2
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: "Sélectionnez une option"
            });
        });
    </script>
@stop

@section('css')
    <style>
        /* Personnalisation des alertes */
        .alert ul {
            list-style-type: none;
            padding-left: 0;
        }
        .alert li {
            margin-bottom: 5px;
        }

        /* Bouton personnalisé */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 15px;
            font-size: 1.2rem;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        /* Card design */
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Champs de formulaire */
        .form-control {
            border-radius: 0.375rem;
        }
    </style>
@stop
