@extends('adminlte::page')

@section('title', 'Gestion des Filières')

@section('content_header')
    <h1 class="mb-3 text-primary"><i class="fas fa-graduation-cap me-1"></i> Gestion des Filières</h1>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@elseif(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Liste des Filières</h3>
        <button class="btn btn-success" data-toggle="modal" data-target="#ajoutFiliereModal">➕ Ajouter une filière</button>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom de la Filière</th>
                        <th>UEs & ECUEs Affectées</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($filieres as $filiere)
                        <tr>
                            <td>{{ $filiere->id }}</td>
                            <td>{{ $filiere->name }}</td>
                            <td>
                                @forelse ($filiere->ue_assignments as $assignment)
                                    <div class="mb-2 p-2 border rounded bg-light">
                                        <strong class="text-dark">{{ $assignment->ue->name }}</strong>
                                        <span class="text-muted">(Coeff: {{ $assignment->coefficient }})</span>
                                        @if ($assignment->ue->ecues->count())
                                            <ul class="pl-3">
                                                @foreach ($assignment->ue->ecues as $ecue)
                                                    <li>{{ $ecue->name }} <span class="badge badge-info">Coeff: {{ $ecue->coefficient }}</span></li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div><em>Pas d’ECUE</em></div>
                                        @endif
                                    </div>
                                @empty
                                    <em>Aucune UE assignée</em>
                                @endforelse
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#modifierFiliereModal{{ $filiere->id }}">✏️</button>

                                    <form action="{{ route('admin.filieres.destroy', $filiere->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Confirmer la suppression ?')">🗑️</button>
                                    </form>

                                    <button class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#assignUeModal{{ $filiere->id }}">🔗</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal : Modifier la filière -->
                        <div class="modal fade" id="modifierFiliereModal{{ $filiere->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.filieres.update') }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="id" value="{{ $filiere->id }}">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Modifier {{ $filiere->name }}</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <label>Nom de la filière</label>
                                            <input type="text" name="name" class="form-control" value="{{ $filiere->name }}" required>

                                            <hr>
                                            <h6>Nouvelle UE à associer</h6>

                                            <div class="form-group">
                                                <label>Nom de l'UE</label>
                                                <input type="text" name="ue_name" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Coefficient de l'UE</label>
                                                <input type="number" name="coefficient" class="form-control" min="1">
                                            </div>

                                            <h6>ECUEs (facultatif)</h6>
                                            <div class="form-group">
                                                <label>ECUE 1</label>
                                                <input type="text" name="ecue1_name" class="form-control">
                                                <input type="number" name="ecue1_coefficient" class="form-control mt-1" placeholder="Coefficient">
                                            </div>

                                            <div class="form-group">
                                                <label>ECUE 2</label>
                                                <input type="text" name="ecue2_name" class="form-control">
                                                <input type="number" name="ecue2_coefficient" class="form-control mt-1" placeholder="Coefficient">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <button class="btn btn-primary" type="submit">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal : Assigner une UE -->
                        <div class="modal fade" id="assignUeModal{{ $filiere->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.filieres.assignUe', $filiere->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Assigner une nouvelle UE à {{ $filiere->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nom de l'UE</label>
                                                <input type="text" name="ue_name" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Coefficient de l'UE</label>
                                                <input type="number" name="coefficient" class="form-control" required min="1">
                                            </div>

                                            <hr>
                                            <h6>ECUEs (facultatif)</h6>

                                            <div class="form-group">
                                                <label>ECUE 1</label>
                                                <input type="text" name="ecue1_name" class="form-control">
                                                <input type="number" name="ecue1_coefficient" class="form-control mt-1">
                                            </div>

                                            <div class="form-group">
                                                <label>ECUE 2</label>
                                                <input type="text" name="ecue2_name" class="form-control">
                                                <input type="number" name="ecue2_coefficient" class="form-control mt-1">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <button class="btn btn-warning" type="submit">Assigner</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal : Ajouter une filière -->
<div class="modal fade" id="ajoutFiliereModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.filieres.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Ajouter une Filière</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" placeholder="Nom de la filière" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button class="btn btn-success" type="submit">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
