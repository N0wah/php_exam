-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 20 jan. 2025 à 08:21
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `name`, `description`, `price`, `publish_date`, `id_author`, `img_link`) VALUES
(1, 'Along', 'Vends un Along bon travailleur', 130, '2025-01-14 09:06:34', 1, 'uploads/image.png');

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

--
-- Déchargement des données de la table `invoice`
--

INSERT INTO `invoice` (`id`, `user_id`, `transaction_date`, `amount`, `invoice_address`, `invoice_city`, `invoice_postal`) VALUES
(1, 2, '2025-01-14', 1000, 'Je sais pas', 'Cacasur mer', '06326');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `mail_adress`, `solde`, `profile_picture`, `role`) VALUES
(1, 'Caca', '$2y$10$/anIfgCNwQe1a6tEFQtvfeXXW.zKnsuueE8NCJaUT4EXCV3/372/m', 'caca@gmail.com', 0, 'src/img/Default_Profile_Picture.png', 'none'),
(2, 'Abbachio', '$2y$10$WLbIZudVlvbllDLke6DSwOpyUnVrvigUb.F6cjxjQQj0xD5qyYNhq', 'leone@gmail.com', 400, 'uploads/tumblr_inline_pqkys0GrEn1rvg9kl_1280.jpg', 'none'),
(3, 'Admin', '$2y$10$gCmYuWAempvuI2LxKqROpua0vRYVfP4rY31mPMO475LCfeFk/Fp62', 'admin@admin.com', 0, 'src/img/Default_Profile_Picture.png', 'admin'),
(4, 'adrien', '$2y$10$lDuLOEfgwb1UvlIBtz305.FoRKWF0RyoOCi7QiP63iAiADv/wbZRS', 'adrienleroux@gmail.com', 0, 'src/img/Default_Profile_Picture.png', 'none');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
