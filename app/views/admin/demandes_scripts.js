// Script pour gérer les modals de demande d'activité
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du modal de visualisation des détails d'une demande d'activité
    const viewActiviteButtons = document.querySelectorAll('[data-bs-target="#viewActiviteDemandeModal"]');
    
    if (viewActiviteButtons) {
        viewActiviteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                // Charger les détails de l'activité via AJAX
                fetch(baseAssetUrl + '/admin/demandes/getDemandeActivite/' + id)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.demande) {
                            const demande = data.demande;
                            
                            // Remplir les détails dans le modal
                            document.getElementById('activite-titre').textContent = demande.nom_activite || 'Sans titre';
                            document.getElementById('activite-description').textContent = demande.description || 'Aucune description';
                            
                            // Gérer l'affichage des dates (formats ancien et nouveau)
                            if (demande.date_debut && demande.date_fin) {
                                document.getElementById('activite-date').textContent = 
                                    `Du ${new Date(demande.date_debut).toLocaleString()} au ${new Date(demande.date_fin).toLocaleString()}`;
                            } else if (demande.date_activite) {
                                document.getElementById('activite-date').textContent = new Date(demande.date_activite).toLocaleDateString();
                            } else {
                                document.getElementById('activite-date').textContent = 'Date non spécifiée';
                            }
                            
                            // Lieu
                            document.getElementById('activite-lieu').textContent = demande.lieu || 'Non spécifié';
                            
                            // Club
                            document.getElementById('activite-club').textContent = demande.club_nom || 'Non spécifié';
                            
                            // Statut
                            let statutBadge = '';
                            if (demande.statut === 'approuvee') {
                                statutBadge = '<span class="badge bg-success">Approuvée</span>';
                            } else if (demande.statut === 'refusee') {
                                statutBadge = '<span class="badge bg-danger">Refusée</span>';
                            } else {
                                statutBadge = '<span class="badge bg-warning">En attente</span>';
                            }
                            document.getElementById('activite-statut').innerHTML = statutBadge;
                            
                            // Commentaire (s'il existe)
                            const commentaireContainer = document.getElementById('activite-commentaire-container');
                            if (demande.commentaire) {
                                document.getElementById('activite-commentaire').textContent = demande.commentaire;
                                commentaireContainer.style.display = 'block';
                            } else {
                                commentaireContainer.style.display = 'none';
                            }
                            
                            // Afficher/cacher les boutons d'action en fonction du statut
                            const actionButtons = document.getElementById('activite-actions');
                            const approveBtn = document.getElementById('approveActiviteBtn');
                            const rejectBtn = document.getElementById('rejectActiviteBtn');
                            
                            if (demande.statut === 'en_attente' || !demande.statut) {
                                // Activité en attente - afficher les boutons d'action
                                approveBtn.style.display = 'inline-block';
                                rejectBtn.style.display = 'inline-block';
                                approveBtn.href = baseAssetUrl + '/admin/demandes/approveActivite/' + id;
                                rejectBtn.setAttribute('data-bs-toggle', 'modal');
                                rejectBtn.setAttribute('data-bs-target', '#rejectActiviteModal');
                                rejectBtn.setAttribute('data-id', id);
                            } else {
                                // Activité déjà traitée - masquer les boutons d'action
                                approveBtn.style.display = 'none';
                                rejectBtn.style.display = 'none';
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    }
});
