                        <table class="table table-striped table-hover">
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
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if (empty($demande['statut']) || $demande['statut'] == 'en_attente'): ?>
                                                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/demandes/approveActivite/<?php echo $demande['id_demande_act']; ?>" class="btn btn-sm btn-success">
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
                        </table>
                    </div>
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
                                                    </button>
                                                    <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/demandes/approveActivite/<?php echo $demande['id_demande_act']; ?>" class="btn btn-sm btn-success">
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
