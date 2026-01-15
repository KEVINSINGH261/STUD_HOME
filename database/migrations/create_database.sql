-- ========================================
-- STUD_HOME - Script de Création de la Base de Données
-- Gestion de Logements Étudiants
-- ========================================

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS stud_home_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE stud_home_db;

-- ========================================
-- TABLE: utilisateurs (Table Parent)
-- ========================================
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    type ENUM('etudiant', 'proprietaire', 'admin') NOT NULL,
    security_question VARCHAR(255) DEFAULT NULL,
    security_answer VARCHAR(255) DEFAULT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE: etudiants (Héritage de utilisateurs)
-- ========================================
CREATE TABLE etudiants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL UNIQUE,
    ecole VARCHAR(255) NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    INDEX idx_utilisateur (utilisateur_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE: proprietaires (Héritage de utilisateurs)
-- ========================================
CREATE TABLE proprietaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    INDEX idx_utilisateur (utilisateur_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE: annonces
-- ========================================
CREATE TABLE annonces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proprietaire_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    type ENUM('studio', 'appartement', 'maison', 'chambre') NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    code_postal VARCHAR(10) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    surface INT NOT NULL COMMENT 'Surface en m²',
    nombre_chambres INT NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    statut ENUM('active', 'inactive') DEFAULT 'active',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (proprietaire_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    INDEX idx_proprietaire (proprietaire_id),
    INDEX idx_ville (ville),
    INDEX idx_prix (prix),
    INDEX idx_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLE: favoris
-- ========================================
CREATE TABLE favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT NOT NULL,
    annonce_id INT NOT NULL,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etudiant_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (annonce_id) REFERENCES annonces(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favori (etudiant_id, annonce_id),
    INDEX idx_etudiant (etudiant_id),
    INDEX idx_annonce (annonce_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- DONNÉES DE TEST
-- ========================================

-- Insertion d'un administrateur par défaut
-- Mot de passe: admin123
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, type) VALUES
('Admin', 'Super', 'admin@studhome.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insertion d'étudiants de test
-- Mot de passe: student123
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, type) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant'),
('Martin', 'Sophie', 'sophie.martin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant');

INSERT INTO etudiants (utilisateur_id, ecole) VALUES
(2, 'Université Paris 8'),
(3, 'Sorbonne Université');

-- Insertion de propriétaires de test
-- Mot de passe: owner123
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, type) VALUES
('Leroux', 'Pierre', 'pierre.leroux@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'proprietaire'),
('Dubois', 'Marie', 'marie.dubois@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'proprietaire');

INSERT INTO proprietaires (utilisateur_id, telephone) VALUES
(4, '06 12 34 56 78'),
(5, '06 98 76 54 32');

-- Insertion d'annonces de test
INSERT INTO annonces (proprietaire_id, titre, description, type, adresse, ville, code_postal, prix, surface, nombre_chambres, statut) VALUES
(4, 'Studio lumineux proche métro', 'Charmant studio de 25m² idéalement situé à proximité du métro. Cuisine équipée, salle de bain avec douche. Parfait pour un étudiant.', 'studio', '12 rue de la Paix', 'Paris', '75002', 650.00, 25, 1, 'active'),
(4, 'Appartement T2 refait à neuf', 'Bel appartement T2 de 45m² entièrement rénové. Salon spacieux, chambre avec placard, cuisine américaine équipée.', 'appartement', '8 avenue des Champs', 'Lyon', '69002', 850.00, 45, 2, 'active'),
(5, 'Chambre dans colocation', 'Grande chambre de 15m² dans une colocation de 4 personnes. Salle de bain partagée, cuisine commune équipée.', 'chambre', '45 boulevard Victor Hugo', 'Lille', '59000', 400.00, 15, 1, 'active'),
(5, 'T3 avec balcon', 'Superbe T3 de 70m² avec balcon. Vue dégagée, lumineux, proche des universités et transports.', 'appartement', '23 rue Pasteur', 'Toulouse', '31000', 1100.00, 70, 3, 'active');

-- ========================================
-- FIN DU SCRIPT
-- ========================================
