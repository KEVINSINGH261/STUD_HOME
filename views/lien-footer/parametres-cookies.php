<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres des Cookies - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/cookies.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="cookies-page">
        <div class="container">
            <h1>Paramètres des Cookies</h1>
            
            <div class="cookies-intro">
                <p>
                    Nous utilisons des cookies pour améliorer votre expérience sur STUD_HOME. 
                    Les cookies sont de petits fichiers texte stockés sur votre appareil qui nous aident 
                    à fournir et améliorer nos services.
                </p>
                <p>
                    Vous pouvez gérer vos préférences en matière de cookies ci-dessous. 
                    Certains cookies sont essentiels au fonctionnement du site et ne peuvent pas être désactivés.
                </p>
            </div>

            <form id="cookiesForm" method="POST" action="<?= APP_URL ?>/parametres-cookies/save">
                
                <!-- Cookies Essentiels -->
                <div class="cookie-category">
                    <div class="cookie-header">
                        <div>
                            <h3>Cookies Essentiels <span class="required-badge">REQUIS</span></h3>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="essential" checked disabled>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <p class="cookie-description">
                        Ces cookies sont nécessaires au fonctionnement du site web et ne peuvent pas être désactivés. 
                        Ils sont généralement définis en réponse à des actions que vous effectuez, comme la connexion 
                        à votre compte ou la configuration de vos préférences de confidentialité.
                    </p>
                    <div class="cookie-examples">
                        <strong>Exemples d'utilisation :</strong>
                        <ul>
                            <li>Maintien de votre session de connexion</li>
                            <li>Sécurité et prévention des fraudes</li>
                            <li>Mémorisation de vos préférences de cookies</li>
                        </ul>
                    </div>
                </div>

                <!-- Cookies Fonctionnels -->
                <div class="cookie-category">
                    <div class="cookie-header">
                        <div>
                            <h3>Cookies Fonctionnels</h3>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="functional" id="functional" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <p class="cookie-description">
                        Ces cookies permettent au site web de mémoriser vos choix et de fournir des fonctionnalités 
                        améliorées et plus personnalisées. Par exemple, ils peuvent mémoriser votre nom d'utilisateur, 
                        votre langue ou votre région.
                    </p>
                    <div class="cookie-examples">
                        <strong>Exemples d'utilisation :</strong>
                        <ul>
                            <li>Mémorisation de vos favoris</li>
                            <li>Sauvegarde de vos filtres de recherche</li>
                            <li>Personnalisation de l'interface utilisateur</li>
                        </ul>
                    </div>
                </div>

                <!-- Cookies Analytiques -->
                <div class="cookie-category">
                    <div class="cookie-header">
                        <div>
                            <h3>Cookies Analytiques</h3>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="analytics" id="analytics">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <p class="cookie-description">
                        Ces cookies nous permettent de compter les visites et les sources de trafic afin de mesurer 
                        et d'améliorer les performances de notre site. Ils nous aident à savoir quelles pages sont 
                        les plus et les moins populaires et à voir comment les visiteurs se déplacent sur le site.
                    </p>
                    <div class="cookie-examples">
                        <strong>Exemples d'utilisation :</strong>
                        <ul>
                            <li>Nombre de visiteurs sur le site</li>
                            <li>Pages les plus consultées</li>
                            <li>Temps passé sur chaque page</li>
                            <li>Analyse du parcours utilisateur</li>
                        </ul>
                    </div>
                </div>

                <!-- Cookies Marketing -->
                <div class="cookie-category">
                    <div class="cookie-header">
                        <div>
                            <h3>Cookies Marketing</h3>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="marketing" id="marketing">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <p class="cookie-description">
                        Ces cookies peuvent être définis par nos partenaires publicitaires via notre site. 
                        Ils peuvent être utilisés pour créer un profil de vos intérêts et vous montrer des 
                        publicités pertinentes sur d'autres sites.
                    </p>
                    <div class="cookie-examples">
                        <strong>Exemples d'utilisation :</strong>
                        <ul>
                            <li>Publicités personnalisées</li>
                            <li>Mesure de l'efficacité des campagnes</li>
                            <li>Ciblage d'audience</li>
                            <li>Remarketing</li>
                        </ul>
                    </div>
                </div>

                <!-- Actions -->
                <div class="cookies-actions">
                    <button type="submit" class="btn-primary">Enregistrer mes préférences</button>
                    <button type="button" class="btn-secondary" onclick="acceptAll()">Tout accepter</button>
                    <button type="button" class="btn-secondary" onclick="rejectAll()">Tout refuser (sauf essentiels)</button>
                </div>
            </form>

        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script>
        // Accepter tous les cookies
        function acceptAll() {
            document.getElementById('functional').checked = true;
            document.getElementById('analytics').checked = true;
            document.getElementById('marketing').checked = true;
            document.getElementById('cookiesForm').submit();
        }

        // Refuser tous les cookies (sauf essentiels)
        function rejectAll() {
            document.getElementById('functional').checked = false;
            document.getElementById('analytics').checked = false;
            document.getElementById('marketing').checked = false;
            document.getElementById('cookiesForm').submit();
        }

        // Sauvegarder les préférences dans localStorage (temporaire)
        document.getElementById('cookiesForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const preferences = {
                essential: true,
                functional: document.getElementById('functional').checked,
                analytics: document.getElementById('analytics').checked,
                marketing: document.getElementById('marketing').checked
            };

            localStorage.setItem('cookiePreferences', JSON.stringify(preferences));
            alert('Vos préférences ont été enregistrées avec succès !');
        });

        // Charger les préférences au chargement de la page
        window.addEventListener('DOMContentLoaded', function() {
            const savedPreferences = localStorage.getItem('cookiePreferences');
            if (savedPreferences) {
                const preferences = JSON.parse(savedPreferences);
                document.getElementById('functional').checked = preferences.functional;
                document.getElementById('analytics').checked = preferences.analytics;
                document.getElementById('marketing').checked = preferences.marketing;
            }
        });
    </script>
</body>
</html>