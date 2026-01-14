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
        $cours = $user->cours()->where('cours.id', $id)
            ->with(['documents' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

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

    // Page principale d'enregistrement de présence (avec onglets)
    public function enregistrerPresence($seanceId)
    {
        $user = Auth::user();
        $seance = Seances::with(['cours', 'groupe.etudiants', 'presences.etudiants'])->findOrFail($seanceId);
        
        // Vérifier que la séance appartient à un cours de l'enseignant
        $coursIds = $user->cours->pluck('id')->toArray();
        if (!in_array($seance->cours_id, $coursIds)) {
            abort(403, 'Vous n\'avez pas accès à cette séance.');
        }

        // Récupérer les étudiants de la classe directement via le groupe de la séance
        if (!$seance->groupe_id) {
            abort(404, 'Cette séance n\'est pas associée à une classe.');
        }

        // Vérifier que le groupe appartient à l'enseignant
        if ($seance->groupe->user_id != $user->id) {
            abort(403, 'Vous n\'avez pas accès à cette classe.');
        }

        $etudiants = $seance->groupe->etudiants;

        // Récupérer les présences déjà enregistrées
        $presencesExistantes = Presences::where('seance_id', $seanceId)
            ->with('etudiants')
            ->get()
            ->keyBy('etudiant_id');

        // Générer ou récupérer le QR code
        if (!$seance->qr_token || ($seance->qr_expires_at && now() > $seance->qr_expires_at)) {
            $seance->qr_token = Str::random(32);
            $seance->qr_expires_at = $seance->date_fin;
            $seance->save();
        }

        // Statistiques
        $stats = [
            'present' => Presences::where('seance_id', $seanceId)->where('statut', 'present')->count(),
            'absent' => Presences::where('seance_id', $seanceId)->where('statut', 'absent')->count(),
            'retard' => Presences::where('seance_id', $seanceId)->where('statut', 'retard')->count(),
            'total' => $etudiants->count(),
        ];

        return view('enseignant.presences.index', compact('seance', 'etudiants', 'presencesExistantes', 'stats'));
    }

    // Enregistrement manuel des présences
    public function storePresenceManuelle(Request $request, $seanceId)
    {
        $user = Auth::user();
        $seance = Seances::findOrFail($seanceId);
        
        // Vérifier que la séance appartient à un cours de l'enseignant
        $coursIds = $user->cours->pluck('id')->toArray();
        if (!in_array($seance->cours_id, $coursIds)) {
            abort(403, 'Vous n\'avez pas accès à cette séance.');
        }

        $request->validate([
            'presences' => 'required|array',
            'presences.*.etudiant_id' => 'required|exists:etudiants,id',
            'presences.*.statut' => 'required|in:present,absent,retard',
        ]);

        foreach ($request->presences as $presenceData) {
            Presences::updateOrCreate(
                [
                    'seance_id' => $seanceId,
                    'etudiant_id' => $presenceData['etudiant_id'],
                ],
                [
                    'statut' => $presenceData['statut'],
                    'date_enregistrement' => now(),
                ]
            );
        }

        $request->session()->flash('etat', 'Présences enregistrées avec succès !');
        return redirect()->route('enseignant.presences.manuel', $seanceId);
    }

    // Formulaire de scan QR Code pour une séance (ancienne méthode, gardée pour compatibilité)
    public function presenceQRCodeForm($seanceId)
    {
        return $this->enregistrerPresence($seanceId);
    }

    // Traitement du scan QR (AJAX) - Route publique pour les étudiants
    public function scanQRCode(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'noet' => 'required|exists:etudiants,noet'
        ]);

        // Trouver la séance par token
        $seance = Seances::where('qr_token', $request->token)
            ->where('qr_expires_at', '>=', now())
            ->first();

        if (!$seance) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code invalide ou expiré.'
            ], 400);
        }

        // Vérifier que la séance est en cours
        if (now() < $seance->date_debut || now() > $seance->date_fin) {
            return response()->json([
                'success' => false,
                'message' => 'La séance n\'est pas en cours.'
            ], 400);
        }

        $etudiant = Etudiants::where('noet', $request->noet)->firstOrFail();

        // Vérifier que l'étudiant appartient au groupe de la séance
        if (!$seance->groupe_id) {
            return response()->json([
                'success' => false,
                'message' => 'Cette séance n\'est pas associée à une classe.'
            ], 400);
        }

        if ($etudiant->groupe_id != $seance->groupe_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas inscrit à cette classe.'
            ], 403);
        }

        // Vérifier si déjà présent
        $presenceExistante = Presences::where('seance_id', $seance->id)
            ->where('etudiant_id', $etudiant->id)
            ->first();

        if ($presenceExistante) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà marqué votre présence.'
            ], 400);
        }

        // Marquer présent
        Presences::create([
            'seance_id' => $seance->id,
            'etudiant_id' => $etudiant->id,
            'statut' => 'present',
            'date_enregistrement' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Présence enregistrée avec succès !'
        ]);
    }

    // Récupérer les présences en temps réel (AJAX)
    public function getPresencesRealtime($seanceId)
    {
        $seance = Seances::with(['presences.etudiants'])->findOrFail($seanceId);
        
        $presences = $seance->presences->map(function($presence) {
            return [
                'id' => $presence->id,
                'etudiant' => $presence->etudiants->nom . ' ' . $presence->etudiants->prenom,
                'noet' => $presence->etudiants->noet,
                'statut' => $presence->statut,
                'date' => $presence->date_enregistrement->format('H:i:s'),
            ];
        });

        $stats = [
            'present' => Presences::where('seance_id', $seanceId)->where('statut', 'present')->count(),
            'absent' => Presences::where('seance_id', $seanceId)->where('statut', 'absent')->count(),
            'retard' => Presences::where('seance_id', $seanceId)->where('statut', 'retard')->count(),
        ];

        return response()->json([
            'presences' => $presences,
            'stats' => $stats,
        ]);
    }

    // Afficher la liste des séances
    public function indexSeances()
    {
        $user = Auth::user();
        $coursIds = $user->cours->pluck('id')->toArray();
        
        $seances = Seances::whereIn('cours_id', $coursIds)
            ->with(['cours', 'groupe'])
            ->orderBy('date_debut', 'desc')
            ->paginate(15);

        return view('enseignant.seances.index', compact('seances'));
    }

    // Formulaire de création de séance
    public function createSeanceForm()
    {
        $user = Auth::user();
        $cours = $user->cours;
        $groupes = Groupe::where('user_id', $user->id)
            ->with('etudiants')
            ->get();

        return view('enseignant.seances.create', compact('cours', 'groupes'));
    }

    // Enregistrer une nouvelle séance
    public function storeSeance(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'groupe_id' => 'required|exists:groupes,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
            'type_seance' => 'required|in:cours,TD,TP',
        ]);

        // Vérifier que le cours appartient à l'enseignant
        $coursIds = $user->cours->pluck('id')->toArray();
        if (!in_array($request->cours_id, $coursIds)) {
            return back()->withErrors(['cours_id' => 'Vous n\'avez pas accès à ce cours.'])->withInput();
        }

        // Vérifier que le groupe appartient à l'enseignant
        $groupe = Groupe::findOrFail($request->groupe_id);
        if ($groupe->user_id != $user->id) {
            return back()->withErrors(['groupe_id' => 'Vous n\'avez pas accès à cette classe.'])->withInput();
        }

        $seance = Seances::create([
            'cours_id' => $request->cours_id,
            'groupe_id' => $request->groupe_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'type_seance' => $request->type_seance,
            'type' => 'présentiel',
        ]);

        $request->session()->flash('etat', 'Séance créée avec succès !');
        return redirect()->route('enseignant.seances.index');
    }

    // Formulaire d'édition de séance
    public function editSeanceForm($id)
    {
        $user = Auth::user();
        $seance = Seances::with(['cours', 'groupe'])->findOrFail($id);
        
        // Vérifier que la séance appartient à l'enseignant
        $coursIds = $user->cours->pluck('id')->toArray();
        if (!in_array($seance->cours_id, $coursIds)) {
            abort(403, 'Vous n\'avez pas accès à cette séance.');
        }

        $cours = $user->cours;
        $groupes = Groupe::where('user_id', $user->id)
            ->with('etudiants')
            ->get();

        return view('enseignant.seances.edit', compact('seance', 'cours', 'groupes'));
    }

    // Mettre à jour une séance
    public function updateSeance(Request $request, $id)
    {
        $user = Auth::user();
        $seance = Seances::findOrFail($id);
        
        // Vérifier que la séance appartient à l'enseignant
        $coursIds = $user->cours->pluck('id')->toArray();
        if (!in_array($seance->cours_id, $coursIds)) {
            abort(403, 'Vous n\'avez pas accès à cette séance.');
        }

        $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'groupe_id' => 'required|exists:groupes,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'type_seance' => 'required|in:cours,TD,TP',
        ]);

        $seance->update($request->all());
        $request->session()->flash('etat', 'Séance mise à jour avec succès !');
        return redirect()->route('enseignant.seances.index');
    }

    // Supprimer une séance
    public function deleteSeance($id)
    {
        $user = Auth::user();
        $seance = Seances::findOrFail($id);
        
        // Vérifier que la séance appartient à l'enseignant
        $coursIds = $user->cours->pluck('id')->toArray();
        if (!in_array($seance->cours_id, $coursIds)) {
            abort(403, 'Vous n\'avez pas accès à cette séance.');
        }

        $seance->delete();
        session()->flash('etat', 'Séance supprimée avec succès !');
        return redirect()->route('enseignant.seances.index');
    }

    // Générer la feuille de présence PDF
    public function generateFeuillePresence($seanceId)
    {
        $seance = Seances::with(['cours', 'etudiants'])->findOrFail($seanceId);
        
        // Récupérer les présences explicitement pour avoir les détails (si via pivot c'est bon)
        // Mais $seance->etudiants vient du belongsToMany qui peut ne pas inclure ceux qui ne sont PAS là si on ne l'a pas configuré pour tout le monde.
        // En général une feuille de présence liste TOUS les étudiants du groupe, avec leur statut.
        
        // On doit récupérer le groupe associé au cours/séance
        // La séance -> cours -> groupes (si ManyToMany) ou étudiants directs?
        // Le modèle actuel semble lier Etudiants au Groupe, et le Groupe au Cours (ou User -> Groupe -> User).
        
        // Simplification : On liste les étudiants du ou des groupes associés à l'enseignant pour ce cours.
        // Ou plus simple : on suppose que la séance a des étudiants "attendus".
        
        // Pour l'instant, on liste les présences enregistrées ET on essaie de lister les absents si on a la liste de la classe.
        // On va faire simple : PDF des PRÉSENTS pour l'instant, ou liste complète si on peut déduire la classe.
        
        // Récupérer les groupes du prof
        $user = Auth::user();
        $groupes = Groupe::where('user_id', $user->id)->with('etudiants')->get();
        // Filtrer les étudiants qui devraient être là ? Difficile sans lien Cours-Groupe explicite dans la séance.
        // On va prendre tous les étudiants des groupes du prof comme base, ou juste les présents.
        // Le mieux est de lister ceux qui ont pointé.
        
        $presences = Presences::where('seance_id', $seance->id)->with('etudiants')->get();
        
        $pdf = \PDF::loadView('enseignant.seances.pdf_presence', compact('seance', 'presences'));
        return $pdf->stream('feuille-presence-' . $seance->id . '.pdf');
    }
}
