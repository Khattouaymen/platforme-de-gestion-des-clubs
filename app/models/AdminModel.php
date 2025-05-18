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
        return $this->execute($sql, ['token' => $token]) ? $this->lastInsertId() : false;
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
        return $this->execute($sql);
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
        $result = $this->single($sql, ['token' => $token]);
        
        return $result !== false;
    }
      /**
     * Marque un token comme utilisé et enregistre l'ID de l'étudiant qui l'a utilisé
     * 
     * @param string $token Token à marquer
     * @param int $etudiantId ID de l'étudiant (optionnel)
     * @return bool Succès de l'opération
     */    
    public function useToken($token, $etudiantId = null) {
        // Vérifier si une table pour les tokens existe déjà, sinon la créer
        $this->createResponsableTokenTableIfNotExists();
        
        // Vérifier si la colonne etudiant_id existe, sinon l'ajouter
        $this->addEtudiantIdColumnIfNotExists();
        
        $sql = "UPDATE inscription_token SET est_utilise = 1, date_utilisation = NOW()";
        $params = ['token' => $token];
        
        // Si un ID étudiant est fourni, on l'enregistre aussi
        if ($etudiantId !== null) {
            $sql .= ", etudiant_id = :etudiant_id";
            $params['etudiant_id'] = $etudiantId;
        }
        
        $sql .= " WHERE token = :token";
        
        return $this->execute($sql, $params);
    }
    
    /**
     * Crée la table des tokens d'inscription si elle n'existe pas
     * 
     * @return void
     */    
    private function createResponsableTokenTableIfNotExists() {        
        $sql = "CREATE TABLE IF NOT EXISTS inscription_token (
            id INT NOT NULL AUTO_INCREMENT,
            token VARCHAR(191) NOT NULL,
            type VARCHAR(50) NOT NULL,
            date_creation DATETIME NOT NULL,
            date_utilisation DATETIME NULL,
            est_utilise TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY (token(191))
        )";
        
        $this->execute($sql);
    }

    /**
     * Vérifie si la colonne etudiant_id existe dans la table inscription_token et l'ajoute si nécessaire
     * 
     * @return void
     */
    private function addEtudiantIdColumnIfNotExists() {
        try {
            // Vérifier si la colonne existe déjà
            $sql = "SHOW COLUMNS FROM inscription_token LIKE 'etudiant_id'";
            $result = $this->single($sql);
            
            if (!$result) {
                // Ajouter la colonne si elle n'existe pas
                $sql = "ALTER TABLE inscription_token ADD COLUMN etudiant_id INT NULL DEFAULT NULL AFTER date_utilisation";
                $this->execute($sql);
            }
        } catch (Exception $e) {
            // Ignorer les erreurs pour éviter de bloquer l'application
            // Dans un environnement de production, on devrait logger cette erreur
        }
    }    /**
     * Récupère les étudiants qui sont marqués comme futurs responsables (via tokens)
     * 
     * @return array Liste des étudiants
     */    
    public function getFutureResponsables() {
        // Vérifier si la colonne etudiant_id existe, sinon l'ajouter
        $this->addEtudiantIdColumnIfNotExists();
        
        // Sélectionne les étudiants qui ont utilisé un token de type responsable
        // mais qui ne sont pas encore assignés comme responsables d'un club
        $sql = "SELECT e.id_etudiant, e.nom, e.prenom, e.email, t.date_utilisation
                FROM etudiant e 
                JOIN inscription_token t ON e.id_etudiant = t.etudiant_id
                WHERE t.type = 'responsable' 
                AND t.est_utilise = 1
                AND NOT EXISTS (
                    SELECT 1 FROM responsableclub r WHERE r.id_etudiant = e.id_etudiant
                )";
                
        return $this->multiple($sql);
                
        /* Implémentation avec la colonne is_future_responsable:
        $sql = "SELECT e.id_etudiant, e.nom, e.prenom, e.email, NOW() as date_utilisation
                FROM etudiant e 
                WHERE e.is_future_responsable = 1
                AND NOT EXISTS (
                    SELECT 1 FROM responsableclub r WHERE r.id_etudiant = e.id_etudiant
                )";
                
        return $this->multiple($sql);
        */
    }

    /**
     * Récupère la liste des responsables de club actuels avec leurs informations
     * 
     * @return array Liste des responsables
     */    
    public function getResponsables() {
        $sql = "SELECT r.id_responsable, r.id_etudiant, r.club_id, 
                       e.nom as etudiant_nom, e.prenom as etudiant_prenom, e.email as etudiant_email,
                       c.nom as club_nom
                FROM responsableclub r
                JOIN etudiant e ON r.id_etudiant = e.id_etudiant
                JOIN club c ON r.club_id = c.id
                ORDER BY c.nom, e.nom, e.prenom";
        
        return $this->multiple($sql);
    }

    /**
     * Assigne un étudiant comme responsable d'un club
     * 
     * @param int $etudiantId ID de l'étudiant
     * @param int $clubId ID du club
     * @return bool|int ID du responsable ou false en cas d'échec
     */    
    public function assignerResponsable($etudiantId, $clubId) {
        // Assigner l'étudiant comme responsable
        $sql = "INSERT INTO responsableclub (id_etudiant, club_id) 
                VALUES (:etudiant_id, :club_id)";
        
        $params = [
            'etudiant_id' => $etudiantId,
            'club_id' => $clubId
        ];
        
        $success = $this->execute($sql, $params);
        
        if ($success) {
            // Dans une version future, nous réinitialiserions le flag is_future_responsable ici
            // $sql = "UPDATE etudiant SET is_future_responsable = 0 WHERE id_etudiant = :id";
            // $this->execute($sql, ['id' => $etudiantId]);
            
            // Pour maintenant, nous supprimons simplement la session is_future_responsable
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $etudiantId) {
                unset($_SESSION['is_future_responsable']);
            }
            
            return $this->lastInsertId();
        }
        
        return false;
    }

    /**
     * Change le club d'un responsable
     * 
     * @param int $responsableId ID du responsable
     * @param int $clubId ID du nouveau club
     * @return bool Succès de l'opération
     */
    public function changerClubResponsable($responsableId, $clubId) {
        $sql = "UPDATE responsableclub SET club_id = :club_id 
                WHERE id_responsable = :responsable_id";
        
        $params = [
            'responsable_id' => $responsableId,
            'club_id' => $clubId
        ];
        
        return $this->execute($sql, $params);
    }

    /**
     * Retire le rôle de responsable à un étudiant
     * 
     * @param int $responsableId ID du responsable
     * @return bool Succès de l'opération
     */
    public function retirerResponsable($responsableId) {
        // Récupérer l'ID de l'étudiant avant de supprimer l'entrée
        $sql = "SELECT id_etudiant FROM responsableclub WHERE id_responsable = :responsable_id";
        $result = $this->single($sql, ['responsable_id' => $responsableId]);
        
        if ($result) {
            $etudiantId = $result['id_etudiant'];
            
            // Supprimer l'entrée de la table responsableclub
            $sql = "DELETE FROM responsableclub WHERE id_responsable = :responsable_id";
            $success = $this->execute($sql, ['responsable_id' => $responsableId]);
            
            return $success;
        }
        
        return false;
    }
}