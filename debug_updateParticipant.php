<?php
// Ce script teste directement la méthode updateParticipantStatut

// Définir les constantes nécessaires
define('APP_PATH', __DIR__ . '/app');
define('PUBLIC_PATH', __DIR__ . '/public');

require_once 'config/database.php';
require_once 'app/core/Model.php';
require_once 'app/models/ActiviteModel.php';

// Paramètres de test
$etudiantId = 1; // Remplacez par un ID d'étudiant valide
$activiteId = 1; // Remplacez par un ID d'activité valide
$statut = 'participe'; // 'participe' ou 'absent'

$activiteModel = new ActiviteModel();

echo "Test de la méthode updateParticipantStatut\n";
echo "etudiantId: $etudiantId\n";
echo "activiteId: $activiteId\n";
echo "statut: $statut\n";

try {
    $result = $activiteModel->updateParticipantStatut($etudiantId, $activiteId, $statut);
    echo "Résultat: " . ($result ? "Succès" : "Échec") . "\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

// Ajoutons une méthode pour vérifier l'entrée dans la base de données
class ParticipationVerifier extends Model {
    public function getParticipation($etudiantId, $activiteId) {
        $sql = "SELECT * FROM participationactivite WHERE etudiant_id = :etudiant_id AND activite_id = :activite_id";
        return $this->single($sql, ['etudiant_id' => $etudiantId, 'activite_id' => $activiteId]);
    }
}

// Vérifier si l'entrée existe dans la base de données
try {
    $verifier = new ParticipationVerifier();
    $row = $verifier->getParticipation($etudiantId, $activiteId);
    
    if ($row) {
        echo "Entrée trouvée dans la base de données:\n";
        echo "etudiant_id: " . $row['etudiant_id'] . "\n";
        echo "activite_id: " . $row['activite_id'] . "\n";
        echo "statut: " . $row['statut'] . "\n";
        echo "date_inscription: " . $row['date_inscription'] . "\n";
    } else {
        echo "Aucune entrée trouvée dans la base de données.\n";
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?>
