<?php
// Load database configuration
require_once 'config/database.php';

try {
    // Create connection
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if column exists
    $checkSql = "SHOW COLUMNS FROM etudiant LIKE 'is_future_responsable'";
    $checkStmt = $conn->query($checkSql);
    
    if ($checkStmt->rowCount() == 0) {
        // Execute SQL to add the column
        $sql = "ALTER TABLE etudiant ADD COLUMN is_future_responsable TINYINT(1) NOT NULL DEFAULT 0";
        $conn->exec($sql);
        echo "Successfully added is_future_responsable column to etudiant table.\n";
    } else {
        echo "Column is_future_responsable already exists in etudiant table.\n";
    }
    
    // Update existing users with is_future_responsable flag from inscription_token
    $updateSql = "UPDATE etudiant e
                  JOIN inscription_token t ON t.email = e.email
                  SET e.is_future_responsable = 1
                  WHERE t.type = 'responsable' 
                  AND t.est_utilise = 1
                  AND NOT EXISTS (
                      SELECT 1 FROM responsableclub r WHERE r.id_etudiant = e.id_etudiant
                  )";
    $conn->exec($updateSql);
    echo "Updated existing students with future responsable flag.\n";
    
    echo "Database update completed successfully.\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
