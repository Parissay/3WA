-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 22 Septembre 2016 à 12:11
-- Version du serveur: 5.5.50-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `blog`
--
CREATE DATABASE IF NOT EXISTS `blog` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `blog`;

-- --------------------------------------------------------

--
-- Structure de la table `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `a_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `a_name` varchar(50) NOT NULL,
  `a_surname` varchar(50) NOT NULL,
  PRIMARY KEY (`a_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `authors`
--

INSERT INTO `authors` (`a_id`, `a_name`, `a_surname`) VALUES
(1, 'John', 'Doe'),
(2, 'Pierre', 'Dupont');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(40) NOT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `cat_name` (`cat_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`) VALUES
(1, 'Nouvelles'),
(2, 'Récits'),
(3, 'Poésies');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `com_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `com_nickname` varchar(30) DEFAULT NULL,
  `com_content` text NOT NULL,
  `com_creation_date` datetime NOT NULL,
  `com_post_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`com_id`),
  KEY `com_creation_date` (`com_creation_date`),
  KEY `com_post_id` (`com_post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `p_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `p_title` varchar(100) NOT NULL,
  `p_content` text NOT NULL,
  `p_creation_date` datetime NOT NULL,
  `p_author_id` tinyint(3) unsigned DEFAULT NULL,
  `p_category_id` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`p_id`),
  UNIQUE KEY `p_title` (`p_title`),
  KEY `p_creation_date` (`p_creation_date`),
  KEY `p_author_id` (`p_author_id`),
  KEY `p_category_id` (`p_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `Comment_ibfk_1` FOREIGN KEY (`com_post_id`) REFERENCES `posts` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `Post_ibfk_1` FOREIGN KEY (`p_author_id`) REFERENCES `authors` (`a_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `Post_ibfk_2` FOREIGN KEY (`p_category_id`) REFERENCES `categories` (`cat_id`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
