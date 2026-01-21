<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/settings.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="profile-page">
        <div class="container">
            <div class="profile-header">
                <h1>Mon profil</h1>
                <a href="<?= APP_URL ?>/etudiant/dashboard" class="btn-secondary">← Retour au dashboard</a>
            </div>
            
            <?php if (isset($flash) && $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>
            
            <div class="profile-content">
                <!-- Informations personnelles -->
                <div class="form-section">
                    <h2>Informations personnelles</h2>
                    <form action="<?= APP_URL ?>/etudiant/profile/update" method="POST" class="profile-form">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($etudiant['nom'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($etudiant['prenom'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($etudiant['email'] ?? '') ?>" required>
                            <small>L'email est utilisé pour vous connecter</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="ecole">École / Université</label>
                            <input type="text" id="ecole" name="ecole" value="<?= htmlspecialchars($etudiant['ecole'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="telephone">Téléphone (optionnel)</label>
                            <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($etudiant['telephone'] ?? '') ?>">
                        </div>
                        
                        <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                    </form>
                </div>
                
                <!-- Informations du compte -->
                <div class="info-section">
                    <h2>Informations du compte</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Type de compte</span>
                            <span class="info-value">Étudiant</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date d'inscription</span>
                            <span class="info-value"><?= date('d/m/Y', strtotime($etudiant['date_inscription'] ?? $etudiant['created_at'] ?? 'now')) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Statut</span>
                            <span class="info-value">Actif</span>
                        </div>
                    </div>
                </div>
                
                <!-- Lien vers les paramètres -->
                <div class="info-section">
                    <h2>Sécurité</h2>
                    <p style="margin-bottom: 1rem; color: var(--secondary-color);">Pour modifier votre mot de passe, rendez-vous dans les paramètres du compte.</p>
                    <a href="<?= APP_URL ?>/etudiant/settings" class="btn-primary">Accéder aux paramètres</a>
                </div>
            </div>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
</body>
</html>
