<?php
// Définir le contenu
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Détails du club: <?php echo $club['nom']; ?></h1>
        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/clubs" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Informations du club -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informations générales</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-3">
                    <?php if (!empty($club['Logo_URL'])): ?>
                        <img src="<?php echo isset($asset) ? $asset($club['Logo_URL']) : $club['Logo_URL']; ?>" alt="Logo du club" class="img-fluid rounded" style="max-height: 200px;">
                    <?php else: ?>
                        <div class="bg-light rounded p-4 d-flex align-items-center justify-content-center" style="height: 200px;">
                            <span class="text-muted">Pas de logo</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <table class="table">
                        <tr>
                            <th style="width: 30%;">Nom du club</th>
                            <td><?php echo $club['nom']; ?></td>
                        </tr>
                        <tr>
                            <th>Nombre de membres</th>
                            <td><?php echo $club['nombre_membres']; ?></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><?php echo $club['description']; ?></td>
                        </tr>
                        <tr>
                            <th>Date de création</th>
                            <td><?php echo isset($club['date_creation']) ? date('d/m/Y', strtotime($club['date_creation'])) : 'Non spécifiée'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des membres -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Membres du club</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($membres) && !empty($membres)): ?>
                            <?php foreach ($membres as $membre): ?>
                                <tr>
                                    <td><?php echo $membre['id_etudiant']; ?></td>
                                    <td><?php echo $membre['nom']; ?></td>
                                    <td><?php echo $membre['prenom']; ?></td>
                                    <td><?php echo $membre['email']; ?></td>
                                    <td>
                                        <?php 
                                            if (isset($membre['role'])) {
                                                if ($membre['role'] == 'president') {
                                                    echo '<span class="badge bg-danger">Président</span>';
                                                } elseif ($membre['role'] == 'secretaire') {
                                                    echo '<span class="badge bg-warning">Secrétaire</span>';
                                                } elseif ($membre['role'] == 'tresorier') {
                                                    echo '<span class="badge bg-info">Trésorier</span>';
                                                } else {
                                                    echo '<span class="badge bg-secondary">Membre</span>';
                                                }
                                            } else {
                                                echo '<span class="badge bg-secondary">Membre</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Ce club n'a pas encore de membres</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Activités du club -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Activités du club</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($activites) && !empty($activites)): ?>
                            <?php foreach ($activites as $activite): ?>
                                <tr>
                                    <td><?php echo $activite['activite_id']; ?></td>
                                    <td><?php echo $activite['titre']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($activite['date_activite'])); ?></td>
                                    <td><?php echo $activite['lieu']; ?></td>
                                    <td>
                                        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/activite/<?php echo $activite['activite_id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Voir détails
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Ce club n'a pas encore organisé d'activités</td>
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
$title = 'Détails du club: ' . $club['nom'];
require APP_PATH . '/views/layouts/main.php';
?>
