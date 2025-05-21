<?php
// filepath: c:\Users\Pavilion\sfe\app\views\etudiant\activites.php
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Activités disponibles</h1>
        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>

    <?php if (empty($activites)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Aucune activité n'est disponible pour le moment.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($activites as $activite): ?>            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                        <?php if (!empty($activite['Poster_URL'])): ?>
                            <img src="<?php echo htmlspecialchars($activite['Poster_URL']); ?>" 
                                 class="card-img-top" 
                                 alt="Poster de <?php echo htmlspecialchars($activite['titre']); ?>"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                <i class="fas fa-calendar-alt fa-3x text-secondary"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($activite['titre']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <?php echo htmlspecialchars($activite['club_nom'] ?? 'Club non spécifié'); ?>
                        </h6>
                        <p class="card-text">
                            <?php echo nl2br(htmlspecialchars(substr($activite['description'], 0, 150))); ?>
                            <?php if (strlen($activite['description']) > 150): ?>...<?php endif; ?>
                        </p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar-alt me-2"></i>Date:</span>
                            <span>
                                <?php 
                                    if (isset($activite['date_debut'])) {
                                        echo date('d/m/Y', strtotime($activite['date_debut']));
                                    } elseif (isset($activite['date_activite'])) {
                                        echo date('d/m/Y', strtotime($activite['date_activite']));
                                    } else {
                                        echo 'Non spécifiée';
                                    }
                                ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-map-marker-alt me-2"></i>Lieu:</span>
                            <span><?php echo !empty($activite['lieu']) ? htmlspecialchars($activite['lieu']) : 'Non spécifié'; ?></span>
                        </li>
                        <?php if (!empty($activite['nombre_max'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-users me-2"></i>Max. participants:</span>
                            <span><?php echo htmlspecialchars($activite['nombre_max']); ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/activite/<?php echo $activite['activite_id']; ?>" class="btn btn-primary w-100">
                            <i class="fas fa-info-circle me-1"></i> Détails
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>        </div>
    <?php endif; ?>
</div>
