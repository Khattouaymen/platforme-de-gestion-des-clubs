<?php
// Script de vérification de l'ajout de membres au club

// Define constants for path resolution
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');

require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/models/ClubModel.php';
require_once APP_PATH . '/models/DemandeAdhesionModel.php';
require_once 'config/database.php';

class MembershipTest extends Model {
    private $clubModel;
    private $adhesionModel;
    
    public function __construct() {
        parent::__construct();
        $this->clubModel = new ClubModel();
        $this->adhesionModel = new DemandeAdhesionModel();
    }
    
    public function run() {
        echo "=== Test des fonctionnalités d'adhésion aux clubs ===\n\n";
        
        // 1. Liste tous les clubs
        $clubs = $this->getClubs();
        if (empty($clubs)) {
            echo "Aucun club trouvé. Test arrêté.\n";
            return;
        }
        
        echo "Clubs disponibles (" . count($clubs) . "):\n";
        foreach ($clubs as $index => $club) {
            echo "[$index] ID: {$club['id']}, Nom: {$club['nom']}, Membres: {$club['nombre_membres']}\n";
        }
        echo "\n";
        
        // 2. Sélectionne un club pour le test
        $clubIndex = 0; // On utilise le premier club par défaut
        $clubId = $clubs[$clubIndex]['id'];
        echo "Club sélectionné pour le test: {$clubs[$clubIndex]['nom']} (ID: $clubId)\n\n";
        
        // 3. Liste les membres actuels du club
        $this->listClubMembers($clubId);
        
        // 4. Liste les demandes d'adhésion en attente
        $pendingRequests = $this->adhesionModel->getByStatut('en_attente');
        echo "Demandes d'adhésion en attente (" . count($pendingRequests) . "):\n";
        foreach ($pendingRequests as $req) {
            if ($req['club_id'] == $clubId) {
                echo "ID: {$req['demande_adh_id']}, Étudiant: {$req['etudiant_nom']} {$req['etudiant_prenom']}, Date: {$req['date_demande']}\n";
            }
        }
        echo "\n";
        
        // 5. Accepter une demande (si disponible)
        if (!empty($pendingRequests)) {
            foreach ($pendingRequests as $req) {
                if ($req['club_id'] == $clubId) {
                    $demandeId = $req['demande_adh_id'];
                    echo "Acceptation de la demande ID: $demandeId...\n";
                    $success = $this->adhesionModel->accepterEtAjouterMembre($demandeId);
                    echo "Résultat: " . ($success ? "SUCCÈS" : "ÉCHEC") . "\n\n";
                    
                    // Vérifier si le membre a bien été ajouté
                    echo "Vérification des membres après acceptation...\n";
                    $this->listClubMembers($clubId);
                    
                    // Vérifier si le nombre de membres du club a été incrémenté
                    $updatedClub = $this->clubModel->getById($clubId);
                    echo "Nombre de membres mis à jour: {$updatedClub['nombre_membres']} (avant: {$clubs[$clubIndex]['nombre_membres']})\n";
                    break;
                }
            }
        } else {
            echo "Aucune demande en attente pour le club sélectionné.\n";
        }
    }
    
    private function getClubs() {
        $sql = "SELECT * FROM club ORDER BY id";
        return $this->multiple($sql);
    }
    
    private function listClubMembers($clubId) {
        $members = $this->clubModel->getMembresByClubId($clubId);
        echo "Membres actuels du club (" . count($members) . "):\n";
        foreach ($members as $member) {
            echo "ID: {$member['id_membre']}, Étudiant ID: {$member['id_etudiant']}, Nom: {$member['nom']} {$member['prenom']}, Rôle: {$member['role']}\n";
        }
        echo "\n";
    }
}

// Exécuter le test
$test = new MembershipTest();
$test->run();
