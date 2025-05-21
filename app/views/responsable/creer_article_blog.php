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
                                    <label for="visibility">Visibilité de l'Article</label>
                                    <select class="form-control" id="visibility" name="visibility">
                                        <option value="public">Public (visible par tous)</option>
                                        <option value="club">Membres du club uniquement</option>
                                    </select>
                                    <small class="text-muted">Choisissez qui peut voir cet article</small>
                                </div>
                                <div class="form-group">
                                    <label for="image_url">Lien de l'Image</label>
                                    <input type="url" class="form-control" id="image_url" name="image_url" placeholder="https://exemple.com/image.jpg">
                                    <small class="text-muted">Optionnel. Entrez l'URL d'une image (ex: https://exemple.com/image.jpg)</small>
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
                            </ul>                            <div class="alert alert-info mt-3">
                                <i class="icon fas fa-info-circle"></i>
                                Les articles peuvent être visibles par tous (public) ou uniquement par les membres de votre club.
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

<script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js"></script>
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
      // Afficher un aperçu de l'image à partir de l'URL
    $('#image_url').on('change keyup paste', function() {
        var imageUrl = $(this).val().trim();
        
        if (imageUrl) {
            $('#image-preview').html('<img src="' + imageUrl + '" class="img-fluid" onerror="this.onerror=null; this.src=\'/assets/img/image-not-found.jpg\'; this.alt=\'Image non disponible\';" />');
        } else {
            $('#image-preview').html('<p class="text-muted">Aucune image sélectionnée</p>');
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
        
        // Valider l'URL de l'image si elle n'est pas vide
        var imageUrl = $('#image_url').val().trim();
        if (imageUrl && !isValidURL(imageUrl)) {
            e.preventDefault();
            alert('Veuillez entrer une URL d\'image valide.');
            return false;
        }
        
        return true;
    });
    
    // Fonction pour valider une URL
    function isValidURL(url) {
        var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocole
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domaine
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OU adresse IP
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port et chemin
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // paramètres
            '(\\#[-a-z\\d_]*)?$','i'); // fragment
        return pattern.test(url);
    }
});
</script>
