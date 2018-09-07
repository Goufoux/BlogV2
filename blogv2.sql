-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 07 sep. 2018 à 17:52
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `typeHistory` varchar(15) NOT NULL,
  `idHistory` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `userhistory`
--

INSERT INTO `userhistory` (`id`, `idUtilisateur`, `typeHistory`, `idHistory`) VALUES
(27, 2, 'user', 1);

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `pseudo`, `pass`, `email`, `dti`, `accessLevel`, `keyEmail`, `categoryUser`) VALUES
(1, 'Quentin', '$2y$10$sw.uV6ApPvhiUWLiR9dtXe0MW7jn92EO/n9V5krVo/HynFq62wPu2', 'deadworldcorp@gmail.com', 1535621232, 0, 'verified', 2),
(2, 'Tony', '$2y$10$iSdhwBUQt9z54nqxhh2hP.Ch4ftFr4gqR4CrWYuWAj5UFEwi8pH4i', 'genarkys.company@gmail.com', 1536329736, 0, 'verified', 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
