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
-- Štruktúra tabuľky pre tabuľku `mbp_lectures`
--

CREATE TABLE IF NOT EXISTS `mbp_lectures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `description` varchar(500) COLLATE utf8_slovak_ci NOT NULL,
  `difficulty` int(11) NOT NULL,
  `audio_link` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `text_link` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `sync_file_link` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=2 ;

--
-- Sťahujem dáta pre tabuľku `mbp_lectures`
--

INSERT INTO `mbp_lectures` (`id`, `name`, `description`, `difficulty`, `audio_link`, `text_link`, `sync_file_link`, `user_id`, `language_id`) VALUES
(1, 'Medved1', 'Medvedasdasdasfasfsaf', 1, 'Data/A-V Sources/medved1.wav', 'Data/Scripts/medved1.txt', 'Data/Sync files/medved1.jkr', 1, 42);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
