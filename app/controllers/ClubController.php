<?php

require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/ClubModel.php';
require_once APP_PATH . '/models/ActiviteModel.php';

/**
 * Contrôleur pour l'affichage public des clubs
 */
class ClubController extends Controller {
    
    private $clubModel;
    private $activiteModel;
    
    public function __construct() {
        $this->clubModel = new ClubModel();
        $this->activiteModel = new ActiviteModel();
    }
    
    /**
     * Affiche les détails d'un club ou la liste des clubs
     * 
     * @param int|null $id ID du club (optionnel)
     * @return void
     */
    public function index($id = null) {
        // Si un ID est fourni, afficher les détails du club
        if ($id !== null) {
            $this->showClubDetails($id);
            return;
        }
        
        // Sinon, afficher la liste des clubs
        $this->showClubsList();
    }
    
    /**
     * Affiche les détails d'un club spécifique
     * 
     * @param int $id ID du club
     * @return void
     */
    private function showClubDetails($id) {
        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['user_id'])) {
            // Rediriger vers le contrôleur approprié selon le rôle
            $this->redirectAuthenticatedUser($id);
            return;
        }
        
        // Récupérer les informations du club
        $club = $this->clubModel->getById($id);
        
        if (!$club) {
            // Club non trouvé, rediriger vers la page d'accueil
            $this->redirect('/');
            return;
        }
        
        // Récupérer les activités du club
        $activites = $this->activiteModel->getByClubId($id);
        
        // Récupérer les membres du club (pour afficher le nombre)
        $membres = $this->clubModel->getMembresByClubId($id);
        $nombreMembres = count($membres);        // Récupérer le responsable du club
        $responsable = $this->clubModel->getResponsableByClubId($id);
        
        // Préparer les données pour la vue
        $data = [
            'title' => $club['nom'] . ' - Détails du Club',
            'club' => $club,
            'activites' => $activites,
            'nombreMembres' => $nombreMembres,
            'responsable' => $responsable,
            'hideNavbar' => false // Afficher la navbar sur cette page
        ];
          // Afficher la vue publique des détails du club
        $this->view('public/club_details', $data);
    }
    
    /**
     * Affiche la liste des clubs
     * 
     * @return void
     */
    private function showClubsList() {
        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['user_id'])) {
            // Rediriger vers le tableau de bord approprié
            $this->redirectAuthenticatedUserToList();
            return;
        }
        
        // Récupérer tous les clubs
        $clubs = $this->clubModel->getAll();
        
        // Préparer les données pour la vue
        $data = [
            'title' => 'Liste des Clubs',
            'clubs' => $clubs,
            'hideNavbar' => false // Afficher la navbar sur cette page
        ];
          // Afficher la vue publique de la liste des clubs
        $this->view('public/clubs', $data);
    }
    
    /**
     * Redirige un utilisateur authentifié vers le bon contrôleur
     * 
     * @param int $id ID du club
     * @return void
     */
    private function redirectAuthenticatedUser($id) {
        $role = $_SESSION['role'] ?? '';
        
        switch ($role) {
            case 'etudiant':
                $this->redirect('/etudiant/club/' . $id);
                break;
            case 'responsable':
                $this->redirect('/responsable/dashboard');
                break;
            case 'admin':
                $this->redirect('/admin/club/' . $id);
                break;
            default:
                $this->redirect('/');
                break;
        }
    }
    
    /**
     * Redirige un utilisateur authentifié vers la liste appropriée
     * 
     * @return void
     */
    private function redirectAuthenticatedUserToList() {
        $role = $_SESSION['role'] ?? '';
        
        switch ($role) {
            case 'etudiant':
                $this->redirect('/etudiant/clubs');
                break;
            case 'responsable':
                $this->redirect('/responsable/dashboard');
                break;
            case 'admin':
                $this->redirect('/admin/clubs');
                break;
            default:
                $this->redirect('/');
                break;
        }
    }
}