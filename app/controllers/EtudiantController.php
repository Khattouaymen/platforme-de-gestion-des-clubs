<?php
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/EtudiantModel.php';
require_once APP_PATH . '/models/ClubModel.php';
require_once APP_PATH . '/models/ActiviteModel.php';

/**
 * Classe EtudiantController - Contrôleur pour les étudiants
 */
class EtudiantController extends Controller {
    private $etudiantModel;
    private $clubModel;
    private $activiteModel;
    
    /**
     * Constructeur
     */
    public function __construct() {
        // Vérifier si l'utilisateur est connecté en tant qu'étudiant
        $this->checkAuth();
        
        $this->etudiantModel = new EtudiantModel();
        $this->clubModel = new ClubModel();
        $this->activiteModel = new ActiviteModel();
    }
    
    /**
     * Vérifier l'authentification
     * 
     * @return void
     */
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'etudiant') {
            $this->redirect('/login');
        }
    }
    
    /**
     * Tableau de bord de l'étudiant
     * 
     * @return void
     */
    public function index() {
        // Récupérer les informations de l'étudiant
        $etudiant = $this->etudiantModel->getById($_SESSION['user_id']);
        
        // Récupérer les clubs disponibles
        $clubs = $this->clubModel->getAll();
        
        // Récupérer les activités à venir
        $activites = $this->activiteModel->getAll();
        
        $data = [
            'title' => 'Tableau de bord - Étudiant',
            'etudiant' => $etudiant,
            'clubs' => $clubs,
            'activites' => $activites
        ];
        
        $this->view('etudiant/dashboard', $data);
    }
    
    /**
     * Liste des clubs disponibles
     * 
     * @return void
     */
    public function clubs() {
        // Récupérer tous les clubs
        $clubs = $this->clubModel->getAll();
        
        $data = [
            'title' => 'Clubs disponibles',
            'clubs' => $clubs
        ];
        
        $this->view('etudiant/clubs', $data);
    }
    
    /**
     * Détails d'un club
     * 
     * @param int $id ID du club
     * @return void
     */
    public function club($id) {
        // Récupérer les informations du club
        $club = $this->clubModel->getById($id);
        
        if (!$club) {
            $this->redirect('/etudiant/clubs');
            return;
        }
        
        // Récupérer les activités du club
        $activites = $this->activiteModel->getByClubId($id);
        
        $data = [
            'title' => 'Détails du club - ' . $club['nom'],
            'club' => $club,
            'activites' => $activites
        ];
        
        $this->view('etudiant/club_details', $data);
    }
    
    /**
     * Liste des activités disponibles
     * 
     * @return void
     */
    public function activites() {
        // Récupérer toutes les activités
        $activites = $this->activiteModel->getAll();
        
        $data = [
            'title' => 'Activités disponibles',
            'activites' => $activites
        ];
        
        $this->view('etudiant/activites', $data);
    }
    
    /**
     * Détails d'une activité
     * 
     * @param int $id ID de l'activité
     * @return void
     */
    public function activite($id) {
        // Récupérer les informations de l'activité
        $activite = $this->activiteModel->getById($id);
        
        if (!$activite) {
            $this->redirect('/etudiant/activites');
            return;
        }
        
        $data = [
            'title' => 'Détails de l\'activité - ' . $activite['titre'],
            'activite' => $activite
        ];
        
        $this->view('etudiant/activite_details', $data);
    }
    
    /**
     * Profil de l'étudiant
     * 
     * @return void
     */
    public function profil() {
        // Récupérer les informations de l'étudiant
        $etudiant = $this->etudiantModel->getById($_SESSION['user_id']);
        
        $data = [
            'title' => 'Mon profil',
            'etudiant' => $etudiant
        ];
        
        $this->view('etudiant/profil', $data);
    }
    
    /**
     * Mise à jour du profil
     * 
     * @return void
     */
    public function updateProfil() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/etudiant/profil');
            return;
        }
        
        // Récupérer les données du formulaire
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        // Valider les entrées
        $errors = [];
        
        if (empty($nom)) {
            $errors[] = 'Le nom est obligatoire';
        }
        
        if (empty($prenom)) {
            $errors[] = 'Le prénom est obligatoire';
        }
        
        if (empty($email)) {
            $errors[] = 'L\'email est obligatoire';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L\'email n\'est pas valide';
        }
        
        // S'il y a des erreurs
        if (!empty($errors)) {
            $etudiant = $this->etudiantModel->getById($_SESSION['user_id']);
            
            $data = [
                'title' => 'Mon profil',
                'etudiant' => $etudiant,
                'errors' => $errors
            ];
            
            $this->view('etudiant/profil', $data);
            return;
        }
        
        // Mettre à jour le profil
        $userData = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email
        ];
        
        $success = $this->etudiantModel->update($_SESSION['user_id'], $userData);
        
        if ($success) {
            // Mettre à jour le nom dans la session
            $_SESSION['user_name'] = $prenom . ' ' . $nom;
            
            $this->redirect('/etudiant/profil?success=1');
        } else {
            $etudiant = $this->etudiantModel->getById($_SESSION['user_id']);
            
            $data = [
                'title' => 'Mon profil',
                'etudiant' => $etudiant,
                'error' => 'Une erreur est survenue lors de la mise à jour du profil'
            ];
            
            $this->view('etudiant/profil', $data);
        }
    }
    
    /**
     * Changement de mot de passe
     * 
     * @return void
     */
    public function changePassword() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/etudiant/profil');
            return;
        }
        
        // Récupérer les données du formulaire
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // Valider les entrées
        $errors = [];
        
        if (empty($currentPassword)) {
            $errors[] = 'Le mot de passe actuel est obligatoire';
        }
        
        if (empty($newPassword)) {
            $errors[] = 'Le nouveau mot de passe est obligatoire';
        } elseif (strlen($newPassword) < 6) {
            $errors[] = 'Le nouveau mot de passe doit contenir au moins 6 caractères';
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = 'Les mots de passe ne correspondent pas';
        }
        
        // S'il y a des erreurs
        if (!empty($errors)) {
            $etudiant = $this->etudiantModel->getById($_SESSION['user_id']);
            
            $data = [
                'title' => 'Mon profil',
                'etudiant' => $etudiant,
                'passwordErrors' => $errors
            ];
            
            $this->view('etudiant/profil', $data);
            return;
        }
        
        // Vérifier si le mot de passe actuel est correct
        $etudiant = $this->etudiantModel->getById($_SESSION['user_id']);
        
        if (!password_verify($currentPassword, $etudiant['password'])) {
            $data = [
                'title' => 'Mon profil',
                'etudiant' => $etudiant,
                'passwordError' => 'Le mot de passe actuel est incorrect'
            ];
            
            $this->view('etudiant/profil', $data);
            return;
        }
        
        // Changer le mot de passe
        $success = $this->etudiantModel->changePassword($_SESSION['user_id'], $newPassword);
        
        if ($success) {
            $this->redirect('/etudiant/profil?password_success=1');
        } else {
            $data = [
                'title' => 'Mon profil',
                'etudiant' => $etudiant,
                'passwordError' => 'Une erreur est survenue lors du changement de mot de passe'
            ];
            
            $this->view('etudiant/profil', $data);
        }
    }
}
?>
