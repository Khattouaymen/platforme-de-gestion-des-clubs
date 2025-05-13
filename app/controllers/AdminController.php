<?php
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/AdminModel.php';
require_once APP_PATH . '/models/ClubModel.php';
require_once APP_PATH . '/models/EtudiantModel.php';

/**
 * Classe AdminController - Contrôleur pour les administrateurs
 */
class AdminController extends Controller {
    private $adminModel;
    private $clubModel;
    private $etudiantModel;
    
    /**
     * Constructeur
     */
    public function __construct() {
        // Vérifier si l'utilisateur est connecté en tant qu'administrateur
        $this->checkAuth();
        
        $this->adminModel = new AdminModel();
        $this->clubModel = new ClubModel();
        $this->etudiantModel = new EtudiantModel();
    }
    
    /**
     * Vérifier l'authentification
     * 
     * @return void
     */
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
            $this->redirect('/login');
        }
    }
    
    /**
     * Tableau de bord de l'administrateur
     * 
     * @return void
     */
    public function index() {
        // Récupérer les informations de l'administrateur
        $admin = $this->adminModel->getById($_SESSION['user_id']);
        
        // Récupérer les statistiques
        $clubs = $this->clubModel->getAll();
        $clubCount = count($clubs);
        
        $etudiants = $this->etudiantModel->getAll();
        $etudiantCount = count($etudiants);
        
        $data = [
            'title' => 'Tableau de bord - Administrateur',
            'admin' => $admin,
            'clubCount' => $clubCount,
            'etudiantCount' => $etudiantCount
        ];
        
        $this->view('admin/dashboard', $data);
    }
    
    /**
     * Gestion des clubs
     * 
     * @return void
     */
    public function clubs() {
        // Récupérer tous les clubs
        $clubs = $this->clubModel->getAll();
        
        $data = [
            'title' => 'Gestion des clubs',
            'clubs' => $clubs
        ];
        
        $this->view('admin/clubs', $data);
    }
    
    /**
     * Ajouter un club
     * 
     * @return void
     */
    public function addClub() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $data = [
                'title' => 'Ajouter un club'
            ];
            
            $this->view('admin/add_club', $data);
            return;
        }
        
        // Récupérer les données du formulaire
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        
        // Traitement du logo
        $logoUrl = '';
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PUBLIC_PATH . '/assets/images/logos/';
            
            // Créer le répertoire s'il n'existe pas
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = basename($_FILES['logo']['name']);
            $targetFile = $uploadDir . $fileName;
            
            // Déplacer le fichier téléchargé vers le répertoire de destination
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                $logoUrl = '/assets/images/logos/' . $fileName;
            }
        }
        
        // Valider les entrées
        $errors = [];
        
        if (empty($nom)) {
            $errors[] = 'Le nom est obligatoire';
        }
        
        if (empty($description)) {
            $errors[] = 'La description est obligatoire';
        }
        
        if (empty($logoUrl)) {
            $errors[] = 'Le logo est obligatoire';
        }
        
        // S'il y a des erreurs
        if (!empty($errors)) {
            $data = [
                'title' => 'Ajouter un club',
                'errors' => $errors,
                'nom' => $nom,
                'description' => $description
            ];
            
            $this->view('admin/add_club', $data);
            return;
        }
        
        // Ajouter le club
        $clubData = [
            'nom' => $nom,
            'description' => $description,
            'logo' => $logoUrl
        ];
        
        $clubId = $this->clubModel->create($clubData);
        
        if ($clubId) {
            $this->redirect('/admin/clubs?success=1');
        } else {
            $data = [
                'title' => 'Ajouter un club',
                'error' => 'Une erreur est survenue lors de l\'ajout du club',
                'nom' => $nom,
                'description' => $description
            ];
            
            $this->view('admin/add_club', $data);
        }
    }
    
    /**
     * Modifier un club
     * 
     * @param int $id ID du club
     * @return void
     */
    public function editClub($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/clubs');
            return;
        }
        
        // Récupérer les informations du club
        $club = $this->clubModel->getById($id);
        
        if (!$club) {
            $this->redirect('/admin/clubs');
            return;
        }
        
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $data = [
                'title' => 'Modifier un club',
                'club' => $club
            ];
            
            $this->view('admin/edit_club', $data);
            return;
        }
        
        // Récupérer les données du formulaire
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        
        // Traitement du logo
        $logoUrl = $club['Logo_URL'];
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = PUBLIC_PATH . '/assets/images/logos/';
            
            // Créer le répertoire s'il n'existe pas
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = basename($_FILES['logo']['name']);
            $targetFile = $uploadDir . $fileName;
            
            // Déplacer le fichier téléchargé vers le répertoire de destination
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                $logoUrl = '/assets/images/logos/' . $fileName;
            }
        }
        
        // Valider les entrées
        $errors = [];
        
        if (empty($nom)) {
            $errors[] = 'Le nom est obligatoire';
        }
        
        if (empty($description)) {
            $errors[] = 'La description est obligatoire';
        }
        
        // S'il y a des erreurs
        if (!empty($errors)) {
            $data = [
                'title' => 'Modifier un club',
                'club' => $club,
                'errors' => $errors
            ];
            
            $this->view('admin/edit_club', $data);
            return;
        }
        
        // Mettre à jour le club
        $clubData = [
            'nom' => $nom,
            'description' => $description,
            'logo' => $logoUrl
        ];
        
        $success = $this->clubModel->update($id, $clubData);
        
        if ($success) {
            $this->redirect('/admin/clubs?update_success=1');
        } else {
            $data = [
                'title' => 'Modifier un club',
                'club' => $club,
                'error' => 'Une erreur est survenue lors de la mise à jour du club'
            ];
            
            $this->view('admin/edit_club', $data);
        }
    }
    
    /**
     * Supprimer un club
     * 
     * @param int $id ID du club
     * @return void
     */
    public function deleteClub($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/clubs');
            return;
        }
        
        // Supprimer le club
        $success = $this->clubModel->delete($id);
        
        if ($success) {
            $this->redirect('/admin/clubs?delete_success=1');
        } else {
            $this->redirect('/admin/clubs?delete_error=1');
        }
    }
    
    /**
     * Gestion des étudiants
     * 
     * @return void
     */
    public function etudiants() {
        // Récupérer tous les étudiants
        $etudiants = $this->etudiantModel->getAll();
        
        $data = [
            'title' => 'Gestion des étudiants',
            'etudiants' => $etudiants
        ];
        
        $this->view('admin/etudiants', $data);
    }
    
    /**
     * Statistiques
     * 
     * @return void
     */
    public function statistiques() {
        // Récupérer les données pour les statistiques
        $clubs = $this->clubModel->getAll();
        $etudiants = $this->etudiantModel->getAll();
        
        $data = [
            'title' => 'Statistiques',
            'clubs' => $clubs,
            'etudiants' => $etudiants
        ];
        
        $this->view('admin/statistiques', $data);
    }
}
?>
