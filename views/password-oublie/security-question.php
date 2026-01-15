<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stud'Home - Question de sécurité</title>
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
                <h1 class="login-title">Question de sécurité</h1>
                <p class="login-subtitle">Répondez à votre question de sécurité pour réinitialiser votre mot de passe</p>
                
                <?php if (isset($flash) && $flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?>">
                        <?= $flash['message'] ?>
                    </div>
                <?php endif; ?>
                
                <form class="login-form" action="<?= APP_URL ?>/verify-security-answer" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="<?= htmlspecialchars($email) ?>" class="form-input" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="security_question">Votre question de sécurité</label>
                        <input type="text" id="security_question" value="<?= htmlspecialchars($security_question) ?>" class="form-input" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="security_answer">Votre réponse</label>
                        <input type="text" id="security_answer" name="security_answer" placeholder="Entrez votre réponse" class="form-input" required autofocus>
                        <small class="form-hint">La réponse n'est pas sensible à la casse</small>
                    </div>
                    
                    <button type="submit" class="btn-submit">Vérifier</button>
                </form>
                
                <div class="form-footer">
                    <a href="<?= APP_URL ?>/forgot-password" class="link">Retour</a>
                </div>
            </div>
        </div>
    </main>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
</body>
</html>