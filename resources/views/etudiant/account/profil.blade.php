@extends('modele')

@section('title', 'Profil')

@section('contents')
    <h1 class="h3 mb-4 text-gray-800">Profil Étudiant</h1>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informations personnelles</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1">Nom d'utilisateur (Login)</label>
                            <input class="form-control" type="text" value="{{ $user->login }}" readonly>
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1">Prénom</label>
                                <input class="form-control" type="text" value="{{ $user->prenom }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1">Nom</label>
                                <input class="form-control" type="text" value="{{ $user->nom }}" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Rôle</label>
                            <input class="form-control" type="text" value="{{ ucfirst($user->type) }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection