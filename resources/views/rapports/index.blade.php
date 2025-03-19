@extends('layouts.app')

@section('title', 'Rapports - ISI')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Rapports</h4>
                </div>

                <div class="card-body">
                    <!-- Formulaire de filtrage des présences -->
                    <form action="{{ route('admin.rapports.presences') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_debut">Date de début</label>
                                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_fin">Date de fin</label>
                                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block">Filtrer</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Boutons d'export -->
                    <div class="mb-4">
                        <a href="{{ route('admin.rapports.export-pdf') }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Exporter en PDF
                        </a>
                        <a href="{{ route('admin.rapports.export-excel') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Exporter en Excel
                        </a>
                        <a href="{{ route('admin.rapports.export-presences') }}" class="btn btn-info">
                            <i class="fas fa-download"></i> Exporter les présences
                        </a>
                    </div>

                    <!-- Tableau des statistiques -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Métrique</th>
                                    <th>Valeur</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total des cours</td>
                                    <td>{{ $stats['total_cours'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td>Total des émargements</td>
                                    <td>{{ $stats['total_emargements'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td>Taux de présence moyen</td>
                                    <td>{{ number_format($stats['taux_presence'] ?? 0, 2) }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 