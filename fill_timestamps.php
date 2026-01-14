<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Cours;
use Illuminate\Support\Carbon;

// Récupérer tous les cours avec des timestamps null ou vides
$cours = Cours::query()
    ->where(function($q) {
        $q->whereNull('created_at')
          ->orWhere('created_at', '')
          ->orWhereNull('updated_at')
          ->orWhere('updated_at', '');
    })
    ->get();

echo "Cours avec timestamps manquants : " . count($cours) . "\n\n";

$count = 0;
foreach ($cours as $cour) {
    if (is_null($cour->created_at) || empty($cour->created_at)) {
        $cour->created_at = Carbon::now();
        $count++;
    }
    if (is_null($cour->updated_at) || empty($cour->updated_at)) {
        $cour->updated_at = Carbon::now();
        $count++;
    }
    $cour->save();
    echo "✓ Cours #" . $cour->id . " (" . $cour->intitule . ") mis à jour\n";
}

echo "\n✅ " . count($cours) . " cours ont été mises à jour avec les timestamps!\n";
