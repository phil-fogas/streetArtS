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
-- Base de données : `street`
--

-- --------------------------------------------------------
-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(12, 'mosaïque ');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_street` int(11) NOT NULL,
  `date_ad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `text`, `id_users`, `id_street`, `date_ad`) VALUES
(91, 'tous les chats de ma jeunesse', 3, 78, '2021-01-12 17:15:47'),
(92, 'je monte ou je décent', 3, 89, '2021-01-13 11:43:42'),
(93, 'cricket ou cigale ou grillon', 5, 93, '2021-01-15 09:22:45'),
(94, 'dommage qui sois détruit ', 5, 92, '2021-01-15 09:26:45');

-- --------------------------------------------------------

--
-- Structure de la table `droit`
--

CREATE TABLE `droit` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valid` int(11) NOT NULL,
  `statut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `droit`
--

INSERT INTO `droit` (`id`, `name`, `valid`, `statut`) VALUES
(1, 'visiteur', 5, 3),
(2, 'menbre', 2, 2),
(5, 'modérateur', 1, 2),
(9, 'admin', -100, 0);

-- --------------------------------------------------------

--
-- Structure de la table `mail`
--

CREATE TABLE `mail` (
  `id` int(11) NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sujet` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `email`, `sujet`, `message`, `date`) VALUES
(1, 'toto@3wa.fr', '', 'khkkhk', '2020-12-22 21:29:22'),
(6, 'loulou', 'coucou', 'site de ouf', '2021-01-15 11:07:02');

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

CREATE TABLE `statut` (
  `id` int(11) NOT NULL,
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `street` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `dateCreation` date DEFAULT NULL,
  `dateFiche` datetime NOT NULL,
  `valid` int(1) DEFAULT NULL,
  `statut` int(1) NOT NULL,
  `id_user` int(11) NOT NULL,
  `categorie` int(1) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `street`
--

INSERT INTO `street` (`id`, `name`, `adresse`, `photo`, `description`, `dateCreation`, `dateFiche`, `valid`, `statut`, `id_user`, `categorie`, `latitude`, `longitude`) VALUES
(78, 'la gamelle', '4 Rue Camille 69003 Lyon', '5ff86f97b561f.jpeg', 'fresque peinte pour le restaurant la gamelle qui fait l\'angle de la rue, par un artiste du cartier', '2020-11-08', '2021-01-08 15:43:36', 7, 3, 5, 11, 45.7539, 4.88946),
(85, '', 'Rue des Antonins 69005 Lyon', '5ffdbdab119ad.jpeg', 'en haut du mur', NULL, '2021-01-12 16:18:03', -5, 2, 5, 3, 45.7615, 4.82659),
(86, 'palais grillet', '24 Rue Palais Grillet 69002 Lyon', '5ffdbe59f124c.jpeg', 'mosaïque a l\'angle de la rue  ', NULL, '2021-01-12 16:20:58', 1, 3, 1, 12, 45.7623, 4.83523),
(87, '', 'Rue de la Gerbe 69002 Lyon', '5ffdbedc2b9c6.jpeg', '', NULL, '2021-01-12 16:23:08', 1, 3, 4, 3, 45.7644, 4.83527),
(88, '', 'Rue Palais Grillet 69002 Lyon', '5ffdc01357f50.jpeg', '', NULL, '2021-01-12 16:28:19', 11, 2, 4, 3, 45.7711, 4.83566),
(89, '', 'Passage Mermet 69001 Lyon', '5ffdc71eea655.jpeg', 'artiste wenc\r\n', NULL, '2021-01-12 16:58:23', 7, 3, 3, 1, 45.7701, 4.83401),
(90, '', 'Rue Donnée 69001 Lyon', '600143b492488.jpeg', '', NULL, '2021-01-15 08:26:44', 1, 2, 1, 1, 45.7697, 4.83436),
(91, '', '53 Rue Jaboulay 69007 Lyon', '60014abe3a7d1.jpeg', '', NULL, '2021-01-15 08:56:46', 6, 3, 1, 11, 45.7469, 4.84184),
(92, '', 'Rue Lamartine 69003 Lyon', '60014c9131446.jpeg', '', NULL, '2021-01-15 09:04:33', 6, 2, 5, 11, 45.7492, 4.88053),
(93, '', 'Allée de Fontenay 69007 Lyon', '60014d80a233d.jpeg', '', NULL, '2021-01-15 09:08:33', 1, 3, 5, 1, 45.7317, 4.83203),
(94, '', '14 Rue Nungesser et Coli 69008 Lyon', '60014f1c9a2c7.jpeg', '', NULL, '2021-01-15 09:15:25', 1, 3, 5, 11, 45.741, 4.87925);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `droit` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `dateVisi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `password`, `droit`, `created_at`, `dateVisi`) VALUES
(1, 'anonyme', '', '4546556', 0, '2020-12-25 19:04:51', NULL),
(3, 'toto', 'toto@3wa.fr', '$2y$10$IdCy8VZAkPY99zN7qLIS3.WOrdhr/.DeBmRzMNPPnZXL4tHX60Efe', 2, '2020-12-20 19:28:17', '2021-01-14 14:27:02'),
(4, 'tata', 'tata@3wa.fr', '$2y$10$uz1/a92/sF1i7RT81NzZgOmNEokkXp4IdQGZ2UfVNpK1Vyta5l8B.', 5, '2020-12-20 19:50:00', '2021-01-14 14:27:19'),
(5, 'loulou', 'loulou@3wa.fr', '$2y$10$XBy81UBmtPkBDHhOA/wNr.a6n2iNZZb6q4g.OZlvtm0tYxTjA3.zO', 9, '2020-12-20 20:00:34', '2021-01-20 09:25:58');

-- --------------------------------------------------------

--
-- Structure de la table `validation`
--

CREATE TABLE `validation` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_street` int(11) NOT NULL,
  `chose` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `validation`
--

INSERT INTO `validation` (`id`, `id_user`, `id_street`, `chose`) VALUES
(11, 5, 78, 'oui'),
(12, 3, 78, 'oui'),
(13, 4, 88, 'oui'),
(14, 4, 85, 'non'),
(15, 5, 88, 'oui'),
(16, 5, 85, 'non'),
(17, 3, 89, 'oui'),
(18, 5, 91, 'oui');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`id_users`),
  ADD KEY `street_id` (`id_street`);

--
-- Index pour la table `droit`
--
ALTER TABLE `droit`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `statut`
--
ALTER TABLE `statut`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `street`
--
ALTER TABLE `street`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_street` (`id_user`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `validation`
--
ALTER TABLE `validation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `street_id_valid` (`id_street`),
  ADD KEY `user_id_valid` (`id_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT pour la table `droit`
--
ALTER TABLE `droit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `statut`
--
ALTER TABLE `statut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `street`
--
ALTER TABLE `street`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `validation`
--
ALTER TABLE `validation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `street_id` FOREIGN KEY (`id_street`) REFERENCES `street` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `validation`
--
ALTER TABLE `validation`
  ADD CONSTRAINT `street_id_valid` FOREIGN KEY (`id_street`) REFERENCES `street` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_valid` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
