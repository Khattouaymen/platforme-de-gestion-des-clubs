<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\creer_demande_activite.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Créer une Demande d'Activité</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="/responsable/gestionActivites">Gestion des Activités</a></li>
                        <li class="breadcrumb-item active">Créer une Demande</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Nouvelle Demande d'Activité</h3>
                        </div>
                        <form method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="titre">Titre de l'Activité</label>
                                    <input type="text" class="form-control" id="titre" name="titre" placeholder="Entrez le titre de l'activité" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="Décrivez l'activité en détail" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_debut">Date et Heure de Début</label>
                                            <input type="datetime-local" class="form-control" id="date_debut" name="date_debut" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_fin">Date et Heure de Fin</label>
                                            <input type="datetime-local" class="form-control" id="date_fin" name="date_fin" required>
                                        </div>
                                    </div>
                                </div>                                <div class="form-group">
                                    <label for="lieu">Lieu</label>
                                    <input type="text" class="form-control" id="lieu" name="lieu" placeholder="Entrez le lieu de l'activité" required>
                                </div>
                                <div class="form-group">
                                    <label for="nombre_max">Nombre maximum de participants</label>
                                    <input type="number" class="form-control" id="nombre_max" name="nombre_max" placeholder="Entrez le nombre maximum de participants" min="1">
                                </div>
                                <div class="form-group">
                                    <label for="poster_url">URL du poster/affiche de l'activité</label>
                                    <input type="url" class="form-control" id="poster_url" name="poster_url" placeholder="https://exemple.com/image.jpg">
                                    <small class="form-text text-muted">Entrez l'URL complète d'une image qui servira d'affiche pour l'activité.</small>
                                </div>
                                <div class="alert alert-info">
                                    <i class="icon fas fa-info-circle"></i>
                                    Une fois soumise, votre demande sera examinée par l'administrateur pour approbation.
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Soumettre</button>
                                <a href="/responsable/gestionActivites" class="btn btn-default">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Définir la date minimale pour les champs de date/heure (aujourd'hui)
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();
    var hh = String(today.getHours()).padStart(2, '0');
    var min = String(today.getMinutes()).padStart(2, '0');
    
    var todayStr = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + min;
    
    document.getElementById('date_debut').setAttribute('min', todayStr);
    document.getElementById('date_fin').setAttribute('min', todayStr);
    
    // Validation du formulaire
    $('form').submit(function(e) {
        var dateDebut = new Date($('#date_debut').val());
        var dateFin = new Date($('#date_fin').val());
        
        if (dateFin <= dateDebut) {
            e.preventDefault();
            alert('La date de fin doit être postérieure à la date de début.');
            return false;
        }
        
        return true;
    });
    
    // Mettre à jour la date minimale de fin lorsque la date de début change
    $('#date_debut').change(function() {
        var dateDebut = new Date($(this).val());
        var dateFin = document.getElementById('date_fin');
        
        // Formater la date de début pour l'attribut min
        var dd = String(dateDebut.getDate()).padStart(2, '0');
        var mm = String(dateDebut.getMonth() + 1).padStart(2, '0');
        var yyyy = dateDebut.getFullYear();
        var hh = String(dateDebut.getHours()).padStart(2, '0');
        var min = String(dateDebut.getMinutes()).padStart(2, '0');
        
        var dateDebutStr = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + min;
        
        dateFin.setAttribute('min', dateDebutStr);
        
        // Si la date de fin est antérieure à la nouvelle date de début, mettre à jour
        if (new Date(dateFin.value) <= dateDebut) {
            // Ajouter 1 heure à la date de début pour la date de fin par défaut
            dateDebut.setHours(dateDebut.getHours() + 1);
            
            dd = String(dateDebut.getDate()).padStart(2, '0');
            mm = String(dateDebut.getMonth() + 1).padStart(2, '0');
            yyyy = dateDebut.getFullYear();
            hh = String(dateDebut.getHours()).padStart(2, '0');
            min = String(dateDebut.getMinutes()).padStart(2, '0');
            
            var newDateFinStr = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + min;
            
            dateFin.value = newDateFinStr;
        }
    });
});
</script>
