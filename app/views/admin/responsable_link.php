<?php
// Définir le contenu
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Lien d'inscription pour responsable de club</h1>
        <a href="<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Lien généré</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Ce lien est valable pour une seule utilisation. Partagez-le uniquement avec la personne devant devenir responsable de club.
            </div>
            
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="lienInscription" value="<?php echo $lien; ?>" readonly>
                <button class="btn btn-outline-secondary" type="button" id="copyBtn" onclick="copyLink()">
                    <i class="fas fa-copy"></i> Copier
                </button>
            </div>
            
            <div class="d-grid gap-2 mt-3">
                <button class="btn btn-success" onclick="sendEmail()">
                    <i class="fas fa-envelope"></i> Envoyer par email
                </button>
                <button class="btn btn-primary" onclick="generateNewLink()">
                    <i class="fas fa-sync-alt"></i> Générer un nouveau lien
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function copyLink() {
    const lienInput = document.getElementById('lienInscription');
    lienInput.select();
    lienInput.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    const copyBtn = document.getElementById('copyBtn');
    copyBtn.innerHTML = '<i class="fas fa-check"></i> Copié!';
    copyBtn.classList.remove('btn-outline-secondary');
    copyBtn.classList.add('btn-success');
    
    setTimeout(function() {
        copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copier';
        copyBtn.classList.remove('btn-success');
        copyBtn.classList.add('btn-outline-secondary');
    }, 2000);
}

function sendEmail() {
    const lien = document.getElementById('lienInscription').value;
    const subject = "Lien d'inscription - Responsable de club";
    const body = "Bonjour,\n\nVoici le lien pour vous inscrire en tant que responsable de club :\n\n" + lien + "\n\nCe lien est à usage unique.\n\nCordialement,\nL'administration";
    
    window.location.href = "mailto:?subject=" + encodeURIComponent(subject) + "&body=" + encodeURIComponent(body);
}

function generateNewLink() {
    if (confirm('Êtes-vous sûr de vouloir générer un nouveau lien ? Le lien actuel ne sera plus valide.')) {
        window.location.href = "<?php echo isset($asset) ? rtrim(dirname($asset('')), '/') : ''; ?>/admin/generateResponsableLink";
    }
}
</script>


