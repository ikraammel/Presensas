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
                    <a href="{{ route('enseignant.modules.index') }}" class="btn btn-secondary btn-sm">Retour aux
                        modules</a>
                </div>
            </div>
        </div>

        <!-- Section Documents -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Documents partagés</h6>
                </div>
                <div class="card-body">
                    @if($cours->documents->isEmpty())
                        <div class="alert alert-info">Aucun document partagé pour ce module.</div>
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
                                        <small>{{ strtoupper($doc->type_fichier) }} - Ajouté le
                                            {{ $doc->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('enseignant.documents.download', $doc->id) }}"
                                            class="btn btn-sm btn-primary" title="Télécharger">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <form action="{{ route('enseignant.documents.destroy', $doc->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Supprimer ce document ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Formulaire d'upload -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Ajouter un document</h6>
                </div>
                <div class="card-body">
                    <!-- Affichage des erreurs -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Message de succès -->
                    @if (session('etat'))
                        <div class="alert alert-success">
                            {{ session('etat') }}
                        </div>
                    @endif

                    <form action="{{ route('enseignant.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="cours_id" value="{{ $cours->id }}">

                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('titre') is-invalid @enderror" 
                                   id="titre" 
                                   name="titre" 
                                   value="{{ old('titre') }}"
                                   required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optionnel)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="2">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fichier" class="form-label">Fichier <span class="text-danger">*</span></label>
                            <input type="file" 
                                   class="form-control @error('fichier') is-invalid @enderror" 
                                   id="fichier" 
                                   name="fichier" 
                                   required>
                            @error('fichier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">PDF, Word, PPT, Excel, Images, Archive (Max 10Mo)</div>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                            <i class="bi bi-upload"></i> Partager le document
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection