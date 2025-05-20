<?php
require_once APP_PATH . '/core/Model.php';

/**
 * Classe ReservationModel - Modèle pour la table reservation
 */
class ReservationModel extends Model {
    /**
     * Récupère toutes les réservations
     * 
     * @return array Liste des réservations
     */
    public function getAll() {        $sql = "SELECT r.*, rs.nom_ressource, a.titre as activite_titre, c.nom as club_nom
                FROM reservation r
                LEFT JOIN ressource rs ON r.ressource_id = rs.id_ressource
                LEFT JOIN activite a ON r.activite_id = a.activite_id
                LEFT JOIN club c ON r.club_id = c.id
                ORDER BY r.date_reservation DESC";
        return $this->multiple($sql);
    }
    
    /**
     * Récupère une réservation par son ID
     * 
     * @param int $id ID de la réservation
     * @return array|false Données de la réservation ou false
     */
    public function getById($id) {        $sql = "SELECT r.*, rs.nom_ressource, a.titre as activite_titre, c.nom as club_nom
                FROM reservation r
                LEFT JOIN ressource rs ON r.ressource_id = rs.id_ressource
                LEFT JOIN activite a ON r.activite_id = a.activite_id
                LEFT JOIN club c ON r.club_id = c.id
                WHERE r.id_reservation = :id";
        return $this->single($sql, ['id' => $id]);
    }
    
    /**
     * Récupère les réservations par club
     * 
     * @param int $clubId ID du club
     * @return array Liste des réservations du club
     */
    public function getByClubId($clubId) {        $sql = "SELECT r.*, rs.nom_ressource, a.titre as activite_titre
                FROM reservation r
                LEFT JOIN ressource rs ON r.ressource_id = rs.id_ressource
                LEFT JOIN activite a ON r.activite_id = a.activite_id
                WHERE r.club_id = :club_id
                ORDER BY r.date_reservation DESC";
        return $this->multiple($sql, ['club_id' => $clubId]);
    }
    
    /**
     * Récupère les réservations par activité
     * 
     * @param int $activiteId ID de l'activité
     * @return array Liste des réservations pour l'activité
     */
    public function getByActiviteId($activiteId) {
        $sql = "SELECT r.*, rs.nom_ressource
                FROM reservation r
                LEFT JOIN ressource rs ON r.ressource_id = rs.id_ressource
                WHERE r.activite_id = :activite_id
                ORDER BY r.date_reservation DESC";
        return $this->multiple($sql, ['activite_id' => $activiteId]);
    }
    
    /**
     * Crée une nouvelle réservation
     * 
     * @param array $data Données de la réservation
     * @return int|false ID de la nouvelle réservation ou false
     */
    public function create($data) {
        $sql = "INSERT INTO reservation (ressource_id, club_id, activite_id, date_debut, date_fin, statut, description, date_reservation)
                VALUES (:ressource_id, :club_id, :activite_id, :date_debut, :date_fin, :statut, :description, :date_reservation)";
        
        $params = [
            'ressource_id' => $data['ressource_id'],
            'club_id' => $data['club_id'],
            'activite_id' => $data['activite_id'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'statut' => $data['statut'] ?? 'en_attente',
            'description' => $data['description'] ?? null,
            'date_reservation' => $data['date_reservation'] ?? date('Y-m-d H:i:s')
        ];
        
        return $this->insert($sql, $params);
    }
    
    /**
     * Mettre à jour une réservation
     * 
     * @param int $id ID de la réservation
     * @param array $data Données à mettre à jour
     * @return bool Succès ou échec
     */
    public function update($id, $data) {
        $sql = "UPDATE reservation SET";
        $params = ['id' => $id];
        
        $updates = [];
        
        if (isset($data['ressource_id'])) {
            $updates[] = " ressource_id = :ressource_id";
            $params['ressource_id'] = $data['ressource_id'];
        }
        
        if (isset($data['date_debut'])) {
            $updates[] = " date_debut = :date_debut";
            $params['date_debut'] = $data['date_debut'];
        }
        
        if (isset($data['date_fin'])) {
            $updates[] = " date_fin = :date_fin";
            $params['date_fin'] = $data['date_fin'];
        }
        
        if (isset($data['statut'])) {
            $updates[] = " statut = :statut";
            $params['statut'] = $data['statut'];
        }
        
        if (isset($data['description'])) {
            $updates[] = " description = :description";
            $params['description'] = $data['description'];
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql .= implode(',', $updates);
        $sql .= " WHERE id_reservation = :id";
        
        return $this->execute($sql, $params);
    }
    
    /**
     * Approuver une réservation
     * 
     * @param int $id ID de la réservation
     * @return bool Succès ou échec
     */
    public function approve($id) {
        return $this->update($id, ['statut' => 'approuvee']);
    }
    
    /**
     * Rejeter une réservation
     * 
     * @param int $id ID de la réservation
     * @return bool Succès ou échec
     */
    public function reject($id) {
        return $this->update($id, ['statut' => 'rejetee']);
    }
    
    /**
     * Supprimer une réservation
     * 
     * @param int $id ID de la réservation
     * @return bool Succès ou échec
     */
    public function delete($id) {
        $sql = "DELETE FROM reservation WHERE id_reservation = :id";
        return $this->execute($sql, ['id' => $id]);
    }
    
    /**
     * Vérifier si une ressource est disponible pour la période demandée
     * 
     * @param int $ressourceId ID de la ressource
     * @param string $dateDebut Date et heure de début
     * @param string $dateFin Date et heure de fin
     * @param int|null $excludeId ID de réservation à exclure (pour les mises à jour)
     * @return bool True si disponible, false sinon
     */
    public function isRessourceAvailable($ressourceId, $dateDebut, $dateFin, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM reservation 
                WHERE ressource_id = :ressource_id 
                AND statut = 'approuvee'
                AND (
                    (:date_debut BETWEEN date_debut AND date_fin) OR
                    (:date_fin BETWEEN date_debut AND date_fin) OR
                    (date_debut BETWEEN :date_debut AND :date_fin)
                )";
        
        $params = [
            'ressource_id' => $ressourceId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin
        ];
        
        if ($excludeId !== null) {
            $sql .= " AND id_reservation != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        $result = $this->single($sql, $params);
        
        return $result['count'] == 0;
    }
}
