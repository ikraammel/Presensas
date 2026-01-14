@extends('modele')

@section('title', 'Enseignants - Admin')

@section('contents')

    <style>
        .table-admin {
            border-collapse: collapse;
        }
        
        .table-admin thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }
        
        .table-admin thead th {
            padding: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
        }
        
        .table-admin tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #ecf0f1;
            vertical-align: middle;
        }
        
        .table-admin tbody tr:hover {
            background-color: #ecf0f1;
            transition: all 0.3s ease;
        }
        
        .table-admin tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .badge-login {
            background-color: #d6eaf8;
            color: #0c5aa0;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .badge-valid {
            background-color: #d5f4e6;
            color: #0b5345;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .badge-pending {
            background-color: #fdebd0;
            color: #7d3c02;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .page-title {
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 700;
        }
        
        .card-admin {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 8px;
        }
        
        .card-header-admin {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            border-radius: 8px 8px 0 0;
            padding: 20px;
            border: none;
        }
        
        .btn-action {
            padding: 6px 10px;
            font-size: 0.85rem;
            border-radius: 4px;
        }
    </style>

    <!-- Page Heading -->
    <div class="mb-4">
        <h1 class="page-title">
            <i class="bi bi-person-badge-fill me-2" style="color: #2c3e50;"></i>
            Liste des Enseignants
        </h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card card-admin">
                <div class="card-header card-header-admin d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">
                        <i class="bi bi-list me-2"></i>Tous les Enseignants ({{ $totalEnseignants }} total)
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($enseignants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-admin mb-0">
                                <thead>
                                    <tr>
                                        <th width="8%">#</th>
                                        <th width="20%">Nom</th>
                                        <th width="20%">Prénom</th>
                                        <th width="20%">Login</th>
                                        <th width="15%">Statut</th>
                                        <th width="17%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enseignants as $enseignant)
                                        <tr>
                                            <td><strong>{{ $enseignant->id }}</strong></td>
                                            <td>{{ $enseignant->nom }}</td>
                                            <td>{{ $enseignant->prenom }}</td>
                                            <td><span class="badge-login">{{ $enseignant->login }}</span></td>
                                            <td>
                                                @if($enseignant->type == 'enseignant')
                                                    <span class="badge-valid">✓ Validé</span>
                                                @else
                                                    <span class="badge-pending">⏳ En attente</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.edit', $enseignant->id) }}" class="btn btn-sm btn-info btn-action">
                                                    <i class="bi bi-pencil-square"></i> Éditer
                                                </a>
                                                <a href="{{ route('admin.users.delete', $enseignant->id) }}" class="btn btn-sm btn-danger btn-action">
                                                    <i class="bi bi-trash"></i> Supprimer
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info m-4">
                            <i class="bi bi-info-circle me-2"></i> Aucun enseignant enregistré.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
