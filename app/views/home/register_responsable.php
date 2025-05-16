<?php
// Définir le contenu
ob_start();
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Inscription Responsable de Club</h3>
                </div>
                <div class="card-body">
                    <p class="card-text mb-4">
                        Vous avez été invité(e) à vous inscrire en tant que responsable de club. Veuillez remplir le formulaire ci-dessous pour créer votre compte.
                    </p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Après votre inscription, un administrateur vous assignera à un club spécifique comme responsable.
                    </div>
                    
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>                    <?php endif; ?>
                    
                    <form action="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/auth/register/responsable/<?php echo $token; ?>" method="POST">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo isset($nom) ? $nom : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo isset($prenom) ? $prenom : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                            <div class="form-text">Le mot de passe doit contenir au moins 6 caractères.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">S'inscrire</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">
                        Déjà inscrit ? <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/login">Connectez-vous ici</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

// Appeler le layout avec le contenu
require_once APP_PATH . '/views/layouts/main.php';
?>
