@extends('adminlte::page')

@section('title', 'Statistiques')

@section('content_header')
    <h1>Statistiques Générales</h1>
@stop

@section('content')
<!-- Résumé rapide -->
<div class="row">
    <div class="col-md-3">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-user-graduate"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Étudiants</span>
                <span class="info-box-number">120</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-book"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Filières</span>
                <span class="info-box-number">5</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fas fa-user-tie"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Secrétaire</span>
                <span class="info-box-number">1</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box bg-danger">
            <span class="info-box-icon"><i class="fas fa-file-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Résultats</span>
                <span class="info-box-number">10</span>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row">
    <!-- Étudiants par filière -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary">Étudiants par filière</div>
            <div class="card-body">
                <canvas id="chartFiliere"></canvas>
            </div>
        </div>
    </div>

    <!-- Répartition des rôles -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success">Répartition des rôles</div>
            <div class="card-body">
                <canvas id="chartRoles"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Graphique d'évolution -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-secondary">Évolution du nombre d'étudiants (par année)</div>
            <div class="card-body">
                <canvas id="chartEvolution"></canvas>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique par filière
    new Chart(document.getElementById('chartFiliere'), {
        type: 'bar',
        data: {
            labels: ['Informatique', 'Réseaux', 'Gestion', 'Stats'],
            datasets: [{
                label: 'Étudiants',
                data: [40, 30, 25, 25],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
            }]
        }
    });

    // Graphique des rôles
    new Chart(document.getElementById('chartRoles'), {
        type: 'pie',
        data: {
            labels: ['Admin', 'Secrétaire', 'Étudiants'],
            datasets: [{
                data: [1, 1, 120],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)'
                ]
            }]
        }
    });

    // Graphique d'évolution
    new Chart(document.getElementById('chartEvolution'), {
        type: 'line',
        data: {
            labels: ['2020', '2021', '2022', '2023', '2024'],
            datasets: [{
                label: 'Étudiants inscrits',
                data: [80, 90, 100, 110, 120],
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                fill: true,
                tension: 0.4
            }]
        }
    });
</script>
@stop
