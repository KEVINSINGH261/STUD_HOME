<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signalements - Administration STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <style>
        .admin-signalements {
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .signalements-header {
            margin-bottom: 30px;
        }
        
        .signalements-header h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .signalements-filters {
            display: flex;
            gap: 10px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .filter-btn:hover {
            border-color: #007bff;
            color: #007bff;
        }
        
        .filter-btn.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-nouveau {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-en_cours {
            background: #cfe2ff;
            color: #084298;
        }
        
        .status-resolu {
            background: #d1e7dd;
            color: #0f5132;
        }
        
        .status-clos {
            background: #f8d7da;
            color: #842029;
        }
        
        .signalements-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        
        .signalements-table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        
        .signalements-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .signalements-table tbody tr {
            border-bottom: 1px solid #dee2e6;
            transition: background 0.2s ease;
        }
        
        .signalements-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .signalements-table td {
            padding: 15px;
            font-size: 14px;
            color: #555;
        }
        
        .signalement-item {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: white;
            margin-bottom: 20px;
        }
        
        .signalement-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            gap: 20px;
        }
        
        .signalement-title {
            flex: 1;
        }
        
        .signalement-title h3 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 16px;
        }
        
        .signalement-info {
            font-size: 13px;
            color: #666;
            margin: 5px 0;
        }
        
        .signalement-motif {
            display: inline-block;
            padding: 6px 12px;
            background: #e9ecef;
            border-radius: 4px;
            font-size: 13px;
            margin: 5px 0;
            font-weight: 600;
            color: #495057;
        }
        
        .signalement-description {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
            font-size: 14px;
            line-height: 1.6;
            color: #555;
            border-left: 4px solid #007bff;
        }
        
        .signalement-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
        }
        
        .status-update-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .status-update-form select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: white;
        }
        
        .status-update-form button {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        
        .status-update-form button:hover {
            background: #0056b3;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        
        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #666;
        }
        
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .stat-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .stat-box.nouveau {
            border-left-color: #ffc107;
        }
        
        .stat-box.en_cours {
            border-left-color: #0dcaf0;
        }
        
        .stat-box.resolu {
            border-left-color: #198754;
        }
        
        .stat-box.clos {
            border-left-color: #dc3545;
        }
        
        .stat-box strong {
            display: block;
            font-size: 24px;
            color: #333;
            margin: 10px 0 5px 0;
        }
        
        .stat-box small {
            display: block;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="admin-signalements">
        <div class="container">
            <div class="signalements-header">
                <h1>Gestion des Signalements</h1>
                
                <?php if (isset($flash) && $flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?>">
                        <?= $flash['message'] ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Statistiques -->
            <div class="stats-row">
                <div class="stat-box nouveau">
                    <small>Nouveaux</small>
                    <strong><?= $counts['nouveau'] ?></strong>
                </div>
                <div class="stat-box en_cours">
                    <small>En cours</small>
                    <strong><?= $counts['en_cours'] ?></strong>
                </div>
                <div class="stat-box resolu">
                    <small>Résolus</small>
                    <strong><?= $counts['resolu'] ?></strong>
                </div>
                <div class="stat-box clos">
                    <small>Clos</small>
                    <strong><?= $counts['clos'] ?></strong>
                </div>
            </div>
            
            <!-- Filtres -->
            <div class="signalements-filters">
                <a href="<?= APP_URL ?>/admin/signalements?statut=nouveau" class="filter-btn <?= ($current_statut === 'nouveau' ? 'active' : '') ?>">
                    Nouveaux
                </a>
                <a href="<?= APP_URL ?>/admin/signalements?statut=en_cours" class="filter-btn <?= ($current_statut === 'en_cours' ? 'active' : '') ?>">
                    En cours
                </a>
                <a href="<?= APP_URL ?>/admin/signalements?statut=resolu" class="filter-btn <?= ($current_statut === 'resolu' ? 'active' : '') ?>">
                    Résolus
                </a>
                <a href="<?= APP_URL ?>/admin/signalements?statut=clos" class="filter-btn <?= ($current_statut === 'clos' ? 'active' : '') ?>">
                    Clos
                </a>
                <a href="<?= APP_URL ?>/admin/signalements?statut=tous" class="filter-btn <?= ($current_statut === 'tous' ? 'active' : '') ?>">
                    Tous
                </a>
            </div>
            
            <!-- Liste des signalements -->
            <?php if (!empty($signalements)): ?>
                <div class="signalements-list">
                    <?php foreach ($signalements as $signalement): ?>
                        <div class="signalement-item">
                            <div class="signalement-header">
                                <div class="signalement-title">
                                    <h3><?= htmlspecialchars($signalement['annonce_titre'] ?? 'Sans titre') ?></h3>
                                    <p class="signalement-info">
                                        Signalé par : <strong><?= htmlspecialchars(($signalement['prenom'] ?? '') . ' ' . ($signalement['nom'] ?? '')) ?></strong> 
                                        <?php if (!empty($signalement['email'])): ?>
                                            (<a href="mailto:<?= htmlspecialchars($signalement['email']) ?>"><?= htmlspecialchars($signalement['email']) ?></a>)
                                        <?php endif; ?>
                                    </p>
                                    <p class="signalement-info">
                                        Le : <strong><?= date('d/m/Y à H:i', strtotime($signalement['date_signalement'])) ?></strong>
                                    </p>
                                    <span class="signalement-motif">
                                        <?php
                                        $motifs = [
                                            'spam' => '📧 Spam',
                                            'arnaque' => '⚠️ Arnaque',
                                            'contenu_inapproprie' => '🚫 Contenu inapproprié',
                                            'annonce_disparue' => '❓ Annonce disparue',
                                            'autre' => '📝 Autre'
                                        ];
                                        echo $motifs[$signalement['motif']] ?? htmlspecialchars($signalement['motif']);
                                        ?>
                                    </span>
                                </div>
                                <span class="status-badge status-<?= str_replace(' ', '_', $signalement['statut']) ?>">
                                    <?= ucfirst(str_replace('_', ' ', $signalement['statut'])) ?>
                                </span>
                            </div>
                            
                            <?php if (!empty($signalement['description'])): ?>
                                <div class="signalement-description">
                                    <strong>Description :</strong><br>
                                    <?= nl2br(htmlspecialchars($signalement['description'])) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="signalement-actions">
                                <form method="POST" action="<?= APP_URL ?>/admin/signalements/update-statut/<?= $signalement['id'] ?>" class="status-update-form">
                                    <select name="statut">
                                        <option value="nouveau" <?= ($signalement['statut'] === 'nouveau' ? 'selected' : '') ?>>Nouveau</option>
                                        <option value="en_cours" <?= ($signalement['statut'] === 'en_cours' ? 'selected' : '') ?>>En cours</option>
                                        <option value="resolu" <?= ($signalement['statut'] === 'resolu' ? 'selected' : '') ?>>Résolu</option>
                                        <option value="clos" <?= ($signalement['statut'] === 'clos' ? 'selected' : '') ?>>Clos</option>
                                    </select>
                                    <button type="submit">Mettre à jour</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <h3>Aucun signalement trouvé</h3>
                    <p>Il n'y a actuellement aucun signalement avec le statut sélectionné.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script src="<?= APP_URL ?>/js/main.js"></script>
</body>
</html>
