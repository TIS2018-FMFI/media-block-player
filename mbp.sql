-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1:3312
-- Čas generovania: Po 28.Jan 2019, 20:23
-- Verzia serveru: 10.1.28-MariaDB-1~xenial
-- Verzia PHP: 7.2.10-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `hrebenarm_mbp`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `mbp_languages`
--

CREATE TABLE `mbp_languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_slovak_ci NOT NULL,
  `abbr` varchar(10) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

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
(57, 'Welsh', 'cy'),
(58, 'Others', 'NaN');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `mbp_lectures`
--

CREATE TABLE `mbp_lectures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `description` varchar(500) COLLATE utf8_slovak_ci NOT NULL,
  `difficulty` int(11) NOT NULL,
  `audio_link` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `text_link` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `sync_file_link` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `download_count` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `mbp_translations`
--

CREATE TABLE `mbp_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `trans_link` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `lecture_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `mbp_users`
--

CREATE TABLE `mbp_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_slovak_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_slovak_ci NOT NULL,
  `admin` tinyint(2) NOT NULL DEFAULT '0',
  `email` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_slovak_ci DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8_slovak_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_slovak_ci DEFAULT NULL,
  `gender` varchar(5) COLLATE utf8_slovak_ci DEFAULT NULL,
  `age` tinyint(4) DEFAULT NULL,
  `native_lang_id` int(11) DEFAULT '13'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `mbp_users`
--

INSERT INTO `mbp_users` (`id`, `username`, `password`, `admin`, `email`, `image`, `first_name`, `last_name`, `gender`, `age`, `native_lang_id`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'admin@admin.com', NULL, 'System', 'Admin', 'M', 21, 35);

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `mbp_languages`
--
ALTER TABLE `mbp_languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexy pre tabuľku `mbp_lectures`
--
ALTER TABLE `mbp_lectures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexy pre tabuľku `mbp_translations`
--
ALTER TABLE `mbp_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexy pre tabuľku `mbp_users`
--
ALTER TABLE `mbp_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `mbp_languages`
--
ALTER TABLE `mbp_languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pre tabuľku `mbp_lectures`
--
ALTER TABLE `mbp_lectures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `mbp_translations`
--
ALTER TABLE `mbp_translations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `mbp_users`
--
ALTER TABLE `mbp_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
