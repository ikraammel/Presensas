# Comment Accéder à la Base de Données SQLite pour Valider les Utilisateurs

## Méthode 1 : DB Browser for SQLite (Recommandé - Interface Graphique)

### Installation :
1. Téléchargez **DB Browser for SQLite** depuis : https://sqlitebrowser.org/
2. Installez l'application
3. Ouvrez DB Browser for SQLite

### Utilisation :
1. Cliquez sur **"Ouvrir une base de données"**
2. Naviguez vers : `C:\Users\wiame\Desktop\ENSASPRESAPP\Presensas\database\database.sqlite`
3. Cliquez sur l'onglet **"Parcourir les données"**
4. Sélectionnez la table **"users"**
5. Vous verrez tous les utilisateurs avec leurs champs (id, login, nom, prenom, mdp, type, etc.)
6. **Pour valider un utilisateur :**
   - Double-cliquez sur le champ **"type"** de l'utilisateur que vous voulez valider
   - Changez `NULL` en `admin`, `enseignant`, ou `gestionnaire`
   - Appuyez sur **Entrée**
   - Cliquez sur **"Écrire les modifications"** (icône de disquette)
   - Confirmez les modifications

### Exemple :
Pour valider l'utilisateur ID 1 (wiame) en tant qu'admin :
- Trouvez la ligne avec `id = 1`
- Double-cliquez sur `type` (qui est `NULL`)
- Tapez `admin`
- Sauvegardez

## Méthode 2 : Extension VS Code (Si vous utilisez VS Code)

### Installation :
1. Dans VS Code, allez dans Extensions (Ctrl+Shift+X)
2. Recherchez "SQLite" ou "SQLite Viewer"
3. Installez une extension comme "SQLite Viewer" ou "SQLite"

### Utilisation :
1. Ouvrez le fichier : `database/database.sqlite`
2. L'extension devrait afficher une interface pour visualiser les tables
3. Vous pouvez modifier les données directement

## Méthode 3 : Extension PHPStorm (Si vous utilisez PHPStorm)

PHPStorm a un support intégré pour SQLite. Ouvrez simplement le fichier `database.sqlite` et vous pourrez voir les tables.

## Méthode 4 : SQLite Online (Navigateur Web)

Vous pouvez utiliser des outils en ligne comme :
- https://sqliteviewer.app/
- https://inloop.github.io/sqlite-viewer/

**Note :** Pour des raisons de sécurité, ne téléversez pas votre base de données de production sur des sites web.

## Méthode 5 : Script PHP Simple

Créez un fichier `modify_user.php` dans la racine du projet :

```php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

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
```

Puis exécutez :
```bash
php modify_user.php
```

## Méthode la Plus Simple : Commande Artisan

Utilisez simplement la commande que nous avons créée :
```bash
php artisan user:validate 1 admin
```

## Emplacement de la Base de Données

Le fichier SQLite se trouve à :
```
C:\Users\wiame\Desktop\ENSASPRESAPP\Presensas\database\database.sqlite
```

## Structure de la Table users

La table `users` a les colonnes suivantes :
- `id` : Identifiant unique
- `login` : Nom d'utilisateur (utilisé pour la connexion)
- `nom` : Nom de famille
- `prenom` : Prénom
- `mdp` : Mot de passe (hashé)
- `type` : Type d'utilisateur (`NULL`, `admin`, `enseignant`, `gestionnaire`)
- `email` : Email (peut être NULL)
- `grade` : Grade (peut être NULL)
- `remember_token` : Token de session
- `created_at` : Date de création
- `updated_at` : Date de mise à jour

## Recommandation

Je recommande **DB Browser for SQLite** car c'est :
- ✅ Gratuit
- ✅ Open source
- ✅ Interface graphique intuitive
- ✅ Fonctionne sur Windows
- ✅ Pas besoin de ligne de commande
