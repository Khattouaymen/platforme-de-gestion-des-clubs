<?php
// filepath: c:\Users\Pavilion\sfe\app\views\etudiant\activite_details.php
?>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/dashboard">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/clubs">Clubs</a></li>
            <?php if (isset($activite['club_id'])): ?>
            <li class="breadcrumb-item"><a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/club/<?php echo $activite['club_id']; ?>">
                <?php echo htmlspecialchars($activite['club_nom'] ?? 'Club'); ?>
            </a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page">Détails de l'activité</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4"><?php echo htmlspecialchars($activite['titre']); ?></h1>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <?php if (!empty($activite['Poster_URL'])): ?>
                    <div class="text-center mb-4">
                        <img src="<?php echo htmlspecialchars($activite['Poster_URL']); ?>" alt="Poster de l'activité" class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                    <?php endif; ?>
                    
                    <h5 class="card-title">Description</h5>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($activite['description'])); ?></p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Informations</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-calendar-alt me-2"></i>Date:</span>
                                    <span class="text-muted">
                                        <?php
                                        if (isset($activite['date_debut'])) {
                                            echo date('d/m/Y H:i', strtotime($activite['date_debut']));
                                        } elseif (isset($activite['date_activite'])) {
                                            echo date('d/m/Y', strtotime($activite['date_activite']));
                                        } else {
                                            echo 'Non spécifiée';
                                        }
                                        ?>
                                    </span>
                                </li>
                                
                                <?php if (isset($activite['date_fin'])): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-calendar-check me-2"></i>Fin:</span>
                                    <span class="text-muted"><?php echo date('d/m/Y H:i', strtotime($activite['date_fin'])); ?></span>
                                </li>
                                <?php endif; ?>
                                
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-map-marker-alt me-2"></i>Lieu:</span>
                                    <span class="text-muted"><?php echo !empty($activite['lieu']) ? htmlspecialchars($activite['lieu']) : 'Non spécifié'; ?></span>
                                </li>
                                
                                <?php if (isset($activite['nombre_max'])): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Nombre max. de participants:</span>
                                    <span class="text-muted"><?php echo $activite['nombre_max']; ?></span>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Organisateur</h5>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo htmlspecialchars($activite['club_nom'] ?? 'Club'); ?></h6>
                                    <?php if (isset($activite['club_id'])): ?>
                                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/club/<?php echo $activite['club_id']; ?>" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-external-link-alt me-1"></i>Voir le club
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Participer à cette activité</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($already_registered) && $already_registered): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>Vous êtes inscrit à cette activité!
                    </div>
                    <?php elseif (isset($is_full) && $is_full): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>Cette activité est complète.
                    </div>
                    <?php else: ?>
                    <p>Vous pouvez vous inscrire pour participer à cette activité organisée par le club.</p>
                    <form action="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/inscrireActivite" method="post">
                        <input type="hidden" name="activite_id" value="<?php echo $activite['activite_id']; ?>">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>S'inscrire
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (isset($participants) && !empty($participants)): ?>
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Participants (<?php echo count($participants); ?>)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($participants as $participant): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($participant['prenom'] . ' ' . $participant['nom']); ?>
                            <?php if (isset($participant['present']) && $participant['present']): ?>
                            <span class="badge bg-success">Présent</span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>        </div>
    </div>
</div>
