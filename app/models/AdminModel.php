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

    /**
     * Sauvegarde un token d'inscription pour responsable
     * 
     * @param string $token Token unique
     * @return int|false ID du token ou false
     */
    public function saveResponsableToken($token) {
        // Vérifier si une table pour les tokens existe déjà, sinon la créer
        $this->createResponsableTokenTableIfNotExists();
        
        $sql = "INSERT INTO inscription_token (token, type, date_creation) VALUES (:token, 'responsable', NOW())";
        $stmt = $this->prepare($sql);
        $stmt->execute(['token' => $token]);
        
        return $this->lastInsertId();
    }
    
    /**
     * Invalide tous les tokens d'inscription pour responsable
     * 
     * @return bool Succès de l'opération
     */
    public function invalidateResponsableTokens() {
        // Vérifier si une table pour les tokens existe déjà, sinon la créer
        $this->createResponsableTokenTableIfNotExists();
        
        $sql = "UPDATE inscription_token SET est_utilise = 1 WHERE type = 'responsable' AND est_utilise = 0";
        $stmt = $this->prepare($sql);
        return $stmt->execute();
    }
    
    /**
     * Vérifie si un token est valide
     * 
     * @param string $token Token à vérifier
     * @return bool True si le token est valide, false sinon
     */
    public function isTokenValid($token) {
        // Vérifier si une table pour les tokens existe déjà, sinon la créer
        $this->createResponsableTokenTableIfNotExists();
        
        $sql = "SELECT id FROM inscription_token WHERE token = :token AND type = 'responsable' AND est_utilise = 0";
        $stmt = $this->prepare($sql);
        $stmt->execute(['token' => $token]);
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Marque un token comme utilisé
     * 
     * @param string $token Token à marquer
     * @return bool Succès de l'opération
     */
    public function useToken($token) {
        // Vérifier si une table pour les tokens existe déjà, sinon la créer
        $this->createResponsableTokenTableIfNotExists();
        
        $sql = "UPDATE inscription_token SET est_utilise = 1, date_utilisation = NOW() WHERE token = :token";
        $stmt = $this->prepare($sql);
        return $stmt->execute(['token' => $token]);
    }
    
    /**
     * Crée la table des tokens d'inscription si elle n'existe pas
     * 
     * @return void
     */
    private function createResponsableTokenTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS inscription_token (
            id INT NOT NULL AUTO_INCREMENT,
            token VARCHAR(255) NOT NULL,
            type VARCHAR(50) NOT NULL,
            date_creation DATETIME NOT NULL,
            date_utilisation DATETIME NULL,
            est_utilise TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY (token)
        )";
        
        $this->query($sql);
    }
}
?>
