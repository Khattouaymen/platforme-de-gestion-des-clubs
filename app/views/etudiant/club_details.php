<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><?php echo htmlspecialchars($club['nom']); ?></h1>
        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/clubs" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux clubs
        </a>
    </div>

    <div class="row mb-5">
        <div class="col-md-4">
            <?php if (!empty($club['Logo_URL'])): ?>
                <img src="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/public/assets/images/clubs/<?php echo htmlspecialchars($club['Logo_URL']); ?>" 
                     class="img-fluid rounded shadow-sm" 
                     alt="Logo de <?php echo htmlspecialchars($club['nom']); ?>" 
                     style="max-height: 250px; width: 100%; object-fit: cover;">
            <?php else: ?>
                <div class="bg-light rounded text-center py-5 mb-3">
                    <i class="fas fa-users fa-5x text-secondary"></i>
                </div>
            <?php endif; ?>
              <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong><i class="fas fa-users me-2"></i>Membres :</strong> <?php echo htmlspecialchars($club['nombre_membres']); ?></p>
                    
                    <?php if (!empty($responsable)): ?>
                    <div class="mt-3">
                        <h6><i class="fas fa-user-tie me-2"></i>Responsable du club :</h6>
                        <p class="ms-3 mb-0"><?php echo htmlspecialchars($responsable['prenom'] . ' ' . $responsable['nom']); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="d-grid mb-4">
                <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/demandeAdhesion/<?php echo $club['id']; ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>Demander l'adhésion
                </a>
            </div>
        </div>
          <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Description</h5>
                </div>
                <div class="card-body">
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($club['description'])); ?></p>
                </div>
            </div>
            
            <!-- Liste des membres du club -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Membres du club</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($membres)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Ce club n'a pas encore de membres.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Rôle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($membres as $membre): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($membre['nom']); ?></td>
                                        <td><?php echo htmlspecialchars($membre['prenom']); ?></td>
                                        <td>
                                            <?php if ($membre['role'] === 'president'): ?>
                                                <span class="badge bg-danger">Président</span>
                                            <?php elseif ($membre['role'] === 'secretaire'): ?>
                                                <span class="badge bg-info">Secrétaire</span>
                                            <?php elseif ($membre['role'] === 'tresorier'): ?>
                                                <span class="badge bg-warning text-dark">Trésorier</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Membre</span>
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
            
            <!-- Liste des activités du club -->
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Activités organisées par ce club</h5>
                </div>                <div class="card-body">
                    <?php if (empty($activites)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Aucune activité n'est prévue pour le moment par ce club.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date</th>
                                        <th>Lieu</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($activites as $activite): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($activite['titre']); ?></strong>
                                            <div class="small text-muted">
                                                <?php echo nl2br(htmlspecialchars(substr($activite['description'], 0, 100))); ?>
                                                <?php if (strlen($activite['description']) > 100): ?>...<?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php 
                                                try {
                                                    $date = new DateTime($activite['date_activite']);
                                                    echo $date->format('d/m/Y');
                                                    echo '<div class="small text-muted">' . $date->format('H:i') . '</div>';
                                                } catch (Exception $e) {
                                                    echo 'Date non définie';
                                                }
                                            ?>
                                        </td>                                        <td>
                                            <?php echo !empty($activite['lieu']) ? htmlspecialchars($activite['lieu']) : '<span class="text-muted">Non précisé</span>'; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/activite/<?php echo $activite['activite_id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Détails
                                            </a>
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
    </div>
</div>
