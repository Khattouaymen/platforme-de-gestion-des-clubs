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
     */    public function __construct() {
        // Vérifier si l'utilisateur est connecté en tant qu'étudiant
        $this->checkAuth();
        
        $this->etudiantModel = new EtudiantModel();
        $this->clubModel = new ClubModel();
        $this->activiteModel = new ActiviteModel();
        
        // Si l'URL courante n'est pas la page de profil, et que le profil est incomplet,
        // rediriger vers la page de profil avec un message
        $currentUrl = $_SERVER['REQUEST_URI'];
        if (!strpos($currentUrl, '/etudiant/profil') && isset($_SESSION['user_id'])) {
            if (!$this->isProfileComplete($_SESSION['user_id'])) {
                // Uniquement suggérer de compléter le profil sur la page d'accueil
                // Ne pas rediriger automatiquement pour ne pas bloquer l'utilisateur
                if ($currentUrl === "/etudiant" || $currentUrl === "/etudiant/") {
                    $_SESSION['profile_completion_error'] = 'Pour profiter pleinement des fonctionnalités, veuillez compléter votre profil (filière, niveau, numéro étudiant).';
                }
            }
        }
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
     * Vérifie si le profil de l'étudiant est complet
     * 
     * @param int $etudiantId ID de l'étudiant
     * @return bool Retourne true si le profil est complet, false sinon
     */
    private function isProfileComplete($etudiantId) {
        $etudiant = $this->etudiantModel->getById($etudiantId);
        
        return !empty($etudiant['filiere']) 
               && !empty($etudiant['niveau']) 
               && !empty($etudiant['numero_etudiant']);
    }
    
    /**
     * Rediriger vers la page de profil avec un message demandant de compléter le profil
     * 
     * @return void
     */
    private function redirectToCompleteProfile() {
        $_SESSION['profile_completion_error'] = 'Veuillez compléter toutes les informations de votre profil (filière, niveau, numéro étudiant) pour continuer.';
        $this->redirect('/etudiant/profil');
    }
    
    /**
     * Tableau de bord de l'étudiant
     * 
     * @return void
     */    public function index() {
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
            'activites' => $activites,
            'profileComplete' => $this->isProfileComplete($_SESSION['user_id'])
        ];
        
        $this->view('etudiant/dashboard', $data);
    }
      /**
     * Liste des clubs disponibles
     * 
     * @return void
     */    public function clubs() {
        $etudiantId = $_SESSION['user_id'];
        
        // Récupérer tous les clubs
        $clubs = $this->clubModel->getAll();
        
        // Récupérer les clubs dont l'étudiant est membre
        $mesClubs = $this->clubModel->getClubsByEtudiantId($etudiantId);
        
        // Créer un tableau des IDs des clubs dont l'étudiant est membre pour faciliter les vérifications
        $mesClubsIds = [];
        foreach ($mesClubs as $club) {
            $mesClubsIds[] = $club['id'];
        }
        
        // Récupérer les demandes d'adhésion en attente de l'étudiant
        require_once APP_PATH . '/models/DemandeAdhesionModel.php';
        $demandeAdhesionModel = new DemandeAdhesionModel();
        $demandes = $demandeAdhesionModel->getByEtudiantId($etudiantId);
        
        // Créer un tableau associant l'ID du club au statut de la demande
        $demandesParClub = [];
        foreach ($demandes as $demande) {
            $demandesParClub[$demande['club_id']] = $demande['statut'];
        }
        
        $data = [
            'title' => 'Clubs disponibles',
            'clubs' => $clubs,
            'mesClubsIds' => $mesClubsIds,
            'demandesParClub' => $demandesParClub
        ];
        
        $this->view('etudiant/clubs', $data);
    }
    
    /**
     * Détails d'un club
     * 
     * @param int $id ID du club
     * @return void
     */    public function club($id) {
        // Récupérer les informations du club
        $club = $this->clubModel->getById($id);
        
        if (!$club) {
            $this->redirect('/etudiant/clubs');
            return;
        }
        
        // Vérifier si le profil est complet avant d'afficher les détails du club
        if (!$this->isProfileComplete($_SESSION['user_id'])) {
            $this->redirectToCompleteProfile();
            return;
        }
        
        // Récupérer les activités du club
        $activites = $this->activiteModel->getByClubId($id);
        
        // Récupérer les membres du club
        $membres = $this->clubModel->getMembresByClubId($id);
        
        // Récupérer le responsable du club
        $responsable = $this->clubModel->getResponsableByClubId($id);
        
        $data = [
            'title' => 'Détails du club - ' . $club['nom'],
            'club' => $club,
            'activites' => $activites,
            'membres' => $membres,
            'responsable' => $responsable
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
     */    public function activite($id) {
        // Récupérer les informations de l'activité
        $activite = $this->activiteModel->getById($id);
        
        if (!$activite) {
            $this->redirect('/etudiant/activites');
            return;
        }
        
        // Vérifier si le profil est complet avant d'afficher les détails de l'activité
        if (!$this->isProfileComplete($_SESSION['user_id'])) {
            $this->redirectToCompleteProfile();
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
     */    public function updateProfil() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/etudiant/profil');
            return;
        }
        
        // Récupérer d'abord les informations actuelles de l'étudiant pour préserver nom, prénom et email
        $etudiant = $this->etudiantModel->getById($_SESSION['user_id']);
        if (!$etudiant) {
            $this->redirect('/etudiant/profil?error=Étudiant+introuvable');
            return;
        }
        
        // Récupérer les données du formulaire (uniquement les champs modifiables)
        $filiere = htmlspecialchars(trim($_POST['filiere'] ?? ''));
        $niveau = htmlspecialchars(trim($_POST['niveau'] ?? ''));
        $numero_etudiant = htmlspecialchars(trim($_POST['numero_etudiant'] ?? ''));
        
        // On ne bloque pas la mise à jour si les champs spécifiques sont vides
        // Mais on avertit l'utilisateur que son profil est incomplet
        $profileIncomplete = false;
        
        if (empty($filiere) || empty($niveau) || empty($numero_etudiant)) {
            $profileIncomplete = true;
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
        }        // Mettre à jour le profil (ne pas modifier nom, prénom et email)
        $userData = [
            'filiere' => $filiere,
            'niveau' => $niveau,
            'numero_etudiant' => $numero_etudiant
        ];
        
        $success = $this->etudiantModel->update($_SESSION['user_id'], $userData);
          if ($success) {
            // Ne pas mettre à jour le nom dans la session car il reste inchangé
            
            // Vérifier si c'était la première fois que l'utilisateur complétait son profil
            $etudiantAvant = $this->etudiantModel->getById($_SESSION['user_id']);
            $profilCompletAvant = !empty($etudiantAvant['filiere']) && 
                                  !empty($etudiantAvant['niveau']) && 
                                  !empty($etudiantAvant['numero_etudiant']);
            
            $profilCompletApres = !empty($filiere) && !empty($niveau) && !empty($numero_etudiant);
              if (!$profilCompletAvant && $profilCompletApres) {
                // Le profil vient d'être complété
                $this->redirect('/etudiant/profil?success=1&completed=1');
            } else if ($profileIncomplete) {
                // Le profil a été mis à jour mais est toujours incomplet
                $_SESSION['profile_completion_error'] = 'Votre profil a été mis à jour, mais il reste des informations manquantes (filière, niveau, numéro étudiant) nécessaires pour rejoindre des clubs ou participer à des activités.';
                $this->redirect('/etudiant/profil?success=1');
            } else {
                $this->redirect('/etudiant/profil?success=1');
            }
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
      /**
     * Permet à un étudiant de demander l'adhésion à un club
     * @param int $clubId
     * @return void
     */    public function demandeAdhesion($clubId)
    {
        $etudiantId = $_SESSION['user_id'];
        
        // Vérifier si le profil est complet avant de permettre l'adhésion
        if (!$this->isProfileComplete($etudiantId)) {
            $this->redirectToCompleteProfile();
            return;
        }
        
        $club = $this->clubModel->getById($clubId);
        if (!$club) {
            $this->redirect('/etudiant/clubs?error=Club+introuvable');
            return;
        }        // Si c'est une soumission de formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Using htmlspecialchars instead of deprecated FILTER_SANITIZE_STRING
            $motivation = isset($_POST['motivation']) ? htmlspecialchars($_POST['motivation'], ENT_QUOTES, 'UTF-8') : '';
            
            if (empty($motivation)) {
                $data = [
                    'title' => 'Demande d\'adhésion - ' . $club['nom'],
                    'club' => $club,
                    'error' => 'Veuillez expliquer votre motivation pour rejoindre ce club'
                ];
                
                $this->view('etudiant/demande_adhesion', $data);
                return;
            }
            
            require_once APP_PATH . '/models/DemandeAdhesionModel.php';
            $demandeAdhesionModel = new DemandeAdhesionModel();
            $demandeAdhesionModel->create([
                'etudiant_id' => $etudiantId,
                'club_id' => $clubId,
                'statut' => 'en_attente',
                'date_demande' => date('Y-m-d H:i:s'),
                'motivation' => $motivation
            ]);
            
            $this->redirect('/etudiant/clubs?success=Votre+demande+a+été+envoyée');
        } else {
            // Afficher le formulaire de motivation
            $data = [
                'title' => 'Demande d\'adhésion - ' . $club['nom'],
                'club' => $club
            ];
            
            $this->view('etudiant/demande_adhesion', $data);
        }
    }
      /**
     * Affiche les clubs auxquels l'étudiant est membre ainsi que ses demandes d'adhésion
     * 
     * @return void
     */
    public function mesClubs() {
        $etudiantId = $_SESSION['user_id'];
        
        // Vérifier si le profil est complet avant d'afficher les clubs
        if (!$this->isProfileComplete($etudiantId)) {
            $this->redirectToCompleteProfile();
            return;
        }
        
        // Récupérer les clubs de l'étudiant
        $clubs = $this->clubModel->getClubsByEtudiantId($etudiantId);
        
        // Récupérer les demandes d'adhésion en attente de l'étudiant
        require_once APP_PATH . '/models/DemandeAdhesionModel.php';
        $demandeAdhesionModel = new DemandeAdhesionModel();
        $demandes = $demandeAdhesionModel->getByEtudiantId($etudiantId);
        
        $data = [
            'title' => 'Mes clubs',
            'clubs' => $clubs,
            'demandes' => $demandes
        ];
        
        $this->view('etudiant/mes_clubs', $data);
    }
}
?>
