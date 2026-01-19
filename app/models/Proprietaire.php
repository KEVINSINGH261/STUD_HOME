<?php
/**
 * Modèle Proprietaire - Hérite de Utilisateur
 * Gestion des propriétaires
 */
class Proprietaire extends Model
{
    protected string $table = 'proprietaires';
    
    /**
     * Crée un profil propriétaire (après création de l'utilisateur)
     */
    public function createProfile(int $utilisateurId, string $telephone): int
    {
        return $this->insert([
            'utilisateur_id' => $utilisateurId,
            'telephone' => $telephone
        ]);
    }
    
    /**
     * Récupère un propriétaire avec ses informations utilisateur
     */
    public function findByUserId(int $userId): ?array
    {
        $sql = "SELECT u.*, p.telephone 
                FROM utilisateurs u 
                INNER JOIN proprietaires p ON u.id = p.utilisateur_id 
                WHERE u.id = :user_id AND u.type = 'proprietaire'";
        
        return $this->queryOne($sql, ['user_id' => $userId]);
    }
    
    /**
     * Récupère tous les propriétaires avec leurs informations
     */
    public function findAllWithUsers(): array
    {
        $sql = "SELECT u.*, p.telephone 
                FROM utilisateurs u 
                INNER JOIN proprietaires p ON u.id = p.utilisateur_id 
                WHERE u.type = 'proprietaire'
                ORDER BY u.date_inscription DESC";
        
        return $this->query($sql);
    }
    
    /**
     * Met à jour le téléphone d'un propriétaire
     */
    public function updateTelephone(int $utilisateurId, string $telephone): bool
    {
        $sql = "UPDATE {$this->table} SET telephone = :telephone WHERE utilisateur_id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'telephone' => $telephone,
            'user_id' => $utilisateurId
        ]);
    }
    
    /**
     * Compte le nombre de propriétaires
     */
    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->queryOne($sql);
        
        return (int) $result['total'];
    }
    
    /**
     * Récupère les annonces d'un propriétaire
     */
    public function getAnnonces(int $utilisateurId): array
    {
        $sql = "SELECT * FROM annonces 
                WHERE proprietaire_id = :proprietaire_id 
                ORDER BY date_creation DESC";
        
        return $this->query($sql, ['proprietaire_id' => $utilisateurId]);
    }
    
    /**
     * Compte les annonces d'un propriétaire
     */
    public function countAnnonces(int $utilisateurId): int
    {
        $sql = "SELECT COUNT(*) as total FROM annonces WHERE proprietaire_id = :proprietaire_id";
        $result = $this->queryOne($sql, ['proprietaire_id' => $utilisateurId]);
        
        return (int) $result['total'];
    }
}
