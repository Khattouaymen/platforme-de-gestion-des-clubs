<?php
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/ClubModel.php';
require_once APP_PATH . '/models/ActiviteModel.php';
require_once APP_PATH . '/models/EtudiantModel.php';
require_once APP_PATH . '/models/RessourceModel.php';
require_once APP_PATH . '/models/DemandeActiviteModel.php';
require_once APP_PATH . '/models/DemandeAdhesionModel.php';
require_once APP_PATH . '/models/ReservationModel.php';

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
    private $reservationModel;
    
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
        $this->reservationModel = new ReservationModel();
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
        // Vérifier s'il y a des activités nouvellement approuvées
        $activitesApprouvees = $this->activiteModel->getApprovedActivitiesWithoutNotification($clubId);
        $activitesSansReservation = $this->activiteModel->getActivitiesWithoutReservation($clubId);
        
        // Marquer les activités approuvées comme notifiées
        foreach ($activitesApprouvees as $activite) {
            $this->activiteModel->markAsNotified($activite['activite_id']);
        }
        
        $data = [
            'club' => $club,
            'membres' => $membres,
            'activites' => $activites,
            'demandesAdhesion' => $demandesAdhesion,
            'activitesApprouvees' => $activitesApprouvees,
            'activitesSansReservation' => $activitesSansReservation
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
    }    /**
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
            
            // Récupérer les données du membre pour obtenir l'ID étudiant
            $membre = $this->clubModel->getMembreById($membreId);
            
            if ($membre && $membre['club_id'] == $clubId) {
                $success = $this->clubModel->updateMemberRole($clubId, $membre['id_etudiant'], $role);
                echo json_encode(['success' => $success]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ce membre n\'appartient pas à votre club']);
            }
            exit;
        }
        
        $this->redirect('/responsable/gestionMembres');
    }    /**
     * Supprimer un membre du club
     * 
     * @return void
     */
    public function supprimerMembre() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $membreId = $_POST['membre_id'] ?? 0;
            
            // Vérifier que le membre appartient bien au club du responsable
            $clubId = $this->getClubId();
            
            // Récupérer les données du membre pour obtenir l'ID étudiant
            $membre = $this->clubModel->getMembreById($membreId);
            
            if ($membre && $membre['club_id'] == $clubId) {
                $success = $this->clubModel->removeMember($clubId, $membre['id_etudiant']);
                echo json_encode(['success' => $success]);
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
            $nombreMax = $_POST['nombre_max'] ?? null;
            $posterUrl = $_POST['poster_url'] ?? '';
            
            // Créer la demande d'activité
            $demandeId = $this->demandeActiviteModel->create([
                'club_id' => $clubId,
                'titre' => $titre,
                'description' => $description,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'lieu' => $lieu,
                'nombre_max' => $nombreMax,
                'poster_url' => $posterUrl,
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
     * Liste des réservations du club
     * 
     * @return void
     */
    public function reservations() {
        $clubId = $this->getClubId();
        $reservations = $this->reservationModel->getByClubId($clubId);
        $activitesSansReservation = $this->activiteModel->getActivitiesWithoutReservation($clubId);
        
        $data = [
            'reservations' => $reservations,
            'activitesSansReservation' => $activitesSansReservation
        ];
        
        $this->view('responsable/reservations', $data);
    }
    
    /**
     * Créer une nouvelle réservation pour une activité
     * 
     * @return void
     */
    public function creerReservation() {
        $clubId = $this->getClubId();
        
        // Récupérer les activités sans réservation
        $activitesSansReservation = $this->activiteModel->getActivitiesWithoutReservation($clubId);
        
        // Récupérer les ressources disponibles
        $ressources = $this->ressourceModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $activiteId = $_POST['activite_id'] ?? null;
            $ressourceId = $_POST['ressource_id'] ?? null;
            $dateDebut = $_POST['date_debut'] ?? null;
            $dateFin = $_POST['date_fin'] ?? null;
            $description = $_POST['description'] ?? '';
            
            // Valider les données
            if ($activiteId && $ressourceId && $dateDebut && $dateFin) {
                // Vérifier si la ressource est disponible
                if ($this->reservationModel->isRessourceAvailable($ressourceId, $dateDebut, $dateFin)) {
                    // Créer la réservation
                    $reservationData = [
                        'ressource_id' => $ressourceId,
                        'club_id' => $clubId,
                        'activite_id' => $activiteId,
                        'date_debut' => $dateDebut,
                        'date_fin' => $dateFin,
                        'description' => $description,
                        'statut' => 'en_attente',
                        'date_reservation' => date('Y-m-d H:i:s')
                    ];
                    
                    $reservationId = $this->reservationModel->create($reservationData);
                    
                    if ($reservationId) {
                        $this->redirect('/responsable/reservations?success=La réservation a été créée avec succès.');
                    } else {
                        $this->redirect('/responsable/creerReservation?error=Une erreur est survenue lors de la création de la réservation.');
                    }
                } else {
                    $this->redirect('/responsable/creerReservation?error=La ressource n\'est pas disponible pour la période demandée.');
                }
            } else {
                $this->redirect('/responsable/creerReservation?error=Veuillez remplir tous les champs obligatoires.');
            }
        }
        
        $data = [
            'activites' => $activitesSansReservation,
            'ressources' => $ressources
        ];
        
        $this->view('responsable/creer_reservation', $data);
    }
    
    /**
     * Annuler une réservation
     * 
     * @param int $id ID de la réservation
     * @return void
     */
    public function annulerReservation($id = null) {
        if (!$id) {
            $this->redirect('/responsable/reservations?error=Réservation non spécifiée.');
            return;
        }
        
        $clubId = $this->getClubId();
        
        // Vérifier si la réservation appartient au club
        $reservation = $this->reservationModel->getById($id);
        
        if (!$reservation || $reservation['club_id'] != $clubId) {
            $this->redirect('/responsable/reservations?error=Vous n\'êtes pas autorisé à annuler cette réservation.');
            return;
        }
        
        // Vérifier si la réservation peut être annulée (seulement en attente)
        if ($reservation['statut'] != 'en_attente') {
            $this->redirect('/responsable/reservations?error=Cette réservation ne peut plus être annulée.');
            return;
        }
        
        // Annuler la réservation
        if ($this->reservationModel->delete($id)) {
            $this->redirect('/responsable/reservations?success=La réservation a été annulée avec succès.');
        } else {
            $this->redirect('/responsable/reservations?error=Une erreur est survenue lors de l\'annulation de la réservation.');
        }
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
     * Traiter une demande d'adhésion (accepter ou refuser)
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
                // Utiliser la bonne méthode selon le statut
                $success = false;
                
                if ($statut === 'acceptee') {
                    $success = $this->demandeAdhesionModel->accepterEtAjouterMembre($demandeId);
                } else if ($statut === 'refusee') {
                    $success = $this->demandeAdhesionModel->refuser($demandeId);
                }
                
                echo json_encode(['success' => $success]);
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
        
        // Utiliser BlogModel pour récupérer les articles
        require_once APP_PATH . '/models/BlogModel.php';
        $blogModel = new BlogModel();
        $articles = $blogModel->getAllBlogArticlesForClub($clubId);
        
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
            $imageUrl = $_POST['image_url'] ?? null;
            $visibility = $_POST['visibility'] ?? 'public';
            
            // Créer l'article
            require_once APP_PATH . '/models/BlogModel.php';
            $blogModel = new BlogModel();
            $blogModel->createBlogArticle([
                'club_id' => $clubId,
                'titre' => $titre,
                'contenu' => $contenu,
                'image_url' => $imageUrl,
                'visibility' => $visibility,
                'user_id' => $_SESSION['user_id']
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
        
        // Utiliser BlogModel pour la récupération de l'article
        require_once APP_PATH . '/models/BlogModel.php';
        $blogModel = new BlogModel();
        $article = $blogModel->getBlogArticleById($id);
        
        // Vérifier que l'article appartient bien au club du responsable
        if (!$article || $article['club_id'] != $clubId) {
            $this->redirect('/responsable/gestionBlog');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
            $imageUrl = $_POST['image_url'] ?? null;
            $visibility = $_POST['visibility'] ?? 'public';
            
            // Mettre à jour l'article
            $blogModel->updateBlogArticle($id, [
                'titre' => $titre,
                'contenu' => $contenu,
                'image_url' => $imageUrl,
                'visibility' => $visibility
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
     */    public function supprimerArticleBlog() {
        $clubId = $this->getClubId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $articleId = $_POST['article_id'] ?? 0;
            
            // Vérifier que l'article appartient bien au club du responsable
            require_once APP_PATH . '/models/BlogModel.php';
            $blogModel = new BlogModel();
            $article = $blogModel->getBlogArticleById($articleId);
            
            if ($article && $article['club_id'] == $clubId) {
                $blogModel->deleteBlogArticle($articleId);
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
     * Gestion des feuilles de présence
     * 
     * @return void
     */
    public function gestionPresence() {
        $clubId = $this->getClubId();
        $activites = $this->activiteModel->getByClubId($clubId);
        
        // Calculer les statistiques de présence pour chaque activité
        foreach ($activites as &$activite) {
            // Récupérer les statistiques de présence pour cette activité
            $stats = $this->activiteModel->getPresenceStatsByActiviteId($activite['activite_id']);
            
            // Si aucun résultat n'est trouvé, initialiser les statistiques à zéro
            if (!$stats) {
                $activite['presents'] = 0;
                $activite['absents'] = 0;
                $activite['non_verifies'] = 0;
                $activite['total_participants'] = 0;
            } else {
                // Ajouter les statistiques à l'activité
                $activite['presents'] = (int)$stats['presents'];
                $activite['absents'] = (int)$stats['absents'];
                $activite['non_verifies'] = (int)$stats['non_verifies'];
                $activite['total_participants'] = (int)$stats['total'];
            }
        }
        
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
     */    public function marquerPresence() {
        $clubId = $this->getClubId();
        $logFile = 'C:/Users/Pavilion/sfe/debug_presence.log';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activiteId = $_POST['activite_id'] ?? 0;
            $etudiantId = $_POST['etudiant_id'] ?? 0;
            $present = isset($_POST['present']) ? (($_POST['present'] === 'true') ? true : false) : false;
            
            // Journalisation des données reçues pour débogage
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Requête de marquage présence:\n", FILE_APPEND);
            file_put_contents($logFile, "activiteId: $activiteId, etudiantId: $etudiantId, present: " . ($present ? 'true' : 'false') . "\n", FILE_APPEND);
            
            // Vérifier que l'activité appartient bien au club du responsable
            $activite = $this->activiteModel->getById($activiteId);
            file_put_contents($logFile, "Activité trouvée: " . ($activite ? 'oui' : 'non') . "\n", FILE_APPEND);
            if ($activite) {
                file_put_contents($logFile, "club_id de l'activité: " . $activite['club_id'] . ", club_id responsable: $clubId\n", FILE_APPEND);
            }
            
            $statut = $present ? 'participe' : 'absent';
            
            if ($activite && $activite['club_id'] == $clubId) {
                file_put_contents($logFile, "Appel de updateParticipantStatut avec etudiantId=$etudiantId, activiteId=$activiteId, statut=$statut\n", FILE_APPEND);
                $result = $this->activiteModel->updateParticipantStatut($etudiantId, $activiteId, $statut);
                file_put_contents($logFile, "Résultat de updateParticipantStatut: " . ($result ? 'success' : 'échec') . "\n", FILE_APPEND);
                
                // Assurons-nous que le navigateur comprend que la réponse est du JSON
                header('Content-Type: application/json');
                echo json_encode(['success' => $result]);
            } else {
                file_put_contents($logFile, "Activité non trouvée ou n'appartient pas au club.\n", FILE_APPEND);
                
                header('Content-Type: application/json');
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
