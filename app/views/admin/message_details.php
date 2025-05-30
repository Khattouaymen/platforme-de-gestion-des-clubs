<?php
$pageTitle = 'Détails du Message';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Bouton de retour -->
            <div class="mb-3">
                <a href="/admin/messages" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                </a>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-envelope-open me-2"></i> Détails du Message
                    </h5>
                    <div class="d-flex gap-2">
                        <?php
                        $statusClass = [
                            'non_lu' => 'bg-warning',
                            'lu' => 'bg-info',
                            'traite' => 'bg-success'
                        ];
                        $statusText = [
                            'non_lu' => 'Non lu',
                            'lu' => 'Lu',
                            'traite' => 'Traité'
                        ];
                        ?>
                        <span class="badge <?php echo $statusClass[$message['statut']]; ?>">
                            <?php echo $statusText[$message['statut']]; ?>
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Informations de l'expéditeur -->
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-user me-2"></i> Informations de l'expéditeur
                                    </h6>
                                    
                                    <div class="mb-2">
                                        <strong>Nom :</strong><br>
                                        <?php echo htmlspecialchars($message['nom']); ?>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>Email :</strong><br>
                                        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
                                            <?php echo htmlspecialchars($message['email']); ?>
                                        </a>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>Sujet :</strong><br>
                                        <?php
                                        $sujets = [
                                            'information' => 'Demande d\'information',
                                            'adhesion' => 'Question sur l\'adhésion',
                                            'activite' => 'Question sur les activités',
                                            'technique' => 'Problème technique',
                                            'suggestion' => 'Suggestion d\'amélioration',
                                            'autre' => 'Autre'
                                        ];
                                        echo $sujets[$message['sujet']] ?? $message['sujet'];
                                        ?>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>Date d'envoi :</strong><br>
                                        <?php echo date('d/m/Y à H:i', strtotime($message['date_creation'])); ?>
                                    </div>
                                    
                                    <?php if ($message['date_lecture']): ?>
                                        <div class="mb-2">
                                            <strong>Lu le :</strong><br>
                                            <?php echo date('d/m/Y à H:i', strtotime($message['date_lecture'])); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($message['date_traitement']): ?>
                                        <div class="mb-2">
                                            <strong>Traité le :</strong><br>
                                            <?php echo date('d/m/Y à H:i', strtotime($message['date_traitement'])); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contenu du message -->
                        <div class="col-md-8">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-comment me-2"></i> Message
                                    </h6>
                                    
                                    <div class="message-content p-3 bg-white rounded border">
                                        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-tools me-2"></i> Actions
                                    </h6>
                                    
                                    <div class="d-flex gap-2 flex-wrap">
                                        <!-- Répondre par email -->
                                        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo urlencode($sujets[$message['sujet']] ?? $message['sujet']); ?>&body=Bonjour <?php echo urlencode($message['nom']); ?>,%0D%0A%0D%0ANous avons bien reçu votre message concernant: <?php echo urlencode($sujets[$message['sujet']] ?? $message['sujet']); ?>%0D%0A%0D%0A" 
                                           class="btn btn-primary">
                                            <i class="fas fa-reply me-2"></i> Répondre par email
                                        </a>
                                        
                                        <?php if ($message['statut'] !== 'traite'): ?>
                                            <a href="/admin/markMessageProcessed/<?php echo $message['id']; ?>" 
                                               class="btn btn-success"
                                               onclick="return confirm('Marquer ce message comme traité ?')">
                                                <i class="fas fa-check me-2"></i> Marquer comme traité
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="/admin/deleteMessage/<?php echo $message['id']; ?>" 
                                           class="btn btn-danger"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                            <i class="fas fa-trash me-2"></i> Supprimer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message-content {
    max-height: 400px;
    overflow-y: auto;
    line-height: 1.6;
    font-size: 0.95rem;
}
</style>
