<?php
require_once __DIR__ . '/../config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    echo "=== Vérification et création de la table annonces_images ===\n\n";
    
    // Vérifier si la table existe
    $stmt = $db->query("SHOW TABLES LIKE 'annonces_images'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "✓ La table annonces_images existe déjà\n";
    } else {
        echo "✗ La table annonces_images n'existe pas - Création en cours...\n";
        
        // Créer la table
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
        echo "✓ Table annonces_images créée avec succès\n";
    }
    
    // Compter les enregistrements
    $count = $db->query("SELECT COUNT(*) FROM annonces_images")->fetchColumn();
    echo "\nNombre d'images dans la table: $count\n";
    
    // Afficher les dernières images
    if ($count > 0) {
        echo "\n=== Dernières images enregistrées ===\n";
        $stmt = $db->query("SELECT * FROM annonces_images ORDER BY date_ajout DESC LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "ID: {$row['id']}, Annonce: {$row['annonce_id']}, Chemin: {$row['chemin']}, Principale: " . ($row['est_principale'] ? 'OUI' : 'NON') . "\n";
        }
    }
    
    echo "\n✓ Migration terminée avec succès!\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
