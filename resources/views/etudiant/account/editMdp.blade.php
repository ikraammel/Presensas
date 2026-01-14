@extends('modele')
@section('title', 'Changer son mot de passe')
@section('contents')

    <style>
        .password-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            background: none;
            border: none;
            color: #7f8c8d;
            font-size: 1.1rem;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: #2c3e50;
        }

        .password-input {
            padding-right: 45px !important;
        }

        .form-container {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .form-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 1.8rem;
        }

        .form-label {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 12px;
        }

        .form-control:focus {
            border-color: #0c5aa0;
            box-shadow: 0 0 0 0.2rem rgba(12, 90, 160, 0.25);
        }

        .btn-submit {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 6px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            color: white;
        }
    </style>

    <div class="form-container">
        <h1 class="form-title">
            <i class="bi bi-key me-2" style="color: #2c3e50;"></i>
            Changer Mon Mot de Passe
        </h1>

        @if(session()->has('etat'))
            <div class="alert alert-{{ session()->get('etat') == 'Mot de passe changé' ? 'success' : 'danger' }} alert-dismissible fade show"
                role="alert">
                <i
                    class="bi bi-{{ session()->get('etat') == 'Mot de passe changé' ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>
                {{ session()->get('etat') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('etudiant.account.edit') }}" method="POST">
            <div class="mb-3">
                <label for="mdp_old" class="form-label">Ancien Mot de Passe</label>
                <input type="password" class="form-control" name="mdp_old" id="mdp_old" value="password" readonly>
                <small class="form-text text-muted mt-1">Votre mot de passe actuel (affiché masqué)</small>
                @error('mdp_old')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="mdp" class="form-label">Nouveau Mot de Passe</label>
                <div class="password-field">
                    <input type="password" class="form-control password-input" name="mdp" id="mdp"
                        placeholder="Entrez votre nouveau mot de passe" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('mdp')">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
                @error('mdp')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="mdp_confirmation" class="form-label">Confirmer Mot de Passe</label>
                <div class="password-field">
                    <input type="password" class="form-control password-input" name="mdp_confirmation" id="mdp_confirmation"
                        placeholder="Confirmez votre nouveau mot de passe" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('mdp_confirmation')">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
                @error('mdp_confirmation')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn-submit" type="submit" name="Confirmer" value="Confirmer">
                <i class="bi bi-check-circle me-2"></i>Confirmer le Changement
            </button>

            @csrf
        </form>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = event.currentTarget;
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }
    </script>

@endsection