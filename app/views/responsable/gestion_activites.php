<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\gestion_activites.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gestion des Activités</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item active">Gestion des Activités</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success">
                    La demande d'activité a été créée avec succès et est en attente d'approbation.
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Demandes d'Activités</h3>
                            <div class="card-tools">
                                <a href="/responsable/creerDemandeActivite" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Nouvelle Demande
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titre</th>
                                        <th>Date Début</th>
                                        <th>Date Fin</th>
                                        <th>Lieu</th>
                                        <th>Statut</th>
                                        <th>Date Création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($demandes)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Aucune demande d'activité pour le moment</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($demandes as $demande): ?>
                                            <tr>
                                                <td><?= $demande['id'] ?></td>
                                                <td><?= $demande['titre'] ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($demande['date_debut'])) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($demande['date_fin'])) ?></td>
                                                <td><?= $demande['lieu'] ?></td>
                                                <td>
                                                    <?php if ($demande['statut'] == 'approuvee'): ?>
                                                        <span class="badge badge-success">Approuvée</span>
                                                    <?php elseif ($demande['statut'] == 'refusee'): ?>
                                                        <span class="badge badge-danger">Refusée</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">En attente</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($demande['date_creation'])) ?></td>
                                                <td>
                                                    <button class="btn btn-xs btn-info view-btn" data-toggle="modal" data-target="#viewModal" 
                                                            data-id="<?= $demande['id'] ?>"
                                                            data-titre="<?= htmlspecialchars($demande['titre']) ?>"
                                                            data-description="<?= htmlspecialchars($demande['description']) ?>"
                                                            data-dateDebut="<?= $demande['date_debut'] ?>"
                                                            data-dateFin="<?= $demande['date_fin'] ?>"
                                                            data-lieu="<?= htmlspecialchars($demande['lieu']) ?>"
                                                            data-statut="<?= $demande['statut'] ?>"
                                                            data-commentaire="<?= htmlspecialchars($demande['commentaire'] ?? '') ?>">
                                                        <i class="fas fa-eye"></i>
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
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Activités Approuvées</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titre</th>
                                        <th>Date Début</th>
                                        <th>Date Fin</th>
                                        <th>Lieu</th>
                                        <th>Participants</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($activites)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Aucune activité approuvée pour le moment</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($activites as $activite): ?>
                                            <tr>
                                                <td><?= $activite['id'] ?></td>
                                                <td><?= $activite['titre'] ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($activite['date_debut'])) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($activite['date_fin'])) ?></td>
                                                <td><?= $activite['lieu'] ?></td>
                                                <td>
                                                    <?php if (isset($activite['nb_participants'])): ?>
                                                        <?= $activite['nb_participants'] ?>
                                                    <?php else: ?>
                                                        0
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="/responsable/presenceActivite/<?= $activite['id'] ?>" class="btn btn-xs btn-success">
                                                        <i class="fas fa-clipboard-check"></i> Présence
                                                    </a>
                                                    <button class="btn btn-xs btn-info view-activite-btn" data-toggle="modal" data-target="#viewActiviteModal" 
                                                            data-id="<?= $activite['id'] ?>"
                                                            data-titre="<?= htmlspecialchars($activite['titre']) ?>"
                                                            data-description="<?= htmlspecialchars($activite['description']) ?>"
                                                            data-dateDebut="<?= $activite['date_debut'] ?>"
                                                            data-dateFin="<?= $activite['date_fin'] ?>"
                                                            data-lieu="<?= htmlspecialchars($activite['lieu']) ?>">
                                                        <i class="fas fa-eye"></i>
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
            </div>
        </div>
    </section>
</div>

<!-- Modal d'affichage des détails de la demande d'activité -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Détails de la Demande d'Activité</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Titre</label>
                    <p id="modal-titre" class="form-control-static"></p>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <p id="modal-description" class="form-control-static"></p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date de Début</label>
                            <p id="modal-date-debut" class="form-control-static"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date de Fin</label>
                            <p id="modal-date-fin" class="form-control-static"></p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Lieu</label>
                    <p id="modal-lieu" class="form-control-static"></p>
                </div>
                <div class="form-group">
                    <label>Statut</label>
                    <p id="modal-statut" class="form-control-static"></p>
                </div>
                <div class="form-group" id="commentaire-container">
                    <label>Commentaire</label>
                    <p id="modal-commentaire" class="form-control-static"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'affichage des détails de l'activité -->
<div class="modal fade" id="viewActiviteModal" tabindex="-1" role="dialog" aria-labelledby="viewActiviteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewActiviteModalLabel">Détails de l'Activité</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Titre</label>
                    <p id="modal-activite-titre" class="form-control-static"></p>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <p id="modal-activite-description" class="form-control-static"></p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date de Début</label>
                            <p id="modal-activite-date-debut" class="form-control-static"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date de Fin</label>
                            <p id="modal-activite-date-fin" class="form-control-static"></p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Lieu</label>
                    <p id="modal-activite-lieu" class="form-control-static"></p>
                </div>
            </div>
            <div class="modal-footer">
                <a id="modal-activite-presence-link" href="#" class="btn btn-success">
                    <i class="fas fa-clipboard-check"></i> Gérer les Présences
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Afficher les détails de la demande d'activité
    $('.view-btn').click(function() {
        var id = $(this).data('id');
        var titre = $(this).data('titre');
        var description = $(this).data('description');
        var dateDebut = new Date($(this).data('datedebut'));
        var dateFin = new Date($(this).data('datefin'));
        var lieu = $(this).data('lieu');
        var statut = $(this).data('statut');
        var commentaire = $(this).data('commentaire');
        
        $('#modal-titre').text(titre);
        $('#modal-description').text(description);
        $('#modal-date-debut').text(dateDebut.toLocaleString('fr-FR'));
        $('#modal-date-fin').text(dateFin.toLocaleString('fr-FR'));
        $('#modal-lieu').text(lieu);
        
        if (statut === 'approuvee') {
            $('#modal-statut').html('<span class="badge badge-success">Approuvée</span>');
        } else if (statut === 'refusee') {
            $('#modal-statut').html('<span class="badge badge-danger">Refusée</span>');
        } else {
            $('#modal-statut').html('<span class="badge badge-warning">En attente</span>');
        }
        
        if (commentaire) {
            $('#modal-commentaire').text(commentaire);
            $('#commentaire-container').show();
        } else {
            $('#commentaire-container').hide();
        }
    });
    
    // Afficher les détails de l'activité
    $('.view-activite-btn').click(function() {
        var id = $(this).data('id');
        var titre = $(this).data('titre');
        var description = $(this).data('description');
        var dateDebut = new Date($(this).data('datedebut'));
        var dateFin = new Date($(this).data('datefin'));
        var lieu = $(this).data('lieu');
        
        $('#modal-activite-titre').text(titre);
        $('#modal-activite-description').text(description);
        $('#modal-activite-date-debut').text(dateDebut.toLocaleString('fr-FR'));
        $('#modal-activite-date-fin').text(dateFin.toLocaleString('fr-FR'));
        $('#modal-activite-lieu').text(lieu);
        $('#modal-activite-presence-link').attr('href', '/responsable/presenceActivite/' + id);
    });
});
</script>
