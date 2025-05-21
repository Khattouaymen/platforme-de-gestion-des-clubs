<?php
require_once APP_PATH . '/core/Model.php';

/**
 * Classe RessourceModel - Modèle pour la table ressource
 */
class RessourceModel extends Model {
    /**
     * Récupère toutes les ressources
     * 
     * @return array Liste des ressources
     */
    public function getAll() {
        $sql = "SELECT r.*, c.nom as club_nom FROM ressource r 
                LEFT JOIN club c ON r.club_id = c.id";
        return $this->multiple($sql);
    }
    
    /**
     * Récupère une ressource par son ID
     * 
     * @param int $id ID de la ressource
     * @return array|false Données de la ressource ou false
     */
    public function getById($id) {
        $sql = "SELECT r.*, c.nom as club_nom FROM ressource r 
                LEFT JOIN club c ON r.club_id = c.id
                WHERE r.id_ressource = :id";
        return $this->single($sql, ['id' => $id]);
    }
    
    /**
     * Récupère les ressources d'un club spécifique
     * 
     * @param int $clubId ID du club
     * @return array Liste des ressources du club
     */
    public function getByClubId($clubId) {
        $sql = "SELECT * FROM ressource WHERE club_id = :club_id";
        return $this->multiple($sql, ['club_id' => $clubId]);
    }
    
    /**
     * Filtre les ressources par type
     * 
     * @param string $type Type de ressource (materiel, humain, financier, autre)
     * @return array Liste des ressources filtrées
     */
    public function getByType($type) {
        $sql = "SELECT r.*, c.nom as club_nom FROM ressource r 
                LEFT JOIN club c ON r.club_id = c.id
                WHERE r.type_ressource = :type";
        return $this->multiple($sql, ['type' => $type]);
    }
    
    /**
     * Filtre les ressources par disponibilité
     * 
     * @param string $disponibilite Disponibilité (disponible, indisponible)
     * @return array Liste des ressources filtrées
     */
    public function getByDisponibilite($disponibilite) {
        $sql = "SELECT r.*, c.nom as club_nom FROM ressource r 
                LEFT JOIN club c ON r.club_id = c.id
                WHERE r.disponibilite = :disponibilite";
        return $this->multiple($sql, ['disponibilite' => $disponibilite]);
    }
    
    /**
     * Crée une nouvelle ressource
     * 
     * @param array $data Données de la ressource
     * @return int|false ID de la nouvelle ressource ou false
     */
    public function create($data) {
        file_put_contents('debug_ressource.log', "RessourceModel::create - ENTER - Data: " . print_r($data, true) . "\n", FILE_APPEND);
        $sql = "INSERT INTO ressource (nom_ressource, type_ressource, quantite, club_id, disponibilite) 
                VALUES (:nom_ressource, :type_ressource, :quantite, :club_id, :disponibilite)";
        
        $params = [
            'nom_ressource' => $data['nom_ressource'], // Corrected key
            'type_ressource' => $data['type_ressource'], // Corrected key
            'quantite' => $data['quantite'] ?? 1,
            'club_id' => $data['club_id'] ?? null,
            'disponibilite' => $data['disponibilite'] ?? 'disponible'
        ];
        file_put_contents('debug_ressource.log', "RessourceModel::create - SQL: $sql\nParams: " . print_r($params, true) . "\n", FILE_APPEND);
        
        $executeResult = $this->execute($sql, $params);
        file_put_contents('debug_ressource.log', "RessourceModel::create - Result from execute: " . print_r($executeResult, true) . "\n", FILE_APPEND);

        if ($executeResult) {
            $lastId = $this->lastInsertId();
            file_put_contents('debug_ressource.log', "RessourceModel::create - LastInsertId: " . print_r($lastId, true) . ". EXITING.\n", FILE_APPEND);
            return $lastId;
        }
        
        file_put_contents('debug_ressource.log', "RessourceModel::create - Execute FAILED. EXITING.\n", FILE_APPEND);
        return false;
    }
    
    /**
     * Met à jour une ressource existante
     * 
     * @param int $id ID de la ressource à mettre à jour
     * @param array $data Nouvelles données
     * @return bool Succès ou échec
     */
    public function update($id, $data) {
        $sql = "UPDATE ressource SET 
                nom_ressource = :nom,
                type_ressource = :type,
                quantite = :quantite,
                club_id = :club_id,
                disponibilite = :disponibilite,
                description = :description 
                WHERE id_ressource = :id";
        
        $params = [
            'nom' => $data['nom'],
            'type' => $data['type'],
            'quantite' => $data['quantite'] ?? 1,
            'club_id' => $data['club_id'] ?? null,
            'disponibilite' => $data['disponibilite'] ?? 'disponible',
            'description' => $data['description'] ?? '', // Added description
            'id' => $id
        ];
        
        return $this->execute($sql, $params);
    }
    
    /**
     * Supprime une ressource
     * 
     * @param int $id ID de la ressource à supprimer
     * @return bool Succès ou échec
     */
    public function delete($id) {
        $sql = "DELETE FROM ressource WHERE id_ressource = :id";
        return $this->execute($sql, ['id' => $id]);
    }
}
