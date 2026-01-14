<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Cours;
use App\Models\Document;
use App\Models\Etudiants;
use App\Models\Groupe;
use App\Models\Presences;
use App\Models\Seances;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EnseignantController extends Controller
{
    // Dashboard enseignant
    public function index()
    {
        $user = Auth::user();
        $cours = $user->cours;
        $totalCours = $cours->count();

        // Classes de l'enseignant avec leurs étudiants
        $groupes = Groupe::where('user_id', $user->id)
            ->with('etudiants')
            ->get();
        $totalClasses = $groupes->count();

        // Séances du jour
        $coursIds = $cours->pluck('id');
        $seancesAujourdhui = Seances::whereIn('cours_id', $coursIds)
            ->whereDate('date_debut', Carbon::today())
            ->count();

        $seancesAvenir = Seances::whereIn('cours_id', $coursIds)
            ->where('date_debut', '>', now())
            ->with('cours')
            ->orderBy('date_debut')
            ->take(5)
            ->get();
        $totalSeancesAvenir = Seances::whereIn('cours_id', $coursIds)
            ->where('date_debut', '>', now())
            ->count();

        return view('enseignant.home', [
            'user' => $user,
            'totalCours' => $totalCours,
            'totalClasses' => $totalClasses,
            'groupes' => $groupes, // Passer les groupes à la vue
            'seancesAujourdhui' => $seancesAujourdhui,
            'totalSeancesAvenir' => $totalSeancesAvenir,
            'seancesAvenir' => $seancesAvenir,
        ]);
    }
    //Modifier le mot de passe enseignant
    public function editFormMdp()
    {
        return view('enseignant.account.editMdp');
    }

    //Modifier le mot de passe enseignant
    public function editMdp(Request $request)
    {
        $request->validate([
            'mdp_old' => 'required|string',
            'mdp' => 'required|string|confirmed'
        ]);
        $user = Auth::user();
        if (Hash::check($request->mdp_old, $user->mdp)) {
            $user->fill(['mdp' => Hash::make($request->mdp)])->save();
            $request->session()->flash('etat', 'Mot de passe changé');
            return redirect()->route('enseignant.home');
        }
        $request->session()->flash('etat', 'votre mot de passe n\'est pas correct, Veuillez réessayer');

        return redirect()->route('enseignant.home');
    }

    //Moidifier le nom et prenom de l'enseignant
    public function editForm_NomPrenom($id)
    {
        $edit = User::find($id);
        return view('enseignant.account.editNomPrenom', ['users' => $edit]);
    }

    //Modifier le nom et prenom de l'enseignant
    public function editName(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|alpha|max:50',
            'prenom' => 'required|string|max:265',
        ]);
        $user = User::findOrfail($id);
        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
        $user->save();
        $request->session()->flash('etat', 'la modification a été effectuée avec succès');
        return redirect()->route('enseignant.home');
    }

    //Pointer un Etudiant
    public function pointageEtudiantForm($id)
    {
        $seance = Seances::findOrFail($id);
        $etudiant = Etudiants::all();
        return view('enseignant.pointage.pointageEtudiant', ['seances' => $seance, 'etudiants' => $etudiant]);
    }

    //Pointer Un étudiant
    public function pointageEtudiant(Request $request)
    {
        $request->validate([
            'id' => 'required', //l'ID de la séance
            'id_etudiant' => 'required' // ID de l'étudiant
        ]);
        $etudiantsAssos = DB::table('presences')->get();

        foreach ($etudiantsAssos as $asso) {
            if (($asso->seance_id == $request->id && $asso->etudiant_id == $request->id_etudiant)) {
                return back()->withErrors([
                    'errors' => 'Erreur: vous êtes déjà pointé à ce cours'
                ]);
            }
        }
        $etudiant = Etudiants::where('id', $request->id_etudiant)->first();
        $seance = Seances::where('id', $request->id)->first();
        $etudiant->seances()->attach($seance);
        $etudiant->save();
        $request->session()->flash('etat', "Pointage réussie: " . $etudiant->nom . " " . $etudiant->prenom . " present pour la séance");
        return redirect()->back();
    }

    //Affiche la séance des cours
    public function showSeance()
    {
        $user = Auth::user();
        $coursIds = $user->cours->pluck('id');
        $seances = Seances::whereIn('cours_id', $coursIds)
            ->with('cours')
            ->orderBy('date_debut', 'desc')
            ->get();
        return view('enseignant.pointage.ListeSeanceCours', ['seances' => $seances]);
    }

    //Pointer Plusieurs étudiants
    public function pointageEtudiantPlusieursForm()
    {
        $seance = Seances::all();
        $etudiant = Etudiants::all();
        return view('enseignant.pointage.pointageEtudiantPlusieurs', ['seances' => $seance, 'etudiants' => $etudiant]);
    }

    //Pointer Plusieurs étudiants
    public function pointageEtudiantPlusieurs(Request $request)
    {// la fonction marche bien sauf qu'il ne gère pas le cas où l'étudiant a déjà été pointé dans la base de donnée
        $request->validate([
            'id' => 'required', //l'ID de la séance
            'id_etudiant' => 'required' // ID de l'étudiant
        ]);
        $etudiants = Etudiants::findOrFail($request->id_etudiant);
        $seance = Seances::where('id', $request->id)->first();
        foreach ($etudiants as $etudiant) {
            $etudiant->seances()->attach($seance);
            $etudiant->save();
        }
        $request->session()->flash('etat', "Pointage Plusieurs Etudiant réussie: présent pour la séance");
        return redirect()->back();
    }

    //Voir la liste des cours associés à un enseignant 1.1. Voir la liste des cours associés.
    public function showEnseignantList($id)
    {
        $u = User::findOrFail($id);
        $g = $u->cours;
        return view('enseignant.VoirListeCoursEnseignant', ['cours' => $g]);
    }

    // Afficher tous les modules de l'enseignant
    public function indexModules()
    {
        $user = Auth::user();
        $modules = $user->cours()->paginate(10);

        return view('enseignant.modules', [
            'modules' => $modules,
            'totalModules' => $user->cours()->count(),
        ]);
    }

    // Afficher tous les étudiants pour les cours de l'enseignant
    public function indexEtudiants()
    {
        $user = Auth::user();

        // Récupérer les groupes de l'enseignant
        $groupeIds = Groupe::where('user_id', $user->id)->pluck('id');

        // Récupérer les étudiants de ces groupes
        $etudiants = Etudiants::whereIn('groupe_id', $groupeIds)->paginate(15);

        return view('enseignant.etudiants', [
            'etudiants' => $etudiants,
            'totalEtudiants' => Etudiants::count(),
        ]);
    }

    // Afficher les détails d'un module (cours) avec ses documents
    public function showModule($id)
    {
        $user = Auth::user();
        // Vérifier que le cours appartient bien à l'enseignant ou est assigné
        // Ici on suppose que $user->cours contient les cours assignés
        $cours = $user->cours()->where('cours.id', $id)->with('documents')->firstOrFail();

        return view('enseignant.modules.show', compact('cours'));
    }

    // Afficher le profil de l'enseignant
    public function showProfil()
    {
        $user = Auth::user();
        return view('enseignant.account.profil', ['user' => $user]);
    }

    // Lister les absences avec justificatifs à valider
    public function listeAbsences()
    {
        $user = Auth::user();
        $coursIds = $user->cours->pluck('id');

        // Récupérer les présences (absences) pour les cours de l'enseignant qui ont un justificatif
        $presences = Presences::whereHas('seances', function ($query) use ($coursIds) {
            $query->whereIn('cours_id', $coursIds);
        })
            ->where('statut', 'absent')
            ->whereNotNull('justificatif') // Seulement ceux qui ont soumis quelque chose
            ->with(['etudiants', 'seances.cours'])
            ->orderBy('statut_justificatif', 'desc') // En attente d'abord si on trie ou autre
            ->orderBy('date_enregistrement', 'desc')
            ->get();

        return view('enseignant.absences.index', [
            'presences' => $presences
        ]);
    }

    // Valider un justificatif
    public function validerJustificatif(Request $request, $id)
    {
        $presence = Presences::findOrFail($id);
        $presence->statut_justificatif = 'valide';
        // Optionnel : changer le statut global 'absent' -> 'justifie' ou similaire si la logique métier le demande
        // $presence->statut = 'justifie'; 
        $presence->save();

        $request->session()->flash('etat', 'Justificatif validé.');
        return redirect()->back();
    }

    // Refuser un justificatif
    public function refuserJustificatif(Request $request, $id)
    {
        $presence = Presences::findOrFail($id);
        $presence->statut_justificatif = 'refuse';
        $presence->save();

        $request->session()->flash('etat', 'Justificatif refusé.');
        return redirect()->back();
    }

    // Afficher toutes les classes assignées à l'enseignant
    public function indexClasses()
    {
        $user = Auth::user();
        $groupes = Groupe::where('user_id', $user->id)
            ->with('etudiants')
            ->get();

        return view('enseignant.classes.index', [
            'groupes' => $groupes,
            'totalClasses' => $groupes->count(),
        ]);
    }

    // Afficher les étudiants d'une classe
    public function showClasseEtudiants($id)
    {
        $user = Auth::user();
        $groupe = Groupe::where('id', $id)
            ->where('user_id', $user->id)
            ->with('etudiants')
            ->firstOrFail();

        return view('enseignant.classes.etudiants', [
            'groupe' => $groupe,
        ]);
    }
}
