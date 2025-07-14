@extends('adminlte::page')

@section('title', 'Dashboard Étudiant')

@section('content_header')
    <h1 class="text-center">Dashboard Étudiant</h1>
@stop

@section('content')
    <div class="row">
        <!-- Notes Validées -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>15</h3>
                    <p>Notes Validées</p>
                </div>
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <a href="#" class="small-box-footer">Voir les résultats <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Réclamations -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>3</h3>
                    <p>Réclamations Soumises</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <a href="#" class="small-box-footer">Voir les réclamations <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Autre Statistique -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>8</h3>
                    <p>Unités Validées</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="#" class="small-box-footer">Voir les unités <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Moyenne Générale -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>12.8</h3>
                    <p>Moyenne Générale</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <a href="#" class="small-box-footer">Voir la moyenne <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Graphique des résultats -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Mes Résultats par Matière</h3>
        </div>
        <div class="card-body">
            <canvas id="resultChart" style="height: 300px; width: 100%;"></canvas>
        </div>
    </div>
@stop

@section('js')
    <script>
        // Exemple de graphique avec taille réduite
        var ctx = document.getElementById('resultChart').getContext('2d');
        var resultChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Matière 1', 'Matière 2', 'Matière 3', 'Matière 4'],
                datasets: [{
                    label: 'Note',
                    data: [15, 18, 10, 12],
                    backgroundColor: '#17a2b8',
                    borderColor: '#17a2b8',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,  // Le graphique s'adapte à la taille
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@stop
