-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 21, 2026 at 01:23 PM
-- Server version: 5.7.24
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stud_home_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `annonces`
--

CREATE TABLE `annonces` (
  `id` int(11) NOT NULL,
  `proprietaire_id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('studio','appartement','maison','chambre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `surface` int(11) NOT NULL COMMENT 'Surface en m²',
  `nombre_chambres` int(11) NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `annonces`
--

INSERT INTO `annonces` (`id`, `proprietaire_id`, `titre`, `description`, `type`, `adresse`, `ville`, `code_postal`, `prix`, `surface`, `nombre_chambres`, `photo`, `statut`, `date_creation`, `date_modification`) VALUES
(1, 4, 'Studio lumineux proche métro', 'Charmant studio de 25m² idéalement situé à proximité du métro. Cuisine équipée, salle de bain avec douche. Parfait pour un étudiant.', 'studio', '12 rue de la Paix', 'Paris', '75002', '650.00', 25, 1, NULL, 'active', '2025-12-12 11:31:50', '2025-12-12 11:31:50'),
(2, 4, 'Appartement T2 refait à neuf', 'Bel appartement T2 de 45m² entièrement rénové. Salon spacieux, chambre avec placard, cuisine américaine équipée.', 'appartement', '8 avenue des Champs', 'Lyon', '69002', '850.00', 45, 2, NULL, 'active', '2025-12-12 11:31:50', '2025-12-12 11:31:50'),
(8, 23, '\\000', '% ---&#039;&#039;&#039; \\\\\\\r\nghg cjgfjf fg jgfjgf gjgjj j jfjg ugy', 'studio', '12 rue de vanves', 'issy', '92120', '750.00', 1, 1, NULL, 'active', '2026-01-09 11:24:33', '2026-01-09 11:24:33'),
(9, 7, 'dsgfs', 'qsvsdbwsgwgwggwsgdwsgsgdsgswgdsgdsdsgsdwg&#039;OR &#039;1&#039;=&#039;1&#039;;', 'studio', 'sgdssbs', 'vss', '34556', '32.00', 23, 2, NULL, 'active', '2026-01-12 10:40:37', '2026-01-12 10:40:37'),
(10, 7, 'cxddddd', 'csqfccddddddddddddddddddddc', 'studio', '12 rue de vanves', 'paris', '75015', '200.00', 1, 1, NULL, 'active', '2026-01-21 00:06:45', '2026-01-21 00:06:45'),
(11, 29, 'Studio Porte de Maillot', 'Studio proche de la porte de bagneux', 'studio', '85 rue de la Porte de Maillot', 'Paris', '75016', '800.00', 50, 2, NULL, 'active', '2026-01-21 13:37:11', '2026-01-21 13:37:11');

-- --------------------------------------------------------

--
-- Table structure for table `annonces_images`
--

CREATE TABLE `annonces_images` (
  `id` int(11) NOT NULL,
  `annonce_id` int(11) NOT NULL,
  `chemin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(11) DEFAULT '0' COMMENT 'Ordre d''affichage',
  `est_principale` tinyint(1) DEFAULT '0' COMMENT 'Image principale de l''annonce',
  `date_ajout` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `annonces_images`
--

INSERT INTO `annonces_images` (`id`, `annonce_id`, `chemin`, `ordre`, `est_principale`, `date_ajout`) VALUES
(1, 10, 'uploads/annonces/annonce_69700a85329fb7.14973346.png', 0, 1, '2026-01-21 00:06:45'),
(2, 11, 'uploads/annonces/annonce_6970c8777e0721.62477661.png', 0, 1, '2026-01-21 13:37:11'),
(3, 11, 'uploads/annonces/annonce_6970c8777ed5d3.63624829.png', 1, 0, '2026-01-21 13:37:11'),
(4, 11, 'uploads/annonces/annonce_6970c877970945.01816862.png', 2, 0, '2026-01-21 13:37:11'),
(5, 11, 'uploads/annonces/annonce_6970c87797b9f9.88057235.png', 3, 0, '2026-01-21 13:37:11');

-- --------------------------------------------------------

--
-- Table structure for table `demandes_interet`
--

CREATE TABLE `demandes_interet` (
  `id` int(11) NOT NULL,
  `annonce_id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('nouveau','vue','refuse','accepte') COLLATE utf8mb4_unicode_ci DEFAULT 'nouveau',
  `date_demande` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_reponse` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `demandes_interet`
--

INSERT INTO `demandes_interet` (`id`, `annonce_id`, `etudiant_id`, `email`, `telephone`, `message`, `statut`, `date_demande`, `date_reponse`) VALUES
(1, 9, 28, 'ridge@gmail.com', '0699999999', 'Bonjour, je vous sollicite pour ce logement', 'nouveau', '2026-01-21 12:19:36', NULL),
(2, 11, 6, 'alexandre.lepont@gmail.com', '06 55 55 55 55', 'dekldjcjkjczed', 'nouveau', '2026-01-21 13:48:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `etudiants`
--

CREATE TABLE `etudiants` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `ecole` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `etudiants`
--

INSERT INTO `etudiants` (`id`, `utilisateur_id`, `ecole`) VALUES
(1, 2, 'Université Paris 8'),
(2, 3, 'Sorbonne Université'),
(3, 6, 'Isep'),
(6, 11, 'Isep'),
(7, 12, 'ISEP'),
(8, 13, 'ISEP'),
(17, 24, 'isep'),
(18, 26, 'ISEP'),
(19, 28, 'ISEP');

-- --------------------------------------------------------

--
-- Table structure for table `favoris`
--

CREATE TABLE `favoris` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `annonce_id` int(11) NOT NULL,
  `date_ajout` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favoris`
--

INSERT INTO `favoris` (`id`, `etudiant_id`, `annonce_id`, `date_ajout`) VALUES
(1, 6, 1, '2025-12-16 16:05:20'),
(2, 11, 2, '2025-12-18 16:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(29, 'alkaly1981@gmail.com', '4cc2473e7e5204e0b2b87632b973570f6ccef133bc9c918a9dcbe82153098b10', NULL, '2025-12-18 10:26:13'),
(32, 'gabinmermet05@gmail.com', '4e2f16ecb164fc3147816ccde70f055636cb0aab98bb5371c1bac7562fd3582d', NULL, '2026-01-09 11:16:28');

-- --------------------------------------------------------

--
-- Table structure for table `proprietaires`
--

CREATE TABLE `proprietaires` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proprietaires`
--

INSERT INTO `proprietaires` (`id`, `utilisateur_id`, `telephone`) VALUES
(1, 4, '06 12 34 56 78'),
(3, 7, '06 88 74 12 25'),
(6, 23, '0612345645'),
(7, 29, '06 98 98 98 98');

-- --------------------------------------------------------

--
-- Table structure for table `signalements`
--

CREATE TABLE `signalements` (
  `id` int(11) NOT NULL,
  `annonce_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `motif` enum('spam','arnaque','contenu_inapproprie','annonce_disparue','autre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('nouveau','en_cours','resolu','clos') COLLATE utf8mb4_unicode_ci DEFAULT 'nouveau',
  `date_signalement` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `signalements`
--

INSERT INTO `signalements` (`id`, `annonce_id`, `utilisateur_id`, `motif`, `description`, `statut`, `date_signalement`) VALUES
(1, 10, 7, 'annonce_disparue', 'L&#039;annonce est fausse, en effet il y aucun logement à cette addresse', 'en_cours', '2026-01-21 11:39:39');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('etudiant','proprietaire','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `security_question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `security_answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `type`, `date_inscription`, `security_question`, `security_answer`) VALUES
(1, 'Admin', 'Super', 'admin@studhome.fr', '$2y$10$5rZXF/ro4NVPTeZdGpdNFeRi9.GP30z69GhqlakdXkgjDTgK2lz4C', 'admin', '2025-12-12 11:31:50', 'Quelle est votre ville de naissance ?', '$2y$10$RvADPGFxMy/es.5rQtTTd.Y.n1s3UNLxRK5cjYj8AaO40MR4hXqOi'),
(2, 'Dupont', 'Jean', 'jean.dupont@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', '2025-12-12 11:31:50', '', ''),
(4, 'Leroux', 'Pierre', 'pierre.leroux@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'proprietaire', '2025-12-12 11:31:50', '', ''),
(6, 'Lepont', 'Alexandre', 'alexandre.lepont@gmail.com', '$2y$10$qZ0aVkH2deqItSe2SkjM/.jm4TYy.D5INh78vqyvgfClH9Q/zVOLG', 'etudiant', '2025-12-15 11:01:23', '', ''),
(7, 'dutrou', 'jean', 'jean@popo.com', '$2y$10$KFDYhf.qdISNFesM.0sMGO/VQ4Z/IG.UJTeZnEG3RaqSqjZm3eBAu', 'proprietaire', '2025-12-16 16:06:45', '', ''),
(11, 'Grouiller', 'Arnaud', 'arnaud@gmail.com', '$2y$10$HTILO/.4eI0mnL.mhMubaO/L./mdeZ2pXlehYYqoC5takqPt7e6ya', 'etudiant', '2025-12-18 14:12:13', 'Quelle est votre ville de naissance ?', '$2y$10$RvADPGFxMy/es.5rQtTTd.Y.n1s3UNLxRK5cjYj8AaO40MR4hXqOi'),
(12, 'l&#039;evantreur', 'Jack', 'jack@gmail.com', '$2y$10$xLBZPVy6vpPH.bAoT33L.uAyXOfHXDO9U.3AXBMssMAZQ2KPvx82G', 'etudiant', '2025-12-19 08:27:46', 'Quel était le nom de votre école primaire ?', '$2y$10$rfEQW0PejngI2usaqo2kxe5TAiuGkQqlSOyrjeX1qk2X.fzFN.rlG'),
(13, 'dubois', 'claude', 'claude@gmail.com', '$2y$10$q.aYNBVjrTRDcjcdc/e3puAR1dU8tPEpvfSO.ugQW4CEY0123Cz3C', 'etudiant', '2025-12-19 09:48:42', 'Quelle est votre ville de naissance ?', '$2y$10$ut.6hMiqx8GbfPP/QSWe3u11ChIgTt2cEocWtO.pTtxoj3bb2EFFS'),
(14, 'blabla', 'car', 'car@gmail.com', '$2y$10$/qTEh8gw27LpPtO2ppFILep3QCWXCER92xQOufCQ/Gh73mbk1m1L.', 'etudiant', '2026-01-08 10:57:20', 'Quelle est votre ville de naissance ?', '$2y$10$zGOv.YyWrsZ.dgWCsprxSecEqBEAn5LtVnHCYshFUBLSGmn.7tGEC'),
(22, 'r', 's', 'rayansahraoui@gmail.com', '$2y$10$fzivWUd/a7l6qbAp4KOhb.Nvp68KFcU9MSNfZMdh3adc3KC5R4YY6', 'proprietaire', '2026-01-09 08:49:07', 'Quel est le nom de votre premier animal ?', '$2y$10$zZF1.RBde8TX5.4cFIDmOuWof6jIxFGhxP88RtqK/kJNtMY3BiS3O'),
(23, 'r2', 's2', 'rayan2@gmail.com', '$2y$10$2ToviY8S6/b6LlgM1Xdi6eAtYmfK.Zj5fauI5EH9oFwP92GSm5h.K', 'proprietaire', '2026-01-09 09:02:10', 'Quel est le nom de jeune fille de votre mère ?', '$2y$10$kpLbmstDbACVmKunGM3I4O7iZ14S325IIopJ7qVrd0OwTQhogBu/W'),
(24, 're', 'se', 'rayane@gmail.com', '$2y$10$W/F2E0p6RyxiBUWGtrKvveDXNNGYjpUumtju5hg8OG5hJdnZ0Dg.y', 'etudiant', '2026-01-09 09:04:52', 'Quelle est votre ville de naissance ?', '$2y$10$rYeLVNUr4IvHgf.FmfYTo.J30jepRkQZAHrbKM8Ywau0Xc3udkDPS'),
(26, 'Mermet', 'Gabin', 'gabinmermet05@gmail.com', '$2y$10$2CrXXK9Y6bCBaV9rnRi2JOHAMc5HzSrQkVgbl1lXne5stl.bXhttS', 'etudiant', '2026-01-09 12:16:12', 'Quelle est votre ville de naissance ?', '$2y$10$IWSY43CDmQGMyD1xVPAiQ.aEE6Hjk1Bqr.iRkYPNc3oymGudDonmu'),
(27, 'Dubois', 'Claude', 'claude92100@gmail.com', '$2y$10$efQWk8BauEUMcPwfhJoyGua/5zzSEDxtPpxNeGDP0.9G6kEZ.1T4S', 'etudiant', '2026-01-21 12:09:45', 'Quelle est votre ville de naissance ?', '$2y$10$C71VRP6v5MxPQ9fc/DMQEer/tUTLeqB5Nr0CihBtJvRWyHX1eLfoO'),
(28, 'Laguerre', 'Ridge', 'ridge@gmail.com', '$2y$10$T/FEZq8wTT.ZzQjxxAhF6OaxT4W5jVmGSf389HkVZqUuS2GKkJkCW', 'etudiant', '2026-01-21 12:12:34', 'Quelle est votre ville de naissance ?', '$2y$10$L3KPBi.gxRJ.4.wbULFKvedHGpgqKMhso8j1mY3SBhW9y0PdL4eqK'),
(29, 'Keita', 'Oumy', 'alkaly156@gmail.com', '$2y$10$pQcEyKY2MfOSeWai8Pzi7uhUh/ryjfYRVvhgg2vQqqmuEjNesR2da', 'proprietaire', '2026-01-21 13:34:42', 'Quelle est votre ville de naissance ?', '$2y$10$9LVOfmv206doiwBc0Bh2qeBOiifFDOcqW3VmWEDj2Gl4fyw0SusNa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `annonces`
--
ALTER TABLE `annonces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_proprietaire` (`proprietaire_id`),
  ADD KEY `idx_ville` (`ville`),
  ADD KEY `idx_prix` (`prix`),
  ADD KEY `idx_statut` (`statut`);

--
-- Indexes for table `annonces_images`
--
ALTER TABLE `annonces_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_annonce` (`annonce_id`),
  ADD KEY `idx_ordre` (`ordre`);

--
-- Indexes for table `demandes_interet`
--
ALTER TABLE `demandes_interet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_annonce` (`annonce_id`),
  ADD KEY `idx_etudiant` (`etudiant_id`),
  ADD KEY `idx_statut` (`statut`);

--
-- Indexes for table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proprietaires`
--
ALTER TABLE `proprietaires`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `signalements`
--
ALTER TABLE `signalements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_signalement` (`annonce_id`,`utilisateur_id`),
  ADD KEY `idx_annonce` (`annonce_id`),
  ADD KEY `idx_utilisateur` (`utilisateur_id`),
  ADD KEY `idx_statut` (`statut`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `annonces`
--
ALTER TABLE `annonces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `annonces_images`
--
ALTER TABLE `annonces_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `demandes_interet`
--
ALTER TABLE `demandes_interet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `proprietaires`
--
ALTER TABLE `proprietaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `signalements`
--
ALTER TABLE `signalements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `annonces_images`
--
ALTER TABLE `annonces_images`
  ADD CONSTRAINT `annonces_images_ibfk_1` FOREIGN KEY (`annonce_id`) REFERENCES `annonces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `demandes_interet`
--
ALTER TABLE `demandes_interet`
  ADD CONSTRAINT `demandes_interet_ibfk_1` FOREIGN KEY (`annonce_id`) REFERENCES `annonces` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `demandes_interet_ibfk_2` FOREIGN KEY (`etudiant_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
