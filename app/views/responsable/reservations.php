<?php
// Vue pour la liste des réservations
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Réservations de ressources</h1>
        <div>
            <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/responsable" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Retour au tableau de bord
            </a>
            <?php if (!empty($activitesSansReservation)): ?>
                <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/responsable/creerReservation" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvelle réservation
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <!-- Liste des réservations -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Vos réservations</h5>
        </div>
        <div class="card-body">
            <?php if (empty($reservations)): ?>
                <p class="text-center text-muted">Vous n'avez pas encore de réservations.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Ressource</th>
                                <th>Activité</th>
                                <th>Date de début</th>
                                <th>Date de fin</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reservation['nom_ressource']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['activite_titre']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($reservation['date_debut'])); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($reservation['date_fin'])); ?></td>
                                    <td>
                                        <?php if ($reservation['statut'] === 'en_attente'): ?>
                                            <span class="badge bg-warning">En attente</span>
                                        <?php elseif ($reservation['statut'] === 'approuvee'): ?>
                                            <span class="badge bg-success">Approuvée</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Rejetée</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($reservation['statut'] === 'en_attente'): ?>
                                            <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/responsable/annulerReservation/<?php echo $reservation['id_reservation']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation?')">
                                                <i class="fas fa-times"></i> Annuler
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Activités sans réservation -->
    <?php if (!empty($activitesSansReservation)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Activités approuvées sans réservation</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Date</th>
                                <th>Lieu</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activitesSansReservation as $activite): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($activite['titre']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($activite['date_activite'])); ?></td>
                                    <td><?php echo htmlspecialchars($activite['lieu']); ?></td>
                                    <td>                                        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/responsable/creerReservation?activite_id=<?php echo $activite['activite_id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i> Réserver
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
