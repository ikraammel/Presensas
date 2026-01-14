<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin {login : Le login de l\'administrateur} {nom : Le nom} {prenom : Le prénom} {--password= : Le mot de passe (optionnel)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crée un utilisateur administrateur';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $login = $this->argument('login');
        $nom = $this->argument('nom');
        $prenom = $this->argument('prenom');

        // Vérifier si l'utilisateur existe déjà
        if (User::where('login', $login)->exists()) {
            $this->error("Un utilisateur avec le login '{$login}' existe déjà.");
            return 1;
        }

        // Demander le mot de passe si non fourni
        $password = $this->option('password');
        if (!$password) {
            $password = $this->secret('Entrez le mot de passe');
            $passwordConfirmation = $this->secret('Confirmez le mot de passe');
            
            if ($password !== $passwordConfirmation) {
                $this->error("Les mots de passe ne correspondent pas.");
                return 1;
            }
        }

        // Créer l'utilisateur admin
        $user = new User();
        $user->login = $login;
        $user->nom = $nom;
        $user->prenom = $prenom;
        $user->mdp = Hash::make($password);
        $user->type = 'admin';
        $user->save();

        $this->info("✓ Administrateur créé avec succès!");
        $this->table(
            ['ID', 'Login', 'Nom', 'Prénom', 'Type'],
            [[$user->id, $user->login, $user->nom, $user->prenom, $user->type]]
        );

        return 0;
    }
}
