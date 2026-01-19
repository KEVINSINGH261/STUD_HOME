<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migration Images - STUD'HOME</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #FF6B6B;
            margin-bottom: 20px;
        }
        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .info {
            background: #dbeafe;
            color: #1e40af;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        pre {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Migration: Support Images Multiples</h1>
        
        <?php
        require_once __DIR__ . '/../config/database.php';
        
        try {
            $db = Database::getInstance()->getConnection();
            
            echo '<div class="info"><strong>Étape 1:</strong> Création de la table annonces_images...</div>';
            
            // Créer la table annonces_images
            $sql = "CREATE TABLE IF NOT EXISTS annonces_images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                annonce_id INT NOT NULL,
                chemin VARCHAR(255) NOT NULL,
                ordre INT DEFAULT 0 COMMENT 'Ordre d''affichage',
                est_principale BOOLEAN DEFAULT FALSE COMMENT 'Image principale de l''annonce',
                date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
                INDEX idx_annonce (annonce_id),
                INDEX idx_ordre (ordre)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $db->exec($sql);
            echo '<div class="success">✓ Table annonces_images créée avec succès</div>';
            
            echo '<div class="info"><strong>Étape 2:</strong> Migration des images existantes...</div>';
            
            // Migrer les photos existantes
            $sql = "INSERT INTO annonces_images (annonce_id, chemin, ordre, est_principale)
                    SELECT id, photo, 0, TRUE
                    FROM annonces
                    WHERE photo IS NOT NULL AND photo != ''
                    AND NOT EXISTS (SELECT 1 FROM annonces_images WHERE annonce_id = annonces.id)";
            
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $migratedCount = $stmt->rowCount();
            
            echo '<div class="success">✓ ' . $migratedCount . ' image(s) existante(s) migrée(s)</div>';
            
            echo '<div class="info"><strong>Étape 3:</strong> Création du dossier d\'upload...</div>';
            
            // Créer le dossier uploads/annonces s'il n'existe pas
            $uploadDir = __DIR__ . '/../uploads/annonces';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
                echo '<div class="success">✓ Dossier uploads/annonces créé</div>';
            } else {
                echo '<div class="success">✓ Dossier uploads/annonces déjà existant</div>';
            }
            
            echo '<div class="success"><strong>🎉 Migration terminée avec succès!</strong></div>';
            
            echo '<div class="info">
                <strong>Prochaines étapes:</strong>
                <ul>
                    <li>Les propriétaires peuvent maintenant ajouter plusieurs images lors de la création d\'une annonce</li>
                    <li>Les images existantes ont été automatiquement migrées</li>
                    <li>Vous pouvez maintenant supprimer ce fichier (migration-images.php) pour des raisons de sécurité</li>
                </ul>
            </div>';
            
        } catch (PDOException $e) {
            echo '<div class="error">❌ Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        }
        ?>
    </div>
</body>
</html>
