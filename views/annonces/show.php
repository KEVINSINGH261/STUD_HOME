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
                
                <?php if ($this->isAuthenticated()): ?>
                    <a href="<?= APP_URL ?>/annonces/report/<?= $annonce['id'] ?>" class="btn-warning" title="Signaler cette annonce">
                        🚩 Signaler
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="annonce-content">
                <div class="annonce-image">
                    <?php if (!empty($annonce['image_principale'])): ?>
                        <img src="<?= APP_URL ?>/<?= htmlspecialchars($annonce['image_principale']) ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                    <?php else: ?>
                        <div class="no-image-large">Pas de photo disponible</div>
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
                    </div>
                </div>
            </div>
            
            <div class="annonce-description">
                <h2>Description du logement</h2>
                <p><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>
            </div>
            
            <div class="annonce-address">
                <h2>Localisation</h2>
                <p><strong>Adresse :</strong> <?= htmlspecialchars($annonce['adresse']) ?></p>
                <p><strong>Code postal & Ville :</strong> <?= htmlspecialchars($annonce['code_postal']) ?> <?= htmlspecialchars($annonce['ville']) ?></p>
            </div>

            <!-- Formulaire d'intérêt pour les étudiants -->
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'etudiant'): ?>
            <div class="demande-interet-section">
                <h2>Intéressé(e) par ce logement ?</h2>
                <p>Remplissez ce formulaire pour contacter le propriétaire</p>
                
                <form method="POST" action="<?= APP_URL ?>/demande-interet/create" class="demande-form">
                    <input type="hidden" name="annonce_id" value="<?= $annonce['id'] ?>">
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required placeholder="Votre email">
                    </div>
                    
                    <div class="form-group">
                        <label for="telephone">Téléphone *</label>
                        <input type="tel" id="telephone" name="telephone" required placeholder="Votre numéro de téléphone">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="4" placeholder="Présentez-vous brièvement et posez vos questions..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">Envoyer ma demande d'intérêt</button>
                </form>
            </div>
            <?php elseif (!isset($_SESSION['user_id'])): ?>
            <div class="demande-interet-section">
                <h2>Intéressé(e) par ce logement ?</h2>
                <p><a href="<?= APP_URL ?>/login">Connectez-vous</a> pour contacter le propriétaire</p>
            </div>
            <?php endif; ?>
            
            <div class="back-link">
                <a href="<?= APP_URL ?>/annonces" class="btn-secondary">← Retour aux annonces</a>
            </div>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script src="<?= APP_URL ?>/js/main.js"></script>
</body>
</html>
