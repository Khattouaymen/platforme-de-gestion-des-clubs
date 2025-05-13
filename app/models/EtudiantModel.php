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
     */
    public function authenticate($email, $password) {
        $sql = "SELECT * FROM etudiant WHERE email = :email";
        $etudiant = $this->single($sql, ['email' => $email]);
        
        if ($etudiant && password_verify($password, $etudiant['password'])) {
            unset($etudiant['password']); // Ne pas retourner le mot de passe
            return $etudiant;
        }
        
        return false;
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
}
?>
