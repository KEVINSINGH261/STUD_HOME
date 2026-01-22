<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F.A.Q - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/faq.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main>
        <div class="faq-container">
            <div class="faq-content">
                <div class="faq-header">
                    <h1>Foire Aux Questions (F.A.Q)</h1>
                    <p>Trouvez des réponses aux questions les plus fréquentes</p>
                </div>
                
                <div class="faq-section">
                    <div class="faq-item">
                        <h3 class="faq-question">Comment créer un compte sur STUD_HOME ?</h3>
                        <div class="faq-answer">
                            <p>Pour créer un compte, cliquez sur "S'inscrire" dans le menu de navigation. Vous devrez choisir votre type de compte (Étudiant ou Propriétaire), puis remplir le formulaire avec vos informations personnelles.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">Comment publier une annonce de logement ?</h3>
                        <div class="faq-answer">
                            <p>Seuls les propriétaires peuvent publier des annonces. Une fois connecté avec un compte propriétaire, accédez à votre espace personnel et cliquez sur "Créer une annonce". Remplissez tous les champs requis et ajoutez des photos de votre bien.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">Comment rechercher un logement ?</h3>
                        <div class="faq-answer">
                            <p>Utilisez la page "Rechercher" pour filtrer les annonces selon vos critères : ville, prix, type de logement, nombre de chambres, etc. Les résultats s'afficheront en temps réel.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">Comment ajouter une annonce à mes favoris ?</h3>
                        <div class="faq-answer">
                            <p>Les étudiants connectés peuvent ajouter des annonces à leurs favoris en cliquant sur le bouton "Ajouter aux favoris" sur la page de détail d'une annonce. Retrouvez tous vos favoris dans votre espace personnel.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">Comment contacter un propriétaire ?</h3>
                        <div class="faq-answer">
                            <p>Sur la page de détail d'une annonce, vous trouverez un formulaire de contact permettant d'envoyer une demande d'intérêt au propriétaire. Remplissez vos informations et votre message, le propriétaire recevra votre demande.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">Comment modifier ou supprimer mon annonce ?</h3>
                        <div class="faq-answer">
                            <p>Accédez à votre espace propriétaire, puis à "Mes annonces". Vous y trouverez toutes vos annonces avec des options pour les modifier, les désactiver ou les supprimer.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">Comment signaler une annonce inappropriée ?</h3>
                        <div class="faq-answer">
                            <p>Sur chaque page d'annonce, vous trouverez un bouton "Signaler". Utilisez-le pour nous alerter d'un contenu inapproprié. Notre équipe examinera le signalement dans les plus brefs délais.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">Comment changer mon mot de passe ?</h3>
                        <div class="faq-answer">
                            <p>Connectez-vous à votre compte, accédez à "Paramètres" dans votre espace personnel. Vous y trouverez un formulaire pour changer votre mot de passe.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">Les services de STUD_HOME sont-ils gratuits ?</h3>
                        <div class="faq-answer">
                            <p>Oui, STUD_HOME est entièrement gratuit pour les étudiants et les propriétaires. Aucun frais n'est facturé pour l'inscription, la publication d'annonces ou la mise en relation.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <h3 class="faq-question">Comment protégez-vous mes données personnelles ?</h3>
                        <div class="faq-answer">
                            <p>La protection de vos données est notre priorité. Consultez notre page <a href="<?= APP_URL ?>/protection-donnees">Protection des Données</a> pour en savoir plus sur nos pratiques en matière de confidentialité.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-contact-box">
                    <h3>Vous n'avez pas trouvé de réponse ?</h3>
                    <p>Notre équipe est là pour vous aider</p>
                    <a href="<?= APP_URL ?>/contact">Nous Contacter</a>
                </div>
            </div>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script>
        // Toggle FAQ items
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const item = question.closest('.faq-item');
                const isActive = item.classList.contains('active');
                
                // Close all items
                document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
                
                // Open clicked item if it wasn't active
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
