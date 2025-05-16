<?php
// Définir le contenu
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Détails de l'activité: <?php echo $activite['titre']; ?></h1>
        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/club/<?php echo $activite['club_id']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au club
        </a>
    </div>

    <!-- Informations de l'activité -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informations générales</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <tr>
                            <th style="width: 30%;">Titre</th>
                            <td><?php echo $activite['titre']; ?></td>
                        </tr>
                        <tr>
                            <th>Club organisateur</th>
                            <td><?php echo $activite['club_nom']; ?></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td><?php echo date('d/m/Y', strtotime($activite['date_activite'])); ?></td>
                        </tr>
                        <tr>
                            <th>Lieu</th>
                            <td><?php echo $activite['lieu']; ?></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><?php echo $activite['description']; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des participants -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste de présence</h5>
            <div>
                <button class="btn btn-sm btn-light" onclick="window.print();">
                    <i class="fas fa-print"></i> Imprimer
                </button>
                <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/activite/<?php echo $activite['activite_id']; ?>/export-pdf" class="btn btn-sm btn-light ms-2">
                    <i class="fas fa-file-pdf"></i> Exporter en PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($participants) && !empty($participants)): ?>
                            <?php foreach ($participants as $participant): ?>
                                <tr>
                                    <td><?php echo $participant['membre_id']; ?></td>
                                    <td><?php echo $participant['nom']; ?></td>
                                    <td><?php echo $participant['prenom']; ?></td>
                                    <td>
                                        <?php 
                                            switch($participant['statut']) {
                                                case 'inscrit':
                                                    echo '<span class="badge bg-warning">Inscrit</span>';
                                                    break;
                                                case 'participe':
                                                    echo '<span class="badge bg-success">Présent</span>';
                                                    break;
                                                case 'absent':
                                                    echo '<span class="badge bg-danger">Absent</span>';
                                                    break;
                                                default:
                                                    echo '<span class="badge bg-secondary">Non défini</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Cette activité n'a pas encore de participants</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Détails de l\'activité: ' . $activite['titre'];
require APP_PATH . '/views/layouts/main.php';
?>
