# Gestion des Clubs - Application MVC en PHP

Cette application est un système de gestion des clubs universitaires développé en PHP natif suivant le modèle MVC (Modèle-Vue-Contrôleur). Elle permet aux étudiants de s'inscrire, de rejoindre des clubs, de participer à des activités, et aux administrateurs de gérer les clubs et les ressources.

## Fonctionnalités

- **Système d'authentification** : Inscription et connexion des étudiants et administrateurs
- **Gestion des clubs** : Création, modification et suppression de clubs
- **Gestion des activités** : Organisation et participation aux activités des clubs
- **Gestion des demandes** : Demandes d'adhésion aux clubs et création de nouveaux clubs
- **Tableau de bord** : Interfaces spécifiques pour étudiants, responsables de clubs et administrateurs
- **Blog et annonces** : Publication et consultation d'articles liés aux activités des clubs

## Architecture MVC

Le projet suit une architecture MVC (Modèle-Vue-Contrôleur) structurée comme suit :

- **Modèles** (`app/models/`) : Gestion des données et logique métier
- **Vues** (`app/views/`) : Interface utilisateur et présentation
- **Contrôleurs** (`app/controllers/`) : Gestion des requêtes et coordination
- **Helpers** (`app/helpers/`) : Fonctions d'aide, notamment pour la génération d'URLs

## Structure du projet

```
├── app/
│   ├── controllers/      # Contrôleurs de l'application
│   ├── core/             # Classes fondamentales du MVC
│   ├── models/           # Modèles de données
│   ├── views/            # Vues de l'interface utilisateur
│   └── helpers/          # Fonctions d'aide (ex: url_helper.php)
├── config/               # Fichiers de configuration
├── public/               # Ressources accessibles publiquement
│   ├── assets/
│   │   ├── css/          # Feuilles de style
│   │   ├── images/       # Images
│   │   └── js/           # Scripts JavaScript
├── .htaccess             # Configuration Apache
├── index.php             # Point d'entrée de l'application
└── gestion_clubs.sql     # Script SQL de la base de données
```

## Installation

1. Placez tous les fichiers dans votre serveur web (WAMP, XAMPP, etc.)
2. Importez la base de données en exécutant : 
   ```
   php import_database.php
   ```
3. Configurez les paramètres de connexion à la base de données dans `config/database.php` si nécessaire
4. Assurez-vous que le module de réécriture d'URL (mod_rewrite) est activé sur Apache

## Démarrage rapide

1. Démarrez votre serveur web (WAMP, XAMPP, etc.)
2. Accédez à l'application via votre navigateur: `http://localhost/sfe/`
3. Connectez-vous avec le compte administrateur par défaut:
   - Email: admin@example.com
   - Mot de passe: admin123

## Utilisation

1. Ouvrez l'application dans un navigateur web
2. Créez un compte étudiant ou connectez-vous en tant qu'administrateur
3. Explorez les fonctionnalités disponibles selon votre rôle

## Génération des URLs

L'application gère dynamiquement la génération des URLs pour s'adapter à différents environnements (local, production, tunnels de développement).

- Dans les **vues (.php)**, utilisez toujours la fonction `url()` pour générer des liens :
  ```php
  <a href="<?php echo url('chemin/vers/la/page'); ?>">Lien</a>
  ```
  Exemple : `url('login')` générera `http://localhost/sfe/login` en local ou `https://<tunnel_id>.devtunnels.ms/login` via un tunnel.

- Dans les **contrôleurs (.php)**, vous pouvez utiliser la méthode `baseUrl()` (disponible dans les classes héritant de `Controller`) pour obtenir l'URL de base, ou également la fonction `url()` si `app/helpers/url_helper.php` est inclus :
  ```php
  // Avec la méthode baseUrl() de la classe Controller
  $lienAbsolu = $this->baseUrl() . '/chemin/specifique';

  // Avec la fonction url()
  $lienAbsolu = url('chemin/specifique');
  ```

La logique de détection de l'URL de base prend en compte les en-têtes `HTTP_X_FORWARDED_HOST` et `HTTP_X_FORWARDED_PROTO` pour un fonctionnement correct derrière les proxys inverses ou les tunnels. Le fichier principal pour cette logique est `app/helpers/url_helper.php`.

## Exigences techniques

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Apache avec mod_rewrite activé

## Crédits

Développé par KHATTOU AYMEN dans le cadre d'un stage de fin d'études.
