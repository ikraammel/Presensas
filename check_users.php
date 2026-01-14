<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== LISTE DES UTILISATEURS ===\n\n";

$users = User::all();

if ($users->isEmpty()) {
    echo "Aucun utilisateur trouvé dans la base de données.\n";
} else {
    echo sprintf("%-5s %-20s %-20s %-15s %-10s\n", "ID", "LOGIN", "NOM", "PRENOM", "TYPE");
    echo str_repeat("-", 70) . "\n";
    
    foreach ($users as $user) {
        $type = $user->type ?? 'NULL';
        echo sprintf("%-5s %-20s %-20s %-15s %-10s\n", 
            $user->id, 
            $user->login, 
            $user->nom, 
            $user->prenom, 
            $type
        );
    }
    
    echo "\nTotal: " . $users->count() . " utilisateur(s)\n";
}
