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
        
        // Routes spéciales
        if (isset($url[0]) && $url[0] === 'login') {
            require_once APP_PATH . '/controllers/HomeController.php';
            $controllerInstance = new HomeController();
            call_user_func([$controllerInstance, 'login']);
            return;
        }
        
        // Route pour l'inscription - redirige vers login qui contient maintenant les deux formulaires
        if (isset($url[0]) && $url[0] === 'register') {
            require_once APP_PATH . '/controllers/HomeController.php';
            $controllerInstance = new HomeController();
            call_user_func([$controllerInstance, 'register']);
            return;
        }        
        // Route pour le tableau de bord admin
        if (isset($url[0]) && $url[0] === 'admin') {
            require_once APP_PATH . '/controllers/AdminController.php';
            $controllerInstance = new AdminController();
            
            // Vérifier si l'action principale est spécifiée (admin/<action>)
            if (isset($url[1]) && !empty($url[1])) {
                $action = $url[1];
                
                // Traitement spécial pour les actions de gestion des clubs
                if ($action === 'clubs') {
                    // Vérifier s'il y a une sous-action (admin/clubs/<sous-action>)
                    if (isset($url[2]) && !empty($url[2])) {
                        $subAction = $url[2];
                        
                        // Sous-actions pour les clubs
                        switch ($subAction) {
                            case 'add':
                                call_user_func([$controllerInstance, 'addClub']);
                                return;
                            
                            case 'edit':
                                // Vérifier s'il y a un ID de club (admin/clubs/edit/<id>)
                                if (isset($url[3]) && !empty($url[3])) {
                                    call_user_func([$controllerInstance, 'editClub'], $url[3]);
                                } else {
                                    $this->handleError("ID de club non spécifié pour l'édition");
                                }
                                return;
                            
                            case 'delete':
                                // Vérifier s'il y a un ID de club (admin/clubs/delete/<id>)
                                if (isset($url[3]) && !empty($url[3])) {
                                    call_user_func([$controllerInstance, 'deleteClub'], $url[3]);
                                } else {
                                    $this->handleError("ID de club non spécifié pour la suppression");
                                }
                                return;
                                
                            case 'get':
                                // Vérifier s'il y a un ID de club (admin/clubs/get/<id>)
                                if (isset($url[3]) && !empty($url[3])) {
                                    call_user_func([$controllerInstance, 'getClub'], $url[3]);
                                } else {
                                    $this->handleError("ID de club non spécifié pour la récupération");
                                }
                                return;
                        }
                    }
                    
                    // Si aucune sous-action, afficher la page de gestion des clubs
                    call_user_func([$controllerInstance, 'clubs']);
                    return;
                } // End of if ($action === 'clubs') block

                // Vérifier si la méthode existe dans le contrôleur pour les autres actions
                if (method_exists($controllerInstance, $action)) {
                    // Paramètres restants
                    $params = [];
                    if (isset($url[2])) {
                        for ($i = 2; $i < count($url); $i++) {
                            $params[] = $url[$i];
                        }
                    }
                    
                    // Appeler l'action avec les paramètres
                    call_user_func_array([$controllerInstance, $action], $params);
                    return;
                }
            } else {
                // Action par défaut (tableau de bord)
                call_user_func([$controllerInstance, 'index']);
                return;
            }
            
            // Si la méthode n'existe pas, rediriger vers la page d'erreur
            $this->handleError("La méthode {$action} n'existe pas dans le contrôleur AdminController");
            return;
        }
        
        // Route d'authentification
        if (isset($url[0]) && $url[0] === 'auth') {
            require_once APP_PATH . '/controllers/AuthController.php';
            $controllerInstance = new AuthController();
            
            // Cas spécial pour l'inscription des responsables
            if (isset($url[1]) && $url[1] === 'register' && isset($url[2]) && $url[2] === 'responsable' && isset($url[3])) {
                $token = $url[3];
                call_user_func([$controllerInstance, 'registerResponsable'], $token);
                return;
            }
            
            // Vérifier si l'action est spécifiée
            if (isset($url[1]) && !empty($url[1])) {
                $action = $url[1];
                unset($url[1]);
                
                // Vérifier si la méthode existe dans le contrôleur
                if (method_exists($controllerInstance, $action)) {
                    // Paramètres restants
                    $params = $url ? array_values($url) : [];
                    
                    // Appeler l'action avec les paramètres
                    call_user_func_array([$controllerInstance, $action], $params);
                    return;
                }
            }
            
            // Si aucune action spécifiée ou l'action n'existe pas, redirection vers la page d'accueil
            $this->handleError("La route d'authentification spécifiée n'est pas valide");
            return;
        }

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
            $this->handleError("Le contrôleur {$controller} n'existe pas");        }
    }
    
    /**
     * Récupère l'URL demandée et la divise en segments
     * 
     * @return array Segments de l'URL
     */
    private function getUrl() {
        // Pour le serveur PHP intégré
        if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Development Server') !== false) {
            // Récupère l'URI de la requête et supprime le slash initial
            $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $uri = trim($uri, '/');
            
            // Si l'URI n'est pas vide, le diviser en segments
            if (!empty($uri)) {
                return explode('/', $uri);
            }
            
            return [];
        }
        
        // Pour le serveur Apache
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
