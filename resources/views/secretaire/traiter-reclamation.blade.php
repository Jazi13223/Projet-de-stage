@extends('adminlte::page')

@section('title', 'Traitement Réclamation')

@section('content_header')
    <h1 class="mb-3 text-primary">
        <i class="fas fa-tools"></i> Traitement de la Réclamation
    </h1>
@endsection

@section('content')
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Réclamation #{{ $reclamation['id'] }}</h5>
            <a href="{{ route('secretaire.manage-reclamation') }}" class="btn btn-sm btn-dark text-light">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <div class="card-body bg-light">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-muted">Informations de l'étudiant</h5>
                    <p><strong>Nom :</strong> <span class="text-dark">{{ $reclamation['etudiant'] ?? 'Non disponible' }}</span></p>
                    <p><strong>Matricule :</strong> <span class="text-dark">{{ $reclamation['matricule'] ?? 'N/A' }}</span></p>
                    <p><strong>Email :</strong> <span class="text-dark">{{ $reclamation['email'] ?? 'non disponible' }}</span></p>
                </div>
                <div class="col-md-6">
                    <h5 class="text-muted">Informations sur la réclamation</h5>
                    <p>
                        <strong>UE / ECUE :</strong>
                        <span class="text-dark">
                            {{ $reclamation['ue'] ?? 'UE inconnue' }}
                            @if(!empty($reclamation['ecue']))
                                - {{ $reclamation['ecue'] }}
                            @endif
                        </span>
                    </p>
                    <p><strong>Motif :</strong> <span class="text-dark">{{ $reclamation['motif'] }}</span></p>
                    <p><strong>Date :</strong> <span class="text-dark">{{ $reclamation['date_submission'] ?? 'Non précisée' }}</span></p>
                    <p>
                        <strong>Status :</strong>
                        @if ($reclamation['status'] === 'en_attente')
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock"></i> En attente
                            </span>
                        @elseif ($reclamation['status'] === 'résolue')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> Résolue
                            </span>
                        @elseif ($reclamation['status'] === 'refusée')
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle"></i> Refusée
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            <hr>

            @if ($mode === 'résolue')
                <form method="POST" action="{{ route('secretaire.update-reclamation') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $reclamation['id'] }}">

                    <h5 class="mb-3 text-primary"><i class="fas fa-pen"></i> Traitement</h5>

                    <div class="form-group">
                        <label for="reponse" class="font-weight-bold">Réponse / Décision</label>
                        <textarea id="reponse" name="reponse" rows="4" class="form-control shadow-sm" placeholder="Écrivez la réponse à l'étudiant ici..."></textarea>
                    </div>

                    <div class="form-group mt-3">
                        <label for="statut" class="font-weight-bold">Changer le status</label>
                        <select id="statut" name="statut" class="form-control shadow-sm">
                            <option value="en_attente" {{ $reclamation['status'] == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="traite" {{ $reclamation['status'] == 'résolue' ? 'selected' : '' }}>Résolue</option>
                            <option value="rejete" {{ $reclamation['status'] == 'refusée' ? 'selected' : '' }}>Refusée</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success px-4 py-2 text-white font-weight-bold shadow-lg">
                            <i class="fas fa-check-circle"></i> Valider la décision
                        </button>
                    </div>
                </form>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Cette réclamation est en lecture seule. Aucun traitement n'est autorisé en mode "voir".
                </div>
            @endif
        </div>
    </div>
@endsection
