<?php
require_once APP_PATH . '/core/Model.php';

class ParticipationActiviteModel extends Model {
    protected $table = 'participationactivite';

    public function create($data) {
        // Check if already registered
        $stmt_check = $this->db->prepare("SELECT * FROM {$this->table} WHERE etudiant_id = :etudiant_id AND activite_id = :activite_id");
        $stmt_check->bindParam(':etudiant_id', $data['etudiant_id']);
        $stmt_check->bindParam(':activite_id', $data['activite_id']);
        $stmt_check->execute();
        if ($stmt_check->fetch()) {
            return ['success' => false, 'message' => 'Vous êtes déjà inscrit à cette activité.'];
        }

        // Check if activity is full
        $activiteModel = new ActiviteModel(); // Assuming ActiviteModel exists and can provide nombre_max and current participants
        $activite = $activiteModel->getById($data['activite_id']);
        if ($activite && isset($activite['nombre_max']) && $activite['nombre_max'] !== null) {
            $current_participants = $this->getParticipantCount($data['activite_id']);
            if ($current_participants >= $activite['nombre_max']) {
                return ['success' => false, 'message' => 'Cette activité a atteint son nombre maximum de participants.'];
            }
        }

        $sql = "INSERT INTO {$this->table} (etudiant_id, activite_id, statut, date_inscription) VALUES (:etudiant_id, :activite_id, :statut, :date_inscription)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':etudiant_id' => $data['etudiant_id'],
            ':activite_id' => $data['activite_id'],
            ':statut' => $data['statut'] ?? 'inscrit',
            ':date_inscription' => $data['date_inscription'] ?? date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Delete a participation record for a student and activity
     * @param int $etudiantId
     * @param int $activiteId
     * @return bool
     */
    public function deleteByEtudiantAndActivite($etudiantId, $activiteId) {
        $sql = "DELETE FROM {$this->table} WHERE etudiant_id = :etudiant_id AND activite_id = :activite_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':etudiant_id', $etudiantId);
        $stmt->bindParam(':activite_id', $activiteId);
        return $stmt->execute();
    }

    public function getByEtudiantAndActivite($etudiantId, $activiteId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE etudiant_id = :etudiant_id AND activite_id = :activite_id");
        $stmt->bindParam(':etudiant_id', $etudiantId);
        $stmt->bindParam(':activite_id', $activiteId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getParticipantCount($activiteId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE activite_id = :activite_id AND statut = 'inscrit'");
        $stmt->bindParam(':activite_id', $activiteId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['count'] : 0;
    }

    public function getParticipationsByEtudiant($etudiantId) {
        $sql = "SELECT pa.*, a.titre as activite_titre, a.date_activite, c.nom as club_nom 
                FROM {$this->table} pa
                JOIN activite a ON pa.activite_id = a.activite_id
                LEFT JOIN club c ON a.club_id = c.id
                WHERE pa.etudiant_id = :etudiant_id ORDER BY a.date_activite DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':etudiant_id', $etudiantId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    // Add a method to get participations for a specific activity (useful for admins/responsables)
    public function getParticipantsByActivite($activiteId) {
        $sql = "SELECT pa.*, e.nom as etudiant_nom, e.prenom as etudiant_prenom, e.email as etudiant_email, e.filiere 
                FROM {$this->table} pa
                JOIN etudiant e ON pa.etudiant_id = e.id_etudiant
                WHERE pa.activite_id = :activite_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':activite_id', $activiteId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
