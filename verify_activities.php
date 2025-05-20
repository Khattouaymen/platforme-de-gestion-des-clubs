<?php
// Fichier pour vérifier que les activités sont correctement accessibles
require_once 'config/database.php';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Vérification des activités</h2>";
    
    // Vérifier la structure de la table activite
    $stmt = $pdo->query("DESCRIBE activite");
    $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Structure de la table activite:</h3>";
    echo "<pre>";
    print_r($structure);
    echo "</pre>";
    
    // Récupérer toutes les activités
    $stmt = $pdo->query("SELECT * FROM activite LIMIT 10");
    $activites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Exemples d'activités:</h3>";
    echo "<pre>";
    print_r($activites);
    echo "</pre>";
    
    // Vérifier les contraintes de clé étrangère
    $stmt = $pdo->query("
        SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE REFERENCED_TABLE_NAME = 'activite'
        AND TABLE_SCHEMA = '" . DB_NAME . "'
    ");
    $constraints = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Contraintes de clé étrangère référençant la table activite:</h3>";
    echo "<pre>";
    print_r($constraints);
    echo "</pre>";
    
    // Vérifier les réservations existantes
    $stmt = $pdo->query("SELECT * FROM reservation LIMIT 10");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Exemples de réservations:</h3>";
    echo "<pre>";
    print_r($reservations);
    echo "</pre>";
    
    echo "<p>Vérification terminée.</p>";
    echo "<p><a href='/index.php'>Retour à l'accueil</a></p>";
    
} catch (PDOException $e) {
    echo "<div style='color:red'>";
    echo "<h2>Erreur lors de la vérification</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
