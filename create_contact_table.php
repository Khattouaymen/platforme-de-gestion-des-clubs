<?php
require_once 'config/database.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = file_get_contents('create_contact_messages_table.sql');
    $pdo->exec($sql);
    
    echo 'Table contact_messages créée avec succès!' . PHP_EOL;
} catch (PDOException $e) {
    echo 'Erreur: ' . $e->getMessage() . PHP_EOL;
}
?>
