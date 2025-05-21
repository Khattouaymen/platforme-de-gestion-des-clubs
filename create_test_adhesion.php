<?php
// Script pour créer une nouvelle demande d'adhésion pour test

// Define constants for path resolution
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');

require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/models/ClubModel.php';
require_once APP_PATH . '/models/DemandeAdhesionModel.php';
require_once APP_PATH . '/models/EtudiantModel.php';
require_once 'config/database.php';

class CreateAdhesionTest extends Model {
    private $clubModel;
    private $adhesionModel;
    private $etudiantModel;
    
    public function __construct() {
        parent::__construct();
        $this->clubModel = new ClubModel();
        $this->adhesionModel = new DemandeAdhesionModel();
        $this->etudiantModel = new EtudiantModel();
    }
    
    public function run() {
        echo "=== Création d'une demande d'adhésion pour test ===\n\n";
        
        // 1. Récupérer un club
        $clubs = $this->getClubs();
        if (empty($clubs)) {
            echo "Aucun club trouvé. Test arrêté.\n";
            return;
        }
        
        $clubId = $clubs[0]['id'];
        echo "Club sélectionné: {$clubs[0]['nom']} (ID: $clubId)\n";
        
        // 2. Récupérer un étudiant qui n'est pas déjà membre du club
        $etudiants = $this->getEtudiants();
        if (empty($etudiants)) {
            echo "Aucun étudiant trouvé. Test arrêté.\n";
            return;
        }
        
        // Récupérer les membres actuels
        $members = $this->clubModel->getMembresByClubId($clubId);
        $memberIds = array_column($members, 'id_etudiant');
        
        $etudiantId = null;
        foreach ($etudiants as $etudiant) {
            if (!in_array($etudiant['id_etudiant'], $memberIds)) {
                $etudiantId = $etudiant['id_etudiant'];
                echo "Étudiant sélectionné: {$etudiant['nom']} {$etudiant['prenom']} (ID: $etudiantId)\n";
                break;
            }
        }
        
        if ($etudiantId === null) {
            echo "Tous les étudiants sont déjà membres de ce club. Test arrêté.\n";
            return;
        }
        
        // 3. Vérifier si une demande existe déjà
        $existingRequests = $this->adhesionModel->getByEtudiantId($etudiantId);
        foreach ($existingRequests as $req) {
            if ($req['club_id'] == $clubId && $req['statut'] === 'en_attente') {
                echo "Une demande en attente existe déjà pour cet étudiant et ce club.\n";
                echo "Demande ID: {$req['demande_adh_id']}, Statut: {$req['statut']}, Date: {$req['date_demande']}\n";
                return;
            }
        }
        
        // 4. Créer une nouvelle demande
        $data = [
            'etudiant_id' => $etudiantId,
            'club_id' => $clubId,
            'date_demande' => date('Y-m-d'),
            'statut' => 'en_attente',
            'motivation' => 'Demande créée automatiquement pour test d\'adhésion ' . date('Y-m-d H:i:s')
        ];
        
        $demandeId = $this->adhesionModel->create($data);
        
        if ($demandeId) {
            echo "Demande d'adhésion créée avec succès! ID: $demandeId\n";
        } else {
            echo "Erreur lors de la création de la demande.\n";
        }
    }
    
    private function getClubs() {
        $sql = "SELECT * FROM club ORDER BY id";
        return $this->multiple($sql);
    }
    
    private function getEtudiants() {
        $sql = "SELECT * FROM etudiant ORDER BY id_etudiant";
        return $this->multiple($sql);
    }
}

// Exécuter le test
$test = new CreateAdhesionTest();
$test->run();
