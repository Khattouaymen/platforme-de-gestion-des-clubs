<?php
require_once APP_PATH . '/core/Controller.php';

/**
 * Classe ErrorController - Contrôleur pour les erreurs
 */
class ErrorController extends Controller {
    /**
     * Affiche la page d'erreur
     * 
     * @param string $message Message d'erreur
     * @return void
     */
    public function index($message = "Une erreur s'est produite") {
        $data = [
            'message' => $message,
            'title' => 'Erreur'
        ];
        
        $this->view('error/index', $data);
    }
    
    /**
     * Page non trouvée (404)
     * 
     * @return void
     */
    public function notFound() {
        $data = [
            'message' => 'La page demandée n\'existe pas',
            'title' => 'Page non trouvée'
        ];
        
        $this->view('error/not_found', $data);
    }
    
    /**
     * Accès non autorisé (403)
     * 
     * @return void
     */
    public function forbidden() {
        $data = [
            'message' => 'Vous n\'avez pas les droits pour accéder à cette page',
            'title' => 'Accès interdit'
        ];
        
        $this->view('error/forbidden', $data);
    }
}
?>
