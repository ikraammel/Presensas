@extends('modele')

@section('title', 'Inscription - Présensas')

@section('contents')
    <div class="container auth-container">
        <div class="auth-card">
            <div class="brand-logo">
                <i class="bi bi-geo-alt-fill"></i>
                <span class="brand-text">PRÉSENSAS</span>
            </div>

            <p class="auth-subtitle">Créez votre compte pour accéder à la plateforme</p>

            <!-- Affichage des erreurs de validation -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('register') }}">
                @csrf

                <!-- Login (Email/Username) -->
                <div class="mb-3">
                    <label for="login" class="form-label">Login</label>
                    <input type="text" class="form-control @error('login') is-invalid @enderror" name="login" id="login" value="{{ old('login') }}" required
                        placeholder="Choisissez un identifiant">
                    @error('login')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <!-- Nom -->
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" id="nom" value="{{ old('nom') }}" required
                            placeholder="Votre nom">
                        @error('nom')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Prénom -->
                    <div class="col-md-6 mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control @error('prenom') is-invalid @enderror" name="prenom" id="prenom" value="{{ old('prenom') }}"
                            required placeholder="Votre prénom">
                        @error('prenom')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control @error('mdp') is-invalid @enderror" name="mdp" id="mdp" required
                        placeholder="Créer un mot de passe">
                    @error('mdp')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="mb-3">
                    <label for="mdp_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" name="mdp_confirmation" id="mdp_confirmation" required
                        placeholder="Confirmer votre mot de passe">
                </div>

                <!-- Type d'utilisateur -->
                <div class="mb-3">
                    <label for="type" class="form-label">Type d'utilisateur</label>
                    <select class="form-select @error('type') is-invalid @enderror" name="type" id="type" required>
                        <option value="">-- Choisissez votre profil --</option>
                        <option value="etudiant" {{ old('type') == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                        <option value="enseignant" {{ old('type') == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                    </select>
                    @error('type')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Champs spécifiques aux étudiants (affichés conditionnellement) -->
                <div id="etudiant-fields" style="display: none;">
                    <!-- Numéro d'étudiant -->
                    <div class="mb-3">
                        <label for="noet" class="form-label">Numéro d'étudiant</label>
                        <input type="text" class="form-control @error('noet') is-invalid @enderror" name="noet" id="noet" 
                            value="{{ old('noet') }}" placeholder="Ex: ET12345">
                        @error('noet')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- CNE -->
                    <div class="mb-3">
                        <label for="cne" class="form-label">CNE</label>
                        <input type="text" class="form-control @error('cne') is-invalid @enderror" name="cne" id="cne" 
                            value="{{ old('cne') }}" placeholder="Ex: G123456789">
                        @error('cne')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Niveau -->
                    <div class="mb-3">
                        <label for="niveau" class="form-label">Niveau</label>
                        <select class="form-select @error('niveau') is-invalid @enderror" name="niveau" id="niveau">
                            <option value="">-- Choisissez votre niveau --</option>
                            <option value="CP1" {{ old('niveau') == 'CP1' ? 'selected' : '' }}>CP1</option>
                            <option value="CP2" {{ old('niveau') == 'CP2' ? 'selected' : '' }}>CP2</option>
                            <option value="GIIA1" {{ old('niveau') == 'GIIA1' ? 'selected' : '' }}>GIIA1</option>
                            <option value="GIIA2" {{ old('niveau') == 'GIIA2' ? 'selected' : '' }}>GIIA2</option>
                            <option value="GIIA3" {{ old('niveau') == 'GIIA3' ? 'selected' : '' }}>GIIA3</option>
                            <option value="GTR1" {{ old('niveau') == 'GTR1' ? 'selected' : '' }}>GTR1</option>
                            <option value="GTR2" {{ old('niveau') == 'GTR2' ? 'selected' : '' }}>GTR2</option>
                            <option value="GTR3" {{ old('niveau') == 'GTR3' ? 'selected' : '' }}>GTR3</option>
                            <option value="GPMA1" {{ old('niveau') == 'GPMA1' ? 'selected' : '' }}>GPMA1</option>
                            <option value="GPMA2" {{ old('niveau') == 'GPMA2' ? 'selected' : '' }}>GPMA2</option>
                            <option value="GPMA3" {{ old('niveau') == 'GPMA3' ? 'selected' : '' }}>GPMA3</option>
                            <option value="GATE1" {{ old('niveau') == 'GATE1' ? 'selected' : '' }}>GATE1</option>
                            <option value="GATE2" {{ old('niveau') == 'GATE2' ? 'selected' : '' }}>GATE2</option>
                            <option value="GATE3" {{ old('niveau') == 'GATE3' ? 'selected' : '' }}>GATE3</option>
                            <option value="GMSI1" {{ old('niveau') == 'GMSI1' ? 'selected' : '' }}>GMSI1</option>
                            <option value="GMSI2" {{ old('niveau') == 'GMSI2' ? 'selected' : '' }}>GMSI2</option>
                            <option value="GMSI3" {{ old('niveau') == 'GMSI3' ? 'selected' : '' }}>GMSI3</option>
                            <option value="GINDUS1" {{ old('niveau') == 'GINDUS1' ? 'selected' : '' }}>GINDUS1</option>
                            <option value="GINDUS2" {{ old('niveau') == 'GINDUS2' ? 'selected' : '' }}>GINDUS2</option>
                            <option value="GINDUS3" {{ old('niveau') == 'GINDUS3' ? 'selected' : '' }}>GINDUS3</option>
                            <option value="IDIA1" {{ old('niveau') == 'IDIA1' ? 'selected' : '' }}>IDIA1</option>
                            <option value="IDIA2" {{ old('niveau') == 'IDIA2' ? 'selected' : '' }}>IDIA2</option>
                        </select>
                        @error('niveau')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary-custom mt-2">S'inscrire</button>

                <!-- Footer Links -->
                <div class="footer-links">
                    <p>Déjà membre ? <a href="{{ route('login') }}">Se connecter</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Afficher/masquer les champs étudiants selon le type sélectionné
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const etudiantFields = document.getElementById('etudiant-fields');
            const noetInput = document.getElementById('noet');
            const cneInput = document.getElementById('cne');
            const niveauSelect = document.getElementById('niveau');

            function toggleEtudiantFields() {
                if (typeSelect.value === 'etudiant') {
                    etudiantFields.style.display = 'block';
                    noetInput.setAttribute('required', 'required');
                    cneInput.setAttribute('required', 'required');
                    niveauSelect.setAttribute('required', 'required');
                } else {
                    etudiantFields.style.display = 'none';
                    noetInput.removeAttribute('required');
                    cneInput.removeAttribute('required');
                    niveauSelect.removeAttribute('required');
                }
            }

            // Initialiser l'état au chargement
            toggleEtudiantFields();

            // Écouter les changements
            typeSelect.addEventListener('change', toggleEtudiantFields);
        });
    </script>
@endsection