@extends('adminlte::page')

@section('title', 'Validation de l’Année')

@section('content_header')
    <h1>Validation Académique</h1>
@stop

@section('content')
    <!-- Informations générales -->
    <div class="callout callout-info">
        <h5>Année académique : 2024-2025</h5>
        <hr>
        <h4>Statut de Validation :</h4>
        <h5>
            Validation Annuelle :
            <span class="badge badge-success">Validée</span>
        </h5>
        <h5>Moyenne Annuelle : <span class="badge badge-primary">13.9 / 20</span></h5>
    </div>

    <!-- Pourcentage de Validation -->
    <div class="mt-4">
        <h5>Pourcentage de Validation</h5>
        <div class="progress" style="height: 25px;">
            <div class="progress-bar bg-success" 
                 role="progressbar" 
                 style="width: 85%;" 
                 aria-valuenow="85" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                85%
            </div>
        </div>
    </div>

    <hr>

    <!-- Validation Semestrielle -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header"><strong>Semestre 1 - Validation</strong></div>
                <div class="card-body">
                    <h5>Moyenne Générale : <span class="badge badge-primary">13.5 / 20</span></h5>
                    <h5>
                        Validation Semestrielle :
                        <span class="badge badge-success">Validée</span>
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header"><strong>Semestre 2 - Validation</strong></div>
                <div class="card-body">
                    <h5>Moyenne Générale : <span class="badge badge-primary">14.2 / 20</span></h5>
                    <h5>
                        Validation Semestrielle :
                        <span class="badge badge-success">Validée</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <!-- Détail des UE validées par semestre -->
    <div class="row">
        <!-- Semestre 1 -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header"><strong>Semestre 1 - Unités d’Enseignement</strong></div>
                <div class="card-body p-0">
                    <table class="table table-striped m-0">
                        <thead>
                            <tr>
                                <th>UE</th>
                                <th>Moyenne</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>UE1 - Mathématiques</td>
                                <td>14</td>
                                <td><span class="badge badge-success">Validée</span></td>
                            </tr>
                            <tr>
                                <td>UE2 - Informatique</td>
                                <td>13</td>
                                <td><span class="badge badge-success">Validée</span></td>
                            </tr>
                            <tr>
                                <td>UE3 - Économie</td>
                                <td>9.5</td>
                                <td><span class="badge badge-danger">Non validée</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Semestre 2 -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header"><strong>Semestre 2 - Unités d’Enseignement</strong></div>
                <div class="card-body p-0">
                    <table class="table table-striped m-0">
                        <thead>
                            <tr>
                                <th>UE</th>
                                <th>Moyenne</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>UE4 - Réseaux</td>
                                <td>15.5</td>
                                <td><span class="badge badge-success">Validée</span></td>
                            </tr>
                            <tr>
                                <td>UE5 - Base de Données</td>
                                <td>13</td>
                                <td><span class="badge badge-success">Validée</span></td>
                            </tr>
                            <tr>
                                <td>UE6 - Communication</td>
                                <td>10</td>
                                <td><span class="badge badge-success">Validée</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
