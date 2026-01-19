<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Diagnostic Images</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #FF6B6B; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f9f9f9; font-weight: 600; }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .warning { color: #f59e0b; }
        pre { background: #f9f9f9; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="box">
        <h2>📊 Diagnostic du Système d'Images</h2>
        
        <?php
        try {
            $db = Database::getInstance()->getConnection();
            
            echo '<h3>1. Vérification de la table annonces_images</h3>';
            $stmt = $db->query("SHOW TABLES LIKE 'annonces_images'");
            if ($stmt->rowCount() > 0) {
                echo '<p class="success">✓ Table annonces_images existe</p>';
                
                // Structure de la table
                echo '<h4>Structure de la table :</h4>';
                $columns = $db->query("DESCRIBE annonces_images")->fetchAll(PDO::FETCH_ASSOC);
                echo '<table><tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Default</th></tr>';
                foreach ($columns as $col) {
                    echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Null']}</td><td>{$col['Key']}</td><td>{$col['Default']}</td></tr>";
                }
                echo '</table>';
            } else {
                echo '<p class="error">✗ Table annonces_images n\'existe pas</p>';
            }
            
            echo '<h3>2. Annonces existantes</h3>';
            $annonces = $db->query("SELECT id, titre, proprietaire_id, DATE_FORMAT(date_creation, '%d/%m/%Y %H:%i') as date_creation FROM annonces ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
            if (count($annonces) > 0) {
                echo '<table><tr><th>ID</th><th>Titre</th><th>Propriétaire</th><th>Date création</th></tr>';
                foreach ($annonces as $a) {
                    echo "<tr><td>{$a['id']}</td><td>{$a['titre']}</td><td>{$a['proprietaire_id']}</td><td>{$a['date_creation']}</td></tr>";
                }
                echo '</table>';
            } else {
                echo '<p class="warning">Aucune annonce trouvée</p>';
            }
            
            echo '<h3>3. Images enregistrées dans annonces_images</h3>';
            $images = $db->query("SELECT ai.*, a.titre FROM annonces_images ai LEFT JOIN annonces a ON ai.annonce_id = a.id ORDER BY ai.date_ajout DESC")->fetchAll(PDO::FETCH_ASSOC);
            if (count($images) > 0) {
                echo '<p class="success">✓ ' . count($images) . ' image(s) enregistrée(s)</p>';
                echo '<table><tr><th>ID</th><th>Annonce</th><th>Titre</th><th>Chemin</th><th>Principale</th></tr>';
                foreach ($images as $img) {
                    echo "<tr><td>{$img['id']}</td><td>{$img['annonce_id']}</td><td>{$img['titre']}</td><td>{$img['chemin']}</td><td>" . ($img['est_principale'] ? 'OUI' : 'NON') . "</td></tr>";
                }
                echo '</table>';
            } else {
                echo '<p class="warning">⚠ Aucune image enregistrée dans la base de données</p>';
            }
            
            echo '<h3>4. Fichiers physiques dans uploads/annonces</h3>';
            $uploadDir = __DIR__ . '/uploads/annonces';
            if (is_dir($uploadDir)) {
                $files = array_diff(scandir($uploadDir), array('.', '..'));
                if (count($files) > 0) {
                    echo '<p class="warning">⚠ ' . count($files) . ' fichier(s) physique(s) trouvé(s)</p>';
                    echo '<table><tr><th>Nom du fichier</th><th>Taille</th><th>Date</th></tr>';
                    foreach ($files as $file) {
                        $filePath = $uploadDir . '/' . $file;
                        $size = filesize($filePath);
                        $date = date('d/m/Y H:i', filemtime($filePath));
                        echo "<tr><td>$file</td><td>" . round($size / 1024, 2) . " Ko</td><td>$date</td></tr>";
                    }
                    echo '</table>';
                } else {
                    echo '<p class="warning">Aucun fichier physique trouvé</p>';
                }
            } else {
                echo '<p class="error">✗ Dossier uploads/annonces n\'existe pas</p>';
            }
            
            echo '<h3>5. Diagnostic</h3>';
            if (count($images) === 0 && count($files ?? []) > 0) {
                echo '<div style="background: #fef3c7; padding: 15px; border-radius: 6px; border-left: 4px solid #f59e0b;">';
                echo '<p><strong>⚠️ Problème détecté :</strong></p>';
                echo '<p>Les images ont été uploadées physiquement MAIS ne sont pas enregistrées dans la base de données.</p>';
                echo '<p><strong>Raison :</strong> La table annonces_images n\'existait pas au moment de l\'upload.</p>';
                echo '<p><strong>Solution :</strong> Créez une NOUVELLE annonce avec une image maintenant que la table existe.</p>';
                echo '</div>';
            } elseif (count($images) > 0) {
                echo '<div style="background: #d1fae5; padding: 15px; border-radius: 6px; border-left: 4px solid #10b981;">';
                echo '<p><strong>✅ Tout fonctionne correctement !</strong></p>';
                echo '<p>Les images sont uploadées et enregistrées dans la base de données.</p>';
                echo '</div>';
            }
            
        } catch (PDOException $e) {
            echo '<div style="background: #fee2e2; padding: 15px; border-radius: 6px; border-left: 4px solid #ef4444;">';
            echo '<p><strong>❌ Erreur de base de données :</strong></p>';
            echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
            echo '</div>';
        }
        ?>
        
        <hr style="margin: 30px 0;">
        <p><a href="<?= APP_URL ?>">← Retour à l'accueil</a> | <a href="<?= APP_URL ?>/proprietaire/annonces/create">Créer une nouvelle annonce</a></p>
    </div>
</body>
</html>
