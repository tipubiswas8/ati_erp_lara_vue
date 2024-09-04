-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 04, 2024 at 11:45 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(16, 'App\\Models\\User', 1, 'Access Token', 'ad28d5c2daf74db2af035174eb36d08f8153192ceed06ee4eae3809f841ee6df', '[\"*\"]', '2024-09-04 03:16:48', '2024-09-04 03:16:36', '2024-09-04 03:16:48'),
(15, 'App\\Models\\User', 1, 'Access Token', 'f1f67f72bc7747e5024c2f606a2dd90e9fddade4da94012e45dbb76fe1d9a199', '[\"*\"]', '2024-09-02 05:58:03', '2024-09-02 03:32:00', '2024-09-02 05:58:03'),
(14, 'App\\Models\\User', 2, 'Access Token', '01efd246be35a6f52caeaa61c0d376dfbf866b29dc5a931c1e4cd50b793b31ca', '[\"*\"]', '2024-09-04 05:38:04', '2024-09-02 00:28:12', '2024-09-04 05:38:04'),
(13, 'App\\Models\\User', 1, 'Access Token', '218650fbfab51089a8158dafca378a3767b3c28813cea806ac787fbdfb033dd9', '[\"*\"]', '2024-09-04 05:35:37', '2024-09-01 23:37:00', '2024-09-04 05:35:37'),
(12, 'App\\Models\\User', 1, 'Access Token', 'be6a799ec65836c0d9057b7e0417c23b03b133ce6f22f1601d66ad6274a2507a', '[\"*\"]', '2024-09-01 23:18:33', '2024-09-01 22:33:25', '2024-09-01 23:18:33'),
(11, 'App\\Models\\User', 1, 'Access Token', 'fc893ca0754f8d5d8f4e8f8f035a9237d8fbd94894d6760eb119384f518ad324', '[\"*\"]', '2024-09-02 00:27:15', '2024-09-01 22:31:14', '2024-09-02 00:27:15'),
(10, 'App\\Models\\User', 1, 'Access Token', 'ca9b1e9f552c668355863b2f288377acbec5d69b192896078fd1d2f0bf262d96', '[\"*\"]', '2024-09-01 22:30:14', '2024-09-01 22:30:02', '2024-09-01 22:30:14'),
(9, 'App\\Models\\User', 1, 'Access Token', '3e8a6cda1dc9d0380060fc11ab7241b4b237a6256713d3d13c245602c994bf8c', '[\"*\"]', '2024-09-01 21:44:25', '2024-09-01 07:29:16', '2024-09-01 21:44:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` int(20) DEFAULT NULL,
  `address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `phone`, `address`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Sohel4', 'sohel4@gmail.com', NULL, 199999999, 'Dhaka', '$2y$10$8gtOyHgE/.mwdZPhM8Rk/.2bmH4T.I0bVat/k/5/LY0Jlenbynzve', 1, NULL, '2024-08-29 05:23:33', '2024-09-04 04:31:59'),
(2, 'Sohel1', 'sohel1@gmail.com', NULL, 123456, 'Dhaka1000', '$2y$10$Y5DrZxuINJxGI9.gHVe6vOmr5YToqJBZ09ZZ7lDuKMySZBMGiNot2', 1, NULL, '2024-09-02 00:27:15', '2024-09-04 04:44:32'),
(3, 'Sohel223', 'hj@gmail.com', NULL, 123456111, 'gfg555', '$2y$10$tBtQsz2fke9d7fEz4pUIw.4sZeVp2h0rsod2f4kn.i54q5UBmSDcq', 0, NULL, '2024-09-02 00:29:34', '2024-09-04 04:13:18'),
(4, 'Sohel1', 'sohel2@gmail.com', NULL, 123456, 'gfg', '$2y$10$p9MZiPZQ1jJ.rElUj6Shlu.L2IGF6KqrU/nmMvF6Ce0a0AReJ75RW', 0, NULL, '2024-09-02 00:34:33', '2024-09-04 04:13:02'),
(5, 'Sohel1', 'hj2@gmail.com', NULL, 123456, 'gfg', '$2y$10$BES63W7Jd91tPLXKfI5UV.qLI7UwaU70.wglvKDB5boRfHtM80bba', 0, NULL, '2024-09-02 02:43:52', '2024-09-04 03:50:39'),
(9, 'Sohel2111', 'hj11222@gmail.com', NULL, 333333, 'Khulna', '$2y$10$4h6dSyT/qztAcydrKhIbUul6CYz0BY1JMR.F3QCU5qXZEnw2TCWvW', 1, NULL, '2024-09-02 02:53:52', '2024-09-04 03:50:43'),
(13, 'Tipu Biswas22', 'tipu22@gmail.com', NULL, 22222222, 'gfg', '$2y$10$sXLXE4M2nat9UIWylgjjBerQXC5sFd0tspm1RWUFIGlMHYx/avvR.', 0, NULL, '2024-09-02 23:05:53', '2024-09-04 03:50:48');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
