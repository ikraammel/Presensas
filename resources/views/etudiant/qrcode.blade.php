@extends('modele')

@section('title', 'Mon QR Code')

@section('contents')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary text-center">Mon QR Code Étudiant</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        {{-- Utilisation de la facade QrCode --}}
                        {{-- Le contenu du QR est le numéro étudiant (noet) --}}
                        {!! QrCode::size(250)->generate($etudiant->noet) !!}
                    </div>

                    <h4>{{ $etudiant->nom }} {{ $etudiant->prenom }}</h4>
                    <p class="text-muted">{{ $etudiant->noet }}</p>
                    <p class="small text-info">Présentez ce code à votre enseignant pour valider votre présence.</p>
                </div>
            </div>
        </div>
    </div>
@endsection