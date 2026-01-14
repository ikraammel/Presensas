@extends('modele')

@section('title', 'Étudiants de la classe - Enseignant')

@section('contents')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Étudiants de la classe : {{ $groupe->nom }}</h1>
        <a href="{{ route('enseignant.classes.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour aux classes
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Liste des Étudiants ({{ $groupe->etudiants->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($groupe->etudiants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>N° Étudiant</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>CNE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupe->etudiants as $etudiant)
                                        <tr>
                                            <td>{{ $etudiant->noet }}</td>
                                            <td>{{ $etudiant->nom }}</td>
                                            <td>{{ $etudiant->prenom }}</td>
                                            <td>{{ $etudiant->cne ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Aucun étudiant dans cette classe pour le moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
