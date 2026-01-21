<?php
try {
    $host = 'localhost';
    $user = 'root';
    $password = 'root';
    $dbname = 'stud_home_db';
    
    // Créer la connexion
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Sélectionner la base de données
    $pdo->exec("USE $dbname");
    
    // Créer la table
    $sql = "CREATE TABLE IF NOT EXISTS signalements (
      id INT(11) NOT NULL AUTO_INCREMENT,
      annonce_id INT(11) NOT NULL,
      utilisateur_id INT(11) NOT NULL,
      motif ENUM('spam','arnaque','contenu_inapproprie','annonce_disparue','autre') COLLATE utf8mb4_unicode_ci NOT NULL,
      description TEXT COLLATE utf8mb4_unicode_ci,
      statut ENUM('nouveau','en_cours','resolu','clos') COLLATE utf8mb4_unicode_ci DEFAULT 'nouveau',
      date_signalement DATETIME DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      UNIQUE KEY unique_signalement (annonce_id, utilisateur_id),
      KEY idx_annonce (annonce_id),
      KEY idx_utilisateur (utilisateur_id),
      KEY idx_statut (statut),
      FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
      FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✅ Table 'signalements' créée avec succès!";
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage();
}
?>
