<?php
/**
 * Modèle DemandeInteret
 * Gère les demandes d'intérêt des étudiants pour les annonces
 */
class DemandeInteret extends Model
{
    protected string $table = 'demandes_interet';

    /**
     * Crée une nouvelle demande d'intérêt
     */
    public function create(array $data): int
    {
        $data['date_demande'] = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO {$this->table} (annonce_id, etudiant_id, email, telephone, message, statut)
                VALUES (:annonce_id, :etudiant_id, :email, :telephone, :message, 'nouveau')";
        
        $this->query($sql, [
            'annonce_id' => $data['annonce_id'],
            'etudiant_id' => $data['etudiant_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'message' => $data['message'] ?? null
        ]);
        
        return (int)$this->db->lastInsertId();
    }

    /**
     * Récupère toutes les demandes pour une annonce
     */
    public function getByAnnonce(int $annonceId): array
    {
        $sql = "SELECT d.*, u.nom, u.prenom, u.email as utilisateur_email
                FROM {$this->table} d
                INNER JOIN utilisateurs u ON d.etudiant_id = u.id
                WHERE d.annonce_id = :annonce_id
                ORDER BY d.date_demande DESC";
        
        return $this->query($sql, ['annonce_id' => $annonceId]);
    }

    /**
     * Récupère toutes les demandes pour les annonces d'un propriétaire
     */
    public function getByProprietaire(int $proprietaireId): array
    {
        $sql = "SELECT d.*, u.nom, u.prenom, u.email as utilisateur_email, 
                       a.titre as annonce_titre, a.id as annonce_id
                FROM {$this->table} d
                INNER JOIN utilisateurs u ON d.etudiant_id = u.id
                INNER JOIN annonces a ON d.annonce_id = a.id
                WHERE a.proprietaire_id = :proprietaire_id
                ORDER BY d.date_demande DESC";
        
        return $this->query($sql, ['proprietaire_id' => $proprietaireId]);
    }

    /**
     * Récupère toutes les demandes avec un statut spécifique pour un propriétaire
     */
    public function getByProprietaireAndStatut(int $proprietaireId, string $statut): array
    {
        $sql = "SELECT d.*, u.nom, u.prenom, u.email as utilisateur_email,
                       a.titre as annonce_titre, a.id as annonce_id
                FROM {$this->table} d
                INNER JOIN utilisateurs u ON d.etudiant_id = u.id
                INNER JOIN annonces a ON d.annonce_id = a.id
                WHERE a.proprietaire_id = :proprietaire_id AND d.statut = :statut
                ORDER BY d.date_demande DESC";
        
        return $this->query($sql, [
            'proprietaire_id' => $proprietaireId,
            'statut' => $statut
        ]);
    }

    /**
     * Récupère une demande par ID
     */
    public function findById(int $id): ?array
    {
        return $this->findOne(['id' => $id]);
    }

    /**
     * Vérifie si une demande existe déjà (évite les doublons)
     */
    public function exists(int $annonceId, int $etudiantId): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}
                WHERE annonce_id = :annonce_id AND etudiant_id = :etudiant_id
                AND statut IN ('nouveau', 'vue', 'accepte')";
        
        $result = $this->query($sql, [
            'annonce_id' => $annonceId,
            'etudiant_id' => $etudiantId
        ]);
        
        return (isset($result[0]['count']) && $result[0]['count'] > 0);
    }

    /**
     * Met à jour le statut d'une demande
     */
    public function updateStatut(int $id, string $statut): bool
    {
        $sql = "UPDATE {$this->table} 
                SET statut = :statut, date_reponse = NOW()
                WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':statut' => $statut, ':id' => $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Marque une demande comme vue
     */
    public function markAsViewed(int $id): bool
    {
        return $this->updateStatut($id, 'vue');
    }

    /**
     * Compte les demandes par statut pour un propriétaire
     */
    public function countByStatutForProprietaire(int $proprietaireId, string $statut): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} d
                INNER JOIN annonces a ON d.annonce_id = a.id
                WHERE a.proprietaire_id = :proprietaire_id AND d.statut = :statut";
        
        $result = $this->query($sql, [
            'proprietaire_id' => $proprietaireId,
            'statut' => $statut
        ]);
        
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
    }

    /**
     * Supprime une demande
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
