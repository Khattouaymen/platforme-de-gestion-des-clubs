<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\presence_activite.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Feuille de Présence</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="/responsable/gestionPresence">Feuilles de Présence</a></li>
                        <li class="breadcrumb-item active">Activité</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $activite['titre'] ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Date:</th>
                                    <td><?= isset($activite['date_activite']) ? date('d/m/Y', strtotime($activite['date_activite'])) : 'N/A' ?></td>
                                </tr>
                                <?php if (isset($activite['heure_debut'])): ?>
                                <tr>
                                    <th>Heure de début:</th>
                                    <td><?= date('H:i', strtotime($activite['heure_debut'])) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (isset($activite['heure_fin'])): ?>
                                <tr>
                                    <th>Heure de fin:</th>
                                    <td><?= date('H:i', strtotime($activite['heure_fin'])) ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Lieu:</th>
                                    <td><?= $activite['lieu'] ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Participants</span>
                                    <span class="info-box-number"><?= count($participants) ?></span>
                                </div>
                            </div>
                            
                            <div class="progress-group">
                                <span class="progress-text">Présence vérifiée</span>
                                <?php
                                $total = count($participants);
                                $verified = 0;
                                foreach ($participants as $participant) {
                                    if ($participant['statut'] === 'present' || $participant['statut'] === 'absent') {
                                        $verified++;
                                    }
                                }
                                $percentage = $total > 0 ? round(($verified / $total) * 100) : 0;
                                ?>
                                <span class="float-right"><b><?= $verified ?></b>/<?= $total ?></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: <?= $percentage ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Liste des Participants</h3>
                                    <div class="card-tools">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="search" id="search-participant" class="form-control float-right" placeholder="Rechercher">
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
                                                <th>Email</th>
                                                <th>Filière</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($participants)): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Aucun participant pour cette activité</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($participants as $participant): ?>
                                                    <tr>
                                                        <td><?= $participant['etudiant_id'] ?></td>
                                                        <td><?= $participant['nom'] . ' ' . $participant['prenom'] ?></td>
                                                        <td><?= $participant['email'] ?></td>
                                                        <td><?= $participant['filiere'] ?></td>
                                                        <td>
                                                            <?php if ($participant['statut'] === 'present'): ?>
                                                                <span class="badge bg-success">Présent</span>
                                                            <?php elseif ($participant['statut'] === 'absent'): ?>
                                                                <span class="badge bg-danger">Absent</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-warning">Non vérifié</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-xs btn-success mark-present" data-activite-id="<?= $activite['id'] ?>" data-etudiant-id="<?= $participant['etudiant_id'] ?>">
                                                                    <i class="fas fa-check"></i> Présent
                                                                </button>
                                                                <button class="btn btn-xs btn-danger mark-absent" data-activite-id="<?= $activite['id'] ?>" data-etudiant-id="<?= $participant['etudiant_id'] ?>">
                                                                    <i class="fas fa-times"></i> Absent
                                                                </button>
                                                            </div>
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

                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="icon fas fa-info-circle"></i>
                                Marquez les participants comme présents ou absents. Cette information est importante pour le suivi des activités du club.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="/responsable/gestionPresence" class="btn btn-default">Retour</a>
                    <?php if ($total > 0 && $percentage === 100): ?>
                        <a href="#" class="btn btn-success float-right export-btn">
                            <i class="fas fa-file-export"></i> Exporter la Feuille de Présence
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Filtrer les participants lors de la recherche
    $("#search-participant").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    // Marquer un étudiant comme présent
    $(".mark-present").click(function() {
        var activiteId = $(this).data("activite-id");
        var etudiantId = $(this).data("etudiant-id");
        
        updatePresence(activiteId, etudiantId, true);
    });
    
    // Marquer un étudiant comme absent
    $(".mark-absent").click(function() {
        var activiteId = $(this).data("activite-id");
        var etudiantId = $(this).data("etudiant-id");
        
        updatePresence(activiteId, etudiantId, false);
    });
    
    // Fonction pour mettre à jour la présence
    function updatePresence(activiteId, etudiantId, present) {
        $.ajax({
            url: '/responsable/marquerPresence',
            type: 'POST',
            data: {
                activite_id: activiteId,
                etudiant_id: etudiantId,
                present: present
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    toastr.success("Le statut a été mis à jour avec succès");
                    // Recharger la page pour mettre à jour les informations
                    location.reload();
                } else {
                    toastr.error(result.message || "Une erreur est survenue lors de la mise à jour du statut");
                }
            },
            error: function() {
                toastr.error("Une erreur est survenue lors de la communication avec le serveur");
            }
        });
    }
    
    // Gérer l'exportation de la feuille de présence
    $(".export-btn").click(function(e) {
        e.preventDefault();
        alert("Fonctionnalité d'exportation en cours de développement. Elle sera disponible prochainement.");
    });
});
</script>
