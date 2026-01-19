/**
 * Validation du formulaire d'inscription
 * Vérifie les champs en temps réel avec indicateurs visuels
 */

// Configuration de l'application
const APP_URL = window.location.origin + '/STUD_HOME/public';

// Règles de validation
const VALIDATION_RULES = {
    nom: {
        minLength: 2,
        maxLength: 50,
        pattern: /^[a-zA-ZÀ-ÿ\s'-]+$/,
        message: 'Le nom doit contenir uniquement des lettres (2-50 caractères)'
    },
    prenom: {
        minLength: 2,
        maxLength: 50,
        pattern: /^[a-zA-ZÀ-ÿ\s'-]+$/,
        message: 'Le prénom doit contenir uniquement des lettres (2-50 caractères)'
    },
    email: {
        pattern: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
        message: 'Email invalide (ex: exemple@domaine.com)'
    },
    password: {
        minLength: 8,
        maxLength: 100,
        requireUppercase: true,
        requireLowercase: true,
        requireNumber: true,
        requireSpecial: true,
        message: 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial'
    },
    ecole: {
        minLength: 2,
        maxLength: 100,
        message: 'Le nom de l\'école doit contenir entre 2 et 100 caractères'
    },
    telephone: {
        pattern: /^(?:(?:\+|00)33|0)[1-9](?:[0-9]{8})$/,
        message: 'Numéro de téléphone invalide (format: 06 12 34 56 78)'
    },
    security_answer: {
        minLength: 2,
        maxLength: 100,
        message: 'La réponse doit contenir entre 2 et 100 caractères'
    }
};

/**
 * Affiche un message d'erreur pour un champ
 */
function showError(input, message) {
    const formGroup = input.closest('.form-group');
    let errorDiv = formGroup.querySelector('.error-message');
    
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        formGroup.appendChild(errorDiv);
    }
    
    errorDiv.textContent = message;
    input.classList.add('error');
    input.classList.remove('valid');
    
    return false;
}

/**
 * Affiche un message de succès pour un champ
 */
function showSuccess(input) {
    const formGroup = input.closest('.form-group');
    const errorDiv = formGroup.querySelector('.error-message');
    
    if (errorDiv) {
        errorDiv.remove();
    }
    
    input.classList.add('valid');
    input.classList.remove('error');
    
    return true;
}

/**
 * Valide le nom ou prénom
 */
function validateName(input) {
    const value = input.value.trim();
    const rules = VALIDATION_RULES[input.name];
    
    if (value === '') {
        return showError(input, `Le ${input.name} est obligatoire`);
    }
    
    if (value.length < rules.minLength || value.length > rules.maxLength) {
        return showError(input, rules.message);
    }
    
    if (!rules.pattern.test(value)) {
        return showError(input, rules.message);
    }
    
    return showSuccess(input);
}

/**
 * Valide l'email
 */
function validateEmail(input) {
    const value = input.value.trim();
    const rules = VALIDATION_RULES.email;
    
    if (value === '') {
        return showError(input, 'L\'email est obligatoire');
    }
    
    if (!rules.pattern.test(value)) {
        return showError(input, rules.message);
    }
    
    // Vérification AJAX si l'email existe déjà
    checkEmailExists(input, value);
    
    return showSuccess(input);
}

/**
 * Vérifie si l'email existe déjà via AJAX
 */
function checkEmailExists(input, email) {
    const formGroup = input.closest('.form-group');
    let checkingDiv = formGroup.querySelector('.checking-message');
    
    // Afficher le message de vérification
    if (!checkingDiv) {
        checkingDiv = document.createElement('div');
        checkingDiv.className = 'checking-message';
        checkingDiv.textContent = 'Vérification de l\'email...';
        formGroup.appendChild(checkingDiv);
    }
    
    fetch(`${APP_URL}/register/check-email`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(email)}`
    })
    .then(response => response.json())
    .then(data => {
        checkingDiv.remove();
        
        if (data.exists) {
            showError(input, 'Cet email est déjà utilisé');
        } else {
            showSuccess(input);
        }
    })
    .catch(error => {
        console.error('Erreur lors de la vérification de l\'email:', error);
        checkingDiv.remove();
    });
}

/**
 * Valide le mot de passe avec indicateur de force
 */
function validatePassword(input) {
    const value = input.value;
    const rules = VALIDATION_RULES.password;
    const formGroup = input.closest('.form-group');
    
    if (value === '') {
        removePasswordStrength(formGroup);
        return showError(input, 'Le mot de passe est obligatoire');
    }
    
    // Vérifier la longueur
    if (value.length < rules.minLength) {
        removePasswordStrength(formGroup);
        return showError(input, `Le mot de passe doit contenir au moins ${rules.minLength} caractères`);
    }
    
    if (value.length > rules.maxLength) {
        removePasswordStrength(formGroup);
        return showError(input, `Le mot de passe ne peut pas dépasser ${rules.maxLength} caractères`);
    }
    
    // Calculer la force du mot de passe
    let strength = 0;
    const checks = {
        hasUppercase: /[A-Z]/.test(value),
        hasLowercase: /[a-z]/.test(value),
        hasNumber: /[0-9]/.test(value),
        hasSpecial: /[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\/]/.test(value),
        hasLength: value.length >= 12
    };
    
    // Afficher les exigences
    showPasswordRequirements(formGroup, checks);
    
    // Calculer le score
    if (checks.hasUppercase) strength++;
    if (checks.hasLowercase) strength++;
    if (checks.hasNumber) strength++;
    if (checks.hasSpecial) strength++;
    if (checks.hasLength) strength++;
    
    // Afficher l'indicateur de force
    showPasswordStrength(formGroup, strength);
    
    // Vérifier si toutes les exigences sont remplies
    if (!checks.hasUppercase || !checks.hasLowercase || !checks.hasNumber || !checks.hasSpecial) {
        return showError(input, rules.message);
    }
    
    return showSuccess(input);
}

/**
 * Affiche les exigences du mot de passe
 */
function showPasswordRequirements(formGroup, checks) {
    let requirementsDiv = formGroup.querySelector('.password-requirements');
    
    if (!requirementsDiv) {
        requirementsDiv = document.createElement('div');
        requirementsDiv.className = 'password-requirements';
        formGroup.appendChild(requirementsDiv);
    }
    
    requirementsDiv.innerHTML = `
        <div class="requirement ${checks.hasLength ? 'valid' : ''}">
            <span class="icon">${checks.hasLength ? '✓' : '○'}</span> Au moins 8 caractères
        </div>
        <div class="requirement ${checks.hasUppercase ? 'valid' : ''}">
            <span class="icon">${checks.hasUppercase ? '✓' : '○'}</span> Une lettre majuscule
        </div>
        <div class="requirement ${checks.hasLowercase ? 'valid' : ''}">
            <span class="icon">${checks.hasLowercase ? '✓' : '○'}</span> Une lettre minuscule
        </div>
        <div class="requirement ${checks.hasNumber ? 'valid' : ''}">
            <span class="icon">${checks.hasNumber ? '✓' : '○'}</span> Un chiffre
        </div>
        <div class="requirement ${checks.hasSpecial ? 'valid' : ''}">
            <span class="icon">${checks.hasSpecial ? '✓' : '○'}</span> Un caractère spécial (!@#$%^&*...)
        </div>
    `;
}

/**
 * Affiche l'indicateur de force du mot de passe
 */
function showPasswordStrength(formGroup, strength) {
    let strengthDiv = formGroup.querySelector('.password-strength');
    
    if (!strengthDiv) {
        strengthDiv = document.createElement('div');
        strengthDiv.className = 'password-strength';
        formGroup.appendChild(strengthDiv);
    }
    
    const labels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
    const colors = ['#dc3545', '#fd7e14', '#ffc107', '#28a745', '#155724'];
    
    strengthDiv.innerHTML = `
        <div class="strength-bar">
            <div class="strength-bar-fill" style="width: ${(strength / 5) * 100}%; background-color: ${colors[strength - 1] || colors[0]}"></div>
        </div>
        <div class="strength-label" style="color: ${colors[strength - 1] || colors[0]}">${labels[strength - 1] || labels[0]}</div>
    `;
}

/**
 * Supprime l'indicateur de force du mot de passe
 */
function removePasswordStrength(formGroup) {
    const strengthDiv = formGroup.querySelector('.password-strength');
    const requirementsDiv = formGroup.querySelector('.password-requirements');
    
    if (strengthDiv) strengthDiv.remove();
    if (requirementsDiv) requirementsDiv.remove();
}

/**
 * Valide la confirmation du mot de passe
 */
function validatePasswordConfirm(input) {
    const password = document.getElementById('password').value;
    const confirm = input.value;
    
    if (confirm === '') {
        return showError(input, 'Veuillez confirmer le mot de passe');
    }
    
    if (password !== confirm) {
        return showError(input, 'Les mots de passe ne correspondent pas');
    }
    
    return showSuccess(input);
}

/**
 * Valide l'école
 */
function validateEcole(input) {
    const value = input.value.trim();
    const rules = VALIDATION_RULES.ecole;
    const type = document.querySelector('input[name="type"]:checked').value;
    
    if (type === 'etudiant') {
        if (value === '') {
            return showError(input, 'L\'école est obligatoire');
        }
        
        if (value.length < rules.minLength || value.length > rules.maxLength) {
            return showError(input, rules.message);
        }
    }
    
    return showSuccess(input);
}

/**
 * Valide le téléphone
 */
function validateTelephone(input) {
    const value = input.value.trim().replace(/\s/g, '');
    const rules = VALIDATION_RULES.telephone;
    const type = document.querySelector('input[name="type"]:checked').value;
    
    if (type === 'proprietaire') {
        if (value === '') {
            return showError(input, 'Le téléphone est obligatoire');
        }
        
        if (!rules.pattern.test(value)) {
            return showError(input, rules.message);
        }
    }
    
    return showSuccess(input);
}

/**
 * Valide la réponse de sécurité
 */
function validateSecurityAnswer(input) {
    const value = input.value.trim();
    const rules = VALIDATION_RULES.security_answer;
    
    if (value === '') {
        return showError(input, 'La réponse de sécurité est obligatoire');
    }
    
    if (value.length < rules.minLength || value.length > rules.maxLength) {
        return showError(input, rules.message);
    }
    
    return showSuccess(input);
}

/**
 * Valide la question de sécurité
 */
function validateSecurityQuestion(select) {
    if (select.value === '') {
        return showError(select, 'Veuillez sélectionner une question de sécurité');
    }
    
    return showSuccess(select);
}

/**
 * Valide tout le formulaire avant soumission
 */
function validateForm(form) {
    let isValid = true;
    
    // Valider tous les champs
    const nom = form.querySelector('#nom');
    const prenom = form.querySelector('#prenom');
    const email = form.querySelector('#email');
    const password = form.querySelector('#password');
    const passwordConfirm = form.querySelector('#password_confirm');
    const securityQuestion = form.querySelector('#security_question');
    const securityAnswer = form.querySelector('#security_answer');
    
    if (nom && !validateName(nom)) isValid = false;
    if (prenom && !validateName(prenom)) isValid = false;
    if (email && !validateEmail(email)) isValid = false;
    if (password && !validatePassword(password)) isValid = false;
    if (passwordConfirm && !validatePasswordConfirm(passwordConfirm)) isValid = false;
    if (securityQuestion && !validateSecurityQuestion(securityQuestion)) isValid = false;
    if (securityAnswer && !validateSecurityAnswer(securityAnswer)) isValid = false;
    
    // Valider selon le type
    const type = form.querySelector('input[name="type"]:checked').value;
    if (type === 'etudiant') {
        const ecole = form.querySelector('#ecole');
        if (ecole && !validateEcole(ecole)) isValid = false;
    } else {
        const telephone = form.querySelector('#telephone');
        if (telephone && !validateTelephone(telephone)) isValid = false;
    }
    
    return isValid;
}

/**
 * Initialise la validation du formulaire
 */
function initValidation() {
    const form = document.querySelector('.login-form');
    
    if (!form) return;
    
    // Validation en temps réel
    const nom = form.querySelector('#nom');
    const prenom = form.querySelector('#prenom');
    const email = form.querySelector('#email');
    const password = form.querySelector('#password');
    const passwordConfirm = form.querySelector('#password_confirm');
    const ecole = form.querySelector('#ecole');
    const telephone = form.querySelector('#telephone');
    const securityQuestion = form.querySelector('#security_question');
    const securityAnswer = form.querySelector('#security_answer');
    
    if (nom) {
        nom.addEventListener('blur', () => validateName(nom));
        nom.addEventListener('input', () => {
            if (nom.classList.contains('error')) {
                validateName(nom);
            }
        });
    }
    
    if (prenom) {
        prenom.addEventListener('blur', () => validateName(prenom));
        prenom.addEventListener('input', () => {
            if (prenom.classList.contains('error')) {
                validateName(prenom);
            }
        });
    }
    
    if (email) {
        let emailTimeout;
        email.addEventListener('blur', () => validateEmail(email));
        email.addEventListener('input', () => {
            if (email.classList.contains('error') || email.classList.contains('valid')) {
                clearTimeout(emailTimeout);
                emailTimeout = setTimeout(() => validateEmail(email), 500);
            }
        });
    }
    
    if (password) {
        password.addEventListener('input', () => validatePassword(password));
        password.addEventListener('blur', () => validatePassword(password));
    }
    
    if (passwordConfirm) {
        passwordConfirm.addEventListener('input', () => validatePasswordConfirm(passwordConfirm));
        passwordConfirm.addEventListener('blur', () => validatePasswordConfirm(passwordConfirm));
    }
    
    if (ecole) {
        ecole.addEventListener('blur', () => validateEcole(ecole));
        ecole.addEventListener('input', () => {
            if (ecole.classList.contains('error')) {
                validateEcole(ecole);
            }
        });
    }
    
    if (telephone) {
        telephone.addEventListener('blur', () => validateTelephone(telephone));
        telephone.addEventListener('input', () => {
            if (telephone.classList.contains('error')) {
                validateTelephone(telephone);
            }
        });
    }
    
    if (securityQuestion) {
        securityQuestion.addEventListener('change', () => validateSecurityQuestion(securityQuestion));
    }
    
    if (securityAnswer) {
        securityAnswer.addEventListener('blur', () => validateSecurityAnswer(securityAnswer));
        securityAnswer.addEventListener('input', () => {
            if (securityAnswer.classList.contains('error')) {
                validateSecurityAnswer(securityAnswer);
            }
        });
    }
    
    // Validation à la soumission
    form.addEventListener('submit', (e) => {
        if (!validateForm(form)) {
            e.preventDefault();
            
            // Scroll vers le premier champ en erreur
            const firstError = form.querySelector('.error');
            if (firstError) {
                firstError.focus();
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
}

// Initialiser quand le DOM est prêt
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initValidation);
} else {
    initValidation();
}
