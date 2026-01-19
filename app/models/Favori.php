<?php
/**
 * Modèle Favori - Gestion des favoris des étudiants
 */
class Favori extends Model
{
    protected string $table = 'favoris';
    
    /**
     * Récupère les favoris d'un étudiant avec détails des annonces
     */
    public function findByEtudiant(int $etudiantId): array
    {
        $sql = "SELECT f.*, a.*, u.nom, u.prenom
                FROM {$this->table} f
                INNER JOIN annonces a ON f.annonce_id = a.id
                INNER JOIN utilisateurs u ON a.proprietaire_id = u.id
                WHERE f.etudiant_id = :etudiant_id
                ORDER BY f.date_ajout DESC";
        
        return $this->query($sql, ['etudiant_id' => $etudiantId]);
    }
    
    /**
     * Ajoute une annonce aux favoris
     */
    public function add(int $etudiantId, int $annonceId): int
    {
        // Vérifier si déjà en favori
        if ($this->exists($etudiantId, $annonceId)) {
            return 0; // Déjà en favori
        }
        
        return $this->insert([
            'etudiant_id' => $etudiantId,
            'annonce_id' => $annonceId,
            'date_ajout' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Retire une annonce des favoris
     */
    public function remove(int $etudiantId, int $annonceId): bool
    {
        $sql = "DELETE FROM {$this->table} 
                WHERE etudiant_id = :etudiant_id 
                AND annonce_id = :annonce_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'etudiant_id' => $etudiantId,
            'annonce_id' => $annonceId
        ]);
    }
    
    /**
     * Vérifie si une annonce est en favori
     */
    public function exists(int $etudiantId, int $annonceId): bool
    {
        $result = $this->findOneWhere([
            'etudiant_id' => $etudiantId,
            'annonce_id' => $annonceId
        ]);
        
        return $result !== null;
    }
    
    /**
     * Compte les favoris d'un étudiant
     */
    public function countByEtudiant(int $etudiantId): int
    {
        return $this->count(['etudiant_id' => $etudiantId]);
    }
    
    /**
     * Supprime tous les favoris d'une annonce
     */
    public function deleteByAnnonce(int $annonceId): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE annonce_id = :annonce_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['annonce_id' => $annonceId]);
    }
    
    /**
     * Compte combien de fois une annonce est en favori
     */
    public function countByAnnonce(int $annonceId): int
    {
        return $this->count(['annonce_id' => $annonceId]);
    }
}
