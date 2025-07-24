@extends('adminlte::page')

@section('title', 'Mes Résultats')

@section('content_header')
    <div class="content-header-wrapper" data-aos="fade-down" data-aos-duration="800">
        <h1 class="page-title">Mes Résultats Globaux</h1>
        <div class="header-divider"></div>
    </div>
@stop

@section('content')
    <div class="results-container">
        <!-- Carte de pourcentage global -->
        <div class="percentage-card" data-aos="fade-up" data-aos-duration="600">
            <div class="percentage-header">
                <h3>Pourcentage de Validation Globale</h3>
            </div>
            <div class="percentage-body">
                <div class="percentage-circle">
                    <span class="percentage-value">{{ round($pourcentage ?? 0, 2) }}%</span>
                </div>
                <div class="validation-stats">
                    <div class="stat-item validated" data-aos="fade-right" data-aos-delay="200">
                        <div class="stat-number">{{ $validUes ?? 0 }}</div>
                        <div class="stat-label">UE(s) Validée(s)</div>
                    </div>
                    <div class="stat-item not-validated" data-aos="fade-left" data-aos-delay="300">
                        <div class="stat-number">{{ $invalidUes ?? 0 }}</div>
                        <div class="stat-label">UE(s) Non Validée(s)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des UE -->
        <div class="ues-container">
            @forelse($ues as $index => $ue)
                <div class="ue-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="ue-header">
                        <div class="ue-title">
                            <h4>{{ $ue['nom'] }}</h4>
                        </div>
                        <div class="ue-status">
                            <div class="grade-badge {{ ($ue['moyenne_ue'] ?? 0) >= 10 ? 'validated' : 'not-validated' }}">
                                {{ $ue['moyenne_ue'] !== null ? $ue['moyenne_ue'] . '/20' : '—' }}
                            </div>
                            <div class="status-badge {{ ($ue['moyenne_ue'] ?? 0) >= 10 ? 'validated' : 'not-validated' }}">
                                {{ ($ue['moyenne_ue'] ?? 0) >= 10 ? 'Validée' : 'Non Validée' }}
                            </div>
                        </div>
                    </div>

                    <div class="ue-body">
                        <div class="section-title">
                            <h5>Notes UE Directes</h5>
                        </div>
                        
                        <div class="notes-table-wrapper">
                            <table class="notes-table">
                                <thead>
                                    <tr>
                                        <th>Type d'évaluation</th>
                                        <th>Session normale</th>
                                        <th>Rattrapage</th>
                                        <th>Note retenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ue['notes'] as $note)
                                        <tr>
                                            <td class="type-cell">{{ ucfirst($note['type']) }}</td>
                                            <td class="grade-cell">
                                                {{ $note['session'] === 'normale' ? ($note['valeur'] . '/20') : '—' }}
                                            </td>
                                            <td class="grade-cell">
                                                {{ $note['session'] === 'rattrapage' ? ($note['valeur'] . '/20') : '—' }}
                                            </td>
                                            <td class="final-grade-cell">
                                                @if ($ue['note_retenue'] !== null)
                                                    <span class="final-grade">{{ $ue['note_retenue'] }}/20</span>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(!empty($ue['ecues']))
                            <!-- ECUEs -->
                            @foreach ($ue['ecues'] as $ecueIndex => $ecue)
                                <div class="ecue-section" data-aos="fade-up" data-aos-delay="{{ ($index * 100) + ($ecueIndex * 50) }}">
                                    <div class="section-title">
                                        <h5>{{ $ecue['nom'] }}</h5>
                                        <div class="ecue-grade {{ ($ecue['moyenne_ecue'] ?? 0) >= 10 ? 'validated' : 'not-validated' }}">
                                            {{ $ecue['moyenne_ecue'] !== null ? $ecue['moyenne_ecue'] . '/20' : '—' }}
                                        </div>
                                    </div>

                                    <div class="notes-table-wrapper">
                                        <table class="notes-table">
                                            <thead>
                                                <tr>
                                                    <th>Type d'évaluation</th>
                                                    <th>Session normale</th>
                                                    <th>Rattrapage</th>
                                                    <th>Note retenue</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ecue['notes'] as $note)
                                                    <tr>
                                                        <td class="type-cell">{{ ucfirst($note['type']) }}</td>
                                                        <td class="grade-cell">
                                                            {{ $note['session'] === 'normale' ? ($note['valeur'] . '/20') : '—' }}
                                                        </td>
                                                        <td class="grade-cell">
                                                            {{ $note['session'] === 'rattrapage' ? ($note['valeur'] . '/20') : '—' }}
                                                        </td>
                                                        <td class="final-grade-cell">
                                                            @if ($ecue['note_retenue'] !== null)
                                                                <span class="final-grade">{{ $ecue['note_retenue'] }}/20</span>
                                                            @else
                                                                —
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-ecues-message">
                                <p>Aucune ECUE pour cette UE.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state" data-aos="fade-up">
                    <div class="empty-icon">📚</div>
                    <h3>Aucune UE assignée</h3>
                    <p>Aucune UE n'est assignée à votre filière pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
@stop

@section('css')
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* Variables CSS */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #6c757d;
            --border-color: #dee2e6;
            --shadow-light: 0 2px 4px rgba(0,0,0,0.1);
            --shadow-medium: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-strong: 0 8px 25px rgba(0,0,0,0.15);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        /* Reset et base */
        .content-wrapper {
            background-color: var(--light-gray);
        }

        /* Header */
        .content-header-wrapper {
            text-align: center;
            margin-bottom: 2rem;
        }

        .page-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 1rem;
            letter-spacing: -0.025em;
        }

        .header-divider {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            margin: 0 auto;
            border-radius: 2px;
        }

        /* Container principal */
        .results-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Carte de pourcentage */
        .percentage-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-strong);
            margin-bottom: 2rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .percentage-header {
            background: var(--primary-color);
            color: white;
            padding: 1.5rem 2rem;
            text-align: center;
        }

        .percentage-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.3rem;
        }

        .percentage-body {
            padding: 2rem;
            text-align: center;
        }

        .percentage-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            margin-bottom: 2rem;
            position: relative;
        }

        .percentage-circle::before {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .percentage-value {
            position: relative;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            z-index: 1;
        }

        .validation-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-item.validated .stat-number {
            color: var(--success-color);
        }

        .stat-item.not-validated .stat-number {
            color: var(--danger-color);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--dark-gray);
            font-weight: 500;
        }

        /* Cartes UE */
        .ue-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            margin-bottom: 1.5rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .ue-card:hover {
            box-shadow: var(--shadow-strong);
            transform: translateY(-2px);
        }

        .ue-header {
            background: var(--light-gray);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .ue-title h4 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.25rem;
        }

        .ue-status {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .grade-badge, .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .grade-badge.validated, .status-badge.validated {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(39, 174, 96, 0.3);
        }

        .grade-badge.not-validated, .status-badge.not-validated {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(231, 76, 60, 0.3);
        }

        .ue-body {
            padding: 2rem;
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--light-gray);
        }

        .section-title h5 {
            margin: 0;
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .ecue-grade {
            padding: 0.3rem 0.6rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .ecue-grade.validated {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .ecue-grade.not-validated {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        /* Tables */
        .notes-table-wrapper {
            overflow-x: auto;
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .notes-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 0.9rem;
        }

        .notes-table thead th {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .notes-table tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .notes-table tbody tr:hover {
            background-color: var(--light-gray);
        }

        .notes-table tbody tr:last-child td {
            border-bottom: none;
        }

        .type-cell {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .grade-cell {
            text-align: center;
            color: var(--dark-gray);
        }

        .final-grade-cell {
            text-align: center;
        }

        .final-grade {
            background-color: var(--primary-color);
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Section ECUE */
        .ecue-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        /* Messages */
        .no-ecues-message {
            text-align: center;
            padding: 2rem;
            color: var(--dark-gray);
            font-style: italic;
            background-color: var(--light-gray);
            border-radius: var(--border-radius);
            margin-top: 1rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--border-color);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--dark-gray);
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .results-container {
                padding: 0 0.5rem;
            }

            .page-title {
                font-size: 1.8rem;
            }

            .percentage-body {
                padding: 1.5rem;
            }

            .validation-stats {
                gap: 1.5rem;
            }

            .ue-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .ue-body {
                padding: 1.5rem;
            }

            .notes-table thead th,
            .notes-table tbody td {
                padding: 0.5rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .validation-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .percentage-circle {
                width: 100px;
                height: 100px;
            }

            .percentage-circle::before {
                width: 80px;
                height: 80px;
            }

            .percentage-value {
                font-size: 1.4rem;
            }
        }
    </style>
@stop

@section('js')
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialisation d'AOS
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });
    </script>
@stop