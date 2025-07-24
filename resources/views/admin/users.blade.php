@extends('adminlte::page')

@section('title', 'Gestion des Étudiants')

@section('content_header')
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-user-graduate me-2"></i>Gestion des Étudiants</h1>
                <p class="text-muted">Gérez efficacement vos étudiants et leurs informations</p>
            </div>
            <button class="btn btn-success" data-toggle="modal" data-target="#ajoutEtudiantModal">
                <i class="fas fa-user-plus me-2"></i>Ajouter un Étudiant
            </button>
        </div>
    </div>
@stop

@section('content')

    {{-- Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Recherche --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-search me-2"></i>Recherche</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" id="searchMatricule" class="form-control" placeholder="Rechercher par matricule...">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary w-100" onclick="filtrerMatricule()">
                        <i class="fas fa-search me-2"></i>Rechercher
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des étudiants par filière --}}
    @foreach ($etudiantsGroupes as $filiere => $etudiants)
        <div class="card mb-4 filiere-section">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>{{ $filiere }}
                    <span class="badge bg-secondary ms-2">{{ count($etudiants) }} étudiant{{ count($etudiants) > 1 ? 's' : '' }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 etudiantsTable">
                        <thead >
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Matricule</th>
                                <th>Email</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($etudiants as $etudiant)
                                <tr>
                                    <td>{{ $etudiant->user->name }}</td>
                                    <td>{{ $etudiant->user->first_name }}</td>
                                    <td><span class="badge bg-primary">{{ $etudiant->matricule }}</span></td>
                                    <td>{{ $etudiant->user->email }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm me-1"
                                            data-toggle="modal"
                                            data-target="#modifierEtudiantModal"
                                            onclick="remplirForm('{{ $etudiant->user->id }}', '{{ $etudiant->user->name }}', '{{ $etudiant->user->first_name }}', '{{ $etudiant->matricule }}', '{{ $filiere }}', '{{ $etudiant->user->email }}')"
                                            title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                            data-toggle="modal"
                                            data-target="#confirmDeleteModal"
                                            onclick="setDeleteAction({{ $etudiant->user->id }}, '{{ $etudiant->user->name }} {{ $etudiant->user->first_name }}')"
                                            title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal : Ajout Étudiant -->
    <div class="modal fade" id="ajoutEtudiantModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.etudiants.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-user-plus me-2"></i>Ajouter un Étudiant
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nom</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" class="form-control" name="first_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Matricule</label>
                                    <input type="text" class="form-control" name="matricule" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Filière</label>
                                    <select class="form-control" name="filiere" required>
                                        @foreach ($filieres as $filiere)
                                            <option value="{{ $filiere->name }}">{{ $filiere->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Année académique</label>
                                    <select class="form-control" name="year_id" required>
                                        @foreach ($years as $year)
                                            <option value="{{ $year->id }}">{{ $year->academic_year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Semestre</label>
                                    <select class="form-control" name="semester_id" required>
                                        @foreach ($semesters as $semester)
                                            <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Confirmer mot de passe</label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal : Modifier Étudiant -->
    <div class="modal fade" id="modifierEtudiantModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formModifierEtudiant" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-edit me-2"></i>Modifier un Étudiant
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nom</label>
                                    <input type="text" id="edit_nom" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" id="edit_prenom" class="form-control" name="first_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Matricule</label>
                                    <input type="text" id="edit_matricule" class="form-control" name="matricule" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Filière</label>
                                    <select class="form-control" id="edit_filiere" name="filiere" required>
                                        @foreach ($filieres as $filiere)
                                            <option value="{{ $filiere->name }}">{{ $filiere->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" id="edit_email" class="form-control" name="email" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal : Confirmation de Suppression -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteEtudiantForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>Confirmation de suppression
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                            <p id="deleteMessage" class="mb-2">Voulez-vous vraiment supprimer cet étudiant ?</p>
                            <p class="text-muted small">Cette action est irréversible.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Confirmer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('css')
    <style>
        .content-header {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
        }
        
        .badge {
            font-size: 0.8em;
        }
        
        .btn {
            border-radius: 6px;
        }
        
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }

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
    </style>
@endsection

@section('js')
    <script>
        function filtrerMatricule() {
            let input = document.getElementById('searchMatricule').value.toUpperCase();
            let tables = document.getElementsByClassName('etudiantsTable');

            Array.from(tables).forEach(table => {
                let tr = table.getElementsByTagName('tr');
                let visibleRows = 0;
                
                for (let i = 1; i < tr.length; i++) {
                    let td = tr[i].getElementsByTagName('td')[2];
                    if (td) {
                        let txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(input) > -1) {
                            tr[i].style.display = "";
                            visibleRows++;
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
                
                const filiereSection = table.closest('.filiere-section');
                filiereSection.style.display = visibleRows === 0 && input !== '' ? 'none' : 'block';
            });
        }

        function remplirForm(id, name, first_name, matricule, filiere, email) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_nom').value = name;
            document.getElementById('edit_prenom').value = first_name;
            document.getElementById('edit_matricule').value = matricule;
            document.getElementById('edit_filiere').value = filiere;
            document.getElementById('edit_email').value = email;
            document.getElementById('formModifierEtudiant').action = '/admin/users/' + id;
        }

        function setDeleteAction(userId, fullName) {
            document.getElementById('deleteEtudiantForm').action = '/admin/users/' + userId;
            document.getElementById('deleteMessage').textContent = `Voulez-vous vraiment supprimer l'étudiant ${fullName} ?`;
        }

        // Recherche en temps réel
        document.getElementById('searchMatricule').addEventListener('input', function() {
            filtrerMatricule();
        });

        // Auto-hide alerts
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    </script>
@stop