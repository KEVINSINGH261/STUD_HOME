<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($annonce['titre']) ?> - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/annonce-detail.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="annonce-details">
        <div class="container">
            <?php if (isset($flash) && $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>
            
            <div class="annonce-header">
                <h1><?= htmlspecialchars($annonce['titre']) ?></h1>
                
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'etudiant'): ?>
                    <?php if ($isFavori): ?>
                        <a href="<?= APP_URL ?>/etudiant/favoris/remove/<?= $annonce['id'] ?>" class="btn-danger">
                            Retirer des favoris
                        </a>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/etudiant/favoris/add/<?= $annonce['id'] ?>" class="btn-primary">
                            Ajouter aux favoris
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="annonce-content">
                <div class="annonce-image">
                    <?php if (!empty($annonce['image_principale'])): ?>
                        <img src="<?= APP_URL ?>/<?= htmlspecialchars($annonce['image_principale']) ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                    <?php else: ?>
                        <div class="no-image-large">Pas de photo disponible</div>
                    <?php if ($annonce['photo']): ?>
                        <img src="<?= APP_URL ?>/<?= htmlspecialchars($annonce['photo']) ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                    <?php else: ?>
                        <div class="no-image-large">📷 Pas de photo disponible</div>
                    <?php endif; ?>
                </div>
                
                <div class="annonce-sidebar">
                    <div class="price-box">
                        <span class="price"><?= number_format($annonce['prix'], 0, ',', ' ') ?> €</span>
                        <span>/mois</span>
                    </div>
                    
                    <div class="info-box">
                        <h3>Caractéristiques</h3>
                        <ul>
                            <li>
                                <strong>Type</strong>
                                <span><?= ucfirst($annonce['type']) ?></span>
                            </li>
                            <li>
                                <strong>Surface</strong>
                                <span><?= $annonce['surface'] ?> m²</span>
                            </li>
                            <li>
                                <strong>Pièces</strong>
                                <span><?= $annonce['nombre_pieces'] ?? 'N/A' ?></span>
                            </li>
                            <li>
                                <strong>Chambres</strong>
                                <span><?= $annonce['nombre_chambres'] ?></span>
                            </li>
                            <li>
                                <strong>Ville</strong>
                                <span><?= htmlspecialchars($annonce['ville']) ?></span>
                            </li>
                            <li>
                                <strong>Code postal</strong>
                                <span><?= htmlspecialchars($annonce['code_postal']) ?></span>
                            </li>
                            <li><strong>Type:</strong> <?= ucfirst($annonce['type']) ?></li>
                            <li><strong>Surface:</strong> <?= $annonce['surface'] ?> m²</li>
                            <li><strong>Chambres:</strong> <?= $annonce['nombre_chambres'] ?></li>
                            <li><strong>Ville:</strong> <?= htmlspecialchars($annonce['ville']) ?></li>
                            <li><strong>Code postal:</strong> <?= htmlspecialchars($annonce['code_postal']) ?></li>
                        </ul>
                    </div>
                    
                    <div class="contact-box">
                        <h3>Contact Propriétaire</h3>
                        <p>
                            <strong>Nom</strong>
                            <?= htmlspecialchars($annonce['prenom'] . ' ' . $annonce['nom']) ?>
                        </p>
                        <p>
                            <strong>Email</strong>
                            <a href="mailto:<?= htmlspecialchars($annonce['email']) ?>"><?= htmlspecialchars($annonce['email']) ?></a>
                        </p>
                        <p>
                            <strong>Téléphone</strong>
                            <a href="tel:<?= htmlspecialchars($annonce['telephone']) ?>"><?= htmlspecialchars($annonce['telephone']) ?></a>
                        </p>
                        <p><strong>Nom:</strong> <?= htmlspecialchars($annonce['prenom'] . ' ' . $annonce['nom']) ?></p>
                        <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($annonce['email']) ?>"><?= htmlspecialchars($annonce['email']) ?></a></p>
                        <p><strong>Téléphone:</strong> <?= htmlspecialchars($annonce['telephone']) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="annonce-description">
                <h2>Description du logement</h2>
                <h2>Description</h2>
                <p><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>
            </div>
            
            <div class="annonce-address">
                <h2>Localisation</h2>
                <p><strong>Adresse :</strong> <?= htmlspecialchars($annonce['adresse']) ?></p>
                <p><strong>Code postal & Ville :</strong> <?= htmlspecialchars($annonce['code_postal']) ?> <?= htmlspecialchars($annonce['ville']) ?></p>
                <p><?= htmlspecialchars($annonce['adresse']) ?></p>
                <p><?= htmlspecialchars($annonce['code_postal']) ?> <?= htmlspecialchars($annonce['ville']) ?></p>
            </div>
            
            <div class="back-link">
                <a href="<?= APP_URL ?>/annonces" class="btn-secondary">← Retour aux annonces</a>
            </div>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script src="<?= APP_URL ?>/js/main.js"></script>
</body>
</html>
