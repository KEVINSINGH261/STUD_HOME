<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stud'Home - Inscription</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/register.css">
    <style>
        /* Styles pour la validation */
        .form-input.error {
            border-color: #dc3545;
            background-color: #fff5f5;
        }
        
        .form-input.valid {
            border-color: #28a745;
            background-color: #f0fff4;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
        
        .checking-message {
            color: #007bff;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
        
        .password-requirements {
            margin-top: 0.5rem;
            padding: 0.75rem;
            background-color: #f8f9fa;
            border-radius: 4px;
            font-size: 0.875rem;
        }
        
        .requirement {
            display: flex;
            align-items: center;
            margin: 0.25rem 0;
            color: #6c757d;
        }
        
        .requirement.valid {
            color: #28a745;
        }
        
        .requirement .icon {
            margin-right: 0.5rem;
            font-weight: bold;
        }
        
        .password-strength {
            margin-top: 0.5rem;
        }
        
        .strength-bar {
            height: 4px;
            background-color: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }
        
        .strength-bar-fill {
            height: 100%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }
        
        .strength-label {
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
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
                    
                    <div class="form-group" id="ecole-field">
                        <label for="ecole">École</label>
                        <select id="ecole" name="ecole" class="form-input">
                            <option value="">-- Sélectionnez votre école --</option>
                            <option value="École Polytechnique">École Polytechnique</option>
                            <option value="CentraleSupélec">CentraleSupélec</option>
                            <option value="Mines ParisTech">Mines ParisTech</option>
                            <option value="Télécom Paris">Télécom Paris</option>
                            <option value="ENSTA Paris">ENSTA Paris</option>
                            <option value="École des Ponts ParisTech">École des Ponts ParisTech</option>
                            <option value="ENSAE Paris">ENSAE Paris</option>
                            <option value="ISAE-SUPAERO">ISAE-SUPAERO</option>
                            <option value="ISEP">ISEP</option>
                            <option value="INSA Lyon">INSA Lyon</option>
                            <option value="INSA Toulouse">INSA Toulouse</option>
                            <option value="Centrale Nantes">Centrale Nantes</option>
                            <option value="Centrale Lyon">Centrale Lyon</option>
                            <option value="IMT Atlantique">IMT Atlantique</option>
                            <option value="Grenoble INP">Grenoble INP</option>
                            <option value="Arts et Métiers">Arts et Métiers</option>
                            <option value="UTC Compiègne">UTC Compiègne</option>
                            <option value="ENSEEIHT">ENSEEIHT</option>
                            <option value="ESPCI Paris">ESPCI Paris</option>
                            <option value="Télécom SudParis">Télécom SudParis</option>
                            <option value="Mines Nancy">Mines Nancy</option>
                            <option value="Mines Saint-Étienne">Mines Saint-Étienne</option>
                            <option value="ENSIMAG">ENSIMAG</option>
                            <option value="INSA Rennes">INSA Rennes</option>
                            <option value="INSA Strasbourg">INSA Strasbourg</option>
                            <option value="INSA Rouen">INSA Rouen</option>
                            <option value="Supélec Gif">Supélec Gif</option>
                            <option value="EPITA">EPITA</option>
                            <option value="EPITECH">EPITECH</option>
                            <option value="ECE Paris">ECE Paris</option>
                            <option value="ESIEE Paris">ESIEE Paris</option>
                            <option value="ENSAM">ENSAM</option>
                            <option value="ESTP">ESTP</option>
                            <option value="ESILV">ESILV</option>
                            <option value="ISEN Lille">ISEN Lille</option>
                            <option value="ISEN Brest">ISEN Brest</option>
                            <option value="ESIGELEC">ESIGELEC</option>
                            <option value="ENSEA">ENSEA</option>
                            <option value="ENSIC">ENSIC</option>
                            <option value="ENSGSI">ENSGSI</option>
                            <option value="Polytech Paris-Saclay">Polytech Paris-Saclay</option>
                            <option value="Polytech Lille">Polytech Lille</option>
                            <option value="Polytech Nantes">Polytech Nantes</option>
                            <option value="Polytech Grenoble">Polytech Grenoble</option>
                            <option value="ENSGI">ENSGI</option>
                            <option value="ENSIACET">ENSIACET</option>
                            <option value="ENSCBP">ENSCBP</option>
                            <option value="CPE Lyon">CPE Lyon</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="telephone-field">
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
    
    <script src="<?= APP_URL ?>/js/notifications.js"></script>
    <script src="<?= APP_URL ?>/js/validation.js"></script>
    <script>
        const student = document.getElementById('student');
        const owner = document.getElementById('owner');
        const ecoleField = document.getElementById('ecole-field');
        const telephoneField = document.getElementById('telephone-field');
        
        student.addEventListener('change', function() {
            if(this.checked) {
                ecoleField.style.display = 'block';
                telephoneField.style.display = 'block';
                document.getElementById('ecole').setAttribute('required', 'required');
            }
        });
        
        owner.addEventListener('change', function() {
            if(this.checked) {
                ecoleField.style.display = 'none';
                telephoneField.style.display = 'block';
                document.getElementById('ecole').removeAttribute('required');
                document.getElementById('telephone').setAttribute('required', 'required');
            }
        });
    </script>
</body>
</html>