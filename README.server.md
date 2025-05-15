# Utilisation de l'application avec le serveur PHP intégré

Ce guide vous explique comment utiliser l'application de gestion des clubs avec le serveur PHP intégré, qui est particulièrement utile pour le développement et les tests.

## Configuration requise

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Modules PHP : PDO, mysqli

## Étapes de mise en place

### 1. Importez la base de données

Avant de lancer le serveur, assurez-vous que la base de données est importée :

```powershell
C:\wamp64\bin\php\php8.2.26\php.exe import_database.php
```

### 2. Démarrez le serveur PHP intégré

Utilisez la commande suivante pour démarrer le serveur PHP intégré à partir du dossier racine du projet :

```powershell
C:\wamp64\bin\php\php8.2.26\php.exe -S localhost:8000 router.php
```

Le fichier `router.php` est crucial car il gère correctement la redirection des requêtes vers les fichiers statiques (CSS, JS, images) ou vers le contrôleur approprié.

### 3. Accédez à l'application

Ouvrez votre navigateur et accédez à :

```
http://localhost:8000
```

### Authentification

Après l'installation, vous pouvez vous connecter avec le compte administrateur par défaut :
- Email : admin@example.com
- Mot de passe : admin123

## Avantages du serveur PHP intégré

- Aucune installation ou configuration d'Apache nécessaire
- Affichage direct des erreurs dans la console
- Rechargement automatique des fichiers modifiés
- Idéal pour le développement rapide

## Résolution des problèmes

### Les styles CSS ne s'affichent pas

Vérifiez que le fichier `router.php` est bien configuré pour servir les fichiers statiques. Les requêtes pour les fichiers CSS, JS et images doivent être correctement gérées.

### Erreurs 404

Si vous rencontrez des erreurs 404, assurez-vous que tous les chemins dans l'application utilisent la fonction `asset()` pour les ressources statiques, et que les liens de navigation utilisent des URLs relatives ou sont générés dynamiquement.

### Problèmes de connexion à la base de données

Vérifiez les paramètres de connexion dans `config/database.php` et assurez-vous que le serveur MySQL est en cours d'exécution.
