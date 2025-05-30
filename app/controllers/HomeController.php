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
     */    public function index() {
        // Récupérer les clubs pour la page d'accueil
        $clubs = $this->clubModel->getAll();
        
        // Récupérer les activités à venir
        $activites = $this->activiteModel->getAll();        $data = [
            'title' => 'Accueil - Gestion des Clubs',
            'clubs' => $clubs,
            'activites' => $activites,
            'asset' => function($path) { return $this->asset($path); },
            'hideNavbar' => false  // Paramètre pour afficher la navbar sur la page d'accueil
        ];
        
        $this->view('home/index', $data);
    }
    
    /**
     * Page de connexion
     * 
     * @return void
     */    public function login() {
        // Si l'utilisateur est déjà connecté, rediriger
        if (isset($_SESSION['user_id'])) {
            // Rediriger selon le type d'utilisateur
            if ($_SESSION['user_type'] === 'admin') {
                $this->redirect('/admin');
            } else {
                $this->redirect('/etudiant');
            }
        }
          $data = [
            'title' => 'Connexion',
            'error' => '',
            'hideNavbar' => true  // Paramètre pour cacher la navbar
        ];
        
        $this->view('home/login', $data);
    }
      /**
     * Page d'inscription
     * 
     * @return void
     */    public function register() {
        // Si l'utilisateur est déjà connecté, rediriger
        if (isset($_SESSION['user_id'])) {
            // Rediriger selon le type d'utilisateur
            if ($_SESSION['user_type'] === 'admin') {
                $this->redirect('/admin');
            } else {
                $this->redirect('/etudiant');
            }
        }
        
        // Rediriger vers la page de connexion qui contient maintenant le formulaire d'inscription
        $this->redirect('/login');
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
        // Charger le modèle de contact
        require_once APP_PATH . '/models/ContactModel.php';
        $contactModel = new ContactModel();
        
        $data = [
            'title' => 'Contact',
            'success' => false,
            'error' => false
        ];
        
        // Traitement du formulaire de contact
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleContactForm($contactModel, $data);
        }
        
        $this->view('home/contact', $data);
    }
    
    /**
     * Traite la soumission du formulaire de contact
     * 
     * @param ContactModel $contactModel
     * @param array &$data
     * @return void
     */
    private function handleContactForm($contactModel, &$data) {
        // Validation des données
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $sujet = trim($_POST['sujet'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        // Vérifications de base
        if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
            $data['error'] = 'Tous les champs sont obligatoires.';
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['error'] = 'Adresse email invalide.';
            return;
        }
        
        if (strlen($message) < 10) {
            $data['error'] = 'Le message doit contenir au moins 10 caractères.';
            return;
        }
        
        // Sujets autorisés
        $sujetsAutorises = ['information', 'adhesion', 'activite', 'technique', 'suggestion', 'autre'];
        if (!in_array($sujet, $sujetsAutorises)) {
            $data['error'] = 'Sujet non valide.';
            return;
        }
        
        // Préparer les données pour l'enregistrement
        $messageData = [
            'nom' => $nom,
            'email' => $email,
            'sujet' => $sujet,
            'message' => $message
        ];
        
        // Enregistrer le message
        if ($contactModel->createMessage($messageData)) {
            $data['success'] = 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.';
        } else {
            $data['error'] = 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.';
        }
    }
}
?>
