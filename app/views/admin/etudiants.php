<?php
// Définir le contenu
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestion des Étudiants</h2>
        <a href="/admin" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php if ($_GET['success'] == '1'): ?>
                L'étudiant a été mis à jour avec succès.
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title"><?php echo isset($etudiants) ? count($etudiants) : 0; ?></h5>
                    <p class="card-text">Total étudiants</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php 
                        $profilesComplets = 0;
                        if (isset($etudiants)) {
                            foreach ($etudiants as $etudiant) {
                                if (!empty($etudiant['filiere']) && !empty($etudiant['niveau']) && !empty($etudiant['numero_etudiant'])) {
                                    $profilesComplets++;
                                }
                            }
                        }
                        echo $profilesComplets;
                        ?>
                    </h5>
                    <p class="card-text">Profils complets</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo isset($etudiants) ? (count($etudiants) - $profilesComplets) : 0; ?>
                    </h5>
                    <p class="card-text">Profils incomplets</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php 
                        $responsables = 0;
                        if (isset($etudiants)) {
                            foreach ($etudiants as $etudiant) {
                                if (isset($etudiant['is_future_responsable']) && $etudiant['is_future_responsable'] == 1) {
                                    $responsables++;
                                }
                            }
                        }
                        echo $responsables;
                        ?>
                    </h5>
                    <p class="card-text">Futurs responsables</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres de recherche -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-search"></i> Filtres de recherche</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" id="searchName" class="form-control" placeholder="Rechercher par nom ou prénom...">
                </div>
                <div class="col-md-3">
                    <select id="filterFiliere" class="form-select">
                        <option value="">Toutes les filières</option>
                        <?php 
                        $filieres = [];
                        if (isset($etudiants)) {
                            foreach ($etudiants as $etudiant) {
                                if (!empty($etudiant['filiere']) && !in_array($etudiant['filiere'], $filieres)) {
                                    $filieres[] = $etudiant['filiere'];
                                }
                            }
                            sort($filieres);
                            foreach ($filieres as $filiere): ?>
                                <option value="<?php echo htmlspecialchars($filiere); ?>"><?php echo htmlspecialchars($filiere); ?></option>
                            <?php endforeach;
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterNiveau" class="form-select">
                        <option value="">Tous les niveaux</option>
                        <?php 
                        $niveaux = [];
                        if (isset($etudiants)) {
                            foreach ($etudiants as $etudiant) {
                                if (!empty($etudiant['niveau']) && !in_array($etudiant['niveau'], $niveaux)) {
                                    $niveaux[] = $etudiant['niveau'];
                                }
                            }
                            sort($niveaux);
                            foreach ($niveaux as $niveau): ?>
                                <option value="<?php echo htmlspecialchars($niveau); ?>"><?php echo htmlspecialchars($niveau); ?></option>
                            <?php endforeach;
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="clearFilters" class="btn btn-outline-secondary w-100">Effacer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des étudiants -->
    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle" id="etudiantsTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Filière</th>
                    <th>Niveau</th>
                    <th>Numéro étudiant</th>
                    <th>Statut profil</th>
                    <th>Responsable</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($etudiants) && !empty($etudiants)): ?>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <tr>
                            <td><?php echo $etudiant['id_etudiant']; ?></td>
                            <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['email']); ?></td>
                            <td>
                                <?php if (!empty($etudiant['filiere'])): ?>
                                    <span class="badge bg-info"><?php echo htmlspecialchars($etudiant['filiere']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Non définie</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($etudiant['niveau'])): ?>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($etudiant['niveau']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Non défini</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($etudiant['numero_etudiant'])): ?>
                                    <code><?php echo htmlspecialchars($etudiant['numero_etudiant']); ?></code>
                                <?php else: ?>
                                    <span class="text-muted">Non défini</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $profilComplet = !empty($etudiant['filiere']) && !empty($etudiant['niveau']) && !empty($etudiant['numero_etudiant']);
                                if ($profilComplet): ?>
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Complet</span>
                                <?php else: ?>
                                    <span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Incomplet</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($etudiant['is_future_responsable']) && $etudiant['is_future_responsable'] == 1): ?>
                                    <span class="badge bg-primary"><i class="fas fa-star"></i> Futur responsable</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group-vertical" role="group">
                                    <button type="button" class="btn btn-sm btn-info mb-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewEtudiantModal" 
                                        data-id="<?php echo $etudiant['id_etudiant']; ?>"
                                        onclick="viewEtudiant(<?php echo $etudiant['id_etudiant']; ?>)">
                                        <i class="fas fa-eye"></i> Voir
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning mb-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editEtudiantModal" 
                                        data-id="<?php echo $etudiant['id_etudiant']; ?>"
                                        onclick="editEtudiant(<?php echo $etudiant['id_etudiant']; ?>)">
                                        <i class="fas fa-edit"></i> Modifier
                                    </button>
                                    <?php if (!$profilComplet): ?>
                                        <button type="button" class="btn btn-sm btn-outline-warning"
                                            title="Profil incomplet">
                                            <i class="fas fa-exclamation-triangle"></i> Incomplet
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">Aucun étudiant trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Voir Étudiant -->
<div class="modal fade" id="viewEtudiantModal" tabindex="-1" aria-labelledby="viewEtudiantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEtudiantModalLabel">Détails de l'étudiant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewEtudiantContent">
                <!-- Le contenu sera chargé dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier Étudiant -->
<div class="modal fade" id="editEtudiantModal" tabindex="-1" aria-labelledby="editEtudiantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEtudiantModalLabel">Modifier l'étudiant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editEtudiantForm" method="POST" action="/admin/updateEtudiant">
                <div class="modal-body">
                    <input type="hidden" id="edit_etudiant_id" name="etudiant_id">
                    
                    <div class="mb-3">
                        <label for="edit_nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="edit_nom" name="nom" required readonly>
                        <small class="text-muted">Le nom ne peut pas être modifié par l'administrateur</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="edit_prenom" name="prenom" required readonly>
                        <small class="text-muted">Le prénom ne peut pas être modifié par l'administrateur</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required readonly>
                        <small class="text-muted">L'email ne peut pas être modifié par l'administrateur</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_filiere" class="form-label">Filière</label>
                        <select class="form-select" id="edit_filiere" name="filiere">
                            <option value="">Sélectionner une filière</option>
                            <option value="Informatique">Informatique</option>
                            <option value="Génie Civil">Génie Civil</option>
                            <option value="Génie Électrique">Génie Électrique</option>
                            <option value="Génie Mécanique">Génie Mécanique</option>
                            <option value="Management">Management</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Droit">Droit</option>
                            <option value="Médecine">Médecine</option>
                            <option value="Pharmacie">Pharmacie</option>
                            <option value="Architecture">Architecture</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_niveau" class="form-label">Niveau</label>
                        <select class="form-select" id="edit_niveau" name="niveau">
                            <option value="">Sélectionner un niveau</option>
                            <option value="L1">L1 (Licence 1ère année)</option>
                            <option value="L2">L2 (Licence 2ème année)</option>
                            <option value="L3">L3 (Licence 3ème année)</option>
                            <option value="M1">M1 (Master 1ère année)</option>
                            <option value="M2">M2 (Master 2ème année)</option>
                            <option value="Doctorat">Doctorat</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_numero_etudiant" class="form-label">Numéro d'étudiant</label>
                        <input type="text" class="form-control" id="edit_numero_etudiant" name="numero_etudiant" 
                               placeholder="Ex: 2024001234">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Filtrage et recherche
document.getElementById('searchName').addEventListener('keyup', filterTable);
document.getElementById('filterFiliere').addEventListener('change', filterTable);
document.getElementById('filterNiveau').addEventListener('change', filterTable);
document.getElementById('clearFilters').addEventListener('click', clearFilters);

function filterTable() {
    const searchName = document.getElementById('searchName').value.toLowerCase();
    const filterFiliere = document.getElementById('filterFiliere').value.toLowerCase();
    const filterNiveau = document.getElementById('filterNiveau').value.toLowerCase();
    
    const table = document.getElementById('etudiantsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        if (cells.length > 0) {
            const nom = cells[1].textContent.toLowerCase();
            const prenom = cells[2].textContent.toLowerCase();
            const filiere = cells[4].textContent.toLowerCase();
            const niveau = cells[5].textContent.toLowerCase();
            
            const nameMatch = nom.includes(searchName) || prenom.includes(searchName);
            const filiereMatch = filterFiliere === '' || filiere.includes(filterFiliere);
            const niveauMatch = filterNiveau === '' || niveau.includes(filterNiveau);
            
            if (nameMatch && filiereMatch && niveauMatch) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
}

function clearFilters() {
    document.getElementById('searchName').value = '';
    document.getElementById('filterFiliere').value = '';
    document.getElementById('filterNiveau').value = '';
    filterTable();
}

// Fonctions pour les modals
function viewEtudiant(id) {
    // Dans une implémentation complète, on ferait un appel AJAX pour récupérer les détails
    const content = document.getElementById('viewEtudiantContent');
    content.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>';
    
    // Simuler le chargement des données (à remplacer par un vrai appel AJAX)
    setTimeout(() => {
        content.innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Fonctionnalité en cours de développement.
                <br>ID de l'étudiant: ${id}
            </div>
        `;
    }, 500);
}

function editEtudiant(id) {
    // Récupérer les données de l'étudiant depuis le tableau
    const table = document.getElementById('etudiantsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        if (cells.length > 0 && cells[0].textContent == id) {
            document.getElementById('edit_etudiant_id').value = id;
            document.getElementById('edit_nom').value = cells[1].textContent;
            document.getElementById('edit_prenom').value = cells[2].textContent;
            document.getElementById('edit_email').value = cells[3].textContent;
            
            // Pour la filière et le niveau, on doit extraire le texte des badges
            const filiereText = cells[4].querySelector('.badge') ? cells[4].querySelector('.badge').textContent : '';
            const niveauText = cells[5].querySelector('.badge') ? cells[5].querySelector('.badge').textContent : '';
            const numeroText = cells[6].querySelector('code') ? cells[6].querySelector('code').textContent : '';
            
            document.getElementById('edit_filiere').value = filiereText;
            document.getElementById('edit_niveau').value = niveauText;
            document.getElementById('edit_numero_etudiant').value = numeroText;
            
            break;
        }
    }
}
</script>
