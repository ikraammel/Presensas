@extends('modele')

@section('title', 'Modifier une Séance')

@section('contents')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modifier la Séance</h1>
        <a href="{{ route('enseignant.seances.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Modifier la Séance</h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('enseignant.seances.update', $seance->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="cours_id" class="form-label">Module <span class="text-danger">*</span></label>
                            <select class="form-select @error('cours_id') is-invalid @enderror" 
                                    id="cours_id" 
                                    name="cours_id" 
                                    required>
                                <option value="">-- Choisir un module --</option>
                                @foreach($cours as $c)
                                    <option value="{{ $c->id }}" 
                                        {{ (old('cours_id', $seance->cours_id) == $c->id) ? 'selected' : '' }}>
                                        {{ $c->intitule }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cours_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="groupe_id" class="form-label">Classe <span class="text-danger">*</span></label>
                            <select class="form-select @error('groupe_id') is-invalid @enderror" 
                                    id="groupe_id" 
                                    name="groupe_id" 
                                    required>
                                <option value="">-- Choisir une classe --</option>
                                @foreach($groupes as $groupe)
                                    <option value="{{ $groupe->id }}" 
                                        {{ (old('groupe_id', $seance->groupe_id) == $groupe->id) ? 'selected' : '' }}>
                                        {{ $groupe->nom }} ({{ $groupe->etudiants->count() }} étudiants)
                                    </option>
                                @endforeach
                            </select>
                            @error('groupe_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label">Date et Heure de début <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                       class="form-control @error('date_debut') is-invalid @enderror" 
                                       id="date_debut" 
                                       name="date_debut" 
                                       value="{{ old('date_debut', \Carbon\Carbon::parse($seance->date_debut)->format('Y-m-d\TH:i')) }}"
                                       required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_fin" class="form-label">Date et Heure de fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                       class="form-control @error('date_fin') is-invalid @enderror" 
                                       id="date_fin" 
                                       name="date_fin" 
                                       value="{{ old('date_fin', \Carbon\Carbon::parse($seance->date_fin)->format('Y-m-d\TH:i')) }}"
                                       required>
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="type_seance" class="form-label">Type de séance <span class="text-danger">*</span></label>
                            <select class="form-select @error('type_seance') is-invalid @enderror" 
                                    id="type_seance" 
                                    name="type_seance" 
                                    required>
                                <option value="">-- Choisir un type --</option>
                                <option value="cours" {{ (old('type_seance', $seance->type_seance) == 'cours') ? 'selected' : '' }}>Cours</option>
                                <option value="TD" {{ (old('type_seance', $seance->type_seance) == 'TD') ? 'selected' : '' }}>TD</option>
                                <option value="TP" {{ (old('type_seance', $seance->type_seance) == 'TP') ? 'selected' : '' }}>TP</option>
                            </select>
                            @error('type_seance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('enseignant.seances.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
