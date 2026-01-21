<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'annonce - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/annonce-form.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="annonce-form-page">
        <div class="container">
            <h1>Modifier l'annonce</h1>
            
            <?php if (isset($flash) && $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= APP_URL ?>/proprietaire/annonces/update/<?= $annonce['id'] ?>" enctype="multipart/form-data" class="annonce-form">
                
                <!-- Informations générales -->
                <div class="form-section">
                    <h2>Informations générales</h2>
                    
                    <div class="form-group">
                        <label for="titre">Titre de l'annonce *</label>
                        <input type="text" id="titre" name="titre" required placeholder="Ex: Studio lumineux proche métro" value="<?= htmlspecialchars($annonce['titre']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="type">Type de logement *</label>
                        <select id="type" name="type" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="studio" <?= $annonce['type'] === 'studio' ? 'selected' : '' ?>>Studio</option>
                            <option value="appartement" <?= $annonce['type'] === 'appartement' ? 'selected' : '' ?>>Appartement</option>
                            <option value="maison" <?= $annonce['type'] === 'maison' ? 'selected' : '' ?>>Maison</option>
                            <option value="chambre" <?= $annonce['type'] === 'chambre' ? 'selected' : '' ?>>Chambre</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" rows="6" required placeholder="Décrivez votre logement en détail..."><?= htmlspecialchars($annonce['description']) ?></textarea>
                        <small>Minimum 50 caractères</small>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="form-section">
                    <h2>Localisation</h2>
                    
                    <div class="form-group">
                        <label for="adresse">Adresse *</label>
                        <input type="text" id="adresse" name="adresse" required placeholder="12 rue de la Paix" value="<?= htmlspecialchars($annonce['adresse']) ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ville">Ville *</label>
                            <input type="text" id="ville" name="ville" required placeholder="Paris" value="<?= htmlspecialchars($annonce['ville']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="code_postal">Code postal *</label>
                            <input type="text" id="code_postal" name="code_postal" required placeholder="75002" pattern="[0-9]{5}" value="<?= htmlspecialchars($annonce['code_postal']) ?>">
                        </div>
                    </div>
                </div>

                <!-- Caractéristiques -->
                <div class="form-section">
                    <h2>Caractéristiques</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="surface">Surface (m²) *</label>
                            <input type="number" id="surface" name="surface" required min="1" placeholder="25" value="<?= htmlspecialchars($annonce['surface']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="chambres">Nombre de chambres *</label>
                            <input type="number" id="chambres" name="chambres" required min="0" max="10" placeholder="1" value="<?= htmlspecialchars($annonce['nombre_chambres']) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prix">Loyer mensuel (€) *</label>
                        <input type="number" id="prix" name="prix" required min="0" step="0.01" placeholder="650" value="<?= htmlspecialchars($annonce['prix']) ?>">
                        <small>Charges comprises ou non comprises</small>
                    </div>
                </div>

                <!-- Photos existantes -->
                <?php if (!empty($annonce['photo'])): ?>
                <div class="form-section">
                    <h2>Photo actuelle</h2>
                    <div class="current-photo">
                        <img src="<?= APP_URL ?>/uploads/annonces/<?= htmlspecialchars($annonce['photo']) ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>" style="max-width: 300px; border-radius: 8px;">
                    </div>
                </div>
                <?php endif; ?>

                <!-- Nouvelles photos -->
                <div class="form-section">
                    <h2>Ajouter de nouvelles photos</h2>
                    
                    <div class="form-group">
                        <label for="photos">Photos (plusieurs possibles)</label>
                        <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
                        <small>Formats acceptés : JPG, PNG, GIF (max 5Mo par image). Vous pouvez sélectionner plusieurs images.</small>
                    </div>

                    <div id="preview-container" class="preview-container" style="display: none;">
                        <p>Aperçu :</p>
                        <div id="preview-images"></div>
                    </div>
                </div>

                <!-- Statut -->
                <div class="form-section">
                    <h2>Publication</h2>
                    
                    <div class="form-group">
                        <label for="statut">Statut de l'annonce *</label>
                        <select id="statut" name="statut" required>
                            <option value="active" <?= $annonce['statut'] === 'active' ? 'selected' : '' ?>>Publier immédiatement</option>
                            <option value="inactive" <?= $annonce['statut'] === 'inactive' ? 'selected' : '' ?>>Enregistrer en brouillon</option>
                        </select>
                        <small>Les annonces publiées seront visibles par les étudiants</small>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                    <a href="<?= APP_URL ?>/proprietaire/annonces" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script>
        // Aperçu des images multiples
        document.getElementById('photos').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('preview-images');
            previewContainer.innerHTML = '';
            
            if (files.length > 0) {
                document.getElementById('preview-container').style.display = 'block';
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '200px';
                        img.style.margin = '10px';
                        img.style.borderRadius = '8px';
                        previewContainer.appendChild(img);
                    }
                    
                    reader.readAsDataURL(file);
                }
            } else {
                document.getElementById('preview-container').style.display = 'none';
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
