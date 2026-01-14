@extends('modele')

@section('title', 'Enregistrement de Présence - ' . $seance->cours->intitule)

@section('contents')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Enregistrement de Présence</h1>
        <a href="{{ route('enseignant.showSeance') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour aux séances
        </a>
    </div>

    <!-- Informations de la séance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h5>{{ $seance->cours->intitule }}</h5>
                    <p class="mb-1">
                        <strong>Date :</strong> {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y') }}
                    </p>
                    <p class="mb-1">
                        <strong>Heure :</strong> {{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}
                    </p>
                    <p class="mb-0">
                        <strong>Type :</strong> {{ $seance->type_seance ?? 'Cours' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="text-center">
                        <div class="h3 mb-0 text-success">{{ $stats['present'] }}</div>
                        <div class="text-muted">Présents</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-danger shadow">
                <div class="card-body">
                    <div class="text-center">
                        <div class="h3 mb-0 text-danger">{{ $stats['absent'] }}</div>
                        <div class="text-muted">Absents</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-warning shadow">
                <div class="card-body">
                    <div class="text-center">
                        <div class="h3 mb-0 text-warning">{{ $stats['retard'] }}</div>
                        <div class="text-muted">Retards</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Onglets -->
    <ul class="nav nav-tabs" id="presenceTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="manuel-tab" data-bs-toggle="tab" data-bs-target="#manuel" type="button" role="tab">
                <i class="bi bi-pencil-square"></i> Méthode Manuelle
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="qrcode-tab" data-bs-toggle="tab" data-bs-target="#qrcode" type="button" role="tab">
                <i class="bi bi-qr-code"></i> QR Code
            </button>
        </li>
    </ul>

    <div class="tab-content" id="presenceTabsContent">
        <!-- Onglet Méthode Manuelle -->
        <div class="tab-pane fade show active" id="manuel" role="tabpanel">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Enregistrement Manuel</h6>
                </div>
                <div class="card-body">
                    @if (session('etat'))
                        <div class="alert alert-success">
                            {{ session('etat') }}
                        </div>
                    @endif

                    <form action="{{ route('enseignant.presences.store', $seance->id) }}" method="POST" id="formPresenceManuelle">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° Étudiant</th>
                                        <th>Nom Prénom</th>
                                        <th>Présent</th>
                                        <th>Absent</th>
                                        <th>Retard</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etudiants as $etudiant)
                                        @php
                                            $presence = $presencesExistantes->get($etudiant->id);
                                            $statutActuel = $presence ? $presence->statut : null;
                                        @endphp
                                        <tr>
                                            <td>{{ $etudiant->noet }}</td>
                                            <td>{{ $etudiant->nom }} {{ $etudiant->prenom }}</td>
                                            <td>
                                                <input type="radio" 
                                                       name="presences[{{ $etudiant->id }}][statut]" 
                                                       value="present" 
                                                       {{ $statutActuel == 'present' ? 'checked' : '' }}
                                                       required>
                                                <input type="hidden" name="presences[{{ $etudiant->id }}][etudiant_id]" value="{{ $etudiant->id }}">
                                            </td>
                                            <td>
                                                <input type="radio" 
                                                       name="presences[{{ $etudiant->id }}][statut]" 
                                                       value="absent"
                                                       {{ $statutActuel == 'absent' ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="radio" 
                                                       name="presences[{{ $etudiant->id }}][statut]" 
                                                       value="retard"
                                                       {{ $statutActuel == 'retard' ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Enregistrer les présences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Onglet QR Code -->
        <div class="tab-pane fade" id="qrcode" role="tabpanel">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">QR Code de Présence</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- QR Code -->
                        <div class="col-md-6 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-3">Scannez ce code pour marquer votre présence</h6>
                                    <div class="mb-3">
                                        @php
                                            $qrUrl = route('scan.qr') . '?token=' . $seance->qr_token;
                                        @endphp
                                        {!! QrCode::size(300)->generate($qrUrl) !!}
                                    </div>
                                    <p class="text-muted small">
                                        Valide jusqu'à {{ \Carbon\Carbon::parse($seance->date_fin)->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="text-info small">
                                        URL: {{ route('scan.qr') }}?token={{ $seance->qr_token }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Liste des présences en temps réel -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0">Présences enregistrées (Temps réel)</h6>
                                </div>
                                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                    <div id="presences-list">
                                        <p class="text-muted">Chargement...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh pour les présences QR Code
        let refreshInterval;
        
        document.getElementById('qrcode-tab').addEventListener('shown.bs.tab', function() {
            loadPresences();
            refreshInterval = setInterval(loadPresences, 3000); // Refresh toutes les 3 secondes
        });
        
        document.getElementById('qrcode-tab').addEventListener('hidden.bs.tab', function() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        });

        function loadPresences() {
            fetch('{{ route("enseignant.presences.realtime", $seance->id) }}')
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    if (data.presences.length === 0) {
                        html = '<p class="text-muted">Aucune présence enregistrée pour le moment.</p>';
                    } else {
                        data.presences.forEach(function(presence) {
                            let badgeClass = presence.statut === 'present' ? 'success' : 
                                            presence.statut === 'absent' ? 'danger' : 'warning';
                            html += `
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                    <div>
                                        <strong>${presence.etudiant}</strong><br>
                                        <small class="text-muted">${presence.noet}</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-${badgeClass}">${presence.statut}</span><br>
                                        <small class="text-muted">${presence.date}</small>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    document.getElementById('presences-list').innerHTML = html;
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        }
    </script>
@endsection
