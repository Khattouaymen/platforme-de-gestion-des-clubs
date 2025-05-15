<?php
// Définir le contenu
ob_start();
?>

<div class="text-center my-5">
    <h1 class="display-1 text-danger">Erreur</h1>
    <p class="lead"><?php echo $message ?? "Une erreur s'est produite"; ?></p>
    <div class="mt-4">
        <a href="/" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</div>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
