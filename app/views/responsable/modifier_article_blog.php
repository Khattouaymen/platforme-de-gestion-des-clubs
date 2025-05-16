<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\modifier_article_blog.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Modifier un Article de Blog</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="/responsable/gestionBlog">Gestion du Blog</a></li>
                        <li class="breadcrumb-item active">Modifier un Article</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Modification d'Article</h3>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="titre">Titre de l'Article</label>
                                    <input type="text" class="form-control" id="titre" name="titre" value="<?= $article['titre'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="contenu">Contenu</label>
                                    <textarea class="form-control" id="contenu" name="contenu" rows="12" required><?= $article['contenu'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image">Image de Couverture</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                            <label class="custom-file-label" for="image">Choisir une nouvelle image</label>
                                        </div>
                                    </div>
                                    <small class="text-muted">Optionnel. Laissez vide pour conserver l'image actuelle.</small>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Mettre à Jour</button>
                                <a href="/responsable/gestionBlog" class="btn btn-default">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informations</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Créé le:</strong> <?= date('d/m/Y', strtotime($article['date_creation'])) ?></p>
                            <?php if (!empty($article['date_modification'])): ?>
                                <p><strong>Dernière modification:</strong> <?= date('d/m/Y', strtotime($article['date_modification'])) ?></p>
                            <?php endif; ?>
                            <div class="alert alert-info mt-3">
                                <i class="icon fas fa-info-circle"></i>
                                Les modifications seront immédiatement visibles par tous les membres.
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Image Actuelle</h3>
                        </div>
                        <div class="card-body text-center">
                            <div id="image-preview">
                                <?php if (!empty($article['image'])): ?>
                                    <img src="<?= $article['image'] ?>" class="img-fluid" alt="Image de couverture">
                                <?php else: ?>
                                    <p class="text-muted">Aucune image</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script>
$(document).ready(function() {
    // Initialiser l'éditeur de texte
    CKEDITOR.replace('contenu', {
        toolbarGroups: [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            '/',
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] },
            { name: 'about', groups: [ 'about' ] }
        ],
        removeButtons: 'Save,NewPage,Preview,Print,Templates,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Language,BidiRtl,BidiLtr,Flash,Smiley,PageBreak,Iframe,About,ShowBlocks',
        height: 300,
        removePlugins: 'elementspath',
        resize_enabled: false
    });
    
    // Afficher le nom du fichier image sélectionné
    $('#image').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').html(fileName);
        
        // Aperçu de la nouvelle image
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#image-preview').html('<img src="' + e.target.result + '" class="img-fluid" />');
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Validation du formulaire
    $('form').submit(function(e) {
        // Vérifier que le contenu n'est pas vide
        if (CKEDITOR.instances.contenu.getData().trim() === '') {
            e.preventDefault();
            alert('Le contenu de l\'article ne peut pas être vide.');
            return false;
        }
        
        // Vérifier la taille de l'image
        var imageInput = document.getElementById('image');
        if (imageInput.files.length > 0) {
            if (imageInput.files[0].size > 2 * 1024 * 1024) { // 2MB
                e.preventDefault();
                alert('La taille de l\'image ne doit pas dépasser 2MB.');
                return false;
            }
        }
        
        return true;
    });
});
</script>
