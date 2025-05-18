<?php
// Définir le contenu
ob_start();
?>

<header class="bg-danger text-white text-center py-4">
    <h1>Bonjour Administrateur</h1>
    <p>Gérez efficacement l'administration des clubs universitaires</p>
</header>

<main class="container my-5">
    <!-- Section principale -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="border-bottom pb-2 mb-4">Tableau de Bord Principal</h2>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-home fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Accueil Admin</h5>
                    <p class="card-text">Tableau de bord principal de l'administration</p>
                    <a href="/admin" class="btn btn-danger w-100">Accueil</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Statistiques</h5>
                    <p class="card-text">Consulter les statistiques générales du système</p>
                    <a href="/admin/statistiques" class="btn btn-danger w-100">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-user-graduate fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Étudiants</h5>
                    <p class="card-text">Gérer les comptes étudiants du système</p>
                    <a href="/admin/etudiants" class="btn btn-danger w-100">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-list-alt fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Supervision</h5>
                    <p class="card-text">Supervision générale des clubs</p>
                    <a href="/admin/supervisionClubs" class="btn btn-danger w-100">Accéder</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion des clubs -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="border-bottom pb-2 mb-4">Gestion des Clubs</h2>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-university fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Liste des Clubs</h5>
                    <p class="card-text">Consulter la liste complète des clubs</p>
                    <a href="/admin/clubs" class="btn btn-danger w-100">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-plus-circle fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Ajouter un Club</h5>
                    <p class="card-text">Créer un nouveau club dans le système</p>
                    <a href="/admin/addClub" class="btn btn-danger w-100">Créer</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-user-plus fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Lien Responsable</h5>
                    <p class="card-text">Générer un lien d'inscription pour responsable</p>
                    <a href="/admin/responsableLink" class="btn btn-danger w-100">Générer</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-user-shield fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Gestion Responsables</h5>
                    <p class="card-text">Assigner les étudiants comme responsables de clubs</p>
                    <a href="/admin/gestionResponsables" class="btn btn-danger w-100">Gérer</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion des demandes -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="border-bottom pb-2 mb-4">Gestion des Demandes</h2>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-envelope-open-text fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Toutes les Demandes</h5>
                    <p class="card-text">Examiner et traiter toutes les demandes</p>
                    <a href="/admin/demandes" class="btn btn-danger w-100">Accéder</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion des ressources -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="border-bottom pb-2 mb-4">Gestion des Ressources</h2>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-tools fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Liste des Ressources</h5>
                    <p class="card-text">Gérer les ressources disponibles</p>
                    <a href="/admin/ressources" class="btn btn-danger w-100">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-plus-circle fa-2x text-danger mb-3"></i>
                    <h5 class="card-title">Ajouter une Ressource</h5>
                    <p class="card-text">Créer une nouvelle ressource dans le système</p>
                    <a href="/admin/addRessource" class="btn btn-danger w-100">Créer</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Liens rapides -->
    <div class="row">
        <div class="col-12">
            <h2 class="border-bottom pb-2 mb-4">Menu Rapide</h2>
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Accès Rapide à Toutes les Fonctionnalités</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Tableau de Bord et Administration</h6>
                            <ul class="list-unstyled">
                                <li><a href="/admin" class="text-decoration-none"><i class="fas fa-home me-2"></i>Tableau de Bord</a></li>
                                <li><a href="/admin/statistiques" class="text-decoration-none"><i class="fas fa-chart-line me-2"></i>Statistiques</a></li>
                                <li><a href="/admin/etudiants" class="text-decoration-none"><i class="fas fa-user-graduate me-2"></i>Gestion des Étudiants</a></li>
                                <li><a href="/admin/supervisionClubs" class="text-decoration-none"><i class="fas fa-list-alt me-2"></i>Supervision des Clubs</a></li>
                            </ul>
                            
                            <h6 class="fw-bold mt-4">Gestion des Clubs</h6>
                            <ul class="list-unstyled">
                                <li><a href="/admin/clubs" class="text-decoration-none"><i class="fas fa-university me-2"></i>Liste des Clubs</a></li>
                                <li><a href="/admin/addClub" class="text-decoration-none"><i class="fas fa-plus-circle me-2"></i>Ajouter un Club</a></li>
                                <li><a href="/admin/responsableLink" class="text-decoration-none"><i class="fas fa-user-plus me-2"></i>Lien d'Inscription Responsable</a></li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="fw-bold">Gestion des Demandes</h6>
                            <ul class="list-unstyled">
                                <li><a href="/admin/demandes" class="text-decoration-none"><i class="fas fa-envelope-open-text me-2"></i>Toutes les Demandes</a></li>
                            </ul>
                            
                            <h6 class="fw-bold mt-4">Gestion des Ressources</h6>
                            <ul class="list-unstyled">
                                <li><a href="/admin/ressources" class="text-decoration-none"><i class="fas fa-tools me-2"></i>Liste des Ressources</a></li>
                                <li><a href="/admin/addRessource" class="text-decoration-none"><i class="fas fa-plus-circle me-2"></i>Ajouter une Ressource</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
$title = 'Tableau de Bord Administrateur';
require APP_PATH . '/views/layouts/main.php';
?>
