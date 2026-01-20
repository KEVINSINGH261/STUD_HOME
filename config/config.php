<?php
/**
 * Configuration generale de l'application
 * STUD_HOME - Gestion de Logements Etudiants
 */

// Environnement (development | production)
define('APP_ENV', 'development');

// Configuration de l'application
define('APP_NAME', 'STUD_HOME');
define('APP_URL', 'http://localhost/STUD_HOME/public');
define('APP_CHARSET', 'UTF-8');

// Chemins absolus
define('ROOT', dirname(__DIR__));
define('APP_PATH', ROOT . '/app');
define('CONFIG_PATH', ROOT . '/config');
define('PUBLIC_PATH', ROOT . '/public');
define('VIEWS_PATH', ROOT . '/views');
define('STORAGE_PATH', ROOT . '/storage');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// Configuration de securite
define('SESSION_LIFETIME', 3600); // 1 heure
define('CSRF_TOKEN_NAME', '_csrf_token');
define('PASSWORD_MIN_LENGTH', 8);

// Configuration des uploads
define('MAX_FILE_SIZE', 5242880); // 5 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg', 'image/webp']);

// Timezone
date_default_timezone_set('Europe/Paris');

// Gestion des erreurs selon l'environnement
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', STORAGE_PATH . '/logs/error.log');
}