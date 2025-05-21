<?php
// Définir le contenu
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Gestion des Demandes</h1>
    </div>

    <?php if (isset($alertSuccess)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $alertSuccess; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($alertError)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $alertError; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Onglets pour les différents types de demandes -->
    <ul class="nav nav-tabs mb-4" id="demandesTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="club-tab" data-bs-toggle="tab" data-bs-target="#club" type="button" role="tab" aria-controls="club" aria-selected="true">Demandes de clubs</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="adhesion-tab" data-bs-toggle="tab" data-bs-target="#adhesion" type="button" role="tab" aria-controls="adhesion" aria-selected="false">Demandes d'adhésion</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="activite-tab" data-bs-toggle="tab" data-bs-target="#activite" type="button" role="tab" aria-controls="activite" aria-selected="false">Demandes d'activités</button>
        </li>
    </ul>

    <!-- Contenu des onglets -->
    <div class="tab-content" id="demandesTabContent">
        <!-- Demandes de clubs -->
        <div class="tab-pane fade show active" id="club" role="tabpanel" aria-labelledby="club-tab">
            <!-- Sous-onglets pour les statuts des demandes de clubs -->
            <ul class="nav nav-pills mb-3" id="clubStatusTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="club-pending-tab" data-bs-toggle="pill" data-bs-target="#club-pending" type="button" role="tab" aria-controls="club-pending" aria-selected="true">En attente</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="club-approved-tab" data-bs-toggle="pill" data-bs-target="#club-approved" type="button" role="tab" aria-controls="club-approved" aria-selected="false">Approuvées</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="club-rejected-tab" data-bs-toggle="pill" data-bs-target="#club-rejected" type="button" role="tab" aria-controls="club-rejected" aria-selected="false">Rejetées</button>
                </li>
            </ul>

            <div class="tab-content" id="clubStatusTabContent">
                <!-- Demandes de clubs en attente -->
                <div class="tab-pane fade show active" id="club-pending" role="tabpanel" aria-labelledby="club-pending-tab">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom du Club</th>
                                            <th>Description</th>
                                            <th>Demandeur</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($demandesClub['en_attente']) && !empty($demandesClub['en_attente'])): ?>
                                            <?php foreach ($demandesClub['en_attente'] as $demande): ?>
                                                <tr>
                                                    <td><?php echo $demande['id_demande']; ?></td>
                                                    <td><?php echo $demande['nom_club']; ?></td>
                                                    <td><?php echo substr($demande['description'], 0, 100) . (strlen($demande['description']) > 100 ? '...' : ''); ?></td>
                                                    <td><?php echo $demande['etudiant_prenom'] . ' ' . $demande['etudiant_nom']; ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewClubDemandeModal" data-id="<?php echo $demande['id_demande']; ?>">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/demandes/approveClub/<?php echo $demande['id_demande']; ?>" class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                            <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/demandes/rejectClub/<?php echo $demande['id_demande']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir rejeter cette demande?')">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Aucune demande en attente</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demandes de clubs approuvées -->
                <div class="tab-pane fade" id="club-approved" role="tabpanel" aria-labelledby="club-approved-tab">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom du Club</th>
                                            <th>Description</th>
                                            <th>Demandeur</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($demandesClub['approuve']) && !empty($demandesClub['approuve'])): ?>
                                            <?php foreach ($demandesClub['approuve'] as $demande): ?>
                                                <tr>
                                                    <td><?php echo $demande['id_demande']; ?></td>
                                                    <td><?php echo $demande['nom_club']; ?></td>
                                                    <td><?php echo substr($demande['description'], 0, 100) . (strlen($demande['description']) > 100 ? '...' : ''); ?></td>
                                                    <td><?php echo $demande['etudiant_prenom'] . ' ' . $demande['etudiant_nom']; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewClubDemandeModal" data-id="<?php echo $demande['id_demande']; ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Aucune demande approuvée</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demandes de clubs rejetées -->
                <div class="tab-pane fade" id="club-rejected" role="tabpanel" aria-labelledby="club-rejected-tab">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom du Club</th>
                                            <th>Description</th>
                                            <th>Demandeur</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($demandesClub['rejete']) && !empty($demandesClub['rejete'])): ?>
                                            <?php foreach ($demandesClub['rejete'] as $demande): ?>
                                                <tr>
                                                    <td><?php echo $demande['id_demande']; ?></td>
                                                    <td><?php echo $demande['nom_club']; ?></td>
                                                    <td><?php echo substr($demande['description'], 0, 100) . (strlen($demande['description']) > 100 ? '...' : ''); ?></td>
                                                    <td><?php echo $demande['etudiant_prenom'] . ' ' . $demande['etudiant_nom']; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewClubDemandeModal" data-id="<?php echo $demande['id_demande']; ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Aucune demande rejetée</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demandes d'adhésion -->
        <div class="tab-pane fade" id="adhesion" role="tabpanel" aria-labelledby="adhesion-tab">
            <!-- Sous-onglets pour les statuts des demandes d'adhésion -->
            <ul class="nav nav-pills mb-3" id="adhesionStatusTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="adhesion-pending-tab" data-bs-toggle="pill" data-bs-target="#adhesion-pending" type="button" role="tab" aria-controls="adhesion-pending" aria-selected="true">En attente</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="adhesion-accepted-tab" data-bs-toggle="pill" data-bs-target="#adhesion-accepted" type="button" role="tab" aria-controls="adhesion-accepted" aria-selected="false">Acceptées</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="adhesion-refused-tab" data-bs-toggle="pill" data-bs-target="#adhesion-refused" type="button" role="tab" aria-controls="adhesion-refused" aria-selected="false">Refusées</button>
                </li>
            </ul>

            <div class="tab-content" id="adhesionStatusTabContent">
                <!-- Demandes d'adhésion en attente -->
                <div class="tab-pane fade show active" id="adhesion-pending" role="tabpanel" aria-labelledby="adhesion-pending-tab">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Étudiant</th>
                                            <th>Club</th>
                                            <th>Date de demande</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($demandesAdhesion['en_attente']) && !empty($demandesAdhesion['en_attente'])): ?>
                                            <?php foreach ($demandesAdhesion['en_attente'] as $demande): ?>
                                                <tr>
                                                    <td><?php echo $demande['demande_adh_id']; ?></td>
                                                    <td><?php echo $demande['etudiant_prenom'] . ' ' . $demande['etudiant_nom']; ?></td>
                                                    <td><?php echo $demande['club_nom']; ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($demande['date_demande'])); ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/demandes/acceptAdhesion/<?php echo $demande['demande_adh_id']; ?>" class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i> Accepter
                                                            </a>
                                                            <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/demandes/refuseAdhesion/<?php echo $demande['demande_adh_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir refuser cette demande?')">
                                                                <i class="fas fa-times"></i> Refuser
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Aucune demande en attente</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demandes d'adhésion acceptées -->
                <div class="tab-pane fade" id="adhesion-accepted" role="tabpanel" aria-labelledby="adhesion-accepted-tab">
                    <!-- Contenu pour les demandes d'adhésion acceptées -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Étudiant</th>
                                            <th>Club</th>
                                            <th>Date de demande</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($demandesAdhesion['acceptee']) && !empty($demandesAdhesion['acceptee'])): ?>
                                            <?php foreach ($demandesAdhesion['acceptee'] as $demande): ?>
                                                <tr>
                                                    <td><?php echo $demande['demande_adh_id']; ?></td>
                                                    <td><?php echo $demande['etudiant_prenom'] . ' ' . $demande['etudiant_nom']; ?></td>
                                                    <td><?php echo $demande['club_nom']; ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($demande['date_demande'])); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">Aucune demande acceptée</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demandes d'adhésion refusées -->
                <div class="tab-pane fade" id="adhesion-refused" role="tabpanel" aria-labelledby="adhesion-refused-tab">
                    <!-- Contenu pour les demandes d'adhésion refusées -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Étudiant</th>
                                            <th>Club</th>
                                            <th>Date de demande</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($demandesAdhesion['refusee']) && !empty($demandesAdhesion['refusee'])): ?>
                                            <?php foreach ($demandesAdhesion['refusee'] as $demande): ?>
                                                <tr>
                                                    <td><?php echo $demande['demande_adh_id']; ?></td>
                                                    <td><?php echo $demande['etudiant_prenom'] . ' ' . $demande['etudiant_nom']; ?></td>
                                                    <td><?php echo $demande['club_nom']; ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($demande['date_demande'])); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">Aucune demande refusée</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        <!-- Demandes d'activités -->
        <div class="tab-pane fade" id="activite" role="tabpanel" aria-labelledby="activite-tab">
            <!-- Sous-onglets pour les statuts des demandes d'activités -->
            <ul class="nav nav-pills mb-3" id="activiteStatusTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="activite-all-tab" data-bs-toggle="pill" data-bs-target="#activite-all" type="button" role="tab" aria-controls="activite-all" aria-selected="true">Toutes</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="activite-pending-tab" data-bs-toggle="pill" data-bs-target="#activite-pending" type="button" role="tab" aria-controls="activite-pending" aria-selected="false">En attente</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="activite-approved-tab" data-bs-toggle="pill" data-bs-target="#activite-approved" type="button" role="tab" aria-controls="activite-approved" aria-selected="false">Approuvées</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="activite-rejected-tab" data-bs-toggle="pill" data-bs-target="#activite-rejected" type="button" role="tab" aria-controls="activite-rejected" aria-selected="false">Rejetées</button>
                </li>
            </ul>

            <div class="tab-content" id="activiteStatusTabContent">
                <!-- Toutes les demandes d'activités -->
                <div class="tab-pane fade show active" id="activite-all" role="tabpanel" aria-labelledby="activite-all-tab">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom de l'activité</th>
                                    <th>Club</th>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Lieu</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($demandesActivite['toutes']) && !empty($demandesActivite['toutes'])): ?>
                                    <?php foreach ($demandesActivite['toutes'] as $demande): ?>
                                        <tr>
                                            <td><?php echo $demande['id_demande_act']; ?></td>
                                            <td><?php echo $demande['nom_activite']; ?></td>
                                            <td><?php echo $demande['club_nom']; ?></td>
                                            <td><?php echo isset($demande['date_debut']) ? date('d/m/Y H:i', strtotime($demande['date_debut'])) : (isset($demande['date_activite']) ? date('d/m/Y', strtotime($demande['date_activite'])) : 'Non spécifiée'); ?></td>
                                            <td><?php echo isset($demande['date_fin']) ? date('d/m/Y H:i', strtotime($demande['date_fin'])) : 'Non spécifiée'; ?></td>
                                            <td><?php echo $demande['lieu'] ?? 'Non spécifié'; ?></td>
                                            <td>
                                                <?php if (isset($demande['statut']) && $demande['statut'] == 'approuvee'): ?>
                                                    <span class="badge bg-success">Approuvée</span>
                                                <?php elseif (isset($demande['statut']) && $demande['statut'] == 'refusee'): ?>
                                                    <span class="badge bg-danger">Refusée</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">En attente</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewActiviteDemandeModal" data-id="<?php echo $demande['id_demande_act']; ?>">
                                                        <i class="fas fa-eye"></i>                                                    </button>                                                    <?php if (empty($demande['statut']) || $demande['statut'] == 'en_attente'): ?>
                                                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/approveActivite/<?php echo $demande['id_demande_act']; ?>" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger reject-activite-btn" data-bs-toggle="modal" data-bs-target="#rejectActiviteModal" data-id="<?php echo $demande['id_demande_act']; ?>">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Aucune demande d'activité</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>                    </div>
                </div>
            </div>
        </div>

                <!-- Demandes d'activités en attente -->
                <div class="tab-pane fade" id="activite-pending" role="tabpanel" aria-labelledby="activite-pending-tab">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom de l'activité</th>
                                            <th>Club</th>
                                            <th>Date début</th>
                                            <th>Date fin</th>
                                            <th>Lieu</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($demandesActivite['en_attente']) && !empty($demandesActivite['en_attente'])): ?>
                                            <?php foreach ($demandesActivite['en_attente'] as $demande): ?>
                                                <tr>
                                                    <td><?php echo $demande['id_demande_act']; ?></td>
                                                    <td><?php echo $demande['nom_activite']; ?></td>
                                                    <td><?php echo $demande['club_nom']; ?></td>
                                                    <td><?php echo isset($demande['date_debut']) ? date('d/m/Y H:i', strtotime($demande['date_debut'])) : (isset($demande['date_activite']) ? date('d/m/Y', strtotime($demande['date_activite'])) : 'Non spécifiée'); ?></td>
                                                    <td><?php echo isset($demande['date_fin']) ? date('d/m/Y H:i', strtotime($demande['date_fin'])) : 'Non spécifiée'; ?></td>
                                                    <td><?php echo $demande['lieu'] ?? 'Non spécifié'; ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewActiviteDemandeModal" data-id="<?php echo $demande['id_demande_act']; ?>">
                                                                <i class="fas fa-eye"></i>
                                                            </button>                                                            <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/approveActivite/<?php echo $demande['id_demande_act']; ?>" class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-danger reject-activite-btn" data-bs-toggle="modal" data-bs-target="#rejectActiviteModal" data-id="<?php echo $demande['id_demande_act']; ?>">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune demande en attente</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demandes d'activités approuvées -->
                <div class="tab-pane fade" id="activite-approved" role="tabpanel" aria-labelledby="activite-approved-tab">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom de l'activité</th>
                                            <th>Club</th>
                                            <th>Date début</th>
                                            <th>Date fin</th>
                                            <th>Lieu</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($demandesActivite['approuvee']) && !empty($demandesActivite['approuvee'])): ?>
                                            <?php foreach ($demandesActivite['approuvee'] as $demande): ?>
                                                <tr>
                                                    <td><?php echo $demande['id_demande_act']; ?></td>
                                                    <td><?php echo $demande['nom_activite']; ?></td>
                                                    <td><?php echo $demande['club_nom']; ?></td>
                                                    <td><?php echo isset($demande['date_debut']) ? date('d/m/Y H:i', strtotime($demande['date_debut'])) : (isset($demande['date_activite']) ? date('d/m/Y', strtotime($demande['date_activite'])) : 'Non spécifiée'); ?></td>
                                                    <td><?php echo isset($demande['date_fin']) ? date('d/m/Y H:i', strtotime($demande['date_fin'])) : 'Non spécifiée'; ?></td>
                                                    <td><?php echo $demande['lieu'] ?? 'Non spécifié'; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewActiviteDemandeModal" data-id="<?php echo $demande['id_demande_act']; ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune demande approuvée</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demandes d'activités rejetées -->
                <div class="tab-pane fade" id="activite-rejected" role="tabpanel" aria-labelledby="activite-rejected-tab">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom de l'activité</th>
                                            <th>Club</th>
                                            <th>Date début</th>
                                            <th>Date fin</th>
                                            <th>Lieu</th>
                                            <th>Commentaire</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($demandesActivite['refusee']) && !empty($demandesActivite['refusee'])): ?>
                                            <?php foreach ($demandesActivite['refusee'] as $demande): ?>
                                                <tr>
                                                    <td><?php echo $demande['id_demande_act']; ?></td>
                                                    <td><?php echo $demande['nom_activite']; ?></td>
                                                    <td><?php echo $demande['club_nom']; ?></td>
                                                    <td><?php echo isset($demande['date_debut']) ? date('d/m/Y H:i', strtotime($demande['date_debut'])) : (isset($demande['date_activite']) ? date('d/m/Y', strtotime($demande['date_activite'])) : 'Non spécifiée'); ?></td>
                                                    <td><?php echo isset($demande['date_fin']) ? date('d/m/Y H:i', strtotime($demande['date_fin'])) : 'Non spécifiée'; ?></td>
                                                    <td><?php echo $demande['lieu'] ?? 'Non spécifié'; ?></td>
                                                    <td><?php echo isset($demande['commentaire']) && !empty($demande['commentaire']) ? substr($demande['commentaire'], 0, 50) . (strlen($demande['commentaire']) > 50 ? '...' : '') : 'Aucun'; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewActiviteDemandeModal" data-id="<?php echo $demande['id_demande_act']; ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center">Aucune demande rejetée</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails d'une demande de club -->
<div class="modal fade" id="viewClubDemandeModal" tabindex="-1" aria-labelledby="viewClubDemandeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewClubDemandeModalLabel">Détails de la demande de club</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4" id="club-logo-container">
                    <img src="" id="club-logo" alt="Logo du club" class="img-fluid" style="max-height: 150px;">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nom du club:</strong> <span id="club-nom"></span></p>
                        <p><strong>Demandeur:</strong> <span id="club-demandeur"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Statut:</strong> <span id="club-statut"></span></p>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>Description</h6>
                    <p id="club-description"></p>
                </div>
            </div>
            <div class="modal-footer" id="club-actions">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="#" id="approveClubBtn" class="btn btn-success">Approuver</a>
                <a href="#" id="rejectClubBtn" class="btn btn-danger">Rejeter</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails d'une demande d'activité -->
<div class="modal fade" id="viewActiviteDemandeModal" tabindex="-1" aria-labelledby="viewActiviteDemandeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewActiviteDemandeModalLabel">Détails de la demande d'activité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>            <div class="modal-body">                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Nom de l'activité:</strong> <span id="activite-titre"></span></p>
                        <p><strong>Club:</strong> <span id="activite-club"></span></p>
                        <p><strong>Statut:</strong> <span id="activite-statut"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Date:</strong> <span id="activite-date"></span></p>
                        <p><strong>Lieu:</strong> <span id="activite-lieu"></span></p>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>Description</h6>
                    <div class="p-3 bg-light rounded" id="activite-description"></div>
                </div>
                <div class="mb-3" id="activite-commentaire-container" style="display: none;">
                    <h6>Motif du rejet</h6>
                    <div class="p-3 bg-light rounded" id="activite-commentaire"></div>                </div>
            </div>
            <div class="modal-footer" id="activite-actions">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="#" id="approveActiviteBtn" class="btn btn-success">Approuver</a>
                <button type="button" id="rejectActiviteBtn" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectActiviteModal" data-id="">Rejeter</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal pour voir les détails d'une demande de club
    const viewClubDemandeModal = document.getElementById('viewClubDemandeModal');
    if (viewClubDemandeModal) {
        viewClubDemandeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const demandeId = button.getAttribute('data-id');
            
            // Récupérer les données de la demande via AJAX
            fetch(`<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/demandes/getDemandeClub/${demandeId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const demande = data.demande;
                    document.getElementById('club-nom').textContent = demande.nom_club;
                    document.getElementById('club-demandeur').textContent = demande.etudiant_prenom + ' ' + demande.etudiant_nom;
                    document.getElementById('club-description').textContent = demande.description;
                    
                    // Afficher le statut avec un badge
                    let statutBadge = '';
                    if (demande.statut === 'en_attente') {
                        statutBadge = '<span class="badge bg-warning">En attente</span>';
                    } else if (demande.statut === 'approuve') {
                        statutBadge = '<span class="badge bg-success">Approuvé</span>';
                    } else {
                        statutBadge = '<span class="badge bg-danger">Rejeté</span>';
                    }
                    document.getElementById('club-statut').innerHTML = statutBadge;
                    
                    // Afficher/masquer les boutons d'action selon le statut
                    if (demande.statut === 'en_attente') {
                        document.getElementById('approveClubBtn').style.display = 'inline-block';
                        document.getElementById('rejectClubBtn').style.display = 'inline-block';
                        document.getElementById('approveClubBtn').href = `<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/demandes/approveClub/${demandeId}`;
                        document.getElementById('rejectClubBtn').href = `<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/demandes/rejectClub/${demandeId}`;
                    } else {
                        document.getElementById('approveClubBtn').style.display = 'none';
                        document.getElementById('rejectClubBtn').style.display = 'none';
                    }
                    
                    // Afficher le logo du club s'il existe
                    if (demande.Logo_URL) {
                        document.getElementById('club-logo').src = `<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>${demande.Logo_URL}`;
                        document.getElementById('club-logo-container').style.display = 'block';
                    } else {
                        document.getElementById('club-logo-container').style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
      // Modal pour voir les détails d'une demande d'activité
    const viewActiviteDemandeModal = document.getElementById('viewActiviteDemandeModal');
    if (viewActiviteDemandeModal) {
        viewActiviteDemandeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const demandeId = button.getAttribute('data-id');
            
            // Récupérer les données de la demande via AJAX
            fetch(`<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/getDemandeActivite/${demandeId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const demande = data.demande;
                    
                    // Informations de base
                    document.getElementById('activite-nom').textContent = demande.nom_activite || 'Non spécifié';
                    document.getElementById('activite-club').textContent = demande.club_nom || 'Non spécifié';
                    document.getElementById('activite-description').textContent = demande.description || 'Aucune description fournie';
                    document.getElementById('activite-lieu').textContent = demande.lieu || 'Non spécifié';
                    
                    // Date de création
                    const dateCreation = demande.date_creation ? 
                        new Date(demande.date_creation).toLocaleDateString('fr-FR') : 'Non spécifiée';
                    document.getElementById('activite-date-creation').textContent = dateCreation;
                    
                    // Dates de l'activité
                    // Pour la compatibilité avec l'ancien et le nouveau format
                    const dateDebut = demande.date_debut ? 
                        new Date(demande.date_debut).toLocaleString('fr-FR') : 
                        (demande.date_activite ? new Date(demande.date_activite).toLocaleDateString('fr-FR') : 'Non spécifiée');
                    document.getElementById('activite-date-debut').textContent = dateDebut;
                    
                    const dateFin = demande.date_fin ? 
                        new Date(demande.date_fin).toLocaleString('fr-FR') : 'Non spécifiée';
                    document.getElementById('activite-date-fin').textContent = dateFin;
                    
                    // Nombre max de participants
                    document.getElementById('activite-max-participants').textContent = demande.nombre_max || 'Non spécifié';
                    
                    // Statut avec badge
                    let statutBadge = '';
                    if (demande.statut === 'approuvee' || demande.statut === 'approuve') {
                        statutBadge = '<span class="badge bg-success">Approuvée</span>';
                    } else if (demande.statut === 'refusee' || demande.statut === 'rejete') {
                        statutBadge = '<span class="badge bg-danger">Refusée</span>';
                    } else {
                        statutBadge = '<span class="badge bg-warning">En attente</span>';
                    }
                    document.getElementById('activite-statut').innerHTML = statutBadge;
                    
                    // Commentaire (s'il existe)
                    const commentaireContainer = document.getElementById('activite-commentaire-container');
                    if (demande.commentaire) {
                        document.getElementById('activite-commentaire').textContent = demande.commentaire;
                        commentaireContainer.style.display = 'block';
                    } else {
                        commentaireContainer.style.display = 'none';
                    }
                      // Configurer les boutons d'action
                    document.getElementById('approveActiviteBtn').href = `<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/approveActivite/${demandeId}`;
                    document.getElementById('rejectActiviteBtn').href = `<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/rejectActivite/${demandeId}`;                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
</script>

<!-- Modal pour rejeter une demande d'activité avec commentaire -->
<div class="modal fade" id="rejectActiviteModal" tabindex="-1" aria-labelledby="rejectActiviteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectActiviteModalLabel">Rejeter la demande d'activité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectActiviteForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reject-commentaire" class="form-label">Motif du rejet</label>
                        <textarea class="form-control" id="reject-commentaire" name="commentaire" rows="3" placeholder="Expliquez la raison du rejet"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Inclusion du script externe pour gérer les modals -->
<script>
    // Définir la variable d'asset URL pour le script externe
    const baseAssetUrl = '<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>';
</script>
<script src="<?php echo isset($asset) ? $asset('views/admin/demandes_scripts.js') : '../views/admin/demandes_scripts.js'; ?>"></script>

<script>
    // Script d'initialisation du formulaire de rejet après le chargement du script externe
    document.addEventListener('DOMContentLoaded', function() {
        // Configurer l'action du formulaire avec le bon chemin de base
        const rejectButtons = document.querySelectorAll('.reject-activite-btn');
        const rejectForm = document.getElementById('rejectActiviteForm');
        
        if (rejectButtons && rejectForm) {
            rejectButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    rejectForm.action = baseAssetUrl + '/admin/rejectActivite/' + id;
                });
            });
        }
    });
</script>
