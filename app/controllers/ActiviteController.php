<?php
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/ActiviteModel.php';
require_once APP_PATH . '/models/ParticipationActiviteModel.php';

/**
 * Classe ActiviteController - Contrôleur pour la gestion des activités publiques
 */
class ActiviteController extends Controller {
    private $activiteModel;
    private $participationActiviteModel;
      /**
     * Constructeur
     */
    public function __construct() {
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->activiteModel = new ActiviteModel();
        $this->participationActiviteModel = new ParticipationActiviteModel();
    }
      /**
     * Méthode par défaut - peut afficher la liste ou les détails selon le paramètre
     * 
     * @param int|null $id ID de l'activité (optionnel)
     * @return void
     */
    public function index($id = null) {
        if ($id !== null) {
            // Si un ID est fourni, afficher les détails de l'activité
            $this->show($id);
            return;
        }
        
        // Sinon, afficher la liste des activités
        $activites = $this->activiteModel->getAll();
        
        $data = [
            'title' => 'Activités - Gestion des Clubs',
            'activites' => $activites,
            'asset' => function($path) { return $this->asset($path); },
            'hideNavbar' => true
        ];
        
        $this->view('public/activites', $data);
    }
    
    /**
     * Détails d'une activité - accessible au public
     * 
     * @param int $id ID de l'activité
     * @return void
     */
    public function show($id = null) {
        if (!$id) {
            $this->redirect('/');
            return;
        }
        
        // Si l'utilisateur est connecté, rediriger vers le contrôleur approprié
        if (isset($_SESSION['user_id'])) {
            switch ($_SESSION['user_type'] ?? '') {
                case 'etudiant':
                    $this->redirect('/etudiant/activite/' . $id);
                    return;
                case 'responsable':
                    $this->redirect('/responsable/gestionActivites');
                    return;
                case 'admin':
                    $this->redirect('/admin/activiteDetails/' . $id);
                    return;
                default:
                    // Utilisateur connecté mais sans type défini, continuer avec la vue publique
                    break;
            }
        }
        
        // Récupérer les informations de l'activité
        $activite = $this->activiteModel->getById($id);
        
        if (!$activite) {
            $this->redirect('/');
            return;
        }
        
        // Compter le nombre de participants
        $nombreParticipants = $this->participationActiviteModel->getParticipantCount($id);
        
        $data = [
            'title' => 'Détails de l\'activité - ' . $activite['titre'],
            'activite' => $activite,
            'nombreParticipants' => $nombreParticipants,
            'asset' => function($path) { return $this->asset($path); },
            'hideNavbar' => true
        ];
        
        $this->view('public/activite_details', $data);
    }
    
    /**
     * Redirection depuis l'ancienne URL (pour compatibilité)
     * 
     * @param int $id ID de l'activité
     * @return void
     */
    public function activite($id = null) {
        // Rediriger vers la nouvelle méthode show
        $this->show($id);
    }
}
?>
