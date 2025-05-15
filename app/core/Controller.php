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
    }
    
    /**
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
            require_once $viewFile;
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
     */
    protected function asset($path) {
        // Supprime le slash initial s'il existe
        $path = ltrim($path, '/');
        
        // Détermine si nous sommes à la racine ou dans un sous-dossier
        $baseUrl = '';
        
        // Retourne l'URL de la ressource
        return $baseUrl . '/public/' . $path;
    }
}
?>
