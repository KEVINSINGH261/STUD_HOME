<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test STUD_HOME</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-card {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .info { color: #3b82f6; }
        h1 { color: #2563eb; }
        pre {
            background: #1e293b;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>🧪 Tests de Configuration STUD_HOME</h1>
    
    <div class="test-card">
        <h2>1️⃣ Configuration PHP</h2>
        <?php
        echo "<p class='success'>✅ PHP Version: " . PHP_VERSION . "</p>";
        echo "<p class='success'>✅ Serveur: " . $_SERVER['SERVER_SOFTWARE'] ?? 'PHP Built-in Server' . "</p>";
        ?>
    </div>
    
    <div class="test-card">
        <h2>2️⃣ Extensions PHP Requises</h2>
        <?php
        $extensions = ['pdo', 'pdo_mysql', 'mbstring', 'fileinfo'];
        foreach ($extensions as $ext) {
            if (extension_loaded($ext)) {
                echo "<p class='success'>✅ Extension {$ext} : Installée</p>";
            } else {
                echo "<p class='error'>❌ Extension {$ext} : MANQUANTE</p>";
            }
        }
        ?>
    </div>
    
    <div class="test-card">
        <h2>3️⃣ Configuration Projet</h2>
        <?php
        $configFile = __DIR__ . '/../config/config.php';
        if (file_exists($configFile)) {
            require_once $configFile;
            echo "<p class='success'>✅ Fichier config.php chargé</p>";
            echo "<p class='info'>📁 ROOT: " . ROOT . "</p>";
            echo "<p class='info'>🌐 APP_URL: " . APP_URL . "</p>";
        } else {
            echo "<p class='error'>❌ Fichier config.php introuvable</p>";
        }
        ?>
    </div>
    
    <div class="test-card">
        <h2>4️⃣ Structure des Dossiers</h2>
        <?php
        $dirs = [
            'app/core',
            'app/controllers',
            'app/models',
            'config',
            'views',
            'public/css',
            'public/js',
            'public/uploads/annonces'
        ];
        
        foreach ($dirs as $dir) {
            $path = __DIR__ . '/../' . $dir;
            if (is_dir($path)) {
                echo "<p class='success'>✅ {$dir}</p>";
            } else {
                echo "<p class='error'>❌ {$dir} : MANQUANT</p>";
            }
        }
        ?>
    </div>
    
    <div class="test-card">
        <h2>5️⃣ Connexion Base de Données</h2>
        <?php
        try {
            $dbConfig = require __DIR__ . '/../config/database.php';
            $dsn = "mysql:host={$dbConfig['host']};charset={$dbConfig['charset']}";
            $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
            echo "<p class='success'>✅ Connexion MySQL réussie</p>";
            
            // Vérifier si la base existe
            $stmt = $pdo->query("SHOW DATABASES LIKE '{$dbConfig['dbname']}'");
            if ($stmt->rowCount() > 0) {
                echo "<p class='success'>✅ Base de données '{$dbConfig['dbname']}' existe</p>";
                
                // Se connecter à la base
                $pdo = new PDO(
                    "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}",
                    $dbConfig['username'],
                    $dbConfig['password']
                );
                
                // Vérifier les tables
                $tables = ['utilisateurs', 'etudiants', 'proprietaires', 'annonces', 'favoris'];
                foreach ($tables as $table) {
                    $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
                    if ($stmt->rowCount() > 0) {
                        echo "<p class='success'>✅ Table '{$table}' existe</p>";
                    } else {
                        echo "<p class='error'>❌ Table '{$table}' manquante</p>";
                    }
                }
            } else {
                echo "<p class='error'>❌ Base de données '{$dbConfig['dbname']}' n'existe pas</p>";
                echo "<p class='info'>💡 Importez le fichier database/migrations/create_database.sql</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>❌ Erreur de connexion MySQL: " . $e->getMessage() . "</p>";
            echo "<p class='info'>💡 Vérifiez que MySQL est démarré et que les identifiants sont corrects dans config/database.php</p>";
        }
        ?>
    </div>
    
    <div class="test-card">
        <h2>6️⃣ Prochaines Étapes</h2>
        <?php
        $allGood = true;
        try {
            $dbConfig = require __DIR__ . '/../config/database.php';
            $pdo = new PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}",
                $dbConfig['username'],
                $dbConfig['password']
            );
            $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs");
            $allGood = $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            $allGood = false;
        }
        
        if ($allGood) {
            echo "<p class='success'>🎉 Tout est prêt ! Accédez à l'application :</p>";
            echo "<p><a href='" . APP_URL . "' style='color: #2563eb; font-weight: bold; font-size: 18px;'>👉 Accéder à STUD_HOME</a></p>";
            echo "<br><h3>Comptes de test :</h3>";
            echo "<pre><strong>Admin:</strong>
Email: admin@studhome.fr
Mot de passe: admin123

<strong>Étudiant:</strong>
Email: jean.dupont@example.com
Mot de passe: student123

<strong>Propriétaire:</strong>
Email: pierre.leroux@example.com
Mot de passe: owner123</pre>";
        } else {
            echo "<p class='info'>📋 Instructions d'installation :</p>";
            echo "<ol>";
            echo "<li>Installez XAMPP ou WAMP</li>";
            echo "<li>Démarrez Apache et MySQL</li>";
            echo "<li>Ouvrez phpMyAdmin (http://localhost/phpmyadmin)</li>";
            echo "<li>Importez le fichier <code>database/migrations/create_database.sql</code></li>";
            echo "<li>Rafraîchissez cette page</li>";
            echo "</ol>";
        }
        ?>
    </div>
</body>
</html>
