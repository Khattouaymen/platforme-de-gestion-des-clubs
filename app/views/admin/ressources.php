<?php
// Définir le contenu
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Gestion des Ressources</h1>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addResourceModal">
            <i class="fas fa-plus"></i> Ajouter une ressource
        </button>
    </div>

    <!-- Tableau des ressources -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Capacité</th>
                            <th>Disponibilité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Salle A101</td>
                            <td>Salle de réunion</td>
                            <td>30 personnes</td>
                            <td><span class="badge bg-success">Disponible</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editResourceModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Salle B202</td>
                            <td>Amphithéâtre</td>
                            <td>100 personnes</td>
                            <td><span class="badge bg-danger">Réservée</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editResourceModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Equipment A</td>
                            <td>Matériel audiovisuel</td>
                            <td>N/A</td>
                            <td><span class="badge bg-success">Disponible</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editResourceModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter une ressource -->
<div class="modal fade" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResourceModalLabel">Ajouter une ressource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/ressources/add" method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Sélectionner un type</option>
                            <option value="Salle de réunion">Salle de réunion</option>
                            <option value="Amphithéâtre">Amphithéâtre</option>
                            <option value="Matériel audiovisuel">Matériel audiovisuel</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="capacite" class="form-label">Capacité (si applicable)</label>
                        <input type="text" class="form-control" id="capacite" name="capacite">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="disponible" name="disponible" checked>
                        <label class="form-check-label" for="disponible">Disponible</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour modifier une ressource -->
<div class="modal fade" id="editResourceModal" tabindex="-1" aria-labelledby="editResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editResourceModalLabel">Modifier une ressource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/ressources/edit" method="POST">
                    <input type="hidden" name="id" id="edit_id" value="">
                    <div class="mb-3">
                        <label for="edit_nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="edit_nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Type</label>
                        <select class="form-select" id="edit_type" name="type" required>
                            <option value="">Sélectionner un type</option>
                            <option value="Salle de réunion">Salle de réunion</option>
                            <option value="Amphithéâtre">Amphithéâtre</option>
                            <option value="Matériel audiovisuel">Matériel audiovisuel</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_capacite" class="form-label">Capacité (si applicable)</label>
                        <input type="text" class="form-control" id="edit_capacite" name="capacite">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="edit_disponible" name="disponible">
                        <label class="form-check-label" for="edit_disponible">Disponible</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once APP_PATH . '/views/layouts/main.php';
?>
