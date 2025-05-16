<?php
require_once APP_PATH . '/core/Model.php';

/**
 * Classe DemandeClubModel - Modèle pour la table demandeapprobationclub
 */
class DemandeClubModel extends Model {
    /**
     * Récupère toutes les demandes d'approbation de clubs
     * 
     * @return array Liste des demandes
     */
    public function getAll() {
        $sql = "SELECT d.*, e.nom as etudiant_nom, e.prenom as etudiant_prenom 
                FROM demandeapprobationclub d
                LEFT JOIN etudiant e ON d.id_etudiant = e.id_etudiant";
        return $this->multiple($sql);
    }
    
    /**
     * Récupère les demandes avec un statut spécifique
     * 
     * @param string $statut Statut des demandes (en_attente, approuve, rejete)
     * @return array Liste des demandes avec ce statut
     */
    public function getByStatut($statut) {
        $sql = "SELECT d.*, e.nom as etudiant_nom, e.prenom as etudiant_prenom 
                FROM demandeapprobationclub d
                LEFT JOIN etudiant e ON d.id_etudiant = e.id_etudiant
                WHERE d.statut = :statut";
        return $this->multiple($sql, ['statut' => $statut]);
    }
    
    /**
     * Récupère une demande par son ID
     * 
     * @param int $id ID de la demande
     * @return array|false Données de la demande ou false
     */
    public function getById($id) {
        $sql = "SELECT d.*, e.nom as etudiant_nom, e.prenom as etudiant_prenom 
                FROM demandeapprobationclub d
                LEFT JOIN etudiant e ON d.id_etudiant = e.id_etudiant
                WHERE d.id_demande = :id";
        return $this->single($sql, ['id' => $id]);
    }
    
    /**
     * Récupère les demandes d'un étudiant spécifique
     * 
     * @param int $etudiantId ID de l'étudiant
     * @return array Liste des demandes de l'étudiant
     */
    public function getByEtudiantId($etudiantId) {
        $sql = "SELECT * FROM demandeapprobationclub WHERE id_etudiant = :id_etudiant";
        return $this->multiple($sql, ['id_etudiant' => $etudiantId]);
    }
    
    /**
     * Crée une nouvelle demande d'approbation de club
     * 
     * @param array $data Données de la demande
     * @return int|false ID de la nouvelle demande ou false
     */
    public function create($data) {
        $sql = "INSERT INTO demandeapprobationclub (nom_club, description, Logo_URL, statut, id_etudiant) 
                VALUES (:nom, :description, :logo, :statut, :id_etudiant)";
        
        $params = [
            'nom' => $data['nom'],
            'description' => $data['description'],
            'logo' => $data['logo'] ?? null,
            'statut' => $data['statut'] ?? 'en_attente',
            'id_etudiant' => $data['id_etudiant']
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
     * @param string $statut Nouveau statut (en_attente, approuve, rejete)
     * @return bool Succès ou échec
     */
    public function updateStatut($id, $statut) {
        $sql = "UPDATE demandeapprobationclub SET statut = :statut WHERE id_demande = :id";
        return $this->execute($sql, ['statut' => $statut, 'id' => $id]);
    }
    
    /**
     * Approuve une demande et crée le club correspondant
     * 
     * @param int $id ID de la demande
     * @param ClubModel $clubModel Instance de ClubModel pour créer le club
     * @return int|false ID du nouveau club créé ou false
     */
    public function approveAndCreateClub($id, $clubModel) {
        // Récupérer les informations de la demande
        $demande = $this->getById($id);
        
        if (!$demande) {
            return false;
        }
        
        // Commencer une transaction
        $this->beginTransaction();
        
        try {
            // Mettre à jour le statut de la demande
            $updateSuccess = $this->updateStatut($id, 'approuve');
            
            if (!$updateSuccess) {
                $this->rollBack();
                return false;
            }
            
            // Créer le nouveau club
            $clubData = [
                'nom' => $demande['nom_club'],
                'description' => $demande['description'],
                'logo' => $demande['Logo_URL']
            ];
            
            $clubId = $clubModel->create($clubData);
            
            if (!$clubId) {
                $this->rollBack();
                return false;
            }
            
            $this->commit();
            return $clubId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
    
    /**
     * Rejette une demande
     * 
     * @param int $id ID de la demande
     * @return bool Succès ou échec
     */
    public function reject($id) {
        return $this->updateStatut($id, 'rejete');
    }
    
    /**
     * Supprime une demande
     * 
     * @param int $id ID de la demande à supprimer
     * @return bool Succès ou échec
     */
    public function delete($id) {
        $sql = "DELETE FROM demandeapprobationclub WHERE id_demande = :id";
        return $this->execute($sql, ['id' => $id]);
    }
}
