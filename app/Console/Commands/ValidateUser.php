<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ValidateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:validate {id : L\'ID de l\'utilisateur} {type : Le type (admin, enseignant, gestionnaire)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Valide un utilisateur en lui attribuant un type';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        $type = $this->argument('type');

        // Vérifier que le type est valide
        $validTypes = ['admin', 'enseignant', 'gestionnaire'];
        if (!in_array($type, $validTypes)) {
            $this->error("Type invalide. Types autorisés: " . implode(', ', $validTypes));
            return 1;
        }

        // Récupérer l'utilisateur
        $user = User::find($id);
        
        if (!$user) {
            $this->error("Utilisateur avec l'ID {$id} non trouvé.");
            return 1;
        }

        // Afficher les informations avant la modification
        $this->info("Utilisateur trouvé:");
        $this->table(
            ['ID', 'Login', 'Nom', 'Prénom', 'Type actuel'],
            [[$user->id, $user->login, $user->nom, $user->prenom, $user->type ?? 'NULL']]
        );

        // Confirmer la modification
        if ($this->confirm("Voulez-vous valider cet utilisateur en tant que '{$type}'?")) {
            $user->type = $type;
            $user->save();
            
            $this->info("✓ Utilisateur validé avec succès!");
            $this->table(
                ['ID', 'Login', 'Nom', 'Prénom', 'Nouveau Type'],
                [[$user->id, $user->login, $user->nom, $user->prenom, $user->type]]
            );
            
            return 0;
        }

        $this->info("Opération annulée.");
        return 0;
    }
}
