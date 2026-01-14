<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Seances;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EtudiantController extends Controller
{
    // Dashboard étudiant
    public function index()
    {
        $user = Auth::user();

        // Trouver l'étudiant correspondant (via nom/prénom)
        $etudiant = \App\Models\Etudiants::where('nom', $user->nom)
            ->where('prenom', $user->prenom)
            ->with(['groupe.user', 'groupe.cours', 'cours']) // Load Group/Teacher, Group Courses, Direct Courses
            ->first();

        return view('etudiant.home', [
            'user' => $user,
            'etudiant' => $etudiant
        ]);
    }

    public function showProfil()
    {
        $user = Auth::user();
        return view('etudiant.account.profil', ['user' => $user]);
    }

    public function editFormMdp()
    {
        return view('etudiant.account.editMdp');
    }

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
            return redirect()->route('etudiant.home');
        }
        $request->session()->flash('etat', 'votre mot de passe n\'est pas correct, Veuillez réessayer');

        return redirect()->route('etudiant.home');
    }
    // Lister les absences
    public function mesAbsences()
    {
        $user = Auth::user();
        // Trouver l'étudiant correspondant (via nom/prénom car pas de lien direct ID)
        $etudiant = \App\Models\Etudiants::where('nom', $user->nom)
            ->where('prenom', $user->prenom)
            ->first();

        if (!$etudiant) {
            return back()->withErrors(['error' => 'Profil étudiant introuvable.']);
        }

        $presences = \App\Models\Presences::where('etudiant_id', $etudiant->id)
            ->where('statut', 'absent')
            ->with(['seances.cours']) // Charger les relations pour l'affichage
            ->orderBy('date_enregistrement', 'desc')
            ->get();

        return view('etudiant.absences.index', [
            'presences' => $presences,
            'user' => $user
        ]);
    }

    // Formulaire pour justifier
    public function formJustificatif($id)
    {
        $presence = \App\Models\Presences::with('seances.cours')->findOrFail($id);
        return view('etudiant.absences.justifier', ['presence' => $presence]);
    }

    // Traitement du justificatif
    public function envoyerJustificatif(Request $request, $id)
    {
        $request->validate([
            'justificatif' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'commentaire' => 'nullable|string' // Si on veut ajouter un commentaire
        ]);

        $presence = \App\Models\Presences::findOrFail($id);

        if ($request->hasFile('justificatif')) {
            $file = $request->file('justificatif');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('justificatifs', $filename, 'public');

            $presence->justificatif = $path;
            $presence->statut_justificatif = 'en_attente';
            $presence->save();

            $request->session()->flash('etat', 'Justificatif envoyé avec succès.');
        }

        return redirect()->route('etudiant.absences.liste');
    }

    // Afficher les détails d'un module et ses documents
    public function showModule($id)
    {
        $user = Auth::user();
        // Récupérer l'étudiant
        $etudiant = \App\Models\Etudiants::where('nom', $user->nom)->where('prenom', $user->prenom)->firstOrFail();

        // Vérifier si l'étudiant a ce cours (soit directement via cours_etudiants, soit via son groupe)
        // Check direct enrollment
        $cours = $etudiant->cours()->where('cours.id', $id)->with('documents')->first();

        // If not found, check via group
        if (!$cours && $etudiant->groupe) {
            $cours = $etudiant->groupe->cours()->where('cours.id', $id)->with('documents')->first();
        }

        // If still not found, check via group teacher (courses owned by the teacher of the group)
        if (!$cours && $etudiant->groupe && $etudiant->groupe->user) {
            $cours = $etudiant->groupe->user->cours()->where('cours.id', $id)->with('documents')->first();
        }

        if (!$cours) {
            abort(403, 'Accès non autorisé à ce module.');
        }

        return view('etudiant.modules.show', compact('cours'));
    }

    // Afficher le QR Code de l'étudiant
    public function showQRCode()
    {
        $user = Auth::user();
        $etudiant = \App\Models\Etudiants::where('nom', $user->nom)->where('prenom', $user->prenom)->firstOrFail();

        return view('etudiant.qrcode', compact('etudiant'));
    }

    // Afficher les séances de l'étudiant
    public function mesSeances()
    {
        $user = Auth::user();
        $etudiant = \App\Models\Etudiants::where('nom', $user->nom)
            ->where('prenom', $user->prenom)
            ->with('groupe')
            ->first();

        if (!$etudiant) {
            return back()->withErrors(['error' => 'Profil étudiant introuvable.']);
        }

        // Récupérer les séances de la classe de l'étudiant
        $seances = \App\Models\Seances::where('groupe_id', $etudiant->groupe_id)
            ->with(['cours', 'groupe'])
            ->orderBy('date_debut', 'desc')
            ->get();

        // Charger les présences pour chaque séance
        $seanceIds = $seances->pluck('id')->toArray();
        $presences = \App\Models\Presences::where('etudiant_id', $etudiant->id)
            ->whereIn('seance_id', $seanceIds)
            ->get()
            ->keyBy('seance_id');

        // Attacher les présences aux séances
        foreach ($seances as $seance) {
            $seance->presence = $presences->get($seance->id);
        }

        return view('etudiant.seances.index', [
            'seances' => $seances,
            'etudiant' => $etudiant,
            'user' => $user
        ]);
    }

    // Afficher une séance spécifique avec QR code
    public function showSeance($id)
    {
        $user = Auth::user();
        $etudiant = \App\Models\Etudiants::where('nom', $user->nom)
            ->where('prenom', $user->prenom)
            ->first();

        if (!$etudiant) {
            return back()->withErrors(['error' => 'Profil étudiant introuvable.']);
        }

        $seance = \App\Models\Seances::with(['cours', 'groupe'])->findOrFail($id);

        // Vérifier que la séance appartient à la classe de l'étudiant
        if ($seance->groupe_id != $etudiant->groupe_id) {
            abort(403, 'Vous n\'avez pas accès à cette séance.');
        }

        // Vérifier si l'étudiant a déjà marqué sa présence
        $presence = \App\Models\Presences::where('seance_id', $id)
            ->where('etudiant_id', $etudiant->id)
            ->first();

        // Générer le QR code si la séance est en cours
        $canScan = false;
        $qrUrl = null;
        if ($seance->qr_token && now() >= $seance->date_debut && now() <= $seance->date_fin) {
            $canScan = true;
            $qrUrl = route('scan.qr') . '?token=' . $seance->qr_token;
        }

        return view('etudiant.seances.show', [
            'seance' => $seance,
            'etudiant' => $etudiant,
            'presence' => $presence,
            'canScan' => $canScan,
            'qrUrl' => $qrUrl
        ]);
    }
}
