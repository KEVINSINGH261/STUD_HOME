<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stud'Home - Inscription</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/register.css">
</head>
<body>
     <?php include VIEWS_PATH . '/partials/header.php'; ?>

    <main class="main">
        <div class="hero-section">
            <div class="hero-image">
                <div class="image-overlay"></div>
            </div>
            
            <div class="login-card register-card">
                <h1 class="login-title">Créez votre compte</h1>
                
                <?php if (isset($flash) && $flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?>">
                        <?= $flash['message'] ?>
                    </div>
                <?php endif; ?>
                
                <form class="login-form" action="<?= APP_URL ?>/register/submit" method="POST">
                    <div class="role-selection">
                        <label class="role-label">Vous êtes :</label>
                        <div class="role-options">
                            <input type="radio" id="student" name="type" value="etudiant" class="role-input" checked>
                            <label for="student" class="role-button">Étudiant</label>

                            <input type="radio" id="owner" name="type" value="proprietaire" class="role-input">
                            <label for="owner" class="role-button">Propriétaire</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" placeholder="Votre nom" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="votre@email.com" class="form-input" required>
                    </div>

                    <div class="security-section">
                        <div class="form-group">
                            <label for="security_question">Question de sécurité</label>
                            <select name="security_question" id="security_question" class="form-select" required>
                                <option value="" disabled selected>Choisissez une question...</option>
                                <option value="Quel est le nom de votre premier animal ?">Quel est le nom de votre premier animal ?</option>
                                <option value="Quelle est votre ville de naissance ?">Quelle est votre ville de naissance ?</option>
                                <option value="Quel était le nom de votre école primaire ?">Quel était le nom de votre école primaire ?</option>
                                <option value="Quelle est la marque de votre première voiture ?">Quelle est la marque de votre première voiture ?</option>
                                <option value="Quel est le nom de jeune fille de votre mère ?">Quel est le nom de jeune fille de votre mère ?</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="security_answer">Votre réponse secrète</label>
                            <input type="text" id="security_answer" name="security_answer" placeholder="Votre réponse" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="form-group" id="ecole-field">
                        <label for="ecole">École</label>
                        <input type="text" id="ecole" name="ecole" placeholder="Votre école" class="form-input">
                    </div>
                    
                    <div class="form-group" id="telephone-field" style="display:none;">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="Minimum 8 caractères" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirm">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirm" name="password_confirm" placeholder="Répétez le mot de passe" class="form-input" required>
                    </div>
                    
                    <button type="submit" class="btn-submit">S'inscrire</button>
                    
                    <p class="terms-text">
                        En cliquant sur "S'inscrire", vous acceptez nos Conditions.
                    </p>
                </form>
                
                <div class="form-footer">
                    <p>Déjà un compte ?</p>
                    <button class="btn-secondary-dark" onclick="location.href='<?= APP_URL ?>/login'">Se connecter</button>
                </div>
            </div>
        </div>
    </main>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script>
        const student = document.getElementById('student');
        const owner = document.getElementById('owner');
        const ecoleField = document.getElementById('ecole-field');
        const telephoneField = document.getElementById('telephone-field');
        
        student.addEventListener('change', function() {
            if(this.checked) {
                ecoleField.style.display = 'block';
                telephoneField.style.display = 'none';
            }
        });
        
        owner.addEventListener('change', function() {
            if(this.checked) {
                ecoleField.style.display = 'none';
                telephoneField.style.display = 'block';
            }
        });
    </script>
</body>
</html>