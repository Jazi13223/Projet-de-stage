@extends('adminlte::page')

@section('title', 'Gérer les UE / ECUE')

@section('content_header')
    <h1 class="text-primary">Gestion des UE / ECUE</h1>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow">
    <div class="card-header bg-gradient-info text-white d-flex justify-content-between">
        <h4 class="mb-0">Tableau combiné des UE et ECUE par filière</h4>
        <button class="btn btn-success" data-toggle="modal" data-target="#addModal">Ajouter UE / ECUE</button>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-hover table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Filière</th>
                    <th>UE</th>
                    <th>Coeff UE</th>
                    <th>ECUE</th>
                    <th>Coeff ECUE</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filieres as $filiere)
                    @php $firstFiliere = true; @endphp
                    @foreach($filiere->ues as $ue)
                        @php
                            $ecues = $ue->ecues;
                            $firstUe = true;
                        @endphp
                        @if($ecues->isEmpty())
                            <tr>
                                @if($firstFiliere)
                                    <td rowspan="{{ $filiere->ues->flatMap->ecues->count() ?: $filiere->ues->count() }}">{{ $filiere->name }}</td>
                                    @php $firstFiliere = false; @endphp
                                @endif
                                <td>{{ $ue->name }}</td>
                                <td>{{ $ue->coefficient }}</td>
                                <td colspan="2" class="text-muted text-center">Aucun ECUE</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editUeModal{{ $ue->id }}">Modifier</button>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteUeModal{{ $ue->id }}">Supprimer</button>
                                </td>
                            </tr>
                        @elseif($ecues->count() === 2)
                            @foreach($ecues as $index => $ecue)
                                <tr>
                                    @if($firstFiliere)
                                        <td rowspan="{{ $filiere->ues->flatMap->ecues->count() ?: 1 }}">{{ $filiere->name }}</td>
                                        @php $firstFiliere = false; @endphp
                                    @endif

                                    @if($firstUe)
                                        <td rowspan="2">{{ $ue->name }}</td>
                                        <td rowspan="2">{{ $ue->coefficient }}</td>
                                        @php $firstUe = false; @endphp
                                    @endif

                                    <td>{{ $ecue->name }}</td>
                                    <td>{{ $ecue->coefficient }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editEcueModal{{ $ecue->id }}">Modifier</button>
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteEcueModal{{ $ecue->id }}">Supprimer</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        <!-- Modal Modifier UE -->
                        <div class="modal fade" id="editUeModal{{ $ue->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <form method="POST" action="{{ route('secretaire.update-ue', $ue->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title">Modifier l’UE</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nom de l’UE</label>
                                                <input type="text" name="name" class="form-control" value="{{ $ue->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Coefficient</label>
                                                <input type="number" name="coefficient" class="form-control" value="{{ $ue->coefficient }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Supprimer UE -->
                        <div class="modal fade" id="deleteUeModal{{ $ue->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <form method="POST" action="{{ route('secretaire.delete-ue', $ue->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Confirmer la suppression</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez-vous vraiment supprimer l’UE <strong>{{ $ue->name }}</strong> ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modals Modifier & Supprimer ECUE -->
                        @foreach($ecues as $ecue)
                            <!-- Modifier ECUE -->
                            <div class="modal fade" id="editEcueModal{{ $ecue->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <form method="POST" action="{{ route('secretaire.update-ecue', $ecue->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title">Modifier l’ECUE</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nom de l’ECUE</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $ecue->name }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Coefficient</label>
                                                    <input type="number" name="coefficient" class="form-control" value="{{ $ecue->coefficient }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Supprimer ECUE -->
                            <div class="modal fade" id="deleteEcueModal{{ $ecue->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <form method="POST" action="{{ route('secretaire.delete-ecue', $ecue->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Confirmer la suppression</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                Voulez-vous vraiment supprimer l’ECUE <strong>{{ $ecue->name }}</strong> ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ajouter UE avec ECUE facultatifs -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('secretaire.ajouter-ue-ecue') }}" id="addUeEcueForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-gradient-info text-white">
                    <h5 class="modal-title">Ajouter une UE</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Filière</label>
                        <select name="filiere_id" class="form-control" required>
                            <option value="">Sélectionner une filière</option>
                            @foreach($filieres as $f)
                                <option value="{{ $f->id }}">{{ $f->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Année académique</label>
                        <select name="year_id" class="form-control" required>
                            <option value="">Sélectionner une année</option>
                            @foreach($years as $y)
                                <option value="{{ $y->id }}">{{ $y->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Semestre</label>
                        <select name="semester_id" class="form-control" required>
                            <option value="">Sélectionner un semestre</option>
                            @foreach($semesters as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nom de l’UE</label>
                        <input type="text" name="ue_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Coefficient de l’UE</label>
                        <input type="number" name="ue_coefficient" class="form-control" min="1" required>
                    </div>

                    <hr>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="addEcuesCheckbox">
                        <label class="form-check-label font-weight-bold text-info" for="addEcuesCheckbox">Ajouter des ECUE à cette UE</label>
                    </div>

                    <div id="ecueFields" style="display: none;">
                        <div class="form-group">
                            <label>ECUE 1 - Nom</label>
                            <input type="text" name="ecue1_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>ECUE 1 - Coefficient</label>
                            <input type="number" name="ecue1_coefficient" class="form-control" min="1">
                        </div>

                        <div class="form-group">
                            <label>ECUE 2 - Nom</label>
                            <input type="text" name="ecue2_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>ECUE 2 - Coefficient</label>
                            <input type="number" name="ecue2_coefficient" class="form-control" min="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script>
    document.getElementById('addEcuesCheckbox').addEventListener('change', function () {
        const ecueFields = document.getElementById('ecueFields');
        ecueFields.style.display = this.checked ? 'block' : 'none';
    });

    document.getElementById('addUeEcueForm').addEventListener('submit', function(event) {
        const isChecked = document.getElementById('addEcuesCheckbox').checked;
        if (isChecked) {
            const e1 = document.querySelector('[name="ecue1_name"]').value.trim();
            const e2 = document.querySelector('[name="ecue2_name"]').value.trim();
            const c1 = document.querySelector('[name="ecue1_coefficient"]').value.trim();
            const c2 = document.querySelector('[name="ecue2_coefficient"]').value.trim();

            if (!e1 || !e2 || !c1 || !c2) {
                alert('Veuillez remplir les deux ECUE (noms et coefficients).');
                event.preventDefault();
            }
        }
    });
</script>
@endsection
