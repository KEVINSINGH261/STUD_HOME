<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STUD'HOME - Le logement étudiant, enfin facile</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/index.css">
</head>
<body>
    <!-- Header -->
     <?php include VIEWS_PATH . '/partials/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero" style="background-image: url('<?= APP_URL ?>/images/image_accueil.jpg'); background-size: cover; background-position: center; position: relative; margin-bottom: 50px;">
        <div class="hero-content" style="position: relative; z-index: 2;">
            <h1 style="color: white;">Le logement étudiant, enfin facile.</h1>
            <form class="search-bar" action="<?= APP_URL ?>/annonces" method="GET">
                <input type="text" name="search" placeholder="Trouver un logement" required>
                <button type="submit">🔍</button>
            </form>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <!-- Section 1 -->
        <div class="section">
            <div class="section-image image-placeholder-1">
                <span class="placeholder-icon"></span>
            </div>
            <div class="section-content">
                <h2>Bien plus qu'un logement, votre nouvelle vie étudiante.</h2>
                <p>Simplifiez votre quotidien pour vous concentrer sur l'essentiel. Nous vous proposons des logements entièrement équipés, situés au cœur des pôles étudiants, alliant confort moderne et sécurité. De la connexion Wi-Fi haut débit aux services inclus, tout est pensé pour que vous vous sentiez immédiatement chez vous dès votre arrivée. Posez vos valises et commencez votre nouvelle aventure.</p>
            </div>
        </div>

        <!-- Section 2 -->
        <div class="section section-reverse">
            <div class="section-image image-placeholder-2">
                <span class="placeholder-icon"></span>
            </div>
            <div class="section-content">
                <h2>Vous possédez un logement ?</h2>
                <p>Confiez-nous votre logement et bénéficiez d'un accompagnement personnalisé pour une mise en location sécurisée. Nous sélectionnons rigoureusement les candidats et nous assurons du respect des normes en vigueur pour protéger votre investissement. Que vous soyez un propriétaire expérimenté ou que ce soit votre première mise en location, notre équipe est à vos côtés pour simplifier vos démarches.</p>
                <a href="<?= APP_URL ?>/proprietaire/dashboard" class="btn-secondary">Faire louer votre bien →</a>
            </div>
        </div>

        <!-- Features Section -->
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon icon-1"></div>
                <h3>Pourquoi la choisir la colocation ?</h3>
                <p>Partagez bien plus qu’un appartement. La colocation vous permet de réduire vos frais tout en vivant une expérience humaine unique. C'est la solution idéale pour allier convivialité, grands espaces communs et nouvelles rencontres dès votre arrivée.</p>
                <a href="#colocation" class="feature-link">En savoir + →</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon icon-2"></div>
                <h3>Loyer réduit et aides</h3>
                <p>Maîtrisez votre budget sans sacrifier votre confort. Profitez de tarifs compétitifs et découvrez toutes les aides financières (APL, ALS) auxquelles vous avez droit. Nous vous accompagnons pour optimiser votre dossier et rendre votre logement plus abordable.</p>
                <a href="#aides" class="feature-link">En savoir + →</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon icon-3"></div>
                <h3>Statistiques et actualité</h3>
                <p>Restez informé des tendances du marché immobilier étudiant. Accédez à nos analyses exclusives, aux chiffres clés de l'offre et de la demande, ainsi qu'aux dernières actualités juridiques et sociales qui impactent votre vie de locataire ou de propriétaire.</p>
                <a href="#stats" class="feature-link">En savoir + →</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon icon-4"></div>
                <h3>RGPD, nos engagements</h3>
                <p>La protection de vos données est notre priorité. Nous nous engageons à traiter vos informations personnelles avec la plus grande transparence et sécurité, conformément à la réglementation européenne. Vos données vous appartiennent, nous les sécurisons.</p>
                <a href="#rgpd" class="feature-link">En savoir + →</a>
            </div>
        </div>
        </div>
    </div>

    <!-- Footer -->
     <<?php include VIEWS_PATH . '/partials/footer.php'; ?>
     
</body>
</html>
