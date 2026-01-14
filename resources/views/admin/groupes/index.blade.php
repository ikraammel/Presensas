@extends('modele')

@section('title', 'Gestion des Classes')

@section('contents')
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Liste des Classes</h6>
                    <a href="{{ route('admin.groupes.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Nouvelle Classe
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Professeur Principal</th>
                                    <th>Nombre d'étudiants</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupes as $groupe)
                                    <tr>
                                        <td>{{ $groupe->nom }}</td>
                                        <td>{{ $groupe->user->nom ?? 'Aucun' }} {{ $groupe->user->prenom ?? '' }}</td>
                                        <td>{{ $groupe->etudiants->count() }}</td>
                                        <td>
                                            <a href="{{ route('admin.groupes.show', $groupe->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> Détails
                                            </a>
                                            <form action="{{ route('admin.groupes.destroy', $groupe->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer cette classe ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
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