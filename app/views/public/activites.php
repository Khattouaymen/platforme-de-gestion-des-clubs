<!-- filepath: c:\Users\Pavilion\sfe\app\views\public\activites.php -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Activités'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo isset($asset) ? $asset('assets/css/style.css') : '/public/assets/css/style.css'; ?>">
</head>

<div class="container-fluid">
    <!-- Navigation simple pour revenir à l'accueil -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-home"></i> Retour à l'accueil
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/login">Connexion</a>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">Toutes les Activités</h1>
                
                <?php if (isset($activites) && !empty($activites)): ?>
                    <div class="row">
                        <?php foreach ($activites as $activite): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <?php if (isset($activite['poster_url']) && !empty($activite['poster_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($activite['poster_url']); ?>" class="card-img-top" alt="Poster de l'activité" style="height: 200px; object-fit: cover;">
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($activite['titre']); ?></h5>
                                        <p class="card-text"><?php echo substr(htmlspecialchars($activite['description'] ?? ''), 0, 120) . '...'; ?></p>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt"></i> 
                                                <?php
                                                if (isset($activite['date_debut'])) {
                                                    echo date('d/m/Y', strtotime($activite['date_debut']));
                                                } elseif (isset($activite['date_activite'])) {
                                                    echo date('d/m/Y', strtotime($activite['date_activite']));
                                                } else {
                                                    echo 'Date à confirmer';
                                                }
                                                ?>
                                            </small>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i> 
                                                <?php echo htmlspecialchars($activite['lieu'] ?? 'Lieu à confirmer'); ?>
                                            </small>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-users"></i> 
                                                <?php echo $activite['nb_participants'] ?? 0; ?> participants
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <a href="/activite/<?php echo $activite['activite_id']; ?>" class="btn btn-primary btn-sm">Voir détails</a>
                                        <span class="float-end text-muted small">
                                            <?php echo htmlspecialchars($activite['club_nom'] ?? 'Club'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Aucune activité disponible pour le moment.
                        </div>
                        <a href="/" class="btn btn-primary">Retour à l'accueil</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Footer simple -->
    <footer class="bg-light mt-5 py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Système de Gestion des Clubs Universitaires</p>
        </div>
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
