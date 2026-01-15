<?php
/**
 * Classe Router - Gestion du routage de l'application
 * Dispatche les requêtes vers les contrôleurs appropriés
 */
class Router
{
    private array $routes;
    private string $url;
    private array $params = [];
    
    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->url = $this->getUrl();
    }
    
    /**
     * Récupération et nettoyage de l'URL
     */
    private function getUrl(): string
    {
        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return $url;
        }
        
        return '';
    }
    
    /**
     * Dispatche la requête vers le bon contrôleur
     */
    public function dispatch(): void
    {
        $matchedRoute = $this->matchRoute();
        
        if ($matchedRoute === null) {
            $this->handleNotFound();
            return;
        }
        
        [$controller, $method] = explode('@', $matchedRoute);
        
        // Vérification de l'existence du contrôleur
        if (!class_exists($controller)) {
            $this->handleNotFound();
            return;
        }
        
        // Instanciation du contrôleur
        $controllerInstance = new $controller();
        
        // Vérification de l'existence de la méthode
        if (!method_exists($controllerInstance, $method)) {
            $this->handleNotFound();
            return;
        }
        
        // Appel de la méthode avec les paramètres
        call_user_func_array([$controllerInstance, $method], $this->params);
    }
    
    /**
     * Correspondance entre l'URL et les routes définies
     */
    private function matchRoute(): ?string
    {
        foreach ($this->routes as $route => $target) {
            // Conversion de la route en regex
            $pattern = $this->convertRouteToRegex($route);
            
            if (preg_match($pattern, $this->url, $matches)) {
                // Extraction des paramètres dynamiques
                array_shift($matches); // Retire le match complet
                $this->params = $matches;
                
                return $target;
            }
        }
        
        return null;
    }
    
    /**
     * Conversion d'une route en expression régulière
     */
    private function convertRouteToRegex(string $route): string
    {
        // Remplace {id} par un pattern regex pour capturer les paramètres
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '([0-9]+)', $route);
        $pattern = str_replace('/', '\/', $pattern);
        
        return '/^' . $pattern . '$/';
    }
    
    /**
     * Gestion des erreurs 404
     */
    private function handleNotFound(): void
    {
        http_response_code(404);
        
        if (file_exists(VIEWS_PATH . '/errors/404.php')) {
            require_once VIEWS_PATH . '/errors/404.php';
        } else {
            echo "<h1>404 - Page non trouvée</h1>";
            echo "<p>La page que vous recherchez n'existe pas.</p>";
        }
        
        exit;
    }
}
