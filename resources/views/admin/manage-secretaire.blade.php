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
                    @foreach($secretaires as $sec)
                        <tr data-statut="{{ $sec->statut }}">
                           <td>{{ $sec->user->name }}</td>
                            <td>{{ $sec->user->email }}</td>

                            <td>
                                <span class="badge bg-{{ $sec->statut === 'Actif' ? 'success' : 'secondary' }}">
                                    {{ $sec->statut }}
                                </span>
                            </td>
                            <td class="text-center">
                               

                               <button class="btn btn-sm btn-warning me-1"
                                    data-toggle="modal"
                                    data-target="#modifierModal"
                                    onclick="remplirFormulaire(
                                        '{{ $sec->id }}',
                                        '{{ addslashes($sec->user->name) }}',
                                        '{{ addslashes($sec->user->email) }}',
                                        '{{ $sec->statut }}'
                                    )">
                                    <i class="fas fa-edit"></i>
                                </button>


                                <button class="btn btn-sm btn-secondary me-1"
                                    data-toggle="modal"
                                    data-target="#activitesModal"
                                    onclick="chargerActivites({{ $sec->id }}, '{{ $sec->name }}')">
                                    <i class="fas fa-list"></i> Voir Activités
                                </button>

                                <form action="{{ route('admin.secretaires.destroy', $sec->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

   

  
   

   
{{-- Modal : Modifier Secrétaire --}}
<div class="modal fade" id="modifierModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.secretaires.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Modifier Secrétaire</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" class="form-control" id="edit_nom" name="name" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Statut</label>
                        <select class="form-control" id="edit_statut" name="statut" required>
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
                <form action="{{ route('admin.secretaires.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-user-plus"></i> Ajouter un Secrétaire</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nom complet</label>
                            <input type="text" class="form-control" name="name" required>
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

    {{-- Modal : Activités du secrétaire --}}
<div class="modal fade" id="activitesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title"><i class="fas fa-history"></i> Activités de <span id="activite_nom"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <ul id="liste_activites" class="list-group">
                    {{-- Contenu chargé par AJAX --}}
                </ul>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
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
function remplirFormulaire(id, nom, email, statut) {
     console.log(">>> remplirFormulaire appelé avec : ", id, nom, email, statut);

    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nom').value = nom;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_statut').value = statut;
}


    // Charger les activités du secrétaire
    function chargerActivites(secretaryId, nom) {
        document.getElementById('activite_nom').textContent = nom;
        const liste = document.getElementById('liste_activites');
        liste.innerHTML = `<li class="list-group-item">Chargement...</li>`;

        function chargerActivites(secretaryId, nom) {
    document.getElementById('activite_nom').textContent = nom;
    const liste = document.getElementById('liste_activites');
    liste.innerHTML = `<li class="list-group-item">Chargement...</li>`;

    fetch(`/admin/secretaires/${secretaryId}/activites`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur HTTP: " + response.status);
            }
            return response.json();
        })
        .then(data => {
            liste.innerHTML = '';
            if (data.length === 0) {
                liste.innerHTML = `<li class="list-group-item">Aucune activité trouvée.</li>`;
            } else {
                data.forEach(log => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.innerHTML = `<strong>${log.action}</strong> - ${log.description} <br><small class="text-muted">${log.created_at}</small>`;
                    liste.appendChild(li);
                });
            }
        })
        .catch(error => {
            console.error("Erreur lors du fetch :", error);
            liste.innerHTML = `<li class="list-group-item text-danger">Erreur lors du chargement des activités.</li>`;
        });
}

</script>
@stop
