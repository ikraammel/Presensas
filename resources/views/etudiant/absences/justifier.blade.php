@extends('modele')

@section('title', 'Justifier une absence')

@section('contents')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Justifier l'absence</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Détails de l'absence</h5>
                        <p><strong>Cours :</strong> {{ $presence->seances->cours->intitule }}</p>
                        <p><strong>Date :</strong>
                            {{ \Carbon\Carbon::parse($presence->seances->date_debut)->format('d/m/Y H:i') }}</p>
                    </div>

                    <form action="{{ route('etudiant.absences.envoyer', $presence->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="justificatif" class="form-label">Document justificatif (PDF, Image)</label>
                            <input type="file" class="form-control @error('justificatif') is-invalid @enderror"
                                id="justificatif" name="justificatif" required>
                            <div class="form-text">Formats acceptés : PDF, JPG, PNG. Max 2Mo.</div>
                            @error('justificatif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="commentaire" class="form-label">Commentaire (Optionnel)</label>
                            <textarea class="form-control" id="commentaire" name="commentaire" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('etudiant.absences.liste') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection