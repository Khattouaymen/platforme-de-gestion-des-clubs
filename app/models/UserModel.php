<?php
require_once APP_PATH . '/core/Model.php';

/**
 * Classe UserModel - Modèle de base pour les utilisateurs
 */
class UserModel extends Model {
    /**
     * Authentifie un utilisateur
     * 
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe
     * @return array|false Données de l'utilisateur ou false
     */
    public function authenticate($email, $password) {
        return false; // À surcharger dans les classes enfants
    }
    
    /**
     * Vérifie si un email existe déjà
     * 
     * @param string $email Email à vérifier
     * @return bool True si l'email existe, false sinon
     */
    public function emailExists($email) {
        return false; // À surcharger dans les classes enfants
    }
}
?>
