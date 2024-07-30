-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mariadb:3306
-- Erstellungszeit: 30. Jul 2024 um 19:04
-- Server-Version: 11.4.2-MariaDB-ubu2404
-- PHP-Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `stundenplaner-db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurse`
--

CREATE TABLE `kurse` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kurs_name` varchar(255) NOT NULL,
  `doz_id` bigint(20) UNSIGNED NOT NULL,
  `stdg_id` bigint(20) UNSIGNED NOT NULL,
  `semester` int(11) NOT NULL,
  `sws` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `kurse`
--

INSERT INTO `kurse` (`id`, `kurs_name`, `doz_id`, `stdg_id`, `semester`, `sws`, `created_at`, `updated_at`) VALUES
(9, 'Client-Server-Programmierung', 1, 1, 5, 4, NULL, NULL),
(10, 'Web-Engineering', 1, 1, 6, 4, NULL, NULL),
(11, 'Praxisprojekt', 2, 1, 6, 4, NULL, NULL),
(12, 'Entwicklung mobiler Applikationen', 1, 1, 5, 4, NULL, NULL),
(13, 'Mediendesign Foto', 3, 1, 5, 4, NULL, NULL),
(14, 'Mediendesign Art', 3, 1, 6, 4, NULL, NULL),
(15, 'Design Interaktiv', 4, 1, 6, 4, NULL, NULL),
(16, 'e Publishing', 4, 1, 5, 4, NULL, NULL),
(17, 'Projekt Crossmedia', 5, 3, 4, 6, NULL, NULL),
(18, 'Projekt Management', 6, 3, 6, 4, NULL, NULL),
(19, 'Projekt Next Media', 7, 3, 6, 4, NULL, NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `kurse`
--
ALTER TABLE `kurse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kurse_doz_id_foreign` (`doz_id`),
  ADD KEY `kurse_stdg_id_foreign` (`stdg_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `kurse`
--
ALTER TABLE `kurse`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `kurse`
--
ALTER TABLE `kurse`
  ADD CONSTRAINT `kurse_doz_id_foreign` FOREIGN KEY (`doz_id`) REFERENCES `dozenten` (`id`),
  ADD CONSTRAINT `kurse_stdg_id_foreign` FOREIGN KEY (`stdg_id`) REFERENCES `studiengänge` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
