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

        // Classes de l'enseignant
        $groupes = Groupe::where('user_id', $user->id)->get();
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

    // Afficher le profil de l'enseignant
    public function showProfil()
    {
        $user = Auth::user();
        return view('enseignant.account.profil', ['user' => $user]);
    }
}
