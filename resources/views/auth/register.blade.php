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

                <!-- Submit -->
                <button type="submit" class="btn btn-primary-custom mt-2">S'inscrire</button>

                <!-- Footer Links -->
                <div class="footer-links">
                    <p>Déjà membre ? <a href="{{ route('login') }}">Se connecter</a></p>
                </div>
            </form>
        </div>
    </div>
@endsection