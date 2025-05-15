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

## Structure du projet

```
├── app/
│   ├── controllers/      # Contrôleurs de l'application
│   ├── core/             # Classes fondamentales du MVC
│   ├── models/           # Modèles de données
│   └── views/            # Vues de l'interface utilisateur
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

1. Clonez le dépôt sur votre serveur web
2. Importez le fichier `gestion_clubs.sql` dans votre base de données MySQL
3. Configurez les paramètres de connexion à la base de données dans `config/database.php`
4. Configurez votre serveur web pour pointer vers le répertoire du projet
5. Assurez-vous que le module de réécriture d'URL (mod_rewrite) est activé sur Apache

Consultez le fichier `CONFIGURATION.md` pour des instructions détaillées sur la configuration du serveur web.

## Utilisation

1. Ouvrez l'application dans un navigateur web
2. Créez un compte étudiant ou connectez-vous en tant qu'administrateur
3. Explorez les fonctionnalités disponibles selon votre rôle

## Exigences techniques

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Apache avec mod_rewrite activé

## Crédits

Développé par [Votre Nom] dans le cadre d'un projet de fin d'études.
