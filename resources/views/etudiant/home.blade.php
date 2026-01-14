@extends('modele')

@section('title', 'Dashboard Étudiant')

@section('contents')

    <style>
        .stat-card {
            border: none;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .stat-card.card-welcome {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }

        .stat-icon {
            font-size: 3rem;
            opacity: 0.3;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .page-heading {
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 2rem;
        }
    </style>

    <!-- Page Heading -->
    <div class="mb-4">
        <h1 class="page-heading">
            <i class="bi bi-speedometer2 me-2" style="color: #2c3e50;"></i>
            Tableau de bord
        </h1>
    </div>

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card stat-card card-welcome h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-label">Bonjour</div>
                        <div class="stat-number">{{ $user->prenom }} {{ $user->nom }}</div>
                        <p>Bienvenue sur votre espace étudiant. Vous pouvez consulter vos cours et votre profil depuis le
                            menu.</p>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-mortarboard-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($etudiant) && $etudiant->groupe)
        <div class="row">
            <!-- Ma Classe Card -->
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card stat-card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Ma Classe</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $etudiant->groupe->nom }}</div>
                                <div class="text-muted small">{{ $etudiant->groupe->description }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people-fill fa-2x text-gray-300" style="font-size: 2rem; color: #dddfeb;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mon Professeur Card -->
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card stat-card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Professeur Principal</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $etudiant->groupe->user->nom ?? 'Non assigné' }}
                                    {{ $etudiant->groupe->user->prenom ?? '' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-person-badge-fill fa-2x text-gray-300"
                                    style="font-size: 2rem; color: #dddfeb;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            Vous n'êtes assigné à aucune classe pour le moment.
        </div>
    @endif

    <!-- Section Mes Cours -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mes Modules (Cours)</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $coursDirects = $etudiant->cours ?? collect();
                            $coursGroupe = $etudiant->groupe->cours ?? collect();
                            $coursProf = $etudiant->groupe && $etudiant->groupe->user ? $etudiant->groupe->user->cours : collect();
                            $tousLesCours = $coursDirects->merge($coursGroupe)->merge($coursProf)->unique('id');
                        @endphp

                        @forelse($tousLesCours as $cours)
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    {{ Str::limit($cours->intitule, 20) }}
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <a href="{{ route('etudiant.modules.show', $cours->id) }}"
                                                        class="text-decoration-none stretched-link">
                                                        Voir le cours
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="bi bi-book fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">Aucun cours assigné pour le moment.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection