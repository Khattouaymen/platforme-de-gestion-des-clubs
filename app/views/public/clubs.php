<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Liste des Clubs'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo isset($asset) ? $asset('assets/css/style.css') : '/public/assets/css/style.css'; ?>">
</head>
<body>

<div class="container-fluid">
    <!-- Navigation simple pour revenir à l'accueil -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-home"></i> Retour à l'accueil
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/auth/login">Connexion</a>
                <a class="nav-link" href="/club">Tous les clubs</a>
                <a class="nav-link" href="/activite">Activités</a>
            </div>
        </div>
    </nav>

<div class="container mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Clubs</li>
                </ol>
            </nav>
            <h1 class="display-4">Découvrez nos Clubs</h1>
            <p class="lead text-muted">Explorez la diversité de nos clubs étudiants et trouvez celui qui vous correspond.</p>
        </div>
    </div>

    <!-- Call to action -->
    <div class="row mb-5">
        <div class="col">
            <div class="alert alert-info" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Rejoignez la communauté !</h5>
                        <p class="mb-2">Connectez-vous pour rejoindre des clubs, participer aux activités et enrichir votre expérience étudiante.</p>
                        <a href="/auth/login" class="btn btn-primary btn-sm me-2">Se connecter</a>
                        <a href="/auth/register" class="btn btn-outline-primary btn-sm">S'inscrire</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des clubs -->
    <?php if (!empty($clubs)): ?>
    <div class="row">
        <?php foreach ($clubs as $club): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <!-- Logo du club -->
                    <div class="text-center mb-3">
                        <?php if (!empty($club['Logo_URL'])): ?>
                            <img src="<?php echo htmlspecialchars($club['Logo_URL']); ?>" 
                                 alt="Logo de <?php echo htmlspecialchars($club['nom']); ?>" 
                                 class="img-fluid rounded-circle" 
                                 style="max-width: 80px; max-height: 80px;">
                        <?php else: ?>
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 80px; height: 80px; font-size: 1.5rem;">
                                <?php echo strtoupper(substr($club['nom'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Nom du club -->
                    <h5 class="card-title text-center mb-3"><?php echo htmlspecialchars($club['nom']); ?></h5>
                    
                    <!-- Description -->
                    <p class="card-text text-muted flex-grow-1">
                        <?php 
                        $description = htmlspecialchars($club['description']);
                        echo strlen($description) > 120 ? substr($description, 0, 120) . '...' : $description;
                        ?>
                    </p>
                    
                    <!-- Statistiques -->
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <h6 class="text-primary mb-0"><?php echo $club['nombre_membres']; ?></h6>
                            <small class="text-muted">Membres</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-primary mb-0">
                                <?php 
                                // Compter les activités (nous pourrions ajouter cette info au modèle plus tard)
                                echo rand(1, 8); // Placeholder pour l'instant
                                ?>
                            </h6>
                            <small class="text-muted">Activités</small>
                        </div>
                    </div>
                    
                    <!-- Bouton d'action -->
                    <div class="mt-auto">
                        <a href="/club/<?php echo $club['id']; ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-eye me-2"></i>Découvrir le club
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <!-- Aucun club trouvé -->
    <div class="row">
        <div class="col">
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-4"></i>
                <h3 class="text-muted">Aucun club disponible</h3>
                <p class="text-muted">Il n'y a actuellement aucun club enregistré dans le système.</p>
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Section d'information -->
    <div class="row mt-5">
        <div class="col">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-lightbulb me-2"></i>Pourquoi rejoindre un club ?
                    </h5>
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-users text-primary me-2"></i>Créer des liens</h6>
                            <p class="small text-muted">Rencontrez des étudiants qui partagent vos passions et intérêts.</p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-trophy text-warning me-2"></i>Développer des compétences</h6>
                            <p class="small text-muted">Participez à des projets et développez de nouvelles compétences.</p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-calendar text-success me-2"></i>Enrichir votre parcours</h6>
                            <p class="small text-muted">Ajoutez de la valeur à votre expérience universitaire.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mt-4">
        <div class="col text-center">
            <a href="/" class="btn btn-outline-secondary me-2">
                <i class="fas fa-home me-2"></i>Retour à l'accueil
            </a>
            <a href="/activite" class="btn btn-outline-primary">
                <i class="fas fa-calendar me-2"></i>Voir les activités
            </a>
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px 0 rgba(0,0,0,.15);
}

.btn-block {
    width: 100%;
}

.alert {
    border: none;
    border-radius: 10px;
}
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
