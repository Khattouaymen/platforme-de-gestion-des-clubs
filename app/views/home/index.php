<?php
// Définir le contenu
ob_start();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title><?php echo $title ?? 'Gestion des Clubs'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo isset($asset) ? $asset('assets/css/style.css') : '/public/assets/css/style.css'; ?>">
</head>
<!-- Hero Section inspiré de la page d'accueil originale -->
<section class="hero text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Bienvenue dans la plateforme de gestion des clubs universitaires</h1>
        <a href="<?php echo isset($asset) ? $asset('home/login') : '/login'; ?>" class="btn btn-warning btn-connexion mt-4">Connexion</a>
    </div>
</section>

<!-- Section pourquoi -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Pourquoi cette application ?</h2>
        <p class="text-center">Les clubs universitaires sont essentiels pour 
            développer l'esprit d'équipe, la créativité et l'engagement des 
            étudiants. Cette plateforme vise à centraliser la gestion,
            automatiser les processus,
            et faciliter la communication entre l'administration,
            les responsables et les étudiants.</p>
    </div>
</section>

<!-- Clubs passés -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Clubs des années précédentes</h2>
        <div class="row align-items-center">
            <div class="col-md-4 d-flex justify-content-end">
                <img src="<?php echo isset($asset) ? $asset('assets/images/logo_creative.jpg') : 'public/assets/images/logo_creative.jpg'; ?>" alt="logo_creative" class="img-fluid rounded shadow" style="width: 200px;" />
            </div>
            <div class="col-md-6">
                <h2>CLUB creative community</h2>
                <p>Creative Community est un club étudiant dédié à la créativité et aux arts.
                    Il offre un espace d'expression pour les talents en design, photographie,
                    écriture et création de contenu à travers des ateliers et des activités 
                    interactives stimulant l'innovation et la sensibilité artistique.
                </p>
            </div>
        </div>

        <div class="row align-items-center my-4">
            <div class="col-md-6 d-flex justify-content-end">
                <img src="<?php echo isset($asset) ? $asset('assets/images/logo_cyberdune.jpg') : 'public/assets/images/logo_cyberdune.jpg'; ?>" alt="logo_cyberdune" class="img-fluid rounded shadow" style="width: 200px;">
            </div>
            <div class="col-md-6">
                <h2>Club CYBER_DUNE</h2>
                <p>CyberDune est un club étudiant orienté vers le numérique 
                    et les technologies. Il a pour objectif de développer les compétences des étudiants en programmation,
                    cybersécurité, intelligence artificielle et technologies modernes à travers des ateliers,
                    des compétitions et des activités éducatives.
                </p>
            </div>
        </div>

        <div class="row align-items-center mt-4">
            <div class="col-md-4 d-flex justify-content-end">
                <img src="<?php echo isset($asset) ? $asset('assets/images/logo_sportif.jpg') : 'public/assets/images/logo_sportif.jpg'; ?>" alt="logo_sportif" class="img-fluid rounded shadow" style="width: 200px;">
            </div>
            <div class="col-md-6">
                <h2>Club Sportif</h2>
                <p>Le Club Sportif Universitaire est un espace dédié aux passionnés de sport,
                    visant à promouvoir l'activité physique,
                    l'esprit d'équipe et la compétition saine à travers des entraînements réguliers, 
                    des tournois et divers événements sportifs</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Clubs Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Clubs populaires</h2>
        
        <div class="row">
            <?php if (isset($clubs) && !empty($clubs)): ?>
                <?php foreach (array_slice($clubs, 0, 3) as $club): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo $club['Logo_URL']; ?>" class="card-img-top" alt="<?php echo $club['nom']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $club['nom']; ?></h5>
                                <p class="card-text"><?php echo substr($club['description'], 0, 100) . '...'; ?></p>
                                <a href="/club/<?php echo $club['id']; ?>" class="btn btn-sm btn-primary">En savoir plus</a>
                            </div>
                            <div class="card-footer text-muted">
                                <small><?php echo $club['nombre_membres']; ?> membres</small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        Aucun club disponible pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-3">
            <a href="/clubs" class="btn btn-outline-primary">Voir tous les clubs</a>
        </div>
    </div>
</section>

<!-- Upcoming Activities Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Activités à venir</h2>
        
        <div class="row">
            <?php if (isset($activites) && !empty($activites)): ?>
                <?php foreach (array_slice($activites, 0, 4) as $activite): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $activite['titre']; ?></h5>
                                <p class="card-text"><?php echo substr($activite['description'], 0, 150) . '...'; ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-calendar-alt"></i> 
                                        <?php echo date('d/m/Y', strtotime($activite['date_activite'])); ?>
                                    </div>
                                    <div>
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <?php echo $activite['lieu']; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="/activite/<?php echo $activite['activite_id']; ?>" class="btn btn-sm btn-primary">Détails</a>
                                <span class="float-end text-muted">
                                    <?php echo $activite['club_nom']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        Aucune activité disponible pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-3">
            <a href="/activites" class="btn btn-outline-primary">Voir toutes les activités</a>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5 text-center">
    <div class="container">
        <h2 class="mb-3">Rejoignez notre communauté dès aujourd'hui !</h2>
        <p class="lead mb-4">Créez un compte pour pouvoir rejoindre des clubs, participer à des activités et interagir avec d'autres étudiants</p>
        <a href="/register" class="btn btn-primary btn-lg">S'inscrire maintenant</a>
    </div>
</section>

<!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="/assets/js/script.js"></script>


