# Configuration de l'Application de Gestion des Clubs

Ce guide vous aidera à configurer correctement l'application de gestion des clubs.

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web Apache avec mod_rewrite activé (ou serveur WAMP/XAMPP)
  OU
- PHP avec son serveur web intégré (pour le développement)

## Option 1: Configuration avec le serveur PHP intégré (Recommandé pour le développement)

Pour une configuration rapide et simple, vous pouvez utiliser le serveur web intégré de PHP:

1. Importez la base de données:
   ```powershell
   C:\wamp64\bin\php\php8.2.26\php.exe import_database.php
   ```

2. Démarrez le serveur PHP:
   ```powershell
   C:\wamp64\bin\php\php8.2.26\php.exe -S localhost:8000 router.php
   ```

3. Accédez à l'application via: http://localhost:8000

Pour plus de détails, consultez le fichier `README.server.md`.

## Option 2: Configuration avec WAMP ou XAMPP

### Étape 1: Installation de l'application

1. Placez tous les fichiers de l'application dans le répertoire `www` de WAMP ou `htdocs` de XAMPP
2. Si vous utilisez un sous-dossier (par exemple `/sfe/`), notez ce chemin pour les étapes suivantes

### Étape 2: Importation de la base de données

Option 1: Utiliser le script d'importation automatique
```
cd C:/wamp64/www/sfe  # Ajustez selon votre emplacement
C:/wamp64/bin/php/php8.2.26/php.exe import_database.php  # Utilisez la version correcte de PHP
```

Option 2: Importation manuelle via phpMyAdmin
1. Accédez à phpMyAdmin (http://localhost/phpmyadmin/)
2. Créez une nouvelle base de données nommée `gestion_clubs`
3. Importez le fichier `gestion_clubs.sql`

### Étape 3: Configuration de la base de données

Vérifiez et modifiez si nécessaire le fichier `config/database.php` :
```php
<?php
define('DB_HOST', 'localhost'); 
define('DB_NAME', 'gestion_clubs');
define('DB_USER', 'root');     // Modifiez selon votre configuration
define('DB_PASS', '');         // Modifiez selon votre configuration
?>
```

### Étape 4: Vérification de l'accès

1. Assurez-vous que les services Apache et MySQL sont démarrés
2. Accédez à l'application via votre navigateur:
   - Si à la racine: http://localhost/
   - Si dans un sous-dossier: http://localhost/sfe/

## Résolution des problèmes

### Problème de réécriture d'URL

Si vous rencontrez des erreurs 404 ou de routage:
1. Vérifiez que le module rewrite d'Apache est activé
   - Dans WAMP: Clic gauche sur l'icône WAMP > Apache > Modules > rewrite_module
   - Dans XAMPP: Ouvrez le fichier httpd.conf et décommentez la ligne `LoadModule rewrite_module modules/mod_rewrite.so`

2. Vérifiez les fichiers .htaccess à la racine et dans le dossier public

### Problèmes d'accès aux ressources statiques

Si les styles CSS ou images ne se chargent pas:
1. Vérifiez que les chemins dans le fichier `app/core/Controller.php` sont corrects
2. Assurez-vous que la fonction `asset()` retourne des chemins accessibles

## Identifiants par défaut

Après l'installation, vous pouvez vous connecter avec:
- Email: admin@example.com
- Mot de passe: admin123
