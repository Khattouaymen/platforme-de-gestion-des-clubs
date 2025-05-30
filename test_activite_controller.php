<?php
// Simple test to check if ActiviteController is working
session_start();
require_once 'app/core/Controller.php';
require_once 'app/models/ActiviteModel.php';
require_once 'app/models/ParticipationActiviteModel.php';
require_once 'app/controllers/ActiviteController.php';

echo "Testing ActiviteController...\n";

try {
    $controller = new ActiviteController();
    echo "ActiviteController created successfully!\n";
    
    // Test getting all activities
    $activiteModel = new ActiviteModel();
    $activites = $activiteModel->getAll();
    echo "Found " . count($activites) . " activities\n";
    
    if (!empty($activites)) {
        $firstActivity = $activites[0];
        echo "First activity: " . $firstActivity['titre'] . " (ID: " . $firstActivity['activite_id'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
