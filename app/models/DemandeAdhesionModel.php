<?php
require_once APP_PATH . '/core/Model.php';

/**
 * Classe DemandeAdhesionModel - Modèle pour la table demandeadhesion
 */
class DemandeAdhesionModel extends Model {
    /**
     * Récupère toutes les demandes d'adhésion
     * 
     * @return array Liste des demandes d'adhésion
     */
    public function getAll() {
        $sql = "SELECT d.*, e.nom as etudiant_nom, e.prenom as etudiant_prenom, c.nom as club_nom
                FROM demandeadhesion d
                LEFT JOIN etudiant e ON d.etudiant_id = e.id_etudiant
                LEFT JOIN club c ON d.club_id = c.id";
        return $this->multiple($sql);
    }
    
    /**
     * Récupère les demandes avec un statut spécifique
     * 
     * @param string $statut Statut des demandes (en_attente, acceptee, refusee)
     * @return array Liste des demandes avec ce statut
     */
    public function getByStatut($statut) {
        $sql = "SELECT d.*, e.nom as etudiant_nom, e.prenom as etudiant_prenom, c.nom as club_nom
                FROM demandeadhesion d
                LEFT JOIN etudiant e ON d.etudiant_id = e.id_etudiant
                LEFT JOIN club c ON d.club_id = c.id
                WHERE d.statut = :statut";
        return $this->multiple($sql, ['statut' => $statut]);
    }
    
    /**
     * Récupère une demande d'adhésion par son ID
     * 
     * @param int $id ID de la demande
     * @return array|false Données de la demande ou false
     */
    public function getById($id) {
        $sql = "SELECT d.*, e.nom as etudiant_nom, e.prenom as etudiant_prenom, c.nom as club_nom
                FROM demandeadhesion d
                LEFT JOIN etudiant e ON d.etudiant_id = e.id_etudiant
                LEFT JOIN club c ON d.club_id = c.id
                WHERE d.demande_adh_id = :id";
        return $this->single($sql, ['id' => $id]);
    }
    
    /**
     * Récupère les demandes d'adhésion d'un étudiant spécifique
     * 
     * @param int $etudiantId ID de l'étudiant
     * @return array Liste des demandes de l'étudiant
     */
    public function getByEtudiantId($etudiantId) {
        $sql = "SELECT d.*, c.nom as club_nom
                FROM demandeadhesion d
                LEFT JOIN club c ON d.club_id = c.id
                WHERE d.etudiant_id = :etudiant_id";
        return $this->multiple($sql, ['etudiant_id' => $etudiantId]);
    }
    
    /**
     * Récupère les demandes d'adhésion pour un club spécifique
     * 
     * @param int $clubId ID du club     * @return array Liste des demandes pour ce club
     */    public function getByClubId($clubId) {
        $sql = "SELECT d.demande_adh_id, d.etudiant_id, d.club_id, d.date_demande, d.statut, 
                d.motivation, d.date_traitement,
                e.nom as etudiant_nom, e.prenom as etudiant_prenom, e.email as etudiant_email, 
                e.filiere as etudiant_filiere, e.niveau as etudiant_niveau, e.numero_etudiant as etudiant_numero 
                FROM demandeadhesion d 
                LEFT JOIN etudiant e ON d.etudiant_id = e.id_etudiant 
                WHERE d.club_id = :club_id";
        return $this->multiple($sql, ['club_id' => $clubId]);
    }
      /**
     * Crée une nouvelle demande d'adhésion
     * 
     * @param array $data Données de la demande
     * @return int|false ID de la nouvelle demande ou false
     */    public function create($data) {
        $sql = "INSERT INTO demandeadhesion (etudiant_id, club_id, date_demande, statut, motivation, date_traitement) 
                VALUES (:etudiant_id, :club_id, :date_demande, :statut, :motivation, :date_traitement)";
        
        $params = [
            'etudiant_id' => $data['etudiant_id'],
            'club_id' => $data['club_id'],
            'date_demande' => $data['date_demande'] ?? date('Y-m-d'),
            'statut' => $data['statut'] ?? 'en_attente',
            'motivation' => $data['motivation'] ?? null,
            'date_traitement' => $data['date_traitement'] ?? null
        ];
        
        if ($this->execute($sql, $params)) {
            return $this->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Met à jour le statut d'une demande
     * 
     * @param int $id ID de la demande
     * @param string $statut Nouveau statut (en_attente, acceptee, refusee)
     * @return bool Succès ou échec
     */    public function updateStatut($id, $statut) {
        $sql = "UPDATE demandeadhesion SET statut = :statut, date_traitement = :date_traitement 
                WHERE demande_adh_id = :id";
        return $this->execute($sql, [
            'statut' => $statut, 
            'date_traitement' => date('Y-m-d'),
            'id' => $id
        ]);
    }      /**
     * Accepte une demande d'adhésion et ajoute l'étudiant au club
     * 
     * @param int $id ID de la demande
     * @return bool Succès ou échec
     */    
    public function accepterEtAjouterMembre($id) {
        // Récupérer les informations de la demande
        $demande = $this->getById($id);
        
        if (!$demande) {
            return false;
        }

        if (!isset($demande['etudiant_id']) || !isset($demande['club_id'])) {
            return false;
        }
        
        try {
            // Mettre à jour le statut de la demande
            $updateStatutSuccess = $this->updateStatut($id, 'acceptee');
            
            if (!$updateStatutSuccess) {
                return false;
            }
                
            // Utiliser ClubModel pour ajouter le membre
            require_once APP_PATH . '/models/ClubModel.php';
            $clubModel = new ClubModel();
            
            // Ajouter l'étudiant au club
            $membreId = $clubModel->addMember($demande['club_id'], $demande['etudiant_id'], 'membre');
              
            // Si l'ajout a réussi, retourner true
            return $membreId !== false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Refuse une demande d'adhésion
     * 
     * @param int $id ID de la demande
     * @return bool Succès ou échec
     */
    public function refuser($id) {
        return $this->updateStatut($id, 'refusee');
    }
    
    /**
     * Supprime une demande d'adhésion
     * 
     * @param int $id ID de la demande à supprimer
     * @return bool Succès ou échec
     */
    public function delete($id) {
        $sql = "DELETE FROM demandeadhesion WHERE demande_adh_id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
}
