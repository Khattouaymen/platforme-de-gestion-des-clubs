<?php
// Load database configuration
require_once 'config/database.php';

try {
    // Create connection
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Update the responsableclub table to have auto-increment
    $sql = "ALTER TABLE responsableclub MODIFY COLUMN id_responsable INT NOT NULL AUTO_INCREMENT";
    $conn->exec($sql);
    echo "Successfully updated responsableclub table with auto-increment.\n";
    
    echo "Database update completed successfully.\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
