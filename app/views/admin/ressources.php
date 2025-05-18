<?php
// Définir le contenu
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Gestion des Ressources</h1>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addResourceModal">
            <i class="fas fa-plus"></i> Ajouter une ressource
        </button>
    </div>

    <!-- Tableau des ressources -->
    <div class="card shadow-sm">
        <div class="card-body">            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Club</th>
                            <th>Disponibilité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($ressources) && !empty($ressources)): ?>
                            <?php foreach ($ressources as $ressource): ?>
                                <tr>
                                    <td><?php echo $ressource['id_ressource']; ?></td>
                                    <td><?php echo $ressource['nom_ressource']; ?></td>
                                    <td>
                                        <?php 
                                            switch($ressource['type_ressource']) {
                                                case 'materiel':
                                                    echo '<span class="badge bg-primary">Matériel</span>';
                                                    break;
                                                case 'humain':
                                                    echo '<span class="badge bg-success">Humain</span>';
                                                    break;
                                                case 'financier':
                                                    echo '<span class="badge bg-warning">Financier</span>';
                                                    break;
                                                default:
                                                    echo '<span class="badge bg-secondary">Autre</span>';
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo $ressource['quantite']; ?></td>
                                    <td><?php echo $ressource['club_nom'] ?? 'Non assigné'; ?></td>
                                    <td>
                                        <?php if ($ressource['disponibilite'] === 'disponible'): ?>
                                            <span class="badge bg-success">Disponible</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Indisponible</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editRessourceModal" 
                                                data-id="<?php echo $ressource['id_ressource']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteRessourceModal" 
                                                data-id="<?php echo $ressource['id_ressource']; ?>"
                                                data-name="<?php echo $ressource['nom_ressource']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Aucune ressource trouvée</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Ressource -->
<div class="modal fade" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResourceModalLabel">Ajouter une ressource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/ressources/add" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom de la ressource</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type de ressource</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="materiel">Matériel</option>
                            <option value="humain">Humain</option>
                            <option value="financier">Financier</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantite" class="form-label">Quantité</label>
                        <input type="number" class="form-control" id="quantite" name="quantite" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="club_id" class="form-label">Club</label>
                        <select class="form-select" id="club_id" name="club_id">
                            <option value="">Non assigné</option>
                            <?php if (isset($clubs) && !empty($clubs)): ?>
                                <?php foreach ($clubs as $club): ?>
                                    <option value="<?php echo $club['id']; ?>"><?php echo $club['nom']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="disponibilite" class="form-label">Disponibilité</label>
                        <select class="form-select" id="disponibilite" name="disponibilite" required>
                            <option value="disponible">Disponible</option>
                            <option value="indisponible">Indisponible</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier Ressource -->
<div class="modal fade" id="editRessourceModal" tabindex="-1" aria-labelledby="editRessourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRessourceModalLabel">Modifier la ressource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRessourceForm" action="" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nom" class="form-label">Nom de la ressource</label>
                        <input type="text" class="form-control" id="edit_nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Type de ressource</label>
                        <select class="form-select" id="edit_type" name="type" required>
                            <option value="materiel">Matériel</option>
                            <option value="humain">Humain</option>
                            <option value="financier">Financier</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_quantite" class="form-label">Quantité</label>
                        <input type="number" class="form-control" id="edit_quantite" name="quantite" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_club_id" class="form-label">Club</label>
                        <select class="form-select" id="edit_club_id" name="club_id">
                            <option value="">Non assigné</option>
                            <?php if (isset($clubs) && !empty($clubs)): ?>
                                <?php foreach ($clubs as $club): ?>
                                    <option value="<?php echo $club['id']; ?>"><?php echo $club['nom']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_disponibilite" class="form-label">Disponibilité</label>
                        <select class="form-select" id="edit_disponibilite" name="disponibilite" required>
                            <option value="disponible">Disponible</option>
                            <option value="indisponible">Indisponible</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Supprimer Ressource -->
<div class="modal fade" id="deleteRessourceModal" tabindex="-1" aria-labelledby="deleteRessourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRessourceModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la ressource <strong id="deleteRessourceName"></strong> ?</p>
                <p class="text-danger">Cette action ne peut pas être annulée.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" id="confirmDeleteRessourceBtn" class="btn btn-danger">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- Script pour les modals -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pour le modal d'édition
    const editRessourceModal = document.getElementById('editRessourceModal');
    if (editRessourceModal) {
        editRessourceModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const ressourceId = button.getAttribute('data-id');
            
            // Récupérer les données de la ressource via AJAX
            fetch(`<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/ressources/get/${ressourceId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const ressource = data.ressource;
                    document.getElementById('edit_nom').value = ressource.nom_ressource;
                    document.getElementById('edit_type').value = ressource.type_ressource;
                    document.getElementById('edit_quantite').value = ressource.quantite;
                    document.getElementById('edit_club_id').value = ressource.club_id || '';
                    document.getElementById('edit_disponibilite').value = ressource.disponibilite;
                    
                    // Définir l'action du formulaire
                    document.getElementById('editRessourceForm').action = 
                        `<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/ressources/edit/${ressourceId}`;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
    
    // Pour le modal de suppression
    const deleteRessourceModal = document.getElementById('deleteRessourceModal');
    if (deleteRessourceModal) {
        deleteRessourceModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const ressourceId = button.getAttribute('data-id');
            const ressourceName = button.getAttribute('data-name');
            
            document.getElementById('deleteRessourceName').textContent = ressourceName;
            document.getElementById('confirmDeleteRessourceBtn').href = 
                `<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/ressources/delete/${ressourceId}`;
        });
    }
});
</script>




