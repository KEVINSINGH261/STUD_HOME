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
                            <?php if (!empty($favori['image_principale'])): ?>
                                <img src="<?= APP_URL ?>/<?= htmlspecialchars($favori['image_principale']) ?>" alt="<?= htmlspecialchars($favori['titre']) ?>">
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
                
                <!-- Pagination -->
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="pagination" style="margin-top: 2rem; display: flex; justify-content: center; gap: 0.5rem;">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= APP_URL ?>/etudiant/favoris?page=<?= $currentPage - 1 ?>" class="page-link" style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333;">&laquo; Précédent</a>
                    <?php endif; ?>
                    
                    <?php 
                    $start = max(1, $currentPage - 2);
                    $end = min($totalPages, $currentPage + 2);
                    
                    if ($start > 1): ?>
                        <a href="<?= APP_URL ?>/etudiant/favoris?page=1" class="page-link" style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333;">1</a>
                        <?php if ($start > 2): ?>
                            <span class="page-dots" style="padding: 0.5rem;">...</span>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <a href="<?= APP_URL ?>/etudiant/favoris?page=<?= $i ?>" class="page-link" style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: <?= $i === $currentPage ? '#fff' : '#333' ?>; background-color: <?= $i === $currentPage ? '#007bff' : 'transparent' ?>;"><?= $i ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($end < $totalPages): ?>
                        <?php if ($end < $totalPages - 1): ?>
                            <span class="page-dots" style="padding: 0.5rem;">...</span>
                        <?php endif; ?>
                        <a href="<?= APP_URL ?>/etudiant/favoris?page=<?= $totalPages ?>" class="page-link" style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333;"><?= $totalPages ?></a>
                    <?php endif; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= APP_URL ?>/etudiant/favoris?page=<?= $currentPage + 1 ?>" class="page-link" style="padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333;">Suivant &raquo;</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
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