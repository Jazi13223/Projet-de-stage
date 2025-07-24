@extends('adminlte::page')

@section('title', 'Dashboard Étudiant')

@section('content_header')
    <div class="text-center mb-4" data-aos="fade-down" data-aos-duration="1000">
        <h1 class="dashboard-title">
            <i class="fas fa-user-graduate text-primary"></i>
            Dashboard Étudiant
        </h1>
        <p class="text-muted">Suivez votre progression académique en temps réel</p>
    </div>
@stop

@section('content')
    <!-- Statistiques principales -->
    <div class="row mb-4">
        <!-- Notes Validées -->
        <div class="col-lg-3 col-6" data-aos="fade-up" data-aos-delay="100">
            <div class="small-box bg-gradient-info shadow-lg rounded-lg hover-lift">
                <div class="inner">
                    <h3 class="counter">{{ $notesValidees }}</h3>
                    <p>Notes Validées</p>
                </div>
                <div class="icon pulse">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <a href="#" class="small-box-footer btn-hover">
                    Voir les résultats <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Réclamations -->
        <div class="col-lg-3 col-6" data-aos="fade-up" data-aos-delay="200">
            <div class="small-box bg-gradient-warning shadow-lg rounded-lg hover-lift">
                <div class="inner">
                    <h3 class="counter">{{ $reclamationsCount }}</h3>
                    <p>Réclamations Soumises</p>
                </div>
                <div class="icon pulse">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <a href="#" class="small-box-footer btn-hover">
                    Voir les réclamations <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Unités Validées -->
        <div class="col-lg-3 col-6" data-aos="fade-up" data-aos-delay="300">
            <div class="small-box bg-gradient-success shadow-lg rounded-lg hover-lift">
                <div class="inner">
                    <h3 class="counter">{{ $uesValidees }}</h3>
                    <p>Unités Validées</p>
                </div>
                <div class="icon pulse">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="#" class="small-box-footer btn-hover">
                    Voir les unités <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Moyenne Générale -->
        <div class="col-lg-3 col-6" data-aos="fade-up" data-aos-delay="400">
            <div class="small-box bg-gradient-danger shadow-lg rounded-lg hover-lift">
                <div class="inner">
                    <h3 class="counter">{{ number_format($moyenneGenerale, 2) }}</h3>
                    <p>Moyenne Générale</p>
                </div>
                <div class="icon pulse">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <a href="#" class="small-box-footer btn-hover">
                    Voir la moyenne <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mt-4">
        <!-- Résultats ECUE -->
        <div class="col-md-6" data-aos="fade-right" data-aos-duration="1000">
            <div class="card shadow-lg border-0 rounded-lg modern-card">
                <div class="card-header bg-gradient-info text-white rounded-top">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Moyennes par ECUE
                    </h3>
                </div>
                <div class="card-body p-4">
                    <canvas id="ecueChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Moyennes UE -->
        <div class="col-md-6" data-aos="fade-left" data-aos-duration="1000">
            <div class="card shadow-lg border-0 rounded-lg modern-card">
                <div class="card-header bg-gradient-success text-white rounded-top">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-2"></i>
                        Moyennes par UE
                    </h3>
                </div>
                <div class="card-body p-4">
                    <canvas id="ueChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Section supplémentaire pour améliorer le design -->
    <div class="row mt-5" data-aos="fade-up" data-aos-duration="1000">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-lg modern-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h3 class="card-title">
                        <i class="fas fa-trophy mr-2"></i>
                        Progression Académique
                    </h3>
                </div>
                <div class="card-body">
                    <div class="progress-section">
                        <div class="progress-item">
                            <span class="progress-label">Taux de Validation</span>
                            <div class="progress progress-lg">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                     style="width: {{ ($uesValidees / max($uesValidees + 5, 10)) * 100 }}%">
                                    {{ round(($uesValidees / max($uesValidees + 5, 10)) * 100) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Styles personnalisés */
        .dashboard-title {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(45deg, #007bff, #17a2b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .hover-lift {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .hover-lift:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;
        }

        .modern-card {
            border-radius: 15px !important;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
        }

        .small-box {
            border-radius: 15px !important;
            overflow: hidden;
        }

        .small-box .icon {
            transition: all 0.3s ease;
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .counter {
            font-size: 2.5rem !important;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .btn-hover {
            transition: all 0.3s ease;
        }

        .btn-hover:hover {
            background-color: rgba(255,255,255,0.2) !important;
            transform: translateX(5px);
        }

        .card-header {
            background: linear-gradient(45deg, var(--bs-primary), var(--bs-info)) !important;
            border: none !important;
        }

        .progress-section {
            padding: 20px 0;
        }

        .progress-item {
            margin-bottom: 20px;
        }

        .progress-label {
            font-weight: bold;
            color: #495057;
            font-size: 1.1rem;
        }

        .progress-lg {
            height: 20px;
            border-radius: 10px;
            margin-top: 10px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Animations pour les graphiques */
        #ecueChart, #ueChart {
            animation: fadeInUp 1s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-title {
                font-size: 2rem;
            }
            
            .counter {
                font-size: 2rem !important;
            }
        }

        /* Effet de particules en arrière-plan */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .content-wrapper {
            background: transparent !important;
        }
    </style>
@stop

@section('js')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialisation AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 50
        });

        // Animation des compteurs
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 30);
        }

        // Animer les compteurs au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseFloat(counter.textContent);
                counter.textContent = '0';
                setTimeout(() => animateCounter(counter, target), 500);
            });
        });

        const ecueLabels = @json($ecueResults->pluck('ecue.name'));
        const ecueData = @json($ecueResults->pluck('final_grade'));

        const ueLabels = @json($ueResults->pluck('ue.name'));
        const ueData = @json($ueResults->pluck('final_average'));

        // Configuration commune des graphiques
        const commonConfig = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#fff',
                    borderWidth: 1,
                    cornerRadius: 10
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: 20,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutBounce'
            }
        };

        // Graphique ECUE avec dégradé
        setTimeout(() => {
            const ecueCtx = document.getElementById('ecueChart').getContext('2d');
            const ecueGradient = ecueCtx.createLinearGradient(0, 0, 0, 300);
            ecueGradient.addColorStop(0, 'rgba(23, 162, 184, 0.8)');
            ecueGradient.addColorStop(1, 'rgba(23, 162, 184, 0.2)');

            new Chart(ecueCtx, {
                type: 'bar',
                data: {
                    labels: ecueLabels,
                    datasets: [{
                        label: 'Note ECUE',
                        data: ecueData,
                        backgroundColor: ecueGradient,
                        borderColor: '#17a2b8',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: commonConfig
            });
        }, 1000);

        // Graphique UE avec dégradé
        setTimeout(() => {
            const ueCtx = document.getElementById('ueChart').getContext('2d');
            const ueGradient = ueCtx.createLinearGradient(0, 0, 0, 300);
            ueGradient.addColorStop(0, 'rgba(40, 167, 69, 0.8)');
            ueGradient.addColorStop(1, 'rgba(40, 167, 69, 0.2)');

            new Chart(ueCtx, {
                type: 'bar',
                data: {
                    labels: ueLabels,
                    datasets: [{
                        label: 'Moyenne UE',
                        data: ueData,
                        backgroundColor: ueGradient,
                        borderColor: '#28a745',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: commonConfig
            });
        }, 1500);

        // Effet de particules (optionnel)
        function createParticles() {
            const particles = document.createElement('div');
            particles.classList.add('particles');
            document.body.appendChild(particles);

            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: fixed;
                    width: 4px;
                    height: 4px;
                    background: rgba(23, 162, 184, 0.3);
                    border-radius: 50%;
                    pointer-events: none;
                    animation: float ${Math.random() * 3 + 2}s infinite linear;
                    left: ${Math.random() * 100}%;
                    top: ${Math.random() * 100}%;
                    z-index: -1;
                `;
                particles.appendChild(particle);
            }
        }

        // Style pour l'animation des particules
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
                10% { opacity: 1; }
                90% { opacity: 1; }
                100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        // Lancer les particules (optionnel - décommenter si souhaité)
        // createParticles();
    </script>
@stop