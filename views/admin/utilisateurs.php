<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stud'Home - Administration</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/admin.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>

    <main class="main">
        <div class="admin-container">
            <div class="admin-header">
                <h1>Gestion des utilisateurs</h1>
                <button class="btn-primary" onclick="openAddUserModal()">
                    <span>+</span> Ajouter un utilisateur
                </button>
            </div>

            <?php if (isset($flash) && $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>

            <!-- Filtres -->
            <div class="filters-section">
                <div class="filter-group">
                    <label for="filter-type">Type :</label>
                    <select id="filter-type" class="filter-select">
                        <option value="all">Tous</option>
                        <option value="etudiant">Étudiants</option>
                        <option value="proprietaire">Propriétaires</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <input type="text" id="search-input" class="search-input" placeholder="Rechercher par nom, prénom ou email...">
                </div>
            </div>

            <!-- Tableau des utilisateurs -->
            <div class="table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>École/Téléphone</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody">
                        <?php if (isset($users) && !empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr data-type="<?= htmlspecialchars($user['type']) ?>" data-user-id="<?= $user['id'] ?>">
                                    <td><?= htmlspecialchars($user['id']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $user['type'] === 'etudiant' ? 'student' : 'owner' ?>">
                                            <?= $user['type'] === 'etudiant' ? 'Étudiant' : 'Propriétaire' ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($user['nom']) ?></td>
                                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <?= $user['type'] === 'etudiant' 
                                            ? htmlspecialchars($user['ecole'] ?? '-') 
                                            : htmlspecialchars($user['telephone'] ?? '-') ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <button class="btn-icon btn-delete" 
                                                onclick="confirmDelete(<?= $user['id'] ?>, '<?= htmlspecialchars($user['nom']) ?> <?= htmlspecialchars($user['prenom']) ?>')"
                                                title="Supprimer">
                                            🗑️
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="no-data">Aucun utilisateur trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal d'ajout d'utilisateur -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ajouter un utilisateur</h2>
                <button class="modal-close" onclick="closeAddUserModal()">&times;</button>
            </div>
            
            <form class="modal-form" action="<?= APP_URL ?>/register/submit" method="POST">
                <div class="role-selection">
                    <label class="role-label">Type d'utilisateur :</label>
                    <div class="role-options">
                        <input type="radio" id="modal-student" name="type" value="etudiant" class="role-input" checked>
                        <label for="modal-student" class="role-button">Étudiant</label>

                        <input type="radio" id="modal-owner" name="type" value="proprietaire" class="role-input">
                        <label for="modal-owner" class="role-button">Propriétaire</label>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="modal-nom">Nom *</label>
                        <input type="text" id="modal-nom" name="nom" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal-prenom">Prénom *</label>
                        <input type="text" id="modal-prenom" name="prenom" class="form-input" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="modal-email">Email *</label>
                    <input type="email" id="modal-email" name="email" class="form-input" required>
                </div>

                <div class="security-section">
                    <div class="form-group">
                        <label for="modal-security-question">Question de sécurité *</label>
                        <select name="security_question" id="modal-security-question" class="form-select" required>
                            <option value="" disabled selected>Choisissez une question...</option>
                            <option value="Quel est le nom de votre premier animal ?">Quel est le nom de votre premier animal ?</option>
                            <option value="Quelle est votre ville de naissance ?">Quelle est votre ville de naissance ?</option>
                            <option value="Quel était le nom de votre école primaire ?">Quel était le nom de votre école primaire ?</option>
                            <option value="Quelle est la marque de votre première voiture ?">Quelle est la marque de votre première voiture ?</option>
                            <option value="Quel est le nom de jeune fille de votre mère ?">Quel est le nom de jeune fille de votre mère ?</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal-security-answer">Réponse secrète *</label>
                        <input type="text" id="modal-security-answer" name="security_answer" class="form-input" required>
                    </div>
                </div>
                
                <div class="form-group" id="modal-ecole-field">
                    <label for="modal-ecole">École</label>
                    <input type="text" id="modal-ecole" name="ecole" class="form-input">
                </div>
                
                <div class="form-group" id="modal-telephone-field" style="display:none;">
                    <label for="modal-telephone">Téléphone</label>
                    <input type="tel" id="modal-telephone" name="telephone" class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="modal-password">Mot de passe *</label>
                    <input type="password" id="modal-password" name="password" placeholder="Minimum 8 caractères" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="modal-password-confirm">Confirmer le mot de passe *</label>
                    <input type="password" id="modal-password-confirm" name="password_confirm" class="form-input" required>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeAddUserModal()">Annuler</button>
                    <button type="submit" class="btn-primary">Ajouter l'utilisateur</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div id="deleteModal" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h2>Confirmer la suppression</h2>
                <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="delete-user-name"></strong> ?</p>
                <p class="warning-text">Cette action est irréversible.</p>
            </div>
            
            <form id="deleteForm" method="POST">
                <input type="hidden" name="user_id" id="delete-user-id">
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Annuler</button>
                    <button type="submit" class="btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script>
        // Gestion du modal d'ajout
        function openAddUserModal() {
            document.getElementById('addUserModal').style.display = 'flex';
        }
        
        function closeAddUserModal() {
            document.getElementById('addUserModal').style.display = 'none';
            document.querySelector('.modal-form').reset();
        }
        
        // Gestion du modal de suppression
        function confirmDelete(userId, userName) {
            document.getElementById('delete-user-id').value = userId;
            document.getElementById('delete-user-name').textContent = userName;
            // Définir l'action du formulaire avec l'ID dans l'URL
            document.getElementById('deleteForm').action = '<?= APP_URL ?>/admin/utilisateurs/delete/' + userId;
            document.getElementById('deleteModal').style.display = 'flex';
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Fermer les modals en cliquant en dehors
        window.onclick = function(event) {
            const addModal = document.getElementById('addUserModal');
            const deleteModal = document.getElementById('deleteModal');
            if (event.target === addModal) {
                closeAddUserModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }
        
        // Gestion des champs selon le type d'utilisateur
        const modalStudent = document.getElementById('modal-student');
        const modalOwner = document.getElementById('modal-owner');
        const modalEcoleField = document.getElementById('modal-ecole-field');
        const modalTelephoneField = document.getElementById('modal-telephone-field');
        
        modalStudent.addEventListener('change', function() {
            if(this.checked) {
                modalEcoleField.style.display = 'block';
                modalTelephoneField.style.display = 'none';
            }
        });
        
        modalOwner.addEventListener('change', function() {
            if(this.checked) {
                modalEcoleField.style.display = 'none';
                modalTelephoneField.style.display = 'block';
            }
        });
        
        // Filtrage par type
        const filterType = document.getElementById('filter-type');
        filterType.addEventListener('change', function() {
            const rows = document.querySelectorAll('#users-tbody tr[data-type]');
            const selectedType = this.value;
            
            rows.forEach(row => {
                if (selectedType === 'all' || row.dataset.type === selectedType) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Recherche en temps réel
        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#users-tbody tr[data-type]');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>