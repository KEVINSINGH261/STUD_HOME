<?php
/**
 * Modèle Annonce - Gestion des annonces de logement
 */
class Annonce extends Model
{
    protected string $table = 'annonces';
    
    /**
     * Récupère toutes les annonces avec les informations du propriétaire
     */
    public function findAllWithProprietaire(): array
    {
        $sql = "SELECT a.*, u.nom, u.prenom, u.email, p.telephone
                FROM {$this->table} a
                INNER JOIN utilisateurs u ON a.proprietaire_id = u.id
                INNER JOIN proprietaires p ON u.id = p.utilisateur_id
                WHERE a.statut = 'active'
                ORDER BY a.date_creation DESC";
        
        return $this->query($sql);
    }
    
    /**
     * Récupère toutes les annonces avec pagination
     */
    public function findAllWithProprietairePaginated(int $limit = 9, int $offset = 0): array
    {
        $sql = "SELECT a.*, u.nom, u.prenom, u.email, p.telephone
                FROM {$this->table} a
                INNER JOIN utilisateurs u ON a.proprietaire_id = u.id
                INNER JOIN proprietaires p ON u.id = p.utilisateur_id
                WHERE a.statut = 'active'
                ORDER BY a.date_creation DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Compte le nombre total d'annonces actives
     */
    public function countActive(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE statut = 'active'";
        $result = $this->queryOne($sql);
        return (int)$result['total'];
    }
    
    /**
     * Récupère une annonce par ID avec infos propriétaire
     */
    public function findByIdWithProprietaire(int $id): ?array
    {
        $sql = "SELECT a.*, u.nom, u.prenom, u.email, p.telephone
                FROM {$this->table} a
                INNER JOIN utilisateurs u ON a.proprietaire_id = u.id
                INNER JOIN proprietaires p ON u.id = p.utilisateur_id
                WHERE a.id = :id";
        
        return $this->queryOne($sql, ['id' => $id]);
    }
    
    /**
     * Recherche d'annonces avec filtres
     */
    public function search(array $filters): array
    {
        $sql = "SELECT a.*, u.nom, u.prenom
                FROM {$this->table} a
                INNER JOIN utilisateurs u ON a.proprietaire_id = u.id
                WHERE a.statut = 'active'";
        
        $params = [];
        
        if (!empty($filters['ville'])) {
            $sql .= " AND a.ville LIKE :ville";
            $params['ville'] = '%' . $filters['ville'] . '%';
        }
        
        if (!empty($filters['prix_min'])) {
            $sql .= " AND a.prix >= :prix_min";
            $params['prix_min'] = $filters['prix_min'];
        }
        
        if (!empty($filters['prix_max'])) {
            $sql .= " AND a.prix <= :prix_max";
            $params['prix_max'] = $filters['prix_max'];
        }
        
        // Handle type as array (from checkboxes) or single value
        if (!empty($filters['type'])) {
            $typeArray = is_array($filters['type']) ? $filters['type'] : [$filters['type']];
            $typeArray = array_filter($typeArray); // Remove empty values
            
            if (!empty($typeArray)) {
                $placeholders = [];
                foreach ($typeArray as $index => $type) {
                    $key = "type_{$index}";
                    $placeholders[] = ":{$key}";
                    $params[$key] = $type;
                }
                $sql .= " AND a.type IN (" . implode(', ', $placeholders) . ")";
            }
        }
        
        // Handle sorting
        $sort = $filters['sort'] ?? 'pertinence';
        switch ($sort) {
            case 'prix_asc':
                $sql .= " ORDER BY a.prix ASC";
                break;
            case 'prix_desc':
                $sql .= " ORDER BY a.prix DESC";
                break;
            case 'surface_desc':
                $sql .= " ORDER BY a.surface DESC";
                break;
            case 'recent':
                $sql .= " ORDER BY a.date_creation DESC";
                break;
            case 'pertinence':
            default:
                $sql .= " ORDER BY a.date_creation DESC";
                break;
        }
        
        return $this->query($sql, $params);
    }
    
    /**
     * Récupère les annonces d'un propriétaire
     */
    public function findByProprietaire(int $proprietaireId): array
    {
        return $this->findWhere(['proprietaire_id' => $proprietaireId]);
    }
    
    /**
     * Crée une nouvelle annonce
     */
    public function create(array $data): int
    {
        $data['date_creation'] = date('Y-m-d H:i:s');
        $data['statut'] = 'active';
        
        return $this->insert($data);
    }
    
    /**
     * Met à jour le statut d'une annonce
     */
    public function updateStatut(int $id, string $statut): bool
    {
        return $this->update($id, ['statut' => $statut]);
    }
    
    /**
     * Compte les annonces par statut
     */
    public function countByStatut(string $statut): int
    {
        return $this->count(['statut' => $statut]);
    }
    
    /**
     * Récupère les annonces récentes (dernières 5)
     */
    public function findRecent(int $limit = 5): array
    {
        $sql = "SELECT a.*, u.nom, u.prenom
                FROM {$this->table} a
                INNER JOIN utilisateurs u ON a.proprietaire_id = u.id
                WHERE a.statut = 'active'
                ORDER BY a.date_creation DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Vérifie si une annonce appartient à un propriétaire
     */
    public function belongsTo(int $annonceId, int $proprietaireId): bool
    {
        $result = $this->findOneWhere([
            'id' => $annonceId,
            'proprietaire_id' => $proprietaireId
        ]);
        
        return $result !== null;
    }
    
    /**
     * Récupère toutes les images d'une annonce
     */
    public function getImages(int $annonceId): array
    {
        $sql = "SELECT * FROM annonces_images 
                WHERE annonce_id = :annonce_id 
                ORDER BY est_principale DESC, ordre ASC";
        
        return $this->query($sql, ['annonce_id' => $annonceId]);
    }
    
    /**
     * Ajoute une image à une annonce
     */
    public function addImage(int $annonceId, string $chemin, bool $estPrincipale = false, int $ordre = 0): bool
    {
        try {
            // Si c'est l'image principale, retirer le flag des autres images
            if ($estPrincipale) {
                $sql = "UPDATE annonces_images SET est_principale = FALSE WHERE annonce_id = :annonce_id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['annonce_id' => $annonceId]);
                error_log("DEBUG addImage - Flag principale retiré pour annonce $annonceId");
            }
            
            $sql = "INSERT INTO annonces_images (annonce_id, chemin, ordre, est_principale, date_ajout) 
                    VALUES (:annonce_id, :chemin, :ordre, :est_principale, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'annonce_id' => $annonceId,
                'chemin' => $chemin,
                'ordre' => $ordre,
                'est_principale' => $estPrincipale ? 1 : 0
            ]);
            
            if ($result) {
                $insertId = $this->db->lastInsertId();
                error_log("DEBUG addImage - Image ajoutée avec succès. ID: $insertId, Annonce: $annonceId, Chemin: $chemin");
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("DEBUG addImage - Échec insertion: " . print_r($errorInfo, true));
            }
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("ERREUR addImage - " . $e->getMessage());
            error_log("ERREUR addImage - SQL State: " . $e->getCode());
            return false;
        }
    }
    
    /**
     * Supprime une image
     */
    public function deleteImage(int $imageId): bool
    {
        $sql = "DELETE FROM annonces_images WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $imageId]);
    }
    
    /**
     * Récupère l'image principale d'une annonce
     */
    public function getImagePrincipale(int $annonceId): ?string
    {
        $sql = "SELECT chemin FROM annonces_images 
                WHERE annonce_id = :annonce_id AND est_principale = TRUE 
                LIMIT 1";
        
        $result = $this->queryOne($sql, ['annonce_id' => $annonceId]);
        
        if ($result) {
            return $result['chemin'];
        }
        
        // Si pas d'image principale, prendre la première image
        $sql = "SELECT chemin FROM annonces_images 
                WHERE annonce_id = :annonce_id 
                ORDER BY ordre ASC 
                LIMIT 1";
        
        $result = $this->queryOne($sql, ['annonce_id' => $annonceId]);
        return $result ? $result['chemin'] : null;
    }
    
    /**
     * Définit une image comme principale
     */
    public function setImagePrincipale(int $imageId, int $annonceId): bool
    {
        // Retirer le flag des autres images
        $sql = "UPDATE annonces_images SET est_principale = FALSE WHERE annonce_id = :annonce_id";
        $this->db->prepare($sql)->execute(['annonce_id' => $annonceId]);
        
        // Définir la nouvelle image principale
        $sql = "UPDATE annonces_images SET est_principale = TRUE WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $imageId]);
    }
}
