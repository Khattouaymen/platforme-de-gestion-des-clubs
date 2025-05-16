<?php
// Définir le contenu
ob_start();
?>

<header class="bg-danger text-white text-center py-4">
    <h1>Bonjour Administrateur</h1>
    <p>Gérez efficacement l'administration des clubs universitaires</p>
</header>

<main class="container my-5">
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-university fa-2x text-danger mb-3"></i>                    <h5 class="card-title">Gestion des Clubs</h5>
                    <p class="card-text">Examiner, supprimer ou modifier les informations des clubs</p>
                    <a href="/admin/clubs" class="btn btn-danger w-100">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-envelope-open-text fa-2x text-danger mb-3"></i>                    <h5 class="card-title">Gestion des Demandes</h5>
                    <p class="card-text">Examiner et traiter les demandes d'activités</p>
                    <a href="/admin/demandes" class="btn btn-danger w-100">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-tools fa-2x text-danger mb-3"></i>                    <h5 class="card-title">Gestion des Ressources</h5>
                    <p class="card-text">Réserver les salles et équipements, suivre leur disponibilité</p>
                    <a href="/admin/ressources" class="btn btn-danger w-100">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-2x text-danger mb-3"></i>                    <h5 class="card-title">Statistiques</h5>
                    <p class="card-text">Consulter le nombre de membres, taux de participation et l'évolution de l'activité</p>
                    <a href="/admin/statistiques" class="btn btn-danger w-100">Accéder</a>
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
