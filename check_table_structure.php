<?php
// Script pour vérifier la structure de la table participationactivite

// Définir les constantes d'application
define('APP_PATH', __DIR__ . '/app');

// Charger la configuration
require_once __DIR__ . '/config/database.php';

// Charger les modèles
require_once APP_PATH . '/core/Model.php';

echo "=== Structure de la table participationactivite ===\n";

try {
    // Créer une connexion directe à la base de données
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtenir la structure de la table participationactivite
    $stmt = $pdo->query("DESCRIBE participationactivite");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Colonnes de la table participationactivite :\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
    echo "\n=== Structure de la table membreclub ===\n";
    $stmt = $pdo->query("DESCRIBE membreclub");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Colonnes de la table membreclub :\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Test terminé ===\n";
?>
