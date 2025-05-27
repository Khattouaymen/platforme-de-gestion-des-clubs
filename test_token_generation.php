<?php
// Script de test pour vérifier la génération de tokens

// Définir les constantes d'application
define('APP_PATH', __DIR__ . '/app');

// Charger la configuration
require_once __DIR__ . '/config/database.php';

// Charger les modèles
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/models/AdminModel.php';

echo "=== Test de génération de token ===\n";

try {
    // Créer une instance du modèle Admin
    $adminModel = new AdminModel();
    
    // Générer un token
    $token = bin2hex(random_bytes(32));
    echo "Token généré: $token\n";
    
    // Tenter de sauvegarder le token
    $tokenId = $adminModel->saveResponsableToken($token);
    
    if ($tokenId) {
        echo "✅ Token sauvegardé avec succès! ID: $tokenId\n";
        
        // Vérifier que le token est valide
        $isValid = $adminModel->isTokenValid($token);
        echo "✅ Token valide: " . ($isValid ? 'Oui' : 'Non') . "\n";
    } else {
        echo "❌ Erreur lors de la sauvegarde du token\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Test terminé ===\n";
?>
