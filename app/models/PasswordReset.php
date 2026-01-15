<?php
/**
 * Modèle PasswordReset - Gestion des tokens de réinitialisation
 */
class PasswordReset extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'password_resets';
    }
    
    /**
     * Créer un nouveau token de réinitialisation
     */
    public function createToken($email)
    {
        // Supprimer les anciens tokens pour cet email
        $this->deleteByEmail($email);
        
        // Générer un token unique
        $token = bin2hex(random_bytes(32));
        
        // Insérer le nouveau token
        $sql = "INSERT INTO {$this->table} (email, token) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email, $token]);
        
        return $token;
    }
    
    /**
     * Vérifier si un token est valide (moins de 1 heure)
     */
    public function validateToken($token)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE token = ? 
                AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$token]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Supprimer un token
     */
    public function deleteToken($token)
    {
        $sql = "DELETE FROM {$this->table} WHERE token = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$token]);
    }
    
    /**
     * Supprimer tous les tokens d'un email
     */
    public function deleteByEmail($email)
    {
        $sql = "DELETE FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$email]);
    }
}