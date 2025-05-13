<?php
/**
 * Classe Router - Gère le routage des requêtes HTTP
 */
class Router {
    /**
     * Route la requête vers le contrôleur et l'action appropriés
     */
    public function route() {
        // URL par défaut
        $controller = 'Home';
        $action = 'index';
        $params = [];

        // Récupération de l'URL
        $url = $this->getUrl();

        // Analyser l'URL
        if (isset($url[0]) && !empty($url[0])) {
            $controller = ucfirst($url[0]);
            unset($url[0]);
        }

        // Vérifier si le fichier du contrôleur existe
        $controllerFile = APP_PATH . '/controllers/' . $controller . 'Controller.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controllerName = $controller . 'Controller';
            $controllerInstance = new $controllerName();
            
            // Vérifier si l'action est spécifiée
            if (isset($url[1]) && !empty($url[1])) {
                $action = $url[1];
                unset($url[1]);
            }
            
            // Vérifier si la méthode existe dans le contrôleur
            if (method_exists($controllerInstance, $action)) {
                // Paramètres restants
                $params = $url ? array_values($url) : [];
                
                // Appeler l'action avec les paramètres
                call_user_func_array([$controllerInstance, $action], $params);
            } else {
                // Action non trouvée
                $this->handleError("La méthode {$action} n'existe pas dans le contrôleur {$controllerName}");
            }
        } else {
            // Contrôleur non trouvé
            $this->handleError("Le contrôleur {$controller} n'existe pas");
        }
    }
    
    /**
     * Récupère l'URL demandée et la divise en segments
     * 
     * @return array Segments de l'URL
     */
    private function getUrl() {
        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        
        return [];
    }
    
    /**
     * Gère les erreurs de routage
     * 
     * @param string $message Message d'erreur
     */
    private function handleError($message) {
        require_once APP_PATH . '/controllers/ErrorController.php';
        $errorController = new ErrorController();
        $errorController->index($message);
    }
}
?>
