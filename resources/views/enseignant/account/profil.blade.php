@extends('modele')

@section('title', 'Mon Profil - Enseignant')

@section('contents')

    <style>
        .profile-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem;
        }

        .profile-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .profile-type {
            font-size: 0.95rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            display: inline-block;
            border-radius: 20px;
        }

        .profile-body {
            padding: 40px;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-group {
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 15px;
        }

        .info-label {
            font-size: 0.9rem;
            color: #7f8c8d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 1.1rem;
            color: #2c3e50;
            font-weight: 500;
        }

        .badge-type {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-enseignant {
            background-color: #d6eaf8;
            color: #0c5aa0;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #ecf0f1;
        }

        .btn-profile {
            padding: 12px 24px;
            border-radius: 6px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            color: white;
            text-decoration: none;
        }

        .btn-changepwd {
            background: #0c5aa0;
            color: white;
        }

        .btn-changepwd:hover {
            background: #0a4481;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            color: white;
            text-decoration: none;
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
            <i class="bi bi-person-circle me-2" style="color: #2c3e50;"></i>
            Mon Profil
        </h1>
    </div>

    @if(session()->has('etat'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session()->get('etat') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="profile-card">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div class="profile-name">{{ $user->nom }} {{ $user->prenom }}</div>
            <div class="profile-type">
                <i class="bi bi-book me-1"></i>Enseignant
            </div>
        </div>

        <!-- Profile Body -->
        <div class="profile-body">
            <!-- Profile Information -->
            <div class="profile-info">
                <div class="info-group">
                    <div class="info-label">Identifiant</div>
                    <div class="info-value">{{ $user->login }}</div>
                </div>

                <div class="info-group">
                    <div class="info-label">Nom</div>
                    <div class="info-value">{{ $user->nom }}</div>
                </div>

                <div class="info-group">
                    <div class="info-label">Prénom</div>
                    <div class="info-value">{{ $user->prenom }}</div>
                </div>

                <div class="info-group">
                    <div class="info-label">Type de Compte</div>
                    <div class="info-value">
                        <span class="badge-type badge-enseignant">Enseignant</span>
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label">Membre Depuis</div>
                    <div class="info-value">
                        {{ $user->created_at ? $user->created_at->format('d/m/Y à H:i') : 'Non disponible' }}
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label">Dernière Modification</div>
                    <div class="info-value">
                        {{ $user->updated_at ? $user->updated_at->format('d/m/Y à H:i') : 'Non disponible' }}
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('enseignant.account.editNomPrenom', $user->id) }}" class="btn-profile btn-edit">
                    <i class="bi bi-pencil me-2"></i>Modifier Mes Informations
                </a>
                <a href="{{ route('enseignant.account.edit') }}" class="btn-profile btn-changepwd">
                    <i class="bi bi-key me-2"></i>Changer Mon Mot de Passe
                </a>
            </div>
        </div>
    </div>

@endsection
