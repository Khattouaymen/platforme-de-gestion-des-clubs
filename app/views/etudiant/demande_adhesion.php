<?php
// app/views/etudiant/demande_adhesion.php
// Vue pour le formulaire de demande d'adhésion à un club
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Demande d'adhésion au club <?php echo htmlspecialchars($club['nom']); ?></h2>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger mb-4">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-4">
                        <div class="row align-items-center">
                            <?php if (!empty($club['Logo_URL'])): ?>
                                <div class="col-md-4 text-center">
                                    <img src="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/public/assets/images/clubs/<?php echo htmlspecialchars($club['Logo_URL']); ?>" 
                                         class="img-fluid rounded" style="max-height: 150px;" 
                                         alt="Logo de <?php echo htmlspecialchars($club['nom']); ?>">
                                </div>
                                <div class="col-md-8">
                            <?php else: ?>
                                <div class="col-12">
                            <?php endif; ?>
                                <h5 class="card-title"><?php echo htmlspecialchars($club['nom']); ?></h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($club['description'])); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-info"><?php echo htmlspecialchars($club['nombre_membres']); ?> membres</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form method="post" action="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/demandeAdhesion/<?php echo $club['id']; ?>">
                        <div class="mb-3">
                            <label for="motivation" class="form-label">Pourquoi souhaitez-vous rejoindre ce club?</label>
                            <textarea class="form-control" id="motivation" name="motivation" rows="5" required
                                placeholder="Expliquez votre motivation, vos expériences précédentes, ce que vous espérez apprendre ou apporter au club, etc."
                            ></textarea>
                            <div class="form-text">Cette information sera transmise au responsable du club pour évaluer votre demande d'adhésion.</div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/etudiant/clubs" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour aux clubs
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Envoyer ma demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
