<?php
// Définir le contenu
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Statistiques</h1>
        <div>
            <button type="button" class="btn btn-outline-primary me-2" id="btnPrint">
                <i class="fas fa-print"></i> Imprimer
            </button>
            <button type="button" class="btn btn-outline-success" id="btnExport">
                <i class="fas fa-file-excel"></i> Exporter
            </button>
        </div>
    </div>

    <!-- Cartes récapitulatives -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Nombre de clubs</h6>
                            <h2 class="mt-2 mb-0"><?php echo count($clubs); ?></h2>
                        </div>
                        <i class="fas fa-university fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Nombre d'étudiants</h6>
                            <h2 class="mt-2 mb-0"><?php echo count($etudiants); ?></h2>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Activités en cours</h6>
                            <h2 class="mt-2 mb-0">5</h2>
                        </div>
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Demandes en attente</h6>
                            <h2 class="mt-2 mb-0">3</h2>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Graphique de distribution des membres par club -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Distribution des membres par club</h5>
                </div>
                <div class="card-body">
                    <canvas id="clubsChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Graphique d'évolution des activités -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Évolution des activités</h5>
                </div>
                <div class="card-body">
                    <canvas id="activitiesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tableau des clubs les plus actifs -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Clubs les plus actifs</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Club</th>
                                    <th>Nombre d'activités</th>
                                    <th>Membres actifs</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($clubs, 0, 5) as $index => $club): ?>
                                <tr>
                                    <td><?php echo $club['nom']; ?></td>
                                    <td><?php echo rand(2, 10); ?></td>
                                    <td><?php echo $club['nombre_membres']; ?></td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo rand(60, 95); ?>%" aria-valuenow="<?php echo rand(60, 95); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Répartition étudiants par niveau -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Répartition des étudiants par année</h5>
                </div>
                <div class="card-body">
                    <canvas id="studentsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclusion de Chart.js pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour les graphiques
    const clubs = <?php echo json_encode(array_column($clubs, 'nom')); ?>;
    const membresCount = <?php echo json_encode(array_column($clubs, 'nombre_membres')); ?>;
    
    // Graphique de distribution des membres par club
    const clubsCtx = document.getElementById('clubsChart').getContext('2d');
    new Chart(clubsCtx, {
        type: 'bar',
        data: {
            labels: clubs,
            datasets: [{
                label: 'Nombre de membres',
                data: membresCount,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Graphique d'évolution des activités
    const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    const activitiesCtx = document.getElementById('activitiesChart').getContext('2d');
    new Chart(activitiesCtx, {
        type: 'line',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Nombre d\'activités',
                data: [4, 6, 8, 7, 9, 5, 3, 2, 5, 8, 10, 12],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Graphique de répartition des étudiants par année
    const studentsCtx = document.getElementById('studentsChart').getContext('2d');
    new Chart(studentsCtx, {
        type: 'pie',
        data: {
            labels: ['1ère année', '2ème année', '3ème année', '4ème année', '5ème année'],
            datasets: [{
                label: 'Étudiants',
                data: [30, 25, 20, 15, 10],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
    
    // Fonctionnalité d'impression
    document.getElementById('btnPrint').addEventListener('click', function() {
        window.print();
    });
      // Fonctionnalité d'export (simulation)
    document.getElementById('btnExport').addEventListener('click', function() {
        alert('Fonctionnalité d\'export en cours de développement. Les données seraient exportées au format Excel.');
    });
});
</script>
