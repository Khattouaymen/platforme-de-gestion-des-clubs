<?php
// Charger la configuration de la base de données
require_once 'config/database.php';

try {
    // Créer la connexion
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la colonne etudiant_id existe déjà
    $checkColSql = "SHOW COLUMNS FROM inscription_token LIKE 'etudiant_id'";
    $checkColStmt = $conn->query($checkColSql);
    
    if ($checkColStmt->rowCount() == 0) {
        // Ajouter la colonne etudiant_id
        $sql = "ALTER TABLE inscription_token ADD COLUMN etudiant_id INT NULL DEFAULT NULL AFTER date_utilisation";
        $conn->exec($sql);
        echo "Colonne etudiant_id ajoutée à la table inscription_token.\n";
    } else {
        echo "La colonne etudiant_id existe déjà dans la table inscription_token.\n";
    }
    
} catch(PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
