<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/models/Annonce.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Insert Image</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #FF6B6B; margin-top: 0; }
        .success { background: #d1fae5; color: #065f46; padding: 15px; border-radius: 6px; margin: 10px 0; }
        .error { background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 6px; margin: 10px 0; }
        pre { background: #f9f9f9; padding: 10px; border-radius: 4px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="box">
        <h2>🧪 Test d'insertion d'image dans annonces_images</h2>
        
        <?php
        try {
            $annonceModel = new Annonce();
            
            // Récupérer la dernière annonce
            echo '<h3>1. Récupération de la dernière annonce</h3>';
            $db = Database::getInstance()->getConnection();
            $lastAnnonce = $db->query("SELECT id, titre FROM annonces ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            
            if (!$lastAnnonce) {
                echo '<div class="error">❌ Aucune annonce trouvée. Créez d\'abord une annonce.</div>';
                exit;
            }
            
            echo '<div class="success">✓ Annonce trouvée: ID=' . $lastAnnonce['id'] . ', Titre="' . htmlspecialchars($lastAnnonce['titre']) . '"</div>';
            
            // Vérifier si des images existent déjà
            echo '<h3>2. Images existantes pour cette annonce</h3>';
            $existingImages = $annonceModel->getImages($lastAnnonce['id']);
            if (count($existingImages) > 0) {
                echo '<table><tr><th>ID</th><th>Chemin</th><th>Principale</th></tr>';
                foreach ($existingImages as $img) {
                    echo "<tr><td>{$img['id']}</td><td>{$img['chemin']}</td><td>" . ($img['est_principale'] ? 'OUI' : 'NON') . "</td></tr>";
                }
                echo '</table>';
            } else {
                echo '<p>Aucune image existante</p>';
            }
            
            // Test d'insertion
            echo '<h3>3. Test d\'insertion d\'une image de test</h3>';
            $testChemin = 'uploads/annonces/test_' . time() . '.jpg';
            $result = $annonceModel->addImage($lastAnnonce['id'], $testChemin, true, 0);
            
            if ($result) {
                echo '<div class="success">✅ Image de test ajoutée avec succès!</div>';
                
                // Vérifier l'insertion
                echo '<h3>4. Vérification de l\'insertion</h3>';
                $newImages = $annonceModel->getImages($lastAnnonce['id']);
                echo '<table><tr><th>ID</th><th>Chemin</th><th>Principale</th><th>Date</th></tr>';
                foreach ($newImages as $img) {
                    echo "<tr><td>{$img['id']}</td><td>{$img['chemin']}</td><td>" . ($img['est_principale'] ? 'OUI' : 'NON') . "</td><td>{$img['date_ajout']}</td></tr>";
                }
                echo '</table>';
                
                echo '<div class="success">
                    <strong>✅ Succès !</strong>
                    <p>La méthode addImage() fonctionne correctement.</p>
                    <p>Si vos images ne s\'enregistrent pas lors de la création d\'annonce, le problème vient du code d\'upload.</p>
                </div>';
            } else {
                echo '<div class="error">❌ Échec de l\'insertion. Consultez les logs PHP.</div>';
            }
            
            // Afficher les logs
            echo '<h3>5. Logs PHP récents</h3>';
            echo '<p><em>Vérifiez le fichier : C:\\MAMP\\logs\\php_error.log</em></p>';
            
        } catch (Exception $e) {
            echo '<div class="error">❌ Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        }
        ?>
        
        <hr style="margin: 30px 0;">
        <p><a href="diagnostic-images.php">Voir le diagnostic complet</a> | <a href="<?= APP_URL ?>">Retour à l'accueil</a></p>
    </div>
</body>
</html>
