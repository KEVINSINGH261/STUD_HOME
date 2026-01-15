<?php
/**
 * Modèle Utilisateur - Classe parent pour Etudiant et Proprietaire
 * Gestion de l'héritage avec la table utilisateurs
 */
class Utilisateur extends Model
{
    protected string $table = 'utilisateurs';
    
    /**
     * Inscription d'un utilisateur
     */
    public function register(array $data): int
    {
        // Hash du mot de passe
        $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        
        // Hash de la réponse de sécurité (normalisée en minuscules et sans espaces)
        if (isset($data['security_answer'])) {
            $securityAnswer = strtolower(trim($data['security_answer']));
            $data['security_answer'] = password_hash($securityAnswer, PASSWORD_DEFAULT);
        }
        
        // Ajout de la date de création
        $data['date_inscription'] = date('Y-m-d H:i:s');
        
        return $this->insert($data);
    }
    
    /**
     * Connexion d'un utilisateur
     */
    public function login(string $email, string $password): ?array
    {
        $user = $this->findOneWhere(['email' => $email]);
        
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Ne pas retourner le mot de passe
            unset($user['mot_de_passe']);
            return $user;
        }
        
        return null;
    }
    
    /**
     * Vérifie si un email existe déjà
     */
    public function emailExists(string $email): bool
    {
        $result = $this->findOneWhere(['email' => $email]);
        return $result !== null;
    }
    
    /**
     * Valide la complexité d'un mot de passe
     * 
     * @param string $password Le mot de passe à valider
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validatePassword(string $password): array
    {
        $errors = [];
        
        // Longueur minimale
        if (strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères';
        }
        
        // Longueur maximale
        if (strlen($password) > 100) {
            $errors[] = 'Le mot de passe ne peut pas dépasser 100 caractères';
        }
        
        // Au moins une lettre majuscule
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre majuscule';
        }
        
        // Au moins une lettre minuscule
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une lettre minuscule';
        }
        
        // Au moins un chiffre
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un chiffre';
        }
        
        // Au moins un caractère spécial
        if (!preg_match('/[!@#$%^&*()\[\]{}\-_=+\\|;:\'",.<>?\/`~]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un caractère spécial (!@#$%^&*...)';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Valide un nom ou prénom
     */
    public function validateName(string $name, string $fieldName = 'Nom'): array
    {
        $errors = [];
        
        $name = trim($name);
        
        if (empty($name)) {
            $errors[] = "$fieldName est obligatoire";
        } elseif (strlen($name) < 2) {
            $errors[] = "$fieldName doit contenir au moins 2 caractères";
        } elseif (strlen($name) > 50) {
            $errors[] = "$fieldName ne peut pas dépasser 50 caractères";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\'-]+$/u', $name)) {
            $errors[] = "$fieldName ne peut contenir que des lettres, espaces, tirets et apostrophes";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Valide un email
     */
    public function validateEmail(string $email): array
    {
        $errors = [];
        
        $email = trim($email);
        
        if (empty($email)) {
            $errors[] = 'Email est obligatoire';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format d\'email invalide';
        } elseif (strlen($email) > 100) {
            $errors[] = 'Email trop long (maximum 100 caractères)';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Valide un numéro de téléphone français
     */
    public function validatePhone(string $phone): array
    {
        $errors = [];
        
        // Supprimer les espaces
        $phone = preg_replace('/\s+/', '', $phone);
        
        if (empty($phone)) {
            $errors[] = 'Téléphone est obligatoire';
        } elseif (!preg_match('/^(?:(?:\+|00)33|0)[1-9](?:[0-9]{8})$/', $phone)) {
            $errors[] = 'Format de téléphone invalide (ex: 06 12 34 56 78)';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Récupère un utilisateur par email
     */
    public function findByEmail(string $email): ?array
    {
        return $this->findOneWhere(['email' => $email]);
    }
    
    /**
     * Récupère un utilisateur par email (alias pour compatibilité)
     */
    public function getUserByEmail(string $email): ?array
    {
        return $this->findByEmail($email);
    }
    
    /**
     * Vérifie la réponse à la question de sécurité
     */
    public function verifySecurityAnswer(string $email, string $answer): bool
    {
        $user = $this->findByEmail($email);
        
        if (!$user || empty($user['security_answer'])) {
            return false;
        }
        
        // Normaliser la réponse (minuscules, sans espaces)
        $normalizedAnswer = strtolower(trim($answer));
        
        // Vérifier avec password_verify
        return password_verify($normalizedAnswer, $user['security_answer']);
    }
    
    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword($email, $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE utilisateurs SET mot_de_passe = ? WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([$hashedPassword, $email]);   
    }
    
    /**
     * Mise à jour du profil
     */
    public function updateProfile(int $id, array $data): bool
    {
        // Retirer le mot de passe si vide
        if (isset($data['mot_de_passe']) && empty($data['mot_de_passe'])) {
            unset($data['mot_de_passe']);
        } elseif (isset($data['mot_de_passe'])) {
            $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        }
        
        // Hash de la réponse de sécurité si elle est modifiée
        if (isset($data['security_answer']) && !empty($data['security_answer'])) {
            $securityAnswer = strtolower(trim($data['security_answer']));
            $data['security_answer'] = password_hash($securityAnswer, PASSWORD_DEFAULT);
        } elseif (isset($data['security_answer']) && empty($data['security_answer'])) {
            unset($data['security_answer']);
        }
        
        return $this->update($id, $data);
    }
    
    /**
     * Récupère un utilisateur avec ses informations spécifiques
     */
    public function findWithDetails(int $id): ?array
    {
        $user = $this->findById($id);
        
        if (!$user) {
            return null;
        }
        
        // Récupérer les détails selon le type
        if ($user['type'] === 'etudiant') {
            $sql = "SELECT u.*, e.ecole 
                    FROM utilisateurs u 
                    LEFT JOIN etudiants e ON u.id = e.utilisateur_id 
                    WHERE u.id = :id";
        } else {
            $sql = "SELECT u.*, p.telephone 
                    FROM utilisateurs u 
                    LEFT JOIN proprietaires p ON u.id = p.utilisateur_id 
                    WHERE u.id = :id";
        }
        
        return $this->queryOne($sql, ['id' => $id]);
    }
}