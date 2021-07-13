-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 14 juin 2021 à 17:28
-- Version du serveur :  8.0.21
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lapassiolapasion`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `name`) VALUES
(1, 'peinture'),
(2, 'grafiti'),
(3, 'collage'),
(4, 'sculpture'),
(10, 'tag'),
(11, 'fresque'),
(12, 'pochoir'),
(13, 'mosaïque');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_users` int NOT NULL,
  `id_street` int NOT NULL,
  `date_ad` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`id_users`),
  KEY `street_id` (`id_street`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `text`, `id_users`, `id_street`, `date_ad`) VALUES
(1, 'Quelle sont les noms des chats, gros minet, Félix, hello Kitty, Chat du Cheshire, ...', 2, 3, '2021-01-27 11:42:53'),
(2, 'À quel personnage célèbre, elle vous fait penser ?', 2, 23, '2021-01-27 20:50:18'),
(3, 'Face au théâtre de guignol et de la maison du soleil', 2, 28, '2021-02-09 09:16:10'),
(4, 'la fameuse croix rousse', 2, 29, '2021-02-13 08:47:11'),
(5, 'dans 52 Passage Saint-Marc', 2, 37, '2021-02-16 16:12:43'),
(6, 'Dans le parc zénith', 2, 41, '2021-02-16 17:04:17');

-- --------------------------------------------------------

--
-- Structure de la table `droit`
--

DROP TABLE IF EXISTS `droit`;
CREATE TABLE IF NOT EXISTS `droit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valid` int NOT NULL,
  `statut` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `droit`
--

INSERT INTO `droit` (`id`, `name`, `valid`, `statut`) VALUES
(1, 'visiteur', 5, 3),
(2, 'menbre', 2, 2),
(5, 'modérateur', 1, 2),
(9, 'admin', -100, -10);

-- --------------------------------------------------------

--
-- Structure de la table `etat`
--

DROP TABLE IF EXISTS `etat`;
CREATE TABLE IF NOT EXISTS `etat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `statut` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etat`
--

INSERT INTO `etat` (`id`, `statut`) VALUES
(1, 'effacé '),
(2, 'visible'),
(3, 'en réalisation');

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

DROP TABLE IF EXISTS `statut`;
CREATE TABLE IF NOT EXISTS `statut` (
  `id` int NOT NULL AUTO_INCREMENT,
  `statut` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `statut`
--

INSERT INTO `statut` (`id`, `statut`) VALUES
(1, 'archive'),
(2, 'effacée '),
(3, 'visible'),
(4, 'en réalisation');

-- --------------------------------------------------------

--
-- Structure de la table `street`
--

DROP TABLE IF EXISTS `street`;
CREATE TABLE IF NOT EXISTS `street` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `dateCreation` date DEFAULT NULL,
  `dateFiche` datetime NOT NULL,
  `valid` int DEFAULT NULL,
  `statut` int NOT NULL,
  `id_user` int NOT NULL,
  `categorie` int NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_street` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `street`
--

INSERT INTO `street` (`id`, `name`, `adresse`, `photo`, `description`, `dateCreation`, `dateFiche`, `valid`, `statut`, `id_user`, `categorie`, `latitude`, `longitude`) VALUES
(78, 'la gamelle', '4 Rue Camille 69003 Lyon', '5ff86f97b561f.jpeg', 'fresque peinte pour le restaurant la gamelle qui fait l\'angle de la rue, par un artiste du quartier', NULL, '2021-01-08 15:43:36', 7, 3, 5, 1, 45.7539, 4.88946),
(85, '', 'Rue des Antonins 69005 Lyon', '5ffdbdab119ad.jpeg', 'en haut du mur', NULL, '2021-01-12 16:18:03', -5, 3, 5, 3, 45.7615, 4.82659),
(86, 'palais grillet', '24 Rue Palais Grillet 69002 Lyon', '5ffdbe59f124c.jpeg', 'mosaïque a l\'angle de la rue  ', NULL, '2021-01-12 16:20:58', 1, 3, 1, 12, 45.7623, 4.83523),
(87, '', 'Rue de la Gerbe 69002 Lyon', '5ffdbedc2b9c6.jpeg', '', NULL, '2021-01-12 16:23:08', 1, 3, 4, 3, 45.7644, 4.83527),
(88, '', 'Rue Palais Grillet 69002 Lyon', '5ffdc01357f50.jpeg', '', NULL, '2021-01-12 16:28:19', 11, 2, 4, 3, 45.7711, 4.83566),
(89, '', 'Passage Mermet 69001 Lyon', '5ffdc71eea655.jpeg', 'artiste wenc\r\n', NULL, '2021-01-12 16:58:23', 7, 3, 3, 1, 45.7701, 4.83401),
(90, '', 'Rue Donnée 69001 Lyon', '600143b492488.jpeg', '', NULL, '2021-01-15 08:26:44', 1, 2, 1, 1, 45.7697, 4.83436),
(91, '', '53 Rue Jaboulay 69007 Lyon', '60014abe3a7d1.jpeg', '', NULL, '2021-01-15 08:56:46', 6, 3, 1, 11, 45.7469, 4.84184),
(92, '', 'Rue Lamartine 69003 Lyon', '60014c9131446.jpeg', '', NULL, '2021-01-15 09:04:33', 6, 2, 5, 11, 45.7492, 4.88053),
(93, '', 'Allée de Fontenay 69007 Lyon', '60014d80a233d.jpeg', '', NULL, '2021-01-15 09:08:33', 1, 3, 5, 1, 45.7317, 4.83203),
(94, '', '14 Rue Nungesser et Coli 69008 Lyon', '60014f1c9a2c7.jpeg', '', NULL, '2021-01-15 09:15:25', 1, 3, 5, 11, 45.741, 4.87925),
(95, '', '58 Route de Lyon 69680 Chassieu', '60e4801997142.jpeg', '', NULL, '2021-07-06 16:08:58', 2, 1, 2, 1, 45.7387, 4.96369);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `droit` int NOT NULL,
  `created_at` datetime NOT NULL,
  `dateVisi` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `password`, `droit`, `created_at`, `dateVisi`) VALUES
(1, 'anonyme', '', '4546556', 1, '2020-12-25 19:04:51', NULL),
(2, 'admin', 'admin@gmail.com', '$2y$10$gKnuxxSZxFJQPPEwC3PYxOp8SMfIuWL/kTToMXQ3rr3logqB02ORe', 9, '2021-01-07 22:06:33', '2021-06-08 14:54:28'),

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `street`
--
ALTER TABLE `street`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
