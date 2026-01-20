<?php
/**
 * Contrôleur AuthController - Gestion de l'authentification
 */
class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     */
    public function showLogin(): void
    {
        // Si déjà connecté, rediriger selon le rôle
        if ($this->isAuthenticated()) {
            $this->redirectToDashboard();
        }
        
        $this->view('auth/login', [
            'flash' => $this->getFlash()
            'flash' => $this->getFlash(),
            'email' => $_SESSION['login_email'] ?? ''
        ]);
    }
    
    /**
     * Traite la connexion
     */
    public function login(): void
    {
        if (!$this->isPost()) {
            $this->redirect('login');
            return;
        }
        
        $email = $this->post('email');
        $password = $this->post('password');
        
        // Validation
        if (empty($email) || empty($password)) {
            $_SESSION['login_email'] = $email;
            $this->setFlash('error', 'Veuillez remplir tous les champs.');
            $this->redirect('login');
            return;
        }
        
        // Authentification
        $utilisateurModel = $this->model('Utilisateur');
        $user = $utilisateurModel->login($email, $password);
        
        if ($user) {
            // Créer la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['type'];
            // Nettoyer l'email de login en cas de succès
            unset($_SESSION['login_email']);
            
            $this->setFlash('success', 'Connexion réussie !');
            $this->redirectToDashboard();
        } else {
            $_SESSION['login_email'] = $email;
            $this->setFlash('error', 'Email ou mot de passe incorrect.');
            $this->redirect('login');
        }
    }
    
    /**
     * Vérifie si un email existe déjà (AJAX)
     */
    public function checkEmail(): void
    {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode(['exists' => false]);
            return;
        }
        
        $email = $this->post('email');
        
        if (empty($email)) {
            echo json_encode(['exists' => false]);
            return;
        }
        
        $utilisateurModel = $this->model('Utilisateur');
        $exists = $utilisateurModel->emailExists($email);
        
        echo json_encode(['exists' => $exists]);
    }
    
    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegister(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirectToDashboard();
        }
        
        $this->view('auth/register', [
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Traite l'inscription
     */
    public function register(): void
    {
        if (!$this->isPost()) {
            $this->redirect('register');
            return;
        }
        
        $type = trim($this->post('type'));
        $nom = trim($this->post('nom'));
        $prenom = trim($this->post('prenom'));
        $email = trim($this->post('email'));
        $password = $this->post('password');
        $passwordConfirm = $this->post('password_confirm');
        $securityQuestion = $this->post('security_question');
        $securityAnswer = trim($this->post('security_answer'));
        
        $utilisateurModel = $this->model('Utilisateur');
        
        // Validation complète
        $errors = [];
        
        // Validation du type
        $type = $this->post('type');
        $nom = $this->post('nom');
        $prenom = $this->post('prenom');
        $email = $this->post('email');
        $password = $this->post('password');
        $passwordConfirm = $this->post('password_confirm');
        $securityQuestion = $this->post('security_question');
        $securityAnswer = $this->post('security_answer');
        
        // Validation de base
        $errors = [];
        
        if (empty($type) || !in_array($type, ['etudiant', 'proprietaire'])) {
            $errors[] = 'Veuillez sélectionner un type de compte.';
        }
        
        // Validation du nom
        $nomValidation = $utilisateurModel->validateName($nom, 'Nom');
        if (!$nomValidation['valid']) {
            $errors = array_merge($errors, $nomValidation['errors']);
        }
        
        // Validation du prénom
        $prenomValidation = $utilisateurModel->validateName($prenom, 'Prénom');
        if (!$prenomValidation['valid']) {
            $errors = array_merge($errors, $prenomValidation['errors']);
        }
        
        // Validation de l'email
        $emailValidation = $utilisateurModel->validateEmail($email);
        if (!$emailValidation['valid']) {
            $errors = array_merge($errors, $emailValidation['errors']);
        } else {
            // Vérifier si l'email existe déjà
            if ($utilisateurModel->emailExists($email)) {
                $errors[] = 'Cet email est déjà utilisé.';
            }
        }
        
        // Validation du mot de passe
        if (empty($password)) {
            $errors[] = 'Le mot de passe est obligatoire.';
        } else {
            $passwordValidation = $utilisateurModel->validatePassword($password);
            if (!$passwordValidation['valid']) {
                $errors = array_merge($errors, $passwordValidation['errors']);
            }
        }
        
        // Validation de la confirmation du mot de passe
        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            $errors[] = 'Tous les champs sont obligatoires.';
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide.';
        }
        
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $errors[] = 'Le mot de passe doit contenir au moins ' . PASSWORD_MIN_LENGTH . ' caractères.';
        }
        
        if ($password !== $passwordConfirm) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }
        
        // Validation de la question de sécurité
        if (empty($securityQuestion)) {
            $errors[] = 'Veuillez sélectionner une question de sécurité.';
        }
        
        // Validation de la réponse de sécurité
        if (empty($securityAnswer)) {
            $errors[] = 'Veuillez répondre à la question de sécurité.';
        } elseif (strlen($securityAnswer) < 2) {
            $errors[] = 'La réponse à la question de sécurité doit contenir au moins 2 caractères.';
        } elseif (strlen($securityAnswer) > 100) {
            $errors[] = 'La réponse à la question de sécurité ne peut pas dépasser 100 caractères.';
        }
        
        // Validation spécifique selon le type
        if ($type === 'etudiant') {
            $ecole = trim($this->post('ecole'));
            if (empty($ecole)) {
                $errors[] = 'L\'école est obligatoire pour les étudiants.';
            } elseif (strlen($ecole) < 2) {
                $errors[] = 'Le nom de l\'école doit contenir au moins 2 caractères.';
            } elseif (strlen($ecole) > 100) {
                $errors[] = 'Le nom de l\'école ne peut pas dépasser 100 caractères.';
            }
        } else {
            $telephone = trim($this->post('telephone'));
            $phoneValidation = $utilisateurModel->validatePhone($telephone);
            if (!$phoneValidation['valid']) {
                $errors = array_merge($errors, $phoneValidation['errors']);
            }
        }
        
        // Vérifier si l'email existe déjà
        $utilisateurModel = $this->model('Utilisateur');
        if ($utilisateurModel->emailExists($email)) {
            $errors[] = 'Cet email est déjà utilisé.';
        }
        
        // Si erreurs, retour au formulaire
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('register');
            return;
        }
        
        // Création de l'utilisateur avec la question de sécurité
        $userId = $utilisateurModel->register([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mot_de_passe' => $password,
            'type' => $type,
            'security_question' => $securityQuestion,
            'security_answer' => $securityAnswer
        ]);
        
        if ($userId) {
            // Créer le profil spécifique
            if ($type === 'etudiant') {
                $ecole = trim($this->post('ecole'));
                $ecole = $this->post('ecole');
                if (empty($ecole)) {
                    $this->setFlash('error', 'L\'école est obligatoire pour les étudiants.');
                    $this->redirect('register');
                    return;
                }
                
                $etudiantModel = $this->model('Etudiant');
                $etudiantModel->createProfile($userId, $ecole);
                
            } else {
                $telephone = trim($this->post('telephone'));
                $telephone = $this->post('telephone');
                if (empty($telephone)) {
                    $this->setFlash('error', 'Le téléphone est obligatoire pour les propriétaires.');
                    $this->redirect('register');
                    return;
                }
                
                $proprietaireModel = $this->model('Proprietaire');
                $proprietaireModel->createProfile($userId, $telephone);
            }
            
            $this->setFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
            $this->redirect('login');
        } else {
            $this->setFlash('error', 'Erreur lors de l\'inscription. Veuillez réessayer.');
            $this->redirect('register');
        }
    }
    
    /**
     * Affiche le formulaire de récupération de mot de passe - Étape 1: Email
     */
    public function showForgotPassword(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirectToDashboard();
        }
        
        $this->view('password-oublie/oublie', [
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Vérifie l'email et affiche la question de sécurité - Étape 2
     * Envoie un email de réinitialisation de mot de passe - Étape 2
     */
    public function forgotPassword(): void
    {
        if (!$this->isPost()) {
            $this->redirect('forgot-password');
            return;
        }
        
        $email = $this->post('email');
        
        // Validation
        if (empty($email)) {
            $this->setFlash('error', 'Veuillez entrer votre email.');
            $this->redirect('forgot-password');
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'Email invalide.');
            $this->redirect('forgot-password');
            return;
        }
        
        // Vérifier si l'email existe
        $utilisateurModel = $this->model('Utilisateur');
        $user = $utilisateurModel->getUserByEmail($email);
        
        if (!$user) {
            // Pour la sécurité, ne pas révéler si l'email existe ou non
            $this->setFlash('error', 'Aucun compte associé à cet email.');
            $this->setFlash('success', 'Si cet email existe, vous recevrez un lien de réinitialisation.');
            $this->redirect('forgot-password');
            return;
        }
        
        // Stocker l'email en session temporairement
        $_SESSION['reset_email'] = $email;
        
        // Rediriger vers la page de question de sécurité
        $this->redirect('forgot-password-security');
        // Créer un token de réinitialisation
        $passwordResetModel = $this->model('PasswordReset');
        $token = $passwordResetModel->createToken($email);
        
        // Envoyer l'email
        $emailService = new EmailService();
        $resetLink = APP_URL . '/reset-password?token=' . $token;
        
        if ($emailService->sendPasswordResetEmail($email, $token)) {
            $this->setFlash('success', 'Un email de réinitialisation a été envoyé à ' . htmlspecialchars($email) . '. Vérifiez votre boîte de réception et les spams.');
        } else {
            $this->setFlash('error', 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer plus tard.');
        }
        
        $this->redirect('forgot-password');
    }
    
    /**
     * Affiche la question de sécurité - Étape 2
     */
    public function showSecurityQuestion(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirectToDashboard();
        }
        
        // Vérifier qu'un email est en session
        if (!isset($_SESSION['reset_email'])) {
            $this->redirect('forgot-password');
            return;
        }
        
        $email = $_SESSION['reset_email'];
        
        // Récupérer la question de sécurité
        $utilisateurModel = $this->model('Utilisateur');
        $user = $utilisateurModel->getUserByEmail($email);
        
        if (!$user || empty($user['security_question'])) {
            unset($_SESSION['reset_email']);
            $this->setFlash('error', 'Impossible de récupérer la question de sécurité.');
            $this->redirect('forgot-password');
            return;
        }
        
        $this->view('password-oublie/security-question', [
            'email' => $email,
            'security_question' => $user['security_question'],
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Vérifie la réponse à la question de sécurité - Étape 3
     */
    public function verifySecurityAnswer(): void
    {
        if (!$this->isPost()) {
            $this->redirect('forgot-password');
            return;
        }
        
        if (!isset($_SESSION['reset_email'])) {
            $this->redirect('forgot-password');
            return;
        }
        
        $email = $_SESSION['reset_email'];
        $answer = $this->post('security_answer');
        
        if (empty($answer)) {
            $this->setFlash('error', 'Veuillez entrer votre réponse.');
            $this->redirect('forgot-password-security');
            return;
        }
        
        // Vérifier la réponse
        $utilisateurModel = $this->model('Utilisateur');
        if ($utilisateurModel->verifySecurityAnswer($email, $answer)) {
            // Générer un token pour la réinitialisation
            $token = bin2hex(random_bytes(32));
            $_SESSION['reset_token'] = $token;
            $_SESSION['reset_token_time'] = time();
            
            // Rediriger vers le formulaire de nouveau mot de passe
            $this->redirect('reset-password?token=' . $token);
        } else {
            $this->setFlash('error', 'Réponse incorrecte.');
            $this->redirect('forgot-password-security');
        }
    }
    
    /**
     * Affiche le formulaire de réinitialisation de mot de passe - Étape 4
     * Affiche le formulaire de réinitialisation de mot de passe
     */
    public function showResetPassword(): void
    {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $this->setFlash('error', 'Token invalide.');
            $this->redirect('login');
            return;
        }
        
        // Vérifier que le token est valide et pas expiré (15 minutes)
        if (!isset($_SESSION['reset_token']) || 
            $_SESSION['reset_token'] !== $token || 
            !isset($_SESSION['reset_token_time']) ||
            (time() - $_SESSION['reset_token_time']) > 900) {
            
            unset($_SESSION['reset_token'], $_SESSION['reset_token_time'], $_SESSION['reset_email']);
            $this->setFlash('error', 'Ce lien est expiré ou invalide.');
        // Vérifier que le token est valide et pas expiré (1 heure)
        $passwordResetModel = $this->model('PasswordReset');
        $resetData = $passwordResetModel->validateToken($token);
        
        if (!$resetData) {
            $this->setFlash('error', 'Ce lien de réinitialisation est expiré ou invalide.');
            $this->redirect('login');
            return;
        }
        
        // Vérifier que l'utilisateur existe
        $utilisateurModel = $this->model('Utilisateur');
        $user = $utilisateurModel->getUserByEmail($resetData['email']);
        
        if (!$user) {
            $this->setFlash('error', 'Utilisateur introuvable.');
            $this->redirect('login');
            return;
        }
        
        $this->view('password-oublie/reset', [
            'token' => $token,
            'email' => $_SESSION['reset_email'],
            'email' => $resetData['email'],
            'flash' => $this->getFlash()
        ]);
    }
    
    /**
     * Traite la réinitialisation du mot de passe - Étape 5
     * Traite la réinitialisation du mot de passe
     */
    public function resetPassword(): void
    {
        if (!$this->isPost()) {
            $this->redirect('login');
            return;
        }
        
        $token = $this->post('token');
        $password = $this->post('password');
        $passwordConfirm = $this->post('password_confirm');
        
        $utilisateurModel = $this->model('Utilisateur');
        $errors = [];
        
        // Validation du mot de passe
        if (empty($password)) {
            $errors[] = 'Le mot de passe est obligatoire.';
        } else {
            $passwordValidation = $utilisateurModel->validatePassword($password);
            if (!$passwordValidation['valid']) {
                $errors = array_merge($errors, $passwordValidation['errors']);
            }
        }
        
        // Validation de la confirmation
        if ($password !== $passwordConfirm) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }
        
        // Vérifier le token
        if (!isset($_SESSION['reset_token']) || 
            $_SESSION['reset_token'] !== $token || 
            !isset($_SESSION['reset_email'])) {
            
            $this->setFlash('error', 'Token invalide.');
            $this->redirect('login');
            return;
        }
        
        // Si erreurs, retour au formulaire
        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
        // Validation
        if (empty($password) || empty($passwordConfirm)) {
            $this->setFlash('error', 'Veuillez remplir tous les champs.');
            $this->redirect('reset-password?token=' . $token);
            return;
        }
        
        $email = $_SESSION['reset_email'];
        
        // Réinitialiser le mot de passe
        if ($utilisateurModel->resetPassword($email, $password)) {
            // Nettoyer la session
            unset($_SESSION['reset_token'], $_SESSION['reset_token_time'], $_SESSION['reset_email']);
        if ($password !== $passwordConfirm) {
            $this->setFlash('error', 'Les mots de passe ne correspondent pas.');
            $this->redirect('reset-password?token=' . $token);
            return;
        }
        
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            $this->setFlash('error', 'Le mot de passe doit contenir au moins ' . PASSWORD_MIN_LENGTH . ' caractères.');
            $this->redirect('reset-password?token=' . $token);
            return;
        }
        
        // Vérifier le token
        $passwordResetModel = $this->model('PasswordReset');
        $resetData = $passwordResetModel->validateToken($token);
        
        if (!$resetData) {
            $this->setFlash('error', 'Ce lien de réinitialisation est expiré ou invalide.');
            $this->redirect('login');
            return;
        }
        
        $email = $resetData['email'];
        
        // Vérifier que le nouveau mot de passe n'est pas identique à l'ancien
        $utilisateurModel = $this->model('Utilisateur');
        $user = $utilisateurModel->getUserByEmail($email);
        
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $this->setFlash('error', 'Votre nouveau mot de passe ne doit pas être identique à l\'ancien.');
            $this->redirect('reset-password?token=' . $token);
            return;
        }
        
        // Réinitialiser le mot de passe
        if ($utilisateurModel->resetPassword($email, $password)) {
            // Supprimer le token utilisé
            $passwordResetModel->deleteToken($token);
            
            $this->setFlash('success', 'Votre mot de passe a été réinitialisé avec succès !');
            $this->redirect('login');
        } else {
            $this->setFlash('error', 'Erreur lors de la réinitialisation. Veuillez réessayer.');
            $this->setFlash('error', 'Erreur lors de la réinitialisation du mot de passe.');
            $this->redirect('reset-password?token=' . $token);
        }
    }
    
    /**
     * Déconnexion
     */
    public function logout(): void
    {
        session_destroy();
        $this->setFlash('success', 'Vous êtes déconnecté.');
        $this->redirect('home');
    }
    
    /**
     * Redirection vers le dashboard selon le rôle
     */
    private function redirectToDashboard(): void
    {
        $role = $this->getUserRole();
        
        switch ($role) {
            case 'etudiant':
                $this->redirect('etudiant/dashboard');
                break;
            case 'proprietaire':
                $this->redirect('proprietaire/dashboard');
                break;
            case 'admin':
                $this->redirect('admin/dashboard');
                break;
            default:
                $this->redirect('home');
        }
    }
}