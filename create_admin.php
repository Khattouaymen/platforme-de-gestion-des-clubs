<?php
// Script pour créer un administrateur initial
require_once 'config/database.php';

// Information administrateur
$prenom = 'Admin';
$nom = 'Système';
$email = 'admin@example.com';
$password = password_hash('admin123', PASSWORD_DEFAULT); // Mot de passe haché

try {
    // Connexion à la base de données
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si l'admin existe déjà
    $stmt = $pdo->prepare("SELECT id FROM administrateur WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        echo "Un administrateur avec cet email existe déjà.\n";
    } else {
        // Insérer le nouvel administrateur
        $stmt = $pdo->prepare("INSERT INTO administrateur (prenom, nom, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$prenom, $nom, $email, $password]);
        
        $admin_id = $pdo->lastInsertId();
        
        if ($admin_id) {
            echo "Administrateur créé avec succès. ID: " . $admin_id . "\n";
            echo "Email: admin@example.com\n";
            echo "Mot de passe: admin123\n";
        } else {
            echo "Erreur lors de la création de l'administrateur.\n";
        }
    }
} catch (PDOException $e) {
    echo "Erreur de base de données: " . $e->getMessage() . "\n";
}
?>
