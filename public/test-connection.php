<?php
/**
 * Test de connexion à la base de données
 */

echo "<h1>Test de Connexion MySQL - MAMP</h1>";

// Test 1: Connexion sans port spécifique
echo "<h2>Test 1: localhost (défaut)</h2>";
try {
    $pdo1 = new PDO(
        'mysql:host=localhost;dbname=stud_home_db;charset=utf8mb4',
        'root',
        'root',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ <strong>SUCCÈS</strong> - Connexion réussie avec localhost<br>";
    $pdo1 = null;
} catch (PDOException $e) {
    echo "❌ <strong>ÉCHEC</strong> - " . $e->getMessage() . "<br>";
}

// Test 2: Connexion avec port 8889
echo "<h2>Test 2: localhost:8889</h2>";
try {
    $pdo2 = new PDO(
        'mysql:host=localhost;port=8889;dbname=stud_home_db;charset=utf8mb4',
        'root',
        'root',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ <strong>SUCCÈS</strong> - Connexion réussie avec localhost:8889<br>";
    $pdo2 = null;
} catch (PDOException $e) {
    echo "❌ <strong>ÉCHEC</strong> - " . $e->getMessage() . "<br>";
}

// Test 3: Connexion avec 127.0.0.1
echo "<h2>Test 3: 127.0.0.1</h2>";
try {
    $pdo3 = new PDO(
        'mysql:host=127.0.0.1;dbname=stud_home_db;charset=utf8mb4',
        'root',
        'root',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ <strong>SUCCÈS</strong> - Connexion réussie avec 127.0.0.1<br>";
    $pdo3 = null;
} catch (PDOException $e) {
    echo "❌ <strong>ÉCHEC</strong> - " . $e->getMessage() . "<br>";
}

// Test 4: Connexion avec 127.0.0.1:8889
echo "<h2>Test 4: 127.0.0.1:8889</h2>";
try {
    $pdo4 = new PDO(
        'mysql:host=127.0.0.1;port=8889;dbname=stud_home_db;charset=utf8mb4',
        'root',
        'root',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ <strong>SUCCÈS</strong> - Connexion réussie avec 127.0.0.1:8889<br>";
    
    // Compter les utilisateurs
    $stmt = $pdo4->query("SELECT COUNT(*) as total FROM utilisateurs");
    $result = $stmt->fetch();
    echo "📊 Nombre d'utilisateurs: <strong>" . $result['total'] . "</strong><br>";
    
    $pdo4 = null;
} catch (PDOException $e) {
    echo "❌ <strong>ÉCHEC</strong> - " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h2>Configuration actuelle dans database.php:</h2>";
$config = require __DIR__ . '/../config/database.php';
echo "<pre>";
print_r($config);
echo "</pre>";

echo "<hr>";
echo "<a href='http://localhost:8888/STUD_HOME/public/' style='display:inline-block; background:#2563eb; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Tester l'application</a>";
?>

<style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
    h1 { color: #2563eb; }
    h2 { color: #334155; margin-top: 30px; }
</style>
