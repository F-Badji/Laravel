@extends('layouts.app')

@section('title', 'Nouvel Émargement - ISI')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card animate__animated animate__fadeInUp">
                <div class="card-header bg-white">
                    <h3 class="card-title mb-0">Nouvel Émargement</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('emargements.store') }}" method="POST" id="emargementForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">Date et Heure</label>
                            <input type="datetime-local" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date') }}" 
                                   required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select class="form-select @error('statut') is-invalid @enderror" 
                                    id="statut" 
                                    name="statut" 
                                    required>
                                <option value="">Sélectionnez un statut</option>
                                <option value="present" {{ old('statut') == 'present' ? 'selected' : '' }}>Présent</option>
                                <option value="absent" {{ old('statut') == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="retard" {{ old('statut') == 'retard' ? 'selected' : '' }}>Retard</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
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

                        <div class="mb-3">
                            <label for="cours_id" class="form-label">Cours</label>
                            <select class="form-select @error('cours_id') is-invalid @enderror" 
                                    id="cours_id" 
                                    name="cours_id" 
                                    required>
                                <option value="">Sélectionnez un cours</option>
                                @foreach($cours as $coursItem)
                                    <option value="{{ $coursItem->id }}" {{ old('cours_id') == $coursItem->id ? 'selected' : '' }}>
                                        {{ $coursItem->nom }} - {{ $coursItem->heure_debut->format('H:i') }} - {{ $coursItem->heure_fin->format('H:i') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cours_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="conflitAlert" class="alert alert-danger d-none">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span id="conflitMessage"></span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('emargements.index') }}" class="btn btn-secondary">
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
    const form = document.getElementById('emargementForm');
    const conflitAlert = document.getElementById('conflitAlert');
    const conflitMessage = document.getElementById('conflitMessage');
    const submitBtn = document.getElementById('submitBtn');

    function checkConflits() {
        const formData = new FormData(form);
        
        fetch('{{ route("emargements.check-conflit") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.conflit) {
                conflitMessage.textContent = data.message;
                conflitAlert.classList.remove('d-none');
                submitBtn.disabled = true;
            } else {
                conflitAlert.classList.add('d-none');
                submitBtn.disabled = false;
            }
        });
    }

    // Vérifier les conflits lors du changement des champs
    ['date', 'professeur_id', 'cours_id'].forEach(field => {
        document.getElementById(field).addEventListener('change', checkConflits);
    });

    // Vérifier les conflits au chargement de la page
    checkConflits();
});
</script>
@endpush
@endsection 