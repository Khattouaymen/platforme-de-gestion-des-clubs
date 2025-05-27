<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title><?php echo $title ?? 'Gestion des Clubs'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo isset($asset) ? $asset('assets/css/style.css') : '/public/assets/css/style.css'; ?>">
    <!-- Admin CSS -->    <link rel="stylesheet" href="<?php echo isset($asset) ? $asset('assets/css/admin.css') : '/public/assets/css/admin.css'; ?>">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php if (!isset($hideNavbar) || $hideNavbar !== true): ?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark <?php echo isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin' ? 'bg-danger' : 'bg-primary'; ?>">
        <div class="container">
            <a class="navbar-brand" href="/">Gestion des Clubs</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                        <!-- Barre de navigation spécifique pour l'admin -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/statistiques') !== false ? 'active' : ''; ?>" href="/admin/statistiques">Statistiques</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/ressources') !== false ? 'active' : ''; ?>" href="/admin/ressources">Gestion des Ressources</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/demandes') !== false ? 'active' : ''; ?>" href="/admin/demandes">Gestion des Demandes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/clubs') !== false ? 'active' : ''; ?>" href="/admin/clubs">Gestion des Clubs</a>
                        </li>                    <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'responsable'): ?>
                        <!-- Barre de navigation spécifique pour le responsable -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo $_SERVER['REQUEST_URI'] === '/responsable' ? 'active' : ''; ?>" href="/responsable">Tableau de Bord</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/responsable/configuration') !== false ? 'active' : ''; ?>" href="/responsable/configurationClub">Configuration Club</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/responsable/gestion_activites') !== false ? 'active' : ''; ?>" href="/responsable/gestionActivites">Gestion Activités</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/responsable/membres') !== false ? 'active' : ''; ?>" href="/responsable/membres">Membres</a>
                        </li>
                    <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'etudiant'): ?>
                        <!-- Barre de navigation spécifique pour l'étudiant -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo $_SERVER['REQUEST_URI'] === '/etudiant' || $_SERVER['REQUEST_URI'] === '/etudiant/' ? 'active' : ''; ?>" href="/etudiant">
                                <i class="fas fa-home me-1"></i> Accueil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/etudiant/clubs') !== false ? 'active' : ''; ?>" href="/etudiant/clubs">
                                <i class="fas fa-users me-1"></i> Clubs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/etudiant/activites') !== false ? 'active' : ''; ?>" href="/etudiant/activites">
                                <i class="fas fa-calendar-alt me-1"></i> Activités
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/etudiant/blogs') !== false ? 'active' : ''; ?>" href="/etudiant/blogs">
                                <i class="fas fa-newspaper me-1"></i> Blogs
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- Barre de navigation standard pour les autres utilisateurs -->
                        <li class="nav-item">
                            <a class="nav-link" href="/">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/clubs">Clubs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/activites">Activités</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/about">À propos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/contact">Contact</a>
                        </li>
                    <?php endif; ?>
                </ul>                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">                                <?php if ($_SESSION['user_type'] === 'etudiant'): ?>
                                    <li><a class="dropdown-item" href="/etudiant">Tableau de bord</a></li>
                                    <li><a class="dropdown-item" href="/etudiant/profil">Mon profil</a></li>
                                <?php elseif ($_SESSION['user_type'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="/admin">Tableau de bord</a></li>
                                <?php elseif ($_SESSION['user_type'] === 'responsable'): ?>
                                    <li><a class="dropdown-item" href="/responsable">Tableau de bord</a></li>
                                    <li><a class="dropdown-item" href="/responsable/configurationClub">Mon club</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/auth/logout">Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Inscription</a>
                        </li>
                    <?php endif; ?>                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            <?php if (isset($alertSuccess)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $alertSuccess; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($alertError)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $alertError; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
              <!-- Content will be inserted here -->
            <?php echo $content ?? ''; ?>
        </div>
    </main>

    <!-- Footer -->
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="<?php echo isset($asset) ? $asset('assets/js/script.js') : '/public/assets/js/script.js'; ?>"></script>    <!-- Script standard pour Bootstrap -->
    <script>
        // Aucun script personnalisé pour les modals - utilisation de Bootstrap standard
    </script>
</body>
</html>