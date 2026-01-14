@extends('modele')

@section('title', 'Accueil - Présensas')

@section('redirect')
    @auth
        @switch($type = Auth::user()->type)
            @case('admin')
                <meta http-equiv="refresh" content="0; URL={{ url('/admin') }}" />
            @break
            @case('enseignant')
                <meta http-equiv="refresh" content="0; URL={{ url('/enseignant') }}" />
            @break
            @case('etudiant')
                <meta http-equiv="refresh" content="0; URL={{ url('/etudiant') }}" />
            @break
            @default
                <!-- User stays here -->
            @break
        @endswitch
    @endauth
@endsection

@section('contents')
    @auth
        @if(Auth::user()->type == 'etudiant')
            <!-- Redirection automatique vers le dashboard étudiant -->
            <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="text-muted">Redirection vers votre espace étudiant...</p>
                    <p class="small">
                        <a href="{{ route('etudiant.home') }}" class="text-primary">Cliquez ici si la redirection ne fonctionne pas</a>
                    </p>
                </div>
            </div>
        @else
            <!-- Page pour les autres types d'utilisateurs (si nécessaire) -->
            <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
                <div class="text-center">
                    <h1 class="h3 mb-4">Bienvenue {{ Auth::user()->prenom }} {{ Auth::user()->nom }}</h1>
                    <p class="text-muted">Redirection en cours...</p>
                </div>
            </div>
        @endif
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
