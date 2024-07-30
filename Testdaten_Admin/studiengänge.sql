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
-- Tabellenstruktur für Tabelle `studiengänge`
--

CREATE TABLE `studiengänge` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stdg_kürzel` varchar(255) NOT NULL,
  `stdg_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `studiengänge`
--

INSERT INTO `studiengänge` (`id`, `stdg_kürzel`, `stdg_name`, `created_at`, `updated_at`) VALUES
(1, 'MUK', 'Multimedia und Kommunikation', NULL, NULL),
(2, 'VIS', 'Visualisierung und Interaktion in\r\ndigitalen Medien', NULL, NULL),
(3, 'RJO', 'Ressortjournalismus', NULL, NULL),
(4, 'PMF', 'Produktionsmanagement Film und TV', NULL, NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `studiengänge`
--
ALTER TABLE `studiengänge`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `studiengänge`
--
ALTER TABLE `studiengänge`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
