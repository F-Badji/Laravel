@extends('layouts.app')

@section('title', 'Gestion des Émargements - ISI')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
        <h1 class="h3 mb-0">Gestion des Émargements</h1>
        <a href="{{ route('emargements.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvel Émargement
        </a>
    </div>

    <div class="card animate__animated animate__fadeInUp">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Professeur</th>
                            <th>Cours</th>
                            <th>Statut</th>
                            <th>Validation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emargements as $emargement)
                            <tr class="animate__animated animate__fadeIn">
                                <td>{{ $emargement->date->format('d/m/Y H:i') }}</td>
                                <td>
                                    {{ $emargement->professeur->nom }} 
                                    {{ $emargement->professeur->prenom }}
                                </td>
                                <td>{{ $emargement->cours->nom }}</td>
                                <td>
                                    @switch($emargement->statut)
                                        @case('present')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Présent
                                            </span>
                                            @break
                                        @case('absent')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Absent
                                            </span>
                                            @break
                                        @case('retard')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Retard
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    @if($emargement->valide)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Validé
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('emargements.show', $emargement) }}" 
                                           class="btn btn-sm btn-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('emargements.edit', $emargement) }}" 
                                           class="btn btn-sm btn-warning" 
                                           data-bs-toggle="tooltip" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$emargement->valide)
                                            <form action="{{ route('emargements.valider', $emargement) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-success" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Valider">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('emargements.destroy', $emargement) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet émargement ?');">
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
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                                        <p>Aucun émargement enregistré</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $emargements->links() }}
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