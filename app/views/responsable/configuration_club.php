<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\configuration_club.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Configuration du Club</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item active">Configuration du Club</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success">
                    Les informations du club ont été mises à jour avec succès.
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Informations Générales</h3>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nom">Nom du Club</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?= $club['nom'] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5" required><?= $club['description'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="logo" name="logo">
                                            <label class="custom-file-label" for="logo">Choisir un fichier</label>
                                        </div>
                                    </div>
                                    <?php if (!empty($club['logo'])): ?>
                                        <div class="mt-2">
                                            <img src="<?= $club['logo'] ?>" alt="Logo actuel" class="img-thumbnail" style="max-height: 100px;">
                                            <p class="small">Logo actuel</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Aperçu</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="club-preview">
                                    <?php if (!empty($club['logo'])): ?>
                                        <img src="<?= $club['logo'] ?>" alt="Logo du club" class="img-circle elevation-2" style="max-height: 150px;">
                                    <?php else: ?>
                                        <div class="club-no-logo">
                                            <i class="fas fa-users fa-5x"></i>
                                        </div>
                                    <?php endif; ?>
                                    <h3 id="preview-nom"><?= $club['nom'] ?></h3>
                                    <p id="preview-description"><?= $club['description'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Mise à jour en direct de l'aperçu
    $('#nom').on('input', function() {
        $('#preview-nom').text($(this).val());
    });
    
    $('#description').on('input', function() {
        $('#preview-description').text($(this).val());
    });
    
    // Afficher le nom du fichier sélectionné
    $('#logo').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
        
        // Aperçu de l'image
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.club-preview img').attr('src', e.target.result);
                $('.club-no-logo').hide();
                $('.club-preview img').show();
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
});
</script>

<style>
.club-preview {
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
}
.club-no-logo {
    margin: 20px 0;
    color: #aaa;
}
</style>
