@extends('adminlte::page')

@section('title', 'Mes Notes')

@section('content_header')
    <center><h1>Mes Notes détaillées</h1></center>
@stop

@section('content')
    @foreach($ues as $ue)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong>{{ $ue['nom'] }}</strong> — Moyenne UE :
                <span class="badge {{ $ue['moyenne_ue'] >= 12 ? 'badge-success' : 'badge-danger' }}">
                    {{ $ue['moyenne_ue'] }}/20
                </span>
            </div>

            <div class="card-body p-0">
                @if (!empty($ue['ecues']))
                    @foreach ($ue['ecues'] as $ecue)
                        <div class="p-3">
                            <h5>{{ $ecue['nom'] }} — Moyenne ECUE :
                                <span class="badge {{ $ecue['moyenne_ecue'] >= 10 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $ecue['moyenne_ecue'] }}/20
                                </span>
                            </h5>

                            <table class="table table-bordered table-striped">
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
                        </div>
                        <hr>
                    @endforeach
                @else
                    <div class="p-3">
                        <h5>Aucune ECUE — Notes UE Directes</h5>
                        <table class="table table-bordered table-striped">
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
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@stop
