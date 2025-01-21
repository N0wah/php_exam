-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 21 jan. 2025 à 22:22
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
-- Base de données : `php_exam`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` int NOT NULL,
  `publish_date` datetime NOT NULL,
  `id_author` int NOT NULL,
  `img_link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `name`, `description`, `price`, `publish_date`, `id_author`, `img_link`) VALUES
(16, 'Pantalon Cargo', 'Pantalon pratique avec poches latérales, souvent ample et en coton.', 99, '2025-01-21 22:18:53', 6, 'uploads/pantalon-cargo-jogger-treillis-478078.webp'),
(17, 'Ensemble Complet ', 'Un super ensemble vestimentaire formel, composé d\'une veste e', 200, '2025-01-21 22:19:32', 6, 'uploads/istockphoto-468828120-612x612.jpg'),
(18, 'Basket ', 'Bbb Basket ', 100, '2025-01-21 22:19:52', 6, 'uploads/capture-d-cran-2023-12-28-11.00.43.png'),
(19, 'Basket ', 'encore', 100, '2025-01-21 22:20:01', 6, 'uploads/vegas.jpg'),
(20, 'Slip', 'super slip pour dormir', 5000, '2025-01-21 22:20:20', 6, 'uploads/163789192194a3cf8419cae25e888eb1ef631976df_thumbnail_720x.jpg'),
(21, 'Haut Roulé', 'Col', 200, '2025-01-21 22:20:54', 6, 'uploads/LAS-318D-E-599-101-COL-5---12316-gris_6.webp'),
(22, 'Bel homme', 'A tot farie', 5000, '2025-01-21 22:21:28', 6, 'uploads/homme-affaires-dans-costume-fond-transparent-blanc_457222-4093.avif');

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `article_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
CREATE TABLE IF NOT EXISTS `invoice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `transaction_date` date NOT NULL,
  `amount` int NOT NULL,
  `invoice_address` varchar(255) NOT NULL,
  `invoice_city` varchar(255) NOT NULL,
  `invoice_postal` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `article_amount` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mail_adress` varchar(255) NOT NULL,
  `solde` int NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `mail_adress`, `solde`, `profile_picture`, `role`) VALUES
(6, 'Alongkorn', '$2y$10$O6OeU6wvvJqm31NXOAOcxO9hVBWsmMAwd/pnxmsbc6CLfBT/ainfu', 'Along@along.com', 0, 'src/img/Default_Profile_Picture.png', 'none');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
