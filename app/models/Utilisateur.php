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