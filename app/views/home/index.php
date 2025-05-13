<?php
// Définir le contenu
ob_start();
?>

<!-- Hero Section -->
<section class="hero-section text-center py-5">
    <div class="container">
        <h1 class="display-4">Bienvenue sur la plateforme de Gestion des Clubs</h1>
        <p class="lead">Découvrez les clubs, participez aux activités et développez vos talents</p>
        <div class="mt-4">
            <a href="/clubs" class="btn btn-primary me-2">Voir les clubs</a>
            <a href="/activites" class="btn btn-outline-primary">Découvrir les activités</a>
        </div>
    </div>
</section>

<!-- Featured Clubs Section -->
<section class="featured-clubs py-5">
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
<section class="upcoming-activities py-5 bg-light">
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
<section class="cta-section py-5 text-center">
    <div class="container">
        <h2 class="mb-3">Rejoignez notre communauté dès aujourd'hui !</h2>
        <p class="lead mb-4">Créez un compte pour pouvoir rejoindre des clubs, participer à des activités et interagir avec d'autres étudiants</p>
        <a href="/register" class="btn btn-primary btn-lg">S'inscrire maintenant</a>
    </div>
</section>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
