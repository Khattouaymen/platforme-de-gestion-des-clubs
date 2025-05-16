<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\reservation_ressources.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Réservation de Ressources</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item active">Réservation de Ressources</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Ressources Disponibles</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="search" id="search-resource" class="form-control float-right" placeholder="Rechercher">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Quantité</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($ressources)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Aucune ressource disponible pour le moment</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($ressources as $ressource): ?>
                                            <tr>
                                                <td><?= $ressource['id'] ?></td>
                                                <td><?= $ressource['nom'] ?></td>
                                                <td><?= $ressource['type'] ?></td>
                                                <td><?= $ressource['description'] ?></td>
                                                <td><?= $ressource['quantite'] ?></td>
                                                <td>
                                                    <button class="btn btn-xs btn-primary reserve-btn" data-id="<?= $ressource['id'] ?>" data-nom="<?= $ressource['nom'] ?>">
                                                        <i class="fas fa-calendar-plus"></i> Réserver
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Mes Réservations</h3>
                        </div>
                        <div class="card-body p-0">
                            <div id="reservation-calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal de réservation -->
<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalLabel">Réserver une ressource</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reservationForm">
                    <input type="hidden" id="ressource_id" name="ressource_id">
                    <div class="form-group">
                        <label>Ressource</label>
                        <p id="modal-ressource-nom" class="form-control-static"></p>
                    </div>
                    <div class="form-group">
                        <label for="date_debut">Date et Heure de Début</label>
                        <input type="datetime-local" class="form-control" id="date_debut" name="date_debut" required>
                    </div>
                    <div class="form-group">
                        <label for="date_fin">Date et Heure de Fin</label>
                        <input type="datetime-local" class="form-control" id="date_fin" name="date_fin" required>
                    </div>
                    <div class="form-group">
                        <label for="motif">Motif de la Réservation</label>
                        <textarea class="form-control" id="motif" name="motif" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="submitReservation">Demander la Réservation</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialiser le calendrier
    var calendarEl = document.getElementById('reservation-calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'fr',
        events: '/responsable/getReservations',  // Endpoint à créer pour récupérer les réservations
        eventClick: function(info) {
            alert('Réservation: ' + info.event.title);
        }
    });
    calendar.render();

    // Filtrer les ressources lors de la recherche
    $("#search-resource").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Ouvrir le modal de réservation
    $(".reserve-btn").click(function() {
        var ressourceId = $(this).data("id");
        var ressourceNom = $(this).data("nom");
        
        $("#ressource_id").val(ressourceId);
        $("#modal-ressource-nom").text(ressourceNom);
        
        // Réinitialiser le formulaire
        $("#reservationForm")[0].reset();
        
        // Définir la date minimale (aujourd'hui)
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();
        var hh = String(today.getHours()).padStart(2, '0');
        var min = String(today.getMinutes()).padStart(2, '0');
        
        var todayStr = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + min;
        
        document.getElementById('date_debut').setAttribute('min', todayStr);
        document.getElementById('date_fin').setAttribute('min', todayStr);
        
        $("#reservationModal").modal('show');
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
    
    // Soumettre la demande de réservation
    $("#submitReservation").click(function() {
        var ressourceId = $("#ressource_id").val();
        var dateDebut = $("#date_debut").val();
        var dateFin = $("#date_fin").val();
        var motif = $("#motif").val();
        
        // Validation de base
        if (!dateDebut || !dateFin || !motif) {
            alert("Veuillez remplir tous les champs.");
            return;
        }
        
        if (new Date(dateFin) <= new Date(dateDebut)) {
            alert("La date de fin doit être postérieure à la date de début.");
            return;
        }
        
        // Envoyer la demande de réservation
        $.ajax({
            url: '/responsable/demanderRessource',
            type: 'POST',
            data: {
                ressource_id: ressourceId,
                date_debut: dateDebut,
                date_fin: dateFin,
                motif: motif
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $("#reservationModal").modal('hide');
                    toastr.success("Demande de réservation envoyée avec succès.");
                    
                    // Recharger le calendrier
                    calendar.refetchEvents();
                } else {
                    toastr.error(result.message || "Une erreur est survenue lors de la demande de réservation.");
                }
            },
            error: function() {
                toastr.error("Une erreur est survenue lors de la communication avec le serveur.");
            }
        });
    });
});
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/fr.js"></script>
