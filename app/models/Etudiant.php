<?php
/**
 * Modèle Etudiant - Hérite de Utilisateur
 * Gestion des étudiants
 */
class Etudiant extends Model
{
    protected string $table = 'etudiants';
    
    /**
     * Crée un profil étudiant (après création de l'utilisateur)
     */
    public function createProfile(int $utilisateurId, string $ecole): int
    {
        return $this->insert([
            'utilisateur_id' => $utilisateurId,
            'ecole' => $ecole
        ]);
    }
    
    /**
     * Récupère un étudiant avec ses informations utilisateur
     */
    public function findByUserId(int $userId): ?array
    {
        $sql = "SELECT u.*, e.ecole 
                FROM utilisateurs u 
                INNER JOIN etudiants e ON u.id = e.utilisateur_id 
                WHERE u.id = :user_id AND u.type = 'etudiant'";
        
        return $this->queryOne($sql, ['user_id' => $userId]);
    }
    
    /**
     * Récupère tous les étudiants avec leurs informations
     */
    public function findAllWithUsers(): array
    {
        $sql = "SELECT u.*, e.ecole 
                FROM utilisateurs u 
                INNER JOIN etudiants e ON u.id = e.utilisateur_id 
                WHERE u.type = 'etudiant'
                ORDER BY u.date_inscription DESC";
        
        return $this->query($sql);
    }
    
    /**
     * Met à jour l'école d'un étudiant
     */
    public function updateEcole(int $utilisateurId, string $ecole): bool
    {
        $sql = "UPDATE {$this->table} SET ecole = :ecole WHERE utilisateur_id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'ecole' => $ecole,
            'user_id' => $utilisateurId
        ]);
    }
    
    /**
     * Compte le nombre d'étudiants
     */
    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->queryOne($sql);
        
        return (int) $result['total'];
    }
}
