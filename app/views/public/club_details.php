<?php require_once APP_PATH . '/views/layouts/main.php'; ?>

<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/club">Clubs</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($club['nom']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Informations principales du club -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <?php if (!empty($club['Logo_URL'])): ?>
                                <img src="<?php echo htmlspecialchars($club['Logo_URL']); ?>" 
                                     alt="Logo de <?php echo htmlspecialchars($club['nom']); ?>" 
                                     class="img-fluid rounded-circle mb-3" 
                                     style="max-width: 150px; max-height: 150px;">
                            <?php else: ?>
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                     style="width: 150px; height: 150px; font-size: 3rem;">
                                    <?php echo strtoupper(substr($club['nom'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9">
                            <h1 class="card-title mb-3"><?php echo htmlspecialchars($club['nom']); ?></h1>
                            <p class="card-text text-muted mb-3"><?php echo nl2br(htmlspecialchars($club['description'])); ?></p>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <h5 class="text-primary"><?php echo $nombreMembres; ?></h5>
                                    <small class="text-muted">Membres</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-primary"><?php echo count($activites); ?></h5>
                                    <small class="text-muted">Activités</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Responsable du club -->
            <?php if ($responsable): ?>
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i>Responsable du Club
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-user"></i>
                        </div>                        <div>
                            <h6 class="mb-1">
                                <?php 
                                if (isset($responsable['prenom']) && isset($responsable['nom'])) {
                                    echo htmlspecialchars($responsable['prenom'] . ' ' . $responsable['nom']); 
                                } else {
                                    echo 'Responsable non défini';
                                }
                                ?>
                            </h6>
                            <small class="text-muted">
                                <?php 
                                if (isset($responsable['email'])) {
                                    echo htmlspecialchars($responsable['email']); 
                                } else {
                                    echo 'Email non disponible';
                                }
                                ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Activités du club -->
            <?php if (!empty($activites)): ?>
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Activités du Club
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($activites as $activite): ?>
                        <div class="col-md-6 mb-3">                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo htmlspecialchars($activite['titre']); ?></h6>
                                    <p class="card-text small text-muted">
                                        <?php echo substr(htmlspecialchars($activite['description']), 0, 100); ?>
                                        <?php if (strlen($activite['description']) > 100): ?>...<?php endif; ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($activite['date_activite'])); ?>
                                        </small>
                                        <a href="/activite/<?php echo $activite['activite_id']; ?>" class="btn btn-sm btn-outline-primary">
                                            Voir détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card shadow-sm mt-4">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune activité planifiée</h5>
                    <p class="text-muted">Ce club n'a pas encore d'activités prévues.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Call to action -->
            <div class="card shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Rejoignez ce club !</h5>
                    <p class="card-text">Connectez-vous pour rejoindre <?php echo htmlspecialchars($club['nom']); ?> et participer à toutes leurs activités.</p>
                    <a href="/auth/login" class="btn btn-light btn-block">
                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                    </a>
                    <div class="mt-2">
                        <small>
                            <a href="/auth/register" class="text-light">Pas encore de compte ? S'inscrire</a>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Navigation rapide -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Navigation rapide</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="/" class="list-group-item list-group-item-action">
                        <i class="fas fa-home me-2"></i>Retour à l'accueil
                    </a>
                    <a href="/club" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Tous les clubs
                    </a>
                    <a href="/activite" class="list-group-item list-group-item-action">
                        <i class="fas fa-calendar me-2"></i>Toutes les activités
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #007bff !important;
}

.card {
    transition: all 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 25px 0 rgba(0,0,0,.1);
}
</style>
