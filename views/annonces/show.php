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
                <div class="annonce-left-section">
                    <div class="annonce-image">
                        <?php 
                        // Debug: afficher le nombre d'images
                        error_log("DEBUG: Nombre d'images = " . (isset($images) ? count($images) : 'non défini'));
                        if (isset($images)) {
                            error_log("DEBUG: Images = " . print_r($images, true));
                        }
                        echo "<!-- DEBUG: Nombre d'images = " . (isset($images) ? count($images) : 'non défini') . " -->";
                        ?>
                        <?php if (!empty($images) && count($images) > 0): ?>
                            <div class="image-carousel">
                                <?php foreach ($images as $index => $image): ?>
                                    <div class="carousel-slide <?= $index === 0 ? 'active' : '' ?>">
                                        <img src="<?= APP_URL ?>/<?= htmlspecialchars($image['chemin']) ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                                    </div>
                                <?php endforeach; ?>
                                
                                <?php if (count($images) > 1): ?>
                                    <button class="carousel-btn carousel-prev" onclick="changeSlide(-1)">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="15 18 9 12 15 6"></polyline>
                                        </svg>
                                    </button>
                                    <button class="carousel-btn carousel-next" onclick="changeSlide(1)">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </button>
                                    
                                    <!-- Compteur d'images -->
                                    <div class="image-counter">
                                        <span id="currentImageNumber">1</span> / <?= count($images) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php elseif (!empty($annonce['image_principale'])): ?>
                            <img src="<?= APP_URL ?>/<?= htmlspecialchars($annonce['image_principale']) ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>">
                        <?php else: ?>
                            <div class="no-image-large">Pas de photo disponible</div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Bouton plus d'images -->
                    <?php if (!empty($images) && count($images) > 1): ?>
                    <div style="margin-top: 1rem;">
                        <a href="<?= APP_URL ?>/annonces/images/<?= $annonce['id'] ?>" class="btn-primary" style="display: block; padding: 0.75rem 1.5rem; text-decoration: none; text-align: center;">
                            Plus d'images (<?= count($images) ?>)
                        </a>
                    </div>
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
    <script>
        // Carrousel d'images
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const indicators = document.querySelectorAll('.indicator');
        const totalSlides = slides.length;

        function showSlide(n) {
            if (totalSlides === 0) return;
            
            // Normaliser l'index
            if (n >= totalSlides) currentSlide = 0;
            else if (n < 0) currentSlide = totalSlides - 1;
            else currentSlide = n;
            
            // Masquer toutes les slides
            slides.forEach(slide => slide.classList.remove('active'));
            
            // Afficher la slide courante
            slides[currentSlide].classList.add('active');
            
            // Mettre à jour le compteur
            const counterElement = document.getElementById('currentImageNumber');
            if (counterElement) {
                counterElement.textContent = currentSlide + 1;
            }
        }

        function changeSlide(direction) {
            showSlide(currentSlide + direction);
        }

        function goToSlide(n) {
            showSlide(n);
            // Faire défiler jusqu'au carrousel
            document.querySelector('.image-carousel')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        function showAllPhotos() {
            // Fonction pour afficher toutes les photos
            alert('Affichage de toutes les photos');
        }

        // Navigation au clavier
        document.addEventListener('keydown', function(e) {
            if (totalSlides > 0) {
                if (e.key === 'ArrowLeft') changeSlide(-1);
                if (e.key === 'ArrowRight') changeSlide(1);
            }
            // Fermer le modal avec Escape
            if (e.key === 'Escape') closeImageModal();
        });
        
        // Fonctions pour le modal d'image
        function openImageModal(imageSrc) {
            event.stopPropagation();
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'flex';
            modalImg.src = imageSrc;
            document.body.style.overflow = 'hidden';
        }
        
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    </script>
</body>
</html>
});