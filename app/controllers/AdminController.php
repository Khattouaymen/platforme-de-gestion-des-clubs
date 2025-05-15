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
    public function index() {        // Récupérer les informations de l'administrateur
        $admin = $this->adminModel->getById($_SESSION['user_id']);
        
        // Récupérer les statistiques
        $clubs = $this->clubModel->getAll();
        $clubCount = count($clubs);
        $etudiants = $this->etudiantModel->getAll();        $etudiantCount = count($etudiants);
        
        $data = [
            'title' => 'Tableau de Bord Administrateur',
            'admin' => $admin,
            'club_count' => $clubCount,
            'etudiant_count' => $etudiantCount,
            'asset' => function($path) { return $this->asset($path); }
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
        
        // Gérer les messages d'alerte
        $alertSuccess = null;
        $alertError = null;
        
        // Messages de succès
        if (isset($_GET['success'])) {
            $alertSuccess = "Le club a été ajouté avec succès.";
        } elseif (isset($_GET['update_success'])) {
            $alertSuccess = "Le club a été mis à jour avec succès.";
        } elseif (isset($_GET['delete_success'])) {
            $alertSuccess = "Le club a été supprimé avec succès.";
        }
        
        // Messages d'erreur
        if (isset($_GET['error'])) {
            $alertError = urldecode($_GET['error']);
        } elseif (isset($_GET['update_error'])) {
            $alertError = "Une erreur est survenue lors de la mise à jour du club.";        } elseif (isset($_GET['delete_error'])) {
            $alertError = "Une erreur est survenue lors de la suppression du club.";
        }
        
        $data = [
            'title' => 'Gestion des clubs',
            'clubs' => $clubs,
            'alertSuccess' => $alertSuccess,
            'alertError' => $alertError,
            'asset' => function($path) { return $this->asset($path); }
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
            $this->redirect('/admin/clubs');
            return;
        }
        
        // Récupérer les données du formulaire
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $logoUrl = filter_input(INPUT_POST, 'logo', FILTER_SANITIZE_URL);
        
        // Valider les entrées
        $errors = [];
        
        if (empty($nom)) {
            $errors[] = 'Le nom est obligatoire';
        }
        
        if (empty($description)) {
            $errors[] = 'La description est obligatoire';
        }
        
        if (empty($logoUrl)) {
            // Si aucun logo n'est fourni, utilisez un logo par défaut
            $logoUrl = '/assets/images/logo_creative.jpg';
        }
        
        // S'il y a des erreurs
        if (!empty($errors)) {
            // Rediriger vers la page des clubs avec les erreurs
            $errorMessage = implode(', ', $errors);
            $this->redirect('/admin/clubs?error=' . urlencode($errorMessage));
            return;
        }
        
        // Préparer les données du club
        $clubData = [
            'nom' => $nom,
            'description' => $description,
            'logo' => $logoUrl
        ];
        
        // Ajouter le club
        $clubId = $this->clubModel->create($clubData);
        
        if ($clubId) {
            $this->redirect('/admin/clubs?success=1');
        } else {
            $this->redirect('/admin/clubs?error=Une+erreur+est+survenue+lors+de+l%27ajout+du+club');
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
            $this->redirect('/admin/clubs?error=Club+introuvable');
            return;
        }
        
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/clubs');
            return;
        }
        
        // Récupérer les données du formulaire
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        
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
            $errorMessage = implode(', ', $errors);
            $this->redirect("/admin/clubs?error=" . urlencode($errorMessage));
            return;
        }
        
        // Traitement de l'URL du logo
        $logoUrl = filter_input(INPUT_POST, 'logo', FILTER_SANITIZE_URL);
        
        // Si aucune URL n'est fournie, conserver l'ancien logo
        if (empty($logoUrl)) {
            $logoUrl = $club['Logo_URL'];
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
            $this->redirect('/admin/clubs?update_error=1');
        }
    }
    
    /**
     * Supprimer un club
     * 
     * @param int $id ID du club
     * @return void
     */
    public function deleteClub($id = null) {        // Vérifier si l'ID est valide
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
     * Récupérer les informations d'un club (AJAX)
     * 
     * @param int $id ID du club
     * @return void
     */
    public function getClub($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            echo json_encode(['success' => false, 'message' => 'ID de club invalide']);
            return;
        }
        
        // Récupérer les informations du club
        $club = $this->clubModel->getById($id);
        
        if (!$club) {
            echo json_encode(['success' => false, 'message' => 'Club introuvable']);
            return;
        }
        
        // Renvoyer les données au format JSON
        echo json_encode([
            'success' => true,
            'club' => $club
        ]);
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
            'etudiants' => $etudiants,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/statistiques', $data);
    }
    
    /**
     * Gestion des ressources
     * 
     * @return void
     */
    public function ressources() {
        $data = [
            'title' => 'Gestion des ressources',
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/ressources', $data);
    }
    
    /**
     * Gestion des demandes d'activités
     * 
     * @return void
     */
    public function demandes() {
        $data = [
            'title' => 'Gestion des demandes',
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/demandes', $data);
    }
}
?>
