<?php
// Fichier pour mettre à jour la table demandeactivite
require_once 'config/database.php';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Mise à jour de la table demandeactivite</h2>";
      // Vérifier si les colonnes existent déjà
    $columnsToAdd = [
        'date_debut' => 'DATETIME DEFAULT NULL',
        'date_fin' => 'DATETIME DEFAULT NULL',
        'statut' => "enum('en_attente','approuvee','rejetee') NOT NULL DEFAULT 'en_attente'",
        'date_creation' => 'DATETIME DEFAULT NULL',
        'nombre_max' => 'INT DEFAULT NULL COMMENT \'Nombre maximum de participants\'',
        'Poster_URL' => 'VARCHAR(255) DEFAULT NULL'
    ];
    
    $existingColumns = [];
    $stmt = $pdo->query("SHOW COLUMNS FROM `demandeactivite`");
    while ($column = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existingColumns[] = $column['Field'];
    }
    
    foreach ($columnsToAdd as $columnName => $columnDef) {
        if (!in_array($columnName, $existingColumns)) {
            $sql = "ALTER TABLE `demandeactivite` ADD COLUMN `$columnName` $columnDef";
            $pdo->exec($sql);
            echo "<p>Colonne '$columnName' ajoutée à la table 'demandeactivite'.</p>";
        } else {
            echo "<p>La colonne '$columnName' existe déjà dans la table 'demandeactivite'.</p>";
        }
    }
    
    // Rendre les colonnes non obligatoires
    $columnsToModify = [
        'nom_activite' => 'VARCHAR(100) DEFAULT NULL',
        'date_activite' => 'DATE DEFAULT NULL',
    ];
    
    foreach ($columnsToModify as $columnName => $columnDef) {
        if (in_array($columnName, $existingColumns)) {
            $sql = "ALTER TABLE `demandeactivite` MODIFY COLUMN `$columnName` $columnDef";
            $pdo->exec($sql);
            echo "<p>Colonne '$columnName' modifiée dans la table 'demandeactivite'.</p>";
        }
    }
    
    echo "<p>Mise à jour de la table 'demandeactivite' terminée avec succès.</p>";
    echo "<p><a href='/index.php'>Retour à l'accueil</a></p>";
    
} catch (PDOException $e) {
    echo "<div style='color:red'>";
    echo "<h2>Erreur lors de la mise à jour de la table 'demandeactivite'</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
