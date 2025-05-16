<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\gestion_blog.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gestion du Blog</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item active">Gestion du Blog</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success">
                    L'article a été créé avec succès.
                </div>
            <?php elseif (isset($_GET['success']) && $_GET['success'] == 2): ?>
                <div class="alert alert-success">
                    L'article a été modifié avec succès.
                </div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-12">
                    <a href="/responsable/creerArticleBlog" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouvel Article
                    </a>
                </div>
            </div>

            <div class="row">
                <?php if (empty($articles)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            Aucun article de blog pour le moment. Créez votre premier article !
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($articles as $article): ?>
                        <div class="col-md-4">
                            <div class="card">
                                <?php if (!empty($article['image'])): ?>
                                    <img class="card-img-top" src="<?= $article['image'] ?>" alt="<?= $article['titre'] ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= $article['titre'] ?></h5>
                                    <p class="card-text">
                                        <?= (strlen($article['contenu']) > 150) ? substr($article['contenu'], 0, 150) . '...' : $article['contenu'] ?>
                                    </p>
                                    <div class="text-muted mb-3">
                                        <small>
                                            <i class="far fa-calendar-alt"></i> Publié le <?= date('d/m/Y', strtotime($article['date_creation'])) ?>
                                            <?php if (!empty($article['date_modification'])): ?>
                                                <br><i class="far fa-edit"></i> Modifié le <?= date('d/m/Y', strtotime($article['date_modification'])) ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="/responsable/modifierArticleBlog/<?= $article['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-article" data-id="<?= $article['id'] ?>">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var articleIdToDelete;
    
    // Gestion de la suppression d'un article
    $(".delete-article").click(function() {
        articleIdToDelete = $(this).data("id");
        $("#deleteModal").modal('show');
    });
    
    // Confirmation de suppression
    $("#confirmDelete").click(function() {
        $.ajax({
            url: '/responsable/supprimerArticleBlog',
            type: 'POST',
            data: {
                article_id: articleIdToDelete
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    toastr.success("L'article a été supprimé avec succès");
                    $("#deleteModal").modal('hide');
                    // Recharger la page après une courte pause
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(result.message || "Une erreur est survenue lors de la suppression de l'article");
                    $("#deleteModal").modal('hide');
                }
            },
            error: function() {
                toastr.error("Une erreur est survenue lors de la communication avec le serveur");
                $("#deleteModal").modal('hide');
            }
        });
    });
});
</script>
