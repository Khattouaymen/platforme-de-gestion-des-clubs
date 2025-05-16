<?php
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/AdminModel.php';
require_once APP_PATH . '/models/ClubModel.php';
require_once APP_PATH . '/models/EtudiantModel.php';
require_once APP_PATH . '/models/RessourceModel.php';
require_once APP_PATH . '/models/DemandeClubModel.php';
require_once APP_PATH . '/models/DemandeActiviteModel.php';
require_once APP_PATH . '/models/DemandeAdhesionModel.php';

/**
 * Classe AdminController - Contrôleur pour les administrateurs
 */
class AdminController extends Controller {
    private $adminModel;
    private $clubModel;
    private $etudiantModel;
    private $ressourceModel;
    private $demandeClubModel;
    private $demandeActiviteModel;
    private $demandeAdhesionModel;
    
    /**
     * Constructeur
     */
    public function __construct() {
        // Vérifier si l'utilisateur est connecté en tant qu'administrateur
        $this->checkAuth();
        
        $this->adminModel = new AdminModel();
        $this->clubModel = new ClubModel();
        $this->etudiantModel = new EtudiantModel();
        $this->ressourceModel = new RessourceModel();
        $this->demandeClubModel = new DemandeClubModel();
        $this->demandeActiviteModel = new DemandeActiviteModel();
        $this->demandeAdhesionModel = new DemandeAdhesionModel();
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
     * Afficher les détails d'un club spécifique
     * 
     * @param int $id ID du club
     * @return void
     */
    public function viewClub($id = null) {
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
        
        // Récupérer les membres du club
        $membres = $this->clubModel->getMembers($id);
        
        // Récupérer les activités du club
        $activites = $this->clubModel->getActivities($id);
        
        $data = [
            'title' => 'Détails du club: ' . $club['nom'],
            'club' => $club,
            'membres' => $membres,
            'activites' => $activites,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/club_details', $data);
    }
    
    /**
     * Afficher les détails d'une activité spécifique avec la liste de présence
     * 
     * @param int $id ID de l'activité
     * @return void
     */
    public function viewActivite($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/clubs');
            return;
        }
        
        // Charger le modèle d'activité si ce n'est pas déjà fait
        if (!isset($this->activiteModel)) {
            require_once APP_PATH . '/models/ActiviteModel.php';
            $this->activiteModel = new ActiviteModel();
        }
        
        // Récupérer les informations de l'activité
        $activite = $this->activiteModel->getById($id);
        
        if (!$activite) {
            $this->redirect('/admin/clubs?error=Activité+introuvable');
            return;
        }
        
        // Récupérer la liste de présence
        $participants = $this->activiteModel->getParticipants($id);
        
        $data = [
            'title' => 'Détails de l\'activité: ' . $activite['titre'],
            'activite' => $activite,
            'participants' => $participants,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/activite_details', $data);
    }
    
    /**
     * Générer un lien d'inscription pour un responsable de club
     * 
     * @return void
     */
    public function generateResponsableLink() {
        // Invalider les liens précédents en les marquant comme utilisés
        $this->adminModel->invalidateResponsableTokens();
        
        // Rediriger vers la page de génération de lien
        $this->redirect('/admin/responsableLink');
    }
    
    /**
     * Affiche la page de génération de lien pour responsable de club
     * 
     * @return void
     */
    public function responsableLink() {
        // Générer un token unique
        $token = bin2hex(random_bytes(32));
        
        // Sauvegarder le token dans la base de données
        $tokenId = $this->adminModel->saveResponsableToken($token);
        
        if (!$tokenId) {
            $this->redirect('/admin/dashboard?error=Erreur+lors+de+la+génération+du+lien');
            return;
        }
        
        // Créer le lien d'inscription
        $baseUrl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
        $baseUrl .= $_SERVER['HTTP_HOST'];
        $lien = $baseUrl . "/auth/register/responsable/" . $token;
        
        $data = [
            'title' => 'Lien d\'inscription pour responsable de club',
            'lien' => $lien,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/responsable_link', $data);
    }
    
    /**
     * Supervise l'ensemble des clubs et activités
     * 
     * @return void
     */
    public function supervisionClubs() {
        // Récupérer tous les clubs avec leurs membres et activités
        $clubs = $this->clubModel->getAllWithDetails();
        
        $data = [
            'title' => 'Supervision des clubs',
            'clubs' => $clubs,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/supervision_clubs', $data);
    }
      /**
     * Affiche les détails d'un club spécifique
     * 
     * @param int $id ID du club
     * @return void
     */
    public function clubDetails($id = null) {
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
        
        // Récupérer les membres du club
        $membres = $this->clubModel->getMembresByClubId($id);
        
        // Récupérer le responsable du club
        $responsable = $this->clubModel->getResponsableByClubId($id);
        
        // Récupérer les activités du club
        $activites = $this->clubModel->getActivitesByClubId($id);
        
        // Récupérer l'historique de présence des activités du club
        $presences = [];
        foreach ($activites as $activite) {
            $presences[$activite['activite_id']] = $this->clubModel->getPresenceByActiviteId($activite['activite_id']);
        }
        
        $data = [
            'title' => 'Détails du club',
            'club' => $club,
            'membres' => $membres,
            'responsable' => $responsable,
            'activites' => $activites,
            'presences' => $presences,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/club_details', $data);
    }
    
    /**
     * Affiche les détails d'une activité spécifique
     * 
     * @param int $id ID de l'activité
     * @return void
     */
    public function activiteDetails($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/clubs');
            return;
        }
        
        // Charger le modèle d'activité si ce n'est pas déjà fait
        if (!isset($this->activiteModel)) {
            require_once APP_PATH . '/models/ActiviteModel.php';
            $this->activiteModel = new ActiviteModel();
        }
        
        // Récupérer les informations de l'activité
        $activite = $this->activiteModel->getById($id);
        
        if (!$activite) {
            $this->redirect('/admin/clubs?error=Activité+introuvable');
            return;
        }
        
        // Récupérer les participants à l'activité
        $participants = $this->activiteModel->getParticipantsByActiviteId($id);
        
        $data = [
            'title' => 'Détails de l\'activité',
            'activite' => $activite,
            'participants' => $participants,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/activite_details', $data);
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
        $clubCount = count($clubs);
        
        $etudiants = $this->etudiantModel->getAll();
        $etudiantCount = count($etudiants);
        
        // Demandes en attente
        $demandesClub = $this->demandeClubModel->getByStatut('en_attente');
        $demandesClubCount = count($demandesClub);
        
        $demandesAdhesion = $this->demandeAdhesionModel->getByStatut('en_attente');
        $demandesAdhesionCount = count($demandesAdhesion);
        
        $data = [
            'title' => 'Statistiques',
            'clubs' => $clubs,
            'etudiants' => $etudiants,
            'club_count' => $clubCount,
            'etudiant_count' => $etudiantCount,
            'demandes_club_count' => $demandesClubCount,
            'demandes_adhesion_count' => $demandesAdhesionCount,
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
        // Récupérer toutes les ressources
        $ressources = $this->ressourceModel->getAll();
        
        // Récupérer tous les clubs pour le formulaire d'ajout
        $clubs = $this->clubModel->getAll();
        
        // Gérer les messages d'alerte
        $alertSuccess = null;
        $alertError = null;
        
        // Messages de succès
        if (isset($_GET['success'])) {
            $alertSuccess = "La ressource a été ajoutée avec succès.";
        } elseif (isset($_GET['update_success'])) {
            $alertSuccess = "La ressource a été mise à jour avec succès.";
        } elseif (isset($_GET['delete_success'])) {
            $alertSuccess = "La ressource a été supprimée avec succès.";
        }
        
        // Messages d'erreur
        if (isset($_GET['error'])) {
            $alertError = urldecode($_GET['error']);
        }
        
        $data = [
            'title' => 'Gestion des ressources',
            'ressources' => $ressources,
            'clubs' => $clubs,
            'alertSuccess' => $alertSuccess,
            'alertError' => $alertError,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/ressources', $data);
    }
    
    /**
     * Ajouter une ressource
     * 
     * @return void
     */
    public function addRessource() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/ressources');
            return;
        }
        
        // Récupérer les données du formulaire
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'default' => 1]]);
        $clubId = filter_input(INPUT_POST, 'club_id', FILTER_VALIDATE_INT);
        $disponibilite = filter_input(INPUT_POST, 'disponibilite', FILTER_SANITIZE_STRING);
        
        // Valider les entrées
        $errors = [];
        
        if (empty($nom)) {
            $errors[] = 'Le nom est obligatoire';
        }
        
        if (empty($type) || !in_array($type, ['materiel', 'humain', 'financier', 'autre'])) {
            $errors[] = 'Le type est obligatoire et doit être valide';
        }
        
        // S'il y a des erreurs
        if (!empty($errors)) {
            $errorMessage = implode(', ', $errors);
            $this->redirect('/admin/ressources?error=' . urlencode($errorMessage));
            return;
        }
        
        // Préparer les données de la ressource
        $ressourceData = [
            'nom' => $nom,
            'type' => $type,
            'quantite' => $quantite,
            'club_id' => $clubId ?: null,
            'disponibilite' => $disponibilite ?: 'disponible'
        ];
        
        // Ajouter la ressource
        $ressourceId = $this->ressourceModel->create($ressourceData);
        
        if ($ressourceId) {
            $this->redirect('/admin/ressources?success=1');
        } else {
            $this->redirect('/admin/ressources?error=Une+erreur+est+survenue+lors+de+l%27ajout+de+la+ressource');
        }
    }
    
    /**
     * Modifier une ressource
     * 
     * @param int $id ID de la ressource
     * @return void
     */
    public function editRessource($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/ressources');
            return;
        }
        
        // Récupérer les informations de la ressource
        $ressource = $this->ressourceModel->getById($id);
        
        if (!$ressource) {
            $this->redirect('/admin/ressources?error=Ressource+introuvable');
            return;
        }
        
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/ressources');
            return;
        }
        
        // Récupérer les données du formulaire
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'default' => 1]]);
        $clubId = filter_input(INPUT_POST, 'club_id', FILTER_VALIDATE_INT);
        $disponibilite = filter_input(INPUT_POST, 'disponibilite', FILTER_SANITIZE_STRING);
        
        // Valider les entrées
        $errors = [];
        
        if (empty($nom)) {
            $errors[] = 'Le nom est obligatoire';
        }
        
        if (empty($type) || !in_array($type, ['materiel', 'humain', 'financier', 'autre'])) {
            $errors[] = 'Le type est obligatoire et doit être valide';
        }
        
        // S'il y a des erreurs
        if (!empty($errors)) {
            $errorMessage = implode(', ', $errors);
            $this->redirect('/admin/ressources?error=' . urlencode($errorMessage));
            return;
        }
        
        // Mettre à jour la ressource
        $ressourceData = [
            'nom' => $nom,
            'type' => $type,
            'quantite' => $quantite,
            'club_id' => $clubId ?: null,
            'disponibilite' => $disponibilite ?: 'disponible'
        ];
        
        $success = $this->ressourceModel->update($id, $ressourceData);
        
        if ($success) {
            $this->redirect('/admin/ressources?update_success=1');
        } else {
            $this->redirect('/admin/ressources?error=Une+erreur+est+survenue+lors+de+la+mise+à+jour+de+la+ressource');
        }
    }
    
    /**
     * Supprimer une ressource
     * 
     * @param int $id ID de la ressource
     * @return void
     */
    public function deleteRessource($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/ressources');
            return;
        }
        
        // Supprimer la ressource
        $success = $this->ressourceModel->delete($id);
        
        if ($success) {
            $this->redirect('/admin/ressources?delete_success=1');
        } else {
            $this->redirect('/admin/ressources?error=Une+erreur+est+survenue+lors+de+la+suppression+de+la+ressource');
        }
    }
    
    /**
     * Récupérer les informations d'une ressource (AJAX)
     * 
     * @param int $id ID de la ressource
     * @return void
     */
    public function getRessource($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            echo json_encode(['success' => false, 'message' => 'ID de ressource invalide']);
            return;
        }
        
        // Récupérer les informations de la ressource
        $ressource = $this->ressourceModel->getById($id);
        
        if (!$ressource) {
            echo json_encode(['success' => false, 'message' => 'Ressource introuvable']);
            return;
        }
        
        // Renvoyer les données au format JSON
        echo json_encode([
            'success' => true,
            'ressource' => $ressource
        ]);
    }
    
    /**
     * Gestion des demandes d'activités
     * 
     * @return void
     */
    public function demandes() {
        // Récupérer les demandes d'approbation de clubs
        $demandesClub = [
            'en_attente' => $this->demandeClubModel->getByStatut('en_attente'),
            'approuve' => $this->demandeClubModel->getByStatut('approuve'),
            'rejete' => $this->demandeClubModel->getByStatut('rejete')
        ];
        
        // Récupérer les demandes d'adhésion
        $demandesAdhesion = [
            'en_attente' => $this->demandeAdhesionModel->getByStatut('en_attente'),
            'acceptee' => $this->demandeAdhesionModel->getByStatut('acceptee'),
            'refusee' => $this->demandeAdhesionModel->getByStatut('refusee')
        ];
        
        // Récupérer les demandes d'activité
        $demandesActivite = $this->demandeActiviteModel->getAll();
        
        // Gérer les messages d'alerte
        $alertSuccess = null;
        $alertError = null;
        
        // Messages de succès
        if (isset($_GET['success'])) {
            $alertSuccess = "La demande a été traitée avec succès.";
        }
        
        // Messages d'erreur
        if (isset($_GET['error'])) {
            $alertError = urldecode($_GET['error']);
        }
        
        $data = [
            'title' => 'Gestion des demandes',
            'demandesClub' => $demandesClub,
            'demandesAdhesion' => $demandesAdhesion,
            'demandesActivite' => $demandesActivite,
            'alertSuccess' => $alertSuccess,
            'alertError' => $alertError,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/demandes', $data);
    }

    /**
     * Approuver une demande de club
     * 
     * @param int $id ID de la demande
     * @return void
     */
    public function approveDemandeClub($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/demandes');
            return;
        }
        
        // Approuver la demande et créer le club
        $clubId = $this->demandeClubModel->approveAndCreateClub($id, $this->clubModel);
        
        if ($clubId) {
            $this->redirect('/admin/demandes?success=1');
        } else {
            $this->redirect('/admin/demandes?error=Une+erreur+est+survenue+lors+de+l%27approbation+de+la+demande');
        }
    }
    
    /**
     * Rejeter une demande de club
     * 
     * @param int $id ID de la demande
     * @return void
     */
    public function rejectDemandeClub($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/demandes');
            return;
        }
        
        // Rejeter la demande
        $success = $this->demandeClubModel->reject($id);
        
        if ($success) {
            $this->redirect('/admin/demandes?success=1');
        } else {
            $this->redirect('/admin/demandes?error=Une+erreur+est+survenue+lors+du+rejet+de+la+demande');
        }
    }
    
    /**
     * Accepter une demande d'adhésion
     * 
     * @param int $id ID de la demande
     * @return void
     */
    public function acceptDemandeAdhesion($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/demandes');
            return;
        }
        
        // Accepter la demande et ajouter l'étudiant au club
        $success = $this->demandeAdhesionModel->accepterEtAjouterMembre($id);
        
        if ($success) {
            $this->redirect('/admin/demandes?success=1');
        } else {
            $this->redirect('/admin/demandes?error=Une+erreur+est+survenue+lors+de+l%27acceptation+de+la+demande');
        }
    }
    
    /**
     * Refuser une demande d'adhésion
     * 
     * @param int $id ID de la demande
     * @return void
     */
    public function refuseDemandeAdhesion($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/demandes');
            return;
        }
        
        // Refuser la demande
        $success = $this->demandeAdhesionModel->refuser($id);
        
        if ($success) {
            $this->redirect('/admin/demandes?success=1');
        } else {
            $this->redirect('/admin/demandes?error=Une+erreur+est+survenue+lors+du+refus+de+la+demande');
        }
    }
    
    /**
     * Approuver une demande d'activité
     * 
     * @param int $id ID de la demande
     * @return void
     */
    public function approveDemandeActivite($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/demandes');
            return;
        }
        
        // Charger le modèle d'activité si ce n'est pas déjà fait
        if (!isset($this->activiteModel)) {
            require_once APP_PATH . '/models/ActiviteModel.php';
            $this->activiteModel = new ActiviteModel();
        }
        
        // Approuver la demande et créer l'activité
        $activiteId = $this->demandeActiviteModel->approveAndCreateActivite($id, $this->activiteModel);
        
        if ($activiteId) {
            $this->redirect('/admin/demandes?success=1');
        } else {
            $this->redirect('/admin/demandes?error=Une+erreur+est+survenue+lors+de+l%27approbation+de+la+demande+d%27activité');
        }
    }
    
    /**
     * Rejeter une demande d'activité
     * 
     * @param int $id ID de la demande
     * @return void
     */
    public function rejectDemandeActivite($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/demandes');
            return;
        }
        
        // Supprimer la demande (rejet)
        $success = $this->demandeActiviteModel->delete($id);
        
        if ($success) {
            $this->redirect('/admin/demandes?success=1');
        } else {
            $this->redirect('/admin/demandes?error=Une+erreur+est+survenue+lors+du+rejet+de+la+demande+d%27activité');
        }
    }

    /**
     * Récupérer les informations d'une demande de club (AJAX)
     * 
     * @param int $id ID de la demande
     * @return void
     */
    public function getDemandeClub($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            echo json_encode(['success' => false, 'message' => 'ID de demande invalide']);
            return;
        }
        
        // Récupérer les informations de la demande
        $demande = $this->demandeClubModel->getById($id);
        
        if (!$demande) {
            echo json_encode(['success' => false, 'message' => 'Demande introuvable']);
            return;
        }
        
        // Renvoyer les données au format JSON
        echo json_encode([
            'success' => true,
            'demande' => $demande
        ]);
    }
    
    /**
     * Récupérer les informations d'une demande d'activité (AJAX)
     * 
     * @param int $id ID de la demande
     * @return void
     */
    public function getDemandeActivite($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            echo json_encode(['success' => false, 'message' => 'ID de demande invalide']);
            return;
        }
        
        // Récupérer les informations de la demande
        $demande = $this->demandeActiviteModel->getById($id);
        
        if (!$demande) {
            echo json_encode(['success' => false, 'message' => 'Demande introuvable']);
            return;
        }
        
        // Renvoyer les données au format JSON
        echo json_encode([
            'success' => true,
            'demande' => $demande
        ]);
    }
}
?>
