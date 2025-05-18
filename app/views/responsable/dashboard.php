<!-- Tableau de bord du responsable -->
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="mb-4">Tableau de Bord - Responsable de Club</h1>
    </div>
    <div class="col-md-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb float-end">
                <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                <li class="breadcrumb-item active">Tableau de Bord</li>
            </ol>
        </nav>
    </div>
</div>    <!-- Informations du club -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <h3 class="card-title mb-0 d-flex align-items-center">
                        <?php if (!empty($club['logo'])): ?>
                            <img src="<?= $club['logo'] ?>" alt="Logo du club" class="rounded-circle me-2" style="max-height: 50px;">
                        <?php endif; ?>
                        <?= $club['nom'] ?>
                    </h3>
                    <div class="card-tools">
                        <a href="/responsable/configurationClub" class="btn btn-sm btn-primary">
                            <i class="fas fa-cog"></i> Configurer
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p><?= $club['description'] ?></p>
                </div>
            </div>
        </div>
    </div>

        <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="card text-white bg-primary h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="display-6 fw-bold"><?= count($membres) ?></h3>
                            <p class="card-text">Membres</p>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-primary bg-opacity-75 border-0">
                    <a href="/responsable/gestionMembres" class="text-white text-decoration-none d-block">
                        Plus d'infos <i class="fas fa-arrow-circle-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="card text-white bg-success h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="display-6 fw-bold"><?= count($activites) ?></h3>
                            <p class="card-text">Activités</p>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-success bg-opacity-75 border-0">
                    <a href="/responsable/gestionActivites" class="text-white text-decoration-none d-block">
                        Plus d'infos <i class="fas fa-arrow-circle-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="card text-white bg-warning h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="display-6 fw-bold"><?= count($demandesAdhesion) ?></h3>
                            <p class="card-text">Demandes d'Adhésion</p>
                        </div>
                        <i class="fas fa-user-plus fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-warning bg-opacity-75 border-0">
                    <a href="/responsable/gestionDemandesAdhesion" class="text-white text-decoration-none d-block">
                        Plus d'infos <i class="fas fa-arrow-circle-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="card text-white bg-danger h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="display-6 fw-bold"><i class="fas fa-blog"></i></h3>
                            <p class="card-text">Blog du Club</p>
                        </div>
                        <i class="fas fa-rss fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-danger bg-opacity-75 border-0">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <a href="/responsable/gestionBlog" class="small-box-footer">
                            Gérer <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Liste des dernières activités -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dernières Activités</h3>
                            <div class="card-tools">
                                <a href="/responsable/gestionActivites" class="btn btn-sm btn-primary">
                                    Toutes les Activités
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($activites)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Aucune activité pour le moment</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach (array_slice($activites, 0, 5) as $activite): ?>
                                            <tr>
                                                <td><?= $activite['titre'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($activite['date_debut'])) ?></td>
                                                <td>
                                                    <?php if ($activite['statut'] == 'approuvee'): ?>
                                                        <span class="badge badge-success">Approuvée</span>
                                                    <?php elseif ($activite['statut'] == 'refusee'): ?>
                                                        <span class="badge badge-danger">Refusée</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">En attente</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Liste des dernières demandes d'adhésion -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dernières Demandes d'Adhésion</h3>
                            <div class="card-tools">
                                <a href="/responsable/gestionDemandesAdhesion" class="btn btn-sm btn-primary">
                                    Toutes les Demandes
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($demandesAdhesion)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Aucune demande d'adhésion en attente</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach (array_slice($demandesAdhesion, 0, 5) as $demande): ?>
                                            <tr>
                                                <td><?= $demande['nom'] . ' ' . $demande['prenom'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($demande['date_creation'])) ?></td>
                                                <td>
                                                    <button class="btn btn-xs btn-success accept-btn" data-id="<?= $demande['id'] ?>">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-danger reject-btn" data-id="<?= $demande['id'] ?>">
                                                        <i class="fas fa-times"></i>
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

<script>
$(document).ready(function() {
    // Gérer l'acceptation d'une demande d'adhésion
    $('.accept-btn').click(function() {
        var demandeId = $(this).data('id');
        if (confirm('Voulez-vous vraiment accepter cette demande d\'adhésion ?')) {
            $.ajax({
                url: '/responsable/traiterDemandeAdhesion',
                type: 'POST',
                data: {
                    demande_id: demandeId,
                    statut: 'acceptee'
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert('Demande acceptée avec succès');
                        location.reload();
                    } else {
                        alert(result.message || 'Une erreur est survenue');
                    }
                }
            });
        }
    });
    
    // Gérer le refus d'une demande d'adhésion
    $('.reject-btn').click(function() {
        var demandeId = $(this).data('id');
        if (confirm('Voulez-vous vraiment refuser cette demande d\'adhésion ?')) {
            $.ajax({
                url: '/responsable/traiterDemandeAdhesion',
                type: 'POST',
                data: {
                    demande_id: demandeId,
                    statut: 'refusee'
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        alert('Demande refusée avec succès');
                        location.reload();
                    } else {
                        alert(result.message || 'Une erreur est survenue');
                    }
                }
            });
        }
    });
});
</script>
