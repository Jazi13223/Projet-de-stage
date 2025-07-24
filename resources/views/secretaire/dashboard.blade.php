@extends('adminlte::page')

@section('title', 'Tableau de bord Secrétaire')

@section('content_header')
    <div class="modern-header" data-aos="fade-down">
        <h1><i class="fas fa-tachometer-alt"></i> Tableau de bord</h1>
        <p>Vue d'ensemble des activités académiques</p>
    </div>
@stop

@section('content')
<div class="row">
    <!-- Étudiants -->
    <div class="col-lg-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="100">
        <div class="stat-box primary">
            <div class="stat-content">
                <h3>{{ $totalEtudiants }}</h3>
                <p>Étudiants inscrits</p>
                <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
            </div>
            <div class="stat-footer">
                <a href="#">Voir la liste <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Réclamations -->
    <div class="col-lg-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="200">
        <div class="stat-box warning">
            <div class="stat-content">
                <h3>{{ $reclamationsEnAttente }}</h3>
                <p>Réclamations en attente</p>
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
            <div class="stat-footer">
                <a href="#">Traiter maintenant <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Moyennes UE -->
    <div class="col-lg-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="300">
        <div class="stat-box success">
            <div class="stat-content">
                <h3>UE</h3>
                <p>Moyennes par UE</p>
                <div class="stat-icon"><i class="fas fa-chart-bar"></i></div>
            </div>
            <div class="stat-footer">
                <a href="#chartUE" class="scroll-link">Voir détails <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Notes ECUE -->
    <div class="col-lg-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="400">
        <div class="stat-box info">
            <div class="stat-content">
                <h3>ECUE</h3>
                <p>Notes par ECUE</p>
                <div class="stat-icon"><i class="fas fa-chart-pie"></i></div>
            </div>
            <div class="stat-footer">
                <a href="#chartECUE" class="scroll-link">Voir détails <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row">
    <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="500">
        <div class="chart-container">
            <div class="chart-header">
                <h4><i class="fas fa-chart-bar"></i> Moyennes par UE</h4>
            </div>
            <canvas id="chartUE"></canvas>
        </div>
    </div>

    <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="600">
        <div class="chart-container">
            <div class="chart-header">
                <h4><i class="fas fa-chart-pie"></i> Notes par ECUE</h4>
            </div>
            <canvas id="chartECUE"></canvas>
        </div>
    </div>
</div>
@stop

@push('css')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
.content-wrapper { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); }

.modern-header {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    padding: 2rem;
    margin: -15px -15px 30px -15px;
    border-radius: 0 0 20px 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border-bottom: 3px solid #3498db;
}

.modern-header h1 {
    color: #2c3e50;
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0;
    background: linear-gradient(135deg, #3498db, #2c3e50);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.modern-header p {
    color: #718096;
    margin: 0.5rem 0 0 0;
    font-size: 1.1rem;
}

.stat-box {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
    border: none;
}

.stat-box:hover {
    transform: translateY(-8px) scale(1.01);
    box-shadow: 0 15px 30px rgba(0,0,0,0.12);
}

.stat-content {
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.stat-content h3 {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    color: #2c3e50;
}

.stat-content p {
    color: #6c757d;
    font-weight: 500;
    margin: 0;
    font-size: 0.9rem;
}

.stat-icon {
    position: absolute;
    right: 1rem;
    top: 1rem;
    font-size: 2.5rem;
    opacity: 0.6;
    color: inherit;
}

.stat-footer {
    background: rgba(52, 152, 219, 0.05);
    padding: 1rem 2rem;
    border-top: 1px solid rgba(52, 152, 219, 0.1);
}

.stat-footer a {
    color: #6c757d;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.85rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.stat-footer a:hover {
    color: #3498db;
    text-decoration: none;
    transform: translateX(5px);
}

.stat-box.primary { 
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); 
    border-left: 5px solid #3498db; 
}
.stat-box.primary .stat-content h3 { color: #1976d2; }
.stat-box.primary .stat-icon { color: #3498db; }

.stat-box.warning { 
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); 
    border-left: 5px solid #f39c12; 
}
.stat-box.warning .stat-content h3 { color: #f57c00; }
.stat-box.warning .stat-icon { color: #f39c12; }

.stat-box.success { 
    background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%); 
    border-left: 5px solid #27ae60; 
}
.stat-box.success .stat-content h3 { color: #2e7d32; }
.stat-box.success .stat-icon { color: #27ae60; }

.stat-box.info { 
    background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%); 
    border-left: 5px solid #8e44ad; 
}
.stat-box.info .stat-content h3 { color: #7b1fa2; }
.stat-box.info .stat-icon { color: #8e44ad; }

.chart-container {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.chart-container:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.12);
}

.chart-header {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    padding: 1.5rem 2rem;
}

.chart-header h4 {
    margin: 0;
    font-weight: 600;
    font-size: 1.1rem;
}

.chart-container canvas {
    padding: 2rem;
    height: 350px !important;
}

@media (max-width: 768px) {
    .modern-header { padding: 1.5rem; margin: -15px -15px 20px -15px; }
    .modern-header h1 { font-size: 1.8rem; }
    .stat-content { padding: 1.5rem; }
    .stat-content h3 { font-size: 2rem; }
    .chart-container canvas { height: 300px !important; }
}
</style>
@endpush

@push('js')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 600, once: true });

// Données
const ueLabels = @json($ues->pluck('ue.name'));
const ueData = @json($ues->pluck('final_average'));
const ecueLabels = @json($ecues->pluck('ecue.name'));
const ecueData = @json($ecues->pluck('final_grade'));

// Graphique UE
new Chart(document.getElementById('chartUE'), {
    type: 'bar',
    data: {
        labels: ueLabels,
        datasets: [{
            data: ueData,
            backgroundColor: 'rgba(52, 152, 219, 0.7)',
            borderColor: '#3498db',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, suggestedMax: 20 },
            x: { ticks: { maxRotation: 45 } }
        }
    }
});

// Graphique ECUE
new Chart(document.getElementById('chartECUE'), {
    type: 'doughnut',
    data: {
        labels: ecueLabels,
        datasets: [{
            data: ecueData,
            backgroundColor: [
                'rgba(52, 152, 219, 0.8)', 
                'rgba(39, 174, 96, 0.8)', 
                'rgba(243, 156, 18, 0.8)', 
                'rgba(142, 68, 173, 0.8)', 
                'rgba(231, 76, 60, 0.8)'
            ],
            borderWidth: 3,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { padding: 20 } } },
        cutout: '60%'
    }
});

// Scroll fluide
document.querySelectorAll('.scroll-link').forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        document.querySelector(link.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
    });
});
</script>
@endpush