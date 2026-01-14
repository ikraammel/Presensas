@extends('modele')

@section('title', 'HOME -ADMIN')

@section('contents')

    @include('admin.partials.navbar-admin')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tableau de bord - Administrateur</h1>
        <div>
            @if(isset($usersNonValides) && $usersNonValides > 0)
                <a href="{{route('admin.users.index')}}" class="d-inline-block btn btn-sm btn-warning shadow-sm mr-2">
                    <i class="bi bi-exclamation-triangle fa-sm text-white-50"></i> {{$usersNonValides}} Utilisateur(s) à valider
                </a>
            @endif
            <a href="{{route('admin.users.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mr-2">
                <i class="bi bi-people fa-sm text-white-50"></i> Gérer les Utilisateurs
            </a>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="bi bi-download fa-sm text-white-50"></i> Générer Rapport
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Total Students Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Étudiants</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">450</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Modules</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-book fa-2x text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seances Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Séances Programmées
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">25</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-event fa-2x text-gray-300" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Taux de Présence</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">88.6%</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-gray-300" style="font-size: 2rem;"></i>
                        </div>
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
                    <h6 class="m-0 font-weight-bold text-primary">Aperçu des Absences</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div
                        style="height: 300px; display: flex; align-items: center; justify-content: center; background: #f8f9fc; border-radius: 5px;">
                        <p class="text-muted">Graphique des absences (Simulation)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Statut des Absences</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div
                        style="height: 300px; display: flex; align-items: center; justify-content: center; background: #f8f9fc; border-radius: 5px;">
                        <p class="text-muted">Diagramme</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection