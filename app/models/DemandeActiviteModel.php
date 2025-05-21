<?php
require_once APP_PATH . '/core/Model.php';

/**
 * Classe DemandeActiviteModel - Modèle pour la table demandeactivite
 */
class DemandeActiviteModel extends Model {    /**
     * Récupère toutes les demandes d'activités
     * 
     * @return array Liste des demandes d'activités
     */
    public function getAll() {
        $sql = "SELECT d.*, c.nom as club_nom
                FROM demandeactivite d
                LEFT JOIN club c ON d.club_id = c.id
                ORDER BY d.date_creation DESC";
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
        $demande = $this->single($sql, ['id' => $id]);
        
        // Si la demande existe, vérifiez et formatez les dates pour assurer la compatibilité avec les anciens et nouveaux formats
        if ($demande) {
            // Format des dates pour une présentation cohérente
            if (!empty($demande['date_debut'])) {
                $demande['date_debut_formatted'] = date('d/m/Y H:i', strtotime($demande['date_debut']));
            }
            
            if (!empty($demande['date_fin'])) {
                $demande['date_fin_formatted'] = date('d/m/Y H:i', strtotime($demande['date_fin']));
            }
            
            if (!empty($demande['date_activite'])) {
                $demande['date_activite_formatted'] = date('d/m/Y', strtotime($demande['date_activite']));
            }
            
            // Assurer que statut est toujours défini
            if (empty($demande['statut'])) {
                $demande['statut'] = 'en_attente';
            }
        }
        
        return $demande;
    }
      /**
     * Récupère les demandes d'activités d'un club spécifique
     * 
     * @param int $clubId ID du club
     * @return array Liste des demandes d'activités du club
     */
    public function getByClubId($clubId) {
        $sql = "SELECT id_demande_act as id, nom_activite as titre, description, 
                       date_debut, date_fin, date_activite, lieu, club_id, statut, 
                       date_creation  
                FROM demandeactivite 
                WHERE club_id = :club_id
                ORDER BY date_creation DESC";
        return $this->multiple($sql, ['club_id' => $clubId]);
    }
    
    /**
     * Récupère les demandes d'activités par statut
     * 
     * @param string $statut Statut de la demande ('en_attente', 'approuvee', 'refusee')
     * @return array Liste des demandes d'activités avec le statut spécifié
     */
    public function getByStatut($statut) {
        $sql = "SELECT d.*, c.nom as club_nom
                FROM demandeactivite d
                LEFT JOIN club c ON d.club_id = c.id
                WHERE d.statut = :statut
                ORDER BY d.date_creation DESC";
        return $this->multiple($sql, ['statut' => $statut]);
    }
    
    /**
     * Crée une nouvelle demande d'activité
     * 
     * @param array $data Données de la demande d'activité
     * @return int|false ID de la nouvelle demande ou false
     */
    public function create($data) {
        // Déterminer le SQL en fonction des champs disponibles
        if (isset($data['date_debut']) && isset($data['date_fin'])) {
            // Nouveau format avec date_debut, date_fin, statut
            $sql = "INSERT INTO demandeactivite 
                   (nom_activite, description, date_debut, date_fin, lieu, club_id, statut, date_creation) 
                   VALUES (:nom, :description, :date_debut, :date_fin, :lieu, :club_id, :statut, :date_creation)";
            
            $params = [
                'nom' => $data['titre'] ?? ($data['nom'] ?? null),
                'description' => $data['description'] ?? null,
                'date_debut' => $data['date_debut'] ?? null,
                'date_fin' => $data['date_fin'] ?? null,
                'lieu' => $data['lieu'] ?? null,
                'club_id' => $data['club_id'] ?? null,
                'statut' => $data['statut'] ?? 'en_attente',
                'date_creation' => $data['date_creation'] ?? date('Y-m-d H:i:s')
            ];
        } else {
            // Ancien format avec date_activite, nombre_max
            $sql = "INSERT INTO demandeactivite 
                   (nom_activite, description, date_activite, nombre_max, lieu, club_id) 
                   VALUES (:nom, :description, :date, :nombre_max, :lieu, :club_id)";
            
            $params = [
                'nom' => $data['titre'] ?? ($data['nom'] ?? null),
                'description' => $data['description'] ?? null,
                'date' => $data['date'] ?? ($data['date_activite'] ?? null),
                'nombre_max' => $data['nombre_max'] ?? null,
                'lieu' => $data['lieu'] ?? null,
                'club_id' => $data['club_id'] ?? null
            ];
        }
        
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
     * Met à jour le statut d'une demande d'activité
     * 
     * @param int $id ID de la demande
     * @param array $data Données à mettre à jour (statut, commentaire)
     * @return bool Succès ou échec
     */    public function updateStatut($id, $data) {
        // Debug - enregistrer dans un fichier de log
        file_put_contents('debug_model.log', "updateStatut appelé avec ID: $id et données: " . print_r($data, true) . "\n", FILE_APPEND);
        
        // Construire la requête SQL en fonction des données fournies
        $sql = "UPDATE demandeactivite SET statut = :statut";
        $params = [
            'statut' => $data['statut'],
            'id' => $id
        ];
        
        // Ajouter le commentaire s'il existe
        if (isset($data['commentaire']) && !empty($data['commentaire'])) {
            $sql .= ", commentaire = :commentaire";
            $params['commentaire'] = $data['commentaire'];
        }
        
        $sql .= " WHERE id_demande_act = :id";
        
        // Debug - enregistrer la requête SQL
        file_put_contents('debug_model.log', "Requête SQL: $sql\nParamètres: " . print_r($params, true) . "\n", FILE_APPEND);
        
        $result = $this->execute($sql, $params);
        
        // Debug - enregistrer le résultat
        file_put_contents('debug_model.log', "Résultat de execute: $result\n", FILE_APPEND);
        
        return $result;
    }
    
    /**
     * Approuve une demande d'activité et crée l'activité correspondante
     * 
     * @param int $id ID de la demande
     * @param ActiviteModel $activiteModel Instance de ActiviteModel pour créer l'activité
     * @return int|false ID de la nouvelle activité créée ou false
     */
    public function approveAndCreateActivite($id, $activiteModel) {
        file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - ENTER - ID: $id\n", FILE_APPEND);
        // Récupérer les informations de la demande
        $demande = $this->getById($id);
        
        if (!$demande) {
            file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - Demande non trouvée pour ID: $id. EXITING.\n", FILE_APPEND);
            return false;
        }
        
        // Commencer une transaction
        $this->db->beginTransaction(); // Changed from $this->beginTransaction()
        file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - Transaction BEGIN pour ID: $id\n", FILE_APPEND);
        
        try {
            // Préparer les données pour la nouvelle activité
            $activiteData = [
                'titre' => $demande['nom_activite'],
                'description' => $demande['description'],
                'lieu' => $demande['lieu'],
                'club_id' => $demande['club_id'],
                'date_debut' => $demande['date_debut'] ?? ($demande['date_activite'] ?? null),
                'date_fin' => $demande['date_fin'] ?? null 
            ];
            file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - Data for ActiviteModel::create: " . print_r($activiteData, true) . "\n", FILE_APPEND);
            
            file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - CALLING activiteModel->create...\n", FILE_APPEND);
            $activiteId = $activiteModel->create($activiteData); // This calls ActiviteModel::create which has its own detailed logs
            file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - RETURNED from activiteModel->create. activiteId: " . print_r($activiteId, true) . "\n", FILE_APPEND);
            
            // Check if activiteId is falsy (false, null, 0, "0")
            if (!$activiteId || $activiteId === 0 || $activiteId === '0') {
                $this->db->rollBack(); // Changed from $this->rollBack()
                file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - activiteModel->create FAILED or returned invalid ID ($activiteId). Rollback. EXITING.\n", FILE_APPEND);
                return false;
            }
            
            file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - CALLING this->updateStatut...\n", FILE_APPEND);
            $updateSuccess = $this->updateStatut($id, ['statut' => 'approuvee']); // This will produce its own logs (already does)
            file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - RETURNED from this->updateStatut. success: " . print_r($updateSuccess, true) . "\n", FILE_APPEND);

            if (!$updateSuccess) {
                $this->db->rollBack(); // Changed from $this->rollBack()
                file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - updateStatut FAILED. Rollback. EXITING.\n", FILE_APPEND);
                return false;
            }
            
            $this->db->commit(); // Changed from $this->commit()
            file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - Commit SUCCESS. activiteId: $activiteId. EXITING.\n", FILE_APPEND);
            return $activiteId;

        } catch (Exception $e) {
            $this->db->rollBack(); // Changed from $this->rollBack()
            file_put_contents('debug_model.log', "DA_Model::approveAndCreateActivite - EXCEPTION: " . $e->getMessage() . ". Rollback. EXITING.\n", FILE_APPEND);
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
