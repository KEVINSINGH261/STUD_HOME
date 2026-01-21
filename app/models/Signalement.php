<?php
/**
 * Modèle Signalement - Gestion des signalements d'annonces
 */
class Signalement extends Model
{
    protected string $table = 'signalements';
    
    /**
     * Crée un nouveau signalement
     */
    public function create(int $annonce_id, int $utilisateur_id, string $motif, ?string $description = null): bool
    {
        $sql = "INSERT INTO signalements (annonce_id, utilisateur_id, motif, description) 
                VALUES (:annonce_id, :utilisateur_id, :motif, :description)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':annonce_id' => $annonce_id,
            ':utilisateur_id' => $utilisateur_id,
            ':motif' => $motif,
            ':description' => $description
        ]);
    }
    
    /**
     * Vérifie si un utilisateur a déjà signalé cette annonce
     */
    public function exists(int $annonce_id, int $utilisateur_id): bool
    {
        $sql = "SELECT COUNT(*) as count FROM signalements 
                WHERE annonce_id = :annonce_id AND utilisateur_id = :utilisateur_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':annonce_id' => $annonce_id,
            ':utilisateur_id' => $utilisateur_id
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (bool)($result['count'] ?? 0);
    }
    
    /**
     * Récupère tous les signalements
     */
    public function getAll(): array
    {
        $sql = "SELECT s.*, a.titre as annonce_titre, u.prenom, u.nom
                FROM signalements s
                JOIN annonces a ON s.annonce_id = a.id
                JOIN utilisateurs u ON s.utilisateur_id = u.id
                ORDER BY s.date_signalement DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
    }
    
    /**
     * Récupère les signalements par annonce
     */
    public function getByAnnonce(int $annonce_id): array
    {
        $sql = "SELECT s.*, u.prenom, u.nom, u.email
                FROM signalements s
                JOIN utilisateurs u ON s.utilisateur_id = u.id
                WHERE s.annonce_id = :annonce_id
                ORDER BY s.date_signalement DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':annonce_id' => $annonce_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
    }
    
    /**
     * Récupère les signalements par statut
     */
    public function getByStatut(string $statut): array
    {
        $sql = "SELECT s.*, a.titre as annonce_titre, u.prenom, u.nom
                FROM signalements s
                JOIN annonces a ON s.annonce_id = a.id
                JOIN utilisateurs u ON s.utilisateur_id = u.id
                WHERE s.statut = :statut
                ORDER BY s.date_signalement DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':statut' => $statut]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
    }
    
    /**
     * Récupère un signalement par ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT s.*, a.titre as annonce_titre, u.prenom, u.nom
                FROM signalements s
                JOIN annonces a ON s.annonce_id = a.id
                JOIN utilisateurs u ON s.utilisateur_id = u.id
                WHERE s.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Met à jour le statut d'un signalement
     */
    public function updateStatut(int $id, string $statut): bool
    {
        $sql = "UPDATE signalements SET statut = :statut WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':statut' => $statut,
            ':id' => $id
        ]);
    }
    
    /**
     * Supprime un signalement
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM signalements WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Compte les signalements par statut
     */
    public function countByStatut(string $statut): int
    {
        $sql = "SELECT COUNT(*) as count FROM signalements WHERE statut = :statut";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':statut' => $statut]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }
}

