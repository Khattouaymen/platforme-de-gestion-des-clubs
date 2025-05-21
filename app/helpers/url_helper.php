<?php
/**
 * Helpers - Fonctions d'aide pour les vues
 */

/**
 * Génère une URL relative à la racine de l'application
 * 
 * @param string $path Chemin relatif
 * @return string URL absolue
 */
function url($path = '') {
    // Supprime le slash initial s'il existe
    $path = ltrim($path, '/');
    
    // Détection du protocole (http ou https)
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
                || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
                ? 'https' 
                : 'http';
      // Host inclut le nom d'hôte et le port si présent
    // Prioritize HTTP_X_FORWARDED_HOST if available (set by reverse proxies/tunnels)
    $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST'];
      // Détection automatique de l'environnement et construction de la baseUrl
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    // Si scriptName est juste '/' ou '\', on le traite comme la racine.
    if ($scriptName === '/' || $scriptName === '\\') {
        $scriptName = '';
    }
    $baseUrl = $protocol . '://' . $host . rtrim($scriptName, '/');
    
    // Retourne l'URL complète, en s'assurant qu'il n'y a pas de double slash si path est vide
    if (empty($path)) {
        return $baseUrl;
    }
    return $baseUrl . '/' . $path;
}
