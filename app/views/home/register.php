<?php
// Définir le contenu
ob_start();
?>

<style>
    .register-container {
        background-color: #fff;
        border-radius: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
        position: relative;
        overflow: hidden;
        width: 768px;
        max-width: 100%;
        min-height: 580px;
        margin: 50px auto;
    }

    .register-form {
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 50px;
        height: 100%;
        text-align: center;
    }

    .register-container .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.6s ease-in-out;
    }

    .register-container .sign-up-container {
        left: 0;
        width: 50%;
        z-index: 2;
    }

    .register-container .overlay-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: transform 0.6s ease-in-out;
        z-index: 100;
    }

    .register-container .overlay {
        background: #007bff;
        background: linear-gradient(to right, #0062E6, #33AEFF);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: 0 0;
        color: #fff;
        position: relative;
        left: 0;
        height: 100%;
        width: 100%;
    }

    .register-container .overlay-panel {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 40px;
        text-align: center;
        top: 0;
        height: 100%;
        width: 100%;
    }

    .register-input {
        background-color: #eee;
        border: none;
        padding: 12px 15px;
        margin: 8px 0;
        width: 100%;
        border-radius: 5px;
    }

    .register-btn {
        border-radius: 20px;
        border: 1px solid #007bff;
        background-color: #007bff;
        color: #ffffff;
        font-size: 12px;
        font-weight: bold;
        padding: 12px 45px;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: transform 80ms ease-in;
        cursor: pointer;
    }

    .register-btn:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }

    .register-btn:active {
        transform: scale(0.95);
    }

    .register-btn:focus {
        outline: none;
    }

    .register-btn.ghost {
        background-color: transparent;
        border-color: #ffffff;
    }

    .register-btn.ghost:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>

<div class="register-container">
    <div class="form-container sign-up-container">
        <form action="/auth/register" method="post" class="register-form">
            <h1 class="mb-4">Créer un compte</h1>
            
            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger w-100" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger w-100" role="alert">
                    <ul class="mb-0 text-start">
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo $err; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="row w-100">
                <div class="col-md-6">
                    <input type="text" name="nom" placeholder="Nom" class="register-input" value="<?php echo isset($nom) ? $nom : ''; ?>" required />
                </div>
                <div class="col-md-6">
                    <input type="text" name="prenom" placeholder="Prénom" class="register-input" value="<?php echo isset($prenom) ? $prenom : ''; ?>" required />
                </div>
            </div>
            <input type="email" name="email" placeholder="Email" class="register-input" value="<?php echo isset($email) ? $email : ''; ?>" required />
            <input type="password" name="password" placeholder="Mot de passe" class="register-input" required />
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" class="register-input" required />
            <div class="form-text w-100 text-start mb-3">Le mot de passe doit contenir au moins 6 caractères.</div>
            <button type="submit" class="register-btn">S'inscrire</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel">
                <h1>Déjà inscrit ?</h1>
                <p>Connectez-vous pour accéder à votre compte et retrouver vos activités et clubs</p>
                <a href="/login" class="register-btn ghost">Se connecter</a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
