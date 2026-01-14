@extends('modele')

@section('title', 'Mes Modules - Enseignant')

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

        .badge-module {
            background-color: #d6eaf8;
            color: #0c5aa0;
            padding: 8px 14px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }

        .page-title {
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 700;
        }

        .card-enseignant {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
        }
    </style>

    <!-- Page Heading -->
    <div class="mb-4">
        <h1 class="page-title">
            <i class="bi bi-book-fill me-2" style="color: #2c3e50;"></i>
            Mes Modules/Cours
        </h1>
        <p class="text-muted">Total: <strong>{{ $totalModules }}</strong> module(s)</p>
    </div>

    @if(session()->has('etat'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session()->get('etat') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-enseignant">
        <div class="card-body">
            @if($modules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-enseignant">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Intitulé du Module</th>
                                <th>Nombre de Séances</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modules as $module)
                                <tr>
                                    <td><strong>#{{ $module->id }}</strong></td>
                                    <td><span class="badge-module">{{ $module->intitule }}</span></td>
                                    <td>
                                        <span class="badge bg-info">{{ $module->seances->count() }} séance(s)</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('enseignant.modules.show', $module->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye me-1"></i>Voir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $modules->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Aucun module</strong> - Vous n'avez pas de module assigné pour le moment.
                </div>
            @endif
        </div>
    </div>

@endsection