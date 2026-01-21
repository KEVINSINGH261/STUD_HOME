<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="dashboard admin-dashboard">
        <div class="container">
            <h1>Administration STUD_HOME</h1>
            
            <?php if (isset($flash) && $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div>
                        <h3><?= $stats['total_etudiants'] ?></h3>
                        <p>Étudiants</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div>
                        <h3><?= $stats['total_proprietaires'] ?></h3>
                        <p>Propriétaires</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div>
                        <h3><?= $stats['total_annonces'] ?></h3>
                        <p>Annonces actives</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div>
                        <h3><?= $stats['annonces_inactives'] ?></h3>
                        <p>Annonces inactives</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div>
                        <h3><?= $stats['signalements_nouveaux'] ?></h3>
                        <p>Signalements nouveaux</p>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-actions">
                <a href="<?= APP_URL ?>/admin/utilisateurs" class="btn-primary">Gérer les utilisateurs</a>
                <a href="<?= APP_URL ?>/admin/annonces" class="btn-primary">Gérer les annonces</a>
                <a href="<?= APP_URL ?>/admin/signalements" class="btn-primary">Gérer les signalements</a>
                <a href="<?= APP_URL ?>/admin/stats" class="btn-secondary">Statistiques</a>
            </div>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script src="<?= APP_URL ?>/js/main.js"></script>
</body>
</html>
