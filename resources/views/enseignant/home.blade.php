@extends('modele')

@section('title', 'Accueil - Enseignant')

@section('contents')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Espace Enseignant</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Cours Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Mes Cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCours }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classes Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Mes Classes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalClasses }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Séances du jour Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Séances aujourd'hui</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $seancesAujourdhui }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-event fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seances à venir Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Séances à venir</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSeancesAvenir }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <!-- Classes assignées -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Mes Classes Assignées</h6>
                    @if($totalClasses > 0)
                        <a href="{{ route('enseignant.classes.index') }}" class="btn btn-sm btn-outline-primary">
                            Voir toutes
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($totalClasses > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom de la classe</th>
                                        <th>Étudiants</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupes as $groupe)
                                        <tr>
                                            <td>
                                                <strong>{{ $groupe->nom }}</strong>
                                                @if($groupe->description)
                                                    <br><small class="text-muted">{{ $groupe->description }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $groupe->etudiants->count() }} étudiant(s)</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('enseignant.classes.etudiants', $groupe->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Voir les étudiants">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune classe assignée pour le moment.</p>
                        <small class="text-muted">Les classes vous seront assignées par l'administrateur.</small>
                    @endif
                </div>
            </div>
        </div>

        <!-- Activité Récente -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Activité Récente</h6>
                </div>
                <div class="card-body">
                    <p>Bienvenue <strong>{{ $user->prenom }} {{ $user->nom }}</strong> dans votre espace enseignant.</p>
                    <h6>Séances à venir :</h6>
                    @forelse($seancesAvenir as $seance)
                        <p><strong>{{ $seance->cours->intitule }}</strong> - {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y H:i') }}</p>
                    @empty
                        <p class="text-muted">Aucune séance à venir.</p>
                    @endforelse
                    <div class="mt-3">
                        <a href="{{ route('enseignant.seances.index') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-calendar-event"></i> Mes séances
                        </a>
                        <a href="{{ route('enseignant.seances.create') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle"></i> Créer une séance
                        </a>
                        <a href="{{ route('enseignant.cours.show', $user->id) }}" class="btn btn-info btn-sm">Mes cours</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection