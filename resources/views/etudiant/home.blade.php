@extends('modele')

@section('title', 'Espace Étudiant - Présensas')

@section('contents')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Espace Étudiant</h1>
</div>

<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Bienvenue</h6>
            </div>
            <div class="card-body">
                <p>Bienvenue {{ $user->prenom }} {{ $user->nom }} dans votre espace étudiant.</p>
                <p>Cette section sera complétée avec vos cours, absences, documents et annonces.</p>
            </div>
        </div>
    </div>
</div>

@endsection
