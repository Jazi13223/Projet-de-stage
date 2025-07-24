@extends('adminlte::page')

@section('title', 'Gestion des Notes')

@section('content_header')
    <h1 class="text-primary mb-4"><i class="fas fa-clipboard-list"></i> Gestion des Notes</h1>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<form class="mb-4 d-flex" method="GET" action="{{ route('secretaire.note') }}">
    <input type="text" name="matricule" class="form-control me-2" placeholder="Rechercher un étudiant par matricule..." required>
    <button class="btn btn-primary btn-sm" type="submit" style="white-space: nowrap;"><i class="fas fa-search"></i> Rechercher</button>
</form>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <strong><i class="fas fa-file-upload text-primary"></i> Importer un fichier de notes</strong>
    </div>
    <div class="card-body">
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8 mb-2">
                    <input type="file" name="fichier_notes" class="form-control" required>
                </div>
                <div class="col-md-4 mb-2">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-cloud-upload-alt"></i> Importer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="mb-3 text-end">
    <button class="btn btn-success" data-toggle="modal" data-target="#ajouterNoteModal">
        <i class="fas fa-plus-circle"></i> Ajouter une note manuellement
    </button>

     <!-- Bouton Calculer la moyenne -->
    <button class="btn btn-info ms-2" data-toggle="modal" data-target="#calculerMoyenneModal">
        <i class="fas fa-calculator"></i> Calculer la moyenne
    </button>
</div>

@isset($etudiant)
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <strong><i class="fas fa-list"></i> Notes de l'étudiant : {{ $etudiant->user->name ?? '-' }} ({{ $etudiant->matricule }})</strong>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>Étudiant</th>
                        <th>UE</th>
                        <th>ECUE</th>
                        <th>Interro</th>
                        <th>Devoir</th>
                        <th>Examen</th>
                        <th>Projet</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($ues as $ue)
    @if($ue->ecues->isNotEmpty())
        @foreach($ue->ecues as $ecue)
            @php
                $ecueId = $ecue->id;
                $noteSet = $notesMap[$ue->id][$ecueId] ?? [];
            @endphp
            <tr class="text-center align-middle">
                <td>{{ $etudiant->user->name ?? '-' }}</td>
                <td>{{ $ue->name }}</td>
                <td>{{ $ecue->name }}</td>
                <td>{{ $noteSet['interro']->value ?? '-' }}</td>
                <td>{{ $noteSet['devoir']->value ?? '-' }}</td>
                <td>{{ $noteSet['examen']->value ?? '-' }}</td>
                <td>{{ $noteSet['projet']->value ?? '-' }}</td>
                <td>
                    @foreach($noteSet as $type => $note)
                        <button class="btn btn-warning btn-sm mb-1" data-toggle="modal" data-target="#modifierNoteModal"
                            data-id="{{ $note->id }}"
                            data-ecue="{{ $ecue->name }}"
                            data-type="{{ $type }}"
                            data-note="{{ $note->value }}">
                            <i class="fas fa-edit"></i> {{ ucfirst($type) }}
                        </button><br>
                    @endforeach
                </td>
            </tr>
        @endforeach
    @else
        @php
            // Si l'UE n'a pas d'ECUEs, on gère les notes de l'UE directement
            $noteSet = $notesMap[$ue->id]['none'] ?? [];
        @endphp
        <tr class="text-center align-middle">
            <td>{{ $etudiant->user->name ?? '-' }}</td>
            <td>{{ $ue->name }}</td>
            <td><em>Aucun ECUE</em></td>
            <td>{{ $noteSet['interro']->value ?? '-' }}</td>
            <td>{{ $noteSet['devoir']->value ?? '-' }}</td>
            <td>{{ $noteSet['examen']->value ?? '-' }}</td>
            <td>{{ $noteSet['projet']->value ?? '-' }}</td>
            <td>
                @foreach($noteSet as $type => $note)
                    <button class="btn btn-warning btn-sm mb-1" data-toggle="modal" data-target="#modifierNoteModal"
                        data-id="{{ $note->id }}"
                        data-ecue="Aucun ECUE"
                        data-type="{{ $type }}"
                        data-note="{{ $note->value }}">
                        <i class="fas fa-edit"></i> {{ ucfirst($type) }}
                    </button><br>
                @endforeach
            </td>
        </tr>
    @endif
@endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
@endisset

{{-- Modal : Ajouter une note --}}
<div class="modal fade" id="ajouterNoteModal" tabindex="-1" role="dialog" aria-labelledby="ajouterNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('secretaire.ajouterNote') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Ajouter une note</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label>Matricule de l’étudiant</label>
                        <input type="text" name="etudiant" class="form-control" placeholder="Ex: 20230123" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>UE</label>
                        <input type="text" name="ue" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>ECUE (facultatif)</label>
                        <input type="text" name="ecue" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Type de note</label>
                        <select name="type" class="form-control" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="interro">Interro</option>
                            <option value="devoir">Devoir</option>
                            <option value="examen">Examen</option>
                            <option value="projet">Projet</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Valeur</label>
                        <input type="number" step="0.01" name="note" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal : Modifier une note --}}
<div class="modal fade" id="modifierNoteModal" tabindex="-1" role="dialog" aria-labelledby="modifierNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('secretaire.modifierNote', ['id' => ':id']) }}" method="POST">
                @csrf 
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Modifier la note</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label>ECUE</label>
                        <input type="text" name="ecue" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Type de note</label>
                        <select class="form-control" name="type_note">
                            <option value="interro">Interro</option>
                            <option value="devoir">Devoir</option>
                            <option value="examen">Examen</option>
                            <option value="projet">Projet</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Valeur</label>
                        <input type="number" step="0.01" name="valeur" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    $('#modifierNoteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var ecue = button.data('ecue');
        var type = button.data('type');
        var note = button.data('note');

        var modal = $(this);
        modal.find('form').attr('action', '/secretaire/modifier-note/' + id);
        modal.find('[name="ecue"]').val(ecue);
        modal.find('[name="type_note"]').val(type);
        modal.find('[name="valeur"]').val(note);
    });
</script>
@endpush
