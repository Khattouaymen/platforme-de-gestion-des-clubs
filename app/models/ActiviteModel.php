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
     * Crée une nouvelle activité
     * 
     * @param array $data Données de l'activité
     * @return int|false ID de la nouvelle activité ou false
     */
    public function create($data) {
        $sql = "INSERT INTO activite (titre, description, date_activite, lieu, club_id) 
                VALUES (:titre, :description, :date_activite, :lieu, :club_id)";
        
        $params = [
            'titre' => $data['titre'],
            'description' => $data['description'],
            'date_activite' => $data['date_activite'],
            'lieu' => $data['lieu'],
            'club_id' => $data['club_id']
        ];
        
        if ($this->execute($sql, $params)) {
            return $this->lastInsertId();
        }
        
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
}
?>
