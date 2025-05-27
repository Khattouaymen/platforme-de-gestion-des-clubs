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
    
    /**
     * Assigne un responsable à un club
     * 
     * @param int $clubId ID du club
     * @param int $etudiantId ID de l'étudiant
     * @return bool Succès de l'opération
     */
    public function assignResponsable($clubId, $etudiantId) {
        // Vérifier d'abord si l'étudiant est déjà responsable d'un club
        $checkSql = "SELECT COUNT(*) as count FROM responsableclub WHERE id_etudiant = :etudiantId";
        $result = $this->single($checkSql, ['etudiantId' => $etudiantId]);
        
        if ($result['count'] > 0) {
            return false; // L'étudiant est déjà responsable d'un club
        }
        
        // Insérer le nouvel enregistrement
        $sql = "INSERT INTO responsableclub (id_etudiant, club_id) VALUES (:etudiantId, :clubId)";
        $params = ['etudiantId' => $etudiantId, 'clubId' => $clubId];
        
        return $this->execute($sql, $params) > 0;
    }
    
    /**
     * Obtient le responsable d'un club
     * 
     * @param int $clubId ID du club
     * @return array|false Informations sur le responsable ou false
     */
    public function getResponsable($clubId) {
        $sql = "SELECT e.* FROM etudiant e
                JOIN responsableclub r ON e.id_etudiant = r.id_etudiant
                WHERE r.club_id = :clubId";
        
        return $this->single($sql, ['clubId' => $clubId]);
    }
    
    /**
     * Récupère les membres d'un club avec leurs rôles
     * 
     * @param int $clubId ID du club
     * @return array Liste des membres
     */
    public function getMembers($clubId) {
        $sql = "SELECT e.id_etudiant, e.nom, e.prenom, e.email, mc.role
                FROM etudiant e
                JOIN membreclub mc ON e.id_etudiant = mc.id_etudiant
                WHERE mc.club_id = :clubId
                ORDER BY 
                    CASE 
                        WHEN mc.role = 'president' THEN 1
                        WHEN mc.role = 'secretaire' THEN 2
                        WHEN mc.role = 'tresorier' THEN 3
                        ELSE 4
                    END";
        
        return $this->multiple($sql, ['clubId' => $clubId]);
    }
    
    /**
     * Récupère les activités d'un club
     * 
     * @param int $clubId ID du club
     * @return array Liste des activités
     */
    public function getActivities($clubId) {
        $sql = "SELECT a.*, c.nom as club_nom
                FROM activite a
                JOIN club c ON a.club_id = c.id
                WHERE a.club_id = :clubId
                ORDER BY a.date_activite DESC";
        
        return $this->multiple($sql, ['clubId' => $clubId]);
    }
    
    /**
     * Ajoute un étudiant en tant que membre du club
     * 
     * @param int $clubId ID du club
     * @param int $etudiantId ID de l'étudiant
     * @param string $role Rôle du membre
     * @return int|false ID du membre ajouté ou false
     */
    public function addMember($clubId, $etudiantId, $role = 'membre') {
        // Vérifier si l'étudiant est déjà membre du club
        $checkSql = "SELECT COUNT(*) as count FROM membreclub 
                     WHERE id_etudiant = :etudiantId AND club_id = :clubId";
        $result = $this->single($checkSql, ['etudiantId' => $etudiantId, 'clubId' => $clubId]);
        
        if ($result['count'] > 0) {
            return false; // L'étudiant est déjà membre du club
        }
        
        // Ajouter l'étudiant au club
        $sql = "INSERT INTO membreclub (id_etudiant, club_id, role) 
                VALUES (:etudiantId, :clubId, :role)";
        $params = [
            'etudiantId' => $etudiantId,
            'clubId' => $clubId,
            'role' => $role
        ];
        
        if ($this->execute($sql, $params)) {
            // Mettre à jour le nombre de membres du club
            $this->incrementMemberCount($clubId);
            return $this->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Supprime un étudiant des membres du club
     * 
     * @param int $clubId ID du club
     * @param int $etudiantId ID de l'étudiant
     * @return bool Succès de la suppression
     */
    public function removeMember($clubId, $etudiantId) {
        $sql = "DELETE FROM membreclub 
                WHERE id_etudiant = :etudiantId AND club_id = :clubId";
        
        $result = $this->execute($sql, ['etudiantId' => $etudiantId, 'clubId' => $clubId]);
        
        if ($result > 0) {
            // Mettre à jour le nombre de membres du club
            $this->decrementMemberCount($clubId);
            return true;
        }
        
        return false;
    }
    
    /**
     * Change le rôle d'un membre
     * 
     * @param int $clubId ID du club
     * @param int $etudiantId ID de l'étudiant
     * @param string $newRole Nouveau rôle
     * @return bool Succès du changement
     */
    public function updateMemberRole($clubId, $etudiantId, $newRole) {
        $sql = "UPDATE membreclub SET role = :newRole 
                WHERE id_etudiant = :etudiantId AND club_id = :clubId";
        
        $params = [
            'newRole' => $newRole,
            'etudiantId' => $etudiantId,
            'clubId' => $clubId
        ];
        
        return $this->execute($sql, $params) > 0;
    }
    
    /**
     * Incrémente le nombre de membres du club
     * 
     * @param int $clubId ID du club
     * @return bool Succès de l'opération
     */
    private function incrementMemberCount($clubId) {
        $sql = "UPDATE club SET nombre_membres = nombre_membres + 1 WHERE id = :clubId";
        return $this->execute($sql, ['clubId' => $clubId]) > 0;
    }
    
    /**
     * Décrémente le nombre de membres du club
     * 
     * @param int $clubId ID du club
     * @return bool Succès de l'opération
     */
    private function decrementMemberCount($clubId) {
        $sql = "UPDATE club SET nombre_membres = GREATEST(0, nombre_membres - 1) WHERE id = :clubId";
        return $this->execute($sql, ['clubId' => $clubId]) > 0;
    }
    
    /**
     * Récupère les blogs/actualités d'un club
     * 
     * @param int $clubId ID du club
     * @return array Liste des articles du blog
     */
    public function getBlogPosts($clubId) {
        $sql = "SELECT * FROM blog WHERE club_id = :clubId ORDER BY date_creation DESC";
        return $this->multiple($sql, ['clubId' => $clubId]);
    }
    
    /**
     * Récupère les clubs disponibles pour rejoindre
     * (clubs actifs avec possibilité d'adhésion)
     * 
     * @return array Liste des clubs disponibles
     */
    public function getAvailableClubs() {
        $sql = "SELECT * FROM club WHERE id IN (
                    SELECT club_id FROM club WHERE 1
                ) ORDER BY nom";
        return $this->multiple($sql);
    }

    /**
     * Récupère tous les clubs avec le nombre d'activités
     * 
     * @return array Liste des clubs avec le nombre d'activités
     */
    public function getAllWithDetails() {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM activite a WHERE a.club_id = c.id) as activites_count 
                FROM club c 
                ORDER BY c.id";
        return $this->multiple($sql);
    }

    /**
     * Récupère les membres d'un club
     * 
     * @param int $clubId ID du club
     * @return array Liste des membres
     */
    public function getMembresByClubId($clubId) {
        $sql = "SELECT mc.id_membre, mc.role, e.id_etudiant, e.nom, e.prenom, e.email 
                FROM membreclub mc 
                JOIN etudiant e ON mc.id_etudiant = e.id_etudiant 
                WHERE mc.club_id = :clubId";
        return $this->multiple($sql, ['clubId' => $clubId]);
    }

    /**
     * Récupère un membre par son ID
     * 
     * @param int $membreId ID du membre
     * @return array|false Informations sur le membre ou false
     */
    public function getMembreById($membreId) {
        $sql = "SELECT mc.*, e.nom, e.prenom, e.email 
                FROM membreclub mc 
                JOIN etudiant e ON mc.id_etudiant = e.id_etudiant 
                WHERE mc.id_membre = :membreId";
        return $this->single($sql, ['membreId' => $membreId]);
    }

    /**
     * Récupère les activités d'un club
     * 
     * @param int $clubId ID du club
     * @return array Liste des activités
     */
    public function getActivitesByClubId($clubId) {
        $sql = "SELECT * FROM activite WHERE club_id = :clubId ORDER BY date_activite DESC";
        return $this->multiple($sql, ['clubId' => $clubId]);
    }

    /**
     * Récupère les informations de présence pour une activité
     * 
     * @param int $activiteId ID de l'activité
     * @return array Liste des présences
     */    public function getPresenceByActiviteId($activiteId) {
        $sql = "SELECT pa.*, e.nom, e.prenom, e.email 
                FROM participationactivite pa 
                JOIN etudiant e ON pa.etudiant_id = e.id_etudiant 
                WHERE pa.activite_id = :activiteId";
        return $this->multiple($sql, ['activiteId' => $activiteId]);
    }
    
    /**
     * Récupère le responsable d'un club
     * 
     * @param int $clubId ID du club
     * @return array|false Données du responsable ou false
     */
    public function getResponsableByClubId($clubId) {
        $sql = "SELECT rc.*, e.nom, e.prenom, e.email 
                FROM responsableclub rc 
                JOIN etudiant e ON rc.id_etudiant = e.id_etudiant 
                WHERE rc.club_id = :clubId";
        return $this->single($sql, ['clubId' => $clubId]);
    }

    /**
     * Récupère l'ID du club géré par un responsable
     * 
     * @param int $responsableId ID de l'étudiant responsable
     * @return int|false ID du club ou false si non trouvé
     */
    public function getClubIdByResponsableId($responsableId) {
        $sql = "SELECT club_id FROM responsableclub WHERE id_etudiant = :responsableId";
        $result = $this->single($sql, ['responsableId' => $responsableId]);
        
        return $result ? $result['club_id'] : false;
    }

    /**
     * Récupère les articles de blog d'un club
     *
     * @param int $clubId ID du club
     * @return array Liste des articles de blog
     */
    public function getBlogArticles($clubId = null) { // Modifié pour accepter $clubId optionnel
        if ($clubId) {
            $sql = "SELECT b.*, c.nom as nom_club FROM blog b JOIN club c ON b.club_id = c.id WHERE b.club_id = :club_id ORDER BY b.date_creation DESC";
            return $this->multiple($sql, ['club_id' => $clubId]);
        } else {
            // Récupère tous les articles de blog si aucun club_id n'est fourni
            $sql = "SELECT b.*, c.nom as nom_club FROM blog b JOIN club c ON b.club_id = c.id ORDER BY b.date_creation DESC";
            return $this->multiple($sql);
        }
    }

    /**
     * Récupère les clubs dont un étudiant est membre
     *
     * @param int $etudiantId ID de l'étudiant
     * @return array Liste des clubs
     */
    public function getClubsByEtudiantId($etudiantId) {
        $sql = "SELECT c.*, mc.role 
                FROM club c
                JOIN membreclub mc ON c.id = mc.club_id
                WHERE mc.id_etudiant = :etudiantId
                ORDER BY c.nom";
        return $this->multiple($sql, ['etudiantId' => $etudiantId]);
    }
}
