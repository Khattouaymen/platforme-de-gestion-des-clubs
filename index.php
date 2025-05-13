<?php
// Point d'entrée principal de l'application
session_start();

// Définition des constantes
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Inclure la configuration de la base de données
require_once(CONFIG_PATH . '/database.php');

// Chargement du router
require_once(APP_PATH . '/core/Router.php');

// Démarrer le routeur
$router = new Router();
$router->route();
?>
