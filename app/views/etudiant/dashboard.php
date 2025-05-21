<div class="container py-4">
    <!-- Message de succès -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>    <!-- Entête de page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Tableau de bord étudiant</h1>
        
        <?php if (isset($_SESSION['is_future_responsable']) && $_SESSION['is_future_responsable']): ?>
            <span class="badge bg-primary fs-6 px-3 py-2">
                <i class="fas fa-star me-1"></i> Futur responsable de club
            </span>
        <?php endif; ?>
    </div>
    
    <!-- Alerte pour profil incomplet -->
    <?php if (isset($profileComplete) && !$profileComplete): ?>
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
        <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Profil incomplet!</h4>
        <p>Votre profil est incomplet. Pour pouvoir rejoindre des clubs ou participer à des activités, vous devez compléter votre profil.</p>
        <hr>
        <p class="mb-0">Rendez-vous sur <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/profil" class="alert-link">votre page de profil</a> pour ajouter les informations manquantes (filière, niveau, numéro étudiant).</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
    <?php endif; ?>

    <!-- Section principales actions -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Clubs disponibles</h5>
                    <p class="card-text">Découvrez les clubs de notre établissement et rejoignez ceux qui vous intéressent.</p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/clubs" class="btn btn-primary">
                        <i class="fas fa-users me-1"></i> Voir les clubs
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Mes clubs</h5>
                    <p class="card-text">Consultez les clubs dont vous êtes membre et accédez à leurs activités.</p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/mesClubs" class="btn btn-primary">
                        <i class="fas fa-user-check me-1"></i> Mes adhésions
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Activités à venir</h5>
                    <p class="card-text">Découvrez les prochaines activités organisées par les clubs et inscrivez-vous.</p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/activites" class="btn btn-primary">
                        <i class="fas fa-calendar-alt me-1"></i> Voir les activités
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Section blogs et annonces -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="mb-0">Dernières actualités des clubs</h3>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        Pas encore d'actualités disponibles. Les blogs et annonces des clubs que vous rejoindrez apparaîtront ici.
                    </p>
                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/blogs" class="btn btn-outline-primary">
                        <i class="fas fa-newspaper me-1"></i> Consulter tous les blogs
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Section informations pour futurs responsables -->
    <?php if (isset($_SESSION['is_future_responsable']) && $_SESSION['is_future_responsable']): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Information importante pour les futurs responsables de club</h3>
                </div>
                <div class="card-body">
                    <p class="fs-5">Votre compte a bien été créé en tant que futur responsable de club.</p>
                    <p>Un administrateur vous assignera prochainement à un club en tant que responsable. 
                       En attendant, vous avez accès aux fonctionnalités standard d'un compte étudiant.</p>
                    <p>Lorsque vous aurez été assigné à un club, des options supplémentaires apparaîtront dans votre interface.</p>
                </div>
            </div>
        </div>
    </div>    <?php endif; ?>

</div>
