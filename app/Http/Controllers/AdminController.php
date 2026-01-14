<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $totalGestionnaires = User::where('type', 'gestionnaire')->count();
        
        return view('admin.home', [
            'usersNonValides' => $usersNonValides,
            'totalUsers' => $totalUsers,
            'totalAdmins' => $totalAdmins,
            'totalEnseignants' => $totalEnseignants,
            'totalGestionnaires' => $totalGestionnaires,
        ]);
    }
}
