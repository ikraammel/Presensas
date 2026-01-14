@extends('modele')

@section('title', 'Mes Classes - Enseignant')

@section('contents')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mes Classes Assignées</h1>
        <a href="{{ route('enseignant.home') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour au dashboard
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Liste des Classes ({{ $totalClasses }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($totalClasses > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nom de la classe</th>
                                        <th>Description</th>
                                        <th>Nombre d'étudiants</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupes as $groupe)
                                        <tr>
                                            <td>
                                                <strong>{{ $groupe->nom }}</strong>
                                            </td>
                                            <td>
                                                {{ $groupe->description ?? 'Aucune description' }}
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $groupe->etudiants->count() }} étudiant(s)</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('enseignant.classes.etudiants', $groupe->id) }}" 
                                                   class="btn btn-sm btn-primary" title="Voir les étudiants">
                                                    <i class="bi bi-people"></i> Voir les étudiants
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Aucune classe assignée pour le moment.
                            <br>
                            <small>Les classes vous seront assignées par l'administrateur.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
