<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\dashboard.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tableau de Bord - Responsable de Club</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item active">Tableau de Bord</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Informations du club -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <?php if (!empty($club['logo'])): ?>
                                    <img src="<?= $club['logo'] ?>" alt="Logo du club" class="img-circle mr-2" style="max-height: 50px;">
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
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= count($membres) ?></h3>
                            <p>Membres</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="/responsable/gestionMembres" class="small-box-footer">
                            Plus d'infos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= count($activites) ?></h3>
                            <p>Activités</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="/responsable/gestionActivites" class="small-box-footer">
                            Plus d'infos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= count($demandesAdhesion) ?></h3>
                            <p>Demandes d'Adhésion</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <a href="/responsable/gestionDemandesAdhesion" class="small-box-footer">
                            Plus d'infos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><i class="fas fa-blog"></i></h3>
                            <p>Blog du Club</p>
                        </div>
                        <div class="icon">
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
