-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 28 mars 2026 à 18:04
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `club_lecture`
--

-- --------------------------------------------------------

--
-- Structure de la table `auteurs`
--

DROP TABLE IF EXISTS `auteurs`;
CREATE TABLE IF NOT EXISTS `auteurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `biographie` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `auteurs`
--

INSERT INTO `auteurs` (`id`, `nom`, `prenom`, `biographie`) VALUES
(3, 'McFadden', 'Freida', NULL),
(4, 'McFadden', 'Freida', NULL),
(5, 'Rivens', 'Sarah', NULL),
(6, 'Amal', 'Djaili Amadou', NULL),
(7, 'McFadden', 'Freida', NULL),
(8, 'Khadra', 'Yasmina', NULL),
(9, 'Bah', 'Mariam ', NULL),
(10, 'Red', 'Azra', NULL),
(11, 'Amal', 'Djaili Amadou', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `note` int DEFAULT NULL,
  `commentaire` text COLLATE utf8mb4_general_ci,
  `utilisateur_id` int DEFAULT NULL,
  `lecture_id` int NOT NULL,
  `date_avis` datetime DEFAULT CURRENT_TIMESTAMP,
  `visible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_avis_user` (`utilisateur_id`),
  KEY `fk_avis_lecture` (`lecture_id`)
) ;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `note`, `commentaire`, `utilisateur_id`, `lecture_id`, `date_avis`, `visible`) VALUES
(21, 1, 'livre bien', 10, 32, '2026-03-28 18:21:18', 1),
(22, 1, 'LIVRE BIEN ', 10, 34, '2026-03-28 18:22:26', 1),
(23, 1, 'LIVRE BIEN ', 10, 35, '2026-03-28 18:22:50', 1),
(24, 1, 'LIVRE BIEN ', 10, 36, '2026-03-28 18:23:20', 1),
(25, 1, 'LIVRE BIEN ', 10, 37, '2026-03-28 18:23:46', 1),
(26, 1, 'LIVRE BIEN ', 10, 38, '2026-03-28 18:24:13', 1),
(27, 1, 'LIVRE BIEN', 10, 39, '2026-03-28 18:25:05', 1);

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lecture_id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `chemin` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `taille` int NOT NULL,
  `date_upload` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_documents_lecture` (`lecture_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `lecture_id`, `nom`, `chemin`, `taille`, `date_upload`) VALUES
(20, 32, 'Exercices CEJM 19_03 (1).pdf', 'uploads/1774718494_Exercices CEJM 19_03 (1).pdf', 75783, '2026-03-28 18:21:34'),
(21, 33, 'Exercices CEJM 19_03 (1).pdf', 'uploads/1774718529_Exercices CEJM 19_03 (1).pdf', 75783, '2026-03-28 18:22:09'),
(22, 34, 'Exercices CEJM 19_03 (1).pdf', 'uploads/1774718557_Exercices CEJM 19_03 (1).pdf', 75783, '2026-03-28 18:22:37'),
(23, 35, 'Exercices CEJM 19_03.pdf', 'uploads/1774718586_Exercices CEJM 19_03.pdf', 75783, '2026-03-28 18:23:06'),
(24, 36, 'Exercices CEJM 19_03 (1).pdf', 'uploads/1774718613_Exercices CEJM 19_03 (1).pdf', 75783, '2026-03-28 18:23:33'),
(25, 37, 'Exercices CEJM 19_03 (1).pdf', 'uploads/1774718638_Exercices CEJM 19_03 (1).pdf', 75783, '2026-03-28 18:23:58'),
(26, 38, 'Exercices CEJM 19_03.pdf', 'uploads/1774718670_Exercices CEJM 19_03.pdf', 75783, '2026-03-28 18:24:30');

-- --------------------------------------------------------

--
-- Structure de la table `fiches`
--

DROP TABLE IF EXISTS `fiches`;
CREATE TABLE IF NOT EXISTS `fiches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contenu` text COLLATE utf8mb4_general_ci,
  `livre_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fiche_livre` (`livre_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
CREATE TABLE IF NOT EXISTS `groupe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `date_creation` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions_session`
--

DROP TABLE IF EXISTS `inscriptions_session`;
CREATE TABLE IF NOT EXISTS `inscriptions_session` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_inscription` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `livre_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_utilisateurs` (`utilisateur_id`(250)),
  KEY `fk_livres` (`livre_id`(250))
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inscriptions_session`
--

INSERT INTO `inscriptions_session` (`id`, `utilisateur_id`, `date_inscription`, `livre_id`) VALUES
(36, 'Diakite', '2026-03-28 18:03:00', 'la femme de ménage'),
(37, 'Fatou', '2026-03-28 18:03:00', 'lakestone tome 2');

-- --------------------------------------------------------

--
-- Structure de la table `lecture`
--

DROP TABLE IF EXISTS `lecture`;
CREATE TABLE IF NOT EXISTS `lecture` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `livre_id` int DEFAULT NULL,
  `statut` enum('en cours','terminé','abandonné') COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lecture_user` (`utilisateur_id`),
  KEY `fk_lecture_livre` (`livre_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lecture`
--

INSERT INTO `lecture` (`id`, `utilisateur_id`, `livre_id`, `statut`) VALUES
(32, 1, 11, 'terminé'),
(33, 1, 12, 'en cours'),
(34, 1, 13, 'en cours'),
(35, 1, 14, 'en cours'),
(36, 1, 15, 'en cours'),
(37, 1, 16, 'en cours'),
(38, 1, 17, 'en cours'),
(39, 1, 18, 'en cours');

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

DROP TABLE IF EXISTS `livres`;
CREATE TABLE IF NOT EXISTS `livres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `genre` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nb_pages` int DEFAULT NULL,
  `nb_exemplaires` int DEFAULT '1',
  `auteur_id` int DEFAULT NULL,
  `couverture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_livre_auteur` (`auteur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id`, `titre`, `genre`, `nb_pages`, `nb_exemplaires`, `auteur_id`, `couverture`) VALUES
(11, 'La femme de ménage ', 'Drame ', 200, 1, 4, 'uploads/1774710426_LF.jpg'),
(12, 'Lakestone Tome 2', 'Dakromance', 200, 1, 5, 'uploads/1774710468_L.jpg'),
(13, 'Les impatientes', '', 200, 5, 6, 'uploads/1774710829_LI.jpg'),
(14, 'Le boyfriend', 'Drame ', 200, 5, 7, 'uploads/1774710894_B.jpg'),
(15, 'Ce que le jour doit à la nuit ', 'Roman', 200, 5, 8, 'uploads/1774710986_YK.jpg'),
(16, 'Une si longue lettre ', 'Roman', 200, 5, 9, 'uploads/1774711364_UL.jpg'),
(17, 'Valentina', 'Dakromance', 200, 1, 10, 'uploads/1774711400_V.jpg'),
(18, 'Au coeur du Sahel', 'Roman', 200, 5, 11, 'uploads/1774711458_AS.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `progression`
--

DROP TABLE IF EXISTS `progression`;
CREATE TABLE IF NOT EXISTS `progression` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lecture_id` int DEFAULT NULL,
  `page_actuelle` int DEFAULT NULL,
  `date_maj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pourcentage` int DEFAULT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_progression_lecture` (`lecture_id`),
  KEY `fk_progression_utilisateur` (`utilisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `progression`
--

INSERT INTO `progression` (`id`, `lecture_id`, `page_actuelle`, `date_maj`, `pourcentage`, `utilisateur_id`) VALUES
(46, 32, 6, '2026-03-28 17:21:24', 3, 10),
(47, 33, 6, '2026-03-28 17:22:00', 3, 10),
(48, 35, 10, '2026-03-28 17:22:54', 5, 10),
(49, 36, 10, '2026-03-28 17:23:25', 5, 10),
(50, 37, 10, '2026-03-28 17:23:50', 5, 10),
(51, 38, 10, '2026-03-28 17:24:18', 5, 10),
(52, 39, 10, '2026-03-28 17:24:55', 5, 10);

-- --------------------------------------------------------

--
-- Structure de la table `sessions_lecture`
--

DROP TABLE IF EXISTS `sessions_lecture`;
CREATE TABLE IF NOT EXISTS `sessions_lecture` (
  `id` int NOT NULL AUTO_INCREMENT,
  `livre_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_heure` datetime NOT NULL,
  `lieu_ou_lien` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `moderateur_id` int DEFAULT NULL,
  `genre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `livre_id` (`livre_id`(250)),
  KEY `moderateur_id` (`moderateur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions_lecture`
--

INSERT INTO `sessions_lecture` (`id`, `livre_id`, `date_heure`, `lieu_ou_lien`, `description`, `moderateur_id`, `genre`) VALUES
(37, 'Lakestone Tome2', '2026-04-28 18:54:00', 'Biblothèque Franconville', 'On se reunis pour faire parler du nouveau livre et faire son débrifingue .', NULL, 'darkromance'),
(36, 'Le boyfriend', '2026-04-05 20:53:00', 'Biblothéque Ermont', 'On se reunis pour faire parler du nouveau livre et faire son débrifingue .', NULL, 'Drame'),
(38, 'la femme de ménage ', '2026-05-28 18:55:00', 'Biblothéque Ermont', 'On se reunis pour faire parler du nouveau livre et faire son débrifingue .', NULL, 'Drame'),
(39, 'les impatientes', '2026-06-28 18:58:00', 'Biblothéque Ermont', 'On se reunis pour faire parler du nouveau livre et faire son débrifingue .', NULL, 'Roman');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','modérateur','membre') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `telephone`, `mot_de_passe`, `role`) VALUES
(1, 'admin', '', 'admin@example.com', NULL, '$2y$10$fLcvRAwpyjZFVvGuNVWppeAsc5lQWdcAc23ofSWnnY9zpr4SpKwJG', 'modérateur'),
(2, 'moderateur', '', 'moderateur@example.com', NULL, '$2y$10$x0CIG8DYnbj85tT8nwmNre/PFtJbv8.S/xCytrbJrHJtDPbTAMFY6', 'modérateur'),
(3, 'membre', '', 'membre@example.com', NULL, '$2y$10$3oOKf2HKRXjdW.bI2LseTu4YzcOH3aK6t6UOlt4awSNi63vMj4ZwK', 'membre'),
(8, 'fatou', '', 'fdiakite38@gmail.com', NULL, '$2y$10$WV4IsdVdnuvHA6fi/yg4KePq6peMtjtzfE2mtoiQbEz0fvPVEohD6', 'membre'),
(9, 'fd', '', 'test@gmail.com', NULL, '$2y$10$S1RtaZ5cHn72JU7.ZhdyvuhtX.P3a179h6aMAbLDGKRmODbxO1uYW', 'modérateur'),
(10, 'fd', '', 'admin@gmail.com', NULL, '$2y$10$S3Gtj9I35MGzp.J6q1I.P.gLX4omv/MsNv7vOvJ83Dy0k8nuBXrgq', 'admin');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `fk_avis_lecture` FOREIGN KEY (`lecture_id`) REFERENCES `lecture` (`id`),
  ADD CONSTRAINT `fk_avis_user` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_documents_lecture` FOREIGN KEY (`lecture_id`) REFERENCES `lecture` (`id`);

--
-- Contraintes pour la table `fiches`
--
ALTER TABLE `fiches`
  ADD CONSTRAINT `fk_fiche_livre` FOREIGN KEY (`livre_id`) REFERENCES `livres` (`id`);

--
-- Contraintes pour la table `lecture`
--
ALTER TABLE `lecture`
  ADD CONSTRAINT `fk_lecture_livre` FOREIGN KEY (`livre_id`) REFERENCES `livres` (`id`),
  ADD CONSTRAINT `fk_lecture_user` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `livres`
--
ALTER TABLE `livres`
  ADD CONSTRAINT `fk_livre_auteur` FOREIGN KEY (`auteur_id`) REFERENCES `auteurs` (`id`);

--
-- Contraintes pour la table `progression`
--
ALTER TABLE `progression`
  ADD CONSTRAINT `fk_progression_lecture` FOREIGN KEY (`lecture_id`) REFERENCES `lecture` (`id`),
  ADD CONSTRAINT `fk_progression_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
