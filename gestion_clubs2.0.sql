-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 14, 2025 at 09:39 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestion_clubs`
--

-- --------------------------------------------------------

--
-- Table structure for table `activite`
--

DROP TABLE IF EXISTS `activite`;
CREATE TABLE IF NOT EXISTS `activite` (
  `activite_id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `description` text,
  `date_activite` date NOT NULL,
  `lieu` varchar(100) DEFAULT NULL,
  `club_id` int DEFAULT NULL,
  PRIMARY KEY (`activite_id`),
  KEY `fk_activite_club` (`club_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `administrateur`
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

-- --------------------------------------------------------

--
-- Table structure for table `blog`
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
-- Table structure for table `club`
--

DROP TABLE IF EXISTS `club`;
CREATE TABLE IF NOT EXISTS `club` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text,
  `nombre_membres` int NOT NULL,
  `Logo_URL` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `demandeactivite`
--

DROP TABLE IF EXISTS `demandeactivite`;
CREATE TABLE IF NOT EXISTS `demandeactivite` (
  `id_demande_act` int NOT NULL AUTO_INCREMENT,
  `nom_activite` varchar(100) NOT NULL,
  `description` text,
  `date_activite` date DEFAULT NULL,
  `nombre_max` int DEFAULT NULL,
  `lieu` varchar(50) DEFAULT NULL,
  `club_id` int DEFAULT NULL,
  PRIMARY KEY (`id_demande_act`),
  KEY `fk_demandeactivite_club` (`club_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `demandeadhesion`
--

DROP TABLE IF EXISTS `demandeadhesion`;
CREATE TABLE IF NOT EXISTS `demandeadhesion` (
  `demande_adh_id` int NOT NULL AUTO_INCREMENT,
  `etudiant_id` int NOT NULL,
  `club_id` int NOT NULL,
  `date_demande` date DEFAULT NULL,
  `statut` enum('en_attente','acceptee','refusee') DEFAULT 'en_attente',
  PRIMARY KEY (`demande_adh_id`),
  UNIQUE KEY `etudiant_id` (`etudiant_id`,`club_id`),
  KEY `fk_demandeadh_club` (`club_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `demandeapprobationclub`
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
-- Table structure for table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `id_etudiant` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_etudiant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `membreclub`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participationactivite`
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
-- Table structure for table `responsableclub`
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

-- --------------------------------------------------------

--
-- Table structure for table `ressource`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activite`
--
ALTER TABLE `activite`
  ADD CONSTRAINT `fk_activite_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`);

--
-- Constraints for table `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `fk_blog_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`);

--
-- Constraints for table `demandeactivite`
--
ALTER TABLE `demandeactivite`
  ADD CONSTRAINT `fk_demandeactivite_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`);

--
-- Constraints for table `demandeadhesion`
--
ALTER TABLE `demandeadhesion`
  ADD CONSTRAINT `fk_demandeadh_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`),
  ADD CONSTRAINT `fk_demandeadh_etudiant` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiant` (`id_etudiant`);

--
-- Constraints for table `demandeapprobationclub`
--
ALTER TABLE `demandeapprobationclub`
  ADD CONSTRAINT `fk_demandeapprob_etudiant` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id_etudiant`);

--
-- Constraints for table `membreclub`
--
ALTER TABLE `membreclub`
  ADD CONSTRAINT `fk_membre_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`),
  ADD CONSTRAINT `fk_membre_etudiant` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id_etudiant`);

--
-- Constraints for table `participationactivite`
--
ALTER TABLE `participationactivite`
  ADD CONSTRAINT `fk_participation_activite` FOREIGN KEY (`activite_id`) REFERENCES `activite` (`activite_id`),
  ADD CONSTRAINT `fk_participation_membre` FOREIGN KEY (`membre_id`) REFERENCES `membreclub` (`id_membre`);

--
-- Constraints for table `responsableclub`
--
ALTER TABLE `responsableclub`
  ADD CONSTRAINT `fk_responsable_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`),
  ADD CONSTRAINT `fk_responsable_etudiant` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id_etudiant`);

--
-- Constraints for table `ressource`
--
ALTER TABLE `ressource`
  ADD CONSTRAINT `fk_ressource_club` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
