<?php
// Vue pour afficher les détails d'un article de blog spécifique
require_once APPROOT . '/views/inc/header_etudiant.php';
require_once APPROOT . '/views/inc/navigation_etudiant.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <?php if (isset($data['article']) && $data['article']): ?>
                <article>
                    <h2><?php echo htmlspecialchars($data['article']['titre']); ?></h2>
                    <hr>
                    <p class="text-muted">
                        Publié le <?php echo date('d/m/Y à H:i', strtotime($data['article']['date_creation'])); ?>
                        <?php if (!empty($data['article']['nom_club'])): ?>
                            par le club : <?php echo htmlspecialchars($data['article']['nom_club']); ?>
                        <?php endif; ?>
                    </p>
                    
                    <?php if (!empty($data['article']['image_url'])): ?>
                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($data['article']['image_url']); ?>" class="img-fluid mb-3" alt="Image de l'article" style="max-height: 400px; width: auto;">
                    <?php endif; ?>

                    <div class="article-content">
                        <?php echo nl2br(htmlspecialchars($data['article']['contenu'])); ?>
                    </div>
                </article>

                <hr>
                <a href="<?php echo URLROOT; ?>/etudiant/blogs" class="btn btn-secondary">&laquo; Retour à la liste des articles</a>
            
            <?php else: ?>
                <p class="text-center">L'article demandé n'a pas été trouvé ou n'est pas accessible.</p>
                <div class="text-center">
                    <a href="<?php echo URLROOT; ?>/etudiant/blogs" class="btn btn-primary">Voir tous les articles</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
require_once APPROOT . '/views/inc/footer_etudiant.php'; 
?>
