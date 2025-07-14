@extends('adminlte::page')

@section('title', 'Réclamations')

@section('content_header')
    <h1 class="mb-4 text-primary"><i class="fas fa-exclamation-circle"></i> Réclamations des Étudiants</h1>
@endsection

@section('content')
    <div class="card shadow">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des Réclamations</h5>
            <span class="badge bg-info text-white p-2">Total : {{ count($reclamations) }}</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Étudiant</th>
                            <th>UE / ECUE</th>
                            <th>Motif</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reclamations as $reclamation)
                            <tr>
                                <td><strong>{{ $reclamation['etudiant'] }}</strong></td>
                                <td>
                                    {{ $reclamation['ue'] }}
                                    @if($reclamation['ecue'])
                                        / {{ $reclamation['ecue'] }}
                                    @endif
                                </td>
                                <td>{{ $reclamation['motif'] }}</td>
                                <td>
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
                                </td>
                                <td class="text-center">
                                    @if ($reclamation['status'] === 'en_attente')
                                        <a href="{{ route('secretaire.traiter-reclamation', ['id' => $reclamation['id'], 'mode' => 'résolue']) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit"></i> Résolue
                                        </a>
                                    @else
                                        <a href="{{ route('secretaire.traiter-reclamation', ['id' => $reclamation['id'], 'mode' => 'view']) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
