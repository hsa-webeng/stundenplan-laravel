-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mariadb:3306
-- Erstellungszeit: 30. Jul 2024 um 19:10
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
-- Tabellenstruktur für Tabelle `dozenten`
--

CREATE TABLE `dozenten` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dozent_vorname` varchar(255) NOT NULL,
  `dozent_nachname` varchar(255) NOT NULL,
  `plan_abgegeben` tinyint(1) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `dozenten`
--

INSERT INTO `dozenten` (`id`, `dozent_vorname`, `dozent_nachname`, `plan_abgegeben`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Helmut', 'Roderus', NULL, 3, NULL, NULL),
(2, 'Joschi', 'Kuphal', NULL, NULL, NULL, NULL),
(3, 'Phillipp', 'Walliczek', NULL, 4, NULL, NULL),
(4, 'Isabell', 'Schlecht', NULL, NULL, NULL, NULL),
(5, 'Passant', 'Refaat', NULL, NULL, NULL, NULL),
(6, 'Svenja', 'Weiß', NULL, NULL, NULL, NULL),
(7, 'Sabrina', 'Zegenhagen', NULL, NULL, NULL, NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `dozenten`
--
ALTER TABLE `dozenten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dozenten_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `dozenten`
--
ALTER TABLE `dozenten`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `dozenten`
--
ALTER TABLE `dozenten`
  ADD CONSTRAINT `dozenten_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
