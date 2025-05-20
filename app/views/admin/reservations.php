<?php
// Vue pour la gestion des réservations de ressources par l'administrateur
?>

<div class="container-fluid py-4">
    <h1 class="h3 mb-4">Gestion des réservations de ressources</h1>
    
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
    
    <div class="card shadow">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Liste des réservations</h5>
                <div>
                    <form action="" method="GET" class="d-flex align-items-center">
                        <label for="statut" class="me-2">Filtrer par statut:</label>
                        <select name="statut" id="statut" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="tous" <?php echo $filtreStatut === 'tous' ? 'selected' : ''; ?>>Tous</option>
                            <option value="en_attente" <?php echo $filtreStatut === 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                            <option value="approuvee" <?php echo $filtreStatut === 'approuvee' ? 'selected' : ''; ?>>Approuvée</option>
                            <option value="rejetee" <?php echo $filtreStatut === 'rejetee' ? 'selected' : ''; ?>>Rejetée</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($reservations)): ?>
                <p class="text-center text-muted my-5">Aucune réservation trouvée.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Club</th>
                                <th>Ressource</th>
                                <th>Activité</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Statut</th>
                                <th>Date demande</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?php echo $reservation['id_reservation']; ?></td>
                                    <td><?php echo htmlspecialchars($reservation['club_nom']); ?></td>
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
                                    <td><?php echo date('d/m/Y H:i', strtotime($reservation['date_reservation'])); ?></td>
                                    <td>
                                        <?php if ($reservation['statut'] === 'en_attente'): ?>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo isset($asset) ? $asset('') : ''; ?>/admin/approuverReservation/<?php echo $reservation['id_reservation']; ?>" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir approuver cette réservation?')">
                                                    <i class="fas fa-check"></i> Approuver
                                                </a>
                                                <a href="<?php echo isset($asset) ? $asset('') : ''; ?>/admin/rejeterReservation/<?php echo $reservation['id_reservation']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir rejeter cette réservation?')">
                                                    <i class="fas fa-times"></i> Rejeter
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">Aucune action disponible</span>
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
</div>

<script>
    // Pour activer les tooltips Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
