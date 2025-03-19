@extends('layouts.app')

@section('title', 'Nouveau Cours - ISI')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card animate__animated animate__fadeInUp">
                <div class="card-header bg-white">
                    <h3 class="card-title mb-0">Nouveau Cours</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('cours.store') }}" method="POST" id="coursForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du cours</label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}" 
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="heure_debut" class="form-label">Heure de début</label>
                                <input type="datetime-local" 
                                       class="form-control @error('heure_debut') is-invalid @enderror" 
                                       id="heure_debut" 
                                       name="heure_debut" 
                                       value="{{ old('heure_debut') }}" 
                                       required>
                                @error('heure_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="heure_fin" class="form-label">Heure de fin</label>
                                <input type="datetime-local" 
                                       class="form-control @error('heure_fin') is-invalid @enderror" 
                                       id="heure_fin" 
                                       name="heure_fin" 
                                       value="{{ old('heure_fin') }}" 
                                       required>
                                @error('heure_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="salle_id" class="form-label">Salle</label>
                                <select class="form-select @error('salle_id') is-invalid @enderror" 
                                        id="salle_id" 
                                        name="salle_id" 
                                        required>
                                    <option value="">Sélectionnez une salle</option>
                                    @foreach($salles as $salle)
                                        <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                                            {{ $salle->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('salle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="professeur_id" class="form-label">Professeur</label>
                                <select class="form-select @error('professeur_id') is-invalid @enderror" 
                                        id="professeur_id" 
                                        name="professeur_id" 
                                        required>
                                    <option value="">Sélectionnez un professeur</option>
                                    @foreach($professeurs as $professeur)
                                        <option value="{{ $professeur->id }}" {{ old('professeur_id') == $professeur->id ? 'selected' : '' }}>
                                            {{ $professeur->nom }} {{ $professeur->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('professeur_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="conflitAlert" class="alert alert-danger d-none">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span id="conflitMessage"></span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('cours.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('coursForm');
    const conflitAlert = document.getElementById('conflitAlert');
    const conflitMessage = document.getElementById('conflitMessage');
    const submitBtn = document.getElementById('submitBtn');

    function checkConflits() {
        const formData = new FormData(form);
        
        fetch('{{ route("cours.check-conflit") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.conflitHoraire || data.conflitSalle) {
                let message = '';
                if (data.conflitHoraire) {
                    message += 'Conflit d\'horaire avec un autre cours pour ce professeur. ';
                }
                if (data.conflitSalle) {
                    message += 'Cette salle est déjà occupée pendant ce créneau horaire.';
                }
                conflitMessage.textContent = message;
                conflitAlert.classList.remove('d-none');
                submitBtn.disabled = true;
            } else {
                conflitAlert.classList.add('d-none');
                submitBtn.disabled = false;
            }
        });
    }

    // Vérifier les conflits lors du changement des champs
    ['heure_debut', 'heure_fin', 'salle_id', 'professeur_id'].forEach(field => {
        document.getElementById(field).addEventListener('change', checkConflits);
    });

    // Vérifier les conflits au chargement de la page
    checkConflits();
});
</script>
@endpush
@endsection 