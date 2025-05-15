<?php
// Définir le contenu
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Gestion des Clubs</h1>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addClubModal">
            <i class="fas fa-plus"></i> Ajouter un club
        </button>
    </div>

    <?php if (isset($alertSuccess)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $alertSuccess; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($alertError)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $alertError; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tableau des clubs -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Date de création</th>
                            <th>Responsable</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>                        <?php if (isset($clubs) && !empty($clubs)): ?>
                            <?php foreach ($clubs as $club): ?>
                                <tr>
                                    <td><?php echo $club['id']; ?></td>
                                    <td><?php echo $club['nom']; ?></td>
                                    <td><?php echo substr($club['description'], 0, 50) . (strlen($club['description']) > 50 ? '...' : ''); ?></td>
                                    <td><?php echo isset($club['date_creation']) ? date('d/m/Y', strtotime($club['date_creation'])) : 'N/A'; ?></td>
                                    <td><?php echo $club['responsable_nom'] ?? 'Non assigné'; ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewClubModal" 
                                                data-id="<?php echo $club['id']; ?>"
                                                data-action="view">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editClubModal" 
                                                data-id="<?php echo $club['id']; ?>"
                                                data-action="edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteClubModal" 
                                                data-id="<?php echo $club['id']; ?>"
                                                data-name="<?php echo $club['nom']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Aucun club trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Club -->
<div class="modal fade" id="addClubModal" tabindex="-1" aria-labelledby="addClubModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">            <div class="modal-header">
                <h5 class="modal-title" id="addClubModalLabel">Ajouter un club</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>            <form action="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/clubs/add" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du club</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>                    <div class="mb-3">
                        <label for="logo" class="form-label">URL du logo</label>
                        <input type="url" class="form-control" id="logo" name="logo" placeholder="https://example.com/logo.jpg">
                        <small class="form-text text-muted">Entrez l'URL complète de l'image du logo</small>
                    </div>
                    <div class="form-text mb-3">
                        Logos disponibles dans le projet:
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <button type="button" class="btn btn-sm btn-outline-primary logo-option" data-logo-url="/assets/images/logo_creative.jpg">Creative</button>
                            <button type="button" class="btn btn-sm btn-outline-primary logo-option" data-logo-url="/assets/images/logo_cyberdune.jpg">Cyber Dune</button>
                            <button type="button" class="btn btn-sm btn-outline-primary logo-option" data-logo-url="/assets/images/logo_sportif.jpg">Sportif</button>
                        </div>
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

<!-- Modal Voir Club -->
<div class="modal fade" id="viewClubModal" tabindex="-1" aria-labelledby="viewClubModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewClubModalLabel">Détails du club</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="clubDetails">
                    <div class="text-center mb-3">
                        <img id="clubLogo" src="" alt="Logo du club" class="img-fluid mb-2" style="max-height: 100px;">
                        <h4 id="clubName"></h4>
                    </div>
                    <p class="fw-bold">Description:</p>
                    <p id="clubDescription" class="mb-3"></p>
                    <p><strong>Membres:</strong> <span id="clubMembers"></span></p>
                    <p><strong>Date de création:</strong> <span id="clubDate"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier Club -->
<div class="modal fade" id="editClubModal" tabindex="-1" aria-labelledby="editClubModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClubModalLabel">Modifier le club</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>            <form id="editClubForm" action="" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nom" class="form-label">Nom du club</label>
                        <input type="text" class="form-control" id="edit_nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>                    <div class="mb-3">
                        <label for="edit_logo" class="form-label">URL du logo</label>
                        <input type="url" class="form-control" id="edit_logo" name="logo">
                        <small class="form-text text-muted">Laissez vide pour conserver le logo actuel.</small>
                    </div>
                    <div class="form-text mb-3">
                        Logos disponibles dans le projet:
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <button type="button" class="btn btn-sm btn-outline-primary logo-option-edit" data-logo-url="/assets/images/logo_creative.jpg">Creative</button>
                            <button type="button" class="btn btn-sm btn-outline-primary logo-option-edit" data-logo-url="/assets/images/logo_cyberdune.jpg">Cyber Dune</button>
                            <button type="button" class="btn btn-sm btn-outline-primary logo-option-edit" data-logo-url="/assets/images/logo_sportif.jpg">Sportif</button>
                        </div>
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

<!-- Modal Supprimer Club -->
<div class="modal fade" id="deleteClubModal" tabindex="-1" aria-labelledby="deleteClubModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteClubModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le club <strong id="deleteClubName"></strong>?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- Script pour les actions modales -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des boutons de sélection rapide des logos
    const logoButtons = document.querySelectorAll('.logo-option');
    if (logoButtons.length > 0) {
        logoButtons.forEach(button => {
            button.addEventListener('click', function() {
                const logoUrl = this.getAttribute('data-logo-url');
                document.getElementById('logo').value = logoUrl;
                
                // Mettre en évidence le bouton sélectionné
                logoButtons.forEach(btn => btn.classList.remove('btn-primary'));
                logoButtons.forEach(btn => btn.classList.add('btn-outline-primary'));
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');
            });
        });
    }
    
    // Gestion des boutons de sélection rapide des logos pour l'édition
    const logoEditButtons = document.querySelectorAll('.logo-option-edit');
    if (logoEditButtons.length > 0) {
        logoEditButtons.forEach(button => {
            button.addEventListener('click', function() {
                const logoUrl = this.getAttribute('data-logo-url');
                document.getElementById('edit_logo').value = logoUrl;
                
                // Mettre en évidence le bouton sélectionné
                logoEditButtons.forEach(btn => btn.classList.remove('btn-primary'));
                logoEditButtons.forEach(btn => btn.classList.add('btn-outline-primary'));
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');
            });
        });
    }
    
    // Pour le modal d'édition
    const editClubModal = document.getElementById('editClubModal');
    if (editClubModal) {
        editClubModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const clubId = button.getAttribute('data-id');
            
            // Récupérer les données du club via AJAX
            fetch(`<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/clubs/get/${clubId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {                if (data.success) {
                    const club = data.club;
                    document.getElementById('edit_nom').value = club.nom;
                    document.getElementById('edit_description').value = club.description;
                    document.getElementById('edit_logo').value = club.Logo_URL || '';
                    
                    // Mettre en surbrillance le bouton du logo correspondant si applicable
                    const logoButtons = document.querySelectorAll('.logo-option-edit');
                    logoButtons.forEach(button => {
                        const logoUrl = button.getAttribute('data-logo-url');
                        if (logoUrl === club.Logo_URL) {
                            button.classList.remove('btn-outline-primary');
                            button.classList.add('btn-primary');
                        } else {
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-outline-primary');
                        }
                    });
                    
                    // Définir l'action du formulaire
                    document.getElementById('editClubForm').action = 
                        `<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/clubs/edit/${clubId}`;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
    
    // Pour le modal de suppression
    const deleteClubModal = document.getElementById('deleteClubModal');
    if (deleteClubModal) {
        deleteClubModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const clubId = button.getAttribute('data-id');
            const clubName = button.getAttribute('data-name');
            
            document.getElementById('deleteClubName').textContent = clubName;
            document.getElementById('confirmDeleteBtn').href = 
                `<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/clubs/delete/${clubId}`;
        });
    }
    
    // Pour le modal de visualisation
    const viewClubModal = document.getElementById('viewClubModal');
    if (viewClubModal) {
        viewClubModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const clubId = button.getAttribute('data-id');
            
            // Récupérer les données du club via AJAX
            fetch(`<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/clubs/get/${clubId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const club = data.club;
                    document.getElementById('clubName').textContent = club.nom;
                    document.getElementById('clubDescription').textContent = club.description;
                    document.getElementById('clubMembers').textContent = club.nombre_membres || '0';
                    document.getElementById('clubDate').textContent = club.date_creation ? 
                        new Date(club.date_creation).toLocaleDateString('fr-FR') : 'N/A';
                    
                    if (club.Logo_URL) {
                        document.getElementById('clubLogo').src = 
                            `<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>${club.Logo_URL}`;
                        document.getElementById('clubLogo').style.display = 'block';
                    } else {
                        document.getElementById('clubLogo').style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
</script>

<?php
$content = ob_get_clean();
$title = 'Gestion des Clubs - Administration';
require APP_PATH . '/views/layouts/main.php';
?>
