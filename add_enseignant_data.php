<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use App\Models\Cours;
use App\Models\Seances;
use App\Models\CoursUsers;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Récupérer les enseignants existants
$enseignants = User::where('type', 'enseignant')->get();

if ($enseignants->isEmpty()) {
    echo "❌ Aucun enseignant trouvé. Créez d'abord des enseignants avec le DemoSeeder.\n";
    exit(1);
}

// Récupérer les cours existants
$cours = Cours::all();

if ($cours->isEmpty()) {
    echo "❌ Aucun cours trouvé. Créez d'abord des cours avec le DemoSeeder.\n";
    exit(1);
}

// Associer les enseignants aux cours
foreach ($enseignants as $enseignant) {
    // Assigner 2-3 cours à chaque enseignant
    $coursAssignes = $cours->random(rand(2, 3));
    
    foreach ($coursAssignes as $c) {
        // Vérifier si la relation n'existe pas déjà
        $exists = CoursUsers::where('user_id', $enseignant->id)
            ->where('cours_id', $c->id)
            ->exists();
        
        if (!$exists) {
            CoursUsers::create([
                'user_id' => $enseignant->id,
                'cours_id' => $c->id,
            ]);
        }
    }
}

// Ajouter plus de séances (30 total)
$coursIds = $cours->pluck('id')->toArray();
$today = now();

for ($i = 0; $i < 30; $i++) {
    $date = $today->clone()->addDays(rand(1, 60));
    $heureDebut = rand(8, 16);
    
    Seances::create([
        'cours_id' => $coursIds[array_rand($coursIds)],
        'date_debut' => $date->setHour($heureDebut)->setMinute(0),
        'date_fin' => $date->clone()->setHour($heureDebut + 2)->setMinute(0),
        'type_seance' => ['Cours', 'TD', 'TP'][array_rand(['Cours', 'TD', 'TP'])],
    ]);
}

echo "✅ " . CoursUsers::count() . " associations enseignant-cours créées!\n";
echo "✅ Séances ajoutées (total: " . Seances::count() . ")\n";
echo "✅ Les enseignants peuvent maintenant voir leurs cours et séances!\n";
