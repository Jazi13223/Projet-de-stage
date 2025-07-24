@extends('adminlte::page')

@section('title', 'Mes Notes')

@section('content_header')
    <center>
        <h1 data-aos="fade-down" data-aos-duration="1000" class="text-dark">Mes Notes détaillées</h1>
    </center>
@stop

@section('css')
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <style>
        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
            transform: translateY(-1px);
        }
        
        .card-header {
            background-color: #f8f9fc !important;
            border-bottom: 2px solid #e3e6f0;
            color: #2c3e50 !important;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: #1cc88a;
            border: none;
            font-weight: 500;
        }
        
        .badge-danger {
            background-color: #e74a3b;
            border: none;
            font-weight: 500;
        }
        
        .badge-light {
            background-color: #f8f9fc;
            color: #5a5c69;
            border: 1px solid #d1d3e2;
        }
        
        .badge-warning {
            background-color: #f6c23e;
            color: #2c3e50;
        }
        
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #e3e6f0;
        }
        
        .table thead th {
            background-color: #f8f9fc;
            color: #2c3e50;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 1rem 0.75rem;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fc;
        }
        
        .table tbody td {
            padding: 0.875rem 0.75rem;
            vertical-align: middle;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            border-radius: 0.5rem;
            border-left: 4px solid #17a2b8;
        }
        
        .ecue-section {
            margin-bottom: 2.5rem;
            padding: 1.5rem;
            background-color: #ffffff;
            border-radius: 0.5rem;
            border: 1px solid #e3e6f0;
        }
        
        .ecue-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e3e6f0;
        }
        
        .moyenne-badge {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }
        
        .ue-header {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 1rem 1.25rem;
        }
        
        .ue-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .note-type {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .divider {
            border: none;
            height: 1px;
            background-color: #e3e6f0;
            margin: 2rem 0;
        }
        
        .no-data {
            color: #6c757d;
            font-style: italic;
            text-align: center;
            padding: 2rem;
        }
    </style>
@stop

@section('content')  
    <div class="alert alert-info" data-aos="fade-right" data-aos-duration="800">
        <strong>ℹ️ Information :</strong> Si une note de rattrapage (session 2) existe, elle remplace la note de session normale.
    </div>

    @foreach($ues as $index => $ue) <!-- Boucle sur chaque UE -->
        <div class="card mb-4" data-aos="fade-up" data-aos-duration="600" data-aos-delay="{{ $index * 80 }}">
            <div class="card-header">
                <div class="ue-header">
                    <span class="ue-title">{{ $ue['name'] }}</span>
                    <div>
                        <span class="text-muted me-2">Moyenne UE :</span>
                        <span class="badge moyenne-badge {{ $ue['moyenne_ue'] >= 12 ? 'badge-success' : 'badge-danger' }}">
                            {{ $ue['moyenne_ue'] ?? 'N/A' }}/20
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                @if (!empty($ue['ecues']))
                    <!-- Si l'UE a des ECUEs, boucle sur chaque ECUE -->
                    @foreach ($ue['ecues'] as $ecueIndex => $ecue)
                        <div class="ecue-section" data-aos="fade-left" data-aos-duration="500" data-aos-delay="{{ ($index * 80) + ($ecueIndex * 40) }}">
                            <h6 class="ecue-title d-flex justify-content-between align-items-center">
                                <span>{{ $ecue['nom'] }}</span>
                                <span class="badge moyenne-badge {{ $ecue['moyenne_ecue'] >= 10 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $ecue['moyenne_ecue'] ?? 'N/A' }}/20
                                </span>
                            </h6>

                            <div data-aos="fade-up" data-aos-duration="400" data-aos-delay="{{ ($index * 80) + ($ecueIndex * 40) + 100 }}">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Type d'évaluation</th>
                                            <th>Session normale</th>
                                            <th>Rattrapage</th>
                                            <th>Note retenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $notes_normales = collect($ecue['notes'])->where('session', 'normale');
                                            $notes_rattrapage = collect($ecue['notes'])->where('session', 'rattrapage');
                                        @endphp

                                        @foreach ($notes_normales as $note_normale)
                                            @php
                                                $rattrapage = $notes_rattrapage->firstWhere('type', $note_normale['type']);
                                                $note_retenue = $rattrapage ? $rattrapage['valeur'] : $note_normale['valeur'];
                                            @endphp
                                            <tr>
                                                <td class="note-type">{{ $note_normale['type'] }}</td>
                                                <td>
                                                    <span class="badge badge-light">{{ $note_normale['valeur'] }}/20</span>
                                                </td>
                                                <td>
                                                    @if($rattrapage)
                                                        <span class="badge badge-warning">{{ $rattrapage['valeur'] }}/20</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $note_retenue >= 10 ? 'badge-success' : 'badge-danger' }} moyenne-badge">
                                                        {{ $note_retenue }}/20
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- S'il existe une note de rattrapage sans note normale correspondante --}}
                                        @foreach ($notes_rattrapage as $note_rattrapage)
                                            @if (!$notes_normales->firstWhere('type', $note_rattrapage['type']))
                                                <tr>
                                                    <td class="note-type">{{ $note_rattrapage['type'] }}</td>
                                                    <td><span class="text-muted">—</span></td>
                                                    <td><span class="badge badge-warning">{{ $note_rattrapage['valeur'] }}/20</span></td>
                                                    <td>
                                                        <span class="badge {{ $note_rattrapage['valeur'] >= 10 ? 'badge-success' : 'badge-danger' }} moyenne-badge">
                                                            {{ $note_rattrapage['valeur'] }}/20
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @if ($notes_normales->isEmpty() && $notes_rattrapage->isEmpty())
                                            <tr>
                                                <td colspan="4" class="no-data">
                                                    Aucune note disponible
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if (!$loop->last)
                            <hr class="divider">
                        @endif
                    @endforeach
                @else
                    {{-- Si l'UE n'a pas d'ECUE, afficher les notes directement pour l'UE --}}
                    <div class="ecue-section" data-aos="fade-left" data-aos-duration="500" data-aos-delay="{{ $index * 80 }}">
                        <h6 class="ecue-title d-flex justify-content-between align-items-center">
                            <span>Évaluation directe</span>
                            <span class="badge moyenne-badge {{ $ue['moyenne_ue'] >= 12 ? 'badge-success' : 'badge-danger' }}">
                                {{ $ue['moyenne_ue'] ?? 'N/A' }}/20
                            </span>
                        </h6>

                        <div data-aos="fade-up" data-aos-duration="400" data-aos-delay="{{ $index * 80 + 100 }}">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Type d'évaluation</th>
                                        <th>Session normale</th>
                                        <th>Rattrapage</th>
                                        <th>Note retenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $notes_normales = collect($ue['notes'])->where('session', 'normale');
                                        $notes_rattrapage = collect($ue['notes'])->where('session', 'rattrapage');
                                    @endphp

                                    @foreach ($notes_normales as $note_normale)
                                        @php
                                            $rattrapage = $notes_rattrapage->firstWhere('type', $note_normale['type']);
                                            $note_retenue = $rattrapage ? $rattrapage['valeur'] : $note_normale['valeur'];
                                        @endphp
                                        <tr>
                                            <td class="note-type">{{ $note_normale['type'] }}</td>
                                            <td>
                                                <span class="badge badge-light">{{ $note_normale['valeur'] }}/20</span>
                                            </td>
                                            <td>
                                                @if($rattrapage)
                                                    <span class="badge badge-warning">{{ $rattrapage['valeur'] }}/20</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $note_retenue >= 10 ? 'badge-success' : 'badge-danger' }} moyenne-badge">
                                                    {{ $note_retenue }}/20
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- Notes de rattrapage sans session normale --}}
                                    @foreach ($notes_rattrapage as $note_rattrapage)
                                        @if (!$notes_normales->firstWhere('type', $note_rattrapage['type']))
                                            <tr>
                                                <td class="note-type">{{ $note_rattrapage['type'] }}</td>
                                                <td><span class="text-muted">—</span></td>
                                                <td><span class="badge badge-warning">{{ $note_rattrapage['valeur'] }}/20</span></td>
                                                <td>
                                                    <span class="badge {{ $note_rattrapage['valeur'] >= 10 ? 'badge-success' : 'badge-danger' }} moyenne-badge">
                                                        {{ $note_rattrapage['valeur'] }}/20
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    @if ($notes_normales->isEmpty() && $notes_rattrapage->isEmpty())
                                        <tr>
                                            <td colspan="4" class="no-data">
                                                Aucune note disponible
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@stop

@section('js')
    <!-- AOS JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialisation d'AOS avec des paramètres sobres
        AOS.init({
            duration: 600,
            easing: 'ease-out',
            once: true,
            offset: 30,
            delay: 50
        });

        // Animation subtile au survol des lignes de tableau
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('.table tbody tr');
            
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(2px)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
@stop