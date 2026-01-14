<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Etudiants;
use App\Models\Groupe;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterUserController extends Controller
{
    public function showForm(){
        return view('auth.register');
    }

    public function store(Request $request){
        // Validation de base
        $rules = [
            'login' => 'required|string|max:255|unique:users',
            'nom'=>'required|string|max:255',
            'prenom'=>'required|string',
            'mdp' => 'required|string|confirmed', //|min:8
            'type' => 'required|string|in:etudiant,enseignant',
        ];

        // Si c'est un étudiant, ajouter les validations spécifiques
        if ($request->type === 'etudiant') {
            $rules['noet'] = 'required|string|max:255|unique:etudiants,noet';
            $rules['cne'] = 'required|string|max:255';
            $rules['niveau'] = 'required|string|in:CP1,CP2,GIIA1,GIIA2,GIIA3,GTR1,GTR2,GTR3,GPMA1,GPMA2,GPMA3,GATE1,GATE2,GATE3,GMSI1,GMSI2,GMSI3,GINDUS1,GINDUS2,GINDUS3,IDIA1,IDIA2';
        }

        $request->validate($rules);

        // Créer l'utilisateur
        $user = new User();
        $user->login = $request->login;
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->mdp = Hash::make($request->mdp);
        $user->type_demande = $request->type; // Stocker le type demandé
        $user->type = null; // Le compte est en attente de validation
        $user->save();

        // Si c'est un étudiant, créer l'entrée dans la table etudiants
        if ($request->type === 'etudiant') {
            // Récupérer ou créer le groupe correspondant au niveau
            $groupe = Groupe::firstOrCreate(
                ['nom' => $request->niveau],
                ['description' => 'Classe ' . $request->niveau]
            );

            // Créer l'étudiant
            Etudiants::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'noet' => $request->noet,
                'cne' => $request->cne,
                'groupe_id' => $groupe->id,
            ]);
        }

        session()->flash('etat','Inscription réussie ! Votre compte est en attente de validation par l\'administrateur.');

        return redirect()->route('login');
    }
}
