<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Images - <?= htmlspecialchars($annonce['titre']) ?> - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/annonce-detail.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="annonce-details">
        <div class="container">
            <div class="annonce-header">
                <h1>Images - <?= htmlspecialchars($annonce['titre']) ?></h1>
                <a href="<?= APP_URL ?>/annonces/details/<?= $annonce['id'] ?>" class="btn-secondary">← Retour à l'annonce</a>
            </div>
            
            <?php if (!empty($images) && count($images) > 0): ?>
                <div class="images-gallery-page">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="image-gallery-item">
                            <img src="<?= APP_URL ?>/<?= htmlspecialchars($image['chemin']) ?>" alt="Image <?= $index + 1 ?>">
                            <div class="image-number">Image <?= $index + 1 ?> / <?= count($images) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 3rem; background: white; border-radius: 8px;">
                    <p style="font-size: 1.2rem; color: #666;">Aucune image disponible pour cette annonce.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <style>
        .images-gallery-page {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .image-gallery-item {
            position: relative;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .image-gallery-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }
        
        .image-gallery-item img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
        }
        
        .image-number {
            padding: 0.75rem 1rem;
            background: white;
            text-align: center;
            color: #333;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        @media (max-width: 768px) {
            .images-gallery-page {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }
    </style>
</body>
</html>
