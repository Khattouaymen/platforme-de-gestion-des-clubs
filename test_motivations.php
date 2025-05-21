<?php
// Ce script insère des données de test pour la motivation dans la table demandeadhesion
// Exécuter ce script dans le navigateur pour mettre à jour les données
// Ne PAS laisser ce script en production - supprimez-le après utilisation

// Chargement de la configuration de la base de données
require_once 'config/database.php';

// Connexion à la base de données
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    // Récupérer toutes les demandes d'adhésion en attente
    $sql = "SELECT demande_adh_id FROM demandeadhesion WHERE statut = 'en_attente'";
    $stmt = $pdo->query($sql);
    $demandes = $stmt->fetchAll();

    $count = 0;
    if (!empty($demandes)) {
        foreach ($demandes as $demande) {
            // Générer un texte de motivation aléatoire
            $motivation = "Bonjour,\n\nJe souhaite rejoindre votre club car je suis passionné(e) par vos activités.\n\nJ'espère pouvoir apporter ma contribution et participer activement aux événements.\n\nMerci de considérer ma demande.\n\nCordialement.";
            
            // Mettre à jour la motivation dans la base de données
            $updateSql = "UPDATE demandeadhesion SET motivation = :motivation WHERE demande_adh_id = :id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([
                'motivation' => $motivation,
                'id' => $demande['demande_adh_id']
            ]);
            
            $count++;
        }
        
        echo "Succès: $count demandes ont été mises à jour avec une motivation test.";
    } else {
        echo "Aucune demande d'adhésion en attente trouvée.";
    }
    
} catch (PDOException $e) {
    echo "Erreur de base de données: " . $e->getMessage();
}
?>

<p><a href="/responsable/gestionDemandesAdhesion">Retour à la gestion des demandes</a></p>
