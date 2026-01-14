<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner QR Code - Présence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0"><i class="bi bi-qr-code-scan"></i> Scanner QR Code</h5>
                    </div>
                    <div class="card-body text-center">
                        <div id="message" class="alert" style="display: none;"></div>
                        
                        <div id="scanner-section">
                            <p class="text-muted mb-4">Scannez le QR code affiché par votre enseignant</p>
                            
                            <!-- Zone pour le scanner (à implémenter avec une librairie JS si nécessaire) -->
                            <div class="mb-3">
                                <input type="text" 
                                       id="qr-input" 
                                       class="form-control form-control-lg text-center" 
                                       placeholder="Entrez le code ou scannez le QR"
                                       autofocus>
                            </div>
                            
                            <button class="btn btn-primary btn-lg" onclick="validerPresence()">
                                <i class="bi bi-check-circle"></i> Valider ma présence
                            </button>
                        </div>

                        <div id="success-section" style="display: none;">
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill"></i>
                                <h5>Présence enregistrée avec succès !</h5>
                            </div>
                            <button class="btn btn-secondary" onclick="resetForm()">Scanner un autre code</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let token = null;

        // Extraire le token de l'URL
        const urlParams = new URLSearchParams(window.location.search);
        token = urlParams.get('token');

        if (token) {
            document.getElementById('qr-input').value = token;
        }

        function validerPresence() {
            const input = document.getElementById('qr-input').value.trim();
            const noet = prompt('Entrez votre numéro d\'étudiant (NOET):');
            
            if (!noet) {
                showMessage('Veuillez entrer votre numéro d\'étudiant.', 'danger');
                return;
            }

            if (!input) {
                showMessage('Veuillez entrer ou scanner le code QR.', 'danger');
                return;
            }

            // Extraire le token de l'input (peut être une URL complète ou juste le token)
            let qrToken = input;
            if (input.includes('token=')) {
                qrToken = input.split('token=')[1].split('&')[0];
            }

            fetch('{{ route("scan.qr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    token: qrToken,
                    noet: noet
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('scanner-section').style.display = 'none';
                    document.getElementById('success-section').style.display = 'block';
                    showMessage(data.message, 'success');
                } else {
                    showMessage(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showMessage('Erreur lors de l\'enregistrement de la présence.', 'danger');
            });
        }

        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = message;
            messageDiv.className = 'alert alert-' + type;
            messageDiv.style.display = 'block';
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }

        function resetForm() {
            document.getElementById('scanner-section').style.display = 'block';
            document.getElementById('success-section').style.display = 'none';
            document.getElementById('qr-input').value = '';
            document.getElementById('qr-input').focus();
        }

        // Permettre la validation avec Enter
        document.getElementById('qr-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                validerPresence();
            }
        });
    </script>
</body>
</html>
