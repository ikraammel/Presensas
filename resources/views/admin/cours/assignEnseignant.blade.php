@extends('modele')

@section('title', 'Affecter un enseignant au module')

@section('contents')
    <div class="container-sm mt-4">
        <!-- Bouton retour -->
        <a class="btn btn-secondary mb-3" href="{{ route('admin.cours.index') }}">
            <i class="bi bi-arrow-left-circle-fill"></i> Retour à la liste des modules
        </a>

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Affecter un enseignant au module</h5>
            </div>
            <div class="card-body">
                <p><strong>Module :</strong> {{ $cours->intitule }}</p>

                <form method="POST" action="{{ route('admin.cours.assign-enseignant', ['id' => $cours->id]) }}">
                    @csrf

                    <div class="mb-3">
                        <label for="user_id" class="form-label">Enseignant</label>
                        <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner un enseignant --</option>
                            @foreach($enseignants as $enseignant)
                                <option value="{{ $enseignant->id }}"
                                    {{ isset($enseignantActuelId) && $enseignantActuelId == $enseignant->id ? 'selected' : '' }}>
                                    {{ $enseignant->nom }} {{ $enseignant->prenom }} ({{ $enseignant->login }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Enregistrer l'affectation
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

