<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::updateOrCreate(
    ['login' => 'ikramelhoul'],
    [
        'nom' => 'El houl',
        'prenom' => 'Ikram',
        'mdp' => Hash::make('password'),
        'type' => 'admin',
    ]
);

echo "✅ Admin ikramelhoul créé/validé avec succès!\n";
echo "Login: ikramelhoul\n";
echo "Mot de passe: password\n";
echo "Type: admin\n";
