<head>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/header.css">
</head>
<header class="header">
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="<?= APP_URL ?>">
                    <h1>STUD'HOME</h1>
                </a>
            </div>
            
            <ul class="navbar-menu">
                <li><a href="<?= APP_URL ?>">Accueil</a></li>
                <li><a href="<?= APP_URL ?>/annonces">Annonces</a></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'etudiant'): ?>
                        <li><a href="<?= APP_URL ?>/etudiant/dashboard">Mon Espace</a></li>
                        <li><a href="<?= APP_URL ?>/etudiant/favoris">Mes Favoris</a></li>
                    <?php elseif ($_SESSION['user_role'] === 'proprietaire'): ?>
                        <li><a href="<?= APP_URL ?>/proprietaire/dashboard">Mon Espace</a></li>
                        <li><a href="<?= APP_URL ?>/proprietaire/annonces">Mes Annonces</a></li>
                    <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="<?= APP_URL ?>/admin/dashboard">Administration</a></li>
                    <?php endif; ?>
                    
                    <li class="user-info">
                        <span><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    </li>
                    <li><a href="<?= APP_URL ?>/logout" class="btn-logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?= APP_URL ?>/login" class="btn-primary">Connexion</a></li>
                    <li><a href="<?= APP_URL ?>/register" class="btn-secondary">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>