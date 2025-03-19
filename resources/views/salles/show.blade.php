@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Détails de la Salle : {{ $salle->libelle }}</h4>
                    <div>
                        <a href="{{ route('salles.edit', $salle) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="{{ route('salles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informations de la salle</h5>
                            <table class="table">
                                <tr>
                                    <th>Libellé</th>
                                    <td>{{ $salle->libelle }}</td>
                                </tr>
                                <tr>
                                    <th>Nombre de cours</th>
                                    <td>{{ $cours->total() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h5 class="mb-3">Cours dans cette salle</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Heure début</th>
                                    <th>Heure fin</th>
                                    <th>Professeur</th>
                                    <th>Cours</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cours as $cour)
                                    <tr>
                                        <td>{{ $cour->date->format('d/m/Y') }}</td>
                                        <td>{{ $cour->heure_debut }}</td>
                                        <td>{{ $cour->heure_fin }}</td>
                                        <td>{{ $cour->professeur->nom }} {{ $cour->professeur->prenom }}</td>
                                        <td>{{ $cour->nom }}</td>
                                        <td>
                                            <span class="badge bg-{{ $cour->statut === 'en_cours' ? 'success' : ($cour->statut === 'termine' ? 'info' : 'warning') }}">
                                                {{ ucfirst($cour->statut) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucun cours trouvé dans cette salle</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $cours->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 