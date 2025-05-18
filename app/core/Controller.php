<?php
/**
 * Classe Controller - Contrôleur de base pour tous les contrôleurs
 */
class Controller {
    /**
     * Charge et instancie un modèle
     * 
     * @param string $model Nom du modèle
     * @return object Instance du modèle
     */
    protected function loadModel($model) {
        // Charge le fichier du modèle
        $modelFile = APP_PATH . '/models/' . $model . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        }
        
        return null;
    }    /**
     * Charge une vue avec des données
     * 
     * @param string $view Chemin de la vue
     * @param array $data Données à passer à la vue
     * @return void
     */
    protected function view($view, $data = []) {
        // Extraire les données pour les rendre accessibles dans la vue
        extract($data);
        
        // Chemin complet de la vue
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            // Démarrer la mise en mémoire tampon
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            
            // Définir une fonction asset pour les fichiers statiques
            $asset = function($path) {
                return '/public/' . ltrim($path, '/');
            };
            
            // Définir un titre par défaut
            $title = $title ?? 'Gestion des Clubs';
            
            // Charger le layout principal
            require APP_PATH . '/views/layouts/main.php';
        } else {
            die("La vue '{$view}' n'existe pas");
        }
    }
      /**
     * Redirige vers une autre URL
     * 
     * @param string $url URL de redirection
     * @return void
     */
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
      /**
     * Génère l'URL d'une ressource (asset)
     * 
     * @param string $path Chemin relatif vers la ressource
     * @return string URL de la ressource
     */    protected function asset($path) {
        // Supprime le slash initial s'il existe
        $path = ltrim($path, '/');
        
        // Détermine le chemin de base selon l'environnement (Apache ou serveur intégré)
        $baseUrl = '';
        
        // Détection automatique de l'environnement
        if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Development Server') !== false) {
            // Serveur de développement PHP intégré
            $baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
        } else {
            // Serveur Apache ou autre
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $scriptName = dirname($_SERVER['SCRIPT_NAME']);
            $baseUrl = $protocol . '://' . $host . rtrim($scriptName, '/');
        }
        
        // Retourne l'URL de la ressource
        return $baseUrl . '/public/' . $path;
    }
}
?>
