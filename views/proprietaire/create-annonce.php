<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une annonce - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/annonce-form.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="annonce-form-page">
        <div class="container">
            <h1>Créer une nouvelle annonce</h1>
            
            <?php if (isset($flash) && $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= APP_URL ?>/proprietaire/annonces/store" enctype="multipart/form-data" class="annonce-form">
                
                <!-- Informations générales -->
                <div class="form-section">
                    <h2>Informations générales</h2>
                    
                    <div class="form-group">
                        <label for="titre">Titre de l'annonce *</label>
                        <input type="text" id="titre" name="titre" required placeholder="Ex: Studio lumineux proche métro">
                    </div>

                    <div class="form-group">
                        <label for="type">Type de logement *</label>
                        <select id="type" name="type" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="studio">Studio</option>
                            <option value="appartement">Appartement</option>
                            <option value="maison">Maison</option>
                            <option value="chambre">Chambre</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" rows="6" required placeholder="Décrivez votre logement en détail..."></textarea>
                        <small>Minimum 50 caractères</small>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="form-section">
                    <h2>Localisation</h2>
                    
                    <div class="form-group">
                        <label for="adresse">Adresse *</label>
                        <input type="text" id="adresse" name="adresse" required placeholder="12 rue de la Paix">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ville">Ville *</label>
                            <input type="text" id="ville" name="ville" required placeholder="Paris">
                        </div>

                        <div class="form-group">
                            <label for="code_postal">Code postal *</label>
                            <input type="text" id="code_postal" name="code_postal" required placeholder="75002" pattern="[0-9]{5}">
                        </div>
                    </div>
                </div>

                <!-- Caractéristiques -->
                <div class="form-section">
                    <h2>Caractéristiques</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="surface">Surface (m²) *</label>
                            <input type="number" id="surface" name="surface" required min="1" placeholder="25">
                        </div>

                        <div class="form-group">
                            <label for="chambres">Nombre de chambres *</label>
                            <input type="number" id="chambres" name="chambres" required min="0" max="10" placeholder="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prix">Loyer mensuel (€) *</label>
                        <input type="number" id="prix" name="prix" required min="0" step="0.01" placeholder="650">
                        <small>Charges comprises ou non comprises</small>
                    </div>
                </div>

                <!-- Photo -->
                <div class="form-section">
                    <h2>Photos du logement</h2>
                    
                    <div class="form-group">
                        <label for="photos">Photos (plusieurs possibles)</label>
                        <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
                        <small>Formats acceptés : JPG, PNG, GIF (max 5Mo par image). Vous pouvez sélectionner plusieurs images.</small>
                    </div>

                    <div id="preview-container" class="preview-container" style="display: none;">
                        <p>Aperçu :</p>
                        <img id="preview-image" src="" alt="Aperçu">
                    </div>
                </div>

                <!-- Statut -->
                <div class="form-section">
                    <h2>Publication</h2>
                    
                    <div class="form-group">
                        <label for="statut">Statut de l'annonce *</label>
                        <select id="statut" name="statut" required>
                            <option value="active">Publier immédiatement</option>
                            <option value="inactive">Enregistrer en brouillon</option>
                        </select>
                        <small>Les annonces publiées seront visibles par les étudiants</small>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Créer l'annonce</button>
                    <a href="<?= APP_URL ?>/proprietaire/annonces" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script>
        // Aperçu de l'image
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-container').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        // Validation du formulaire
        document.querySelector('.annonce-form').addEventListener('submit', function(e) {
            const description = document.getElementById('description').value;
            if (description.length < 50) {
                e.preventDefault();
                alert('La description doit contenir au moins 50 caractères.');
                return false;
            }
        });
    </script>
</body>
</html>