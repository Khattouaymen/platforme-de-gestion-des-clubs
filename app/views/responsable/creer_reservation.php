<?php
// Vue pour créer une nouvelle réservation
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Créer une réservation</h1>
        <div>
            <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/responsable/reservations" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Retour aux réservations
            </a>
        </div>
    </div>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Formulaire de réservation</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/responsable/creerReservation" method="POST" class="needs-validation" novalidate>
                <!-- Activité -->
                <div class="mb-3">
                    <label for="activite_id" class="form-label">Activité</label>
                    <select name="activite_id" id="activite_id" class="form-select" required>
                        <option value="">Sélectionnez une activité</option>                        <?php foreach ($activites as $activite): ?>
                            <option value="<?php echo $activite['activite_id'] ?? $activite['id'] ?? ''; ?>" <?php echo (isset($_GET['activite_id']) && $_GET['activite_id'] == ($activite['activite_id'] ?? $activite['id'])) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($activite['titre'] ?? 'Sans titre'); ?> (<?php echo isset($activite['date_activite']) ? date('d/m/Y', strtotime($activite['date_activite'])) : 'Date non définie'; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Veuillez sélectionner une activité.</div>
                </div>
                
                <!-- Ressource -->
                <div class="mb-3">
                    <label for="ressource_id" class="form-label">Ressource</label>
                    <select name="ressource_id" id="ressource_id" class="form-select" required>
                        <option value="">Sélectionnez une ressource</option>
                        <?php foreach ($ressources as $ressource): ?>
                            <option value="<?php echo $ressource['id_ressource']; ?>">
                                <?php echo htmlspecialchars($ressource['nom_ressource']); ?> - <?php echo htmlspecialchars($ressource['description']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Veuillez sélectionner une ressource.</div>
                </div>
                
                <!-- Période de réservation -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="date_debut" class="form-label">Date et heure de début</label>
                        <input type="datetime-local" name="date_debut" id="date_debut" class="form-control" required>
                        <div class="invalid-feedback">Veuillez spécifier une date et heure de début.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="date_fin" class="form-label">Date et heure de fin</label>
                        <input type="datetime-local" name="date_fin" id="date_fin" class="form-control" required>
                        <div class="invalid-feedback">Veuillez spécifier une date et heure de fin.</div>
                    </div>
                </div>
                
                <!-- Description / Motif -->
                <div class="mb-4">
                    <label for="description" class="form-label">Description / Motif de la réservation</label>
                    <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Créer la réservation
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Validation des formulaires Bootstrap
    (function() {
        'use strict';
        
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch all forms we want to apply validation to
            var forms = document.querySelectorAll('.needs-validation');
            
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
            
            // Valider que la date de fin est après la date de début
            document.getElementById('date_fin').addEventListener('change', function() {
                const dateDebut = new Date(document.getElementById('date_debut').value);
                const dateFin = new Date(this.value);
                
                if (dateFin <= dateDebut) {
                    this.setCustomValidity('La date de fin doit être postérieure à la date de début');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    })();
</script>
