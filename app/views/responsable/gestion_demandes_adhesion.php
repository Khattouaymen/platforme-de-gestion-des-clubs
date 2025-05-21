<!-- filepath: c:\Users\Pavilion\sfe\app\views\responsable\gestion_demandes_adhesion.php -->

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gestion des Demandes d'Adhésion</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/responsable">Accueil</a></li>
                        <li class="breadcrumb-item active">Demandes d'Adhésion</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Demandes d'Adhésion en Attente</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" id="search-demande" class="form-control float-right" placeholder="Rechercher">
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
                                <th>Étudiant</th>
                                <th>Email</th>
                                <th>Filière</th>
                                <th>Niveau</th>
                                <th>Date de Demande</th>
                                <th>Motivation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($demandes)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Aucune demande d'adhésion en attente</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($demandes as $demande): ?>
                                    <?php if ($demande['statut'] === 'en_attente'): ?>
                                        <tr>
                                            <td><?= $demande['demande_adh_id'] ?></td>
                                            <td><?= $demande['etudiant_nom'] . ' ' . $demande['etudiant_prenom'] ?></td>
                                            <td><?= $demande['etudiant_email'] ?></td>
                                            <td><?= $demande['etudiant_filiere'] ?></td>
                                            <td><?= $demande['etudiant_niveau'] ?></td>
                                            <td><?php
    if (!empty($demande['date_demande'])) {
        echo date('d/m/Y', strtotime($demande['date_demande']));
    } else {
        echo 'N/A';
    }
?></td>                                            <td>
                                                <button class="btn btn-xs btn-info show-motivation" data-bs-toggle="modal" data-bs-target="#motivationModal" data-motivation='<?php 
                                                    // Afficher la motivation sous forme simple sans traitement complexe
                                                    $motivationValue = $demande['motivation'] ?? "";
                                                    echo htmlspecialchars($motivationValue, ENT_QUOTES, "UTF-8");
                                                ?>'>
                                                    <i class="fas fa-eye"></i> Voir
                                                </button>
                                            </td>
                                            <td>
                                                <button class="btn btn-xs btn-success accept-btn" data-id="<?= $demande['demande_adh_id'] ?>">
                                                    <i class="fas fa-check"></i> Accepter
                                                </button>
                                                <button class="btn btn-xs btn-danger reject-btn" data-id="<?= $demande['demande_adh_id'] ?>">
                                                    <i class="fas fa-times"></i> Refuser
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Demandes Acceptées</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Email</th>
                                        <th>Date d'Acceptation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $acceptees = array_filter($demandes, function($d) { return $d['statut'] === 'acceptee'; });
                                    if (empty($acceptees)): 
                                    ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Aucune demande acceptée</td>
                                        </tr>
                                    <?php else: ?>                                        <?php foreach ($acceptees as $demande): ?>
                                            <tr>
                                                <td><?= $demande['etudiant_nom'] . ' ' . $demande['etudiant_prenom'] ?></td>
                                                <td><?= $demande['etudiant_email'] ?></td>                                                <td><?php 
                                                    // Utiliser date_traitement qui existe maintenant
                                                    if (!empty($demande['date_traitement'])) {
                                                        echo date('d/m/Y', strtotime($demande['date_traitement']));
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Demandes Refusées</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Étudiant</th>
                                        <th>Email</th>
                                        <th>Date de Refus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $refusees = array_filter($demandes, function($d) { return $d['statut'] === 'refusee'; });
                                    if (empty($refusees)): 
                                    ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Aucune demande refusée</td>
                                        </tr>
                                    <?php else: ?>                                        <?php foreach ($refusees as $demande): ?>
                                            <tr>
                                                <td><?= $demande['etudiant_nom'] . ' ' . $demande['etudiant_prenom'] ?></td>
                                                <td><?= $demande['etudiant_email'] ?></td>                                                <td><?php 
                                                    // Utiliser date_traitement qui existe maintenant
                                                    if (!empty($demande['date_traitement'])) {
                                                        echo date('d/m/Y', strtotime($demande['date_traitement']));
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal d'affichage de la motivation -->
<div class="modal fade" id="motivationModal" tabindex="-1" role="dialog" aria-labelledby="motivationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="motivationModalLabel">Lettre de Motivation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="motivation-text" class="p-3 bg-light rounded"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Filtrer les demandes lors de la recherche
    $("#search-demande").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });    // Afficher la motivation - Version ultra simplifiée
    $(".show-motivation").on("click", function() {
        var motivation = $(this).attr("data-motivation");
        console.log("Motivation reçue:", motivation); // Débogage en console
        
        // En cas de motivation vide, afficher un message
        if (!motivation || motivation.trim() === '') {
            $("#motivation-text").html('<em>Aucune motivation fournie.</em>');
            return;
        }
        
        // Formatter le texte avec des sauts de ligne HTML
        var formattedText = motivation
            .replace(/\n/g, '<br>') // Convertir les sauts de ligne en balises <br>
            .replace(/\\n/g, '<br>'); // Au cas où il y a des \n échappés
            
        $("#motivation-text").html(formattedText);
    });
    
    // Accepter une demande d'adhésion
    $(".accept-btn").click(function() {
        var demandeId = $(this).data("id");
        
        if (confirm("Êtes-vous sûr de vouloir accepter cette demande d'adhésion ?")) {
            $.ajax({
                url: '/responsable/traiterDemandeAdhesion',
                type: 'POST',
                data: {
                    demande_id: demandeId,
                    statut: 'acceptee'
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        toastr.success("La demande a été acceptée avec succès");
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(result.message || "Une erreur est survenue");
                    }
                },
                error: function() {
                    toastr.error("Une erreur est survenue lors de la communication avec le serveur");
                }
            });
        }
    });
    
    // Refuser une demande d'adhésion
    $(".reject-btn").click(function() {
        var demandeId = $(this).data("id");
        
        if (confirm("Êtes-vous sûr de vouloir refuser cette demande d'adhésion ?")) {
            $.ajax({
                url: '/responsable/traiterDemandeAdhesion',
                type: 'POST',
                data: {
                    demande_id: demandeId,
                    statut: 'refusee'
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.success) {
                        toastr.success("La demande a été refusée");
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(result.message || "Une erreur est survenue");
                    }
                },
                error: function() {
                    toastr.error("Une erreur est survenue lors de la communication avec le serveur");
                }
            });
        }
    });
});
</script>
