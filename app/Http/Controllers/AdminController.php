<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cours;
use App\Models\Seances;
use App\Models\Etudiants;
use App\Models\Presences;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Compter les utilisateurs non validés
        $usersNonValides = User::where('type', null)->count();
        
        // Compter les utilisateurs par type
        $totalUsers = User::count();
        $totalAdmins = User::where('type', 'admin')->count();
        $totalEnseignants = User::where('type', 'enseignant')->count();
        
        // Compter les étudiants
        $totalEtudiants = Etudiants::count();
        
        // Compter les cours/modules
        $totalCours = Cours::count();
        
        // Compter les séances
        $totalSeances = Seances::count();
        
        // Calculer le taux de présence
        $totalPresences = Presences::count();
        $presentCount = Presences::where('statut', 'present')->count();
        $tauxPresence = $totalPresences > 0 ? round(($presentCount / $totalPresences) * 100, 1) : 0;
        
        // Récupérer les dernières activités
        $dernieresSeances = Seances::orderBy('date_debut', 'desc')->limit(5)->get();
        
        // Récupérer les utilisateurs en attente de validation
        $utilisateursEnAttente = User::where('type', null)->limit(5)->get();
        
        return view('admin.home', [
            'usersNonValides' => $usersNonValides,
            'totalUsers' => $totalUsers,
            'totalAdmins' => $totalAdmins,
            'totalEnseignants' => $totalEnseignants,
            'totalEtudiants' => $totalEtudiants,
            'totalCours' => $totalCours,
            'totalSeances' => $totalSeances,
            'tauxPresence' => $tauxPresence,
            'dernieresSeances' => $dernieresSeances,
            'utilisateursEnAttente' => $utilisateursEnAttente,
        ]);
    }

    public function etudiants()
    {
        $totalEtudiants = Etudiants::count();
        $etudiants = Etudiants::paginate(15);
        
        return view('admin.etudiants', [
            'totalEtudiants' => $totalEtudiants,
            'etudiants' => $etudiants,
        ]);
    }

    public function enseignants()
    {
        $totalEnseignants = User::where('type', 'enseignant')->count();
        $enseignants = User::where('type', 'enseignant')->paginate(15);
        
        return view('admin.enseignants', [
            'totalEnseignants' => $totalEnseignants,
            'enseignants' => $enseignants,
        ]);
    }

    public function modules()
    {
        // Pour simplifier, on réutilise directement la page existante de gestion des cours
        // qui affiche la liste des modules et permet maintenant d'affecter un enseignant.
        return redirect()->route('admin.cours.index');
    }
}
