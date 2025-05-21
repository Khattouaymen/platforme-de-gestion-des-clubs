<?php
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/EtudiantModel.php';
require_once APP_PATH . '/models/AdminModel.php';

/**
 * Classe AuthController - Contrôleur pour l'authentification
 */
class AuthController extends Controller {
    private $etudiantModel;
    private $adminModel;
    
    /**
     * Constructeur
     */
    public function __construct() {
        $this->etudiantModel = new EtudiantModel();
        $this->adminModel = new AdminModel();
    }
    
    /**
     * Traitement de la connexion
     * 
     * @return void
     */
    public function login() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }
          // Récupérer les données du formulaire
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        // Remplacer FILTER_SANITIZE_STRING déprécié par htmlspecialchars
        $userType = isset($_POST['user_type']) ? htmlspecialchars($_POST['user_type'], ENT_QUOTES, 'UTF-8') : '';
        
        // Valider les entrées
        if (empty($email) || empty($password) || empty($userType)) {
            $data = [
                'title' => 'Connexion',
                'error' => 'Tous les champs sont obligatoires'
            ];
            
            $this->view('home/login', $data);
            return;
        }
        
        // Authentifier l'utilisateur selon son type
        $user = null;
          if ($userType === 'etudiant') {
            $user = $this->etudiantModel->authenticate($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id_etudiant'];
                $_SESSION['user_type'] = 'etudiant';
                $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
                  // Vérifier si l'étudiant est un responsable de club
                $result = $this->etudiantModel->checkIfResponsable($user['id_etudiant']);
                
                if ($result) {
                    $_SESSION['user_type'] = 'responsable';
                    $this->redirect('/responsable');
                    return;
                }
                
                $this->redirect('/etudiant');
                return;
            }
        } else if ($userType === 'admin') {
            $user = $this->adminModel->authenticate($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = 'admin';
                $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
                
                $this->redirect('/admin');
                return;
            }
        }
        
        // Si l'authentification a échoué
        $data = [
            'title' => 'Connexion',
            'error' => 'Email ou mot de passe incorrect'
        ];
        
        $this->view('home/login', $data);
    }
    
    /**
     * Traitement de l'inscription
     * 
     * @return void
     */
    public function register() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }
        
        // Récupérer les données du formulaire
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        // Les champs filière, niveau et numéro d'étudiant ne sont plus requis à l'inscription
        // Ils seront remplis plus tard dans le profil
        
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
        } elseif ($this->etudiantModel->emailExists($email)) {
            $errors[] = 'Cet email est déjà utilisé';
        }
        
        if (empty($password)) {
            $errors[] = 'Le mot de passe est obligatoire';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères';
        } elseif ($password !== $confirmPassword) {
            $errors[] = 'Les mots de passe ne correspondent pas';
        }
          // S'il y a des erreurs
        if (!empty($errors)) {
            $data = [
                'title' => 'Inscription',
                'errors' => $errors,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email
            ];
            
            $this->view('home/login', $data);
            return;
        }        // Enregistrer l'étudiant
        $userData = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $password,
            'filiere' => '',
            'niveau' => '',
            'numero_etudiant' => ''
        ];
        
        $userId = $this->etudiantModel->register($userData);
        
        if ($userId) {
            // Connexion automatique
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_type'] = 'etudiant';
            $_SESSION['user_name'] = $prenom . ' ' . $nom;
            
            // Rediriger vers la page de profil avec un message
            $_SESSION['profile_completion_error'] = 'Veuillez compléter votre profil (filière, niveau, numéro étudiant) pour pouvoir rejoindre des clubs ou participer à des activités.';
            $this->redirect('/etudiant/profil');
        } else {
            $data = [
                'title' => 'Inscription',
                'error' => 'Une erreur est survenue lors de l\'inscription',
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email
            ];
            
            $this->view('home/login', $data);
        }
    }
      /**
     * Déconnexion
     * 
     * @return void
     */
    public function logout() {
        // Détruire la session
        session_destroy();
        
        // Rediriger vers la page d'accueil
        $this->redirect('/');
    }
    
    /**
     * Affichage du formulaire d'inscription pour responsable via token
     * 
     * @param string $token Le token d'invitation
     * @return void
     */
    public function registerResponsable($token = '') {
        // Charger les modèles nécessaires
        require_once APP_PATH . '/models/EtudiantModel.php';
        $etudiantModel = new EtudiantModel();
        
        // Vérifier si le token est fourni
        if (empty($token)) {
            $this->redirect('/');
            return;
        }
        
        // Vérifier si le token est valide
        if (!$this->adminModel->isTokenValid($token)) {
            $data = [
                'title' => 'Lien expiré',
                'message' => 'Ce lien d\'inscription n\'est plus valide ou a déjà été utilisé.',
                'asset' => function($path) { return $this->asset($path); }
            ];
            $this->view('error/not_found', $data);
            return;
        }
        
        // Si la requête est de type POST, traiter l'inscription
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            // Nouveaux champs pour le responsable (qui est aussi un étudiant)
            $filiere = filter_input(INPUT_POST, 'filiere', FILTER_SANITIZE_STRING);
            $niveau = filter_input(INPUT_POST, 'niveau', FILTER_SANITIZE_STRING);
            $numero_etudiant = filter_input(INPUT_POST, 'numero_etudiant', FILTER_SANITIZE_STRING);
            
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
            } elseif ($etudiantModel->emailExists($email)) {
                $errors[] = 'Cet email est déjà utilisé';
            }
            
            if (empty($password)) {
                $errors[] = 'Le mot de passe est obligatoire';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Le mot de passe doit contenir au moins 6 caractères';
            } elseif ($password !== $confirmPassword) {
                $errors[] = 'Les mots de passe ne correspondent pas';
            }

            // Validation pour les nouveaux champs
            if (empty($filiere)) {
                $errors[] = 'La filière est obligatoire';
            }
    
            if (empty($niveau)) {
                $errors[] = 'Le niveau est obligatoire';
            }
    
            if (empty($numero_etudiant)) {
                $errors[] = 'Le numéro d\'étudiant est obligatoire';
            }

            // S'il y a des erreurs
            if (!empty($errors)) {
                $data = [
                    'title' => 'Inscription Responsable de Club',
                    'errors' => $errors,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'filiere' => $filiere, // Passer les nouvelles données à la vue
                    'niveau' => $niveau,
                    'numero_etudiant' => $numero_etudiant,
                    'token' => $token,
                    'asset' => function($path) { return $this->asset($path); }
                ];
                
                $this->view('home/register_responsable', $data);
                return;
            }
            
            // D'abord, créer un compte étudiant (puisque les responsables sont liés aux étudiants)
            $userData = [
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'password' => $password,
                'filiere' => $filiere, // Inclure les nouveaux champs
                'niveau' => $niveau,
                'numero_etudiant' => $numero_etudiant
            ];
            
            $etudiantId = $etudiantModel->register($userData);
            
            if (!$etudiantId) {
                $data = [
                    'title' => 'Inscription Responsable de Club',
                    'error' => 'Une erreur est survenue lors de l\'inscription de l\'étudiant.',
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'filiere' => $filiere,
                    'niveau' => $niveau,
                    'numero_etudiant' => $numero_etudiant,
                    'token' => $token,
                    'asset' => function($path) { return $this->asset($path); }
                ];
                
                $this->view('home/register_responsable', $data);
                return;
            }
            
            // Créer une entrée dans la table responsableclub sera fait plus tard
            // quand un club sera créé ou quand l'admin assignera un club à ce responsable            // Marquer le token comme utilisé et l'associer à l'étudiant
            $this->adminModel->useToken($token, $etudiantId);
            
            // Connecter automatiquement l'utilisateur en tant qu'étudiant
            // (il sera reconnu comme responsable une fois assigné à un club)
            $_SESSION['user_id'] = $etudiantId;
            $_SESSION['user_type'] = 'etudiant';
            $_SESSION['user_name'] = $prenom . ' ' . $nom;
            $_SESSION['is_future_responsable'] = true; // Marquer comme futur responsable
            
            // Rediriger vers la page étudiant avec message de succès
            $this->redirect('/etudiant?success=Votre+compte+a+été+créé+avec+succès.+Un+administrateur+vous+assignera+bientôt+à+un+club+comme+responsable.');
            return;
        }
        
        // Afficher le formulaire d'inscription
        $data = [
            'title' => 'Inscription Responsable de Club',
            'token' => $token,
            'asset' => function($path) { return $this->asset($path); }
        ];
        
        $this->view('home/register_responsable', $data);
    }
}
?>
