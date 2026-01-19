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
        
        if (!empty($filters['type'])) {
            $sql .= " AND a.type = :type";
            $params['type'] = $filters['type'];
        }
        
        $sql .= " ORDER BY a.date_creation DESC";
        
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
}
