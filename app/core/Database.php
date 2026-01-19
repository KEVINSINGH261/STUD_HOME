<?php
/**
 * Classe Database - Gestion de la connexion PDO
 * Pattern Singleton pour une instance unique
 */
class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;
    
    /**
     * Constructeur privé (Singleton)
     */
    private function __construct()
    {
        $config = require CONFIG_PATH . '/database.php';
        
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            
            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
            
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
        }
    }
    
    /**
     * Récupération de l'instance unique (Singleton)
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Récupération de la connexion PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
    
    /**
     * Log des erreurs
     */
    private function logError(string $message): void
    {
        $logFile = STORAGE_PATH . '/logs/database.log';
        $date = date('Y-m-d H:i:s');
        $logMessage = "[{$date}] {$message}\n";
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
    
    /**
     * Empêcher le clonage
     */
    private function __clone() {}
    
    /**
     * Empêcher la désérialisation
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
