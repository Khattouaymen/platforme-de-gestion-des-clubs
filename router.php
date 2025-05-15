<?php
// router.php - Fichier de routage pour le serveur PHP intégré
// Utilisé avec: php -S localhost:8000 router.php

// Récupérer le chemin de la requête
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Vérifier si le fichier demandé existe
$requestedFile = __DIR__ . $uri;

// Pour les ressources statiques, servir directement le fichier
if ($uri !== '/' && file_exists($requestedFile) && !is_dir($requestedFile)) {
    // Déterminer le type MIME
    $extension = pathinfo($requestedFile, PATHINFO_EXTENSION);
    switch ($extension) {
        case 'css':
            header('Content-Type: text/css');
            break;
        case 'js':
            header('Content-Type: application/javascript');
            break;
        case 'jpg':
        case 'jpeg':
            header('Content-Type: image/jpeg');
            break;
        case 'png':
            header('Content-Type: image/png');
            break;
        case 'gif':
            header('Content-Type: image/gif');
            break;
        case 'svg':
            header('Content-Type: image/svg+xml');
            break;
    }
    
    // Servir le fichier
    readfile($requestedFile);
    return true;
}

// Rediriger toutes les autres requêtes vers index.php
require_once __DIR__ . '/index.php';
