<?php
// Cette vue utilise le layout principal (main.php) qui contient déjà le header et la navigation
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <h2><?php echo $data['title']; ?></h2>
            <hr>

            <?php if (empty($data['articles'])): ?>
                <p class="text-center">Aucun article de blog disponible pour le moment.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($data['articles'] as $article): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">                                <?php if (!empty($article['image_url'])): ?>
                                    <img src="<?php echo url('public/' . htmlspecialchars($article['image_url'])); ?>" class="card-img-top" alt="Image de l'article" style="max-height: 200px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($article['titre']); ?></h5>
                                    <p class="card-text flex-grow-1"><?php echo nl2br(htmlspecialchars(substr($article['contenu'], 0, 150))); ?>...</p>
                                    <p class="card-text"><small class="text-muted">Publié le <?php echo date('d/m/Y', strtotime($article['date_creation'])); ?></small></p>
                                    <a href="<?php echo url('etudiant/blogArticle/' . $article['id']); ?>" class="btn btn-primary mt-auto">Lire la suite</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
// Footer déjà inclus dans le layout principal (main.php)
?>
