<?php
// Définir le contenu
?>

<div class="container py-4">    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Supervision des Clubs</h1>
        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Succès!</strong> L'opération a été effectuée avec succès.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Erreur!</strong> <?php echo urldecode($_GET['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Liste des clubs</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($clubs) && !empty($clubs)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Logo</th>
                                        <th>Nom</th>
                                        <th>Membres</th>
                                        <th>Activités</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clubs as $club): ?>
                                    <tr>
                                        <td><?php echo $club['id']; ?></td>
                                        <td>
                                            <?php if (!empty($club['Logo_URL'])): ?>
                                                <img src="<?php echo htmlspecialchars($club['Logo_URL']); ?>" alt="Logo du club" width="50" height="50" class="rounded-circle">
                                            <?php else: ?>
                                                <div class="text-center">-</div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $club['nom']; ?></td>
                                        <td><?php echo $club['nombre_membres']; ?></td>
                                        <td><?php echo isset($club['activites_count']) ? $club['activites_count'] : '0'; ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/clubDetails/<?php echo $club['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Détails
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Aucun club n'est disponible pour le moment.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


