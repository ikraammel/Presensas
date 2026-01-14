@extends('modele')

@section('title', 'Dashboard Gestionnaire')

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

        .stat-card.card-primary {
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

    <div class="row mb-4">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card stat-card card-primary h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="stat-label">Bienvenue</div>
                        <div class="stat-number">Gestionnaire</div>
                        <p>Bienvenue sur votre espace de gestion.</p>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-gear-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection