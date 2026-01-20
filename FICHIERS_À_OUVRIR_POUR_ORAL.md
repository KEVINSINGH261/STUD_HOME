# 📂 FICHIERS À OUVRIR POUR L'ORAL

Ouvre ces fichiers dans cet ordre pour présenter proprement :

---

## **PARTIE 1 : AMÉLIORATIONS CONNEXION** (5-10 min)

### 📄 **1. views/auth/login.php** 
**Ce qu'il faut montrer :**
- ✓ Ligne 26 : `onsubmit="return validateLoginForm(event)"` (validation JS au submit)
- ✓ Ligne 29 : `value="<?= htmlspecialchars($email ?? '', ENT_QUOTES) ?>"` (conservation email)
- ✓ Ligne 30 : `<span class="error-message" id="emailError"></span>` (affichage erreur)
- ✓ Ligne 45 : La fonction `validateLoginForm()` (validation email + password)
- ✓ Ligne 85 : Les event listeners (blur, input)

**Ce que tu dis :**
> "J'ai implémenté une validation côté client avec JavaScript qui vérifie l'email et le mot de passe avant l'envoi. L'email est conservé en session et réaffichée après une erreur. Les boutons ont été unifiés (suppression du bouton redondant)."

---

### 📄 **2. app/controllers/AuthController.php**
**Ce qu'il faut montrer :**
- ✓ Ligne 10-20 : `showLogin()` - Passe l'email à la vue
- ✓ Ligne 25-60 : `login()` - Stocke l'email en session en cas d'erreur
  - Ligne 35 : `$_SESSION['login_email'] = $email;`
  - Ligne 54 : `unset($_SESSION['login_email']);` (nettoie si succès)

**Ce que tu dis :**
> "Le contrôleur stocke l'email en session lors d'une erreur de connexion, et la vue le récupère pour le réafficher dans le champ. Cela améliore l'UX car l'utilisateur n'a pas besoin de retaper son email."

---

## **PARTIE 2 : RÉINITIALISATION MDP + EMAIL** (10-15 min)

### 📄 **3. app/models/PasswordReset.php**
**Ce qu'il faut montrer :**
- ✓ Ligne 14-26 : `createToken()` 
  - Ligne 20 : `$token = bin2hex(random_bytes(32));` → Token unique
  - Ligne 24 : INSERT dans la DB
- ✓ Ligne 30-38 : `validateToken()` 
  - **IMPORTANT** : `DATE_SUB(NOW(), INTERVAL 1 HOUR)` → Expiration 1h
- ✓ Ligne 40-45 : `deleteToken()` → Token à usage unique

**Ce que tu dis :**
> "Le modèle PasswordReset gère les tokens de réinitialisation. On génère un token de 64 caractères aléatoires, on le stocke en base avec l'email. Lors de la vérification, on vérifie que le token existe ET qu'il n'a pas expiré (moins d'1 heure). Après utilisation, on le supprime."

---

### 📄 **4. app/services/EmailService.php**
**Ce qu'il faut montrer :**
- ✓ Ligne 28-50 : `sendPasswordResetEmail()`
  - Ligne 35 : Construction du lien avec le token
  - Ligne 43 : `$this->mailer->Body = $this->getPasswordResetTemplate($resetLink);`
  - Ligne 52 : `$this->mailer->send();` → Envoi avec PHPMailer

**Ce que tu dis :**
> "EmailService utilise la bibliothèque PHPMailer pour envoyer les emails. Il génère un lien de réinitialisation avec le token en paramètre d'URL et l'envoie à l'utilisateur avec un template HTML stylisé."

---

### 📄 **5. app/controllers/AuthController.php** (suite)
**Ce qu'il faut montrer :**
- ✓ Ligne 200-230 : `showForgotPassword()` → Affiche le formulaire email
- ✓ Ligne 235-290 : `forgotPassword()` → Crée le token et envoie l'email
  - Ligne 245 : Vérification email existe
  - Ligne 250 : `$token = $passwordResetModel->createToken($email);`
  - Ligne 253 : `$emailService->sendPasswordResetEmail($email, $token);`
- ✓ Ligne 330-360 : `showResetPassword()` → Valide le token
- ✓ Ligne 365-420 : `resetPassword()` → Met à jour le password
  - Ligne 395 : Vérification token à nouveau
  - Ligne 408 : `$utilisateurModel->resetPassword($email, $password);`
  - Ligne 412 : `$passwordResetModel->deleteToken($token);`

**Ce que tu dis :**
> "Voici le flux complet : 
> 1. Utilisateur entre son email
> 2. Nous créons un token aléatoire et l'envoyons par email
> 3. L'utilisateur clique le lien (le token est en URL)
> 4. Nous vérifions que le token est valide ET pas expiré
> 5. Nous affichons le formulaire de nouveau mot de passe
> 6. Après soumission, nous hashons le password avec bcrypt et le stockons
> 7. Nous supprimons le token (à usage unique)"

---

## **PARTIE 3 : BONUS - SUPPRESSION DE COMPTE** (3-5 min)

### 📄 **6. app/controllers/AdminController.php**
**Ce qu'il faut montrer :**
- ✓ Ligne 130-140 : Récupération email et nom avant suppression
- ✓ Ligne 180-190 : Après suppression, envoi de l'email
  ```php
  $emailService = new EmailService();
  $emailService->sendAccountDeletionEmail($userEmail, $userName);
  ```

**Ce que tu dis :**
> "J'ai aussi implémenté l'envoi d'un email quand un admin supprime un compte utilisateur. L'utilisateur reçoit une notification de suppression avec les détails de ce qui a été supprimé (annonces, favoris, données)."

---

## **RÉCAPITULATIF POUR PRÉSENTER** 📊

### **Commencer par :**
```
"Voici les 3 améliorations que j'ai apportées à la page de connexion :
1. Validation JavaScript côté client pour feedback immédiat
2. Conservation de l'email après rejet de connexion
3. Unification des boutons

Ensuite, voici le système de réinitialisation de mot de passe avec envoi d'email..."
```

### **Points clés à mémoriser :**
- ✓ Token = 64 caractères aléatoires
- ✓ Expiration = 1 heure
- ✓ À usage unique (supprimé après utilisation)
- ✓ Validation JavaScript ≠ Sécurité (serveur redoit valider)
- ✓ Hash = bcrypt
- ✓ Email = PHPMailer

---

## **COMMANDES VS CODE** 

Ouvre rapidement les fichiers :
```
Ctrl+P puis tape :
- views/auth/login.php
- app/controllers/AuthController.php
- app/models/PasswordReset.php
- app/services/EmailService.php
- app/controllers/AdminController.php
```

Ou fais un clic-droit dans l'explorer et ouvre les fichiers.

---

**Temps total estimé : 20-30 minutes**

Bon courage ! 🚀
