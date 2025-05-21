<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Clubs disponibles</h1>
        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>

    <div class="row mb-4">
        <?php if (empty($clubs)): ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Aucun club n'est disponible pour le moment.
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
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/club/<?php echo $club['id']; ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                                <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/demandeAdhesion/<?php echo $club['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Rejoindre
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>    </div>
</div>
