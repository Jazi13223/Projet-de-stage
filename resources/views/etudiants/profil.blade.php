@extends('adminlte::page')

@section('title', 'Mon Profil')

@section('content')
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #f6f9fc, #e9f1f7);
    }

    .card-profil {
        background: linear-gradient(120deg, #ffffff, #f2f7fb);
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-top: 40px;
        transition: 0.3s ease;
    }

    .card-profil h2 {
        font-weight: 700;
        font-size: 26px;
        color: #2c3e50;
        margin-bottom: 25px;
        border-bottom: 2px solid #dfe6ec;
        padding-bottom: 10px;
    }

    .info-item {
        font-size: 16px;
        margin-bottom: 15px;
    }

    .info-item strong {
        color: #1a252f;
        width: 140px;
        display: inline-block;
    }

    .btn-action {
        background: linear-gradient(to right, #3c8dbc, #367fa9);
        color: #fff;
        border: none;
        padding: 10px 22px;
        border-radius: 6px;
        font-weight: 500;
        margin-right: 12px;
        box-shadow: 0 4px 10px rgba(60, 141, 188, 0.2);
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        background: linear-gradient(to right, #367fa9, #3c8dbc);
        transform: translateY(-1px);
    }

    .modal-header {
        background: #3c8dbc;
        color: white;
        border-bottom: none;
    }

    .modal-title {
        font-weight: 600;
        font-size: 20px;
    }

    .modal-footer .btn {
        border-radius: 4px;
        font-weight: 500;
    }

    .btn-cancel {
        background: #ddd;
        color: #333;
    }

    .btn-cancel:hover {
        background: #ccc;
    }

    .form-control {
        border-radius: 4px;
        box-shadow: none;
        border: 1px solid #ced4da;
    }
</style>

@php
    // Récupérer l'étudiant et sa dernière inscription
    $etudiant = Auth::user()->etudiant;
    $inscription = $etudiant ? $etudiant->inscriptions()->latest()->first() : null;
    $filiereNom = $inscription && $inscription->filiere ? $inscription->filiere->name : '';
    $selectedFiliereId = $inscription ? $inscription->filiere_id : null;
@endphp

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <strong>Succès !</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-profil">
                <h2>Mon Profil</h2>
                <div class="info-item"><strong>Nom :</strong> {{ Auth::user()->name }}</div>
                <div class="info-item"><strong>Prénom :</strong> {{ Auth::user()->first_name }}</div>
                <div class="info-item"><strong>Email :</strong> {{ Auth::user()->email }}</div>
                <div class="info-item"><strong>Filière :</strong> {{ $filiereNom }}</div>

                <div class="mt-4">
                    <button class="btn btn-action" data-toggle="modal" data-target="#editModal">
                        Modifier mes informations
                    </button>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-danger">Se déconnecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale de modification -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('etudiants.update') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Modifier mes informations</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="form-group">
                        <label for="first_name">Prénom</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" value="{{ Auth::user()->first_name }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ Auth::user()->email }}">
                    </div>
                    <div class="form-group">
                        <label for="filiere_id">Filière</label>
                        <select name="filiere_id" id="filiere_id" class="form-control" required>
                            @foreach ($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ $filiere->id == $selectedFiliereId ? 'selected' : '' }}>
                                    {{ $filiere->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-action">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
