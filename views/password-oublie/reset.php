<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stud'Home - Nouveau mot de passe</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/connexion.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/password-toggle.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>

    <main class="main">
        <div class="hero-section">
            <div class="hero-image">
                <div class="image-overlay"></div>
            </div>
            
            <div class="login-card">
                <h1 class="login-title">Nouveau mot de passe</h1>
                
                <?php if (isset($flash) && $flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?>" style="margin-bottom: 20px; padding: 10px; border-radius: 5px; background: <?= $flash['type'] === 'success' ? '#d4edda' : '#f8d7da' ?>;">
                        <?= $flash['message'] ?>
                    </div>
                <?php endif; ?>
                
                <p style="text-align: center; color: #666; margin-bottom: 25px; font-size: 14px;">
                    Saisissez votre nouveau mot de passe.
                </p>

                <form class="login-form" action="<?= APP_URL ?>/reset-password/submit" method="POST">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    
                    <div class="form-group">
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" class="form-input" required minlength="8">
                        <small style="color: #666; font-size: 12px;">Au moins 8 caractères</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirm">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirm" name="password_confirm" placeholder="••••••••" class="form-input" required minlength="8">
                    </div>
                    
                    <button type="submit" class="btn-submit">Réinitialiser</button>
                </form>
                
                <div class="form-footer">
                    <button class="btn-secondary" style="flex:1; padding:12px; border-radius:8px; border:none; cursor:pointer;" onclick="location.href='<?= APP_URL ?>/login'">Retour à la connexion</button>
                </div>
            </div>
        </div>
    </main>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    <script src="<?= APP_URL ?>/js/password-toggle.js"></script>
</body>
</html>