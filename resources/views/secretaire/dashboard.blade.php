@extends('adminlte::page')

@section('title', 'Tableau de bord Secrétaire')

@section('content_header')
    <h1 class="text-primary">Tableau de bord</h1>
@stop

@section('content')
<div class="row">
    <!-- Cartes principales -->
    <div class="col-md-3">
        <x-adminlte-small-box title="Étudiants" text="Total : 120" icon="fas fa-users" theme="info" url="#" />
    </div>

    <div class="col-md-3">
        <x-adminlte-small-box title="Réclamations" text="En attente : 8" icon="fas fa-exclamation-circle" theme="warning" url="#" />
    </div>

    <div class="col-md-3">
        <x-adminlte-small-box title="Moyennes par UE" text="Voir les détails" icon="fas fa-chart-bar" theme="success" url="#" />
    </div>

    <div class="col-md-3">
        <x-adminlte-small-box title="Notes par ECUE" text="Voir les détails" icon="fas fa-chart-line" theme="danger" url="#" />
    </div>
</div>

<!-- Graphiques -->
<div class="row mt-4">
    <div class="col-md-6">
        <x-adminlte-card title="Moyennes par UE" theme="light" icon="fas fa-chart-bar">
            <canvas id="chartUE"></canvas>
        </x-adminlte-card>
    </div>

    <div class="col-md-6">
        <x-adminlte-card title="Notes par ECUE" theme="light" icon="fas fa-chart-line">
            <canvas id="chartECUE"></canvas>
        </x-adminlte-card>
    </div>
</div>
@stop

@push('js')
<script>
    const ueLabels = ['Maths', 'Réseau', 'Dev Web'];
    const ueData = [14, 9, 16];

    const ecueLabels = ['Algèbre', 'PHP', 'Cisco', 'Laravel'];
    const ecueData = [12, 15, 10, 17];

    new Chart(document.getElementById('chartUE'), {
        type: 'bar',
        data: {
            labels: ueLabels,
            datasets: [{
                label: 'Moyenne',
                data: ueData,
                backgroundColor: '#4e73df', // Couleur plus douce
                borderColor: '#4e73df',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true, suggestedMax: 20 }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    new Chart(document.getElementById('chartECUE'), {
        type: 'doughnut',
        data: {
            labels: ecueLabels,
            datasets: [{
                label: 'Note',
                data: ecueData,
                backgroundColor: ['#6f42c1', '#28a745', '#ffcc00', '#fd7e14'], // Couleurs moins flashy
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
@endpush
