<?php
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/ClubModel.php';
require_once APP_PATH . '/models/ActiviteModel.php';

/**
 * Classe HomeController - Contrôleur pour la page d'accueil
 */
class HomeController extends Controller {
    private $clubModel;
    private $activiteModel;
    
    /**
     * Constructeur
     */
    public function __construct() {
        $this->clubModel = new ClubModel();
        $this->activiteModel = new ActiviteModel();
    }
    
    /**
     * Page d'accueil
     * 
     * @return void
     */
    public function index() {
        // Récupérer les clubs pour la page d'accueil
        $clubs = $this->clubModel->getAll();
        
        // Récupérer les activités à venir
        $activites = $this->activiteModel->getAll();
        
        $data = [
            'title' => 'Accueil - Gestion des Clubs',
            'clubs' => $clubs,
            'activites' => $activites
        ];
        
        $this->view('home/index', $data);
    }
    
    /**
     * Page de connexion
     * 
     * @return void
     */
    public function login() {
        // Si l'utilisateur est déjà connecté, rediriger
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        
        $data = [
            'title' => 'Connexion',
            'error' => ''
        ];
        
        $this->view('home/login', $data);
    }
    
    /**
     * Page d'inscription
     * 
     * @return void
     */
    public function register() {
        // Si l'utilisateur est déjà connecté, rediriger
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        
        $data = [
            'title' => 'Inscription',
            'error' => ''
        ];
        
        $this->view('home/register', $data);
    }
    
    /**
     * Page À propos
     * 
     * @return void
     */
    public function about() {
        $data = [
            'title' => 'À propos'
        ];
        
        $this->view('home/about', $data);
    }
    
    /**
     * Page de contact
     * 
     * @return void
     */
    public function contact() {
        $data = [
            'title' => 'Contact'
        ];
        
        $this->view('home/contact', $data);
    }
}
?>
