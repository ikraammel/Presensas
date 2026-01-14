@extends('modele')

@section('title', 'Détails du Module ' . $cours->intitule)

@section('contents')
    <div class="row">
        <!-- Informations du cours -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du Module</h6>
                </div>
                <div class="card-body">
                    <h4>{{ $cours->intitule }}</h4>
                    <p><strong>Description :</strong> {{ $cours->description ?? 'Aucune description' }}</p>
                    <a href="{{ route('etudiant.home') }}" class="btn btn-secondary btn-sm">Retour au tableau de bord</a>
                </div>
            </div>
        </div>

        <!-- Section Documents -->
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Documents de cours</h6>
                </div>
                <div class="card-body">
                    @if($cours->documents->isEmpty())
                        <div class="alert alert-info">Aucun document disponible pour ce cours.</div>
                    @else
                        <div class="list-group">
                            @foreach($cours->documents as $doc)
                                <div
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $doc->titre }}</h5>
                                        </div>
                                        <p class="mb-1 text-muted small">{{ $doc->description }}</p>
                                        <small>{{ strtoupper($doc->type_fichier) }} - Mis en ligne le
                                            {{ $doc->created_at->format('d/m/Y') }} par
                                            {{ $doc->user->nom ?? 'Enseignant' }}</small>
                                    </div>
                                    <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-download"></i> Télécharger
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection