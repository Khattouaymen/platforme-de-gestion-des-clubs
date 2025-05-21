<?php
// app/views/etudiant/profil.php
// Vue du profil étudiant avec édition des informations et changement de mot de passe

// Pré-remplir les champs avec les données de l'étudiant
$etudiant = isset($etudiant) ? $etudiant : [];
function val($key, $default = '') {
    global $etudiant;
    return isset($etudiant[$key]) ? htmlspecialchars($etudiant[$key]) : $default;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Mon profil</h3>
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
                        <div class="alert alert-success">Profil mis à jour avec succès.</div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['profile_completion_error'])): ?>
                        <div class="alert alert-warning"><?php echo htmlspecialchars($_SESSION['profile_completion_error']); ?></div>
                        <?php unset($_SESSION['profile_completion_error']); ?>
                    <?php endif; ?>

                    <form method="post" action="/etudiant/updateProfil" class="mb-4">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom :</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo val('nom'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom :</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo val('prenom'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email :</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo val('email'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="filiere" class="form-label">Filière :</label>
                            <input type="text" class="form-control" id="filiere" name="filiere" value="<?php echo val('filiere'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="niveau" class="form-label">Niveau :</label>
                            <input type="text" class="form-control" id="niveau" name="niveau" value="<?php echo val('niveau'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="numero_etudiant" class="form-label">Numéro étudiant :</label>
                            <input type="text" class="form-control" id="numero_etudiant" name="numero_etudiant" value="<?php echo val('numero_etudiant'); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Mettre à jour le profil</button>
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