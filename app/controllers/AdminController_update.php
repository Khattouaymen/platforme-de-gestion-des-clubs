// Enhanced AdminController.php with fixed adhésion handling
<?php
// Only the relevant part of the controller is provided here for reference
// This should replace the existing acceptDemandeAdhesion method in AdminController.php

    /**
     * Accepter une demande d'adhésion
     * 
     * @param int $id ID de la demande
     * @return void
     */
    public function acceptDemandeAdhesion($id = null) {
        // Vérifier si l'ID est valide
        if ($id === null) {
            $this->redirect('/admin/demandes');
            return;
        }
        
        $logPath = 'c:\\Users\\Pavilion\\sfe\\admin_debug.log';
        file_put_contents($logPath, "Admin: Acceptation demande d'adhésion ID: $id @ " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        
        // Accepter la demande et ajouter l'étudiant au club
        $success = $this->demandeAdhesionModel->accepterEtAjouterMembre($id);
        file_put_contents($logPath, "Résultat acceptation: " . ($success ? "succès" : "échec") . "\n", FILE_APPEND);
        
        if ($success) {
            $this->redirect('/admin/demandes?success=1');
        } else {
            $this->redirect('/admin/demandes?error=Une+erreur+est+survenue+lors+de+l%27acceptation+de+la+demande');
        }
    }
