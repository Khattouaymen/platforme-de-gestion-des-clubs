-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 21 mai 2025 à 16:12
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_clubs`
--

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

DROP TABLE IF EXISTS `activite`;
CREATE TABLE IF NOT EXISTS `activite` (
  `activite_id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `description` text,
  `date_activite` date NOT NULL,
  `lieu` varchar(100) DEFAULT NULL,
  `club_id` int DEFAULT NULL,
  `responsable_notifie` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`activite_id`),
  KEY `fk_activite_club` (`club_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `activite`
--

INSERT INTO `activite` (`activite_id`, `titre`, `description`, `date_activite`, `lieu`, `club_id`, `responsable_notifie`) VALUES
(1, 'test', 'test', '2025-05-22', 'salle 1', 1, 1),
(2, 'Capture The Flag (CTF)', 'Nous souhaitons organiser un événement de type Capture The Flag (CTF) destiné aux passionnés de cybersécurité. L\'objectif est de permettre aux participants de tester et d\'améliorer leurs compétences en matière de sécurité informatique à travers une série de défis pratiques. Les épreuves couvriront divers domaines tels que la cryptographie, l\'exploitation de vulnérabilités, l\'analyse de réseaux et le forensic.', '2025-05-21', 'salle 1', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

DROP TABLE IF EXISTS `administrateur`;
CREATE TABLE IF NOT EXISTS `administrateur` (
  `id` int NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`id`, `prenom`, `nom`, `email`, `password`) VALUES
(0, 'Admin', 'Système', 'admin@example.com', '$2y$10$Ij9KDgwu2ethlwbYNlitSOwej8HXAWefJwrM5zujAM7huUbIuCKhq');

-- --------------------------------------------------------

--
-- Structure de la table `blog`
--

DROP TABLE IF EXISTS `blog`;
CREATE TABLE IF NOT EXISTS `blog` (
  `blog_id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(150) NOT NULL,
  `contenu` text NOT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `club_id` int DEFAULT NULL,
  PRIMARY KEY (`blog_id`),
  KEY `fk_blog_club` (`club_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `club`
--

DROP TABLE IF EXISTS `club`;
CREATE TABLE IF NOT EXISTS `club` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text,
  `nombre_membres` int NOT NULL,
  `Logo_URL` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `club`
--

INSERT INTO `club` (`id`, `nom`, `description`, `nombre_membres`, `Logo_URL`) VALUES
(1, 'CyberDune', 'CyberDune est un club &eacute;tudiant orient&eacute; vers le num&eacute;rique et les technologies. Il a pour objectif de d&eacute;velopper les comp&eacute;tences des &eacute;tudiants en programmation, cybers&eacute;curit&eacute;, intelligence artificielle et technologies modernes &agrave; travers des ateliers, des comp&eacute;titions et des activit&eacute;s &eacute;ducatives.', 1, 'https://ik.imagekit.io/aymen/logo_cyberdune.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `demandeactivite`
--

DROP TABLE IF EXISTS `demandeactivite`;
CREATE TABLE IF NOT EXISTS `demandeactivite` (
  `id_demande_act` int NOT NULL AUTO_INCREMENT,
  `nom_activite` varchar(100) DEFAULT NULL,
  `description` text,
  `date_activite` date DEFAULT NULL,
  `nombre_max` int DEFAULT NULL,
  `lieu` varchar(50) DEFAULT NULL,
  `club_id` int DEFAULT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `statut` enum('en_attente','approuvee','rejetee') NOT NULL DEFAULT 'en_attente',
  `date_creation` datetime DEFAULT NULL,
  PRIMARY KEY (`id_demande_act`),
  KEY `fk_demandeactivite_club` (`club_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `demandeactivite`
--

INSERT INTO `demandeactivite` (`id_demande_act`, `nom_activite`, `description`, `date_activite`, `nombre_max`, `lieu`, `club_id`, `date_debut`, `date_fin`, `statut`, `date_creation`) VALUES
(1, 'Capture The Flag (CTF)', 'Nous souhaitons organiser un événement de type Capture The Flag (CTF) destiné aux passionnés de cybersécurité. L\'objectif est de permettre aux participants de tester et d\'améliorer leurs compétences en matière de sécurité informatique à travers une série de défis pratiques. Les épreuves couvriront divers domaines tels que la cryptographie, l\'exploitation de vulnérabilités, l\'analyse de réseaux et le forensic.', NULL, NULL, 'salle 1', 1, '2025-05-21 23:15:00', '2025-05-21 03:15:00', 'approuvee', '2025-05-20 22:15:47'),
(3, 'test', 'test', NULL, NULL, 'salle 1', 1, '2025-05-22 01:30:00', '2025-05-23 01:30:00', 'approuvee', '2025-05-21 00:30:56');

-- --------------------------------------------------------

--
-- Structure de la table `demandeadhesion`
--

DROP TABLE IF EXISTS `demandeadhesion`;
CREATE TABLE IF NOT EXISTS `demandeadhesion` (
  `demande_adh_id` int NOT NULL AUTO_INCREMENT,
  `etudiant_id` int NOT NULL,
  `club_id` int NOT NULL,
  `date_demande` date DEFAULT NULL,
  `statut` enum('en_attente','acceptee','refusee') DEFAULT 'en_attente',
  `motivation` text,
  `date_traitement` date DEFAULT NULL,
  PRIMARY KEY (`demande_adh_id`),
  UNIQUE KEY `etudiant_id` (`etudiant_id`,`club_id`),
  KEY `fk_demandeadh_club` (`club_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `demandeadhesion`
--

INSERT INTO `demandeadhesion` (`demande_adh_id`, `etudiant_id`, `club_id`, `date_demande`, `statut`, `motivation`, `date_traitement`) VALUES
(6, 1, 1, '2025-05-21', 'acceptee', 'je suis motiver interessez par cybersecurity et les ctf', '2025-05-21');

-- --------------------------------------------------------

--
-- Structure de la table `demandeapprobationclub`
--

DROP TABLE IF EXISTS `demandeapprobationclub`;
CREATE TABLE IF NOT EXISTS `demandeapprobationclub` (
  `id_demande` int NOT NULL AUTO_INCREMENT,
  `nom_club` varchar(100) NOT NULL,
  `description` text,
  `Logo_URL` varchar(255) DEFAULT NULL,
  `statut` enum('en_attente','approuve','rejete') DEFAULT 'en_attente',
  `id_etudiant` int DEFAULT NULL,
  PRIMARY KEY (`id_demande`),
  KEY `fk_demandeapprob_etudiant` (`id_etudiant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `id_etudiant` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `filiere` varchar(255) DEFAULT NULL,
  `niveau` varchar(255) DEFAULT NULL,
  `numero_etudiant` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_etudiant`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`id_etudiant`, `nom`, `prenom`, `email`, `password`, `filiere`, `niveau`, `numero_etudiant`) VALUES
(1, 'khattou', 'Aymen', 'khattouaymen@gmail.com', '$2y$10$A50nb3HXRBcHTZ1R3oBsQOLdwTL.RiOsZJSacU1fRyqWMohKqzN1y', 'genie info', '2eme annee', '220704'),
(2, 'issaad', 'badr', 'badr@example.com', '$2y$10$XYXvrXJ3CstyBEkiC4fYPuBJf6gBvm29jxKe.yDW3wNAklKtDpYxC', NULL, NULL, NULL),
(3, 'abid', 'selma', 'selma@gmail.com', '$2y$10$7g.gm.If2l2nQLlvKEqgg.dw9lbtSrFp7z9zjpRPNvxs0Tc9UcXbW', NULL, NULL, NULL),
(4, 'respo', 'test', 'respo@test.com', '$2y$10$6CY99KAR7LBCgcRnGbHHlefmV5poeCtdtpjStPSUb3djNQ.FkLwf6', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `inscription_token`
--

DROP TABLE IF EXISTS `inscription_token`;
CREATE TABLE IF NOT EXISTS `inscription_token` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `date_creation` datetime NOT NULL,
  `date_utilisation` datetime DEFAULT NULL,
  `etudiant_id` int DEFAULT NULL,
  `est_utilise` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `inscription_token`
--

INSERT INTO `inscription_token` (`id`, `token`, `type`, `date_creation`, `date_utilisation`, `etudiant_id`, `est_utilise`) VALUES
(1, '208a688c7c028578991487005a4035338925c6f3bf23a835325bafc2d20aae09', 'responsable', '2025-05-16 19:09:11', NULL, NULL, 1),
(2, '77ed7f6d8f9224c0fe5b6669ebc8a92d2679bd2502ac2f9b09c2b49e3287891b', 'responsable', '2025-05-16 19:09:32', NULL, NULL, 1),
(3, '7acd92c0451b72880bae68f4fc0ad0412f7c49cc3ca8b8f99daebf5e549ad914', 'responsable', '2025-05-16 19:09:33', NULL, NULL, 1),
(4, '3cc7a76f41e07847ac408c31d1a9f581c507f1abfeb71d2c6bb5ad4bd52da06f', 'responsable', '2025-05-16 19:09:36', NULL, NULL, 1),
(5, '059ac6b2545925b78ead0090236febf99d8a0fb44fcd1842497252d51b354a4b', 'responsable', '2025-05-16 19:09:37', NULL, NULL, 1),
(6, '8368f54023636d93343f3a6c813d82c08f795be622bf99928c0655479bd32d78', 'responsable', '2025-05-16 19:09:38', NULL, NULL, 1),
(7, '714e82dc7ff4ccd70f507d8ee607be88359afdecb62150a94dcd17fb5a3ccef7', 'responsable', '2025-05-16 19:10:22', NULL, NULL, 1),
(8, '83f835368e4fd45df7c8e7c46602b438ed30c2131c2f60cab96331547538d2a8', 'responsable', '2025-05-16 19:10:28', NULL, NULL, 1),
(9, '0f657c09cadec5130327f43cad529a8742e593636e928d2f6d7149efc405b8a7', 'responsable', '2025-05-16 19:13:19', NULL, NULL, 1),
(10, '4385ae9891ad88acc9c5dada8130e6e9d252606ddd088831a1d8768e15f57e95', 'responsable', '2025-05-16 19:15:30', NULL, NULL, 1),
(11, '602b0cc3379fe9caa0f9e462f9f1ca2b9195c2b60bf0f1775b0fa61a41ae98a4', 'responsable', '2025-05-16 19:15:33', NULL, NULL, 1),
(12, '3fa2e457cd9226c49857ee21ede39709758a5760ed9df377cce354be9552ec5c', 'responsable', '2025-05-16 19:23:10', NULL, NULL, 1),
(13, '5ae79c42a106f862547bfef9a69804edc850f46b67b6abe4d433ffd40772c1d6', 'responsable', '2025-05-16 19:24:27', NULL, NULL, 1),
(14, 'ba7858123725fce87824438bab07fa9a32007c60cd74681e3ccf08b668404e66', 'responsable', '2025-05-16 19:54:57', NULL, NULL, 1),
(15, '763957caaf31d92c57de585e9bd360c392acd11dc0a80decc5fba7b2611ba08d', 'responsable', '2025-05-16 19:55:07', NULL, NULL, 1),
(16, 'c0e82e1786cd799d60fc03e6615a174e4e14e5a60e8311374755c5d6d0dfb13f', 'responsable', '2025-05-16 19:58:05', NULL, NULL, 1),
(17, '056fdb1b1ab830e72a3a3bc6b6b5c34e6e8f304552f286383e08273a1ffd42e5', 'responsable', '2025-05-16 19:58:06', NULL, NULL, 1),
(18, '7723a777c3497b8ad72f0b28397f44cd7f8261a5b04d4fe05554ac2805ec79de', 'responsable', '2025-05-16 19:58:07', NULL, NULL, 1),
(19, '3495779bb4d6bf98c1e01a04c9154a6a5741ee91379d217b88a7a2de0d4a1c95', 'responsable', '2025-05-16 20:00:03', NULL, NULL, 1),
(20, '554a5341b0829bc21abaef4064be82afb9994a417989e35701137ff575ffbd0b', 'responsable', '2025-05-16 20:09:51', '2025-05-16 20:10:46', NULL, 1),
(21, '26b278e76219b5bf465d4d75f8e749cbd755ef9fe863ee21c860271b45bac6d2', 'responsable', '2025-05-16 20:13:26', NULL, NULL, 1),
(22, '85d026e328494244a74d8bcef0f5accc51144ff867a778d590eccbb61f843e7c', 'responsable', '2025-05-17 23:56:26', '2025-05-17 23:57:01', NULL, 1),
(23, '378d903bfd311ccc96805cf09f368e36929f915e55ee15906686a7a78d034fbd', 'responsable', '2025-05-18 00:10:49', NULL, NULL, 1),
(24, '08e74de7e889f50282bdc1878682ef69c05dce53665f518c0535a222e2840e94', 'responsable', '2025-05-18 00:10:55', '2025-05-18 00:12:14', 4, 1),
(25, '38ec3cf2d5d105c65af17d371dc338f516ad981bbc83167c286edbf65fb9f663', 'responsable', '2025-05-18 21:41:47', NULL, NULL, 0),
(26, '57f6dd468f72df94628e9516752e8453c74cf03c571e5404b9f92e5d3ca307c1', 'responsable', '2025-05-21 03:05:25', NULL, NULL, 0),
(27, '68cf0de5b1d15c89690d4da394d80924135cb2bda796eedbd4282edc129fca8c', 'responsable', '2025-05-21 03:07:20', NULL, NULL, 0),
(28, '0e370fe615683404e0af17b11426fc9e2def62d3456762c2b5fd5a01eb85f93a', 'responsable', '2025-05-21 03:07:23', NULL, NULL, 0),
(29, '57455f53e19253868609eea11fe5e342b4aa09398bb97873d9e7cc275fbf69ae', 'responsable', '2025-05-21 03:07:48', NULL, NULL, 0),
(30, '520eb5d81f303b6c3575fa9b11253aba5cc7d823d7df2a4d8ce0bb451ad2b5d9', 'responsable', '2025-05-21 03:08:20', NULL, NULL, 0),
(31, '205e86a828890bf87fc2b2c6ac7522f1afb5feff082fe7355427984ed0fc0150', 'responsable', '2025-05-21 03:10:07', NULL, NULL, 0),
(32, 'f670e1bb3a6f2caa700aeb666f46be7e5a4561158f55ae36f6afa03d57cd4ae1', 'responsable', '2025-05-21 03:10:42', NULL, NULL, 0),
(33, '2b3d5c9b29e18a650bef82d72e89bcd208353f265c93ef5826f095b7da231e11', 'responsable', '2025-05-21 03:11:07', NULL, NULL, 0),
(34, 'fa91ad781ccde73171e5b916c3a6e9cd8d45759d11f6c1ed0ac6155d1f07e5e9', 'responsable', '2025-05-21 03:11:57', NULL, NULL, 0),
(35, 'd12e82bb907b7394d85270df673ba74739f41977d95225ac8585f24058187857', 'responsable', '2025-05-21 05:22:34', NULL, NULL, 0),
(36, '8b14a603b987a09b5ce847c5797b9619e74ca2af3049f105b76ee51fdd2a5057', 'responsable', '2025-05-21 16:25:08', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `membreclub`
--

DROP TABLE IF EXISTS `membreclub`;
CREATE TABLE IF NOT EXISTS `membreclub` (
  `id_membre` int NOT NULL AUTO_INCREMENT,
  `id_etudiant` int DEFAULT NULL,
  `club_id` int DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_membre`),
  UNIQUE KEY `id_etudiant` (`id_etudiant`),
  KEY `fk_membre_club` (`club_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `membreclub`
--

INSERT INTO `membreclub` (`id_membre`, `id_etudiant`, `club_id`, `role`) VALUES
(1, 1, 1, 'membre');

-- --------------------------------------------------------

--
-- Structure de la table `participationactivite`
--

DROP TABLE IF EXISTS `participationactivite`;
CREATE TABLE IF NOT EXISTS `participationactivite` (
  `membre_id` int NOT NULL,
  `activite_id` int NOT NULL,
  `statut` enum('inscrit','absent','participe') DEFAULT 'inscrit',
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`membre_id`,`activite_id`),
  KEY `fk_participation_activite` (`activite_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `id_reservation` int NOT NULL AUTO_INCREMENT,
  `ressource_id` int NOT NULL,
  `club_id` int NOT NULL,
  `activite_id` int NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `statut` enum('en_attente','approuvee','rejetee') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'en_attente',
  `description` text COLLATE utf8mb4_general_ci,
  `date_reservation` datetime NOT NULL,
  PRIMARY KEY (`id_reservation`),
  KEY `ressource_id` (`ressource_id`),
  KEY `club_id` (`club_id`),
  KEY `activite_id` (`activite_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id_reservation`, `ressource_id`, `club_id`, `activite_id`, `date_debut`, `date_fin`, `statut`, `description`, `date_reservation`) VALUES
(1, 1, 1, 1, '2025-05-22 03:33:00', '2025-05-23 03:33:00', 'approuvee', 'testtetstetstetsetstetstetstestetstest', '2025-05-21 02:35:48');

-- --------------------------------------------------------

--
-- Structure de la table `responsableclub`
--

DROP TABLE IF EXISTS `responsableclub`;
CREATE TABLE IF NOT EXISTS `responsableclub` (
  `id_responsable` int NOT NULL,
  `id_etudiant` int DEFAULT NULL,
  `club_id` int DEFAULT NULL,
  PRIMARY KEY (`id_responsable`),
  UNIQUE KEY `id_etudiant` (`id_etudiant`),
  KEY `fk_responsable_club` (`club_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `responsableclub`
--

INSERT INTO `responsableclub` (`id_responsable`, `id_etudiant`, `club_id`) VALUES
(0, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `ressource`
--

DROP TABLE IF EXISTS `ressource`;
CREATE TABLE IF NOT EXISTS `ressource` (
  `id_ressource` int NOT NULL AUTO_INCREMENT,
  `nom_ressource` varchar(100) NOT NULL,
  `type_ressource` enum('materiel','humain','financier','autre') DEFAULT 'autre',
  `quantite` int DEFAULT '1',
  `club_id` int DEFAULT NULL,
  `disponibilite` enum('disponible','indisponible') DEFAULT 'disponible',
  PRIMARY KEY (`id_ressource`),
  KEY `fk_ressource_club` (`club_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ressource`
--

INSERT INTO `ressource` (`id_ressource`, `nom_ressource`, `type_ressource`, `quantite`, `club_id`, `disponibilite`) VALUES
(1, 'mic', 'materiel', 1, NULL, 'disponible');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activite`
--
ALTER TABLE `activite`
  ADD CONSTRAINT `fk_activite_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`);

--
-- Contraintes pour la table `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `fk_blog_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`);

--
-- Contraintes pour la table `demandeactivite`
--
ALTER TABLE `demandeactivite`
  ADD CONSTRAINT `fk_demandeactivite_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`);

--
-- Contraintes pour la table `demandeadhesion`
--
ALTER TABLE `demandeadhesion`
  ADD CONSTRAINT `fk_demandeadh_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`),
  ADD CONSTRAINT `fk_demandeadh_etudiant` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiant` (`id_etudiant`);

--
-- Contraintes pour la table `demandeapprobationclub`
--
ALTER TABLE `demandeapprobationclub`
  ADD CONSTRAINT `fk_demandeapprob_etudiant` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id_etudiant`);

--
-- Contraintes pour la table `membreclub`
--
ALTER TABLE `membreclub`
  ADD CONSTRAINT `fk_membre_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`),
  ADD CONSTRAINT `fk_membre_etudiant` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id_etudiant`);

--
-- Contraintes pour la table `participationactivite`
--
ALTER TABLE `participationactivite`
  ADD CONSTRAINT `fk_participation_activite` FOREIGN KEY (`activite_id`) REFERENCES `activite` (`activite_id`),
  ADD CONSTRAINT `fk_participation_membre` FOREIGN KEY (`membre_id`) REFERENCES `membreclub` (`id_membre`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`ressource_id`) REFERENCES `ressource` (`id_ressource`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_3` FOREIGN KEY (`activite_id`) REFERENCES `activite` (`activite_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `responsableclub`
--
ALTER TABLE `responsableclub`
  ADD CONSTRAINT `fk_responsable_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`),
  ADD CONSTRAINT `fk_responsable_etudiant` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id_etudiant`);

--
-- Contraintes pour la table `ressource`
--
ALTER TABLE `ressource`
  ADD CONSTRAINT `fk_ressource_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
