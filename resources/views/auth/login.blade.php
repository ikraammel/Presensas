@extends('modele')
@section('title', 'Login - Présensas')

@section('contents')
    <style>
        body {
            background-color: #f8f9fa;
            /* Light grey background */
        }

        .login-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            /* Soft shadow */
            width: 100%;
            max-width: 450px;
            text-align: left;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: #1a4fba;
            /* Deep blue */
        }

        .brand-logo i {
            font-size: 2rem;
            margin-right: 10px;
        }

        .brand-text {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-left: 2px solid #ddd;
            padding-left: 10px;
            margin-left: 10px;
        }

        .login-subtitle {
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 5px;
        }

        .form-control,
        .form-select {
            background-color: #fcfcfc;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: #1a4fba;
        }

        .btn-login {
            background-color: #1a4fba;
            border-color: #1a4fba;
            color: white;
            font-weight: 500;
            padding: 10px;
            border-radius: 6px;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.15s ease-in-out;
        }

        .btn-login:hover {
            background-color: #153d8f;
            border-color: #153d8f;
        }

        .footer-links {
            margin-top: 20px;
            text-align: center;
            font-size: 0.85rem;
        }

        .footer-links a {
            color: #1a4fba;
            text-decoration: none;
            margin: 0 5px;
        }

        .footer-links span {
            color: #ccc;
        }
    </style>

    <div class="container login-container">
        <div class="login-card">
            <div class="brand-logo">
                <i class="bi bi-geo-alt-fill"></i> <!-- Using Bootstrap push pin/geo icon -->
                <span class="brand-text">PRÉSENSAS</span>
            </div>

            <p class="login-subtitle">Connectez-vous pour accéder à votre espace personnel</p>

            <form method="post" action="{{ route('login') }}">
                @csrf

                <!-- Rôle Selector -->
                <div class="mb-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select class="form-select" id="role" name="role">
                        <option value="etudiant" selected>Étudiant</option>
                        <option value="enseignant">Enseignant</option>
                        <option value="gestionnaire">Gestionnaire</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <!-- Email (Login) Input -->
                <div class="mb-3">
                    <label for="login" class="form-label">Adresse e-mail</label>
                    <input type="text" class="form-control" id="login" name="login" placeholder="Entrez votre e-mail"
                        value="{{ old('login') }}" required>
                </div>

                <!-- Password Input -->
                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Entrez votre mot de passe"
                        required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login">Se connecter</button>

                <!-- Footer Links -->
                <div class="footer-links">
                    <a href="#">Mot de passe oublié ?</a>
                    <span>|</span>
                    <a href="{{ route('register') }}">S'inscrire</a>
                </div>
            </form>
        </div>
    </div>
@endsection