<?php
// Redirection vers la page dashboard après connexion
$nom = isset($proprietaire['nom']) ? $proprietaire['nom'] : 'Utilisateur';
$prenom = isset($proprietaire['prenom']) ? $proprietaire['prenom'] : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stud'Home - Dashboard</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/header.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/dashboard.css">
</head>
<body>
   <?php include VIEWS_PATH . '/partials/header.php'; ?>

    <section class="welcome-banner">
        <div class="container">
            <h1>Bonjour <?= htmlspecialchars($prenom . ' ' . $nom) ?></h1>
        </div>
    </section>

    <main class="main">
        <div class="container">
            <div class="dashboard-grid">
                <a href="<?= APP_URL ?>/proprietaire/annonces" class="dashboard-card card-highlight">
                    <div class="card-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                    <h2 class="card-title">Mes Annonces</h2>
                    <p class="card-description">Gérer vos logements</p>
                    <?php if (isset($annoncesCount) && $annoncesCount > 0): ?>
                        <span class="card-badge"><?= $annoncesCount ?></span>
                    <?php endif; ?>
                </a>

                <a href="<?= APP_URL ?>/proprietaire/profile" class="dashboard-card">
                    <div class="card-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <h2 class="card-title">Mon Compte</h2>
                    <p class="card-description">Gérer votre profil</p>
                </a>

                <a href="<?= APP_URL ?>/proprietaire/demandes-interet" class="dashboard-card card-highlight">
                    <div class="card-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <h2 class="card-title">Demandes d'intérêt</h2>
                    <p class="card-description">Voir les demandes des étudiants</p>
                    <?php if (isset($demandesCount) && $demandesCount > 0): ?>
                        <span class="card-badge"><?= $demandesCount ?></span>
                    <?php endif; ?>
                </a>

                <a href="#" class="dashboard-card">
                    <div class="card-icon">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                        </svg>
                    </div>
                    <h2 class="card-title">Paramètres</h2>
                    <p class="card-description">Configuration du compte</p>
                </a>
            </div>

            <a href="<?= APP_URL ?>/logout" class="logout-link">
                Déconnexion
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </a>
        </div>
    </main>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
</body>
</html>
