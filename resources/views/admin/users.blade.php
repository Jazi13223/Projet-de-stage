@extends('adminlte::page')

@section('title', 'Gestion des Étudiants')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-0 text-primary">
            <i class="fas fa-user-graduate me-1"></i> Gestion des Étudiants
        </h1>
        <button class="btn btn-success" data-toggle="modal" data-target="#ajoutEtudiantModal">
            <i class="fas fa-user-plus"></i> Ajouter un Étudiant
        </button>
    </div>
@stop

@section('content')

    {{-- Messages de succès --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Messages d’erreur --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">
                <i class="fas fa-search"></i> Rechercher un Étudiant par Matricule
            </h3>
        </div>

        <div class="card-body">
            <form class="mb-4 d-flex" onsubmit="return false;">
                <input type="text" id="searchMatricule" class="form-control me-2" placeholder="Entrez le matricule...">
                <button class="btn btn-outline-primary" onclick="filtrerMatricule()">Rechercher</button>
            </form>

            @foreach ($etudiantsGroupes as $filiere => $etudiants)
                <div class="mb-3">
                    <h4 class="text-secondary"><i class="fas fa-folder-open me-1"></i> Filière : {{ $filiere }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover etudiantsTable">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Matricule</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($etudiants as $etudiant)
                                
                                    <tr>
                                        <td>{{ $etudiant->user->name }}</td>
                                        <td>{{ $etudiant->user->first_name }}</td>
                                        <td>{{ $etudiant->matricule }}</td>
                                        <td>{{ $etudiant->user->email }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning"
                                                data-toggle="modal"
                                                data-target="#modifierEtudiantModal"
                                                onclick="remplirForm(
                                                    '{{ $etudiant->user->id }}',
                                                    '{{ $etudiant->user->name }}',
                                                    '{{ $etudiant->user->first_name }}',
                                                    '{{ $etudiant->matricule }}',
                                                    '{{ $filiere }}',
                                                    '{{ $etudiant->user->email }}'
                                                )">
                                                <i class="fas fa-edit"></i> Modifier
                                            </button>
                                            <form method="POST" action="{{ route('admin.etudiants.delete', $etudiant->user->id) }}" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet étudiant ?')">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal : Ajout Étudiant -->
    <div class="modal fade" id="ajoutEtudiantModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.etudiants.store') }}">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-user-plus me-1"></i> Ajouter un Étudiant</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label>Nom</label><input type="text" class="form-control" name="name" required></div>
                        <div class="form-group"><label>Prénom</label><input type="text" class="form-control" name="first_name" required></div>
                        <div class="form-group"><label>Matricule</label><input type="text" class="form-control" name="matricule" required></div>
                        <div class="form-group">
                            <label>Filière</label>
                            <select class="form-control" name="filiere" required>
                                @foreach ($filieres as $filiere)
                                    <option value="{{ $filiere->name }}">{{ $filiere->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Année académique</label>
                            <select class="form-control" name="year_id" required>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}">{{ $year->academic_year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Semestre</label>
                            <select class="form-control" name="semester_id" required>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" required></div>
                        <div class="form-group"><label>Mot de passe</label><input type="password" class="form-control" name="password" required></div>
                        <div class="form-group"><label>Confirmer mot de passe</label><input type="password" class="form-control" name="password_confirmation" required></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button class="btn btn-success" type="submit">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal : Modifier Étudiant -->
    <div class="modal fade" id="modifierEtudiantModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formModifierEtudiant" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title"><i class="fas fa-edit me-1"></i> Modifier un Étudiant</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="form-group"><label>Nom</label><input type="text" id="edit_nom" class="form-control" name="name" required></div>
                        <div class="form-group"><label>Prénom</label><input type="text" id="edit_prenom" class="form-control" name="first_name" required></div>
                        <div class="form-group"><label>Matricule</label><input type="text" id="edit_matricule" class="form-control" name="matricule" required></div>
                        <div class="form-group">
                            <label>Filière</label>
                            <select class="form-control" id="edit_filiere" name="filiere" required>
                                @foreach ($filieres as $filiere)
                                    <option value="{{ $filiere->name }}">{{ $filiere->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group"><label>Email</label><input type="email" id="edit_email" class="form-control" name="email" required></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button class="btn btn-warning" type="submit">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop
@section('css')
    <style>
        table.etudiantsTable th,
        table.etudiantsTable td {
            white-space: nowrap;
            text-align: center;
            vertical-align: middle;
        }
        table.etudiantsTable th:nth-child(1),
        table.etudiantsTable td:nth-child(1),
        table.etudiantsTable th:nth-child(2),
        table.etudiantsTable td:nth-child(2),
        table.etudiantsTable th:nth-child(3),
        table.etudiantsTable td:nth-child(3),
        table.etudiantsTable th:nth-child(4),
        table.etudiantsTable td:nth-child(4),
        table.etudiantsTable th:nth-child(5),
        table.etudiantsTable td:nth-child(5) {
            width: 180px;
        }
    </style>
@endsection



@section('js')
    <script>
        function filtrerMatricule() {
            let input = document.getElementById('searchMatricule').value.toUpperCase();
            let tables = document.getElementsByClassName('etudiantsTable');

            Array.from(tables).forEach(table => {
                let tr = table.getElementsByTagName('tr');
                for (let i = 1; i < tr.length; i++) {
                    let td = tr[i].getElementsByTagName('td')[2];
                    if (td) {
                        let txtValue = td.textContent || td.innerText;
                        tr[i].style.display = txtValue.toUpperCase().indexOf(input) > -1 ? "" : "none";
                    }
                }
            });
        }

        function remplirForm(id, name, first_name, matricule, filiere, email) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_nom').value = name;
            document.getElementById('edit_prenom').value = first_name;
            document.getElementById('edit_matricule').value = matricule;
            document.getElementById('edit_filiere').value = filiere;
            document.getElementById('edit_email').value = email;

            // Modifier dynamiquement l'action du formulaire
            document.getElementById('formModifierEtudiant').action = '/admin/users/' + id;
        }
    </script>
@stop
