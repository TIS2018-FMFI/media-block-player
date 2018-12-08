-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Hostiteľ: localhost
-- Vygenerované: So 08.Dec 2018, 21:20
-- Verzia serveru: 5.6.13
-- Verzia PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáza: `mbp`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `mbp_translations`
--

CREATE TABLE IF NOT EXISTS `mbp_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `trans_link` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `lecture_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=3 ;

--
-- Sťahujem dáta pre tabuľku `mbp_translations`
--

INSERT INTO `mbp_translations` (`id`, `name`, `trans_link`, `lecture_id`, `language_id`) VALUES
(1, 'medved1_EN', 'Data/Translations/medved1_EN.txt', 1, 13),
(2, 'medved1_SK', 'Data/Translations/medved1_SK.txt', 1, 45);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
