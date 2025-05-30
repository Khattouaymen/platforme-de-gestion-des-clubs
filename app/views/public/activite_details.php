<!-- filepath: c:\Users\Pavilion\sfe\app\views\public\activite_details.php -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Détails de l\'activité'; ?></title>
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
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <?php if (isset($activite['poster_url']) && !empty($activite['poster_url'])): ?>
                    <img src="<?php echo htmlspecialchars($activite['poster_url']); ?>" class="card-img-top" alt="Poster de l'activité" style="max-height: 400px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div class="card-header">
                        <h1 class="card-title mb-0"><?php echo htmlspecialchars($activite['titre']); ?></h1>
                        <div class="text-muted mt-2">
                            <i class="fas fa-users"></i> <?php echo htmlspecialchars($activite['club_nom'] ?? 'Club'); ?>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5><i class="fas fa-calendar-alt text-primary"></i> Date et heure</h5>
                                <?php if (isset($activite['date_debut']) && isset($activite['date_fin'])): ?>
                                    <p>Du <?php echo date('d/m/Y à H:i', strtotime($activite['date_debut'])); ?><br>
                                    au <?php echo date('d/m/Y à H:i', strtotime($activite['date_fin'])); ?></p>
                                <?php elseif (isset($activite['date_activite'])): ?>
                                    <p><?php echo date('d/m/Y', strtotime($activite['date_activite'])); ?></p>
                                <?php else: ?>
                                    <p>Date à confirmer</p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <h5><i class="fas fa-map-marker-alt text-primary"></i> Lieu</h5>
                                <p><?php echo htmlspecialchars($activite['lieu'] ?? 'À confirmer'); ?></p>
                            </div>
                        </div>
                        
                        <?php if (isset($activite['description']) && !empty($activite['description'])): ?>
                        <div class="mb-4">
                            <h5><i class="fas fa-info-circle text-primary"></i> Description</h5>
                            <p><?php echo nl2br(htmlspecialchars($activite['description'])); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fas fa-users text-primary"></i> Participants</h5>
                                <p><?php echo $nombreParticipants; ?> inscrits
                                <?php if (isset($activite['nombre_max']) && $activite['nombre_max'] > 0): ?>
                                    / <?php echo $activite['nombre_max']; ?> maximum
                                <?php endif; ?>
                                </p>
                            </div>
                            
                            <?php if (isset($activite['date_creation'])): ?>
                            <div class="col-md-6">
                                <h5><i class="fas fa-clock text-primary"></i> Créée le</h5>
                                <p><?php echo date('d/m/Y', strtotime($activite['date_creation'])); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-footer text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-sign-in-alt"></i>
                            <strong>Vous voulez participer ?</strong><br>
                            <a href="/login" class="btn btn-primary mt-2">Connectez-vous</a> pour vous inscrire à cette activité !
                        </div>
                    </div>
                </div>
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
