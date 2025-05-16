<?php
/**
 * Script de mise à jour de la base de données
 * Ce script va importer le nouveau fichier SQL (gestion_clubs2.0.sql) dans la base de données
 */

// Charger les informations de connexion à la base de données
require_once 'config/database.php';

// Vérifier si le nouveau fichier SQL existe
$sqlFile = __DIR__ . '/gestion_clubs2.0.sql';
if (!file_exists($sqlFile)) {
    die("Erreur : Le fichier gestion_clubs2.0.sql n'existe pas.\n");
}

echo "Tentative de connexion à la base de données...\n";

try {
    // Connexion à MySQL (sans spécifier de base de données)
    $pdo = new PDO('mysql:host=' . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion réussie à MySQL.\n";
    
    // Supprimer la base de données si elle existe déjà
    $pdo->exec("DROP DATABASE IF EXISTS `" . DB_NAME . "`;");
    echo "Base de données existante supprimée.\n";
    
    // Créer une nouvelle base de données
    $pdo->exec("CREATE DATABASE `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    echo "Nouvelle base de données créée.\n";
    
    // Sélectionner la base de données
    $pdo->exec("USE `" . DB_NAME . "`;");
      // Lire le contenu du fichier SQL
    $sql = file_get_contents($sqlFile);
    
    echo "Importation du fichier SQL en cours...\n";
    
    // Séparer les requêtes SQL (diviser par des points-virgules suivis d'un retour à la ligne)
    $queries = preg_split('/;\s*[\r\n]+/', $sql);
    
    // Exécuter chaque requête séparément
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            try {
                $pdo->exec($query);
            } catch (PDOException $e) {
                echo "Erreur lors de l'exécution d'une requête : " . $e->getMessage() . "\n";
                echo "Requête : " . substr($query, 0, 100) . "...\n";
                // Continuer malgré les erreurs
            }
        }
    }
    
    echo "Base de données mise à jour avec succès à partir de gestion_clubs2.0.sql!\n";
    
} catch (PDOException $e) {
    die("Erreur de connexion/importation : " . $e->getMessage() . "\n");
}

// Mettre à jour le fichier database.php pour refléter le changement 
// (optionnel si le nom de la base de données reste le même)
echo "Mise à jour terminée. La nouvelle structure de base de données a été importée.\n";
?>
