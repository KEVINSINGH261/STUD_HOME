<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STUD'HOME - Nous Contacter</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/annonce2.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/contact.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Nous Contacter</h1>
        </div>
    </section>

    <!-- Main Content -->
    <div class="contact-container">
        <div class="contact-header">
            <h1>Contactez-nous</h1>
            <p>Notre équipe est à votre disposition pour répondre à toutes vos questions</p>
        </div>

        <div class="contact-info">
            <!-- Email Card -->
            <div class="info-card">
                <div class="icon">📧</div>
                <h3>Email</h3>
                <p>
                    <a href="mailto:studhomefrance@gmail.com">studhomefrance@gmail.com</a>
                </p>
                <p style="margin-top: 15px; color: #666; font-size: 14px;">
                    Nous répondons généralement sous 24-48h
                </p>
            </div>

            <!-- Address Card -->
            <div class="info-card">
                <div class="icon">📍</div>
                <h3>Notre siège</h3>
                <p>
                    10 rue de Vanves<br>
                    92130 Issy-les-Moulineaux<br>
                    France
                </p>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-section">
            <h2>Notre localisation</h2>
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2626.7288967890835!2d2.2697668!3d48.8252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e67af826d3e18b%3A0x5bbb8d6d9d9f5c8e!2s10%20Rue%20de%20Vanves%2C%2092130%20Issy-les-Moulineaux!5e0!3m2!1sfr!2sfr!4v1642860000000!5m2!1sfr!2sfr" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
</body>
</html>
