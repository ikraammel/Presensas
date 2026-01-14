<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GestionnaireController extends Controller
{
    public function index()
    {
        return view('gestionnaire.home');
    }

    public function showProfil()
    {
        $user = Auth::user();
        return view('gestionnaire.account.profil', ['user' => $user]);
    }

    public function editFormMdp()
    {
        return view('gestionnaire.account.editMdp');
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
            return redirect()->route('gestionnaire.home');
        }
        $request->session()->flash('etat', 'votre mot de passe n\'est pas correct, Veuillez réessayer');

        return redirect()->route('gestionnaire.home');
    }
}
