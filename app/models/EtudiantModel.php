<?php
require_once APP_PATH . '/models/UserModel.php';

/**
 * Classe EtudiantModel - Modèle pour la table etudiant
 */
class EtudiantModel extends UserModel {
    /**
     * Authentifie un étudiant
     * 
     * @param string $email Email de l'étudiant
     * @param string $password Mot de passe
     * @return array|false Données de l'étudiant ou false
     */    public function authenticate($email, $password) {
        // Vérifier si la colonne is_future_responsable existe
        $this->checkAndAddFutureResponsableColumn();
        
        $sql = "SELECT * FROM etudiant WHERE email = :email";
        $etudiant = $this->single($sql, ['email' => $email]);
        
        if ($etudiant && password_verify($password, $etudiant['password'])) {
            unset($etudiant['password']); // Ne pas retourner le mot de passe
            
            // Si l'étudiant est marqué comme futur responsable, ajouter à la session
            if (isset($etudiant['is_future_responsable']) && $etudiant['is_future_responsable'] == 1) {
                $_SESSION['is_future_responsable'] = true;
            }
            
            return $etudiant;
        }
        
        return false;
    }
      /**
     * Vérifie si un étudiant est un responsable de club
     * 
     * @param int $etudiantId ID de l'étudiant
     * @return array|false Résultat de la requête ou false
     */
    public function checkIfResponsable($etudiantId) {
        $sql = "SELECT id_responsable FROM responsableclub WHERE id_etudiant = :id_etudiant";
        return $this->single($sql, ['id_etudiant' => $etudiantId]);
    }

    /**
     * Enregistre un nouvel étudiant
     * 
     * @param array $data Données de l'étudiant
     * @return int|false ID de l'étudiant ou false
     */
    public function register($data) {
        // Hachage du mot de passe
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO etudiant (nom, prenom, email, password) 
                VALUES (:nom, :prenom, :email, :password)";
        
        $params = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'password' => $hashedPassword
        ];
        
        if ($this->execute($sql, $params)) {
            return $this->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Vérifie si un email existe déjà
     * 
     * @param string $email Email à vérifier
     * @return bool True si l'email existe, false sinon
     */
    public function emailExists($email) {
        $sql = "SELECT COUNT(*) as count FROM etudiant WHERE email = :email";
        $result = $this->single($sql, ['email' => $email]);
        
        return $result['count'] > 0;
    }
    
    /**
     * Récupère un étudiant par son ID
     * 
     * @param int $id ID de l'étudiant
     * @return array|false Données de l'étudiant ou false
     */
    public function getById($id) {
        $sql = "SELECT * FROM etudiant WHERE id_etudiant = :id";
        return $this->single($sql, ['id' => $id]);
    }
      /**
     * Récupère tous les étudiants
     * 
     * @return array Liste des étudiants
     */
    public function getAll() {
        $sql = "SELECT * FROM etudiant";
        return $this->multiple($sql);
    }
    
    /**
     * Récupère les étudiants qui ne sont pas déjà responsables d'un club
     * 
     * @return array Liste des étudiants disponibles pour être responsables
     */
    public function getAvailableForResponsable() {
        $sql = "SELECT e.* FROM etudiant e 
                LEFT JOIN responsableclub r ON e.id_etudiant = r.id_etudiant 
                WHERE r.id_etudiant IS NULL";
        return $this->multiple($sql);
    }
    
    /**
     * Met à jour les informations d'un étudiant
     * 
     * @param int $id ID de l'étudiant
     * @param array $data Nouvelles données
     * @return bool Succès de la mise à jour
     */
    public function update($id, $data) {
        $sql = "UPDATE etudiant SET 
                nom = :nom, 
                prenom = :prenom, 
                email = :email 
                WHERE id_etudiant = :id";
        
        $params = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'id' => $id
        ];
        
        return $this->execute($sql, $params) > 0;
    }
    
    /**
     * Change le mot de passe d'un étudiant
     * 
     * @param int $id ID de l'étudiant
     * @param string $password Nouveau mot de passe
     * @return bool Succès du changement
     */
    public function changePassword($id, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE etudiant SET password = :password WHERE id_etudiant = :id";
        $params = ['password' => $hashedPassword, 'id' => $id];
        
        return $this->execute($sql, $params) > 0;
    }
      /**
     * Marque un étudiant comme futur responsable (uniquement dans la session)
     * 
     * @param int $etudiantId ID de l'étudiant
     * @return bool Toujours true (opération simplifiée)
     */
    public function markAsFutureResponsable($etudiantId) {
        // Dans une implémentation réelle, nous mettrions à jour la base de données
        // Pour l'instant, nous gérons cela uniquement via la session
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $etudiantId) {
            $_SESSION['is_future_responsable'] = true;
        }
        
        return true;
    }
      /**
     * Vérifie si la colonne is_future_responsable existe et l'ajoute si nécessaire
     * Cette méthode est désactivée pour l'instant car elle nécessite des privilèges admin
     * 
     * @return void
     */
    private function checkAndAddFutureResponsableColumn() {
        // Désactivé pour éviter des erreurs si l'utilisateur n'a pas les privilèges nécessaires
        // Dans un système de production, cette modification serait faite par un script d'installation
        return;
        
        /*
        $sql = "SHOW COLUMNS FROM etudiant LIKE 'is_future_responsable'";
        $result = $this->single($sql);
        
        if (!$result) {
            $sql = "ALTER TABLE etudiant ADD COLUMN is_future_responsable TINYINT(1) NOT NULL DEFAULT 0";
            $this->execute($sql);
        }
        */
    }
}
?>
