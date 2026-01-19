<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/favoris.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="favoris-page">
        <div class="container">
            <h1>Mes Favoris</h1>
            
            <?php if (isset($flash) && $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($favoris)): ?>
                <div class="annonces-grid">
                    <?php foreach ($favoris as $favori): ?>
                        <div class="annonce-card">
                            <?php if ($favori['photo']): ?>
                                <img src="<?= APP_URL ?>/<?= htmlspecialchars($favori['photo']) ?>" alt="<?= htmlspecialchars($favori['titre']) ?>">
                            <?php else: ?>
                                <div class="no-image">📷 Pas de photo</div>
                            <?php endif; ?>
                            
                            <div class="annonce-info">
                                <h3><?= htmlspecialchars($favori['titre']) ?></h3>
                                <p class="location"><?= htmlspecialchars($favori['ville']) ?></p>
                                <p class="price"><?= number_format($favori['prix'], 0, ',', ' ') ?> €/mois</p>
                                <p class="details">
                                    <?= $favori['surface'] ?> m² • 
                                    <?= $favori['nombre_chambres'] ?> chambre(s)
                                </p>
                                <div class="card-actions">
                                    <a href="<?= APP_URL ?>/annonces/details/<?= $favori['annonce_id'] ?>" class="btn-secondary">Voir détails</a>
                                    <a href="<?= APP_URL ?>/etudiant/favoris/remove/<?= $favori['annonce_id'] ?>" class="btn-danger">Retirer</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>Vous n'avez pas encore de favoris.</p>
                    <a href="<?= APP_URL ?>/annonces" class="btn-primary">Parcourir les annonces</a>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script src="<?= APP_URL ?>/js/main.js"></script>
</body>
</html>