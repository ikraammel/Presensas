@extends('modele')

@section('title', 'Mes Absences')

@section('contents')
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mes Absences et Justificatifs</h6>
                </div>
                <div class="card-body">
                    @if(count($presences) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Cours</th>
                                        <th>Type séance</th>
                                        <th>Justificatif</th>
                                        <th>Statut Just.</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presences as $presence)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($presence->seances->date_debut)->format('d/m/Y H:i') }}
                                            </td>
                                            <td>{{ $presence->seances->cours->intitule }}</td>
                                            <td>{{ $presence->seances->type_seance ?? 'Normale' }}</td>
                                            <td>
                                                @if($presence->justificatif)
                                                    <a href="{{ asset('storage/' . $presence->justificatif) }}" target="_blank"
                                                        class="btn btn-sm btn-info">
                                                        <i class="bi bi-file-earmark-text"></i> Voir
                                                    </a>
                                                @else
                                                    <span class="text-muted">Aucun</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($presence->statut_justificatif == 'valide')
                                                    <span class="badge bg-success">Validé</span>
                                                @elseif($presence->statut_justificatif == 'refuse')
                                                    <span class="badge bg-danger">Refusé</span>
                                                @elseif($presence->statut_justificatif == 'en_attente')
                                                    <span class="badge bg-warning text-dark">En attente</span>
                                                @else
                                                    <span class="badge bg-secondary">Non justifié</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$presence->justificatif || $presence->statut_justificatif == 'refuse')
                                                    <a href="{{ route('etudiant.absences.justifier', $presence->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="bi bi-upload"></i> Justifier
                                                    </a>
                                                @else
                                                    <span class="text-muted"><i class="bi bi-check-circle"></i> Soumis</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Vous n'avez aucune absence enregistrée.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection