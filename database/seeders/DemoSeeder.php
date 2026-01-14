<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cours;
use App\Models\Etudiants;
use App\Models\Seances;
use App\Models\Presences;
use App\Models\Groupe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('fr_FR');

        // Créer des utilisateurs administrateurs
        User::create([
            'nom' => 'Administrateur',
            'prenom' => 'Principal',
            'login' => 'admin',
            'mdp' => Hash::make('password'),
            'type' => 'admin',
        ]);

        // Créer des enseignants
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'nom' => $faker->lastName,
                'prenom' => $faker->firstName,
                'login' => "enseignant$i",
                'mdp' => Hash::make('password'),
                'type' => 'enseignant',
            ]);
        }

        // Créer des utilisateurs en attente de validation
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'nom' => $faker->lastName,
                'prenom' => $faker->firstName,
                'login' => "attente$i",
                'mdp' => Hash::make('password'),
                'type' => null,
            ]);
        }

        // Créer des gestionnaires
        User::create([
            'nom' => 'Gestionnaire',
            'prenom' => 'Principal',
            'login' => 'gestionnaire',
            'mdp' => Hash::make('password'),
            'type' => 'gestionnaire',
        ]);

        // Ajouter d'autres gestionnaires avec vrais noms
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'nom' => $faker->lastName,
                'prenom' => $faker->firstName,
                'login' => "gestionnaire$i",
                'mdp' => Hash::make('password'),
                'type' => 'gestionnaire',
            ]);
        }

        // Créer des groupes
        $groupe1 = Groupe::create(['nom' => 'Groupe 1']);
        $groupe2 = Groupe::create(['nom' => 'Groupe 2']);
        $groupe3 = Groupe::create(['nom' => 'Groupe 3']);

        // Créer des étudiants (Table Etudiants + Table Users pour login)
        for ($i = 1; $i <= 30; $i++) {
            $nom = $faker->lastName;
            $prenom = $faker->firstName;
            $noet = 'ET' . str_pad($i, 5, '0', STR_PAD_LEFT);
            $cne = 'CNE' . $faker->numberBetween(100000, 999999);

            // Création dans la table étudiants (données académiques)
            Etudiants::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'noet' => $noet,
                'cne' => $cne,
                'groupe_id' => ($i % 3) + 1,
            ]);

            // Création dans la table users (pour login)
            User::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'login' => "etudiant$i",
                'mdp' => Hash::make('password'),
                'type' => 'etudiant',
            ]);
        }

        // Créer des cours
        $coursTitles = [
            'Mathématiques',
            'Informatique',
            'Physique',
            'Chimie',
            'Biologie',
            'Histoire',
            'Géographie',
            'Anglais',
            'Français',
            'Éducation Physique',
            'Technologie',
            'Arts Plastiques',
        ];

        foreach ($coursTitles as $title) {
            Cours::create([
                'intitule' => $title,
            ]);
        }

        // Créer des séances
        $cours = Cours::all();
        for ($i = 0; $i < 30; $i++) {
            $courseIndex = $i % count($cours);
            Seances::create([
                'cours_id' => $cours[$courseIndex]->id,
                'date_debut' => now()->addDays($i)->toDateTimeString(),
                'date_fin' => now()->addDays($i)->addHours(2)->toDateTimeString(),
                'type' => 'Cours',
                'type_seance' => 'Normale',
                'qr_token' => null,
                'qr_expires_at' => null,
            ]);
        }

        // Créer des présences
        $seances = Seances::all();
        $etudiants = Etudiants::all();

        foreach ($seances as $seance) {
            foreach ($etudiants->random(10) as $etudiant) {
                Presences::create([
                    'etudiant_id' => $etudiant->id,
                    'seance_id' => $seance->id,
                    'statut' => rand(0, 1) ? 'present' : 'absent',
                    'justificatif' => null,
                    'date_enregistrement' => now()->toDateTimeString(),
                ]);
            }
        }
    }
}
