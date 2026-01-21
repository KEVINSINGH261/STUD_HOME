<?php
/**
 * Classe Model - Modèle de base
 * Tous les modèles héritent de cette classe
 */
abstract class Model
{
    protected PDO $db;
    protected string $table;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Récupère tous les enregistrements
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère un enregistrement par ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Récupère des enregistrements selon des conditions
     */
    public function findWhere(array $conditions): array
    {
        $where = [];
        $params = [];
        
        foreach ($conditions as $column => $value) {
            $where[] = "{$column} = :{$column}";
            $params[$column] = $value;
        }
        
        $whereClause = implode(' AND ', $where);
        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère un seul enregistrement selon des conditions
     */
    public function findOneWhere(array $conditions): ?array
    {
        $results = $this->findWhere($conditions);
        return !empty($results) ? $results[0] : null;
    }
    
    /**
     * Insertion d'un enregistrement
     */
    public function insert(array $data): int
    {
        // Exclure l'id s'il n'a pas de valeur
        if (isset($data['id']) && empty($data['id'])) {
            unset($data['id']);
        }
        
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":{$col}", $columns);
        
        $columnsStr = implode(', ', $columns);
        $placeholdersStr = implode(', ', $placeholders);
        
        $sql = "INSERT INTO {$this->table} ({$columnsStr}) VALUES ({$placeholdersStr})";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        return (int) $this->db->lastInsertId();
    }
    
    /**
     * Mise à jour d'un enregistrement
     */
    public function update(int $id, array $data): bool
    {
        $set = [];
        
        foreach ($data as $column => $value) {
            $set[] = "{$column} = :{$column}";
        }
        
        $setClause = implode(', ', $set);
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * Suppression d'un enregistrement
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Compte le nombre d'enregistrements
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if (!empty($conditions)) {
            $where = [];
            $params = [];
            
            foreach ($conditions as $column => $value) {
                $where[] = "{$column} = :{$column}";
                $params[$column] = $value;
            }
            
            $whereClause = implode(' AND ', $where);
            $sql .= " WHERE {$whereClause}";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }
        
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
    
    /**
     * Exécution d'une requête personnalisée
     */
    protected function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Exécution d'une requête personnalisée (une seule ligne)
     */
    protected function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
