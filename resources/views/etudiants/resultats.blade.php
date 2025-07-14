@extends('adminlte::page')

@section('title', 'Mes Résultats')

@section('content_header')
    <h1 class="text-center text-custom-blue font-weight-bold">Mes Résultats Globaux</h1>
@stop

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-custom-blue text-white text-center">
            <strong>Pourcentage de Validation :</strong>
            <span class="badge badge-light text-dark font-weight-bold">
                {{ round($pourcentage, 2) }}%
            </span>
        </div>

        <div class="card-body">
            <h3 class="font-weight-bold text-dark mb-4">Statut de Validation :</h3>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Validées :</strong>
                    <span class="badge badge-success p-2">{{ $validUes }} UE(s) validée(s)</span>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Non Validées :</strong>
                    <span class="badge badge-danger p-2">{{ $invalidUes }} UE(s) non validée(s)</span>
                </div>
            </div>
            <hr class="my-4">

            {{-- Détails des UE --}}
            @foreach($ues as $ue)
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header bg-custom-blue text-white">
                        <strong>{{ $ue['nom'] }}</strong> — Moyenne UE :
                        <span class="badge badge-pill 
                            {{ $ue['moyenne_ue'] >= 10 ? 'badge-success' : 'badge-danger' }}">
                            {{ $ue['moyenne_ue'] }}/20
                        </span>
                        <span class="badge badge-pill 
                            {{ $ue['moyenne_ue'] >= 10 ? 'badge-success' : 'badge-danger' }}">
                            {{ $ue['moyenne_ue'] >= 10 ? 'Validée' : 'Non Validée' }}
                        </span>
                    </div>

                    <div class="card-body">
                        @if(!empty($ue['ecues']))
                            {{-- Détails des ECUE --}}
                            @foreach ($ue['ecues'] as $ecue)
                                <h5 class="font-weight-bold">{{ $ecue['nom'] }} — Moyenne ECUE :
                                    <span class="badge badge-pill 
                                        {{ $ecue['moyenne_ecue'] >= 10 ? 'badge-success' : 'badge-danger' }}">
                                        {{ $ecue['moyenne_ecue'] }}/20
                                    </span>
                                </h5>
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Type d'évaluation</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ecue['notes'] as $note)
                                            <tr>
                                                <td>{{ $note['type'] }}</td>
                                                <td>{{ $note['valeur'] }}/20</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        @else
                            <h5 class="font-weight-bold">Aucune ECUE — Notes UE Directes</h5>
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Type d'évaluation</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ue['notes'] as $note)
                                        <tr>
                                            <td>{{ $note['type'] }}</td>
                                            <td>{{ $note['valeur'] }}/20</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Couleur personnalisée bleue claire */
        .bg-custom-blue {
            background-color: #69aee4 !important;
        }

        .text-custom-blue {
            color: #69aee4 !important;
        }

        /* Amélioration des badges */
        .badge {
            font-size: 1rem;
            padding: 0.4rem 0.8rem;
        }

        /* Amélioration de l'apparence des tables */
        .table {
            font-size: 0.9rem;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f8f9fa;
        }

        .table-striped tbody tr:nth-child(even) {
            background-color: #e9ecef;
        }

        /* Style des cartes */
        .card {
            border-radius: 8px;
        }

        /* Amélioration des titres */
        .card-header {
            font-size: 1.1rem;
            font-weight: 600;
        }
    </style>
@stop
