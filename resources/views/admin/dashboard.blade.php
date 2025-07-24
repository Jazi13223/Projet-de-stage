@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="header-modern" data-aos="fade-down">
        <h1>Tableau de Bord</h1>
        <p>Vue d'ensemble de votre système</p>
    </div>
@stop

@section('content')
<!-- Stats Cards -->
<div class="row">
    <div class="col-lg-3 col-6" data-aos="zoom-in" data-aos-delay="100">
        <div class="stat-box blue-gradient">
            <div class="stat-content">
                <h3>{{ $etudiantsCount }}</h3>
                <p>Étudiants</p>
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6" data-aos="zoom-in" data-aos-delay="200">
        <div class="stat-box green-gradient">
            <div class="stat-content">
                <h3>{{ $filieresCount }}</h3>
                <p>Filières</p>
                <i class="fas fa-book"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6" data-aos="zoom-in" data-aos-delay="300">
        <div class="stat-box orange-gradient">
            <div class="stat-content">
                <h3>{{ $secretairesCount }}</h3>
                <p>Secrétaires</p>
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6" data-aos="zoom-in" data-aos-delay="400">
        <div class="stat-box purple-gradient">
            <div class="stat-content">
                <h3>{{ $validationsCount }}</h3>
                <p>Résultats publiés</p>
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="chart-card" data-aos="fade-up" data-aos-delay="500">
            <div class="chart-header">
                <h3>Distribution des Étudiants par Filière</h3>
            </div>
            <div class="chart-body">
                <canvas id="studentsByFiliereChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Notifications -->
    <div class="col-md-6">
        <div class="content-card" data-aos="fade-right" data-aos-delay="600">
            <div class="card-header-custom">
                <div>
                    <h3>Notifications Système</h3>
                    <span class="subtitle">Alertes importantes</span>
                </div>
                <button class="btn-custom" data-toggle="modal" data-target="#modalToutesNotifications">
                    <i class="fas fa-bell"></i> Voir tout
                </button>
            </div>
            <div class="card-body-custom">
                @foreach($notifications as $notification)
                    <div class="notification-item {{ $notification->type }}" data-aos="slide-right" data-aos-delay="{{ 700 + $loop->index * 100 }}">
                        <div class="notif-icon">
                            <i class="fas 
                                @if($notification->type === 'success') fa-check-circle 
                                @elseif($notification->type === 'danger') fa-exclamation-triangle 
                                @elseif($notification->type === 'info') fa-info-circle 
                                @else fa-bell 
                                @endif"></i>
                        </div>
                        <div class="notif-content">
                            <span>{{ $notification->message }}</span>
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Activities -->
    <div class="col-md-6">
        <div class="content-card" data-aos="fade-left" data-aos-delay="700">
            <div class="card-header-custom">
                <div>
                    <h3>Historique des Activités</h3>
                    <span class="subtitle">Dernières actions</span>
                </div>
                <button class="btn-custom" data-toggle="modal" data-target="#modalHistoriqueComplet">
                    <i class="fas fa-history"></i> Voir tout
                </button>
            </div>
            <div class="card-body-custom">
                <div class="timeline-container">
                    @foreach($activites->take(5) as $log)
                        <div class="timeline-item" data-aos="fade-up" data-aos-delay="{{ 800 + $loop->index * 100 }}">
                            <div class="timeline-marker {{ strtolower($log->action) }}">
                                <i class="fas 
                                    @if($log->action === 'Ajout') fa-plus
                                    @elseif($log->action === 'Modification') fa-edit
                                    @elseif($log->action === 'Suppression') fa-trash
                                    @else fa-info-circle
                                    @endif"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <strong>{{ $log->user ? $log->user->name : 'Utilisateur' }}</strong>
                                    <span class="action-badge">{{ $log->action }}</span>
                                    <span class="time">{{ $log->created_at->format('H:i') }}</span>
                                </div>
                                <p>{{ $log->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals restent identiques -->
<div class="modal fade" id="modalToutesNotifications" tabindex="-1" role="dialog" aria-labelledby="modalToutesNotificationsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalToutesNotificationsLabel">
                    <i class="fas fa-bell"></i> Toutes les Notifications
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach($notifications as $notification)
                    <div class="alert 
                        @if($notification->type === 'success') alert-success
                        @elseif($notification->type === 'danger') alert-danger
                        @elseif($notification->type === 'info') alert-info
                        @else alert-secondary
                        @endif d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas 
                                @if($notification->type === 'success') fa-check-circle
                                @elseif($notification->type === 'danger') fa-times-circle
                                @elseif($notification->type === 'info') fa-info-circle
                                @else fa-bell
                                @endif mr-2"></i>
                            {{ $notification->message }}
                        </div>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                @endforeach
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{ $notifications->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHistoriqueComplet" tabindex="-1" role="dialog" aria-labelledby="modalHistoriqueCompletLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalHistoriqueCompletLabel">
                    <i class="fas fa-history"></i> Historique Complet
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="timeline">
                    @php $lastDate = null; @endphp
                    @foreach($activites as $log)
                        @php $currentDate = \Carbon\Carbon::parse($log->created_at)->translatedFormat('d F Y'); @endphp
                        @if($currentDate !== $lastDate)
                            <div class="time-label">
                                <span class="bg-info text-white px-2 py-1 rounded">{{ $currentDate }}</span>
                            </div>
                            @php $lastDate = $currentDate; @endphp
                        @endif
                        <div>
                            <i class="fas 
                                @if($log->action === 'Ajout') fa-plus bg-success
                                @elseif($log->action === 'Modification') fa-edit bg-warning
                                @elseif($log->action === 'Suppression') fa-trash bg-danger
                                @else fa-info-circle bg-secondary
                                @endif"></i>
                            <div class="timeline-item shadow-sm border-left pl-3">
                                <span class="time text-muted">
                                    <i class="far fa-clock"></i> {{ $log->created_at->format('H:i') }}
                                </span>
                                <h3 class="timeline-header mb-1 font-weight-bold">
                                    {{ $log->user ? $log->user->name : 'Utilisateur' }} — {{ $log->action }}
                                </h3>
                                <div class="timeline-body">
                                    <small class="text-dark">{{ $log->description }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{ $activites->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
<style>
/* Header */
.header-modern {
    background: linear-gradient(135deg,rgb(102, 234, 122) 0%,rgb(200, 241, 208) 100%);
    color: white;
    padding: 2rem;
    margin: -1rem -1rem 2rem;
    border-radius: 0 0 15px 15px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.header-modern h1 { font-size: 2.5rem; margin: 0; font-weight: 700; }
.header-modern p { margin: 0.5rem 0 0; opacity: 0.9; font-size: 1.1rem; }

/* Stat Cards */
.stat-box {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.stat-box:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }

.blue-gradient { border-left: 4px solid #4285f4; }
.green-gradient { border-left: 4px solid #0f9d58; }
.orange-gradient { border-left: 4px solid #ff6900; }
.purple-gradient { border-left: 4px solid #9c27b0; }

.stat-content { position: relative; z-index: 2; }
.stat-content h3 { font-size: 2.5rem; font-weight: 700; margin: 0; color: #2c3e50; }
.stat-content p { color: #7f8c8d; margin: 0.5rem 0 0; font-weight: 600; text-transform: uppercase; font-size: 0.9rem; }
.stat-content i { position: absolute; right: 0; top: 0; font-size: 3rem; opacity: 0.1; color: #2c3e50; }

/* Cards */
.chart-card, .content-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.chart-card:hover, .content-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.15); }

.chart-header, .card-header-custom {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-header h3, .card-header-custom h3 { margin: 0; font-weight: 600; color: #2c3e50; }
.subtitle { color: #6c757d; font-size: 0.9rem; display: block; }

.chart-body, .card-body-custom { padding: 1.5rem; max-height: 400px; overflow-y: auto; }

.btn-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-custom:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3); color: white; }

/* Notifications */
.notification-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 10px;
    transition: all 0.3s ease;
    border-left: 3px solid #dee2e6;
}

.notification-item:hover { transform: translateX(5px); }
.notification-item.success { border-left-color: #28a745; background: #f8fff9; }
.notification-item.danger { border-left-color: #dc3545; background: #fff8f8; }
.notification-item.info { border-left-color: #17a2b8; background: #f8fcff; }

.notif-icon { margin-right: 1rem; font-size: 1.2rem; }
.success .notif-icon { color: #28a745; }
.danger .notif-icon { color: #dc3545; }
.info .notif-icon { color: #17a2b8; }

.notif-content span { display: block; font-weight: 500; color: #2c3e50; }
.notif-content small { color: #6c757d; font-size: 0.85rem; }

/* Timeline */
.timeline-container { position: relative; }
.timeline-item { display: flex; align-items: flex-start; margin-bottom: 1.5rem; }

.timeline-marker {
    width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    color: white; margin-right: 1rem; flex-shrink: 0; font-size: 0.8rem;
}

.timeline-marker.ajout { background: #28a745; }
.timeline-marker.modification { background: #ffc107; }
.timeline-marker.suppression { background: #dc3545; }
.timeline-marker.assignation { background: #17a2b8; }

.timeline-content {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 10px;
    flex: 1;
    border-left: 3px solid #667eea;
}

.timeline-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; flex-wrap: wrap; }
.action-badge { background: #667eea; color: white; padding: 0.2rem 0.5rem; border-radius: 5px; font-size: 0.8rem; }
.time { color: #6c757d; font-size: 0.85rem; margin-left: auto; }
.timeline-content p { margin: 0; color: #495057; }

/* Responsive */
@media (max-width: 768px) {
    .header-modern h1 { font-size: 2rem; }
    .stat-content h3 { font-size: 2rem; }
    .timeline-header { flex-direction: column; align-items: flex-start; }
    .time { margin-left: 0; }
}
</style>
@stop

@section('js')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
AOS.init({ duration: 600, easing: 'ease-out-cubic', once: true });

var ctx = document.getElementById('studentsByFiliereChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: @json($etudiantsParFiliere->pluck('nom_filiere')),
        datasets: [{
            data: @json($etudiantsParFiliere->pluck('students_count')),
            backgroundColor: ['#4285f4', '#0f9d58', '#ff6900', '#9c27b0', '#00bcd4', '#ff5722'],
            borderWidth: 0,
            hoverBorderWidth: 3,
            hoverBorderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } }
        }
    }
});
ctx.canvas.style.height = '300px';
</script>
@stop