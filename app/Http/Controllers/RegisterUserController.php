<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    public function showForm(){
        return view('auth.register');
    }

    public function store(Request $request){
        $request->validate([
            'login' => 'required|string|max:255|unique:users',
            'nom'=>'required|string|max:255',
            'prenom'=>'required|string',
            'mdp' => 'required|string|confirmed', //|min:8
            'type' => 'required|string|in:etudiant,enseignant',
        ]);

        $user = new User();
        $user->login = $request->login;
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->mdp = Hash::make($request->mdp);
        $user->type_demande = $request->type; // Stocker le type demandé
        $user->type = null; // Le compte est en attente de validation
        $user->save();

        session()->flash('etat','Inscription réussie ! Votre compte est en attente de validation par l\'administrateur.');

        //Auth::login($user);

        //return redirect(RouteServiceProvider::HOME);
        return redirect()->route('login');
    }
}
