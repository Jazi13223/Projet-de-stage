@extends('adminlte::page')

@section('title', 'Mon Profil')

@section('content')
@php
    // Récupérer l'étudiant et sa dernière inscription
    $etudiant = Auth::user()->etudiant;
    $inscription = $etudiant ? $etudiant->inscriptions()->latest()->first() : null;
    $filiereNom = $inscription && $inscription->filiere ? $inscription->filiere->name : '';
    $selectedFiliereId = $inscription ? $inscription->filiere_id : null;
@endphp

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down" data-aos-duration="500">
        <i class="fas fa-check-circle mr-2"></i>
        <strong>Succès !</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <!-- En-tête du profil -->
            <div class="profile-header" data-aos="fade-up" data-aos-duration="600">
                <div class="profile-avatar">
                    <span class="avatar-text">
                        {{ strtoupper(substr(Auth::user()->first_name ?? '', 0, 1) . substr(Auth::user()->name ?? '', 0, 1)) }}
                    </span>
                </div>
                <div class="profile-info">
                    <h1 class="profile-name">{{ Auth::user()->first_name }} {{ Auth::user()->name }}</h1>
                    <p class="profile-role">Étudiant</p>
                </div>
            </div>

            <!-- Carte principale -->
            <div class="profile-card" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-2"></i>
                        Informations personnelles
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item" data-aos="fade-right" data-aos-duration="500" data-aos-delay="300">
                            <div class="info-label">
                                <i class="fas fa-user-tag"></i>
                                <span>Nom</span>
                            </div>
                            <div class="info-value">{{ Auth::user()->name }}</div>
                        </div>

                        <div class="info-item" data-aos="fade-right" data-aos-duration="500" data-aos-delay="400">
                            <div class="info-label">
                                <i class="fas fa-user"></i>
                                <span>Prénom</span>
                            </div>
                            <div class="info-value">{{ Auth::user()->first_name }}</div>
                        </div>

                        <div class="info-item" data-aos="fade-right" data-aos-duration="500" data-aos-delay="500">
                            <div class="info-label">
                                <i class="fas fa-envelope"></i>
                                <span>Email</span>
                            </div>
                            <div class="info-value">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="info-item" data-aos="fade-right" data-aos-duration="500" data-aos-delay="600">
                            <div class="info-label">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Filière</span>
                            </div>
                            <div class="info-value">
                                @if($filiereNom)
                                    <span class="filiere-badge">{{ $filiereNom }}</span>
                                @else
                                    <span class="no-filiere">Non assignée</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer" data-aos="fade-up" data-aos-duration="500" data-aos-delay="700">
                    <button class="btn btn-edit" data-toggle="modal" data-target="#editModal">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier mes informations
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline-block ml-3">
                        @csrf
                        <button type="submit" class="btn btn-logout">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modification -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" data-aos="zoom-in" data-aos-duration="300">
            <form method="POST" action="{{ route('etudiants.update') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier mes informations
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user-tag mr-1"></i>
                                    Nom
                                </label>
                                <input type="text" class="form-control" name="name" id="name" 
                                       value="{{ Auth::user()->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name" class="form-label">
                                    <i class="fas fa-user mr-1"></i>
                                    Prénom
                                </label>
                                <input type="text" class="form-control" name="first_name" id="first_name" 
                                       value="{{ Auth::user()->first_name }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope mr-1"></i>
                            Adresse e-mail
                        </label>
                        <input type="email" class="form-control" name="email" id="email" 
                               value="{{ Auth::user()->email }}" required>
                    </div>

                    <div class="form-group">
                        <label for="filiere_id" class="form-label">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            Filière
                        </label>
                        <select name="filiere_id" id="filiere_id" class="form-control" required>
                            <option value="">Sélectionner une filière</option>
                            @foreach ($filieres as $filiere)
                                <option value="{{ $filiere->id }}" 
                                        {{ $filiere->id == $selectedFiliereId ? 'selected' : '' }}>
                                    {{ $filiere->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<!-- AOS CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
/* Variables professionnelles */
:root {
    --primary-color: #2c3e50;
    --secondary-color: #34495e;
    --light-gray: #f8f9fa;
    --border-color: #e9ecef;
    --text-color: #495057;
    --muted-color: #6c757d;
    --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    --border-radius: 0.375rem;
    --transition: all 0.2s ease-in-out;
}

/* Fond général */
.content-wrapper {
    background-color: var(--light-gray);
}

/* En-tête du profil */
.profile-header {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    padding: 2rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    background-color: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.avatar-text {
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    letter-spacing: 1px;
}

.profile-name {
    color: var(--primary-color);
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.profile-role {
    color: var(--muted-color);
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

/* Carte principale */
.profile-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
}

.profile-card .card-header {
    background-color: var(--light-gray);
    border-bottom: 1px solid var(--border-color);
    border-radius: var(--border-radius) var(--border-radius) 0 0;
    padding: 1.25rem 1.5rem;
}

.card-title {
    color: var(--primary-color);
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
}

.card-body {
    padding: 1.5rem;
}

/* Grille d'informations */
.info-grid {
    display: grid;
    gap: 1.25rem;
}

.info-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-color);
    transition: var(--transition);
}

.info-item:last-child {
    border-bottom: none;
}

.info-item:hover {
    background-color: rgba(248, 249, 250, 0.5);
    margin: 0 -1rem;
    padding: 1rem;
    border-radius: var(--border-radius);
    border-bottom: 1px solid var(--border-color);
}

.info-label {
    display: flex;
    align-items: center;
    color: var(--muted-color);
    font-weight: 500;
    font-size: 0.9rem;
}

.info-label i {
    margin-right: 0.75rem;
    width: 16px;
    text-align: center;
    color: var(--primary-color);
}

.info-value {
    color: var(--text-color);
    font-weight: 600;
    text-align: right;
}

.filiere-badge {
    background-color: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.no-filiere {
    color: var(--muted-color);
    font-style: italic;
}

/* Footer de la carte */
.card-footer {
    background-color: var(--light-gray);
    border-top: 1px solid var(--border-color);
    border-radius: 0 0 var(--border-radius) var(--border-radius);
    padding: 1.25rem 1.5rem;
}

/* Boutons */
.btn {
    border-radius: var(--border-radius);
    font-weight: 500;
    padding: 0.625rem 1.25rem;
    transition: var(--transition);
    border: none;
}

.btn-edit {
    background-color: var(--primary-color);
    color: white;
}

.btn-edit:hover {
    background-color: var(--secondary-color);
    color: white;
    transform: translateY(-1px);
    box-shadow: var(--shadow);
}

.btn-logout {
    background-color: #dc3545;
    color: white;
}

.btn-logout:hover {
    background-color: #c82333;
    color: white;
    transform: translateY(-1px);
    box-shadow: var(--shadow);
}

/* Modal */
.modal-content {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.modal-header {
    background-color: var(--primary-color);
    color: white;
    border-bottom: none;
    border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.modal-title {
    font-weight: 600;
}

.modal-header .close {
    color: white;
    opacity: 0.8;
}

.modal-header .close:hover {
    opacity: 1;
}

.modal-body {
    padding: 2rem;
}

.form-label {
    color: var(--primary-color);
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control {
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 0.625rem 0.75rem;
    transition: var(--transition);
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.25);
}

.modal-footer {
    background-color: var(--light-gray);
    border-top: 1px solid var(--border-color);
    border-radius: 0 0 var(--border-radius) var(--border-radius);
}

/* Alert */
.alert {
    border: none;
    border-radius: var(--border-radius);
    margin-bottom: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-header, .profile-card {
        margin-left: 0;
        margin-right: 0;
    }
    
    .card-footer {
        text-align: center;
    }
    
    .card-footer .btn {
        display: block;
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .card-footer form {
        margin: 0;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .info-value {
        text-align: left;
    }
}

@media (max-width: 576px) {
    .modal-dialog {
        margin: 1rem;
        max-width: none;
    }
    
    .modal-body {
        padding: 1rem;
    }
}
</style>
@endsection

@section('js')
<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
// Initialisation AOS
AOS.init({
    duration: 600,
    easing: 'ease-out-cubic',
    once: true,
    offset: 50,
    delay: 100
});

// Auto-dismiss alerts après 5 secondes
setTimeout(function() {
    $('.alert-dismissible').fadeOut('slow');
}, 5000);

// Animation au survol des info-items
$(document).ready(function() {
    $('.info-item').hover(
        function() {
            $(this).find('.info-label i').addClass('fa-pulse');
        },
        function() {
            $(this).find('.info-label i').removeClass('fa-pulse');
        }
    );
});
</script>
@endsection