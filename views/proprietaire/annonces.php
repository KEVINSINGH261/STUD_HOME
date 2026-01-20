<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Annonces - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/proprietaire-annonces.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="annonces-page">
        <div class="container">
            <div class="page-header">
                <h1>Mes Annonces</h1>
            </div>
            
            <?php if (isset($flash) && $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>

            <?php if (empty($annonces)): ?>
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <h2>Aucune annonce pour le moment</h2>
                    <p>Créez votre première annonce pour commencer à louer votre logement</p>
                    <a href="<?= APP_URL ?>/proprietaire/annonces/create" class="btn-primary">Créer ma première annonce</a>
                </div>
            <?php else: ?>
                <div class="annonces-grid">
                    <?php foreach ($annonces as $annonce): ?>
                        <div class="annonce-card">
                            <div class="annonce-image">
                                <?php if (!empty($annonce['image_principale'])): ?>
                                    <img src="<?= APP_URL ?>/<?= htmlspecialchars($annonce['image_principale']) ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                                <?php elseif (!empty($annonce['photo'])): ?>
                                    <img src="<?= APP_URL ?>/<?= htmlspecialchars($annonce['photo']) ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                                <?php else: ?>
                                    <div class="no-image">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <polyline points="21 15 16 10 5 21"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <span class="badge badge-<?= $annonce['statut'] ?>">
                                    <?= $annonce['statut'] === 'active' ? 'Publié' : 'Brouillon' ?>
                                </span>
                            </div>
                            
                            <div class="annonce-content">
                                <h3><?= htmlspecialchars($annonce['titre']) ?></h3>
                                <p class="annonce-location">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <?= htmlspecialchars($annonce['ville']) ?>
                                </p>
                                
                                <div class="annonce-details">
                                    <span class="detail-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                        </svg>
                                        <?= htmlspecialchars($annonce['type']) ?>
                                    </span>
                                    <span class="detail-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        </svg>
                                        <?= htmlspecialchars($annonce['surface']) ?> m²
                                    </span>
                                    <span class="detail-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 7h20M2 12h20M2 17h20"/>
                                        </svg>
                                        <?= htmlspecialchars($annonce['nombre_chambres']) ?> ch.
                                    </span>
                                </div>
                                
                                <p class="annonce-price"><?= number_format($annonce['prix'], 2, ',', ' ') ?> € / mois</p>
                                
                                <div class="annonce-actions">
                                    <a href="<?= APP_URL ?>/proprietaire/annonces/edit/<?= $annonce['id'] ?>" class="btn-secondary">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Modifier
                                    </a>
                                    <a href="<?= APP_URL ?>/annonces/details/<?= $annonce['id'] ?>" class="btn-view" target="_blank">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        Voir
                                    </a>
                                    <button onclick="confirmDelete(<?= $annonce['id'] ?>)" class="btn-danger">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script>
        function confirmDelete(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')) {
                window.location.href = '<?= APP_URL ?>/proprietaire/annonces/delete/' + id;
            }
        }
    </script>
</body>
</html>
