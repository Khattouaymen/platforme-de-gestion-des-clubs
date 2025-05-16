<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\gestion_presence.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gestion des Feuilles de Présence</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item active">Feuilles de Présence</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Activités Approuvées</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" id="search-activity" class="form-control float-right" placeholder="Rechercher">
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
                                            <a href="/responsable/presenceActivite/<?= $activite['id'] ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-clipboard-check"></i> Gérer Présence
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Statistiques de Présence</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if (!empty($activites)): ?>
                                    <div class="col-md-6">
                                        <div class="chart-responsive">
                                            <canvas id="presenceChart" height="300"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="chart-legend clearfix">
                                            <li><i class="far fa-circle text-success"></i> Présents</li>
                                            <li><i class="far fa-circle text-warning"></i> Absents</li>
                                            <li><i class="far fa-circle text-info"></i> Non vérifiés</li>
                                        </ul>
                                        <div class="alert alert-light">
                                            <p><i class="fas fa-info-circle"></i> Cliquez sur une activité pour gérer sa feuille de présence.</p>
                                            <p><i class="fas fa-exclamation-circle"></i> Rappel: Il est important de faire l'appel pour chaque activité.</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col-12 text-center">
                                        <p>Aucune donnée disponible. Les statistiques apparaîtront lorsque vous aurez des activités approuvées.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Filtrer les activités lors de la recherche
    $("#search-activity").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    <?php if (!empty($activites)): ?>
    // Initialiser le graphique si des activités existent
    var presenceData = {
        labels: [
            <?php foreach ($activites as $activite): ?>
                '<?= substr($activite['titre'], 0, 15) . (strlen($activite['titre']) > 15 ? '...' : '') ?>',
            <?php endforeach; ?>
        ],
        datasets: [
            {
                label: 'Présents',
                data: [
                    <?php foreach ($activites as $activite): ?>
                        <?= isset($activite['presents']) ? $activite['presents'] : 0 ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: 'rgba(40, 167, 69, 0.6)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            },
            {
                label: 'Absents',
                data: [
                    <?php foreach ($activites as $activite): ?>
                        <?= isset($activite['absents']) ? $activite['absents'] : 0 ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: 'rgba(255, 193, 7, 0.6)',
                borderColor: 'rgba(255, 193, 7, 1)',
                borderWidth: 1
            },
            {
                label: 'Non vérifiés',
                data: [
                    <?php foreach ($activites as $activite): ?>
                        <?= isset($activite['non_verifies']) ? $activite['non_verifies'] : 0 ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: 'rgba(23, 162, 184, 0.6)',
                borderColor: 'rgba(23, 162, 184, 1)',
                borderWidth: 1
            }
        ]
    };
    
    var presenceCtx = document.getElementById('presenceChart').getContext('2d');
    var presenceChart = new Chart(presenceCtx, {
        type: 'bar',
        data: presenceData,
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            }
        }
    });
    <?php endif; ?>
});
</script>
