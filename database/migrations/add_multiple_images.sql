-- ========================================
-- MIGRATION: Ajout support images multiples
-- Date: 2026-01-15
-- ========================================

-- Créer une table pour stocker plusieurs images par annonce
CREATE TABLE IF NOT EXISTS annonces_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    annonce_id INT NOT NULL,
    chemin VARCHAR(255) NOT NULL,
    ordre INT DEFAULT 0 COMMENT 'Ordre d''affichage',
    est_principale BOOLEAN DEFAULT FALSE COMMENT 'Image principale de l''annonce',
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    INDEX idx_annonce (annonce_id),
    INDEX idx_ordre (ordre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrer les photos existantes vers la nouvelle table
INSERT INTO annonces_images (annonce_id, chemin, ordre, est_principale)
SELECT id, photo, 0, TRUE
FROM annonces
WHERE photo IS NOT NULL AND photo != '';

-- Note: La colonne 'photo' dans la table 'annonces' est conservée pour compatibilité
-- mais ne sera plus utilisée pour les nouvelles annonces
