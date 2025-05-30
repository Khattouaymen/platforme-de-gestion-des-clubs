<?php
$pageTitle = 'Gestion des Messages de Contact';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-envelope me-2"></i> Messages de Contact
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-info">Total: <?php echo $stats['total']; ?></span>
                        <span class="badge bg-warning">Non lus: <?php echo $stats['non_lu']; ?></span>
                        <span class="badge bg-success">Traités: <?php echo $stats['traite']; ?></span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo $stats['total']; ?></h3>
                                    <p class="mb-0">Total Messages</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo $stats['non_lu']; ?></h3>
                                    <p class="mb-0">Non lus</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo $stats['lu']; ?></h3>
                                    <p class="mb-0">Lus</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo $stats['traite']; ?></h3>
                                    <p class="mb-0">Traités</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (empty($messages)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun message de contact pour le moment.</p>
                        </div>
                    <?php else: ?>
                        <!-- Filtres -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <select class="form-select" id="filterStatut" onchange="filterMessages()">
                                    <option value="">Tous les statuts</option>
                                    <option value="non_lu">Non lus</option>
                                    <option value="lu">Lus</option>
                                    <option value="traite">Traités</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" id="filterSujet" onchange="filterMessages()">
                                    <option value="">Tous les sujets</option>
                                    <option value="information">Demande d'information</option>
                                    <option value="adhesion">Question sur l'adhésion</option>
                                    <option value="activite">Question sur les activités</option>
                                    <option value="technique">Problème technique</option>
                                    <option value="suggestion">Suggestion d'amélioration</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                        </div>

                        <!-- Liste des messages -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Statut</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Sujet</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="messagesTable">
                                    <?php foreach ($messages as $message): ?>
                                        <tr data-statut="<?php echo $message['statut']; ?>" data-sujet="<?php echo $message['sujet']; ?>"
                                            class="<?php echo $message['statut'] === 'non_lu' ? 'fw-bold' : ''; ?>">
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'non_lu' => 'bg-warning',
                                                    'lu' => 'bg-info',
                                                    'traite' => 'bg-success'
                                                ];
                                                $statusText = [
                                                    'non_lu' => 'Non lu',
                                                    'lu' => 'Lu',
                                                    'traite' => 'Traité'
                                                ];
                                                ?>
                                                <span class="badge <?php echo $statusClass[$message['statut']]; ?>">
                                                    <?php echo $statusText[$message['statut']]; ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($message['nom']); ?></td>
                                            <td><?php echo htmlspecialchars($message['email']); ?></td>
                                            <td>
                                                <?php
                                                $sujets = [
                                                    'information' => 'Demande d\'information',
                                                    'adhesion' => 'Question sur l\'adhésion',
                                                    'activite' => 'Question sur les activités',
                                                    'technique' => 'Problème technique',
                                                    'suggestion' => 'Suggestion d\'amélioration',
                                                    'autre' => 'Autre'
                                                ];
                                                echo $sujets[$message['sujet']] ?? $message['sujet'];
                                                ?>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($message['date_creation'])); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/admin/messageDetails/<?php echo $message['id']; ?>" 
                                                       class="btn btn-primary" title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($message['statut'] !== 'traite'): ?>
                                                        <a href="/admin/markMessageProcessed/<?php echo $message['id']; ?>" 
                                                           class="btn btn-success" title="Marquer comme traité"
                                                           onclick="return confirm('Marquer ce message comme traité ?')">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="/admin/deleteMessage/<?php echo $message['id']; ?>" 
                                                       class="btn btn-danger" title="Supprimer"
                                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
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
    </div>
</div>

<script>
function filterMessages() {
    const statutFilter = document.getElementById('filterStatut').value;
    const sujetFilter = document.getElementById('filterSujet').value;
    const rows = document.querySelectorAll('#messagesTable tr');
    
    rows.forEach(row => {
        const statut = row.getAttribute('data-statut');
        const sujet = row.getAttribute('data-sujet');
        
        const statutMatch = !statutFilter || statut === statutFilter;
        const sujetMatch = !sujetFilter || sujet === sujetFilter;
        
        if (statutMatch && sujetMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
