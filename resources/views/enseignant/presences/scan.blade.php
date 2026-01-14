@extends('modele')

@section('title', 'Scan Présence - ' . $seance->cours->intitule)

@section('contents')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Scanner QR Code - {{ $seance->cours->intitule }}
                        ({{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }})</h6>
                    <a href="{{ route('enseignant.showSeance') }}" class="btn btn-secondary btn-sm">Retour</a>
                </div>
                <div class="card-body">
                    <div id="reader" width="600px"></div>

                    <div class="mt-4">
                        <h5>Derniers scans :</h5>
                        <ul id="scan-results" class="list-group">
                            <!-- Résultats ici -->
                        </ul>
                    </div>

                    <input type="hidden" id="seance_id" value="{{ $seance->id }}">
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    {{-- Html5-QRCode Library --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Handle on success condition with the decoded text
            // decodedText = noet (Numéro étudiant)
            console.log(`Scan result: ${decodedText}`, decodedResult);

            // Disable scanner temporarily or show processing
            // html5QrcodeScanner.pause(); // Optional

            let seanceId = document.getElementById('seance_id').value;

            $.ajax({
                url: "{{ route('enseignant.presences.qr-code.scan', ['seanceId' => $seance->id]) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    seance_id: seanceId,
                    noet: decodedText
                },
                success: function (response) {
                    let badgeClass = response.success ? 'bg-success' : 'bg-warning';
                    let message = response.message;

                    // Add to list
                    let li = `<li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${decodedText}</span>
                                <span class="badge ${badgeClass} rounded-pill">${message}</span>
                              </li>`;
                    $('#scan-results').prepend(li);

                    // Play sound? (Optional)
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    let li = `<li class="list-group-item list-group-item-danger">Erreur scan: ${decodedText}</li>`;
                    $('#scan-results').prepend(li);
                }
            });
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            // console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: { width: 250, height: 250 } },
            /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
@endsection