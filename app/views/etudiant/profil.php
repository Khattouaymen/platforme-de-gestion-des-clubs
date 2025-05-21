<?php
// app/views/etudiant/profil.php
// Vue du profil étudiant avec édition des informations et changement de mot de passe

// Pré-remplir les champs avec les données de l'étudiant
$etudiant = isset($etudiant) ? $etudiant : [];

function val($key, $default = '') {
    global $etudiant;
    if (is_array($etudiant) && array_key_exists($key, $etudiant)) {
        return htmlspecialchars($etudiant[$key] ?? $default);
    }
    return $default;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm mb-4">                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Mon profil</h3>
                        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <div><?php echo htmlspecialchars($error); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                      <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <?php if (isset($_GET['completed']) && $_GET['completed'] == 1): ?>
                                <strong>Félicitations !</strong> Votre profil est maintenant complet. Vous pouvez désormais rejoindre des clubs et participer à des activités.
                            <?php else: ?>
                                Profil mis à jour avec succès.
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['profile_completion_error'])): ?>
                        <div class="alert alert-warning"><?php echo htmlspecialchars($_SESSION['profile_completion_error']); ?></div>
                        <?php unset($_SESSION['profile_completion_error']); ?>
                    <?php endif; ?>                    <form method="post" action="/etudiant/updateProfil" class="mb-4">
                        <!-- Informations non modifiables -->
                        <div class="card mb-3 border-info">
                            <div class="card-header bg-info text-white">
                                <strong>Informations d'identité (non modifiables)</strong>
                            </div>
                            <div class="card-body">                                <div class="mb-3">
                                    <label for="nom" class="form-label fw-bold">Nom :</label>
                                    <input type="text" class="form-control bg-light border-info" id="nom" name="nom" value="<?php echo isset($etudiant['nom']) ? $etudiant['nom'] : ''; ?>" readonly>
                                    <small class="form-text text-muted">Vous ne pouvez pas modifier votre nom.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="prenom" class="form-label fw-bold">Prénom :</label>
                                    <input type="text" class="form-control bg-light border-info" id="prenom" name="prenom" value="<?php echo isset($etudiant['prenom']) ? $etudiant['prenom'] : ''; ?>" readonly>
                                    <small class="form-text text-muted">Vous ne pouvez pas modifier votre prénom.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold">Email :</label>
                                    <input type="email" class="form-control bg-light border-info" id="email" name="email" value="<?php echo isset($etudiant['email']) ? $etudiant['email'] : ''; ?>" readonly>
                                    <small class="form-text text-muted">Vous ne pouvez pas modifier votre email.</small>
                                </div>
                            </div>
                        </div>
                          <hr>
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <strong>Informations académiques (obligatoires)</strong>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Les champs suivants sont nécessaires pour rejoindre les clubs et participer aux activités.
                                    <?php if (empty(val('filiere')) || empty(val('niveau')) || empty(val('numero_etudiant'))): ?>
                                        <strong>Veuillez compléter les informations manquantes.</strong>
                                    <?php endif; ?>
                                </div>
                        
                        <div class="mb-3">
                            <label for="filiere" class="form-label">
                                Filière :
                                <?php if (empty(val('filiere'))): ?>
                                    <span class="text-danger">*</span>
                                <?php endif; ?>
                            </label>
                            <input type="text" class="form-control <?php echo empty(val('filiere')) ? 'border-danger' : ''; ?>" 
                                   id="filiere" name="filiere" value="<?php echo val('filiere'); ?>" 
                                   <?php echo empty(val('filiere')) ? 'required' : ''; ?>>
                            <?php if (empty(val('filiere'))): ?>
                                <small class="text-danger">Ce champ est requis pour compléter votre profil</small>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="niveau" class="form-label">
                                Niveau :
                                <?php if (empty(val('niveau'))): ?>
                                    <span class="text-danger">*</span>
                                <?php endif; ?>
                            </label>
                            <input type="text" class="form-control <?php echo empty(val('niveau')) ? 'border-danger' : ''; ?>" 
                                   id="niveau" name="niveau" value="<?php echo val('niveau'); ?>" 
                                   <?php echo empty(val('niveau')) ? 'required' : ''; ?>>
                            <?php if (empty(val('niveau'))): ?>
                                <small class="text-danger">Ce champ est requis pour compléter votre profil</small>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="numero_etudiant" class="form-label">
                                Numéro étudiant :
                                <?php if (empty(val('numero_etudiant'))): ?>
                                    <span class="text-danger">*</span>
                                <?php endif; ?>
                            </label>
                            <input type="text" class="form-control <?php echo empty(val('numero_etudiant')) ? 'border-danger' : ''; ?>" 
                                   id="numero_etudiant" name="numero_etudiant" value="<?php echo val('numero_etudiant'); ?>" 
                                   <?php echo empty(val('numero_etudiant')) ? 'required' : ''; ?>>                            <?php if (empty(val('numero_etudiant'))): ?>
                                <small class="text-danger">Ce champ est requis pour compléter votre profil</small>
                            <?php endif; ?>
                        </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">
                                    <?php if (empty(val('filiere')) || empty(val('niveau')) || empty(val('numero_etudiant'))): ?>
                                        <i class="fas fa-check-circle"></i> Compléter mon profil
                                    <?php else: ?>
                                        <i class="fas fa-save"></i> Mettre à jour le profil
                                    <?php endif; ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h3 class="card-title mb-0">Changer le mot de passe</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($passwordErrors) && !empty($passwordErrors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($passwordErrors as $error): ?>
                                <div><?php echo htmlspecialchars($error); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($passwordError)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($passwordError); ?></div>
                    <?php endif; ?>

                    <form method="post" action="/etudiant/changePassword">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel :</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nouveau mot de passe :</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe :</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-secondary">Changer le mot de passe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>