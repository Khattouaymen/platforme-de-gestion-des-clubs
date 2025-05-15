<?php
/**
 * Script d'initialisation de la base de données
 * Ce script importera le fichier SQL dans la base de données configurée
 */

// Charger les informations de connexion à la base de données
require_once 'config/database.php';

// Vérifier si le fichier SQL existe
$sqlFile = __DIR__ . '/gestion_clubs.sql';
if (!file_exists($sqlFile)) {
    die("Erreur : Le fichier SQL n'existe pas.\n");
}

echo "Tentative de connexion à la base de données...\n";

try {
    // Connexion à MySQL (sans spécifier de base de données)
    $pdo = new PDO('mysql:host=' . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion établie avec succès.\n";
    
    // Vérifier si la base de données existe, sinon la créer
    $query = "CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
    $pdo->exec($query);
    
    echo "Base de données '" . DB_NAME . "' créée ou existante.\n";
    
    // Sélectionner la base de données
    $pdo->exec("USE `" . DB_NAME . "`;");
    
    echo "Importation du fichier SQL...\n";
    
    // Lire le contenu du fichier SQL
    $sql = file_get_contents($sqlFile);
    
    // Exécuter les requêtes SQL
    $pdo->exec($sql);
    
    echo "Base de données importée avec succès !\n";
    
    // Ajouter un utilisateur administrateur par défaut
    $adminExists = $pdo->query("SELECT COUNT(*) as count FROM administrateur")->fetch();
    
    if ($adminExists['count'] == 0) {
        echo "Création d'un compte administrateur par défaut...\n";
        
        // Mot de passe haché pour 'admin123'
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO administrateur (id, prenom, nom, email, password) VALUES (1, 'Admin', 'Système', 'admin@example.com', :password)");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        
        echo "Compte administrateur créé. Email: admin@example.com, Mot de passe: admin123\n";
    }
    
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage() . "\n");
}

echo "Configuration de la base de données terminée avec succès !\n";
?>
