
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


INSERT INTO `dozenten` (`id`, `dozent_vorname`, `dozent_nachname`, `plan_abgegeben`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Helmut', 'Roderus', NULL, 3, NULL, NULL),
(2, 'Joschi', 'Kuphal', NULL, NULL, NULL, NULL),
(3, 'Phillipp', 'Walliczek', NULL, 4, NULL, NULL),
(4, 'Isabell', 'Schlecht', NULL, NULL, NULL, NULL),
(5, 'Passant', 'Refaat', NULL, NULL, NULL, NULL),
(6, 'Svenja', 'Weiß', NULL, NULL, NULL, NULL),
(7, 'Sabrina', 'Zegenhagen', NULL, NULL, NULL, NULL);

COMMIT;
