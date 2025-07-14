@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Tableau de Bord</h1>
@stop

@section('content')
<div class="row">
    <!-- Statistiques -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>120</h3>
                <p>Étudiants</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="#" class="small-box-footer">Plus d’infos <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>5</h3>
                <p>Filières</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="#" class="small-box-footer">Plus d’infos <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>1</h3>
                <p>Secrétaire</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <a href="#" class="small-box-footer">Plus d’infos <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>10</h3>
                <p>Résultats publiés</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="#" class="small-box-footer">Plus d’infos <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- Notifications & Timeline -->
<div class="row">
    <!-- Notifications Système -->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Notifications Système</h3>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <i class="fas fa-check-circle text-success"></i> Sauvegarde effectuée
                        <span class="float-right text-muted text-sm">Il y a 10 min</span>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-user-plus text-primary"></i> Nouvel étudiant ajouté
                        <span class="float-right text-muted text-sm">Aujourd’hui</span>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-exclamation-triangle text-warning"></i> Tentative de connexion échouée
                        <span class="float-right text-muted text-sm">Hier</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Timeline d’Activités -->
    <div class="col-md-6">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Historique des Activités</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="time-label">
                        <span class="bg-red">23 Juin 2025</span>
                    </div>
                    <div>
                        <i class="fas fa-user bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i> 10:30</span>
                            <h3 class="timeline-header">L’administrateur a ajouté un étudiant</h3>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-edit bg-green"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i> 09:00</span>
                            <h3 class="timeline-header">Publication de nouveaux résultats</h3>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-power-off bg-gray"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i> 08:00</span>
                            <h3 class="timeline-header">Connexion à l’espace admin</h3>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
