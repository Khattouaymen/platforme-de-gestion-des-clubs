<?php
require_once APP_PATH . '/models/UserModel.php';

/**
 * Classe AdminModel - Modèle pour la table administrateur
 */
class AdminModel extends UserModel {
    /**
     * Authentifie un administrateur
     * 
     * @param string $email Email de l'administrateur
     * @param string $password Mot de passe
     * @return array|false Données de l'administrateur ou false
     */
    public function authenticate($email, $password) {
        $sql = "SELECT * FROM administrateur WHERE email = :email";
        $admin = $this->single($sql, ['email' => $email]);
        
        if ($admin && password_verify($password, $admin['password'])) {
            unset($admin['password']); // Ne pas retourner le mot de passe
            return $admin;
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
        $sql = "SELECT COUNT(*) as count FROM administrateur WHERE email = :email";
        $result = $this->single($sql, ['email' => $email]);
        
        return $result['count'] > 0;
    }
    
    /**
     * Récupère un administrateur par son ID
     * 
     * @param int $id ID de l'administrateur
     * @return array|false Données de l'administrateur ou false
     */
    public function getById($id) {
        $sql = "SELECT * FROM administrateur WHERE id = :id";
        return $this->single($sql, ['id' => $id]);
    }
}
?>
