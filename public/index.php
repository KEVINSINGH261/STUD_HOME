<?php
/**
 * Front Controller - Point d'entrée unique de l'application
 * STUD_HOME - Gestion de Logements Étudiants
 */

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Chargement de la configuration
require_once '../config/config.php';

// Démarrage de la session
session_start();

// Autoloader simple pour charger automatiquement les classes
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/core/',
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/services/',
        APP_PATH . '/helpers/',
        APP_PATH . '/middlewares/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Chargement des routes
$routes = require_once CONFIG_PATH . '/routes.php';

// Initialisation du routeur
$router = new Router($routes);

// Traitement de la requête
$router->dispatch();
