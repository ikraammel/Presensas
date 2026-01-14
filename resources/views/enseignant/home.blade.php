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
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Activité Récente</h6>
                </div>
                <div class="card-body">
                    <p>Bienvenue {{ $user->prenom }} {{ $user->nom }} dans votre espace enseignant.</p>
                    <h6>Séances à venir :</h6>
                    @forelse($seancesAvenir as $seance)
                        <p><strong>{{ $seance->cours->intitule }}</strong> - {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y H:i') }}</p>
                    @empty
                        <p>Aucune séance à venir.</p>
                    @endforelse
                    <div class="mt-3">
                        <a href="{{ route('enseignant.showSeance') }}" class="btn btn-primary btn-sm">Voir toutes les séances</a>
                        <a href="{{ route('enseignant.cours.show', $user->id) }}" class="btn btn-info btn-sm">Mes cours</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection