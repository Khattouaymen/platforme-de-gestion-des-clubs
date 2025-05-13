<?php
/**
 * Classe Model - Modèle de base pour tous les modèles
 */
class Model {
    protected $db;
    
    /**
     * Constructeur du modèle
     */
    public function __construct() {
        $this->connectDB();
    }
    
    /**
     * Connexion à la base de données
     */
    private function connectDB() {
        try {
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->db->exec("SET NAMES utf8");
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }
    
    /**
     * Exécute une requête et retourne un seul résultat
     * 
     * @param string $sql Requête SQL
     * @param array $params Paramètres pour la requête préparée
     * @return array|false Résultat unique ou false si aucun résultat
     */
    protected function single($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    /**
     * Exécute une requête et retourne tous les résultats
     * 
     * @param string $sql Requête SQL
     * @param array $params Paramètres pour la requête préparée
     * @return array Résultats
     */
    protected function multiple($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Exécute une requête d'insertion, de mise à jour ou de suppression
     * 
     * @param string $sql Requête SQL
     * @param array $params Paramètres pour la requête préparée
     * @return int Nombre de lignes affectées
     */
    protected function execute($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    /**
     * Retourne le dernier ID inséré
     * 
     * @return string|false Dernier ID inséré ou false
     */
    protected function lastInsertId() {
        return $this->db->lastInsertId();
    }
}
?>
