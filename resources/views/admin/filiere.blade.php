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
                                            <ul class="list-unstyled mt-2 mb-0 pl-2 text-muted small">
                                                @foreach ($assignment->ue->ecues as $ecue)
                                                    <li class="mb-1">
                                                        <i class="fas fa-circle-notch fa-xs mr-1 text-secondary"></i>
                                                        {{ $ecue->name }}
                                                        <span class="ml-1 text-secondary font-italic">(Coeff. {{ $ecue->coefficient }})</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="mt-2"><em>Pas d’ECUE</em></div>
                                        @endif

                                        <!-- Boutons modifier et supprimer -->
                                        <button class="btn btn-sm btn-outline-primary mt-2" data-toggle="modal"
                                            data-target="#modifierUeModal{{ $assignment->id }}" title="Modifier cette UE">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-outline-danger mt-2" data-toggle="modal"
                                            data-target="#confirmDeleteModal{{ $assignment->id }}" title="Supprimer cette UE">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>

                                    <!-- Modal : Modifier UE -->
                                    <div class="modal fade" id="modifierUeModal{{ $assignment->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.filieres.update') }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="id" value="{{ $filiere->id }}">
                                                    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Modifier {{ $assignment->ue->name }}</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label>Nom de la filière</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $filiere->name }}" required>
                                                        <hr>
                                                        <h6>Modification de l’UE</h6>
                                                        <div class="form-group">
                                                            <label>Nom de l'UE</label>
                                                            <input type="text" name="ue_name" class="form-control" value="{{ $assignment->ue->name }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Coefficient de l'UE</label>
                                                            <input type="number" name="coefficient" class="form-control" value="{{ $assignment->coefficient }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="semester_id">Semestre</label>
                                                            <select name="semester_id" class="form-control">
                                                                @foreach($semesters as $semester)
                                                                    <option value="{{ $semester->id }}" {{ $assignment->ue->semester_id == $semester->id ? 'selected' : '' }}>
                                                                        {{ $semester->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="year_id">Année</label>
                                                            <select name="year_id" class="form-control">
                                                                @foreach($years as $year)
                                                                    <option value="{{ $year->id }}" {{ $assignment->ue->year_id == $year->id ? 'selected' : '' }}>
                                                                        {{ $year->academic_year }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <hr>
                                                        <h6>ECUEs (facultatif)</h6>
                                                        @php $ecues = $assignment->ue->ecues; @endphp
                                                        <div class="form-group">
                                                            <label>ECUE 1</label>
                                                            <input type="text" name="ecue1_name" class="form-control" value="{{ $ecues[0]->name ?? '' }}">
                                                            <input type="number" name="ecue1_coefficient" class="form-control mt-1" value="{{ $ecues[0]->coefficient ?? '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>ECUE 2</label>
                                                            <input type="text" name="ecue2_name" class="form-control" value="{{ $ecues[1]->name ?? '' }}">
                                                            <input type="number" name="ecue2_coefficient" class="form-control mt-1" value="{{ $ecues[1]->coefficient ?? '' }}">
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

                                    <!-- Modal : Supprimer UE -->
                                    <div class="modal fade" id="confirmDeleteModal{{ $assignment->id }}" tabindex="-1">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Confirmer la suppression</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir supprimer cette UE et ses ECUEs associés ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('admin.ues.destroy', $assignment->ue->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <em>Aucune UE assignée</em>
                                @endforelse
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <form action="{{ route('admin.filieres.destroy', $filiere->id) }}" method="POST" class="mr-2">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                    </form>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#assignUeModal{{ $filiere->id }}">🔗</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal : Assigner une nouvelle UE -->
                        <div class="modal fade" id="assignUeModal{{ $filiere->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.filieres.assignUe', $filiere->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="admin_id" value="{{ auth()->user()->id }}">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Assigner une UE à {{ $filiere->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nom de l'UE</label>
                                                <input type="text" name="ue_name" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Coefficient</label>
                                                <input type="number" name="coefficient" class="form-control" required min="1">
                                            </div>
                                            <div class="form-group">
                                                <label>Semestre</label>
                                                <select name="semester_id" class="form-control" required>
                                                    @foreach($semesters as $semester)
                                                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Année</label>
                                                <select name="year_id" class="form-control" required>
                                                    @foreach($years as $year)
                                                        <option value="{{ $year->id }}">{{ $year->academic_year }}</option>
                                                    @endforeach
                                                </select>
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
