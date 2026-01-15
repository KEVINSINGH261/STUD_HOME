<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Upload Images</title>
</head>
<body>
    <h1>Test Upload d'Images</h1>
    
    <?php
    require_once __DIR__ . '/../config/config.php';
    
    echo '<h2>Configuration</h2>';
    echo '<pre>';
    echo 'UPLOAD_PATH: ' . UPLOAD_PATH . "\n";
    echo 'MAX_FILE_SIZE: ' . MAX_FILE_SIZE . ' bytes (' . (MAX_FILE_SIZE / 1024 / 1024) . ' MB)' . "\n";
    echo 'ALLOWED_IMAGE_TYPES: ' . implode(', ', ALLOWED_IMAGE_TYPES) . "\n";
    echo '</pre>';
    
    echo '<h2>Vérifications</h2>';
    echo '<pre>';
    $uploadDir = UPLOAD_PATH . '/annonces';
    echo 'Dossier uploads/annonces existe: ' . (file_exists($uploadDir) ? 'OUI' : 'NON') . "\n";
    echo 'Dossier uploads/annonces accessible en écriture: ' . (is_writable($uploadDir) ? 'OUI' : 'NON') . "\n";
    echo '</pre>';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_photo'])) {
        echo '<h2>Résultat du Test</h2>';
        echo '<pre>';
        print_r($_FILES['test_photo']);
        echo '</pre>';
        
        $file = $_FILES['test_photo'];
        
        if ($file['error'] === UPLOAD_ERR_OK) {
            echo '<p style="color: green;">✓ Fichier uploadé sans erreur</p>';
            
            // Vérifier le type
            if (in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
                echo '<p style="color: green;">✓ Type MIME valide: ' . $file['type'] . '</p>';
            } else {
                echo '<p style="color: red;">✗ Type MIME non autorisé: ' . $file['type'] . '</p>';
            }
            
            // Vérifier la taille
            if ($file['size'] <= MAX_FILE_SIZE) {
                echo '<p style="color: green;">✓ Taille valide: ' . $file['size'] . ' bytes</p>';
            } else {
                echo '<p style="color: red;">✗ Fichier trop volumineux: ' . $file['size'] . ' bytes</p>';
            }
            
            // Tenter de déplacer
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'test_' . uniqid() . '.' . $extension;
            $destination = $uploadDir . '/' . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                echo '<p style="color: green;">✓ Fichier déplacé avec succès vers: ' . $destination . '</p>';
                echo '<img src="' . APP_URL . '/uploads/annonces/' . $filename . '" style="max-width: 300px; margin-top: 20px;">';
            } else {
                echo '<p style="color: red;">✗ Échec du déplacement du fichier</p>';
            }
        } else {
            echo '<p style="color: red;">✗ Erreur upload: ' . $file['error'] . '</p>';
        }
    }
    ?>
    
    <h2>Formulaire de Test</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="test_photo" accept="image/*" required>
        <button type="submit">Tester l'upload</button>
    </form>
    
    <hr>
    <p><a href="<?= APP_URL ?>">← Retour à l'accueil</a></p>
</body>
</html>
