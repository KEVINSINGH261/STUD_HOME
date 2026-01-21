<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protection des Données - STUD_HOME</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .protection-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
            background-color: #ffffff;
            box-shadow: 0 0 30px rgba(0,0,0,0.05);
        }

        /* Header Section */
        .page-header {
            text-align: center;
            margin-bottom: 50px;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }

        .page-header h1 {
            color: #2c3e50;
            font-size: 3rem;
            margin-bottom: 15px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .page-header .subtitle {
            color: #7f8c8d;
            font-size: 1.2rem;
            font-weight: 400;
        }

        .page-header .icon-shield {
            font-size: 4rem;
            color: #FF6B6B;
            margin-bottom: 20px;
        }

        /* Table of Contents */
        .table-of-contents {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 50px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .table-of-contents h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-of-contents ul {
            list-style: none;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 12px;
        }

        .table-of-contents li {
            padding: 0;
        }

        .table-of-contents a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.1);
        }

        .table-of-contents a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }

        /* Introduction Box */
        .protection-intro {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 35px;
            border-radius: 12px;
            margin-bottom: 50px;
            border-left: 5px solid #FF6B6B;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .protection-intro p {
            color: #2c3e50;
            line-height: 1.8;
            margin-bottom: 15px;
            font-size: 1.05rem;
        }

        .protection-intro strong {
            color: #FF6B6B;
        }

        /* Section Styles */
        .content-section {
            margin-bottom: 50px;
            scroll-margin-top: 20px;
        }

        .content-section h2 {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid #FF6B6B;
            display: flex;
            align-items: center;
            gap: 15px;
            font-weight: 600;
        }

        .content-section h2 i {
            color: #FF6B6B;
            font-size: 1.8rem;
        }

        .content-section h3 {
            color: #34495e;
            font-size: 1.4rem;
            margin-top: 30px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .content-section h3 i {
            color: #FF6B6B;
            font-size: 1.2rem;
        }

        .content-section p {
            color: #555;
            line-height: 1.9;
            margin-bottom: 18px;
            font-size: 1.05rem;
        }

        /* Lists */
        .content-section ul, .content-section ol {
            margin-left: 0;
            margin-bottom: 25px;
            padding-left: 0;
        }

        .content-section li {
            color: #555;
            line-height: 1.8;
            margin-bottom: 15px;
            padding-left: 35px;
            position: relative;
            font-size: 1.05rem;
        }

        .content-section ul li::before {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            color: #FF6B6B;
            font-size: 1rem;
        }

        .content-section ol {
            counter-reset: item;
        }

        .content-section ol li {
            counter-increment: item;
        }

        .content-section ol li::before {
            content: counter(item);
            position: absolute;
            left: 0;
            background: #FF6B6B;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: bold;
        }

        /* Info Cards */
        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }

        .info-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border: 2px solid #e8eef5;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #FF6B6B;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .info-card:hover::before {
            transform: scaleY(1);
        }

        .info-card h4 {
            color: #2c3e50;
            font-size: 1.2rem;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card h4 i {
            color: #FF6B6B;
            font-size: 1.5rem;
        }

        .info-card p {
            color: #666;
            line-height: 1.7;
            margin-bottom: 0;
            font-size: 0.95rem;
        }

        /* Highlight Boxes */
        .highlight-box {
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            border-left: 5px solid #d63031;
            padding: 25px;
            margin: 30px 0;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(253, 203, 110, 0.3);
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .highlight-box i {
            font-size: 2rem;
            color: #d63031;
            margin-top: 3px;
        }

        .highlight-box-content {
            flex: 1;
        }

        .highlight-box strong {
            display: block;
            color: #2d3436;
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .highlight-box p {
            color: #2d3436;
            margin-bottom: 0;
        }

        .success-box {
            background: linear-gradient(135deg, #a8e6cf 0%, #6fcf97 100%);
            border-left: 5px solid #00b894;
        }

        .success-box i {
            color: #00b894;
        }

        .info-box {
            background: linear-gradient(135deg, #a8daff 0%, #74b9ff 100%);
            border-left: 5px solid #0984e3;
        }

        .info-box i {
            color: #0984e3;
        }

        /* Contact Box */
        .contact-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            margin: 50px 0;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        .contact-box h3 {
            color: white;
            margin: 0 0 20px 0;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .contact-box h3 i {
            font-size: 2rem;
        }

        .contact-box p {
            color: rgba(255,255,255,0.95);
            margin-bottom: 15px;
            line-height: 1.8;
        }

        .contact-box ul {
            list-style: none;
            margin: 20px 0;
            padding: 0;
        }

        .contact-box li {
            color: white;
            padding: 12px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.05rem;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .contact-box li:last-child {
            border-bottom: none;
        }

        .contact-box li i {
            color: #ffeaa7;
            font-size: 1.2rem;
        }

        .contact-box strong {
            color: #ffeaa7;
        }

        /* Rights Grid */
        .rights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .right-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #FF6B6B;
            transition: all 0.3s ease;
        }

        .right-item:hover {
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transform: translateX(5px);
        }

        .right-item h4 {
            color: #2c3e50;
            font-size: 1.15rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .right-item h4 i {
            color: #FF6B6B;
        }

        .right-item p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding: 30px 0;
            margin: 30px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #FF6B6B, #667eea);
        }

        .timeline-item {
            position: relative;
            padding-left: 70px;
            margin-bottom: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #FF6B6B;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #FF6B6B;
        }

        .timeline-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        .timeline-content strong {
            color: #FF6B6B;
            display: block;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        /* Quick Summary */
        .quick-summary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin: 50px 0;
            box-shadow: 0 15px 40px rgba(240, 147, 251, 0.4);
        }

        .quick-summary h3 {
            color: white;
            margin: 0 0 25px 0;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .summary-item {
            background: rgba(255,255,255,0.15);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .summary-item i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            display: block;
        }

        .summary-item strong {
            display: block;
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .summary-item p {
            color: rgba(255,255,255,0.95);
            font-size: 0.9rem;
            margin: 0;
        }

        /* Date Update */
        .date-update {
            background: #f8f9fa;
            padding: 25px;
            margin-top: 60px;
            border-radius: 10px;
            border-top: 3px solid #FF6B6B;
            text-align: center;
        }

        .date-update p {
            color: #7f8c8d;
            margin: 8px 0;
            font-size: 0.95rem;
        }

        .date-update i {
            color: #FF6B6B;
            margin-right: 8px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .protection-page {
                padding: 30px 15px;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .content-section h2 {
                font-size: 1.5rem;
            }

            .table-of-contents ul {
                grid-template-columns: 1fr;
            }

            .info-cards {
                grid-template-columns: 1fr;
            }

            .rights-grid {
                grid-template-columns: 1fr;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .timeline::before {
                left: 15px;
            }

            .timeline-item {
                padding-left: 50px;
            }

            .timeline-item::before {
                left: 5px;
            }
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Links */
        .content-section a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .content-section a:hover {
            color: #FF6B6B;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="protection-page">
        <!-- Page Header -->
        <div class="page-header">
            <i class="fas fa-shield-alt icon-shield"></i>
            <h1>Protection des Données Personnelles</h1>
            <p class="subtitle">Votre confidentialité est notre priorité</p>
        </div>

        <!-- Quick Summary -->
        <div class="quick-summary">
            <h3><i class="fas fa-bolt"></i> En Résumé</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <i class="fas fa-lock"></i>
                    <strong>Sécurité</strong>
                    <p>Vos données sont cryptées et protégées</p>
                </div>
                <div class="summary-item">
                    <i class="fas fa-user-shield"></i>
                    <strong>Vos Droits</strong>
                    <p>Accès, modification, suppression garantis</p>
                </div>
                <div class="summary-item">
                    <i class="fas fa-ban"></i>
                    <strong>Pas de Vente</strong>
                    <p>Nous ne vendons jamais vos données</p>
                </div>
                <div class="summary-item">
                    <i class="fas fa-balance-scale"></i>
                    <strong>Conformité RGPD</strong>
                    <p>100% conforme à la réglementation</p>
                </div>
            </div>
        </div>

        <!-- Table of Contents -->
        <div class="table-of-contents">
            <h3><i class="fas fa-list"></i> Table des Matières</h3>
            <ul>
                <li><a href="#responsable"><i class="fas fa-chevron-right"></i> Responsable du traitement</a></li>
                <li><a href="#donnees"><i class="fas fa-chevron-right"></i> Données collectées</a></li>
                <li><a href="#finalites"><i class="fas fa-chevron-right"></i> Finalités du traitement</a></li>
                <li><a href="#base-legale"><i class="fas fa-chevron-right"></i> Base légale</a></li>
                <li><a href="#conservation"><i class="fas fa-chevron-right"></i> Durée de conservation</a></li>
                <li><a href="#destinataires"><i class="fas fa-chevron-right"></i> Destinataires</a></li>
                <li><a href="#securite"><i class="fas fa-chevron-right"></i> Sécurité des données</a></li>
                <li><a href="#droits"><i class="fas fa-chevron-right"></i> Vos droits</a></li>
                <li><a href="#exercer"><i class="fas fa-chevron-right"></i> Exercer vos droits</a></li>
                <li><a href="#reclamation"><i class="fas fa-chevron-right"></i> Réclamation</a></li>
                <li><a href="#transfert"><i class="fas fa-chevron-right"></i> Transfert de données</a></li>
                <li><a href="#cookies"><i class="fas fa-chevron-right"></i> Cookies</a></li>
            </ul>
        </div>
        
        <!-- Introduction -->
        <div class="protection-intro">
            <p>
                <strong>STUD_HOME</strong> s'engage à protéger vos données personnelles et à respecter 
                votre vie privée. Cette politique de protection des données explique comment nous 
                collectons, utilisons, stockons et protégeons vos informations personnelles.
            </p>
            <p>
                Cette politique est conforme au <strong>Règlement Général sur la Protection des Données (RGPD)</strong> 
                et à la <strong>loi Informatique et Libertés</strong>. Elle s'applique à tous les utilisateurs 
                de notre plateforme : étudiants, propriétaires et administrateurs.
            </p>
        </div>

        <!-- Section 1 -->
        <div class="content-section" id="responsable">
            <h2><i class="fas fa-building"></i> 1. Responsable du traitement</h2>
            <p>
                Le responsable du traitement des données personnelles est <strong>STUD_HOME</strong>, 
                plateforme de mise en relation entre étudiants et propriétaires pour la location de logements étudiants.
            </p>
            
            <div class="info-cards">
                <div class="info-card">
                    <h4><i class="fas fa-globe"></i> Plateforme</h4>
                    <p>STUD_HOME - Service de logement étudiant en ligne facilitant la recherche et la mise en location de logements adaptés aux étudiants.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-envelope"></i> Contact DPO</h4>
                    <p>Déléguée à la Protection des Données : dpo@studhome.fr pour toute question relative à vos données personnelles.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-file-contract"></i> Engagement</h4>
                    <p>Nous nous engageons à traiter vos données de manière loyale, transparente et dans le respect de vos droits.</p>
                </div>
            </div>
        </div>

        <!-- Section 2 -->
        <div class="content-section" id="donnees">
            <h2><i class="fas fa-database"></i> 2. Données collectées</h2>
            <p>Nous collectons uniquement les données nécessaires au bon fonctionnement de notre service :</p>

            <h3><i class="fas fa-user-graduate"></i> 2.1. Pour les étudiants</h3>
            <ul>
                <li><strong>Données d'identification :</strong> Nom, prénom, adresse email</li>
                <li><strong>Données de connexion :</strong> Mot de passe (crypté avec algorithme bcrypt)</li>
                <li><strong>Données académiques :</strong> Établissement scolaire</li>
                <li><strong>Données comportementales :</strong> Favoris, recherches sauvegardées, historique de navigation</li>
                <li><strong>Données de communication :</strong> Demandes d'intérêt envoyées aux propriétaires</li>
                <li><strong>Données d'interaction :</strong> Signalements, notes et avis sur les logements</li>
            </ul>

            <h3><i class="fas fa-home"></i> 2.2. Pour les propriétaires</h3>
            <ul>
                <li><strong>Données d'identification :</strong> Nom, prénom, adresse email</li>
                <li><strong>Données de contact :</strong> Numéro de téléphone</li>
                <li><strong>Données de connexion :</strong> Mot de passe (crypté)</li>
                <li><strong>Données immobilières :</strong> Informations sur les logements (adresse complète, caractéristiques, photos)</li>
                <li><strong>Données financières :</strong> Prix des loyers, charges</li>
                <li><strong>Historique professionnel :</strong> Annonces publiées, demandes reçues, statistiques</li>
            </ul>

            <h3><i class="fas fa-laptop"></i> 2.3. Données techniques et de navigation</h3>
            <ul>
                <li><strong>Données de connexion :</strong> Adresse IP, date et heure de connexion</li>
                <li><strong>Données de navigation :</strong> Pages visitées, temps passé, parcours utilisateur</li>
                <li><strong>Données techniques :</strong> Type de navigateur, système d'exploitation, résolution d'écran</li>
                <li><strong>Cookies :</strong> Voir notre <a href="<?= APP_URL ?>/parametres-cookies">politique de cookies</a></li>
                <li><strong>Données de localisation :</strong> Ville, code postal (pour les recherches géographiques)</li>
            </ul>

            <div class="info-box highlight-box">
                <i class="fas fa-info-circle"></i>
                <div class="highlight-box-content">
                    <strong>Information importante</strong>
                    <p>Nous ne collectons jamais de données sensibles telles que l'origine raciale ou ethnique, les opinions politiques, les convictions religieuses ou philosophiques, l'appartenance syndicale, les données génétiques ou biométriques, les données de santé ou la vie sexuelle.</p>
                </div>
            </div>
        </div>

        <!-- Section 3 -->
        <div class="content-section" id="finalites">
            <h2><i class="fas fa-bullseye"></i> 3. Finalités du traitement</h2>
            <p>Vos données personnelles sont collectées et traitées pour les finalités suivantes :</p>
            
            <div class="info-cards">
                <div class="info-card">
                    <h4><i class="fas fa-user-check"></i> Gestion des comptes</h4>
                    <p>Création, authentification, gestion et maintenance de votre compte utilisateur sur la plateforme.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-handshake"></i> Mise en relation</h4>
                    <p>Faciliter la communication et les échanges entre étudiants cherchant un logement et propriétaires.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-bullhorn"></i> Publication d'annonces</h4>
                    <p>Permettre aux propriétaires de publier, modifier et gérer leurs offres de logement.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-search"></i> Recherche de logements</h4>
                    <p>Permettre aux étudiants de rechercher, filtrer, sauvegarder et suivre les annonces qui les intéressent.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-bell"></i> Notifications</h4>
                    <p>Envoi d'emails de notification concernant l'activité sur votre compte (nouvelles annonces, messages, etc.).</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-shield-alt"></i> Sécurité</h4>
                    <p>Prévention de la fraude, protection contre les abus et maintien de la sécurité de la plateforme.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-chart-line"></i> Amélioration du service</h4>
                    <p>Analyses statistiques anonymes pour comprendre l'utilisation et améliorer l'expérience utilisateur.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-gavel"></i> Obligations légales</h4>
                    <p>Respect des obligations légales et réglementaires (conservation des données, réponses aux autorités).</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-life-ring"></i> Support client</h4>
                    <p>Traitement de vos demandes d'assistance et résolution des problèmes techniques ou relationnels.</p>
                </div>
            </div>
        </div>

        <!-- Section 4 -->
        <div class="content-section" id="base-legale">
            <h2><i class="fas fa-balance-scale"></i> 4. Base légale du traitement</h2>
            <p>Conformément au RGPD, le traitement de vos données repose sur les bases légales suivantes :</p>
            
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-content">
                        <strong>Exécution du contrat</strong>
                        <p>Le traitement est nécessaire pour l'exécution du contrat vous liant à STUD_HOME et pour fournir les services demandés (création de compte, recherche de logements, publication d'annonces).</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <strong>Consentement</strong>
                        <p>Vous avez donné votre consentement explicite pour le traitement de certaines données (cookies non essentiels, communications marketing, newsletter).</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <strong>Obligation légale</strong>
                        <p>Le traitement est nécessaire pour respecter nos obligations légales (conservation des données, lutte contre la fraude, réponse aux autorités compétentes).</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <strong>Intérêt légitime</strong>
                        <p>Le traitement est nécessaire pour nos intérêts légitimes (sécurité de la plateforme, amélioration des services, analyses statistiques) tout en respectant vos droits et libertés.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5 -->
        <div class="content-section" id="conservation">
            <h2><i class="fas fa-clock"></i> 5. Durée de conservation</h2>
            <p>Vos données personnelles sont conservées uniquement pendant la durée nécessaire aux finalités pour lesquelles elles ont été collectées :</p>
            
            <div class="info-cards">
                <div class="info-card">
                    <h4><i class="fas fa-user-check"></i> Comptes actifs</h4>
                    <p>Pendant toute la durée d'utilisation de votre compte et des services.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-user-clock"></i> Comptes inactifs</h4>
                    <p>3 ans après la dernière connexion, puis suppression automatique après notification.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-home"></i> Annonces</h4>
                    <p>1 an après la fin de publication ou la suppression de l'annonce.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-server"></i> Logs de connexion</h4>
                    <p>1 an pour des raisons de sécurité et de prévention de la fraude.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-calculator"></i> Données comptables</h4>
                    <p>10 ans conformément aux obligations légales et fiscales.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-comments"></i> Demandes de contact</h4>
                    <p>6 mois après le traitement complet de la demande.</p>
                </div>
            </div>

            <div class="highlight-box">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="highlight-box-content">
                    <strong>⚠️ Droit à la suppression</strong>
                    <p>Vous pouvez à tout moment demander la suppression anticipée de votre compte et de toutes vos données personnelles en nous contactant. Nous procéderons à la suppression dans un délai maximum de 30 jours, sauf obligation légale de conservation.</p>
                </div>
            </div>
        </div>

        <!-- Section 6 -->
        <!-- Section 6 -->
        <div class="content-section" id="destinataires">
            <h2><i class="fas fa-share-alt"></i> 6. Destinataires des données</h2>
            <p>Vos données personnelles peuvent être partagées avec les catégories de destinataires suivantes :</p>
            
            <div class="info-cards">
                <div class="info-card">
                    <h4><i class="fas fa-users"></i> Autres utilisateurs</h4>
                    <p>Les propriétaires peuvent voir le nom et l'email des étudiants qui manifestent un intérêt pour leur logement, et vice versa.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-server"></i> Prestataires techniques</h4>
                    <p>Hébergement web, maintenance, services de messagerie et outils d'analyse statistique.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-credit-card"></i> Services de paiement</h4>
                    <p>Si applicable, pour le traitement sécurisé des transactions financières.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-landmark"></i> Autorités légales</h4>
                    <p>En cas d'obligation légale, de demande judiciaire ou pour protéger nos droits légitimes.</p>
                </div>
            </div>

            <div class="success-box highlight-box">
                <i class="fas fa-check-circle"></i>
                <div class="highlight-box-content">
                    <strong>✓ Engagement fort</strong>
                    <p>Nous ne vendons, ne louons et ne partageons jamais vos données personnelles à des fins commerciales ou marketing avec des tiers. Tous nos prestataires sont contractuellement tenus de respecter la confidentialité et la sécurité de vos données.</p>
                </div>
            </div>
        </div>

        <!-- Section 7 -->
        <div class="content-section" id="securite">
            <h2><i class="fas fa-lock"></i> 7. Sécurité des données</h2>
            <p>La sécurité de vos données est notre priorité. Nous mettons en œuvre des mesures techniques et organisationnelles pour protéger vos informations :</p>
            
            <div class="info-cards">
                <div class="info-card">
                    <h4><i class="fas fa-key"></i> Cryptage des mots de passe</h4>
                    <p>Utilisation de l'algorithme bcrypt avec hachage sécurisé. Vos mots de passe ne sont jamais stockés en clair.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-shield-virus"></i> Protection HTTPS</h4>
                    <p>Toutes les communications sont chiffrées via SSL/TLS pour empêcher l'interception des données.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-user-lock"></i> Authentification</h4>
                    <p>Système d'authentification sécurisé avec gestion des sessions et protection contre les accès non autorisés.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-database"></i> Sauvegardes</h4>
                    <p>Sauvegardes régulières et automatiques de la base de données avec stockage sécurisé.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-user-shield"></i> Accès restreint</h4>
                    <p>Seul le personnel autorisé peut accéder aux données, avec traçabilité des accès.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-bug"></i> Protection contre les attaques</h4>
                    <p>Pare-feu, protection XSS, CSRF, injection SQL et surveillance des tentatives d'intrusion.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-sync"></i> Mises à jour</h4>
                    <p>Mises à jour régulières du système et des composants pour corriger les vulnérabilités.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-eye-slash"></i> Anonymisation</h4>
                    <p>Les données statistiques sont anonymisées pour protéger votre identité.</p>
                </div>
            </div>

            <div class="highlight-box">
                <i class="fas fa-exclamation-circle"></i>
                <div class="highlight-box-content">
                    <strong>⚠️ Votre responsabilité</strong>
                    <p>Vous êtes responsable de la confidentialité de vos identifiants de connexion. Ne partagez jamais votre mot de passe et déconnectez-vous après chaque session, surtout sur un ordinateur partagé. En cas de suspicion de compromission de votre compte, changez immédiatement votre mot de passe et contactez-nous.</p>
                </div>
            </div>
        </div>

        <!-- Section 8 -->
        <div class="content-section" id="droits">
            <h2><i class="fas fa-hand-paper"></i> 8. Vos droits</h2>
            <p>Conformément au RGPD, vous disposez des droits suivants concernant vos données personnelles :</p>
            
            <div class="rights-grid">
                <div class="right-item">
                    <h4><i class="fas fa-eye"></i> Droit d'accès</h4>
                    <p>Vous pouvez demander et obtenir une copie de toutes vos données personnelles que nous conservons.</p>
                </div>
                <div class="right-item">
                    <h4><i class="fas fa-edit"></i> Droit de rectification</h4>
                    <p>Vous pouvez corriger, mettre à jour ou compléter vos données si elles sont inexactes ou incomplètes.</p>
                </div>
                <div class="right-item">
                    <h4><i class="fas fa-trash"></i> Droit à l'effacement</h4>
                    <p>Vous pouvez demander la suppression de vos données dans certaines conditions (droit à l'oubli).</p>
                </div>
                <div class="right-item">
                    <h4><i class="fas fa-pause-circle"></i> Droit à la limitation</h4>
                    <p>Vous pouvez demander de limiter le traitement de vos données dans certains cas spécifiques.</p>
                </div>
                <div class="right-item">
                    <h4><i class="fas fa-download"></i> Droit à la portabilité</h4>
                    <p>Vous pouvez recevoir vos données dans un format structuré et lisible par machine pour les transférer à un autre service.</p>
                </div>
                <div class="right-item">
                    <h4><i class="fas fa-ban"></i> Droit d'opposition</h4>
                    <p>Vous pouvez vous opposer au traitement de vos données, notamment à des fins de prospection commerciale.</p>
                </div>
                <div class="right-item">
                    <h4><i class="fas fa-times-circle"></i> Retrait du consentement</h4>
                    <p>Vous pouvez retirer votre consentement à tout moment pour les traitements basés sur celui-ci.</p>
                </div>
                <div class="right-item">
                    <h4><i class="fas fa-user-times"></i> Droit à la désinscription</h4>
                    <p>Vous pouvez vous désinscrire des communications marketing à tout moment via les liens de désinscription.</p>
                </div>
            </div>

            <div class="info-box highlight-box">
                <i class="fas fa-info-circle"></i>
                <div class="highlight-box-content">
                    <strong>ℹ️ Exercice gratuit</strong>
                    <p>L'exercice de vos droits est totalement gratuit. Nous pouvons cependant facturer des frais raisonnables en cas de demandes manifestement infondées ou excessives, notamment en raison de leur caractère répétitif.</p>
                </div>
            </div>
        </div>

        <!-- Section 9 -->
        <div class="content-section" id="exercer">
            <h2><i class="fas fa-paper-plane"></i> 9. Exercer vos droits</h2>
            <p>Pour exercer vos droits ou pour toute question concernant la protection de vos données personnelles :</p>
            
            <div class="contact-box">
                <h3><i class="fas fa-envelope"></i> Nous Contacter</h3>
                <p>Nous nous engageons à répondre à votre demande dans un délai maximum d'<strong>un mois</strong> suivant sa réception. Ce délai peut être prolongé de deux mois si nécessaire, compte tenu de la complexité et du nombre de demandes.</p>
                
                <ul>
                    <li>
                        <i class="fas fa-at"></i>
                        <span><strong>Email :</strong> protection-donnees@studhome.fr</span>
                    </li>
                    <li>
                        <i class="fas fa-user-shield"></i>
                        <span><strong>DPO :</strong> dpo@studhome.fr (Délégué à la Protection des Données)</span>
                    </li>
                    <li>
                        <i class="fas fa-mail-bulk"></i>
                        <span><strong>Courrier :</strong> STUD_HOME - Service Protection des Données<br>
                        [Adresse postale à compléter]<br>
                        [Code postal et Ville]</span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <span><strong>Téléphone :</strong> [Numéro à compléter] (Du lundi au vendredi, 9h-18h)</span>
                    </li>
                </ul>

                <p style="margin-top: 20px;"><strong>Documents requis :</strong> Pour traiter votre demande, nous pourrons vous demander de justifier de votre identité (copie d'une pièce d'identité) afin de garantir la sécurité de vos données.</p>
            </div>
        </div>

        <!-- Section 10 -->
        <div class="content-section" id="reclamation">
            <h2><i class="fas fa-gavel"></i> 10. Droit de réclamation</h2>
            <p>
                Si vous estimez que vos droits concernant vos données personnelles ne sont pas respectés, 
                ou si vous n'êtes pas satisfait de notre réponse, vous avez le droit d'introduire 
                une réclamation auprès de l'autorité de contrôle compétente :
            </p>
            
            <div class="info-cards">
                <div class="info-card">
                    <h4><i class="fas fa-landmark"></i> CNIL</h4>
                    <p>Commission Nationale de l'Informatique et des Libertés - Autorité française de protection des données personnelles.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-globe"></i> Site web</h4>
                    <p><a href="https://www.cnil.fr" target="_blank" rel="noopener">www.cnil.fr</a> - Formulaire de plainte en ligne disponible</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-map-marker-alt"></i> Adresse</h4>
                    <p>CNIL - 3 Place de Fontenoy<br>TSA 80715 - 75334 PARIS CEDEX 07</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-phone-alt"></i> Contact</h4>
                    <p>Téléphone : 01 53 73 22 22<br>Du lundi au jeudi : 9h à 18h30<br>Le vendredi : 9h à 18h</p>
                </div>
            </div>
        </div>

        <!-- Section 11 -->
        <div class="content-section" id="transfert">
            <h2><i class="fas fa-globe-europe"></i> 11. Transfert de données hors UE</h2>
            <p>
                Vos données personnelles sont hébergées en <strong>France</strong> et traitées exclusivement 
                au sein de l'<strong>Union Européenne</strong>. Nous ne transférons pas vos données vers 
                des pays situés en dehors de l'Espace Économique Européen (EEE).
            </p>
            
            <div class="success-box highlight-box">
                <i class="fas fa-check-circle"></i>
                <div class="highlight-box-content">
                    <strong>✓ Protection maximale</strong>
                    <p>En conservant vos données au sein de l'UE, nous garantissons qu'elles bénéficient du plus haut niveau de protection établi par le RGPD. Si à l'avenir nous devions transférer des données hors UE, nous mettrions en place des garanties appropriées (clauses contractuelles types de la Commission européenne) et vous en informerions.</p>
                </div>
            </div>
        </div>

        <!-- Section 12 -->
        <div class="content-section" id="cookies">
            <h2><i class="fas fa-cookie-bite"></i> 12. Cookies et technologies similaires</h2>
            <p>
                Notre site utilise des cookies et technologies similaires pour améliorer votre expérience 
                de navigation et analyser l'utilisation du site.
            </p>
            
            <div class="info-cards">
                <div class="info-card">
                    <h4><i class="fas fa-cog"></i> Cookies essentiels</h4>
                    <p>Nécessaires au fonctionnement du site (connexion, panier). Ils ne peuvent pas être désactivés.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-sliders-h"></i> Cookies fonctionnels</h4>
                    <p>Améliorent l'expérience utilisateur en mémorisant vos préférences (langue, recherches).</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-chart-bar"></i> Cookies analytiques</h4>
                    <p>Nous aident à comprendre comment les visiteurs utilisent le site via des statistiques anonymes.</p>
                </div>
                <div class="info-card">
                    <h4><i class="fas fa-ad"></i> Cookies marketing</h4>
                    <p>Personnalisent la publicité et mesurent l'efficacité des campagnes (avec votre consentement).</p>
                </div>
            </div>

            <p style="margin-top: 25px; text-align: center;">
                <a href="<?= APP_URL ?>/parametres-cookies" style="display: inline-flex; align-items: center; gap: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3); transition: transform 0.3s ease;">
                    <i class="fas fa-cogs"></i>
                    Gérer mes préférences de cookies
                </a>
            </p>
        </div>

        <!-- Section 13 -->
        <div class="content-section">
            <h2><i class="fas fa-sync-alt"></i> 13. Modifications de la politique</h2>
            <p>
                Nous nous réservons le droit de modifier cette politique de protection des données à tout moment 
                pour refléter les changements dans nos pratiques, nos services ou les exigences légales.
            </p>
            <p>
                Toute modification substantielle sera communiquée de manière appropriée :
            </p>
            <ul>
                <li>Publication de la nouvelle version sur notre site web avec mise en évidence des changements</li>
                <li>Notification par email aux utilisateurs enregistrés pour les modifications importantes</li>
                <li>Alerte sur la plateforme lors de votre prochaine connexion</li>
                <li>Période de préavis raisonnable avant l'entrée en vigueur des changements</li>
            </ul>
            <p>
                Nous vous encourageons à consulter régulièrement cette page pour rester informé de nos pratiques 
                en matière de protection des données.
            </p>
        </div>

        <!-- Section 14 -->
        <div class="content-section">
            <h2><i class="fas fa-child"></i> 14. Protection des mineurs</h2>
            <p>
                Notre service est principalement destiné aux étudiants majeurs (18 ans et plus). 
                Si vous êtes mineur, vous devez obtenir l'autorisation de vos parents ou tuteurs 
                légaux avant de créer un compte et d'utiliser nos services.
            </p>
            <p>
                Nous ne collectons pas sciemment de données personnelles auprès de mineurs de moins de 15 ans 
                sans le consentement d'un titulaire de l'autorité parentale. Si nous découvrons qu'un mineur 
                a fourni des données personnelles sans autorisation parentale, nous supprimerons ces informations 
                dans les plus brefs délais.
            </p>
            
            <div class="highlight-box">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="highlight-box-content">
                    <strong>⚠️ Parents et tuteurs</strong>
                    <p>Si vous pensez que votre enfant nous a fourni des données personnelles sans votre consentement, veuillez nous contacter immédiatement à l'adresse protection-donnees@studhome.fr pour que nous puissions supprimer ces informations.</p>
                </div>
            </div>
        </div>

        <!-- Section 15 -->
        <div class="content-section">
            <h2><i class="fas fa-bell"></i> 15. Violation de données</h2>
            <p>
                En cas de violation de données personnelles susceptible d'engendrer un risque élevé pour vos droits 
                et libertés, nous nous engageons à :
            </p>
            <ol>
                <li>Notifier la CNIL dans les 72 heures suivant la découverte de la violation</li>
                <li>Vous informer dans les meilleurs délais si la violation présente un risque élevé pour vous</li>
                <li>Vous fournir des informations sur la nature de la violation et les mesures prises</li>
                <li>Vous conseiller sur les mesures à prendre pour protéger vos données</li>
                <li>Mettre en œuvre toutes les mesures nécessaires pour remédier à la violation</li>
            </ol>
            <p>
                Nous avons mis en place des procédures de détection, de notification et d'intervention en cas 
                de violation de données pour minimiser les risques et réagir rapidement.
            </p>
        </div>

        <!-- Date Update -->
        <div class="date-update">
            <p><i class="fas fa-calendar-alt"></i> <strong>Dernière mise à jour :</strong> 21 janvier 2026</p>
            <p><i class="fas fa-code-branch"></i> <strong>Version :</strong> 1.0</p>
            <p><i class="fas fa-balance-scale"></i> <strong>Conformité :</strong> RGPD (UE) 2016/679 • Loi Informatique et Libertés</p>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
</body>
</html>
