@extends('adminlte::page')

@section('title', 'Gérer les Étudiants')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-primary"><i class="fas fa-users"></i> Liste des Étudiants</h1>
        <button class="btn btn-success" data-toggle="modal" data-target="#ajouterEtudiantModal">
            <i class="fas fa-user-plus"></i> Ajouter un étudiant
        </button>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


   <form method="GET" action="{{ route('secretaire.gerer-etudiant') }}" class="mb-3">
    <div class="row" style="max-width: 500px;">
        <div class="col">
            <select name="filiere" class="form-control">
                <option value="">-- Filtrer par filière --</option>
                @foreach($filieres as $filiere)
                    <option value="{{ $filiere->name }}" @if(request('filiere') == $filiere->name) selected @endif>
                        {{ $filiere->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter"></i> Filtrer
            </button>
        </div>
    </div>
</form>


    <div class="card shadow-sm">
        <div class="card-header bg-white"><strong><i class="fas fa-list"></i> Étudiants inscrits</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Filière</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($etudiants as $etudiant)
                            <tr>
                                <td>{{ $etudiant->matricule }}</td>
                                <td>{{  $etudiant->user->name }}</td>
                                <td>{{  $etudiant->user->first_name }}</td>
                                <td>{{$etudiant->derniereInscription->filiere->name ?? 'Non renseignée'}}</td>

                                <td class="text-center">
                                    <button class="btn btn-info btn-sm"
                                            data-toggle="modal"
                                            data-target="#voirEtudiantModal"
                                            onclick="afficherDetailsEtudiant(
                                                '{{ $etudiant->matricule }}',
                                                '{{ $etudiant->user->name ?? ''}}',
                                                '{{ $etudiant->user->first_name ?? ''}}',
                                                 '{{ $etudiant->derniereInscription->filiere->name ?? 'Non renseignée' }}',
                                                 '{{ $etudiant->user->email ?? '' }}'
                                            )">
                                        <i class="fas fa-eye"></i> Voir
                                    </button>
                                    <button class="btn btn-warning btn-sm"
                                            data-toggle="modal"
                                            data-target="#modifierEtudiantModal"
                                            onclick="remplirFormulaireModification(
                                                {{ $etudiant->id }},
                                                '{{ $etudiant->matricule }}',
                                                '{{  $etudiant->user->name ?? '' }}',
                                                 '{{ $etudiant->user->first_name ?? '' }}',
                                                '{{ $etudiant->filiere->id ??''}}',
                                                '{{ $etudiant->email }}'
                                            )">
                                        <i class="fas fa-edit"></i> Modifier
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Voir étudiant --}}
    <div class="modal fade" id="voirEtudiantModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-user"></i> Détails de l'étudiant</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nom :</strong> <span id="voir_nom"></span></p>
                    <p><strong>Matricule :</strong> <span id="voir_matricule"></span></p>
                    <p><strong>Email :</strong> <span id="voir_email"></span></p>
                    <p><strong>Filière :</strong> <span id="voir_filiere"></span></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Ajouter étudiant --}}
    <div class="modal fade" id="ajouterEtudiantModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('secretaire.ajouter-etudiant') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-user-plus"></i> Ajouter un étudiant</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-md-6">
                            <label>Nom</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Prénom</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Matricule</label>
                            <input type="text" name="matricule" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Filière</label>
                            <select name="filiere_id" class="form-control" required>
                                <option value="">-- Choisir une filière --</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}">{{ $filiere->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="form-group col-md-6">
                            <label>Année académique</label>
                            <select name="year_id" class="form-control" required>
                                <option value="">-- Choisir une année --</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}">{{ $year->academic_year }}</option>
                                @endforeach
                            </select>
                        </div>

                         <div class="form-group col-md-6">
        <label>Semestre</label>
        <select name="semester_id" class="form-control" required>
            <option value="">-- Choisir un semestre --</option>
            @foreach($semesters as $semester)
                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
            @endforeach
        </select>
    </div>
                    
                        <div class="form-group col-md-6">
                            <label>Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Confirmation mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
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

    {{-- Modal Modifier étudiant --}}
    <div class="modal fade" id="modifierEtudiantModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('secretaire.modifier-etudiant', ':id') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-edit"></i> Modifier l'étudiant</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-md-6">
                            <label>Nom complet</label>
                            <input type="text" name="name" id="edit_nom" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Matricule</label>
                            <input type="text" name="matricule" id="edit_matricule" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Filière</label>
                            <select name="filiere_id" id="edit_filiere" class="form-control" required>
                                <option value="">-- Choisir une filière --</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}">{{ $filiere->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button class="btn btn-success">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function remplirFormulaireModification(id, matricule, nom, filiere_id, email) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_matricule').value = matricule;
            document.getElementById('edit_nom').value = nom;
            document.getElementById('edit_filiere').value = filiere_id;
            document.getElementById('edit_email').value = email;
        }

        function afficherDetailsEtudiant(matricule, nom, filiere, email) {
            document.getElementById('voir_matricule').textContent = matricule;
            document.getElementById('voir_nom').textContent = nom;
            document.getElementById('voir_filiere').textContent = filiere;
            document.getElementById('voir_email').textContent = email; 
        }
    </script>

    @if ($errors->any())
    <script>
        $(document).ready(function () {
            $('#ajouterEtudiantModal').modal('show');
        });
    </script>
@endif

@endsection
