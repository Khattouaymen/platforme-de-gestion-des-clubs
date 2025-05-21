<?php
require_once APP_PATH . '/core/Model.php';

/**
 * Classe ActiviteModel - Modèle pour la table activite
 */
class ActiviteModel extends Model {
    /**
     * Récupère toutes les activités
     * 
     * @return array Liste des activités
     */
    public function getAll() {
        $sql = "SELECT a.*, c.nom as club_nom 
                FROM activite a 
                LEFT JOIN club c ON a.club_id = c.id 
                ORDER BY a.date_activite DESC";
        return $this->multiple($sql);
    }
    
    /**
     * Récupère les activités d'un club
     * 
     * @param int $clubId ID du club
     * @return array Liste des activités du club
     */
    public function getByClubId($clubId) {
        $sql = "SELECT * FROM activite WHERE club_id = :club_id ORDER BY date_activite DESC";
        return $this->multiple($sql, ['club_id' => $clubId]);
    }
    
    /**
     * Récupère une activité par son ID
     * 
     * @param int $id ID de l'activité
     * @return array|false Données de l'activité ou false
     */
    public function getById($id) {
        $sql = "SELECT a.*, c.nom as club_nom 
                FROM activite a 
                LEFT JOIN club c ON a.club_id = c.id 
                WHERE a.activite_id = :id";
        return $this->single($sql, ['id' => $id]);
    }
    
    /**
     * Récupère les participants à une activité
     * 
     * @param int $activiteId ID de l'activité
     * @return array Liste des participants
     */
    public function getParticipantsByActiviteId($activiteId) {
        $sql = "SELECT pa.*, e.id_etudiant, e.nom as etudiant_nom, e.prenom as etudiant_prenom, e.email as etudiant_email, mc.role
                FROM participationactivite pa
                JOIN membreclub mc ON pa.membre_id = mc.id_membre
                JOIN etudiant e ON mc.id_etudiant = e.id_etudiant
                WHERE pa.activite_id = :activite_id
                ORDER BY pa.statut, e.nom, e.prenom";
        return $this->multiple($sql, ['activite_id' => $activiteId]);
    }
    
    /**
     * Ajoute un participant à une activité
     * 
     * @param int $membreId ID du membre
     * @param int $activiteId ID de l'activité
     * @param string $statut Statut de la participation ('inscrit', 'absent', 'participe')
     * @return bool Succès de l'opération
     */
    public function addParticipant($membreId, $activiteId, $statut = 'inscrit', $nom = '', $prenom = '') {
        $sql = "INSERT INTO participationactivite (membre_id, activite_id, statut, nom, prenom) 
                VALUES (:membre_id, :activite_id, :statut, :nom, :prenom)
                ON DUPLICATE KEY UPDATE statut = :statut";
        
        $stmt = $this->prepare($sql);
        return $stmt->execute([
            'membre_id' => $membreId,
            'activite_id' => $activiteId,
            'statut' => $statut,
            'nom' => $nom,
            'prenom' => $prenom
        ]);
    }
    
    /**
     * Met à jour le statut d'un participant
     * 
     * @param int $membreId ID du membre
     * @param int $activiteId ID de l'activité
     * @param string $statut Nouveau statut
     * @return bool Succès de l'opération
     */
    public function updateParticipantStatut($membreId, $activiteId, $statut) {
        $sql = "UPDATE participationactivite 
                SET statut = :statut 
                WHERE membre_id = :membre_id AND activite_id = :activite_id";
                
        $stmt = $this->prepare($sql);
        return $stmt->execute([
            'membre_id' => $membreId,
            'activite_id' => $activiteId,
            'statut' => $statut
        ]);
    }
    
    /**
     * Supprime un participant d'une activité
     * 
     * @param int $membreId ID du membre
     * @param int $activiteId ID de l'activité
     * @return bool Succès de la suppression
     */
    public function removeParticipant($membreId, $activiteId) {
        $sql = "DELETE FROM participationactivite 
                WHERE membre_id = :membre_id AND activite_id = :activite_id";
                
        $stmt = $this->prepare($sql);
        return $stmt->execute([
            'membre_id' => $membreId,
            'activite_id' => $activiteId
        ]);
    }
    
    /**
     * Crée une nouvelle activité
     * 
     * @param array $data Données de l'activité
     * @return int|false ID de la nouvelle activité ou false
     */
    public function create($data) {
        // The activite table uses 'date_activite'.
        // $data comes from DemandeActiviteModel and might have 'date_debut'.
        // We'll use $data['date_debut'] if available, otherwise $data['date_activite'].
        $dateActiviteValue = $data['date_debut'] ?? ($data['date_activite'] ?? null);

        // responsable_notifie should be set to 0 by default.
        $sql = "INSERT INTO activite (titre, description, date_activite, lieu, club_id, responsable_notifie) 
                VALUES (:titre, :description, :date_activite, :lieu, :club_id, 0)";
        
        $params = [
            'titre' => $data['titre'],
            'description' => $data['description'],
            'date_activite' => $dateActiviteValue, 
            'lieu' => $data['lieu'],
            'club_id' => $data['club_id']
        ];

        file_put_contents('debug_model.log', "ActiviteModel::create SQL: $sql\nParams: " . print_r($params, true) . "\n", FILE_APPEND);
        
        $executeResult = $this->execute($sql, $params);
        file_put_contents('debug_model.log', "ActiviteModel::create executeResult: " . print_r($executeResult, true) . "\n", FILE_APPEND);

        if ($executeResult) {
            $lastId = $this->lastInsertId();
            file_put_contents('debug_model.log', "ActiviteModel::create lastInsertId: " . print_r($lastId, true) . "\n", FILE_APPEND);
            return $lastId;
        }
        
        file_put_contents('debug_model.log', "ActiviteModel::create failed before lastInsertId.\n", FILE_APPEND);
        return false;
    }
    
    /**
     * Met à jour une activité
     * 
     * @param int $id ID de l'activité
     * @param array $data Nouvelles données
     * @return bool Succès de la mise à jour
     */
    public function update($id, $data) {
        $sql = "UPDATE activite SET 
                titre = :titre, 
                description = :description, 
                date_activite = :date_activite, 
                lieu = :lieu 
                WHERE activite_id = :id";
        
        $params = [
            'titre' => $data['titre'],
            'description' => $data['description'],
            'date_activite' => $data['date_activite'],
            'lieu' => $data['lieu'],
            'id' => $id
        ];
        
        return $this->execute($sql, $params) > 0;
    }
    
    /**
     * Supprime une activité
     * 
     * @param int $id ID de l'activité
     * @return bool Succès de la suppression
     */
    public function delete($id) {
        $sql = "DELETE FROM activite WHERE activite_id = :id";
        return $this->execute($sql, ['id' => $id]) > 0;
    }
    
    /**
     * Récupère les activités approuvées pour un club sans notification
     * 
     * @param int $clubId ID du club
     * @return array Liste des activités approuvées sans notification
     */
    public function getApprovedActivitiesWithoutNotification($clubId) {
        $sql = "SELECT a.*, da.id_demande_act as demande_id 
                FROM activite a
                LEFT JOIN demandeactivite da ON a.titre = da.nom_activite AND a.club_id = da.club_id AND da.statut = 'approuvee'
                WHERE a.club_id = :club_id 
                AND a.responsable_notifie = 0 
                AND da.statut = 'approuvee' -- S'assurer que l'activité correspond à une demande approuvée
                ORDER BY a.date_activite DESC"; // Changed back to date_activite
        return $this->multiple($sql, ['club_id' => $clubId]);
    }
    
    /**
     * Marque une activité comme notifiée au responsable
     * 
     * @param int $id ID de l'activité
     * @return bool Succès ou échec
     */
    public function markAsNotified($id) {
        $sql = "UPDATE activite SET responsable_notifie = 1 WHERE activite_id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
    
    /**
     * Récupère les activités qui n'ont pas encore de réservation
     * 
     * @param int $clubId ID du club
     * @return array Liste des activités sans réservation
     */
    public function getActivitiesWithoutReservation($clubId) {
        $sql = "SELECT a.*, da.id_demande_act as demande_id 
                FROM activite a
                LEFT JOIN demandeactivite da ON a.titre = da.nom_activite AND a.club_id = da.club_id AND da.statut = 'approuvee'
                WHERE a.club_id = :club_id
                AND da.statut = 'approuvee' -- S'assurer que l'activité est approuvée
                AND NOT EXISTS (
                    SELECT 1 FROM reservation r 
                    WHERE r.activite_id = a.activite_id
                )
                ORDER BY a.date_activite DESC"; // Changed back to date_activite
        return $this->multiple($sql, ['club_id' => $clubId]);
    }
}
?>
