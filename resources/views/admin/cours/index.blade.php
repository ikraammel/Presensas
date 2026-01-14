@extends('modele')
@section('title', 'Gestion des Cours')
@section('contents')
    @unless(empty($cours))
        <div class="container-sm mt-4">
            <!-----------------------------Btn Back------------------------------>
            <a class="btn btn-info mt-3" href="{{ URL::previous() }}"><i class="bi bi-arrow-left-circle-fill"></i> Back</a>

            <!------------------------------btn ajoute Cours-------------------------->
            <a type="buttom" class="btn btn-info mt-3" href="{{route('admin.cours.add')}}"><i class="bi bi-plus-circle-fill"></i> Ajoute Cours</a>

            <!---------------------------- Barre de recherche --------------->
            <form action="{{route('admin.cours.search')}}" class="d-flex mt-3">
                <input class="form-control me-2" type="search" name="q" value="{{request()->q ?? ''}}" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-primary"  type="submit">Recherche</button>
            </form>

            <!---------------------- Table --------------------------------->
            <table class="table table-hover caption-top mb-3 shadow-sm">
                <caption>Liste des cours</caption>
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Intitulé</th>
                    <th>Enseignant</th>
                    <th>Créé le</th>
                    <th>Mis à jour le</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                @forelse($cours as $cour)
                    <tr>
                        <td>{{ $cour->id }}</td>
                        <td>{{ $cour->intitule }}</td>
                        <td>
                            @php
                                $enseignant = $cour->user()->first();
                            @endphp
                            @if($enseignant)
                                {{ $enseignant->nom }} {{ $enseignant->prenom }} ({{ $enseignant->login }})
                            @else
                                <span class="text-muted">Aucun enseignant affecté</span>
                            @endif
                        </td>
                        <td>{{ $cour->created_at }}</td>
                        <td>{{ $cour->updated_at }}</td>
                        <td class="text-center">
                            <a type="button" class="btn btn-sm btn-outline-secondary mb-1"
                               href="{{ route('admin.cours.assign-enseignant', ['id' => $cour->id]) }}">
                                <i class="bi bi-person-check"></i> Affecter enseignant
                            </a>
                            <a type="button" class="btn btn-primary btn-sm mb-1"
                               href="{{ route('admin.cours.edit', ['id' => $cour->id]) }}">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </a>
                            <a type="button" class="btn btn-danger btn-sm mb-1"
                               href="{{ route('admin.cours.delete', ['id' => $cour->id]) }}">
                                <i class="bi bi-trash3"></i> Supprimer
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Aucun cours trouvé.</td>
                    </tr>
                @endforelse
            </table>
        </div>
    @endunless
@endsection
