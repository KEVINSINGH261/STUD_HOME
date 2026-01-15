<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stud'Home - Connexion</title>
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
                <h1 class="login-title">Connectez vous</h1>
                
                <?php if (isset($flash) && $flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?>" style="margin-bottom: 20px; padding: 10px; border-radius: 5px; background: <?= $flash['type'] === 'success' ? '#d4edda' : '#f8d7da' ?>;">
                        <?= $flash['message'] ?>
                    </div>
                <?php endif; ?>
                
                <form class="login-form" action="<?= APP_URL ?>/login/submit" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="votre@email.com" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" class="form-input" required>
                    </div>
                    
                    <button type="submit" class="btn-submit">Se connecter</button>
                    
                    <div class="form-group" style="text-align: right;">
                        <a href="<?= APP_URL ?>/forgot-password" class="forgot-password">Mot de passe oublié ?</a>                   
                    </div>
                </form>
                
                <div class="form-footer">
                    <button class="btn-secondary" onclick="location.href='<?= APP_URL ?>/login'">Sign in</button>
                    <button class="btn-secondary-dark" onclick="location.href='<?= APP_URL ?>/register'">S'inscrire</button>
                </div>
            </div>
        </div>
    </main>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
</body>
</html>
