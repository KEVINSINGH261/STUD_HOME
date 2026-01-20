<?php
/**
 * Test de connexion à la base de données
 * Supprimez ce fichier après vérification
 */

// Configuration
$host = 'localhost';
$dbname = 'stud_home_db';
$username = 'root';
$password = 'root';

echo "<h1>Test de connexion MySQL avec PDO</h1>";

// Vérifier si PDO est disponible
if (!extension_loaded('pdo')) {
    die("<p style='color: red;'>❌ Extension PDO non chargée</p>");
}

if (!extension_loaded('pdo_mysql')) {
    die("<p style='color: red;'>❌ Extension PDO_MYSQL non chargée</p>");
}

echo "<p style='color: green;'>✅ Extension PDO chargée</p>";
echo "<p style='color: green;'>✅ Extension PDO_MYSQL chargée</p>";

// Test de connexion
try {
    $dsn = "mysql:host={$host};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Connexion au serveur MySQL réussie</p>";
    
    // Vérifier si la base de données existe
    $stmt = $pdo->query("SHOW DATABASES LIKE '{$dbname}'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ La base de données '{$dbname}' existe</p>";
        
        // Connexion à la base de données
        $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $username, $password);
        echo "<p style='color: green;'>✅ Connexion à la base de données '{$dbname}' réussie</p>";
        
        // Lister les tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "<p style='color: green;'>✅ Tables trouvées (" . count($tables) . ") :</p>";
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>{$table}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucune table trouvée dans la base de données</p>";
            echo "<p>Exécutez le fichier SQL : database/migrations/create_database.sql</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ La base de données '{$dbname}' n'existe pas</p>";
        echo "<p>Créez-la avec la commande SQL :</p>";
        echo "<pre>CREATE DATABASE stud_home_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</pre>";
        echo "<p>Ou exécutez le fichier : database/migrations/create_database.sql</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erreur : " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Configuration actuelle :</strong></p>";
echo "<ul>";
echo "<li>Host: {$host}</li>";
echo "<li>Base de données: {$dbname}</li>";
echo "<li>Username: {$username}</li>";
echo "</ul>";
