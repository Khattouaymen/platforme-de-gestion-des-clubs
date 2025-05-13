<?php
require_once APP_PATH . '/core/Model.php';

/**
 * Classe ClubModel - Modèle pour la table club
 */
class ClubModel extends Model {
    /**
     * Récupère tous les clubs
     * 
     * @return array Liste des clubs
     */
    public function getAll() {
        $sql = "SELECT * FROM club";
        return $this->multiple($sql);
    }
    
    /**
     * Récupère un club par son ID
     * 
     * @param int $id ID du club
     * @return array|false Données du club ou false
     */
    public function getById($id) {
        $sql = "SELECT * FROM club WHERE id = :id";
        return $this->single($sql, ['id' => $id]);
    }
    
    /**
     * Crée un nouveau club
     * 
     * @param array $data Données du club
     * @return int|false ID du nouveau club ou false
     */
    public function create($data) {
        $sql = "INSERT INTO club (nom, description, nombre_membres, Logo_URL) 
                VALUES (:nom, :description, :nombre_membres, :logo)";
        
        $params = [
            'nom' => $data['nom'],
            'description' => $data['description'],
            'nombre_membres' => 0, // Nouveau club, pas de membres
            'logo' => $data['logo']
        ];
        
        if ($this->execute($sql, $params)) {
            return $this->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Met à jour un club
     * 
     * @param int $id ID du club
     * @param array $data Nouvelles données
     * @return bool Succès de la mise à jour
     */
    public function update($id, $data) {
        $sql = "UPDATE club SET 
                nom = :nom, 
                description = :description, 
                Logo_URL = :logo 
                WHERE id = :id";
        
        $params = [
            'nom' => $data['nom'],
            'description' => $data['description'],
            'logo' => $data['logo'],
            'id' => $id
        ];
        
        return $this->execute($sql, $params) > 0;
    }
    
    /**
     * Supprime un club
     * 
     * @param int $id ID du club
     * @return bool Succès de la suppression
     */
    public function delete($id) {
        $sql = "DELETE FROM club WHERE id = :id";
        return $this->execute($sql, ['id' => $id]) > 0;
    }
    
    /**
     * Met à jour le nombre de membres d'un club
     * 
     * @param int $id ID du club
     * @param int $count Nombre de membres
     * @return bool Succès de la mise à jour
     */
    public function updateMemberCount($id, $count) {
        $sql = "UPDATE club SET nombre_membres = :count WHERE id = :id";
        $params = ['count' => $count, 'id' => $id];
        
        return $this->execute($sql, $params) > 0;
    }
}
?>
