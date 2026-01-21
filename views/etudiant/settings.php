<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres du compte - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/settings.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="profile-page">
        <div class="container">
            <div class="profile-header">
                <h1>Paramètres du compte</h1>
                <a href="<?= APP_URL ?>/etudiant/dashboard" class="btn-secondary">← Retour au dashboard</a>
            </div>
            
            <?php if (isset($flash) && $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>
            
            <div class="profile-content">
                <!-- Changement de mot de passe -->
                <div class="form-section">
                    <h2>Changer le mot de passe</h2>
                    <form method="POST" action="<?= APP_URL ?>/etudiant/settings/update" class="profile-form">
                        <div class="form-group">
                            <label for="current_password">Mot de passe actuel *</label>
                            <input type="password" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">Nouveau mot de passe *</label>
                            <input type="password" id="new_password" name="new_password" required>
                            <small>Minimum 8 caractères, dont une majuscule, une minuscule et un chiffre</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirmer le nouveau mot de passe *</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn-primary">Mettre à jour le mot de passe</button>
                    </form>
                </div>
                
                <!-- Préférences de notifications (optionnel pour le futur) -->
                <div class="info-section">
                    <h2>Préférences</h2>
                    <div class="preference-item">
                        <div>
                            <strong>Notifications par email</strong>
                            <p style="margin: 0.25rem 0 0 0; color: #666; font-size: 0.9rem;">Recevoir des notifications pour les nouvelles annonces</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" disabled>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p style="margin-top: 1rem; color: #666; font-size: 0.9rem;"><em>Fonctionnalité à venir...</em></p>
                </div>
                
                <!-- Suppression du compte -->
                <div class="danger-zone">
                    <h2>Suppression du compte</h2>
                    <p style="color: #666; margin-bottom: 1rem;">La suppression de votre compte est irréversible. Toutes vos données seront perdues.</p>
                    <button class="btn-danger" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')) alert('Fonctionnalité à venir...');">
                        Supprimer mon compte
                    </button>
                </div>
            </div>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
</body>
</html>
