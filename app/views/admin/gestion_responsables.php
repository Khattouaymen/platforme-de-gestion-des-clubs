<?php
// Définir le contenu
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Gestion des Responsables de Club</h1>
        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Futurs Responsables en Attente d'Assignation</h5>
        </div>
        <div class="card-body">
            <?php if (empty($futursResponsables)): ?>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i> Aucun futur responsable en attente d'assignation.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Date d'inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($futursResponsables as $etudiant): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($etudiant['email']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($etudiant['date_utilisation'] ?? date('Y-m-d'))); ?></td>
                                    <td>
                                        <form action="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/assignerResponsable" method="POST" style="display:inline;">
                                            <input type="hidden" name="etudiant_id" value="<?php echo $etudiant['id_etudiant']; ?>">
                                            <div class="d-flex">
                                                <select class="form-select form-select-sm me-2" name="club_id" required style="width: auto;">
                                                    <option value="">-- Club --</option>
                                                    <?php foreach ($clubs as $club): ?>
                                                        <option value="<?php echo $club['id']; ?>"><?php echo htmlspecialchars($club['nom']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-user-plus me-1"></i> Assigner
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Responsables de Clubs Actuels</h5>
        </div>
        <div class="card-body">
            <?php if (empty($responsables)): ?>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i> Aucun responsable de club n'a été assigné pour le moment.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Club</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($responsables as $responsable): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($responsable['etudiant_nom']); ?></td>
                                    <td><?php echo htmlspecialchars($responsable['etudiant_prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($responsable['etudiant_email']); ?></td>
                                    <td><?php echo htmlspecialchars($responsable['club_nom']); ?></td>                                    <td>
                                        <div class="d-flex flex-column align-items-center">
                                            <form action="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/changerClubResponsable" method="POST" class="mb-2 w-100">
                                                <input type="hidden" name="responsable_id" value="<?php echo $responsable['id_responsable']; ?>">
                                                <div class="input-group input-group-sm">
                                                    <select class="form-select form-select-sm" name="club_id" required>
                                                        <option value="">Changer...</option>
                                                        <?php foreach ($clubs as $club): ?>
                                                            <?php if (isset($club['id']) && $club['id'] != $responsable['club_id']): ?>
                                                                <option value="<?php echo $club['id']; ?>"><?php echo htmlspecialchars($club['nom']); ?></option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </button>
                                                </div>
                                            </form>
                                            
                                            <form action="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/retirerResponsable" method="POST" class="w-100" onsubmit="return confirm('Êtes-vous sûr de vouloir retirer les droits de responsable à <?php echo addslashes(htmlspecialchars($responsable['etudiant_prenom'] . ' ' . $responsable['etudiant_nom'])); ?> ?');">
                                                <input type="hidden" name="responsable_id" value="<?php echo $responsable['id_responsable']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                                    <i class="fas fa-user-minus"></i> Retirer
                                                </button>
                                            </form>
                                        </div>
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

<?php
$content = ob_get_clean();

// Appeler le layout avec le contenu
require_once APP_PATH . '/views/layouts/main.php';
?>
