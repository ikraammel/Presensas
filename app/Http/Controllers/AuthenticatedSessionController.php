<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    // login action
    public function login(Request $request)
    {

        $request->validate([
            'login' => 'required|string',
            'mdp' => 'required|string'
        ]);

        // Récupérer l'utilisateur par le champ login
        $user = User::where('login', $request->input('login'))->first();

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user && Hash::check($request->input('mdp'), $user->mdp)) {
            
            // Vérifier si le compte est validé (type n'est pas null)
            if ($user->type == NULL) {
                return back()->withErrors([
                    'login' => 'Votre compte n\'a pas encore été validé.',
                ]);
            }

            // Connecter l'utilisateur
            Auth::login($user);
            $request->session()->regenerate();

            session()->flash('etat', 'Connexion réussie');

            // Rediriger selon le type
            if ($user->type == 'admin') {
                return redirect()->intended('/admin');
            } elseif ($user->type == 'enseignant') {
                return redirect()->intended('/enseignant');
            } elseif ($user->type == 'etudiant') {
                return redirect()->intended('/etudiant');
            }
            
            return redirect()->route('home');
        }

        return back()->withErrors([
            'login' => 'Les informations de connexion sont incorrectes.',
        ]);
    }


    // logout action
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
