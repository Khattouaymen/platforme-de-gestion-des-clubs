<?php
// Page de login sans navbar
?><style>
    /* Styles CSS pour le formulaire de connexion/inscription */
    .container#container {
        background-color: #fff;
        border-radius: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
        overflow: hidden;
        width: 768px;
        max-width: 100%;
        min-height: 580px;
        margin: auto;
        position: relative;
    }
    
    .form-container form {
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 0 50px;
        height: 100%;
        text-align: center;
    }
    
    .form-container input, .form-container select {
        background-color: #eee;
        border: none;
        padding: 12px 15px;
        margin: 8px 0;
        width: 100%;
        border-radius: 5px;
    }
    
    button {
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
    
    button:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }
    
    button:active {
        transform: scale(0.95);
    }
    
    button:focus {
        outline: none;
    }
    
    button.hidden {
        background-color: transparent;
        border-color: #ffffff;
    }

    button.hidden:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>

<div class="container" id="container"><div class="form-container sign-up">
             <form action="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/auth/register" method="POST">
                 <h1>Créer un compte</h1>
                 <span>ou utilisez votre email pour vous inscrire</span>
                 
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
                        <input type="text" name="nom" placeholder="Nom" value="<?php echo isset($nom) ? $nom : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="prenom" placeholder="Prénom" value="<?php echo isset($prenom) ? $prenom : ''; ?>" required>
                    </div>                 </div>
                 <input type="email" name="email" placeholder="Email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                 <input type="password" name="password" placeholder="mot de passe" required>
                 <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                 <div class="form-text w-100 text-start mb-3">Le mot de passe doit contenir au moins 6 caractères.</div>
                 <select name="user_type" class="form-select mb-3" required>
                     <option value="">Sélectionnez votre rôle</option>
                     <option value="etudiant">Étudiant</option>
                 </select>
                 <button type="submit">S'inscrire</button>
             </form>
         </div>
         <div class="form-container sign-in">
             <form action="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/auth/login" method="POST">
                 <h1>Se connecter</h1>                     
                     <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger w-100" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
                 <span>ou utilisez votre mot de passe de messagerie</span>
                 <input type="email" name="email" placeholder="Email" required>
                 <input type="password" name="password" placeholder="mot de passe" required>
                 <select name="user_type" class="form-select mb-3" required>
                     <option value="">Sélectionnez votre rôle</option>
                     <option value="etudiant">Étudiant</option>
                     <option value="admin">Administrateur</option>
                 </select>
                 <a href="#">Mot de passe oublié?</a>
                 <button type="submit">Se connecter</button>
             </form>
         </div>
         <div class="toggle-container">
             <div class="toggle">
                 <div class="toggle-panel toggle-left">
                     <h1>Content de te revoir!</h1>
                     <p>Saisissez vos informations personnelles pour utiliser toutes les fonctionnalités du application </p>
                     <button class="hidden" id="login">Se connecter</button>
                 </div>
                 <div class="toggle-panel toggle-right">
                     <h1>Bonjour </h1>
                     <p>Inscrivez-vous avec vos informations personnelles pour utiliser toutes les fonctionnalités du application</p>
                     <button class="hidden" id="register">S'inscrire</button>
                 </div>
             </div>
         </div>
     </div>
 
     <script>
        const container = document.getElementById('container');
 const registerBtn = document.getElementById('register');
 const loginBtn = document.getElementById('login');
 
 registerBtn.addEventListener('click', () => {
     container.classList.add("active");
 });
 
 loginBtn.addEventListener('click', () => {
     container.classList.remove("active");
 });
     </script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');
 
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Montserrat', sans-serif;
}

body{
    background-color: #c9d6ff;
    background: linear-gradient(to right, #e2e2e2, #c9d6ff);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    height: 100vh;
}

.container{
    background-color: #fff;
    border-radius: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
    position: relative;
    overflow: hidden;
    width: 768px;
    max-width: 100%;
    min-height: 480px;
}

.container p{
    font-size: 14px;
    line-height: 20px;
    letter-spacing: 0.3px;
    margin: 20px 0;
}

.container span{
    font-size: 12px;
}

.container a{
    color: #333;
    font-size: 13px;
    text-decoration: none;
    margin: 15px 0 10px;
}

.container button{
    background-color: #120461;
    color: #fff;
    font-size: 12px;
    padding: 10px 45px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-top: 10px;
    cursor: pointer;
}

.container button.hidden{
    background-color: transparent;
    border-color: #fff;
}

.container form{
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 40px;
    height: 100%;
}

.container input{
    background-color: #eee;
    border: none;
    margin: 8px 0;
    padding: 10px 15px;
    font-size: 13px;
    border-radius: 8px;
    width: 100%;
    outline: none;
}

.form-container{
    position: absolute;
    top: 0;
    height: 100%;
    transition: all 0.6s ease-in-out;
}

.sign-in{
    left: 0;
    width: 50%;
    z-index: 2;
}

.container.active .sign-in{
    transform: translateX(100%);
}

.sign-up{
    left: 0;
    width: 50%;
    opacity: 0;
    z-index: 1;
}

.container.active .sign-up{
    transform: translateX(100%);
    opacity: 1;
    z-index: 5;
    animation: move 0.6s;
}

@keyframes move{
    0%, 49.99%{
        opacity: 0;
        z-index: 1;
    }
    50%, 100%{
        opacity: 1;
        z-index: 5;
    }
}

.social-icons{
    margin: 20px 0;
}

.social-icons a{
    border: 1px solid #ccc;
    border-radius: 20%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin: 0 3px;
    width: 40px;
    height: 40px;
}

.toggle-container{
    position: absolute;
    top: 0;
    left: 50%;
    width: 50%;
    height: 100%;
    overflow: hidden;
    transition: all 0.6s ease-in-out;
    border-radius: 150px 0 0 100px;
    z-index: 1000;
}

.container.active .toggle-container{
    transform: translateX(-100%);
    border-radius: 0 150px 100px 0;
}

.toggle{
    background-color: #120461;
    height: 100%;
    background: linear-gradient(to right,#120461, #120461);
    color: #fff;
    position: relative;
    left: -100%;
    height: 100%;
    width: 200%;
    transform: translateX(0);
    transition: all 0.6s ease-in-out;
}

.container.active .toggle{
    transform: translateX(50%);
}

.toggle-panel{
    position: absolute;
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 30px;
    text-align: center;
    top: 0;
    transform: translateX(0);
    transition: all 0.6s ease-in-out;
}

.toggle-left{
    transform: translateX(-200%);
}

.container.active .toggle-left{
    transform: translateX(0);
}

.toggle-right{
    right: 0;
    transform: translateX(0);
}

.container.active .toggle-right{
    transform: translateX(200%);
}
</style>