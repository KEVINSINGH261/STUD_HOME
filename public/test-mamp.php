<?php
/**
 * Page de test pour vérifier la configuration MAMP
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test MAMP - STUD_HOME</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .error { background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .info { background: #dbeafe; color: #1e40af; padding: 15px; border-radius: 8px; margin: 10px 0; }
        h1 { color: #2563eb; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; font-weight: 600; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 10px 20px; 
               text-decoration: none; border-radius: 6px; margin: 10px 5px; }
        .btn:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <h1>🧪 Test de Configuration MAMP - STUD_HOME</h1>
    
    <?php
    // Test 1: Version PHP
    echo '<div class="info">';
    echo '<strong>✅ PHP Version:</strong> ' . phpversion();
    echo ' (Minimum requis: 8.0)';
    echo '</div>';
    
    // Test 2: Extensions PHP
    echo '<h2>📦 Extensions PHP</h2>';
    echo '<table>';
    echo '<tr><th>Extension</th><th>Status</th></tr>';
    
    $extensions = ['pdo', 'pdo_mysql', 'mysqli', 'mbstring', 'gd'];
    foreach ($extensions as $ext) {
        $loaded = extension_loaded($ext);
        echo '<tr>';
        echo '<td>' . $ext . '</td>';
        echo '<td>' . ($loaded ? '✅ Activée' : '❌ Manquante') . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    
    // Test 3: Connexion MySQL
    echo '<h2>🔌 Test de Connexion MySQL</h2>';
    
    try {
        $pdo = new PDO(
            'mysql:host=localhost;charset=utf8mb4',
            'root',
            'root',  // Mot de passe par défaut MAMP
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        echo '<div class="success">';
        echo '<strong>✅ Connexion MySQL réussie !</strong><br>';
        echo 'Host: localhost<br>';
        echo 'Port: 8889 (par défaut MAMP)';
        echo '</div>';
        
        // Vérifier si la base existe
        $stmt = $pdo->query("SHOW DATABASES LIKE 'stud_home_db'");
        $dbExists = $stmt->rowCount() > 0;
        
        if ($dbExists) {
            echo '<div class="success">';
            echo '✅ La base de données <strong>stud_home_db</strong> existe déjà !';
            echo '</div>';
            
            // Compter les tables
            $pdo->query("USE stud_home_db");
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (count($tables) > 0) {
                echo '<div class="success">';
                echo '✅ Tables trouvées: ' . implode(', ', $tables);
                echo '</div>';
            }
        } else {
            echo '<div class="error">';
            echo '⚠️ La base de données <strong>stud_home_db</strong> n\'existe pas encore.<br>';
            echo 'Vous devez importer le fichier SQL.';
            echo '</div>';
        }
        
    } catch (PDOException $e) {
        echo '<div class="error">';
        echo '<strong>❌ Erreur de connexion MySQL:</strong><br>';
        echo $e->getMessage();
        echo '<br><br><strong>Solutions:</strong><br>';
        echo '1. Vérifiez que MAMP est démarré<br>';
        echo '2. Le mot de passe par défaut MAMP est "root"<br>';
        echo '3. Le port par défaut est 8889';
        echo '</div>';
    }
    
    // Test 4: Chemins
    echo '<h2>📁 Chemins du Projet</h2>';
    echo '<table>';
    echo '<tr><th>Dossier</th><th>Existe</th></tr>';
    
    $paths = [
        'app/core' => __DIR__ . '/../app/core',
        'app/controllers' => __DIR__ . '/../app/controllers',
        'app/models' => __DIR__ . '/../app/models',
        'config' => __DIR__ . '/../config',
        'views' => __DIR__ . '/../views',
        'public/css' => __DIR__ . '/css',
    ];
    
    foreach ($paths as $name => $path) {
        echo '<tr>';
        echo '<td>' . $name . '</td>';
        echo '<td>' . (is_dir($path) ? '✅ Existe' : '❌ Manquant') . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    ?>
    
    <h2>🚀 Prochaines Étapes</h2>
    
    <div class="info">
        <strong>1. Importer la base de données:</strong><br>
        - Ouvrez phpMyAdmin: <a href="http://localhost:8888/phpMyAdmin" target="_blank">http://localhost:8888/phpMyAdmin</a><br>
        - Créez une nouvelle base: <strong>stud_home_db</strong><br>
        - Importez le fichier: <code>database/migrations/create_database.sql</code>
    </div>
    
    <div class="info">
        <strong>2. Accéder à l'application:</strong><br>
        <a href="http://localhost:8888/STUD_HOME/public" class="btn">🏠 Ouvrir STUD_HOME</a>
    </div>
    
    <div class="info">
        <strong>3. Comptes de test:</strong><br>
        <strong>Admin:</strong> admin@studhome.fr / admin123<br>
        <strong>Étudiant:</strong> jean.dupont@example.com / student123<br>
        <strong>Propriétaire:</strong> pierre.leroux@example.com / owner123
    </div>
    
</body>
</html>
