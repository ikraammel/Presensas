<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Liste tous les utilisateurs de la base de données';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->info('Aucun utilisateur trouvé dans la base de données.');
            return 0;
        }

        $this->info('=== LISTE DES UTILISATEURS ===');
        $this->newLine();

        $headers = ['ID', 'Login', 'Nom', 'Prénom', 'Type'];
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                $user->id,
                $user->login,
                $user->nom,
                $user->prenom,
                $user->type ?? 'NULL (non validé)'
            ];
        }

        $this->table($headers, $data);
        $this->newLine();
        $this->info("Total: " . $users->count() . " utilisateur(s)");

        return 0;
    }
}
