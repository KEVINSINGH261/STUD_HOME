<?php
/**
 * Classe Controller - Contrôleur de base
 * Tous les contrôleurs héritent de cette classe
 */
abstract class Controller
{
    protected ?PDO $db = null;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Charge une vue avec des données
     */
    protected function view(string $viewPath, array $data = []): void
    {
        // Extraction des données pour les rendre disponibles dans la vue
        extract($data);
        
        // Construction du chemin complet de la vue
        $viewFile = VIEWS_PATH . '/' . $viewPath . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vue introuvable : {$viewPath}");
        }
    }
    
    /**
     * Charge un modèle
     */
    protected function model(string $modelName): object
    {
        $modelFile = APP_PATH . '/models/' . $modelName . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $modelName();
        }
        
        die("Modèle introuvable : {$modelName}");
    }
    
    /**
     * Redirection
     */
    protected function redirect(string $url): void
    {
        header("Location: " . APP_URL . '/' . ltrim($url, '/'));
        exit;
    }
    
    /**
     * Retourne une réponse JSON
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Vérifie si la requête est POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Vérifie si la requête est GET
     */
    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    /**
     * Récupère une donnée POST avec nettoyage
     */
    protected function post(string $key, $default = null)
    {
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }
    
    /**
     * Récupère une donnée GET avec nettoyage
     */
    protected function get(string $key, $default = null)
    {
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }
    
    /**
     * Nettoyage des données
     */
    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        
        return htmlspecialchars(trim($data), ENT_QUOTES, APP_CHARSET);
    }
    
    /**
     * Définit un message flash en session
     */
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Récupère et supprime le message flash
     */
    protected function getFlash(): ?array
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        
        return null;
    }
    
    /**
     * Vérifie si l'utilisateur est connecté
     */
    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Récupère l'ID de l'utilisateur connecté
     */
    protected function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Récupère le rôle de l'utilisateur connecté
     */
    protected function getUserRole(): ?string
    {
        return $_SESSION['user_role'] ?? null;
    }
    
    /**
     * Middleware: Requiert une authentification
     */
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->setFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            $this->redirect('login');
        }
    }
    
    /**
     * Middleware: Requiert un rôle spécifique
     */
    protected function requireRole(string $role): void
    {
        $this->requireAuth();
        
        if ($this->getUserRole() !== $role) {
            $this->setFlash('error', 'Accès non autorisé.');
            $this->redirect('home');
        }
    }
}
