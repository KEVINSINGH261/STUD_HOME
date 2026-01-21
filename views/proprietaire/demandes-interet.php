<?php if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'proprietaire'): 
    header('Location: ' . APP_URL . '/');
    exit;
endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes d'intérêt - STUD'HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/dashboard.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/proprietaire-demandes-interet.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>

    <div class="demandes-container">
        <?php if (isset($flash) && $flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div class="demandes-header">
            <h1>Demandes d'intérêt</h1>
        </div>

        <!-- Statistiques -->
        <div class="demandes-stats">
            <a href="?statut=nouveau" class="stat-card <?= $statut === 'nouveau' ? 'active' : '' ?>">
                <div class="stat-card-value"><?= $counts['nouveau'] ?></div>
                <div class="stat-card-label">Nouvelles</div>
            </a>
            <a href="?statut=vue" class="stat-card <?= $statut === 'vue' ? 'active' : '' ?>">
                <div class="stat-card-value"><?= $counts['vue'] ?></div>
                <div class="stat-card-label">Vues</div>
            </a>
            <a href="?statut=accepte" class="stat-card <?= $statut === 'accepte' ? 'active' : '' ?>">
                <div class="stat-card-value"><?= $counts['accepte'] ?></div>
                <div class="stat-card-label">Acceptées</div>
            </a>
            <a href="?statut=refuse" class="stat-card <?= $statut === 'refuse' ? 'active' : '' ?>">
                <div class="stat-card-value"><?= $counts['refuse'] ?></div>
                <div class="stat-card-label">Refusées</div>
            </a>
            <a href="?statut=tous" class="stat-card <?= $statut === 'tous' ? 'active' : '' ?>">
                <div class="stat-card-value"><?= array_sum($counts) ?></div>
                <div class="stat-card-label">Toutes</div>
            </a>
        </div>

        <!-- Liste des demandes -->
        <?php if (!empty($demandes)): ?>
            <div class="demandes-list">
                <?php foreach ($demandes as $demande): ?>
                    <div class="demande-card <?= htmlspecialchars($demande['statut']) ?>">
                        <div class="demande-header">
                            <div class="demande-info">
                                <div class="demande-annonce">
                                    📍 <strong><?= htmlspecialchars($demande['annonce_titre']) ?></strong>
                                </div>
                                <div class="demande-etudiant">
                                    <?= htmlspecialchars($demande['prenom'] . ' ' . $demande['nom']) ?>
                                </div>
                                <div class="demande-contact">
                                    <div>
                                        ✉️ <a href="mailto:<?= htmlspecialchars($demande['email']) ?>">
                                            <?= htmlspecialchars($demande['email']) ?>
                                        </a>
                                    </div>
                                    <div>
                                        📞 <a href="tel:<?= htmlspecialchars($demande['telephone']) ?>">
                                            <?= htmlspecialchars($demande['telephone']) ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <span class="demande-statut <?= htmlspecialchars($demande['statut']) ?>">
                                <?= ucfirst(htmlspecialchars($demande['statut'])) ?>
                            </span>
                        </div>

                        <?php if (!empty($demande['message'])): ?>
                            <div class="demande-message">
                                <p><?= nl2br(htmlspecialchars($demande['message'])) ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="demande-actions">
                            <form method="POST" action="<?= APP_URL ?>/demande-interet/update-statut" style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="hidden" name="demande_id" value="<?= $demande['id'] ?>">
                                <select name="statut" required>
                                    <option value="nouveau" <?= $demande['statut'] === 'nouveau' ? 'selected' : '' ?>>Nouveau</option>
                                    <option value="vue" <?= $demande['statut'] === 'vue' ? 'selected' : '' ?>>Vue</option>
                                    <option value="accepte" <?= $demande['statut'] === 'accepte' ? 'selected' : '' ?>>Acceptée</option>
                                    <option value="refuse" <?= $demande['statut'] === 'refuse' ? 'selected' : '' ?>>Refusée</option>
                                </select>
                                <button type="submit">Mettre à jour</button>
                            </form>
                        </div>

                        <div class="demande-date">
                            📅 Reçu le <?= date('d/m/Y à H:i', strtotime($demande['date_demande'])) ?>
                            <?php if (!empty($demande['date_reponse'])): ?>
                                — Répondu le <?= date('d/m/Y à H:i', strtotime($demande['date_reponse'])) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h2>Aucune demande d'intérêt</h2>
                <p>
                    <?php if ($statut === 'nouveau'): ?>
                        Vous n'avez pas encore reçu de demande d'intérêt.
                    <?php elseif ($statut === 'nouveau'): ?>
                        Vous n'avez pas de demande en attente.
                    <?php else: ?>
                        Aucune demande avec ce statut.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
</body>
</html>
