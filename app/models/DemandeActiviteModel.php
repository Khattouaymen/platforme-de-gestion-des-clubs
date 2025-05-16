<?php
require_once APP_PATH . '/core/Model.php';

/**
 * Classe DemandeActiviteModel - Modèle pour la table demandeactivite
 */
class DemandeActiviteModel extends Model {
    /**
     * Récupère toutes les demandes d'activités
     * 
     * @return array Liste des demandes d'activités
     */
    public function getAll() {
        $sql = "SELECT d.*, c.nom as club_nom
                FROM demandeactivite d
                LEFT JOIN club c ON d.club_id = c.id";
        return $this->multiple($sql);
    }
    
    /**
     * Récupère une demande d'activité par son ID
     * 
     * @param int $id ID de la demande d'activité
     * @return array|false Données de la demande ou false
     */
    public function getById($id) {
        $sql = "SELECT d.*, c.nom as club_nom
                FROM demandeactivite d
                LEFT JOIN club c ON d.club_id = c.id
                WHERE d.id_demande_act = :id";
        return $this->single($sql, ['id' => $id]);
    }
    
    /**
     * Récupère les demandes d'activités d'un club spécifique
     * 
     * @param int $clubId ID du club
     * @return array Liste des demandes d'activités du club
     */
    public function getByClubId($clubId) {
        $sql = "SELECT * FROM demandeactivite WHERE club_id = :club_id";
        return $this->multiple($sql, ['club_id' => $clubId]);
    }
    
    /**
     * Crée une nouvelle demande d'activité
     * 
     * @param array $data Données de la demande d'activité
     * @return int|false ID de la nouvelle demande ou false
     */
    public function create($data) {
        $sql = "INSERT INTO demandeactivite 
                (nom_activite, description, date_activite, nombre_max, lieu, club_id) 
                VALUES (:nom, :description, :date, :nombre_max, :lieu, :club_id)";
        
        $params = [
            'nom' => $data['nom'],
            'description' => $data['description'],
            'date' => $data['date'] ?? null,
            'nombre_max' => $data['nombre_max'] ?? null,
            'lieu' => $data['lieu'] ?? null,
            'club_id' => $data['club_id']
        ];
        
        if ($this->execute($sql, $params)) {
            return $this->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Met à jour une demande d'activité existante
     * 
     * @param int $id ID de la demande à mettre à jour
     * @param array $data Nouvelles données
     * @return bool Succès ou échec
     */
    public function update($id, $data) {
        $sql = "UPDATE demandeactivite SET 
                nom_activite = :nom,
                description = :description,
                date_activite = :date,
                nombre_max = :nombre_max,
                lieu = :lieu
                WHERE id_demande_act = :id";
        
        $params = [
            'nom' => $data['nom'],
            'description' => $data['description'],
            'date' => $data['date'] ?? null,
            'nombre_max' => $data['nombre_max'] ?? null,
            'lieu' => $data['lieu'] ?? null,
            'id' => $id
        ];
        
        return $this->execute($sql, $params);
    }
    
    /**
     * Approuve une demande d'activité et crée l'activité correspondante
     * 
     * @param int $id ID de la demande
     * @param ActiviteModel $activiteModel Instance de ActiviteModel pour créer l'activité
     * @return int|false ID de la nouvelle activité créée ou false
     */
    public function approveAndCreateActivite($id, $activiteModel) {
        // Récupérer les informations de la demande
        $demande = $this->getById($id);
        
        if (!$demande) {
            return false;
        }
        
        // Commencer une transaction
        $this->beginTransaction();
        
        try {
            // Créer la nouvelle activité
            $activiteData = [
                'titre' => $demande['nom_activite'],
                'description' => $demande['description'],
                'date_activite' => $demande['date_activite'],
                'lieu' => $demande['lieu'],
                'club_id' => $demande['club_id']
            ];
            
            $activiteId = $activiteModel->create($activiteData);
            
            if (!$activiteId) {
                $this->rollBack();
                return false;
            }
            
            // Supprimer la demande
            $this->delete($id);
            
            $this->commit();
            return $activiteId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
    
    /**
     * Supprime une demande d'activité
     * 
     * @param int $id ID de la demande à supprimer
     * @return bool Succès ou échec
     */
    public function delete($id) {
        $sql = "DELETE FROM demandeactivite WHERE id_demande_act = :id";
        return $this->execute($sql, ['id' => $id]);
    }
}
