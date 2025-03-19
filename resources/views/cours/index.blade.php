@extends('layouts.app')

@section('title', 'Gestion des Cours - ISI')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
        <h1 class="h3 mb-0">Gestion des Cours</h1>
        <a href="{{ route('cours.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau Cours
        </a>
    </div>

    <div class="card animate__animated animate__fadeInUp">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Salle</th>
                            <th>Professeur</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cours as $coursItem)
                            <tr class="animate__animated animate__fadeIn">
                                <td>{{ $coursItem->nom }}</td>
                                <td>{{ Str::limit($coursItem->description, 50) }}</td>
                                <td>{{ $coursItem->heure_debut->format('d/m/Y') }}</td>
                                <td>
                                    {{ $coursItem->heure_debut->format('H:i') }} - 
                                    {{ $coursItem->heure_fin->format('H:i') }}
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $coursItem->salle->libelle }}
                                    </span>
                                </td>
                                <td>
                                    {{ $coursItem->professeur->nom }} 
                                    {{ $coursItem->professeur->prenom }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('cours.show', $coursItem) }}" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('cours.edit', $coursItem) }}" 
                                           class="btn btn-sm btn-warning" 
                                           data-bs-toggle="tooltip" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('cours.destroy', $coursItem) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-book fa-2x mb-3"></i>
                                        <p>Aucun cours enregistré</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endpush
@endsection 