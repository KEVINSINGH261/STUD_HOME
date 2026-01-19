<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stud'Home - Récupération de mot de passe</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/connexion.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>

    <main class="main">
        <div class="hero-section">
            <div class="hero-image">
                <div class="image-overlay"></div>
            </div>
            
            <div class="login-card">
                <h1 class="login-title">Récupération</h1>
                
                <?php if (isset($flash) && $flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?>" style="margin-bottom: 20px; padding: 10px; border-radius: 5px; background: <?= $flash['type'] === 'success' ? '#d4edda' : '#f8d7da' ?>;">
                        <?= $flash['message'] ?>
                    </div>
                <?php endif; ?>
                
                <p style="text-align: center; color: #666; margin-bottom: 25px; font-size: 14px;">
                    Saisissez votre e-mail pour recevoir un lien de réinitialisation.
                </p>

                <form class="login-form" action="<?= APP_URL ?>/forgot-password/submit" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="votre@email.com" class="form-input" required>
                    </div>
                    
                    <button type="submit" class="btn-submit">Envoyer le lien</button>
                </form>
                
                <div class="form-footer">
                    <button class="btn-secondary" style="flex:1; padding:12px; border-radius:8px; border:none; cursor:pointer;" onclick="location.href='<?= APP_URL ?>/login'">Retour</button>
                </div>
            </div>
        </div>
    </main>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
</body>
</html>