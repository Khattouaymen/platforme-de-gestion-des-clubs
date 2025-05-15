<?php
// Définir le contenu
ob_start();
?>

<style>
    .login-container {
        background-color: #fff;
        border-radius: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
        position: relative;
        overflow: hidden;
        width: 768px;
        max-width: 100%;
        min-height: 480px;
        margin: 50px auto;
    }

    .login-container .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.6s ease-in-out;
    }

    .login-container .sign-in-container {
        left: 0;
        width: 50%;
        z-index: 2;
    }

    .login-container .sign-up-container {
        left: 0;
        width: 50%;
        opacity: 0;
        z-index: 1;
    }

    .login-container.right-panel-active .sign-in-container {
        transform: translateX(100%);
    }

    .login-container.right-panel-active .sign-up-container {
        transform: translateX(100%);
        opacity: 1;
        z-index: 5;
        animation: show 0.6s;
    }

    .login-container .overlay-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: transform 0.6s ease-in-out;
        z-index: 100;
    }

    .login-container.right-panel-active .overlay-container {
        transform: translateX(-100%);
    }

    .login-container .overlay {
        background: #007bff;
        background: linear-gradient(to right, #0062E6, #33AEFF);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: 0 0;
        color: #fff;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;
    }

    .login-container.right-panel-active .overlay {
        transform: translateX(50%);
    }

    .login-container .overlay-panel {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 40px;
        text-align: center;
        top: 0;
        height: 100%;
        width: 50%;
        transform: translateX(0);
        transition: transform 0.6s ease-in-out;
    }

    .login-container .overlay-left {
        transform: translateX(-20%);
    }

    .login-container.right-panel-active .overlay-left {
        transform: translateX(0);
    }

    .login-container .overlay-right {
        right: 0;
        transform: translateX(0);
    }

    .login-container.right-panel-active .overlay-right {
        transform: translateX(20%);
    }

    .login-form {
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 50px;
        height: 100%;
        text-align: center;
    }

    .login-input {
        background-color: #eee;
        border: none;
        padding: 12px 15px;
        margin: 8px 0;
        width: 100%;
        border-radius: 5px;
    }

    .login-btn {
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

    .login-btn:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }

    .login-btn:active {
        transform: scale(0.95);
    }

    .login-btn:focus {
        outline: none;
    }

    .login-btn.ghost {
        background-color: transparent;
        border-color: #ffffff;
    }

    .login-btn.ghost:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>

<div class="login-container" id="login-container">
    <div class="form-container sign-in-container">
        <form action="/auth/login" method="post" class="login-form">
            <h1 class="mb-4">Connexion</h1>
            
            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger w-100" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <input type="email" name="email" placeholder="Email" class="login-input" required />
            <input type="password" name="password" placeholder="Mot de passe" class="login-input" required />
            <div class="form-group w-100 text-start mb-3">
                <label for="user_type" class="form-label">Type d'utilisateur</label>
                <select class="form-select" id="user_type" name="user_type" required>
                    <option value="">Sélectionnez votre type</option>
                    <option value="etudiant">Étudiant</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>
            <a href="#" class="mb-3">Mot de passe oublié ?</a>
            <button type="submit" class="login-btn">Se connecter</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-right">
                <h1>Bienvenue !</h1>
                <p>Entrez vos informations personnelles pour accéder à votre compte et gérer vos clubs</p>
                <p>Vous n'avez pas de compte ?</p>
                <a href="/register" class="login-btn ghost">S'inscrire</a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
