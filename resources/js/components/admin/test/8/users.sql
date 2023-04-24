-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2021 at 02:22 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pickbetx`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int(191) DEFAULT NULL,
  `active` int(191) NOT NULL DEFAULT 1,
  `cash` decimal(11,3) NOT NULL DEFAULT 0.000,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page` int(191) DEFAULT NULL,
  `group_id` int(191) DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `role`, `active`, `cash`, `email`, `page`, `group_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Alexies', 'Alexies', 3, 1, '500.002', 'Alex@me.com', 25, 10, NULL, '$2y$10$wUP/w./O.yQTFlpijMcXBeHOKz/eUSjT56qaFb3FYVX.1W0D4AkmO', NULL, '2021-01-04 17:01:16', '2021-02-06 19:03:53'),
(2, 'Admin2', 'Admin2', 1, 1, '0.000', 'axlelaxorx@gmail.com', 3, 10, NULL, '$2y$10$PimaNbHMerfO5mp1.LDui.vFD8WeNCPta39uU8Ip18OgxAQiWPMRC', NULL, '2021-01-04 17:25:18', '2021-02-06 19:03:45'),
(3, 'teller1', 'teller1', 0, 1, '62012.000', 'alexplayer3@alexplayer3', NULL, 10, NULL, '$2y$10$gCrbjYiiTLMBe.lE1gRoEuLtLmYMm0tbO7CEqwtzsQWxsYoHc6dJi', NULL, '2021-01-04 17:27:48', '2021-02-07 11:54:39'),
(4, 'admin1', 'admin1', 1, 1, '0.000', 'admin1@me.com', NULL, 10, NULL, '$2y$10$PimaNbHMerfO5mp1.LDui.vFD8WeNCPta39uU8Ip18OgxAQiWPMRC', NULL, '2021-01-12 14:44:20', '2021-02-07 12:00:32'),
(5, 'Ara Nicole', 'arax', 0, 1, '14316.000', 'ara@me.com', NULL, 10, NULL, '$2y$10$1Bg7Z.UCwcZgbermtqIgbuXn.B/6MQ/WpAgUTNmBYzbhx4RdQDZp2', NULL, '2021-01-20 23:45:27', '2021-02-06 19:02:53'),
(6, 'teller2', 'teller2', 0, 1, '6500.000', 'Cashier2@Cashier2.com', NULL, 10, NULL, '$2y$10$MkW7JwJYPnd70MuFtZNQ5ONOrOmINt33N8Ikoo9cEgX8G/6MYqLri', NULL, '2021-01-21 20:23:23', '2021-02-06 19:02:39'),
(7, 'Cashier1', 'cashier1', 4, 1, '386541.508', 'Eri@me.comx', NULL, 10, NULL, '$2y$10$8kPllnWSPfuOuFvISC3uTuwtcSdFLdvHaYXwbKwd9sbksGVfiDoMa', NULL, '2021-01-23 23:59:12', '2021-02-06 19:32:10'),
(8, 'Eros Jhonex', 'eros', 3, 1, '210028.007', 'eros@me.com', NULL, 12, NULL, '$2y$10$pUxQgk3gDFfHbgvjWqu2puBRXWJNN0nDf6w26cX8K0QfXcVk4.w5O', NULL, '2021-01-24 00:07:43', '2021-02-07 11:37:57'),
(9, 'Fiax Testing', 'Fiax', 0, 1, '0.000', 'fiax@me.com', NULL, 11, NULL, '$2y$10$Lry5Mhoyut6HZLwipNmm1.TKMGt12/g4r8NvqabBHdQDj03uqoUBO', NULL, '2021-01-24 14:06:22', '2021-02-06 19:02:22'),
(10, 'Group 2', 'sef', 3, 1, '0.000', 'sef@awddwa.com', NULL, 11, NULL, '$2y$10$bVhxNVhQUxrpn2TPFNOS7Oro.J9zOeKF7WffvEtM3ensczwkfhn0S', NULL, '2021-02-03 23:20:49', '2021-02-06 19:02:17'),
(11, 'Levixxxxalexx', 'levi', 4, 1, '0.000', 'levi@me.com', NULL, 12, NULL, '$2y$10$XQVbBRA.sMOlrG5daO./0uvsV2G1ZV.4AZi7SGHGAXK.mspNwx9Ba', NULL, '2021-02-04 19:46:31', '2021-02-06 18:57:36'),
(12, 'Alexander', 'alexander', 5, 1, '0.000', 'alex@meme', NULL, 10, NULL, '$2y$10$5mlmudHTR3shfttOTLTh.uKyvoaSfAvfZP6DjblPspzo7T9P0nWLK', NULL, '2021-02-06 19:27:24', '2021-02-06 19:27:24'),
(13, 'Alexandra', 'alexandra', 5, 1, '0.000', 'alexa@meme', NULL, 10, NULL, '$2y$10$olKQ70w0hAw7Q8AKDDtp1eBnNM4sEw4wR0ob5ouXWNl942L9tgWb.', NULL, '2021-02-06 19:29:20', '2021-02-06 19:29:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
