
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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

COMMIT;
