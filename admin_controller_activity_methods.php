/**
 * Approuver une demande d'activité
 * 
 * @param int $id ID de la demande
 * @return void
 */
public function approveActivite($id = null) {
    // Vérifier si l'ID est valide
    if ($id === null) {
        $this->redirect('/admin/demandes');
        return;
    }
    
    // Mettre à jour le statut de la demande à "approuvee"
    $data = [
        'statut' => 'approuvee'
    ];
    
    $success = $this->demandeActiviteModel->updateStatut($id, $data);
    
    if ($success) {
        $this->redirect('/admin/demandes?success=La+demande+d%27activité+a+été+approuvée');
    } else {
        $this->redirect('/admin/demandes?error=Une+erreur+est+survenue+lors+de+l%27approbation+de+la+demande+d%27activité');
    }
}

/**
 * Rejeter une demande d'activité
 * 
 * @param int $id ID de la demande
 * @return void
 */
public function rejectActivite($id = null) {
    // Vérifier si l'ID est valide
    if ($id === null) {
        $this->redirect('/admin/demandes');
        return;
    }
    
    // Récupérer le commentaire de rejet s'il existe
    $commentaire = $_POST['commentaire'] ?? '';
    
    // Mettre à jour le statut de la demande à "refusee" et ajouter le commentaire
    $data = [
        'statut' => 'refusee',
        'commentaire' => $commentaire
    ];
    
    $success = $this->demandeActiviteModel->updateStatut($id, $data);
    
    if ($success) {
        $this->redirect('/admin/demandes?success=La+demande+d%27activité+a+été+rejetée');
    } else {
        $this->redirect('/admin/demandes?error=Une+erreur+est+survenue+lors+du+rejet+de+la+demande+d%27activité');
    }
}
