@extends('modele')

@section('title', 'Mes Séances')

@section('contents')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mes Séances</h1>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h5>Classe : {{ $etudiant->groupe->nom ?? 'Non assigné' }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($seances as $seance)
            @php
                $presence = $seance->presence ?? null;
                $isEnCours = now() >= $seance->date_debut && now() <= $seance->date_fin;
                $isPasse = now() > $seance->date_fin;
                $isAVenir = now() < $seance->date_debut;
            @endphp
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow h-100 
                    @if($isEnCours) border-success 
                    @elseif($isPasse) border-secondary 
                    @else border-primary 
                    @endif">
                    <div class="card-header 
                        @if($isEnCours) bg-success text-white 
                        @elseif($isPasse) bg-secondary text-white 
                        @else bg-primary text-white 
                        @endif">
                        <h6 class="mb-0">
                            <i class="bi bi-calendar-event"></i> {{ $seance->cours->intitule }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Date :</strong> {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y') }}
                        </p>
                        <p class="mb-2">
                            <strong>Heure :</strong> 
                            {{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}
                        </p>
                        <p class="mb-2">
                            <strong>Type :</strong> 
                            <span class="badge bg-info">{{ $seance->type_seance ?? 'Cours' }}</span>
                        </p>
                        
                        @if($presence)
                            <p class="mb-2">
                                <strong>Statut :</strong>
                                @if($presence->statut == 'present')
                                    <span class="badge bg-success">Présent</span>
                                @elseif($presence->statut == 'absent')
                                    <span class="badge bg-danger">Absent</span>
                                @elseif($presence->statut == 'retard')
                                    <span class="badge bg-warning">Retard</span>
                                @endif
                            </p>
                        @else
                            <p class="mb-2">
                                <strong>Statut :</strong>
                                <span class="badge bg-secondary">Non enregistré</span>
                            </p>
                        @endif

                        @if($isEnCours)
                            <div class="alert alert-success mb-2">
                                <i class="bi bi-clock"></i> Séance en cours
                            </div>
                        @elseif($isPasse)
                            <div class="alert alert-secondary mb-2">
                                <i class="bi bi-check-circle"></i> Séance terminée
                            </div>
                        @else
                            <div class="alert alert-info mb-2">
                                <i class="bi bi-calendar-check"></i> Séance à venir
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('etudiant.seances.show', $seance->id) }}" 
                           class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-eye"></i> Voir détails
                            @if($isEnCours && !$presence)
                                <span class="badge bg-warning ms-2">Scanner QR</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Aucune séance programmée pour votre classe.
                </div>
            </div>
        @endforelse
    </div>
@endsection
