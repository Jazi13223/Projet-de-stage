@extends('adminlte::page')

@section('title', 'Validation Académique')

@section('content_header')
    <div data-aos="fade-down" data-aos-duration="800">
        <h1 class="text-dark font-weight-bold">Validation Académique</h1>
        <p class="text-muted mb-0">Année académique {{ $anneeAcademique }}</p>
    </div>
@stop

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<style>
    .professional-card {
        border: none;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border-radius: 8px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .professional-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }
    
    .info-callout {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #6c757d;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
    }
    
    .badge-validated {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .badge-not-validated {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .badge-score {
        background-color: #e3f2fd;
        color: #1565c0;
        border: 1px solid #bbdefb;
    }
    
    .progress-professional {
        height: 12px;
        border-radius: 10px;
        background-color: #e9ecef;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .progress-bar-professional {
        height: 100%;
        border-radius: 10px;
        transition: width 1.5s ease-in-out;
        background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
    }
    
    .progress-bar-danger {
        background: linear-gradient(90deg, #dc3545 0%, #fd7e14 100%);
    }
    
    .card-header-professional {
        background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
        color: white;
        border-bottom: none;
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    
    .table-professional {
        margin-bottom: 0;
    }
    
    .table-professional th {
        border-top: none;
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
        padding: 1rem;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
    }
    
    .table-professional td {
        padding: 1rem;
        vertical-align: middle;
        color: #6c757d;
    }
    
    .table-professional tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }
    
    .section-title {
        color: #495057;
        font-weight: 700;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.5rem;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, #6c757d, #adb5bd);
        border-radius: 2px;
    }
    
    .metric-card {
        text-align: center;
        padding: 1.5rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
    
    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .metric-label {
        color: #6c757d;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .divider-professional {
        height: 1px;
        background: linear-gradient(90deg, transparent, #dee2e6, transparent);
        border: none;
        margin: 3rem 0;
    }
</style>
@endpush

@section('content')
    <!-- Informations générales -->
    <div class="info-callout" data-aos="fade-up" data-aos-duration="600">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-3 text-dark font-weight-bold">Statut de Validation</h4>
                <div class="d-flex flex-wrap gap-3 mb-3">
                    <div class="d-flex align-items-center">
                        <span class="text-muted mr-2">Validation Annuelle :</span>
                        <span class="status-badge {{ $validationAnnuelle ? 'badge-validated' : 'badge-not-validated' }}">
                            {{ $validationAnnuelle ? 'Validée' : 'Non validée' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card">
                    <div class="metric-value">
                        {{ $moyenneAnnuelle !== null ? number_format($moyenneAnnuelle, 2) : '—' }}
                    </div>
                    <div class="metric-label">Moyenne Annuelle / 20</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pourcentage de Validation -->
    <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
        <h5 class="section-title">Taux de Validation</h5>
        <div class="professional-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted font-weight-600">Progression</span>
                <span class="font-weight-bold text-dark">{{ $tauxValidation }}%</span>
            </div>
            <div class="progress-professional">
                <div class="progress-bar-professional {{ $tauxValidation >= 50 ? '' : 'progress-bar-danger' }}" 
                     style="width: {{ $tauxValidation }}%;" 
                     data-aos="slide-right" 
                     data-aos-duration="1500" 
                     data-aos-delay="300">
                </div>
            </div>
        </div>
    </div>

    <hr class="divider-professional">

    <!-- Validation Semestrielle -->
    <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
        <h5 class="section-title">Validation Semestrielle</h5>
        <div class="row">
            @foreach($validationsSemestrielles as $index => $data)
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-duration="600" data-aos-delay="{{ 100 * ($index + 1) }}">
                    <div class="professional-card h-100">
                        <div class="card-header-professional">
                            {{ $data['nom'] }}
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="metric-card border-0 bg-light">
                                        <div class="metric-value" style="font-size: 1.5rem;">
                                            {{ $data['moyenne'] !== null ? number_format($data['moyenne'], 2) : '—' }}
                                        </div>
                                        <div class="metric-label" style="font-size: 0.75rem;">Moyenne / 20</div>
                                    </div>
                                </div>
                                <div class="col-6 d-flex align-items-center justify-content-center">
                                    <span class="status-badge {{ $data['valide'] ? 'badge-validated' : 'badge-not-validated' }}">
                                        {{ $data['valide'] ? 'Validée' : 'Non validée' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <hr class="divider-professional">

    <!-- Détail des UE validées par semestre -->
    <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
        <h5 class="section-title">Unités d'Enseignement</h5>
        <div class="row">
            @foreach($uesParSemestre as $semestre => $ues)
                <div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-duration="600" data-aos-delay="{{ 150 * ($loop->iteration) }}">
                    <div class="professional-card h-100">
                        <div class="card-header-professional">
                            {{ $semestre }} - Détail des UE
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-professional">
                                    <thead>
                                        <tr>
                                            <th>Unité d'Enseignement</th>
                                            <th class="text-center">Moyenne</th>
                                            <th class="text-center">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ues as $ue)
                                            <tr data-aos="fade-in" data-aos-duration="400" data-aos-delay="{{ 50 * $loop->iteration }}">
                                                <td class="font-weight-600">{{ $ue['nom'] }}</td>
                                                <td class="text-center">
                                                    <span class="status-badge badge-score">
                                                        {{ $ue['moyenne'] !== null ? number_format($ue['moyenne'], 2) : '—' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="status-badge {{ ($ue['moyenne'] !== null && $ue['moyenne'] >= 12) ? 'badge-validated' : 'badge-not-validated' }}">
                                                        {{ ($ue['moyenne'] !== null && $ue['moyenne'] >= 12) ? 'Validée' : 'Non validée' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">
                                                    <i>Aucune UE disponible pour ce semestre</i>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser AOS
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
        
        // Animation personnalisée pour la barre de progression
        setTimeout(() => {
            const progressBar = document.querySelector('.progress-bar-professional');
            if (progressBar) {
                progressBar.style.opacity = '1';
            }
        }, 500);
    });
</script>
@endpush