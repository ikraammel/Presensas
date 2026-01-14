@extends('modele')

@section('title', 'Validation des Justificatifs')

@section('contents')
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Justificatifs d'absences à valider</h6>
                </div>
                <div class="card-body">
                    @if(count($presences) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Cours</th>
                                        <th>Date Absence</th>
                                        <th>Justificatif</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presences as $presence)
                                        <tr>
                                            <td>{{ $presence->etudiants->nom }} {{ $presence->etudiants->prenom }}</td>
                                            <td>{{ $presence->seances->cours->intitule }}</td>
                                            <td>{{ \Carbon\Carbon::parse($presence->seances->date_debut)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ asset('storage/' . $presence->justificatif) }}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Voir le document
                                                </a>
                                            </td>
                                            <td>
                                                @if($presence->statut_justificatif == 'valide')
                                                    <span class="badge bg-success">Validé</span>
                                                @elseif($presence->statut_justificatif == 'refuse')
                                                    <span class="badge bg-danger">Refusé</span>
                                                @elseif($presence->statut_justificatif == 'en_attente')
                                                    <span class="badge bg-warning text-dark">En attente</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $presence->statut_justificatif }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($presence->statut_justificatif == 'en_attente')
                                                    <form action="{{ route('enseignant.absences.valider', $presence->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" title="Valider">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('enseignant.absences.refuser', $presence->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Refuser">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">Traité</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Aucun justificatif en attente pour le moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
