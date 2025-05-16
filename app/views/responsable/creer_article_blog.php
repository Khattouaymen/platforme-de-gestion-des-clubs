<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\creer_article_blog.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Créer un Article de Blog</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="/responsable/gestionBlog">Gestion du Blog</a></li>
                        <li class="breadcrumb-item active">Créer un Article</li>
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
                            <h3 class="card-title">Nouvel Article de Blog</h3>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="titre">Titre de l'Article</label>
                                    <input type="text" class="form-control" id="titre" name="titre" placeholder="Entrez le titre de l'article" required>
                                </div>
                                <div class="form-group">
                                    <label for="contenu">Contenu</label>
                                    <textarea class="form-control" id="contenu" name="contenu" rows="12" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image">Image de Couverture</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                            <label class="custom-file-label" for="image">Choisir une image</label>
                                        </div>
                                    </div>
                                    <small class="text-muted">Optionnel. Formats recommandés : JPEG, PNG. Taille max : 2MB</small>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Publier l'Article</button>
                                <a href="/responsable/gestionBlog" class="btn btn-default">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Conseils de Rédaction</h3>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li>Utilisez un titre accrocheur</li>
                                <li>Structurez votre contenu avec des sous-titres</li>
                                <li>Ajoutez une image pertinente</li>
                                <li>Rédigez un contenu clair et informatif</li>
                                <li>Relisez votre article avant de le publier</li>
                            </ul>
                            <div class="alert alert-info mt-3">
                                <i class="icon fas fa-info-circle"></i>
                                Les articles de blog seront visibles par tous les membres du club.
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Aperçu de l'Image</h3>
                        </div>
                        <div class="card-body text-center">
                            <div id="image-preview">
                                <p class="text-muted">Aucune image sélectionnée</p>
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
        
        // Aperçu de l'image
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
