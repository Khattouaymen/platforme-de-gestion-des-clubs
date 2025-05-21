<style>
    /* Style pour le ruban "Membre" */
    .ribbon {
        width: 150px;
        height: 150px;
        overflow: hidden;
        position: absolute;
        z-index: 1;
    }
    .ribbon::before,
    .ribbon::after {
        position: absolute;
        z-index: -1;
        content: '';
        display: block;
        border: 5px solid #2980b9;
    }    .ribbon span {
        position: absolute;
        display: block;
        width: 225px;
        padding: 8px 0;
        background-color: #28a745;
        box-shadow: 0 5px 10px rgba(0,0,0,.1);
        color: #fff;
        font: 700 14px/1 'Lato', sans-serif;
        text-shadow: 0 1px 1px rgba(0,0,0,.2);
        text-transform: uppercase;
        text-align: center;
    }
    
    .ribbon span.bg-warning {
        background-color: #ffc107 !important;
    }
    
    /* Top right*/
    .ribbon-top-right {
        top: -10px;
        right: -10px;
    }
    .ribbon-top-right::before,
    .ribbon-top-right::after {
        border-top-color: transparent;
        border-right-color: transparent;
    }
    .ribbon-top-right::before {
        top: 0;
        left: 0;
    }
    .ribbon-top-right::after {
        bottom: 0;
        right: 0;
    }
    .ribbon-top-right span {
        left: -45px;
        top: 30px;
        transform: rotate(45deg);
    }
</style>

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
        <?php else: ?>            <?php foreach ($clubs as $club): ?>
                <?php 
                    $estMembre = in_array($club['id'], $mesClubsIds);
                    $demandeStatut = isset($demandesParClub[$club['id']]) ? $demandesParClub[$club['id']] : null;
                    $aDemandeEnAttente = ($demandeStatut == 'en_attente');
                    $aDemandeRefusee = ($demandeStatut == 'refusee');
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm 
                        <?php if ($estMembre): ?> border border-success <?php endif; ?>
                        <?php if ($aDemandeEnAttente): ?> border border-warning <?php endif; ?>
                        <?php if ($aDemandeRefusee): ?> border border-danger <?php endif; ?>
                    ">
                        <?php if ($estMembre): ?>
                            <div class="ribbon ribbon-top-right"><span>Membre</span></div>
                        <?php elseif ($aDemandeEnAttente): ?>
                            <div class="ribbon ribbon-top-right"><span class="bg-warning text-dark">En attente</span></div>
                        <?php endif; ?>
                        <?php if (!empty($club['Logo_URL'])): ?>
                            <img src="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/public/assets/images/clubs/<?php echo htmlspecialchars($club['Logo_URL']); ?>" class="card-img-top" alt="Logo de <?php echo htmlspecialchars($club['nom']); ?>" style="height: 180px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo htmlspecialchars($club['nom']); ?>
                                <?php if ($estMembre): ?>
                                    <span class="badge bg-success ms-2">Membre</span>
                                <?php elseif ($aDemandeEnAttente): ?>
                                    <span class="badge bg-warning text-dark ms-2">Demande en attente</span>
                                <?php elseif ($aDemandeRefusee): ?>
                                    <span class="badge bg-danger ms-2">Demande refusée</span>
                                <?php endif; ?>
                            </h5>
                            <p class="card-text">
                                <?php echo nl2br(htmlspecialchars(substr($club['description'], 0, 150))); ?>
                                <?php if (strlen($club['description']) > 150): ?>...<?php endif; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-info"><?php echo htmlspecialchars($club['nombre_membres']); ?> membres</span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex <?php echo ($estMembre || $aDemandeEnAttente) ? 'justify-content-center' : 'justify-content-between'; ?>">
                                <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/club/<?php echo $club['id']; ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                                <?php if (!$estMembre && !$aDemandeEnAttente): ?>
                                <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/demandeAdhesion/<?php echo $club['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Rejoindre
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>    </div>
</div>
