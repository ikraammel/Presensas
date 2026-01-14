@extends('modele')

@section('title', 'Ajouter un cours')

@section('contents')
    <div class="container-sm mt-4">
        <!-----------------------------Btn Back------------------------------>
        <a class="btn btn-info mt-3" href="{{ URL::previous() }}"><i class="bi bi-arrow-left-circle-fill"></i> Back</a><br>
        <h4>Formulaire d'ajout</h4>
        <form action="{{route('admin.cours.add')}}" method="POST">
            <div class="mb-3">
                <label for="formGroupExampleInput" class="form-label">Intitulé</label>
                <input type="text"
                       class="form-control @error('intitule') is-invalid @enderror"
                       name="intitule"
                       id="formGroupExampleInput"
                       value="{{ old('intitule') }}"
                       placeholder="Ex : Analyse 1, Base de données, Programmation Web...">
                @error('intitule')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <input class="btn btn-primary w-auto" type="submit" name="creer" value="Créer">
            @csrf
        </form>
    </div>
@endsection
