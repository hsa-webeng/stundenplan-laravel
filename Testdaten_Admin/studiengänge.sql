
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `studiengänge` (`id`, `stdg_kürzel`, `stdg_name`, `created_at`, `updated_at`) VALUES
(1, 'MUK', 'Multimedia und Kommunikation', NULL, NULL),
(2, 'VIS', 'Visualisierung und Interaktion in\r\ndigitalen Medien', NULL, NULL),
(3, 'RJO', 'Ressortjournalismus', NULL, NULL),
(4, 'PMF', 'Produktionsmanagement Film und TV', NULL, NULL);

COMMIT;
