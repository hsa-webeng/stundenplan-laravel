
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `users` (`id`, `admin`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 0, 'H-Roderus', 'helmut.roderus@hs-ansbach.de', NULL, '12345678', NULL, NULL, NULL),
(4, 0, 'P-Walliczek', 'philipp.walliczek@hs-ansbach.de', NULL, '87654321', NULL, NULL, NULL);

COMMIT;
