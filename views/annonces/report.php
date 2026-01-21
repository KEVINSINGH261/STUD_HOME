<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signaler une annonce - STUD_HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/annonce-detail.css">
    <style>
        .report-form {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .report-form h2 {
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        
        .annonce-info {
            background: #e8f4f8;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        
        .annonce-info h3 {
            margin: 0 0 10px 0;
            color: #007bff;
            font-size: 16px;
        }
        
        .annonce-info p {
            margin: 5px 0;
            color: #555;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-group label .required {
            color: #dc3545;
        }
        
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
            box-sizing: border-box;
        }
        
        .form-group select {
            background: white;
            cursor: pointer;
        }
        
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .form-group p {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .form-actions button,
        .form-actions a {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .form-actions button {
            background: #dc3545;
            color: white;
        }
        
        .form-actions button:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220,53,69,0.3);
        }
        
        .form-actions a {
            background: #e9ecef;
            color: #333;
        }
        
        .form-actions a:hover {
            background: #dee2e6;
        }
        
        .motif-description {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 13px;
            color: #666;
            line-height: 1.5;
        }
        
        .motif-description strong {
            color: #333;
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>
    
    <main class="annonce-report">
        <div class="container">
            <?php if (isset($flash) && $flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>
            
            <div class="report-form">
                <h2>🚩 Signaler cette annonce</h2>
                
                <div class="annonce-info">
                    <h3>Annonce signalée :</h3>
                    <p><strong><?= htmlspecialchars($annonce['titre']) ?></strong></p>
                    <p><?= htmlspecialchars($annonce['ville']) ?> - <?= number_format($annonce['prix'], 0, ',', ' ') ?> €/mois</p>
                    <p><small>Par : <?= htmlspecialchars($annonce['prenom'] . ' ' . $annonce['nom']) ?></small></p>
                </div>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="motif">
                            Motif du signalement <span class="required">*</span>
                        </label>
                        <select id="motif" name="motif" required onchange="updateMotifDescription()">
                            <option value="">-- Sélectionnez un motif --</option>
                            <option value="spam">Spam ou contenu publicitaire</option>
                            <option value="arnaque">Arnaque ou escroquerie</option>
                            <option value="contenu_inapproprie">Contenu inapproprié ou offensant</option>
                            <option value="annonce_disparue">Annonce disparue ou inexistante</option>
                            <option value="autre">Autre raison</option>
                        </select>
                        <div class="motif-description" id="motifDescription"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">
                            Détails du signalement <span class="required">*</span>
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            placeholder="Veuillez expliquer en détail pourquoi vous signalez cette annonce..." 
                            required
                        ></textarea>
                        <p>Minimum 10 caractères pour un meilleur traitement de votre signalement.</p>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit">Soumettre le signalement</button>
                        <a href="<?= APP_URL ?>/annonces/details/<?= $annonce['id'] ?>">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <?php include VIEWS_PATH . '/partials/footer.php'; ?>
    
    <script>
        const motifDescriptions = {
            'spam': 'L\'annonce contient du spam ou de la publicité non sollicitée.',
            'arnaque': 'Vous soupçonnez une escroquerie, fraude ou arnaque.',
            'contenu_inapproprie': 'L\'annonce contient du contenu offensant, discriminatoire ou inapproprié.',
            'annonce_disparue': 'Le logement a déjà été loué ou l\'annonce n\'est plus valide.',
            'autre': 'Toute autre raison à préciser dans les détails ci-dessous.'
        };
        
        function updateMotifDescription() {
            const select = document.getElementById('motif');
            const description = document.getElementById('motifDescription');
            const selected = select.value;
            
            if (selected && motifDescriptions[selected]) {
                description.innerHTML = '<strong>Description :</strong> ' + motifDescriptions[selected];
            } else {
                description.innerHTML = '';
            }
        }
        
        // Validation au submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const motif = document.getElementById('motif').value;
            const description = document.getElementById('description').value;
            
            if (!motif) {
                e.preventDefault();
                alert('Veuillez sélectionner un motif de signalement.');
                return false;
            }
            
            if (description.trim().length < 10) {
                e.preventDefault();
                alert('Veuillez fournir plus de détails (minimum 10 caractères).');
                return false;
            }
            
            return true;
        });
    </script>
    
    <script src="<?= APP_URL ?>/js/main.js"></script>
</body>
</html>
