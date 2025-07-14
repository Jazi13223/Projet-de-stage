@extends('adminlte::page')

@section('title', 'Gérer les Secrétaires')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-primary"><i class="fas fa-user-tie me-1"></i> Gestion des Secrétaires</h1>
        <button class="btn btn-success" data-toggle="modal" data-target="#ajouterModal">
            <i class="fas fa-plus-circle me-1"></i> Ajouter un secrétaire
        </button>
    </div>
@stop

@section('content')

    {{-- Filtres --}}
    <div class="my-3 d-flex align-items-center flex-wrap gap-2">
        <label for="filtreStatut" class="fw-bold me-2">Filtrer par statut :</label>
        <select id="filtreStatut" class="form-control w-auto">
            <option value="Tous">Tous</option>
            <option value="Actif">Actif</option>
            <option value="Inactif">Inactif</option>
        </select>
    </div>

    {{-- Liste des secrétaires --}}
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Liste des Secrétaires</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="listeSecretaires">
                    @php
                        $secretaires = [
                            ['id' => 1, 'nom' => 'Koffi M.', 'email' => 'koffi@example.com', 'statut' => 'Actif'],
                            ['id' => 2, 'nom' => 'Amoussou L.', 'email' => 'amoussou@example.com', 'statut' => 'Inactif'],
                            ['id' => 3, 'nom' => 'Houénou M.', 'email' => 'houenou@example.com', 'statut' => 'Actif'],
                        ];
                    @endphp

                    @foreach($secretaires as $sec)
                        <tr data-statut="{{ $sec['statut'] }}">
                            <td>{{ $sec['nom'] }}</td>
                            <td>{{ $sec['email'] }}</td>
                            <td>
                                <span class="badge bg-{{ $sec['statut'] === 'Actif' ? 'success' : 'secondary' }}">
                                    {{ $sec['statut'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info me-1"
                                    data-toggle="modal"
                                    data-target="#voirModal"
                                    onclick="voirSecretaire('{{ $sec['nom'] }}', '{{ $sec['email'] }}', '{{ $sec['statut'] }}')">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button class="btn btn-sm btn-warning me-1"
                                    data-toggle="modal"
                                    data-target="#modifierModal"
                                    onclick="remplirFormulaire('{{ $sec['id'] }}', '{{ $sec['nom'] }}', '{{ $sec['email'] }}', '{{ $sec['statut'] }}')">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Historique des actions --}}
    <div class="mt-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3 class="card-title">Historique des Activités</h3>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">Connexion de Koffi M. le 2025-06-25</li>
                    <li class="list-group-item">Ajout d’un étudiant par Amoussou L. le 2025-06-24</li>
                    <li class="list-group-item">Suppression d’une note par Houénou M. le 2025-06-23</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Notifications système --}}
    <div class="mt-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title">Notifications Système</h3>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item text-warning">⚠️ Tentative d’accès non autorisé le 2025-06-24</li>
                    <li class="list-group-item text-success">✅ Mise à jour réussie d’un étudiant</li>
                    <li class="list-group-item text-danger">❌ Échec d’une suppression le 2025-06-22</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Modal : Voir Secrétaire --}}
    <div class="modal fade" id="voirModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-user"></i> Détails du Secrétaire</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p><strong>Nom :</strong> <span id="voir_nom"></span></p>
                    <p><strong>Email :</strong> <span id="voir_email"></span></p>
                    <p><strong>Statut :</strong> <span id="voir_statut"></span></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal : Modifier Secrétaire --}}
    <div class="modal fade" id="modifierModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title"><i class="fas fa-edit"></i> Modifier Secrétaire</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label>Nom complet</label>
                            <input type="text" class="form-control" id="edit_nom" name="nom">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                        <div class="form-group">
                            <label>Statut</label>
                            <select class="form-control" id="edit_statut" name="statut">
                                <option value="Actif">Actif</option>
                                <option value="Inactif">Inactif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button class="btn btn-warning" type="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal : Ajouter Secrétaire --}}
    <div class="modal fade" id="ajouterModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-user-plus"></i> Ajouter un Secrétaire</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nom complet</label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>Statut</label>
                            <select class="form-control" name="statut">
                                <option value="Actif">Actif</option>
                                <option value="Inactif">Inactif</option>
                            </select>
                        </div>
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

@section('js')
<script>
    // Filtrer par statut
    document.getElementById('filtreStatut').addEventListener('change', function () {
        const value = this.value;
        document.querySelectorAll('#listeSecretaires tr').forEach(row => {
            row.style.display = (value === 'Tous' || row.dataset.statut === value) ? '' : 'none';
        });
    });

    // Voir les détails
    function voirSecretaire(nom, email, statut) {
        document.getElementById('voir_nom').textContent = nom;
        document.getElementById('voir_email').textContent = email;
        document.getElementById('voir_statut').textContent = statut;
    }

    // Remplir le formulaire de modification
    function remplirFormulaire(id, nom, email, statut) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nom').value = nom;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_statut').value = statut;
    }
</script>
@stop
