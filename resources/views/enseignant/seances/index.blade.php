@extends('modele')

@section('title', 'Mes Séances')

@section('contents')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mes Séances</h1>
        <a href="{{ route('enseignant.seances.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Créer une séance
        </a>
    </div>

    @if (session('etat'))
        <div class="alert alert-success">
            {{ session('etat') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Séances</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th>Classe</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($seances as $seance)
                            <tr>
                                <td>{{ $seance->cours->intitule }}</td>
                                <td>{{ $seance->groupe->nom ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $seance->type_seance ?? 'Cours' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('enseignant.presences.index', $seance->id) }}" 
                                       class="btn btn-sm btn-success" title="Enregistrer présence">
                                        <i class="bi bi-check-circle"></i> Présence
                                    </a>
                                    <a href="{{ route('enseignant.seances.edit', $seance->id) }}" 
                                       class="btn btn-sm btn-primary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('enseignant.seances.delete', $seance->id) }}" 
                                          method="GET" 
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cette séance ?');">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucune séance créée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $seances->links() }}
        </div>
    </div>
@endsection
