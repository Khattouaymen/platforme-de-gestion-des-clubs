<?php
// filepath: c:\Users\Pavilion\sfe\app\views\etudiant\activites.php
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Activités</h1>
        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>

    <!-- Système d'onglets -->
    <ul class="nav nav-tabs mb-4" id="activitiesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="disponibles-tab" data-bs-toggle="tab" data-bs-target="#disponibles" type="button" role="tab" aria-controls="disponibles" aria-selected="true">
                <i class="fas fa-calendar-alt me-2"></i>Disponibles 
                <span class="badge bg-primary rounded-pill ms-1"><?php echo count($activitesDisponibles); ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="inscrites-tab" data-bs-toggle="tab" data-bs-target="#inscrites" type="button" role="tab" aria-controls="inscrites" aria-selected="false">
                <i class="fas fa-user-check me-2"></i>Inscrit 
                <span class="badge bg-success rounded-pill ms-1"><?php echo count($activitesInscrites); ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="terminees-tab" data-bs-toggle="tab" data-bs-target="#terminees" type="button" role="tab" aria-controls="terminees" aria-selected="false">
                <i class="fas fa-history me-2"></i>Terminées 
                <span class="badge bg-secondary rounded-pill ms-1"><?php echo count($activitesTerminees); ?></span>
            </button>
        </li>
    </ul>

    <!-- Contenu des onglets -->
    <div class="tab-content" id="activitiesTabsContent">
        <!-- Onglet Activités Disponibles -->
        <div class="tab-pane fade show active" id="disponibles" role="tabpanel" aria-labelledby="disponibles-tab">
            <?php if (empty($activitesDisponibles)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Aucune activité disponible pour le moment.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($activitesDisponibles as $activite): ?>
                    <div class="col">
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
                                <div class="d-grid gap-2">
                                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/activite/<?php echo $activite['activite_id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-info-circle me-1"></i> Détails & Inscription
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Onglet Activités Inscrites -->
        <div class="tab-pane fade" id="inscrites" role="tabpanel" aria-labelledby="inscrites-tab">
            <?php if (empty($activitesInscrites)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Vous n'êtes inscrit à aucune activité pour le moment.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($activitesInscrites as $activite): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm border-success">
                            <div class="position-absolute" style="top: 10px; right: 10px; z-index: 100;">
                                <span class="badge bg-success rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i> Inscrit
                                </span>
                            </div>
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
                                <div class="d-grid gap-2">
                                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/activite/<?php echo $activite['activite_id']; ?>" class="btn btn-outline-success">
                                        <i class="fas fa-info-circle me-1"></i> Voir les détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Onglet Activités Terminées -->
        <div class="tab-pane fade" id="terminees" role="tabpanel" aria-labelledby="terminees-tab">
            <?php if (empty($activitesTerminees)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Aucune activité terminée.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($activitesTerminees as $activite): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm bg-light">
                            <?php if (isset($inscriptions[$activite['activite_id']])): ?>
                            <div class="position-absolute" style="top: 10px; right: 10px; z-index: 100;">
                                <span class="badge bg-secondary rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i> Participé
                                </span>
                            </div>
                            <?php endif; ?>
                            <div class="position-relative" style="height: 180px; overflow: hidden; opacity: 0.7;">
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
                                <div class="position-absolute w-100 h-100 top-0 start-0 d-flex align-items-center justify-content-center">
                                    <div class="bg-dark bg-opacity-50 px-3 py-2 rounded">
                                        <span class="text-white fw-bold">Terminée</span>
                                    </div>
                                </div>
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
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
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
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                    <span><i class="fas fa-map-marker-alt me-2"></i>Lieu:</span>
                                    <span><?php echo !empty($activite['lieu']) ? htmlspecialchars($activite['lieu']) : 'Non spécifié'; ?></span>
                                </li>
                            </ul>
                            <div class="card-footer bg-transparent border-0">
                                <div class="d-grid gap-2">
                                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/activite/<?php echo $activite['activite_id']; ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-history me-1"></i> Voir l'historique
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Script pour maintenir l'onglet actif après un rechargement de page -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer l'onglet actif depuis le stockage local, s'il existe
    const activeTab = localStorage.getItem('activeActivitiesTab');
    
    if (activeTab) {
        // Activer l'onglet sauvegardé
        const tabToActivate = document.querySelector(`#activitiesTabs button[data-bs-target="${activeTab}"]`);
        if (tabToActivate) {
            const tab = new bootstrap.Tab(tabToActivate);
            tab.show();
        }
    }
    
    // Sauvegarder l'onglet actif lorsqu'un onglet est cliqué
    const tabs = document.querySelectorAll('#activitiesTabs button');
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(event) {
            const target = event.target.getAttribute('data-bs-target');
            localStorage.setItem('activeActivitiesTab', target);
        });
    });
});
</script>
