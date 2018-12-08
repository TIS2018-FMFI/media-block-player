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
-- Štruktúra tabuľky pre tabuľku `mbp_users`
--

CREATE TABLE IF NOT EXISTS `mbp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_slovak_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_slovak_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=2 ;

--
-- Sťahujem dáta pre tabuľku `mbp_users`
--

INSERT INTO `mbp_users` (`id`, `username`, `password`, `admin`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
