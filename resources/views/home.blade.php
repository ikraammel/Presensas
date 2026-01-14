@extends('modele')

@section('title', 'Accueil - Présensas')

@section('redirect')
    @auth
        @switch($type = Auth::user()->type)
            @case('admin')
                <meta http-equiv="refresh" content="0; URL=http://localhost/admin" />
            @break
            @case('enseignant')
                <meta http-equiv="refresh" content="0; URL=http://localhost/enseignant" />
            @break
            @default
                <!-- Student stays here -->
            @break
        @endswitch
    @endauth
@endsection

@section('contents')
    @auth
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Espace Étudiant</h1>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Absences Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Mes Absences</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">4</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-exclamation-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cours Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Modules Inscrits</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">6</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-book fa-2x text-gray-300"></i>
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
                        <h6 class="m-0 font-weight-bold text-primary">Mon Emploi du Temps</h6>
                    </div>
                    <div class="card-body">
                        <p>Consultez votre emploi du temps et vos absences ici.</p>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    @guest
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 90vh;">
        <div class="row align-items-center">
            
            <!-- Hero Text -->
            <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start">
                <div class="brand-logo justify-content-lg-start mb-3">
                    <i class="bi bi-geo-alt-fill" style="font-size: 2.5rem;"></i>
                    <span class="brand-text" style="font-size: 2rem;">PRÉSENSAS</span>
                </div>
                <h1 class="display-4 fw-bold mb-4" style="color: var(--primary-color);">Gérez vos présences simplement.</h1>
                <p class="lead text-muted mb-5">
                    Une solution complète pour la gestion scolaire. Planning, présences, et suivi des étudiants en un seul endroit.
                </p>
                <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-lg-start">
                    <a href="{{ route('login') }}" class="btn btn-primary-custom btn-lg px-5">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-5">S'inscrire</a>
                </div>
            </div>

            <!-- Hero Image / Visual -->
            <div class="col-lg-6 text-center">
                <img src="{{ asset('Images/home_page.png') }}" alt="School Management" class="img-fluid rounded-3 shadow-lg" style="max-height: 400px; object-fit: cover;">
            </div>

        </div>
    </div>
    @endguest
@endsection
