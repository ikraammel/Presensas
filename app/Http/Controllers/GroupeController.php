<?php

namespace App\Http\Controllers;

use App\Models\Etudiants;
use App\Models\Groupe;
use App\Models\User;
use Illuminate\Http\Request;

class GroupeController extends Controller
{
    // Affiche la liste des classes
    public function index()
    {
        $groupes = Groupe::with(['user', 'etudiants'])->get();
        return view('admin.groupes.index', compact('groupes'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        // Récupérer les utilisateurs ayant le rôle 'enseignant' ou 'admin' (si admin peut être prof)
        $enseignants = User::where('type', 'enseignant')->get();
        return view('admin.groupes.create', compact('enseignants'));
    }

    // Enregistre une nouvelle classe
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        Groupe::create($request->all());

        return redirect()->route('admin.groupes.index')
            ->with('etat', 'Classe créée avec succès');
    }

    // Affiche les détails d'une classe
    public function show($id)
    {
        $groupe = Groupe::with(['user', 'etudiants'])->findOrFail($id);

        // Pour ajouter des étudiants, on peut vouloir lister ceux qui n'ont pas de groupe ou tous
        // Ici on liste ceux qui n'ont PAS de groupe pour faciliter l'ajout
        $etudiantsSansGroupe = Etudiants::whereNull('groupe_id')->get();

        return view('admin.groupes.show', compact('groupe', 'etudiantsSansGroupe'));
    }

    // Ajoute un étudiant à la classe
    public function addEtudiant(Request $request, $id)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id'
        ]);

        $groupe = Groupe::findOrFail($id);
        $etudiant = Etudiants::findOrFail($request->etudiant_id);

        $etudiant->groupe_id = $groupe->id;
        $etudiant->save();

        return redirect()->route('admin.groupes.show', $id)
            ->with('etat', 'Étudiant ajouté à la classe');
    }

    // Retire un étudiant de la classe
    public function removeEtudiant($id, $etudiant_id)
    {
        $groupe = Groupe::findOrFail($id);
        $etudiant = Etudiants::where('id', $etudiant_id)->where('groupe_id', $id)->firstOrFail();

        $etudiant->groupe_id = null;
        $etudiant->save();

        return redirect()->route('admin.groupes.show', $id)
            ->with('etat', 'Étudiant retiré de la classe');
    }

    // Supprime une classe
    public function destroy($id)
    {
        $groupe = Groupe::findOrFail($id);

        // On libère les étudiants avant de supprimer le groupe
        Etudiants::where('groupe_id', $id)->update(['groupe_id' => null]);

        $groupe->delete();

        return redirect()->route('admin.groupes.index')
            ->with('etat', 'Classe supprimée');
    }
}
