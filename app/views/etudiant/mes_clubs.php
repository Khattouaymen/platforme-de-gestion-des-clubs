<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Mes clubs</h1>
        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>
    
    <!-- Section des demandes d'adhésion en cours -->
    <?php if (!empty($demandes)): ?>
        <div class="mb-5">
            <h3 class="mb-3">Mes demandes d'adhésion</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Club</th>
                            <th>Date de demande</th>
                            <th>Statut</th>
                            <th>Date de traitement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($demandes as $demande): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($demande['club_nom']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($demande['date_demande'])); ?></td>
                                <td>
                                    <?php if ($demande['statut'] == 'en_attente'): ?>
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    <?php elseif ($demande['statut'] == 'acceptee'): ?>
                                        <span class="badge bg-success">Acceptée</span>
                                    <?php elseif ($demande['statut'] == 'refusee'): ?>
                                        <span class="badge bg-danger">Refusée</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($demande['date_traitement']): ?>
                                        <?php echo date('d/m/Y', strtotime($demande['date_traitement'])); ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
    
    <h3 class="mb-3">Clubs dont je suis membre</h3>
    <div class="row mb-4">
        <?php if (empty($clubs)): ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Vous n'êtes membre d'aucun club pour le moment.
                </div>
                <div class="text-center mt-4">
                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/clubs" class="btn btn-primary">
                        <i class="fas fa-search"></i> Explorer les clubs disponibles
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($clubs as $club): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($club['Logo_URL'])): ?>
                            <img src="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/public/assets/images/clubs/<?php echo htmlspecialchars($club['Logo_URL']); ?>" class="card-img-top" alt="Logo de <?php echo htmlspecialchars($club['nom']); ?>" style="height: 180px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($club['nom']); ?></h5>
                            <p class="card-text">
                                <?php echo nl2br(htmlspecialchars(substr($club['description'], 0, 150))); ?>
                                <?php if (strlen($club['description']) > 150): ?>...<?php endif; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-info"><?php echo htmlspecialchars($club['nombre_membres']); ?> membres</span>
                                <?php if (!empty($club['role'])): ?>
                                    <span class="badge bg-success">Rôle: <?php echo htmlspecialchars($club['role']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-center">
                                <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/club/<?php echo $club['id']; ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i> Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
