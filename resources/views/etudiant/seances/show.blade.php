@extends('modele')

@section('title', 'Séance - ' . $seance->cours->intitule)

@section('contents')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Détails de la Séance</h1>
        <a href="{{ route('etudiant.seances.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour aux séances
        </a>
    </div>

    <div class="row">
        <!-- Informations de la séance -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Informations</h6>
                </div>
                <div class="card-body">
                    <h5>{{ $seance->cours->intitule }}</h5>
                    <p class="mb-2">
                        <strong>Classe :</strong> {{ $seance->groupe->nom ?? 'N/A' }}
                    </p>
                    <p class="mb-2">
                        <strong>Date :</strong> {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y') }}
                    </p>
                    <p class="mb-2">
                        <strong>Heure :</strong> 
                        {{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}
                    </p>
                    <p class="mb-2">
                        <strong>Type :</strong> 
                        <span class="badge bg-info">{{ $seance->type_seance ?? 'Cours' }}</span>
                    </p>
                    
                    @if($presence)
                        <div class="alert alert-info mt-3">
                            <strong>Votre présence :</strong>
                            @if($presence->statut == 'present')
                                <span class="badge bg-success">Présent</span>
                            @elseif($presence->statut == 'absent')
                                <span class="badge bg-danger">Absent</span>
                            @elseif($presence->statut == 'retard')
                                <span class="badge bg-warning">Retard</span>
                            @endif
                            <br>
                            <small>Enregistré le : {{ \Carbon\Carbon::parse($presence->date_enregistrement)->format('d/m/Y H:i') }}</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- QR Code pour scanner -->
        <div class="col-lg-6 mb-4">
            @php
                $isEnCours = now() >= $seance->date_debut && now() <= $seance->date_fin;
            @endphp
            
            @if($isEnCours && $seance->qr_token && !$presence)
                <div class="card shadow border-success">
                    <div class="card-header bg-success text-white text-center">
                        <h6 class="m-0 font-weight-bold">
                            <i class="bi bi-qr-code-scan"></i> Scanner le QR Code
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted mb-3">
                            Scannez ce code pour marquer votre présence
                        </p>
                        
                        <div class="mb-3">
                            {!! QrCode::size(250)->generate($qrUrl) !!}
                        </div>

                        <p class="text-info small mb-3">
                            Valide jusqu'à {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}
                        </p>

                        <button onclick="scannerQRCode('{{ $seance->qr_token }}', '{{ $etudiant->noet }}')" 
                                class="btn btn-success btn-lg w-100">
                            <i class="bi bi-qr-code-scan"></i> Scanner et valider ma présence
                        </button>

                        <p class="text-muted small mt-3 mb-0">
                            Ou copiez ce lien :<br>
                            <code class="small">{{ $qrUrl }}</code>
                        </p>
                    </div>
                </div>
            @elseif($presence)
                <div class="card shadow border-success">
                    <div class="card-header bg-success text-white text-center">
                        <h6 class="m-0 font-weight-bold">
                            <i class="bi bi-check-circle"></i> Présence enregistrée
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-success">Votre présence a été enregistrée</h5>
                        <p class="text-muted">
                            Statut : 
                            @if($presence->statut == 'present')
                                <span class="badge bg-success">Présent</span>
                            @elseif($presence->statut == 'retard')
                                <span class="badge bg-warning">Retard</span>
                            @endif
                        </p>
                        <p class="text-muted small">
                            Enregistré le : {{ \Carbon\Carbon::parse($presence->date_enregistrement)->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            @elseif($isEnCours && !$seance->qr_token)
                <div class="card shadow border-warning">
                    <div class="card-header bg-warning text-white text-center">
                        <h6 class="m-0 font-weight-bold">
                            <i class="bi bi-exclamation-triangle"></i> QR Code non disponible
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted">
                            Le QR code n'est pas encore généré pour cette séance.
                            <br>Contactez votre enseignant.
                        </p>
                    </div>
                </div>
            @elseif(now() < $seance->date_debut)
                <div class="card shadow border-info">
                    <div class="card-header bg-info text-white text-center">
                        <h6 class="m-0 font-weight-bold">
                            <i class="bi bi-calendar-check"></i> Séance à venir
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted">
                            Cette séance n'a pas encore commencé.
                            <br>Le QR code sera disponible à partir de 
                            <strong>{{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y à H:i') }}</strong>
                        </p>
                    </div>
                </div>
            @else
                <div class="card shadow border-secondary">
                    <div class="card-header bg-secondary text-white text-center">
                        <h6 class="m-0 font-weight-bold">
                            <i class="bi bi-clock-history"></i> Séance terminée
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted">
                            Cette séance est terminée.
                            @if(!$presence)
                                <br>Vous n'avez pas marqué votre présence.
                            @endif
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function scannerQRCode(token, noet) {
            if (!token || !noet) {
                alert('Erreur : Données manquantes');
                return;
            }

            fetch('{{ route("scan.qr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    token: token,
                    noet: noet
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message);
                    // Recharger la page pour mettre à jour l'affichage
                    window.location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'enregistrement de la présence.');
            });
        }
    </script>
@endsection
