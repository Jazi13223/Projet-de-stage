@extends('adminlte::page')

@section('title', 'Mon Profil')

@section('content_header')
    <h1 class="text-center text-primary"><i class="fas fa-id-badge me-2"></i>Mon Profil</h1>
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow border-0 bg-light">
                <div class="card-body">

                    {{-- Informations utilisateur --}}
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4 text-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff&size=200"
                                 class="img-fluid rounded-circle border border-3 border-primary shadow-sm"
                                 alt="Avatar">
                            <h4 class="mt-3 mb-0">{{ Auth::user()->name }}</h4>
                            <p class="text-muted">{{ ucfirst(Auth::user()->role) }} ENEAM</p>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Nom complet</label>
                                <div class="form-control-plaintext">{{ Auth::user()->name }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Adresse Email</label>
                                <div class="form-control-plaintext">{{ Auth::user()->email }}</div>
                            </div>

                            {{-- Boutons Modifier / Déconnexion --}}
                            <div class="d-flex justify-content-start mt-4 gap-3">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modifierProfilModal">
                                    <i class="fas fa-edit me-1"></i> Modifier mes informations
                                </button>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-sign-out-alt me-3"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div> <!-- card-body -->
            </div> <!-- card -->
        </div>
    </div>

    {{-- Modal : Modifier les infos --}}
    <div class="modal fade" id="modifierProfilModal" tabindex="-1" role="dialog" aria-labelledby="modifierProfilModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-edit"></i> Modifier mon profil</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Nom complet</label>
                            <input type="text" class="form-control" name="nom" value="{{ Auth::user()->name }}">
                        </div>

                        <div class="form-group mb-3">
                            <label>Adresse Email</label>
                            <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}">
                        </div>

                        <hr>

                        <div class="form-group mb-3">
                            <label>Mot de passe actuel</label>
                            <input type="password" class="form-control" name="old_password">
                        </div>

                        <div class="form-group mb-3">
                            <label>Nouveau mot de passe</label>
                            <input type="password" class="form-control" name="new_password">
                        </div>

                        <div class="form-group mb-3">
                            <label>Confirmer le mot de passe</label>
                            <input type="password" class="form-control" name="confirm_password">
                        </div>

                        {{-- Afficher les erreurs --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mt-2">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
