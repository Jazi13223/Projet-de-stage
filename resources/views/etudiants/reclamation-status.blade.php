@extends('layouts.app')

@section('title', 'Statut de la Réclamation')

@section('content')
    <div class="container">
        <h1 class="display-4 text-primary text-center mb-4">Statut de la Réclamation</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>

            {{-- Détails de la réclamation --}}
            @if(session('reclamation'))
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">Détails de la Réclamation</div>
                    <div class="card-body">
                        <p><strong>UE :</strong> {{ session('reclamation.ue') }}</p>
                        <p><strong>ECUE :</strong> {{ session('reclamation.ecue') ?? 'Aucun' }}</p>
                        <p><strong>Type de note :</strong> {{ session('reclamation.note_type') ?? 'Non précisé' }}</p>
                        <p><strong>Note concernée :</strong> {{ session('reclamation.note') ?? 'Non précisée' }}</p>
                        <p><strong>Description :</strong> {{ session('reclamation.description') }}</p>
                    </div>
                </div>
            @endif
        @else
            <div class="alert alert-warning">
                Aucune réclamation soumise pour le moment.
            </div>
        @endif

        {{-- Boutons de navigation --}}
        <div class="mt-4 d-flex justify-content-center gap-3">
            <a href="{{ route('etudiants.dashboard') }}" class="btn btn-outline-primary">Retour au tableau de bord</a>
            <a href="{{ route('etudiants.notes') }}" class="btn btn-outline-secondary">Voir mes notes</a>
        </div>
    </div>
@endsection
