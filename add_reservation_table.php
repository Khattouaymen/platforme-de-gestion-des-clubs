<?php
// Fichier pour ajouter la table reservation à la base de données
require_once 'config/database.php';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Mise à jour de la base de données</h2>";    // Création de la table reservation
    $sql = "CREATE TABLE IF NOT EXISTS `reservation` (
      `id_reservation` int(11) NOT NULL AUTO_INCREMENT,
      `ressource_id` int(11) NOT NULL,
      `club_id` int(11) NOT NULL,
      `activite_id` int(11) NOT NULL,
      `date_debut` datetime NOT NULL,
      `date_fin` datetime NOT NULL,
      `statut` enum('en_attente','approuvee','rejetee') NOT NULL DEFAULT 'en_attente',
      `description` text,
      `date_reservation` datetime NOT NULL,
      PRIMARY KEY (`id_reservation`),
      KEY `ressource_id` (`ressource_id`),
      KEY `club_id` (`club_id`),
      KEY `activite_id` (`activite_id`),
      CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`ressource_id`) REFERENCES `ressource` (`id_ressource`) ON DELETE CASCADE,
      CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`) ON DELETE CASCADE,
      CONSTRAINT `reservation_ibfk_3` FOREIGN KEY (`activite_id`) REFERENCES `activite` (`activite_id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $pdo->exec($sql);
    echo "<p>Table 'reservation' créée avec succès.</p>";
    
    // Vérifier si la colonne existe déjà
    $stmt = $pdo->query("SHOW COLUMNS FROM `activite` LIKE 'responsable_notifie'");
    $exists = $stmt->fetchColumn();
    
    if (!$exists) {
        // Ajouter la colonne de notification
        $sql = "ALTER TABLE `activite` ADD COLUMN `responsable_notifie` TINYINT(1) NOT NULL DEFAULT 0";
        $pdo->exec($sql);
        echo "<p>Colonne 'responsable_notifie' ajoutée à la table 'activite'.</p>";
    } else {
        echo "<p>La colonne 'responsable_notifie' existe déjà dans la table 'activite'.</p>";
    }
    
    echo "<p>Mise à jour de la base de données terminée avec succès.</p>";
    echo "<p><a href='/index.php'>Retour à l'accueil</a></p>";
    
} catch (PDOException $e) {
    echo "<div style='color:red'>";
    echo "<h2>Erreur lors de la mise à jour de la base de données</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
