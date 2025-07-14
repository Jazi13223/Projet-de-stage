@extends('adminlte::page')

@section('title', 'Mon Profil')

@section('content_header')
    <h1 class="mb-3">Mon Profil</h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <!-- Avatar -->
                <div class="col-md-4 text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff&size=120"
                         class="img-circle elevation-2 mb-3" alt="Avatar">
                    <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                    <p class="text-muted">{{ ucfirst(Auth::user()->role) }}</p>
                </div>

                <!-- Infos utilisateur -->
                <div class="col-md-8">
                    <form>
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" id="name" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="email">Adresse Email</label>
                            <input type="email" id="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="role">Rôle</label>
                            <input type="text" id="role" class="form-control" value="{{ ucfirst(Auth::user()->role) }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="created_at">Date d'inscription</label>
                            <input type="text" id="created_at" class="form-control" value="{{ Auth::user()->created_at->format('d/m/Y') }}" readonly>
                        </div>

                        <button type="button" class="btn btn-warning mt-2" data-toggle="modal" data-target="#modifierProfilModal">
                            <i class="fas fa-user-edit"></i> Modifier mon profil
                        </button>

                        <form method="POST" action="{{ route('logout') }}" class="d-inline-block ml-2">
                            @csrf
                            <button type="submit" class="btn btn-secondary mt-2">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </button>
                        </form>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal : Modifier le profil -->
    <div class="modal fade" id="modifierProfilModal" tabindex="-1" role="dialog" aria-labelledby="modifierProfilModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="#" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modifierProfilModalLabel"><i class="fas fa-edit"></i> Modifier mon profil</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}">
                    </div>

                    <div class="form-group">
                        <label>Adresse Email</label>
                        <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}">
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Mot de passe actuel</label>
                        <input type="password" name="old_password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="new_password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Confirmer le nouveau mot de passe</label>
                        <input type="password" name="confirm_password" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@stop
