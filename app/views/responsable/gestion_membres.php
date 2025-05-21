<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\gestion_membres.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gestion des Membres</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item active">Gestion des Membres</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des Membres</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" id="search-membre" class="form-control float-right" placeholder="Rechercher">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Date d'adhésion</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($membres)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Aucun membre pour le moment</td>
                                </tr>                            <?php else: ?>
                                <?php foreach ($membres as $membre): ?>
                                    <tr>
                                        <td><?= $membre['id_membre'] ?? $membre['id_etudiant'] ?? '—' ?></td>
                                        <td><?= $membre['nom'] . ' ' . $membre['prenom'] ?></td>
                                        <td><?= $membre['email'] ?></td>
                                        <td>
                                            <select class="form-control form-control-sm role-select" data-membre-id="<?= $membre['id_membre'] ?? $membre['id_etudiant'] ?? 0 ?>">
                                                <option value="membre" <?= $membre['role'] === 'membre' ? 'selected' : '' ?>>Membre</option>
                                                <option value="secretaire" <?= $membre['role'] === 'secretaire' ? 'selected' : '' ?>>Secrétaire</option>
                                                <option value="tresorier" <?= $membre['role'] === 'tresorier' ? 'selected' : '' ?>>Trésorier</option>
                                                <option value="vice_president" <?= $membre['role'] === 'vice_president' ? 'selected' : '' ?>>Vice-président</option>
                                            </select>
                                        </td>                                        <td><?= isset($membre['date_adhesion']) ? date('d/m/Y', strtotime($membre['date_adhesion'])) : 'N/A' ?></td>
                                        <td>
                                            <button class="btn btn-xs btn-danger delete-membre" data-membre-id="<?= $membre['id_membre'] ?? $membre['id_etudiant'] ?? 0 ?>">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce membre du club ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Filtrer les membres lors de la recherche
    $("#search-membre").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    // Gérer le changement de rôle d'un membre
    $(".role-select").change(function() {
        var membreId = $(this).data("membre-id");
        var nouveauRole = $(this).val();
        
        $.ajax({
            url: '/responsable/modifierRoleMembre',
            type: 'POST',
            data: {
                membre_id: membreId,
                role: nouveauRole
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    toastr.success("Le rôle a été mis à jour avec succès");
                } else {
                    toastr.error(result.message || "Une erreur est survenue lors de la mise à jour du rôle");
                }
            },
            error: function() {
                toastr.error("Une erreur est survenue lors de la communication avec le serveur");
            }
        });
    });
    
    // Gérer la suppression d'un membre
    var membreIdToDelete;
    
    $(".delete-membre").click(function() {
        membreIdToDelete = $(this).data("membre-id");
        $("#confirmDeleteModal").modal('show');
    });
    
    $("#confirmDelete").click(function() {
        $.ajax({
            url: '/responsable/supprimerMembre',
            type: 'POST',
            data: {
                membre_id: membreIdToDelete
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    toastr.success("Le membre a été supprimé avec succès");
                    $("#confirmDeleteModal").modal('hide');
                    // Recharger la page après une courte pause
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(result.message || "Une erreur est survenue lors de la suppression du membre");
                    $("#confirmDeleteModal").modal('hide');
                }
            },
            error: function() {
                toastr.error("Une erreur est survenue lors de la communication avec le serveur");
                $("#confirmDeleteModal").modal('hide');
            }
        });
    });
});
</script>
