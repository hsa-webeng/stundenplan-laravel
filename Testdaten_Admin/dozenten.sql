
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


INSERT INTO `dozenten` (`id`, `dozent_vorname`, `dozent_nachname`, `plan_abgegeben`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 'Helmut', 'Roderus', NULL, 3, NULL, NULL),
(3, 'Joschi', 'Kuphal', NULL, NULL, NULL, NULL),
(4, 'Phillipp', 'Walliczek', NULL, 4, NULL, NULL),
(5, 'Isabell', 'Schlecht', NULL, NULL, NULL, NULL),
(6, 'Passant', 'Refaat', NULL, NULL, NULL, NULL),
(7, 'Svenja', 'Weiß', NULL, NULL, NULL, NULL),
(8, 'Sabrina', 'Zegenhagen', NULL, NULL, NULL, NULL);

COMMIT;
