# Comment Vérifier la Base de Données et Voir les Utilisateurs

## Méthodes pour voir les utilisateurs dans la base de données

### 1. Utiliser la commande Artisan (Recommandé)
```bash
php artisan users:list
```
Cette commande affiche tous les utilisateurs dans un tableau formaté.

### 2. Utiliser le script PHP direct
```bash
php check_users.php
```
Ce script affiche la liste des utilisateurs dans la console.

### 3. Utiliser Tinker (Console interactive Laravel)
```bash
php artisan tinker
```
Puis dans tinker, tapez:
```php
User::all()
```
ou
```php
User::where('login', 'votre_login')->first()
```

### 4. Utiliser une application SQLite (optionnel)
Si vous avez SQLite Browser ou DB Browser for SQLite installé:
- Ouvrez le fichier: `database/database.sqlite`
- Naviguez vers la table `users`

### 5. Vérifier via une route de développement (si nécessaire)
Vous pouvez créer une route temporaire dans `routes/web.php`:
```php
Route::get('/dev/users', function() {
    return \App\Models\User::all();
});
```

## Résultat actuel
D'après la vérification, vous avez **3 utilisateurs** dans la base de données:
- ID 1: wiame (Erraoui Wiame) - Type: NULL
- ID 2: wiame1 (Erraoui Wiame) - Type: NULL  
- ID 3: sara (Hatimi Sara) - Type: NULL

**Note importante:** Tous les utilisateurs ont `type = NULL`, ce qui signifie qu'ils ne peuvent pas se connecter car le système nécessite que le type soit validé par un administrateur (admin, gestionnaire, enseignant, etc.).

Pour permettre la connexion d'un utilisateur, un administrateur doit modifier le champ `type` dans la base de données.
