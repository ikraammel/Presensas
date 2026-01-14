@extends('modele')

@section('title', 'Dashboard Admin')

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
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        
        .stat-card.card-students {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }
        
        .stat-card.card-modules {
            background: linear-gradient(135deg, #0c5aa0 0%, #1e88e5 100%);
            color: white;
        }
        
        .stat-card.card-seances {
            background: linear-gradient(135deg, #0b5345 0%, #16a085 100%);
            color: white;
        }
        
        .stat-card.card-attendance {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
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

    <!-- Action Buttons -->
    <div class="mb-4">
        @if(isset($usersNonValides) && $usersNonValides > 0)
            <a href="{{route('admin.users.index')}}" class="btn btn-warning shadow-sm me-2">
                <i class="bi bi-exclamation-triangle me-2"></i> {{$usersNonValides}} Utilisateur(s) à valider
            </a>
        @endif
        <a href="{{route('admin.users.index')}}" class="btn btn-info shadow-sm me-2">
            <i class="bi bi-people me-2"></i> Gérer les Utilisateurs
        </a>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">

        <!-- Total Students -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card card-students h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-label">Total Étudiants</div>
                        <div class="stat-number">{{ $totalEtudiants }}</div>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-people-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card card-modules h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-label">Modules</div>
                        <div class="stat-number">{{ $totalCours }}</div>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-book-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seances -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card card-seances h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-label">Séances</div>
                        <div class="stat-number">{{ $totalSeances }}</div>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-calendar-event-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card card-attendance h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-label">Taux Présence</div>
                        <div class="stat-number">{{ $tauxPresence }}%</div>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-graph-up stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Dernières Séances</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    @if($dernieresSeances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Cours</th>
                                        <th>Date Début</th>
                                        <th>Date Fin</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dernieresSeances as $seance)
                                        <tr>
                                            <td>{{ $seance->cours_id }}</td>
                                            <td>{{ $seance->date_debut }}</td>
                                            <td>{{ $seance->date_fin }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $seance->type_seance ?? 'Non défini' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucune séance enregistrée.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Statistiques Utilisateurs</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="mb-4">
                        <p><strong>Total Utilisateurs:</strong> {{ $totalUsers }}</p>
                        <p><strong>Administrateurs:</strong> {{ $totalAdmins }}</p>
                        <p><strong>Enseignants:</strong> {{ $totalEnseignants }}</p>
                        <p><strong>En attente de validation:</strong> 
                            <span class="badge badge-warning">{{ $usersNonValides }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($utilisateursEnAttente->count() > 0)
        <!-- Utilisateurs en attente -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Utilisateurs en attente de validation</h6>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">Voir tous</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Login</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($utilisateursEnAttente as $user)
                                        <tr>
                                            <td><strong>{{ $user->login }}</strong></td>
                                            <td>{{ $user->nom }}</td>
                                            <td>{{ $user->prenom }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-check-circle me-1"></i>Valider
                                                </a>
                                                <a href="{{ route('admin.users.delete', $user->id) }}" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Refuser
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection