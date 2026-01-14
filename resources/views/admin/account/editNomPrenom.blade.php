@extends('modele')
@section('title', 'Modifier Mes Informations - Admin')
@section('contents')

    <style>
        .form-container {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-confirm {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 6px;
            flex: 1;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .btn-cancel {
            background: #ecf0f1;
            border: 1px solid #bdc3c7;
            color: #2c3e50;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 6px;
            flex: 1;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #d5dbdb;
            transform: translateY(-2px);
        }
    </style>

    <div class="form-container">
        <h1 class="form-title">
            <i class="bi bi-pencil-square me-2" style="color: #2c3e50;"></i>
            Modifier Mes Informations
        </h1>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Erreur!</strong> Veuillez vérifier vos informations.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.account.editNomPrenom', ['id' => $users->id]) }}" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" id="nom" placeholder="Entrez votre nom" value="{{ old('nom', $users->nom) }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control @error('prenom') is-invalid @enderror" name="prenom" id="prenom" placeholder="Entrez votre prénom" value="{{ old('prenom', $users->prenom) }}" required>
                @error('prenom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" name="Confirmer" value="Confirmer" class="btn-confirm">
                    <i class="bi bi-check-circle me-2"></i>Confirmer
                </button>
                <button type="submit" name="Annuler" value="Annuler" class="btn-cancel">
                    <i class="bi bi-x-circle me-2"></i>Annuler
                </button>
            </div>

            @csrf
        </form>
    </div>

@endsection
