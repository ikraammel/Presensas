<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== MODIFIER LE TYPE D'UN UTILISATEUR ===\n\n";

// Afficher tous les utilisateurs
$users = User::all();
echo "Utilisateurs disponibles:\n";
foreach ($users as $user) {
    echo "ID: {$user->id} - Login: {$user->login} - Nom: {$user->nom} {$user->prenom} - Type: " . ($user->type ?? 'NULL') . "\n";
}

echo "\nEntrez l'ID de l'utilisateur à modifier: ";
$userId = trim(fgets(STDIN));

$user = User::find($userId);
if (!$user) {
    echo "Utilisateur non trouvé!\n";
    exit;
}

echo "\nTypes disponibles: admin, enseignant, gestionnaire\n";
echo "Entrez le nouveau type: ";
$type = trim(fgets(STDIN));

if (!in_array($type, ['admin', 'enseignant', 'gestionnaire'])) {
    echo "Type invalide!\n";
    exit;
}

$user->type = $type;
$user->save();

echo "\n✓ Utilisateur modifié avec succès!\n";
echo "ID: {$user->id} - Login: {$user->login} - Type: {$user->type}\n";
