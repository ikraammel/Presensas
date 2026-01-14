<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔐 Test de Connexion\n";
echo "====================\n\n";

// Test 1: Login 'admin'
$login = 'admin';
$password = 'password';

$user = User::where('login', $login)->first();

if (!$user) {
    echo "❌ Utilisateur '$login' NOT FOUND!\n";
    echo "   Les utilisateurs disponibles:\n";
    $users = User::all();
    foreach ($users as $u) {
        echo "   - {$u->login}\n";
    }
    exit(1);
}

echo "✅ Utilisateur trouvé: {$user->login}\n";
echo "   Nom: {$user->nom} {$user->prenom}\n";
echo "   Type: {$user->type}\n";

// Test 2: Vérification du mot de passe
$hash_in_db = $user->mdp;
echo "\n📝 Hash en BD: " . substr($hash_in_db, 0, 20) . "...\n";

$check_result = Hash::check($password, $hash_in_db);

if ($check_result) {
    echo "✅ Mot de passe CORRECT\n";
    echo "   Mot de passe '$password' correspond au hash\n";
} else {
    echo "❌ Mot de passe INCORRECT\n";
    echo "   Mot de passe '$password' ne correspond pas au hash\n";
    
    // Créer un nouveau hash pour tester
    $new_hash = Hash::make($password);
    echo "\n   Réinitialisation du mot de passe...\n";
    $user->mdp = $new_hash;
    $user->save();
    echo "   ✅ Mot de passe réinitialisé\n";
}

echo "\n====================\n";
echo "Testez maintenant la connexion avec:\n";
echo "  Login: $login\n";
echo "  Password: $password\n";
