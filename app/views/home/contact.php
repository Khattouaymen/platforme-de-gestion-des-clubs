<?php
// Le layout de base sera utilisé ici
?>

<!-- Hero Section -->
<section class="hero text-center py-5 bg-primary text-white">
    <div class="container">
        <h1 class="display-4 fw-bold">Contactez-nous</h1>
        <p class="lead">Nous sommes là pour vous aider et répondre à vos questions</p>
    </div>
</section>

<!-- Section Contact -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="row">
                    <!-- Informations de contact -->
                    <div class="col-md-6 mb-4">
                        <h3 class="mb-4">Informations de contact</h3>
                        
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt fa-lg text-primary"></i>
                            </div>                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Adresse</h6>
                                <p class="text-muted mb-0">
                                    OFPPT ISTA Guelmim<br>
                                    Institut Spécialisé de Technologie Appliquée<br>
                                    Guelmim, Maroc
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-phone fa-lg text-primary"></i>
                            </div>                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Téléphone</h6>
                                <p class="text-muted mb-0">+212 528 87 20 45</p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-envelope fa-lg text-primary"></i>
                            </div>                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Email</h6>
                                <p class="text-muted mb-0">clubs@ista-guelmim.ma</p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock fa-lg text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Horaires d'ouverture</h6>
                                <p class="text-muted mb-0">
                                    Lundi - Vendredi: 8h00 - 18h00<br>
                                    Samedi: 9h00 - 16h00
                                </p>
                            </div>
                        </div>
                    </div>
                      <!-- Formulaire de contact -->
                    <div class="col-md-6">
                        <h3 class="mb-4">Envoyez-nous un message</h3>
                        
                        <?php if (isset($success) && $success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($success); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($error) && $error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?php echo url('contact'); ?>">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom complet *</label>
                                <input type="text" class="form-control" id="nom" name="nom" 
                                       value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="sujet" class="form-label">Sujet *</label>
                                <select class="form-select" id="sujet" name="sujet" required>
                                    <option value="">Choisissez un sujet</option>
                                    <option value="information" <?php echo (($_POST['sujet'] ?? '') === 'information') ? 'selected' : ''; ?>>Demande d'information</option>
                                    <option value="adhesion" <?php echo (($_POST['sujet'] ?? '') === 'adhesion') ? 'selected' : ''; ?>>Question sur l'adhésion</option>
                                    <option value="activite" <?php echo (($_POST['sujet'] ?? '') === 'activite') ? 'selected' : ''; ?>>Question sur les activités</option>
                                    <option value="technique" <?php echo (($_POST['sujet'] ?? '') === 'technique') ? 'selected' : ''; ?>>Problème technique</option>
                                    <option value="suggestion" <?php echo (($_POST['sujet'] ?? '') === 'suggestion') ? 'selected' : ''; ?>>Suggestion d'amélioration</option>
                                    <option value="autre" <?php echo (($_POST['sujet'] ?? '') === 'autre') ? 'selected' : ''; ?>>Autre</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required 
                                          placeholder="Décrivez votre demande ou question..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i> Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section FAQ -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-center mb-5">Questions Fréquemment Posées</h2>
                
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                Comment puis-je créer un nouveau club ?
                            </button>
                        </h2>
                        <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Pour créer un nouveau club, vous devez d'abord vous inscrire sur la plateforme en tant qu'étudiant, puis contacter l'administration pour soumettre votre demande de création de club avec les documents nécessaires.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                Comment rejoindre un club existant ?
                            </button>
                        </h2>
                        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Vous pouvez parcourir la liste des clubs disponibles, consulter leurs détails et faire une demande d'adhésion directement via la plateforme. Le responsable du club examinera votre demande.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                Puis-je participer aux activités sans être membre ?
                            </button>
                        </h2>
                        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Cela dépend de l'activité et des règles du club. Certaines activités sont ouvertes à tous les étudiants, tandis que d'autres sont réservées aux membres du club. Consultez les détails de chaque activité.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                                Comment devenir responsable d'un club ?
                            </button>
                        </h2>
                        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Les responsables de clubs sont généralement élus par les membres ou nommés selon les statuts du club. Contactez l'administration ou le responsable actuel du club pour plus d'informations sur le processus.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Support -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-4">Besoin d'aide supplémentaire ?</h2>
                <p class="lead mb-4">Notre équipe est disponible pour vous accompagner dans l'utilisation de la plateforme.</p>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-question-circle fa-3x text-info mb-3"></i>
                                <h5>Support Technique</h5>
                                <p class="card-text">Problèmes de connexion, bugs ou questions techniques</p>
                                <a href="mailto:support@ista-guelmim.ma" class="btn btn-outline-info">Contacter le support</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x text-success mb-3"></i>
                                <h5>Administration</h5>
                                <p class="card-text">Questions sur les clubs, demandes spéciales</p>
                                <a href="mailto:clubs@ista-guelmim.ma" class="btn btn-outline-success">Contacter l'admin</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm h-100">                            <div class="card-body text-center">
                                <i class="fas fa-graduation-cap fa-3x text-warning mb-3"></i>
                                <h5>Vie Étudiante</h5>
                                <p class="card-text">Questions générales sur la vie associative</p>
                                <a href="mailto:clubs@ista-guelmim.ma" class="btn btn-outline-warning">Nous écrire</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
