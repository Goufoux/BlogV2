-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 05 sep. 2018 à 10:26
-- Version du serveur :  5.7.21
-- Version de PHP :  7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `blogv2`
--

-- --------------------------------------------------------

--
-- Structure de la table `billet`
--

DROP TABLE IF EXISTS `billet`;
CREATE TABLE IF NOT EXISTS `billet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(25) NOT NULL,
  `contenu` text NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `datePub` int(11) NOT NULL,
  `dateMod` int(11) NOT NULL,
  `idBook` int(11) NOT NULL,
  `nbLike` text NOT NULL,
  `nbVue` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `billet`
--

INSERT INTO `billet` (`id`, `titre`, `contenu`, `idUtilisateur`, `datePub`, `dateMod`, `idBook`, `nbLike`, `nbVue`) VALUES
(1, 'test', '<p>test de billet</p>', 1, 1535875516, 0, 1, 'a:1:{i:0;s:1:\"1\";}', 12);

-- --------------------------------------------------------

--
-- Structure de la table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `datePub` int(11) NOT NULL,
  `dateMod` int(11) NOT NULL,
  `content` text NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `categorie` text NOT NULL,
  `nbVue` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `name`, `datePub`, `dateMod`, `content`, `idUtilisateur`, `categorie`, `nbVue`) VALUES
(1, 'zzzzz', 1535633223, 0, 'zzzzzzzzzzzzzzzzzzz', 1, 's:10:\"Espionnage\";', 43),
(2, 'SuperMan', 1536139784, 0, 'L\'aventure de SuperMan !', 1, 's:8:\"Aventure\";', 8);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUtilisateur` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `datePub` int(11) NOT NULL,
  `idBillet` int(11) NOT NULL,
  `report` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `userhistory`
--

DROP TABLE IF EXISTS `userhistory`;
CREATE TABLE IF NOT EXISTS `userhistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUtilisateur` int(11) NOT NULL,
  `history` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `userhistory`
--

INSERT INTO `userhistory` (`id`, `idUtilisateur`, `history`) VALUES
(1, 1, 'a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(25) NOT NULL,
  `pass` varchar(60) NOT NULL,
  `email` varchar(70) NOT NULL,
  `dti` int(11) NOT NULL,
  `accessLevel` int(11) NOT NULL,
  `keyEmail` varchar(20) NOT NULL,
  `categoryUser` int(11) NOT NULL,
  `followBook` text NOT NULL,
  `followUser` text NOT NULL,
  `history` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `pseudo`, `pass`, `email`, `dti`, `accessLevel`, `keyEmail`, `categoryUser`, `followBook`, `followUser`, `history`) VALUES
(1, 'Quentin', '$2y$10$sw.uV6ApPvhiUWLiR9dtXe0MW7jn92EO/n9V5krVo/HynFq62wPu2', 'deadworldcorp@gmail.com', 1535621232, 0, 'verified', 2, 'a:1:{i:0;s:1:\"1\";}', 'a:0:{}', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
