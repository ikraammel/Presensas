@extends('modele')

@section('title', 'Détails de la Classe')

@section('contents')
    <div class="row">
        <!-- Informations de la classe -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations</h6>
                </div>
                <div class="card-body">
                    <h4>{{ $groupe->nom }}</h4>
                    <p><strong>Professeur Principal :</strong> <br>
                        {{ $groupe->user->nom ?? '' }} {{ $groupe->user->prenom ?? '' }}
                    </p>
                    <p><strong>Description :</strong> <br>
                        {{ $groupe->description ?? 'Aucune description' }}
                    </p>
                    <a href="{{ route('admin.groupes.index') }}" class="btn btn-secondary btn-sm">Retour à la liste</a>
                </div>
            </div>

            <!-- Ajouter un étudiant -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Ajouter un étudiant</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.groupes.addEtudiant', $groupe->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="etudiant_id" class="form-label">Sélectionner un étudiant</label>
                            <select class="form-control" id="etudiant_id" name="etudiant_id" required>
                                <option value="">Choisir...</option>
                                @foreach($etudiantsSansGroupe as $etudiant)
                                    <option value="{{ $etudiant->id }}">
                                        {{ $etudiant->nom }} {{ $etudiant->prenom }} ({{ $etudiant->noet }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Seuls les étudiants sans classe sont listés.</div>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des étudiants -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Étudiants de la classe ({{ $groupe->etudiants->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>N° Étudiant</th>
                                    <th>Nom Prénom</th>
                                    <th>CNE</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupe->etudiants as $etudiant)
                                    <tr>
                                        <td>{{ $etudiant->noet }}</td>
                                        <td>{{ $etudiant->nom }} {{ $etudiant->prenom }}</td>
                                        <td>{{ $etudiant->cne }}</td>
                                        <td>
                                            <form
                                                action="{{ route('admin.groupes.removeEtudiant', ['id' => $groupe->id, 'etudiant_id' => $etudiant->id]) }}"
                                                method="POST" onsubmit="return confirm('Retirer cet étudiant de la classe ?');">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-x-lg"></i> Retirer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection