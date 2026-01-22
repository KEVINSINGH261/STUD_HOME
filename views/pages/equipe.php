<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STUD'HOME - Notre équipe</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/annonce2.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/equipe.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Notre équipe</h1>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <p class="intro-text">
            Bienvenue chez 1DEV, une startup innovante spécialisée dans le développement d'applications web sur mesure. Notre mission est de transformer vos idées en solutions numériques performantes et intuitives. Avec une équipe passionnée de développeurs et designers, nous créons des plateformes qui répondent aux besoins réels des utilisateurs. Stud'Home est notre première réalisation majeure, une plateforme dédiée à faciliter la recherche de logements étudiants. Notre startup est composée de 6 membres talentueux et passionnés.
        </p>

        <!-- Team Grid -->
        <div class="team-grid">
            <div class="team-card">
                <div class="team-image klog">
                    <div class="team-name">Klog</div>
                </div>
            </div>

            <div class="team-card">
                <div class="team-image kevin">
                    <div class="team-name">Kevin</div>
                </div>
            </div>

            <div class="team-card">
                <div class="team-image rayan">
                    <div class="team-name">Rayan</div>
                </div>
            </div>

            <div class="team-card">
                <div class="team-image nora">
                    <div class="team-name">Nora</div>
                </div>
            </div>

            <div class="team-card">
                <div class="team-image gabin">
                    <div class="team-name">Gabin</div>
                </div>
            </div>

            <div class="team-card">
                <div class="team-image alkaly">
                    <div class="team-name">Alkaly</div>
                </div>
            </div>
        </div>

        <!-- 1DEV Section -->
        <div class="isep-section">
            <div class="isep-image" style="background-image: url('<?= APP_URL ?>/images/1DEV.png');"></div>
            <div class="isep-content">
                <h2>1DEV</h2>
                <p>
                    1DEV est une startup ambitieuse fondée par une équipe de jeunes développeurs passionnés par la technologie et l'innovation. Notre vision est de révolutionner le développement web en créant des solutions sur mesure qui allient performance, design moderne et expérience utilisateur exceptionnelle. Nous accompagnons nos clients de la conception à la mise en production, en garantissant des applications web fiables, scalables et parfaitement adaptées à leurs besoins.
                </p>
            </div>
        </div>
    </div>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>

</body>
</html>
