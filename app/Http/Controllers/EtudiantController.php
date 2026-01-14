<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Seances;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtudiantController extends Controller
{
    // Dashboard étudiant
    public function index(){
        $user = Auth::user();
        
        // Pour l'étudiant, on récupère ses cours via la table cours_etudiants
        // Pour l'instant, on retourne un dashboard simple
        return view('etudiant.home', [
            'user' => $user,
        ]);
    }
}
