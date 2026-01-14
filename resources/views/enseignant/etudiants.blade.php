@extends('modele')

@section('title', 'Étudiants - Enseignant')

@section('contents')

    <style>
        .table-enseignant {
            border-collapse: collapse;
        }
        
        .table-enseignant thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
        }
        
        .table-enseignant thead th {
            padding: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
        }
        
        .table-enseignant tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #ecf0f1;
            vertical-align: middle;
        }
        
        .table-enseignant tbody tr:hover {
            background-color: #ecf0f1;
            transition: all 0.3s ease;
        }
        
        .table-enseignant tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .badge-etudiant {
            background-color: #d6eaf8;
            color: #0c5aa0;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-groupe {
            background-color: #d5f4e6;
            color: #0b5345;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .page-title {
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 700;
        }
        
        .card-enseignant {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 8px;
        }
    </style>

    <!-- Page Heading -->
    <div class="mb-4">
        <h1 class="page-title">
            <i class="bi bi-people-fill me-2" style="color: #2c3e50;"></i>
            Mes Étudiants
        </h1>
        <p class="text-muted">Total: <strong>{{ $totalEtudiants }}</strong> étudiant(s)</p>
    </div>

    @if(session()->has('etat'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session()->get('etat') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-enseignant">
        <div class="card-body">
            @if($etudiants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-enseignant">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>N° Étudiant</th>
                                <th>CNE</th>
                                <th>Groupe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($etudiants as $etudiant)
                                <tr>
                                    <td><strong>#{{ $etudiant->id }}</strong></td>
                                    <td>{{ $etudiant->nom }}</td>
                                    <td>{{ $etudiant->prenom }}</td>
                                    <td><span class="badge-etudiant">{{ $etudiant->noet }}</span></td>
                                    <td><span class="badge-etudiant">{{ $etudiant->cne }}</span></td>
                                    <td>
                                        @if($etudiant->groupe)
                                            <span class="badge-groupe">{{ $etudiant->groupe->nom }}</span>
                                        @else
                                            <span class="badge bg-secondary">Non assigné</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $etudiants->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Aucun étudiant</strong> - Vous n'avez pas d'étudiant dans vos groupes pour le moment.
                </div>
            @endif
        </div>
    </div>

@endsection
