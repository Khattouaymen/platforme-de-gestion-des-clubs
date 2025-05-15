<?php
// Définir le contenu
ob_start();
?>

<div class="text-center my-5">
    <h1 class="display-1 text-danger">404</h1>
    <p class="lead">Page non trouvée</p>
    <p><?php echo $message ?? "La page que vous recherchez n'existe pas"; ?></p>
    <div class="mt-4">
        <a href="/" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</div>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
