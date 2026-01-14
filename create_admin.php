<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== CRÉATION D'UN ADMINISTRATEUR ===\n\n";

// Créer un admin par défaut
$login = 'admin';
$nom = 'Admin';
$prenom = 'System';
$password = 'admin123'; // Changez ce mot de passe après la première connexion

// Vérifier si l'admin existe déjà
if (User::where('login', $login)->exists()) {
    echo "Un utilisateur avec le login '{$login}' existe déjà.\n";
    echo "Voulez-vous le convertir en admin? (y/n): ";
    $answer = trim(fgets(STDIN));
    
    if (strtolower($answer) === 'y') {
        $user = User::where('login', $login)->first();
        $user->type = 'admin';
        $user->save();
        echo "\n✓ Utilisateur converti en admin avec succès!\n";
        echo "Login: {$user->login} - Type: {$user->type}\n";
        exit;
    } else {
        echo "Opération annulée.\n";
        exit;
    }
}

// Créer le nouvel admin
$user = new User();
$user->login = $login;
$user->nom = $nom;
$user->prenom = $prenom;
$user->mdp = Hash::make($password);
$user->type = 'admin';
$user->save();

echo "✓ Administrateur créé avec succès!\n\n";
echo "Informations de connexion:\n";
echo "==========================\n";
echo "Login: {$login}\n";
echo "Mot de passe: {$password}\n";
echo "Type: admin\n\n";
echo "⚠️  IMPORTANT: Changez ce mot de passe après la première connexion!\n";
