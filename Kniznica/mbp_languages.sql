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
-- Štruktúra tabuľky pre tabuľku `mbp_languages`
--

CREATE TABLE IF NOT EXISTS `mbp_languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_slovak_ci NOT NULL,
  `abbr` varchar(10) COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=58 ;

--
-- Sťahujem dáta pre tabuľku `mbp_languages`
--

INSERT INTO `mbp_languages` (`id`, `name`, `abbr`) VALUES
(1, 'Afrikaans', 'af'),
(2, 'Albanian', 'sq'),
(3, 'Arabic', 'ar'),
(4, 'Armenian', 'hy'),
(5, 'Azerbaijani', 'az'),
(6, 'Belarusian', 'be'),
(7, 'Bulgarian', 'bg'),
(8, 'Chinese', 'zh'),
(9, 'Croatian', 'hr'),
(10, 'Czech', 'cs'),
(11, 'Danish', 'da'),
(12, 'Dutch', 'nl'),
(13, 'English', 'en'),
(14, 'Estonian', 'et'),
(15, 'Finnish', 'fi'),
(16, 'French', 'fr'),
(17, 'German', 'de'),
(18, 'Greek', 'el'),
(19, 'Hebrew', 'he'),
(20, 'Hindi', 'hi'),
(21, 'Hungarian', 'hu'),
(22, 'Indonesian', 'id'),
(23, 'Irish', 'ga'),
(24, 'Icelandic', 'is'),
(25, 'Italian', 'it'),
(26, 'Japanese', 'ja'),
(27, 'Korean', 'ko'),
(28, 'Latin', 'la'),
(29, 'Luxembourgish', 'lb'),
(30, 'Lao', 'lo'),
(31, 'Lithuanian', 'lt'),
(32, 'Latvian', 'lv'),
(33, 'Macedonian', 'mk'),
(34, 'Malayalam', 'ml'),
(35, 'Mongolian', 'mn'),
(36, 'Nepali', 'ne'),
(37, 'Norwegian Nynorsk', 'nn'),
(38, 'Norwegian', 'no'),
(39, 'Polish', 'pl'),
(40, 'Portuguese', 'pt'),
(41, 'Romanian, Moldavian, Moldovan', 'ro'),
(42, 'Russian', 'ru'),
(43, 'Sanskrit (Saṁskṛta)', 'sa'),
(44, 'Serbian', 'sr'),
(45, 'Slovak', 'sk'),
(46, 'Slovene', 'sl'),
(47, 'Somali', 'so'),
(48, 'Spanish', 'es'),
(49, 'Sundanese', 'su'),
(50, 'Swahili', 'sw'),
(51, 'Swedish', 'sv'),
(52, 'Thai', 'th'),
(53, 'Turkmen', 'tk'),
(54, 'Turkish', 'tr'),
(55, 'Ukrainian', 'uk'),
(56, 'Vietnamese', 'vi'),
(57, 'Welsh', 'cy');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
