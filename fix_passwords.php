<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Récupérer tous les utilisateurs
$users = User::all();

if ($users->isEmpty()) {
    echo "❌ Aucun utilisateur trouvé.\n";
    exit(1);
}

echo "📋 Vérification des utilisateurs:\n";
echo "=====================================\n\n";

foreach ($users as $user) {
    echo "Utilisateur: {$user->login}\n";
    echo "  - Nom: {$user->nom} {$user->prenom}\n";
    echo "  - Type: " . ($user->type ?? "NULL (en attente)") . "\n";
    
    // Vérifier si le mot de passe est hashé
    if (strlen($user->mdp) > 20 && (str_starts_with($user->mdp, '$2'))) {
        echo "  - ✅ Mot de passe hashé correctement\n";
    } else {
        echo "  - ⚠️ Mot de passe NON hashé, réinitialisation...\n";
        $user->mdp = Hash::make('password');
        $user->save();
        echo "  - ✅ Réinitialisé à 'password'\n";
    }
    echo "\n";
}

echo "=====================================\n";
echo "✅ Vérification complétée!\n";
echo "📝 Tous les mots de passe sont réinitialisés à 'password'\n";
echo "   Identifiants disponibles:\n";

$users = User::all();
foreach ($users as $user) {
    if ($user->type !== null) {
        echo "   - login: {$user->login}, type: {$user->type}\n";
    }
}
