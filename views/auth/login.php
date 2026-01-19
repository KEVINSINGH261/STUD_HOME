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
                
                <form class="login-form" id="loginForm" action="<?= APP_URL ?>/login/submit" method="POST" onsubmit="return validateLoginForm(event)">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="votre@email.com" class="form-input" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES) ?>" required>
                        <span class="error-message" id="emailError"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" class="form-input" required>
                        <span class="error-message" id="passwordError"></span>
                    </div>
                    
                    <button type="submit" class="btn-submit">Se connecter</button>
                    
                    <div class="form-group" style="text-align: center;">
                        <a href="<?= APP_URL ?>/forgot-password" class="forgot-password">Mot de passe oublié ?</a>                   
                    </div>
                </form>
                
                <div class="form-footer">
                    <button type="button" class="btn-submit" onclick="location.href='<?= APP_URL ?>/register'" style="width: 100%; background-color: #FF6B6B; color: white;">S'inscrire</button>
                </div>
            </div>
        </div>
    </main>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script>
        /**
         * Validation du formulaire de connexion côté client
         */
        function validateLoginForm(event) {
            event.preventDefault();
            
            // Réinitialiser les messages d'erreur
            clearErrorMessages();
            
            // Récupérer les valeurs
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            let isValid = true;
            
            // Validation de l'email
            if (!email) {
                showError('emailError', 'L\'email est obligatoire.');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError('emailError', 'Veuillez entrer un email valide.');
                isValid = false;
            }
            
            // Validation du mot de passe
            if (!password) {
                showError('passwordError', 'Le mot de passe est obligatoire.');
                isValid = false;
            } else if (password.length < 8) {
                showError('passwordError', 'Le mot de passe doit contenir au moins 8 caractères.');
                isValid = false;
            }
            
            // Si valide, soumettre le formulaire
            if (isValid) {
                document.getElementById('loginForm').submit();
            }
            
            return false;
        }
        
        /**
         * Vérifier si l'email est valide
         */
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        /**
         * Afficher un message d'erreur
         */
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
                errorElement.style.color = '#dc3545';
                errorElement.style.fontSize = '12px';
                errorElement.style.marginTop = '5px';
            }
        }
        
        /**
         * Réinitialiser tous les messages d'erreur
         */
        function clearErrorMessages() {
            const errorElements = document.querySelectorAll('.error-message');
            errorElements.forEach(element => {
                element.textContent = '';
                element.style.display = 'none';
            });
        }
        
        /**
         * Validation en temps réel lors de la saisie
         */
        document.getElementById('email').addEventListener('blur', function() {
            if (this.value.trim() && !isValidEmail(this.value.trim())) {
                showError('emailError', 'Veuillez entrer un email valide.');
            } else {
                document.getElementById('emailError').style.display = 'none';
            }
        });
        
        document.getElementById('password').addEventListener('blur', function() {
            if (this.value && this.value.length < 8) {
                showError('passwordError', 'Le mot de passe doit contenir au moins 8 caractères.');
            } else {
                document.getElementById('passwordError').style.display = 'none';
            }
        });
        
        /**
         * Nettoyer les erreurs quand l'utilisateur commence à taper
         */
        document.getElementById('email').addEventListener('input', function() {
            if (document.getElementById('emailError').textContent) {
                document.getElementById('emailError').style.display = 'none';
            }
        });
        
        document.getElementById('password').addEventListener('input', function() {
            if (document.getElementById('passwordError').textContent) {
                document.getElementById('passwordError').style.display = 'none';
            }
        });
    </script>
</body>
</html>
