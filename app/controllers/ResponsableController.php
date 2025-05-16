<?php
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/ClubModel.php';
require_once APP_PATH . '/models/ActiviteModel.php';
require_once APP_PATH . '/models/EtudiantModel.php';
require_once APP_PATH . '/models/RessourceModel.php';
require_once APP_PATH . '/models/DemandeActiviteModel.php';
require_once APP_PATH . '/models/DemandeAdhesionModel.php';

/**
 * Classe ResponsableController - Contrôleur pour les responsables de club
 */
class ResponsableController extends Controller {
    private $clubModel;
    private $activiteModel;
    private $etudiantModel;
    private $ressourceModel;
    private $demandeActiviteModel;
    private $demandeAdhesionModel;
    
    /**
     * Constructeur
     */
    public function __construct() {
        // Vérifier si l'utilisateur est connecté en tant que responsable
        $this->checkAuth();
        
        $this->clubModel = new ClubModel();
        $this->activiteModel = new ActiviteModel();
        $this->etudiantModel = new EtudiantModel();
        $this->ressourceModel = new RessourceModel();
        $this->demandeActiviteModel = new DemandeActiviteModel();
        $this->demandeAdhesionModel = new DemandeAdhesionModel();
    }
    
    /**
     * Vérifier l'authentification
     * 
     * @return void
     */
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'responsable') {
            $this->redirect('/login');
        }
    }
    
    /**
     * Tableau de bord du responsable
     * 
     * @return void
     */
    public function index() {
        $clubId = $this->getClubId();
        $club = $this->clubModel->getById($clubId);
        $membres = $this->clubModel->getMembresByClubId($clubId);
        $activites = $this->activiteModel->getByClubId($clubId);
        $demandesAdhesion = $this->demandeAdhesionModel->getByClubId($clubId);
        
        $data = [
            'club' => $club,
            'membres' => $membres,
            'activites' => $activites,
            'demandesAdhesion' => $demandesAdhesion
        ];
        
        $this->view('responsable/dashboard', $data);
    }
    
    /**
     * Obtenir l'ID du club géré par le responsable
     * 
     * @return int ID du club
     */
    private function getClubId() {
        return $this->clubModel->getClubIdByResponsableId($_SESSION['user_id']);
    }
    
    /**
     * Configuration du club (nom, description, logo)
     * 
     * @return void
     */
    public function configurationClub() {
        $clubId = $this->getClubId();
        $club = $this->clubModel->getById($clubId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Traiter la mise à jour des informations du club
            $nom = $_POST['nom'] ?? '';
            $description = $_POST['description'] ?? '';
            
            // Traitement du logo si un fichier a été téléchargé
            $logoPath = $club['logo'];
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $logoPath = $this->processLogoUpload($_FILES['logo']);
            }
            
            // Mettre à jour les informations du club
            $this->clubModel->update($clubId, [
                'nom' => $nom,
                'description' => $description,
                'logo' => $logoPath
            ]);
            
            // Rediriger vers la page de configuration avec un message de succès
            $this->redirect('/responsable/configurationClub?success=1');
        }
        
        $data = [
            'club' => $club
        ];
        
        $this->view('responsable/configuration_club', $data);
    }
    
    /**
     * Traite le téléchargement d'un logo
     * 
     * @param array $file Données du fichier téléchargé
     * @return string Chemin du fichier enregistré
     */
    private function processLogoUpload($file) {
        $targetDir = PUBLIC_PATH . '/uploads/logos/';
        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $targetDir . $fileName;
        
        // Créer le répertoire si nécessaire
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Déplacer le fichier téléchargé
        move_uploaded_file($file['tmp_name'], $targetPath);
        
        // Retourner le chemin relatif
        return '/uploads/logos/' . $fileName;
    }
    
    /**
     * Gestion des membres
     * 
     * @return void
     */
    public function gestionMembres() {
        $clubId = $this->getClubId();
        $membres = $this->clubModel->getMembresByClubId($clubId);
        
        $data = [
            'membres' => $membres
        ];
        
        $this->view('responsable/gestion_membres', $data);
    }
    
    /**
     * Modifier le rôle d'un membre
     * 
     * @return void
     */
    public function modifierRoleMembre() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $membreId = $_POST['membre_id'] ?? 0;
            $role = $_POST['role'] ?? '';
            
            // Vérifier que le membre appartient bien au club du responsable
            $clubId = $this->getClubId();
            if ($this->clubModel->isMembreDuClub($membreId, $clubId)) {
                $this->clubModel->updateRoleMembre($membreId, $clubId, $role);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ce membre n\'appartient pas à votre club']);
            }
            exit;
        }
        
        $this->redirect('/responsable/gestionMembres');
    }
    
    /**
     * Supprimer un membre du club
     * 
     * @return void
     */
    public function supprimerMembre() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $membreId = $_POST['membre_id'] ?? 0;
            
            // Vérifier que le membre appartient bien au club du responsable
            $clubId = $this->getClubId();
            if ($this->clubModel->isMembreDuClub($membreId, $clubId)) {
                $this->clubModel->removeMembre($membreId, $clubId);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ce membre n\'appartient pas à votre club']);
            }
            exit;
        }
        
        $this->redirect('/responsable/gestionMembres');
    }
    
    /**
     * Gestion des activités
     * 
     * @return void
     */
    public function gestionActivites() {
        $clubId = $this->getClubId();
        $activites = $this->activiteModel->getByClubId($clubId);
        $demandesActivite = $this->demandeActiviteModel->getByClubId($clubId);
        
        $data = [
            'activites' => $activites,
            'demandes' => $demandesActivite
        ];
        
        $this->view('responsable/gestion_activites', $data);
    }
    
    /**
     * Créer une nouvelle demande d'activité
     * 
     * @return void
     */
    public function creerDemandeActivite() {
        $clubId = $this->getClubId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $dateDebut = $_POST['date_debut'] ?? '';
            $dateFin = $_POST['date_fin'] ?? '';
            $lieu = $_POST['lieu'] ?? '';
            
            // Créer la demande d'activité
            $demandeId = $this->demandeActiviteModel->create([
                'club_id' => $clubId,
                'titre' => $titre,
                'description' => $description,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'lieu' => $lieu,
                'statut' => 'en_attente',
                'date_creation' => date('Y-m-d H:i:s')
            ]);
            
            // Rediriger vers la page de gestion des activités avec un message de succès
            $this->redirect('/responsable/gestionActivites?success=1');
        }
        
        $this->view('responsable/creer_demande_activite');
    }
    
    /**
     * Réservation de ressources
     * 
     * @return void
     */
    public function reservationRessources() {
        $ressources = $this->ressourceModel->getAll();
        
        $data = [
            'ressources' => $ressources
        ];
        
        $this->view('responsable/reservation_ressources', $data);
    }
    
    /**
     * Demander une réservation de ressource
     * 
     * @return void
     */
    public function demanderRessource() {
        $clubId = $this->getClubId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ressourceId = $_POST['ressource_id'] ?? 0;
            $dateDebut = $_POST['date_debut'] ?? '';
            $dateFin = $_POST['date_fin'] ?? '';
            $motif = $_POST['motif'] ?? '';
            
            // Vérifier la disponibilité de la ressource
            if ($this->ressourceModel->isDisponible($ressourceId, $dateDebut, $dateFin)) {
                // Créer la réservation
                $this->ressourceModel->createReservation([
                    'ressource_id' => $ressourceId,
                    'club_id' => $clubId,
                    'date_debut' => $dateDebut,
                    'date_fin' => $dateFin,
                    'motif' => $motif,
                    'statut' => 'en_attente',
                    'date_creation' => date('Y-m-d H:i:s')
                ]);
                
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'La ressource n\'est pas disponible pour cette période'
                ]);
            }
            exit;
        }
        
        $this->redirect('/responsable/reservationRessources');
    }
    
    /**
     * Gestion des demandes d'adhésion
     * 
     * @return void
     */
    public function gestionDemandesAdhesion() {
        $clubId = $this->getClubId();
        $demandes = $this->demandeAdhesionModel->getByClubId($clubId);
        
        $data = [
            'demandes' => $demandes
        ];
        
        $this->view('responsable/gestion_demandes_adhesion', $data);
    }
    
    /**
     * Traiter une demande d'adhésion
     * 
     * @return void
     */
    public function traiterDemandeAdhesion() {
        $clubId = $this->getClubId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $demandeId = $_POST['demande_id'] ?? 0;
            $statut = $_POST['statut'] ?? ''; // 'acceptee' ou 'refusee'
            
            // Vérifier que la demande appartient bien au club du responsable
            $demande = $this->demandeAdhesionModel->getById($demandeId);
            if ($demande && $demande['club_id'] == $clubId) {
                // Mettre à jour le statut de la demande
                $this->demandeAdhesionModel->updateStatut($demandeId, $statut);
                
                // Si la demande est acceptée, ajouter l'étudiant comme membre du club
                if ($statut === 'acceptee') {
                    $this->clubModel->addMembre($demande['etudiant_id'], $clubId, 'membre');
                }
                
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Cette demande n\'appartient pas à votre club'
                ]);
            }
            exit;
        }
        
        $this->redirect('/responsable/gestionDemandesAdhesion');
    }
    
    /**
     * Gestion du blog du club
     * 
     * @return void
     */
    public function gestionBlog() {
        $clubId = $this->getClubId();
        $articles = $this->clubModel->getBlogArticles($clubId);
        
        $data = [
            'articles' => $articles
        ];
        
        $this->view('responsable/gestion_blog', $data);
    }
    
    /**
     * Créer un nouvel article de blog
     * 
     * @return void
     */
    public function creerArticleBlog() {
        $clubId = $this->getClubId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
            
            // Traitement de l'image si un fichier a été téléchargé
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->processImageUpload($_FILES['image']);
            }
            
            // Créer l'article
            $this->clubModel->createBlogArticle([
                'club_id' => $clubId,
                'titre' => $titre,
                'contenu' => $contenu,
                'image' => $imagePath,
                'date_creation' => date('Y-m-d H:i:s')
            ]);
            
            $this->redirect('/responsable/gestionBlog?success=1');
        }
        
        $this->view('responsable/creer_article_blog');
    }
    
    /**
     * Modifier un article de blog
     * 
     * @param int $id ID de l'article
     * @return void
     */
    public function modifierArticleBlog($id = null) {
        $clubId = $this->getClubId();
        
        if (!$id) {
            $this->redirect('/responsable/gestionBlog');
        }
        
        $article = $this->clubModel->getBlogArticleById($id);
        
        // Vérifier que l'article appartient bien au club du responsable
        if (!$article || $article['club_id'] != $clubId) {
            $this->redirect('/responsable/gestionBlog');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
            
            // Traitement de l'image si un fichier a été téléchargé
            $imagePath = $article['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->processImageUpload($_FILES['image']);
            }
            
            // Mettre à jour l'article
            $this->clubModel->updateBlogArticle($id, [
                'titre' => $titre,
                'contenu' => $contenu,
                'image' => $imagePath,
                'date_modification' => date('Y-m-d H:i:s')
            ]);
            
            $this->redirect('/responsable/gestionBlog?success=2');
        }
        
        $data = [
            'article' => $article
        ];
        
        $this->view('responsable/modifier_article_blog', $data);
    }
    
    /**
     * Supprimer un article de blog
     * 
     * @return void
     */
    public function supprimerArticleBlog() {
        $clubId = $this->getClubId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $articleId = $_POST['article_id'] ?? 0;
            
            // Vérifier que l'article appartient bien au club du responsable
            $article = $this->clubModel->getBlogArticleById($articleId);
            if ($article && $article['club_id'] == $clubId) {
                $this->clubModel->deleteBlogArticle($articleId);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Cet article n\'appartient pas à votre club'
                ]);
            }
            exit;
        }
        
        $this->redirect('/responsable/gestionBlog');
    }
    
    /**
     * Traite le téléchargement d'une image
     * 
     * @param array $file Données du fichier téléchargé
     * @return string Chemin du fichier enregistré
     */
    private function processImageUpload($file) {
        $targetDir = PUBLIC_PATH . '/uploads/blog/';
        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $targetDir . $fileName;
        
        // Créer le répertoire si nécessaire
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Déplacer le fichier téléchargé
        move_uploaded_file($file['tmp_name'], $targetPath);
        
        // Retourner le chemin relatif
        return '/uploads/blog/' . $fileName;
    }
    
    /**
     * Gestion des feuilles de présence
     * 
     * @return void
     */
    public function gestionPresence() {
        $clubId = $this->getClubId();
        $activites = $this->activiteModel->getByClubId($clubId);
        
        $data = [
            'activites' => $activites
        ];
        
        $this->view('responsable/gestion_presence', $data);
    }
    
    /**
     * Gérer la présence pour une activité spécifique
     * 
     * @param int $id ID de l'activité
     * @return void
     */
    public function presenceActivite($id = null) {
        $clubId = $this->getClubId();
        
        if (!$id) {
            $this->redirect('/responsable/gestionPresence');
        }
        
        $activite = $this->activiteModel->getById($id);
        
        // Vérifier que l'activité appartient bien au club du responsable
        if (!$activite || $activite['club_id'] != $clubId) {
            $this->redirect('/responsable/gestionPresence');
        }
        
        $participants = $this->activiteModel->getParticipantsByActiviteId($id);
        
        $data = [
            'activite' => $activite,
            'participants' => $participants
        ];
        
        $this->view('responsable/presence_activite', $data);
    }
    
    /**
     * Marquer la présence d'un participant
     * 
     * @return void
     */
    public function marquerPresence() {
        $clubId = $this->getClubId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activiteId = $_POST['activite_id'] ?? 0;
            $etudiantId = $_POST['etudiant_id'] ?? 0;
            $present = isset($_POST['present']) ? (($_POST['present'] === 'true') ? true : false) : false;
            
            // Vérifier que l'activité appartient bien au club du responsable
            $activite = $this->activiteModel->getById($activiteId);
            if ($activite && $activite['club_id'] == $clubId) {
                $this->activiteModel->updateParticipantStatut($activiteId, $etudiantId, $present ? 'present' : 'absent');
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Cette activité n\'appartient pas à votre club'
                ]);
            }
            exit;
        }
        
        $this->redirect('/responsable/gestionPresence');
    }
}
