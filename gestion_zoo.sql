-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2023 at 05:41 PM
-- Server version: 5.5.10
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gestion_zoo`
--

-- --------------------------------------------------------

--
-- Table structure for table `animaux`
--

CREATE TABLE IF NOT EXISTS `animaux` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `race_id` int(11) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `sexe` enum('M','F') DEFAULT NULL,
  `pseudo` varchar(50) NOT NULL,
  `commentaire` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `race_id` (`race_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `animaux`
--

INSERT INTO `animaux` (`id`, `race_id`, `date_naissance`, `sexe`, `pseudo`, `commentaire`) VALUES
(3, 1, '2023-04-06', 'M', 'test', 'Kozhutta Singatiuhsiudhhoda Pondatti'),
(4, 1, '2023-04-13', 'M', 'L1', 'guyghg'),
(6, 4, '2023-04-07', 'F', 'Chat1', 'isfuhciudsbv');

-- --------------------------------------------------------

--
-- Table structure for table `especes`
--

CREATE TABLE IF NOT EXISTS `especes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `race` varchar(50) NOT NULL,
  `nourriture` enum('Carnivore','Herbivore','Omnivore') NOT NULL,
  `duree_vie` int(11) NOT NULL,
  `aquatique` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `especes`
--

INSERT INTO `especes` (`id`, `race`, `nourriture`, `duree_vie`, `aquatique`) VALUES
(1, 'Lion', 'Carnivore', 5, 0),
(2, 'Tigre', 'Carnivore', 6, 0),
(3, 'Dauphin', 'Omnivore', 5, 0),
(4, 'chat', 'Herbivore', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `loc_animaux`
--

CREATE TABLE IF NOT EXISTS `loc_animaux` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `animal_id` int(11) NOT NULL,
  `date_arrivee` date DEFAULT NULL,
  `date_sortie` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `animal_id` (`animal_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `loc_animaux`
--

INSERT INTO `loc_animaux` (`id`, `animal_id`, `date_arrivee`, `date_sortie`) VALUES
(3, 4, '2023-04-20', '2023-04-28');

-- --------------------------------------------------------

--
-- Table structure for table `personnels`
--

CREATE TABLE IF NOT EXISTS `personnels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `sexe` enum('H','F') NOT NULL,
  `login` varchar(50) NOT NULL,
  `mdp` varchar(50) NOT NULL,
  `fonction` enum('DIRECTEUR','EMPLOYE') NOT NULL,
  `salaire` decimal(7,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `personnels`
--

INSERT INTO `personnels` (`id`, `nom`, `prenom`, `date_naissance`, `sexe`, `login`, `mdp`, `fonction`, `salaire`) VALUES
(2, 'bat', 'bat', '1999-08-31', 'H', 'batz', 'test123', 'DIRECTEUR', 2000.00),
(4, 'Cadirvele', 'Prasanth', '2004-11-29', 'H', 'Pra123', 'Pra123', 'DIRECTEUR', 10000.00),
(5, 'HISAO', 'HADRIAN', '2023-04-02', 'H', 'Hadri1', 'Hadri1', 'EMPLOYE', 5000.00);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `animaux`
--
ALTER TABLE `animaux`
  ADD CONSTRAINT `animaux_ibfk_1` FOREIGN KEY (`race_id`) REFERENCES `especes` (`id`);

--
-- Constraints for table `loc_animaux`
--
ALTER TABLE `loc_animaux`
  ADD CONSTRAINT `loc_animaux_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `animaux` (`id`);
