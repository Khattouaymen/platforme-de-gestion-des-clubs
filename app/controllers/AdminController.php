<?php
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/AdminModel.php';
require_once APP_PATH . '/models/ClubModel.php';
require_once APP_PATH . '/models/EtudiantModel.php';
require_once APP_PATH . '/models/RessourceModel.php';
require_once APP_PATH . '/models/DemandeClubModel.php';
require_once APP_PATH . '/models/DemandeActiviteModel.php';
require_once APP_PATH . '/models/DemandeAdhesionModel.php';
require_once APP_PATH . '/models/ReservationModel.php';
require_once APP_PATH . '/models/ActiviteModel.php'; // Added this line
require_once APP_PATH . '/models/ContactModel.php';

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
    private $demandeAdhesionModel;    private $reservationModel;
    private $activiteModel; // Added this line
    private $contactModel;
    
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
        $this->demandeAdhesionModel = new DemandeAdhesionModel();        $this->reservationModel = new ReservationModel();
        $this->activiteModel = new ActiviteModel(); // Added this line
        $this->contactModel = new ContactModel();
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
            $alertError = "Une erreur est survenue lors de la mise à jour du club.";        
        } elseif (isset($_GET['delete_error'])) {
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
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
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
     */    public function responsableLink() {
        try {
            // Générer un token unique
            $token = bin2hex(random_bytes(32));
            error_log("Token généré: $token");
            
            // Sauvegarder le token dans la base de données
            $tokenId = $this->adminModel->saveResponsableToken($token);
            error_log("Token ID retourné: " . ($tokenId ? $tokenId : 'false'));
            
            if (!$tokenId) {
                error_log("Erreur: tokenId est false, redirection vers admin avec erreur");
                $this->redirect('/admin?error=Erreur+lors+de+la+génération+du+lien');
                return;
            }
            
            // Créer le lien d'inscription
            $baseUrl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
            $baseUrl .= $_SERVER['HTTP_HOST'];
            $lien = $baseUrl . "/auth/register/responsable/" . $token;
            error_log("Lien généré: $lien");
            
            $data = [
                'title' => 'Lien d\'inscription pour responsable de club',
                'lien' => $lien,
                'asset' => function($path) { return $this->asset($path); }
            ];
            
            $this->view('admin/responsable_link', $data);
        } catch (Exception $e) {
            error_log("Exception dans responsableLink: " . $e->getMessage());
            $this->redirect('/admin?error=Erreur+lors+de+la+génération+du+lien');
        }
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
        
        // Demandes en attente - total
        $demandesClub = $this->demandeClubModel->getByStatut('en_attente');
        $demandesClubCount = count($demandesClub);
        
        $demandesAdhesion = $this->demandeAdhesionModel->getByStatut('en_attente');
        $demandesAdhesionCount = count($demandesAdhesion);
        
        $demandesActivite = $this->demandeActiviteModel->getByStatut('en_attente');
        $demandesActiviteCount = count($demandesActivite);
        
        $totalDemandesEnAttente = $demandesClubCount + $demandesAdhesionCount + $demandesActiviteCount;
        
        // Activités en cours (activités approuvées et futures ou en cours)
        $activitesEnCours = $this->getActivitesEnCours();
        $activitesEnCoursCount = count($activitesEnCours);
        
        // Statistiques des clubs avec nombre d'activités réel
        $clubsAvecActivites = $this->getClubsAvecStatistiques();
        
        // Répartition des étudiants par niveau
        $repartitionNiveaux = $this->getRepartitionEtudiantsParNiveau($etudiants);
        
        // Évolution des activités par mois (12 derniers mois)
        $evolutionActivites = $this->getEvolutionActivitesParMois();
        
        $data = [
            'title' => 'Statistiques',
            'clubs' => $clubs,
            'etudiants' => $etudiants,
            'club_count' => $clubCount,
            'etudiant_count' => $etudiantCount,
            'demandes_club_count' => $demandesClubCount,
            'demandes_adhesion_count' => $demandesAdhesionCount,
            'demandes_activite_count' => $demandesActiviteCount,
            'total_demandes_en_attente' => $totalDemandesEnAttente,
            'activites_en_cours_count' => $activitesEnCoursCount,
            'clubs_avec_activites' => $clubsAvecActivites,
            'repartition_niveaux' => $repartitionNiveaux,
            'evolution_activites' => $evolutionActivites,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/statistiques', $data);
    }
      /**
     * Récupère les activités en cours (approuvées et futures/actuelles)
     * 
     * @return array Liste des activités en cours
     */
    private function getActivitesEnCours() {
        // Utiliser l'ActiviteModel existant du contrôleur
        $activites = $this->activiteModel->getAll();
        
        // Filtrer les activités en cours (futures ou actuelles)
        $activitesEnCours = [];
        $today = date('Y-m-d');
        
        foreach ($activites as $activite) {
            // Vérifier si l'activité est encore en cours ou future
            $dateActivite = $activite['date_activite'] ?? null;
            $dateDebut = $activite['date_debut'] ?? null;
            $dateFin = $activite['date_fin'] ?? null;
            
            $isEnCours = false;
            
            if ($dateDebut && $dateFin) {
                // Si on a une période, vérifier si aujourd'hui est dans la période ou après le début
                $isEnCours = ($dateDebut >= $today || $dateFin >= $today);
            } elseif ($dateActivite) {
                // Si on a juste une date d'activité, vérifier si elle est future ou aujourd'hui
                $isEnCours = ($dateActivite >= $today);
            }
            
            if ($isEnCours) {
                $activitesEnCours[] = $activite;
            }
        }
        
        return $activitesEnCours;
    }
      /**
     * Récupère les clubs avec leurs statistiques d'activités
     * 
     * @return array Clubs avec nombre d'activités et performance
     */
    private function getClubsAvecStatistiques() {
        // Récupérer tous les clubs avec leurs détails
        $clubs = $this->clubModel->getAllWithDetails();
        
        // Pour chaque club, calculer les statistiques réelles
        foreach ($clubs as &$club) {
            // Récupérer les activités du club
            $activites = $this->activiteModel->getByClubId($club['id']);
            $club['nombre_activites'] = count($activites);
            
            // Calculer la performance basée sur les participations
            $totalParticipations = 0;
            $totalPresents = 0;
            
            foreach ($activites as $activite) {
                $participants = $this->activiteModel->getParticipantsByActiviteId($activite['activite_id']);
                $totalParticipations += count($participants);
                
                foreach ($participants as $participant) {
                    if ($participant['statut'] === 'participe') {
                        $totalPresents++;
                    }
                }
            }
            
            // Calculer le pourcentage de performance
            if ($totalParticipations > 0) {
                $club['performance'] = round(($totalPresents / $totalParticipations) * 100);
            } else {
                $club['performance'] = 0;
            }
        }
        
        // Trier par nombre d'activités décroissant
        usort($clubs, function($a, $b) {
            return $b['nombre_activites'] - $a['nombre_activites'];
        });
        
        return $clubs;
    }
    
    /**
     * Calcule la répartition des étudiants par niveau
     * 
     * @param array $etudiants Liste des étudiants
     * @return array Répartition par niveau
     */
    private function getRepartitionEtudiantsParNiveau($etudiants) {
        $repartition = [];
        
        foreach ($etudiants as $etudiant) {
            $niveau = $etudiant['niveau'] ?? 'Non défini';
            if (!isset($repartition[$niveau])) {
                $repartition[$niveau] = 0;
            }
            $repartition[$niveau]++;
        }
        
        return $repartition;
    }
      /**
     * Récupère l'évolution des activités par mois (12 derniers mois)
     * 
     * @return array Données d'évolution
     */
    private function getEvolutionActivitesParMois() {
        // Récupérer toutes les activités pour analyser leur évolution
        $activites = $this->activiteModel->getAll();
        
        // Créer un tableau avec les 12 derniers mois
        $evolution = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $evolution[$date] = 0;
        }
        
        // Compter les activités par mois de création
        foreach ($activites as $activite) {
            $dateCreation = $activite['date_creation'] ?? null;
            if ($dateCreation) {
                $moisCreation = date('Y-m', strtotime($dateCreation));
                if (isset($evolution[$moisCreation])) {
                    $evolution[$moisCreation]++;
                }
            }
        }
        
        return array_values($evolution);
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
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'default' => 1]]);
        $clubId = filter_input(INPUT_POST, 'club_id', FILTER_VALIDATE_INT);
        $disponibilite = filter_input(INPUT_POST, 'disponibilite', FILTER_SANITIZE_FULL_SPECIAL_CHARS);        // Valider les entrées
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
            'nom_ressource' => $nom, 
            'type_ressource' => $type,
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
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'default' => 1]]);
        $clubId = filter_input(INPUT_POST, 'club_id', FILTER_VALIDATE_INT);
        $disponibilite = filter_input(INPUT_POST, 'disponibilite', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
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
            $this->redirect('/admin/ressources?error=' . urlencode($errorMessage) . '&id_edit=' . $id);
            return;
        }
        
        // Mettre à jour la ressource
        $ressourceData = [
            'nom_ressource' => $nom, // Ensure key matches column name
            'type_ressource' => $type, // Ensure key matches column name
            'quantite' => $quantite,
            'club_id' => $clubId ?: null,
            'disponibilite' => $disponibilite ?: 'disponible'
        ];
        
        $success = $this->ressourceModel->update($id, $ressourceData);
        
        if ($success) {
            $this->redirect('/admin/ressources?update_success=1');
        } else {
            $this->redirect('/admin/ressources?error=Une+erreur+est+survenue+lors+de+la+mise+à+jour+de+la+ressource' . '&id_edit=' . $id);
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
        
        // Récupérer les demandes d'activité (toutes et par statut)
        $demandesActivite = [
            'toutes' => $this->demandeActiviteModel->getAll(),
            'en_attente' => $this->demandeActiviteModel->getByStatut('en_attente'),
            'approuvee' => $this->demandeActiviteModel->getByStatut('approuvee'),
            'refusee' => $this->demandeActiviteModel->getByStatut('refusee')
        ];
        
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
    public function approveActivite($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/demandes');
            return;
        }
        
        // Approuver la demande et créer l'activité correspondante
        $success = $this->demandeActiviteModel->approveAndCreateActivite($id, $this->activiteModel);
        
        if ($success) {
            $this->redirect('/admin/demandes?success=La+demande+d%27activité+a+été+approuvée+et+l\'activité+créée');
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
    public function rejectActivite($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/demandes');
            return;
        }
        
        // Récupérer le commentaire de rejet s'il existe
        $commentaire = $_POST['commentaire'] ?? '';
        
        // Mettre à jour le statut de la demande à "refusee" et ajouter le commentaire
        $data = [
            'statut' => 'refusee',
            'commentaire' => $commentaire
        ];
        
        $success = $this->demandeActiviteModel->updateStatut($id, $data);
        
        if ($success) {
            $this->redirect('/admin/demandes?success=La+demande+d%27activité+a+été+rejetée');
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

    /**
     * Affiche la page de gestion des responsables de club
     * 
     * @return void
     */
    public function gestionResponsables() {
        // Récupérer la liste des étudiants qui sont marqués comme futurs responsables
        $futursResponsables = $this->adminModel->getFutureResponsables();
        
        // Récupérer la liste des responsables actuels
        $responsables = $this->adminModel->getResponsables();
        
        // Récupérer la liste des clubs pour les menus déroulants
        $clubs = $this->clubModel->getAll();
        
        $data = [
            'title' => 'Gestion des Responsables',
            'futursResponsables' => $futursResponsables,
            'responsables' => $responsables,
            'clubs' => $clubs,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/gestion_responsables', $data);
    }
    
    /**
     * Assigne un étudiant comme responsable d'un club
     * 
     * @return void
     */
    public function assignerResponsable() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/gestionResponsables?error=Méthode non autorisée');
            return;
        }
        
        // Récupérer et valider les données du formulaire
        $etudiantId = isset($_POST['etudiant_id']) ? (int)$_POST['etudiant_id'] : 0;
        $clubId = isset($_POST['club_id']) ? (int)$_POST['club_id'] : 0;
        
        if ($etudiantId <= 0 || $clubId <= 0) {
            $this->redirect('/admin/gestionResponsables?error=Données invalides');
            return;
        }
        
        // Assigner l'étudiant comme responsable du club
        $success = $this->adminModel->assignerResponsable($etudiantId, $clubId);
        
        if ($success) {
            $this->redirect('/admin/gestionResponsables?success=Étudiant assigné comme responsable avec succès');
        } else {
            $this->redirect('/admin/gestionResponsables?error=Erreur lors de l\'assignation. Veuillez réessayer.');
        }
    }
    
    /**
     * Change le club d'un responsable
     * 
     * @return void
     */
    public function changerClubResponsable() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/gestionResponsables?error=Méthode non autorisée');
            return;
        }
        
        // Récupérer et valider les données du formulaire
        $responsableId = isset($_POST['responsable_id']) ? (int)$_POST['responsable_id'] : 0;
        $clubId = isset($_POST['club_id']) ? (int)$_POST['club_id'] : 0;
        
        if ($responsableId <= 0 || $clubId <= 0) {
            $this->redirect('/admin/gestionResponsables?error=Données invalides');
            return;
        }
        
        // Changer le club du responsable
        $success = $this->adminModel->changerClubResponsable($responsableId, $clubId);
        
        if ($success) {
            $this->redirect('/admin/gestionResponsables?success=Club du responsable changé avec succès');
        } else {
            $this->redirect('/admin/gestionResponsables?error=Erreur lors du changement de club. Veuillez réessayer.');
        }
    }
    
    /**
     * Retire le rôle de responsable à un étudiant
     * 
     * @return void
     */
    public function retirerResponsable() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/gestionResponsables?error=Méthode non autorisée');
            return;
        }
        
        // Récupérer et valider les données du formulaire
        $responsableId = isset($_POST['responsable_id']) ? (int)$_POST['responsable_id'] : 0;
        
        if ($responsableId <= 0) {
            $this->redirect('/admin/gestionResponsables?error=Données invalides');
            return;
        }
        
        // Retirer le rôle de responsable à l'étudiant
        $success = $this->adminModel->retirerResponsable($responsableId);
        
        if ($success) {
            $this->redirect('/admin/gestionResponsables?success=Rôle de responsable retiré avec succès');
        } else {
            $this->redirect('/admin/gestionResponsables?error=Erreur lors du retrait du rôle de responsable. Veuillez réessayer.');
        }
    }

    /**
     * Gestion des réservations de ressources
     * 
     * @return void
     */
    public function gererReservations() {
        // Récupérer toutes les réservations
        $reservations = $this->reservationModel->getAll();
        
        // Filtrer par statut si demandé
        $filtreStatut = $_GET['statut'] ?? 'tous';
        if ($filtreStatut !== 'tous') {
            $reservationsFiltrees = array_filter($reservations, function($r) use ($filtreStatut) {
                return $r['statut'] === $filtreStatut;
            });
            $reservations = $reservationsFiltrees;
        }
        
        $data = [
            'title' => 'Gestion des Réservations',
            'reservations' => $reservations,
            'filtreStatut' => $filtreStatut,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/reservations', $data);
    }
    
    /**
     * Approuver une réservation
     * 
     * @param int $id ID de la réservation
     * @return void
     */
    public function approuverReservation($id = null) {
        if (!$id) {
            $this->redirect('/admin/gererReservations?error=' . urlencode('ID de réservation non spécifié'));
            return;
        }
        
        // Récupérer la réservation
        $reservation = $this->reservationModel->getById($id);
        
        if (!$reservation) {
            $this->redirect('/admin/gererReservations?error=' . urlencode('Réservation non trouvée'));
            return;
        }
        
        // Vérifier si la réservation est en attente
        if ($reservation['statut'] !== 'en_attente') {
            $this->redirect('/admin/gererReservations?error=' . urlencode('Cette réservation a déjà été traitée'));
            return;
        }
        
        // Vérifier la disponibilité de la ressource avant d'approuver
        if (!$this->reservationModel->isRessourceAvailable(
            $reservation['ressource_id'], 
            $reservation['date_debut'], 
            $reservation['date_fin'], 
            $reservation['id_reservation']
        )) {
            $this->redirect('/admin/gererReservations?error=' . urlencode('La ressource n\'est plus disponible pour cette période'));
            return;
        }
        
        // Approuver la réservation
        if ($this->reservationModel->approve($id)) {
            $this->redirect('/admin/gererReservations?success=' . urlencode('Réservation approuvée avec succès'));
        } else {
            $this->redirect('/admin/gererReservations?error=' . urlencode('Erreur lors de l\'approbation de la réservation'));
        }
    }
    
    /**
     * Rejeter une réservation
     * 
     * @param int $id ID de la réservation
     * @return void
     */
    public function rejeterReservation($id = null) {
        if (!$id) {
            $this->redirect('/admin/gererReservations?error=' . urlencode('ID de réservation non spécifié'));
            return;
        }
        
        // Récupérer la réservation
        $reservation = $this->reservationModel->getById($id);
        
        if (!$reservation) {
            $this->redirect('/admin/gererReservations?error=' . urlencode('Réservation non trouvée'));
            return;
        }
        
        // Vérifier si la réservation est en attente
        if ($reservation['statut'] !== 'en_attente') {
            $this->redirect('/admin/gererReservations?error=' . urlencode('Cette réservation a déjà été traitée'));
            return;
        }
          // Rejeter la réservation
        if ($this->reservationModel->reject($id)) {
            $this->redirect('/admin/gererReservations?success=' . urlencode('Réservation rejetée avec succès'));
        } else {
            $this->redirect('/admin/gererReservations?error=' . urlencode('Erreur lors du rejet de la réservation'));
        }
    }

    /**
     * Affiche la liste des messages de contact
     * 
     * @return void
     */
    public function messages() {
        $messages = $this->contactModel->getAllMessages();
        $stats = $this->contactModel->getStatistics();
        
        $data = [
            'title' => 'Gestion des Messages de Contact',
            'messages' => $messages,
            'stats' => $stats,
            'asset' => function($path) { return $this->asset($path); },
            'alertSuccess' => $_GET['success'] ?? null,
            'alertError' => $_GET['error'] ?? null
        ];
        
        $this->view('admin/messages', $data);
    }
    
    /**
     * Affiche les détails d'un message de contact
     * 
     * @param int $id ID du message
     * @return void
     */
    public function messageDetails($id = null) {
        if (!$id) {
            $this->redirect('/admin/messages?error=' . urlencode('ID du message non spécifié'));
            return;
        }
        
        $message = $this->contactModel->getMessageById($id);
        
        if (!$message) {
            $this->redirect('/admin/messages?error=' . urlencode('Message non trouvé'));
            return;
        }
        
        // Marquer le message comme lu s'il ne l'était pas déjà
        if ($message['statut'] === 'non_lu') {
            $this->contactModel->markAsRead($id);
        }
        
        $data = [
            'title' => 'Détails du Message - ' . $message['nom'],
            'message' => $message,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('admin/message_details', $data);
    }
    
    /**
     * Marque un message comme traité
     * 
     * @param int $id ID du message
     * @return void
     */
    public function markMessageProcessed($id = null) {
        if (!$id) {
            $this->redirect('/admin/messages?error=' . urlencode('ID du message non spécifié'));
            return;
        }
        
        if ($this->contactModel->markAsProcessed($id)) {
            $this->redirect('/admin/messages?success=' . urlencode('Message marqué comme traité'));
        } else {
            $this->redirect('/admin/messages?error=' . urlencode('Erreur lors du traitement du message'));
        }
    }
    
    /**
     * Supprime un message de contact
     * 
     * @param int $id ID du message
     * @return void
     */
    public function deleteMessage($id = null) {
        if (!$id) {
            $this->redirect('/admin/messages?error=' . urlencode('ID du message non spécifié'));
            return;
        }
          if ($this->contactModel->deleteMessage($id)) {
            $this->redirect('/admin/messages?success=' . urlencode('Message supprimé avec succès'));
        } else {
            $this->redirect('/admin/messages?error=' . urlencode('Erreur lors de la suppression du message'));
        }
    }
}
?>
