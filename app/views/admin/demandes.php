<?php
// Définir le contenu
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Gestion des Demandes</h1>
    </div>

    <!-- Onglets pour les différents types de demandes -->
    <ul class="nav nav-tabs mb-4" id="demandesTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">En attente</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">Approuvées</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">Rejetées</button>
        </li>
    </ul>

    <!-- Contenu des onglets -->
    <div class="tab-content" id="demandesTabContent">
        <!-- Demandes en attente -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Club</th>
                                    <th>Titre</th>
                                    <th>Date de soumission</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Activité</td>
                                    <td>Club Informatique</td>
                                    <td>Atelier de Programmation</td>
                                    <td>10/05/2025</td>
                                    <td>
                                        <button class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#viewDemandModal">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Ressource</td>
                                    <td>Club Débat</td>
                                    <td>Réservation d'amphithéâtre</td>
                                    <td>12/05/2025</td>
                                    <td>
                                        <button class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#viewDemandModal">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Demandes approuvées -->
        <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Club</th>
                                    <th>Titre</th>
                                    <th>Date d'approbation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>3</td>
                                    <td>Activité</td>
                                    <td>Club Musique</td>
                                    <td>Concert de fin d'année</td>
                                    <td>05/05/2025</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewDemandModal">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Demandes rejetées -->
        <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Club</th>
                                    <th>Titre</th>
                                    <th>Date de rejet</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>4</td>
                                    <td>Ressource</td>
                                    <td>Club Art</td>
                                    <td>Atelier de peinture</td>
                                    <td>02/05/2025</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewDemandModal">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails d'une demande -->
<div class="modal fade" id="viewDemandModal" tabindex="-1" aria-labelledby="viewDemandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDemandModalLabel">Détails de la demande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Type:</strong> <span id="demand_type">Activité</span></p>
                        <p><strong>Club:</strong> <span id="demand_club">Club Informatique</span></p>
                        <p><strong>Titre:</strong> <span id="demand_title">Atelier de Programmation</span></p>
                        <p><strong>Date de soumission:</strong> <span id="demand_date">10/05/2025</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Statut:</strong> <span class="badge bg-warning">En attente</span></p>
                        <p><strong>Demandeur:</strong> <span id="demand_requester">Mohammed Alami</span></p>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>Description</h6>
                    <p id="demand_description">
                        Nous souhaitons organiser un atelier de programmation pour les débutants. L'atelier couvrira les bases de la programmation en Python et sera animé par des étudiants expérimentés du club. Nous aurons besoin d'une salle équipée d'ordinateurs.
                    </p>
                </div>
                <div class="mb-3">
                    <h6>Détails supplémentaires</h6>
                    <ul>
                        <li><strong>Date prévue:</strong> <span id="demand_planned_date">20/05/2025</span></li>
                        <li><strong>Heure:</strong> <span id="demand_time">14h00 - 17h00</span></li>
                        <li><strong>Lieu demandé:</strong> <span id="demand_location">Salle Informatique B12</span></li>
                        <li><strong>Nombre de participants attendus:</strong> <span id="demand_participants">25</span></li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success">Approuver</button>
                <button type="button" class="btn btn-danger">Rejeter</button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/layouts/main.php';
?>
