@extends('layouts.app')

@section('title', 'Tableau de bord - ISI')

@section('content')
<div class="row">
    <!-- Statistiques -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white animate__animated animate__fadeInLeft">
            <div class="card-body">
                <h5 class="card-title">Total Professeurs</h5>
                <h2 class="card-text">{{ $totalProfesseurs ?? 0 }}</h2>
                <i class="fas fa-chalkboard-teacher position-absolute top-50 end-0 translate-middle-y opacity-25 fa-2x"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white animate__animated animate__fadeInLeft" style="animation-delay: 0.2s">
            <div class="card-body">
                <h5 class="card-title">Cours du Jour</h5>
                <h2 class="card-text">{{ $coursDuJour ?? 0 }}</h2>
                <i class="fas fa-book position-absolute top-50 end-0 translate-middle-y opacity-25 fa-2x"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white animate__animated animate__fadeInLeft" style="animation-delay: 0.4s">
            <div class="card-body">
                <h5 class="card-title">Présences Aujourd'hui</h5>
                <h2 class="card-text">{{ $presencesAujourdhui ?? 0 }}</h2>
                <i class="fas fa-check-circle position-absolute top-50 end-0 translate-middle-y opacity-25 fa-2x"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white animate__animated animate__fadeInLeft" style="animation-delay: 0.6s">
            <div class="card-body">
                <h5 class="card-title">Salles Disponibles</h5>
                <h2 class="card-text">{{ $sallesDisponibles ?? 0 }}</h2>
                <i class="fas fa-door-open position-absolute top-50 end-0 translate-middle-y opacity-25 fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Graphique des présences -->
    <div class="col-md-8 mb-4">
        <div class="card animate__animated animate__fadeInUp">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Statistiques des Présences</h5>
            </div>
            <div class="card-body">
                <canvas id="presenceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Dernières activités -->
    <div class="col-md-4 mb-4">
        <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Dernières Activités</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($dernieresActivites ?? [] as $activite)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $activite->description }}</h6>
                                <small>{{ $activite->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            Aucune activité récente
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Prochains cours -->
    <div class="col-md-6 mb-4">
        <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Prochains Cours</h5>
                <a href="{{ route('cours.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($prochainsCours ?? [] as $cours)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $cours->nom }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $cours->heure_debut->format('H:i') }} - {{ $cours->heure_fin->format('H:i') }}
                                    </small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $cours->salle->libelle }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            Aucun cours prévu
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div class="col-md-6 mb-4">
        <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Notifications</h5>
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifications ?? [] as $notification)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $notification->message }}</h6>
                                    <small class="text-muted">{{ $notification->date_envoi->diffForHumans() }}</small>
                                </div>
                                @if(!$notification->lu)
                                    <span class="badge bg-danger rounded-pill">Nouveau</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            Aucune notification
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration du graphique des présences
    const ctx = document.getElementById('presenceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            datasets: [{
                label: 'Présences',
                data: [65, 59, 80, 81, 56, 55],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 