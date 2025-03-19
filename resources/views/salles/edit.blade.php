@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Modifier la Salle</h4>
                    <a href="{{ route('salles.show', $salle) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('salles.update', $salle) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="libelle">Libellé de la salle</label>
                            <input type="text" class="form-control @error('libelle') is-invalid @enderror" 
                                   id="libelle" name="libelle" value="{{ old('libelle', $salle->libelle) }}" required>
                            @error('libelle')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 