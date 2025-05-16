<?php
require_once APP_PATH . '/models/UserModel.php';

/**
 * Classe ResponsableModel - Modèle pour les responsables de clubs
 */
class ResponsableModel extends UserModel {
    /**
     * Authentifie un responsable
     * 
     * @param string $email Email du responsable
     * @param string $password Mot de passe
     * @return array|false Données du responsable ou false
     */
    public function authenticate($email, $password) {
        $sql = "SELECT * FROM responsable WHERE email = :email";
        $responsable = $this->single($sql, ['email' => $email]);
        
        if ($responsable && password_verify($password, $responsable['password'])) {
            unset($responsable['password']); // Ne pas retourner le mot de passe
            return $responsable;
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
        $sql = "SELECT COUNT(*) as count FROM responsable WHERE email = :email";
        $result = $this->single($sql, ['email' => $email]);
        
        return $result['count'] > 0;
    }
    
    /**
     * Enregistre un nouveau responsable
     * 
     * @param array $data Données du responsable
     * @return int|false ID du responsable ou false
     */
    public function register($data) {
        // Hacher le mot de passe
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Insérer dans la base de données
        $sql = "INSERT INTO responsable (nom, prenom, email, password, date_creation) VALUES (:nom, :prenom, :email, :password, NOW())";
        
        $params = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'password' => $passwordHash
        ];
        
        return $this->execute($sql, $params) ? $this->lastInsertId() : false;
    }
    
    /**
     * Récupère un responsable par son ID
     * 
     * @param int $id ID du responsable
     * @return array|false Données du responsable ou false
     */
    public function getById($id) {
        $sql = "SELECT * FROM responsable WHERE id = :id";
        $responsable = $this->single($sql, ['id' => $id]);
        
        if ($responsable) {
            unset($responsable['password']); // Ne pas retourner le mot de passe
        }
        
        return $responsable;
    }
}
?>
