<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonces - STUD'HOME</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/annonce2.css">
</head>
<body>
    <?php include VIEWS_PATH . '/partials/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Trouvez votre logement étudiant idéal</h1>
            <form class="search-bar" action="<?= APP_URL ?>/annonces" method="GET">
                <input type="text" name="search" placeholder="Ville, quartier, code postal..." value="<?= $_GET['search'] ?? '' ?>">
                <button type="submit">🔍</button>
            </form>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container">
        <div class="content-wrapper">
            <!-- Sidebar Filters -->
            <aside class="sidebar-filters">
                <h2>
                    Filtres
                    <span class="filter-reset" onclick="resetFilters()">Réinitialiser</span>
                </h2>

                <form action="<?= APP_URL ?>/annonces" method="GET" id="filterForm">
                    <!-- Type de bien -->
                    <div class="filter-group">
                        <h3>Type de bien</h3>
                        <label>
                            <input type="checkbox" name="type[]" value="appartement" <?= in_array('appartement', $_GET['type'] ?? []) ? 'checked' : '' ?>>
                            Appartement
                        </label>
                        <label>
                            <input type="checkbox" name="type[]" value="studio" <?= in_array('studio', $_GET['type'] ?? []) ? 'checked' : '' ?>>
                            Studio
                        </label>
                        <label>
                            <input type="checkbox" name="type[]" value="chambre" <?= in_array('chambre', $_GET['type'] ?? []) ? 'checked' : '' ?>>
                            Chambre
                        </label>
                        <label>
                            <input type="checkbox" name="type[]" value="colocation" <?= in_array('colocation', $_GET['type'] ?? []) ? 'checked' : '' ?>>
                            Colocation
                        </label>
                    </div>

                    <!-- Prix -->
                    <div class="filter-group">
                        <h3>Prix (€/mois)</h3>
                        <div class="price-range">
                            <input type="number" name="prix_min" placeholder="Min" min="0" value="<?= $_GET['prix_min'] ?? '' ?>">
                            <span>-</span>
                            <input type="number" name="prix_max" placeholder="Max" min="0" value="<?= $_GET['prix_max'] ?? '' ?>">
                        </div>
                    </div>

                    <!-- Surface -->
                    <div class="filter-group">
                        <h3>Surface (m²)</h3>
                        <div class="price-range">
                            <input type="number" name="surface_min" placeholder="Min" min="0" value="<?= $_GET['surface_min'] ?? '' ?>">
                            <span>-</span>
                            <input type="number" name="surface_max" placeholder="Max" min="0" value="<?= $_GET['surface_max'] ?? '' ?>">
                        </div>
                    </div>

                    <!-- Nombre de pièces -->
                    <div class="filter-group">
                        <h3>Nombre de pièces</h3>
                        <select name="pieces">
                            <option value="">Indifférent</option>
                            <option value="1" <?= ($_GET['pieces'] ?? '') === '1' ? 'selected' : '' ?>>1 pièce</option>
                            <option value="2" <?= ($_GET['pieces'] ?? '') === '2' ? 'selected' : '' ?>>2 pièces</option>
                            <option value="3" <?= ($_GET['pieces'] ?? '') === '3' ? 'selected' : '' ?>>3 pièces</option>
                            <option value="4" <?= ($_GET['pieces'] ?? '') === '4' ? 'selected' : '' ?>>4 pièces</option>
                            <option value="5+" <?= ($_GET['pieces'] ?? '') === '5+' ? 'selected' : '' ?>>5 pièces et +</option>
                        </select>
                    </div>

                    <!-- Équipements -->
                    <div class="filter-group">
                        <h3>Équipements</h3>
                        <label>
                            <input type="checkbox" name="equipement[]" value="meuble" <?= in_array('meuble', $_GET['equipement'] ?? []) ? 'checked' : '' ?>>
                            Meublé
                        </label>
                        <label>
                            <input type="checkbox" name="equipement[]" value="parking" <?= in_array('parking', $_GET['equipement'] ?? []) ? 'checked' : '' ?>>
                            Parking
                        </label>
                        <label>
                            <input type="checkbox" name="equipement[]" value="ascenseur" <?= in_array('ascenseur', $_GET['equipement'] ?? []) ? 'checked' : '' ?>>
                            Ascenseur
                        </label>
                        <label>
                            <input type="checkbox" name="equipement[]" value="balcon" <?= in_array('balcon', $_GET['equipement'] ?? []) ? 'checked' : '' ?>>
                            Balcon
                        </label>
                    </div>

                    <button type="submit" class="filter-apply">Appliquer les filtres</button>
                </form>
            </aside>

            <!-- Main Content Area -->
            <div class="main-content">
                <!-- Results Header -->
                <div class="results-header">
                    <div class="results-count">
                        <strong><?= count($annonces ?? []) ?></strong> logements disponibles
                    </div>
                    <div class="sort-options">
                        <select name="sort" onchange="sortResults(this.value)">
                            <option value="pertinence">Pertinence</option>
                            <option value="prix_asc">Prix croissant</option>
                            <option value="prix_desc">Prix décroissant</option>
                            <option value="surface_desc">Surface décroissante</option>
                            <option value="recent">Plus récent</option>
                        </select>
                    </div>
                </div>

                <!-- Listings Grid -->
                <div class="listings-grid" id="annonces-container">
                <div class="listings-grid">
                    <?php if (!empty($annonces)): ?>
                        <?php foreach ($annonces as $annonce): ?>
                            <article class="listing-card" onclick="location.href='<?= APP_URL ?>/annonces/details/<?= $annonce['id'] ?>'">
                                <div class="card-image">
                                    <?php if (!empty($annonce['image_principale'])): ?>
                                        <img src="<?= APP_URL ?>/<?= htmlspecialchars($annonce['image_principale']) ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php if (!empty($annonce['photo'])): ?>
                                        <img src="<?= APP_URL ?>/<?= htmlspecialchars($annonce['photo']) ?>" alt="<?= htmlspecialchars($annonce['titre']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="image-placeholder"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-content">
                                    <div class="card-header">
                                        <span class="price"><?= number_format($annonce['prix'], 0, ',', ' ') ?> €/mois</span>
                                    </div>
                                    <h3 class="property-type"><?= ucfirst(htmlspecialchars($annonce['type'])) ?></h3>
                                    <p class="property-details">
                                        <?= $annonce['nombre_pieces'] ?? 'N/A' ?> Pièce(s) - 
                                        <?= $annonce['nombre_chambres'] ?? 'N/A' ?> Chambre(s) - 
                                        <?= $annonce['surface'] ?? 'N/A' ?> m²
                                    </p>
                                    <p class="property-location"><?= htmlspecialchars($annonce['ville']) ?> (<?= htmlspecialchars($annonce['code_postal'] ?? '') ?>)</p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #999;">Aucune annonce trouvée avec ces critères.</p>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="pagination" id="pagination-container" data-current-page="<?= $currentPage ?>" data-total-pages="<?= $totalPages ?>">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= APP_URL ?>/annonces?page=<?= $currentPage - 1 ?>" class="page-link">&laquo; Précédent</a>
                    <?php endif; ?>
                    
                    <?php 
                    // Afficher les numéros de page
                    $start = max(1, $currentPage - 2);
                    $end = min($totalPages, $currentPage + 2);
                    
                    if ($start > 1): ?>
                        <a href="<?= APP_URL ?>/annonces?page=1" class="page-link">1</a>
                        <?php if ($start > 2): ?>
                            <span class="page-dots">...</span>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <a href="<?= APP_URL ?>/annonces?page=<?= $i ?>" class="page-link <?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($end < $totalPages): ?>
                        <?php if ($end < $totalPages - 1): ?>
                            <span class="page-dots">...</span>
                        <?php endif; ?>
                        <a href="<?= APP_URL ?>/annonces?page=<?= $totalPages ?>" class="page-link"><?= $totalPages ?></a>
                    <?php endif; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= APP_URL ?>/annonces?page=<?= $currentPage + 1 ?>" class="page-link">Suivant &raquo;</a>
                    <?php endif; ?>
                <?php if (!empty($annonces) && count($annonces) > 0): ?>
                <div class="pagination">
                    <a href="#page1" class="page-link active">1</a>
                    <a href="#page2" class="page-link">2</a>
                    <a href="#page3" class="page-link">3</a>
                    <span class="page-dots">...</span>
                    <a href="#page10" class="page-link">10</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include VIEWS_PATH . '/partials/footer.php'; ?>

    <script>
        function resetFilters() {
            document.getElementById('filterForm').reset();
            window.location.href = '<?= APP_URL ?>/annonces';
        }

        function sortResults(sortValue) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortValue);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>
