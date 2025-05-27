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
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo isset($prenom) ? $prenom : ''; ?>" required>                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="filiere" class="form-label">Filière <span class="text-danger">*</span></label>
                            <select class="form-control" id="filiere" name="filiere" required>
                                <option value="">Sélectionnez votre filière</option>
                                <option value="Informatique" <?php echo (isset($filiere) && $filiere == 'Informatique') ? 'selected' : ''; ?>>Informatique</option>
                                <option value="Génie Civil" <?php echo (isset($filiere) && $filiere == 'Génie Civil') ? 'selected' : ''; ?>>Génie Civil</option>
                                <option value="Génie Électrique" <?php echo (isset($filiere) && $filiere == 'Génie Électrique') ? 'selected' : ''; ?>>Génie Électrique</option>
                                <option value="Génie Mécanique" <?php echo (isset($filiere) && $filiere == 'Génie Mécanique') ? 'selected' : ''; ?>>Génie Mécanique</option>
                                <option value="Management" <?php echo (isset($filiere) && $filiere == 'Management') ? 'selected' : ''; ?>>Management</option>
                                <option value="Finance" <?php echo (isset($filiere) && $filiere == 'Finance') ? 'selected' : ''; ?>>Finance</option>
                                <option value="Marketing" <?php echo (isset($filiere) && $filiere == 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                                <option value="Autre" <?php echo (isset($filiere) && $filiere == 'Autre') ? 'selected' : ''; ?>>Autre</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="niveau" class="form-label">Niveau <span class="text-danger">*</span></label>
                            <select class="form-control" id="niveau" name="niveau" required>
                                <option value="">Sélectionnez votre niveau</option>
                                <option value="1ère année" <?php echo (isset($niveau) && $niveau == '1ère année') ? 'selected' : ''; ?>>1ère année</option>
                                <option value="2ème année" <?php echo (isset($niveau) && $niveau == '2ème année') ? 'selected' : ''; ?>>2ème année</option>
                                <option value="3ème année" <?php echo (isset($niveau) && $niveau == '3ème année') ? 'selected' : ''; ?>>3ème année</option>
                                <option value="4ème année" <?php echo (isset($niveau) && $niveau == '4ème année') ? 'selected' : ''; ?>>4ème année</option>
                                <option value="5ème année" <?php echo (isset($niveau) && $niveau == '5ème année') ? 'selected' : ''; ?>>5ème année</option>
                                <option value="Master 1" <?php echo (isset($niveau) && $niveau == 'Master 1') ? 'selected' : ''; ?>>Master 1</option>
                                <option value="Master 2" <?php echo (isset($niveau) && $niveau == 'Master 2') ? 'selected' : ''; ?>>Master 2</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="numero_etudiant" class="form-label">Numéro d'étudiant <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="numero_etudiant" name="numero_etudiant" value="<?php echo isset($numero_etudiant) ? $numero_etudiant : ''; ?>" required placeholder="Ex: 2024001234">
                            <div class="form-text">Votre numéro d'étudiant unique.</div>
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
        </div>    </div>
</div>
