-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2021 at 02:21 AM
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
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(191) NOT NULL,
  `fightnumber` int(191) NOT NULL,
  `result` varchar(191) NOT NULL,
  `event_id` int(191) NOT NULL,
  `confirm1` int(191) DEFAULT NULL,
  `confirm2` int(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `fightnumber`, `result`, `event_id`, `confirm1`, `confirm2`, `created_at`, `updated_at`) VALUES
(1, 1, 'Meron', 4, 2, 4, '2021-02-07 11:21:57', '2021-02-07 11:21:57'),
(2, 2, 'Meron', 4, 4, 2, '2021-02-07 11:21:59', '2021-02-07 11:21:59'),
(3, 3, 'Meron', 4, 4, 2, '2021-02-07 11:22:02', '2021-02-07 11:22:02'),
(4, 4, 'Meron', 4, 4, 2, '2021-02-07 11:22:05', '2021-02-07 11:22:05'),
(5, 5, 'Wala', 4, 4, 2, '2021-02-07 11:22:07', '2021-02-07 11:22:07'),
(6, 6, 'Wala', 4, 4, 2, '2021-02-07 11:22:09', '2021-02-07 11:22:09'),
(7, 7, 'Cancelled', 4, 4, 2, '2021-02-07 11:22:12', '2021-02-07 11:22:12'),
(8, 8, 'Draw', 4, 4, 2, '2021-02-07 11:22:14', '2021-02-07 11:22:14'),
(9, 9, 'Meron', 4, 4, 2, '2021-02-07 11:22:16', '2021-02-07 11:22:16'),
(10, 10, 'Wala', 4, 4, 2, '2021-02-07 11:22:19', '2021-02-07 11:22:19'),
(11, 11, 'Meron', 4, 4, 2, '2021-02-07 11:22:21', '2021-02-07 11:22:21'),
(12, 12, 'Wala', 4, 4, 2, '2021-02-07 11:22:24', '2021-02-07 11:22:24'),
(13, 13, 'Wala', 4, 4, 2, '2021-02-07 11:22:26', '2021-02-07 11:22:26'),
(14, 14, 'Wala', 4, 4, 2, '2021-02-07 11:22:30', '2021-02-07 11:22:30'),
(15, 15, 'Meron', 4, 4, 2, '2021-02-07 11:22:33', '2021-02-07 11:22:33'),
(16, 16, 'Meron', 4, 4, 2, '2021-02-07 11:22:35', '2021-02-07 11:22:35'),
(17, 17, 'Meron', 4, 4, 2, '2021-02-07 11:22:38', '2021-02-07 11:22:38'),
(18, 18, 'Meron', 4, 4, 2, '2021-02-07 11:22:40', '2021-02-07 11:22:40'),
(19, 19, 'Meron', 4, 4, 2, '2021-02-07 11:22:43', '2021-02-07 11:22:43'),
(20, 20, 'Meron', 4, 4, 2, '2021-02-07 11:22:45', '2021-02-07 11:22:45'),
(21, 21, 'Meron', 4, 4, 2, '2021-02-07 11:36:57', '2021-02-07 11:36:57'),
(22, 22, 'Meron', 4, 2, 4, '2021-02-07 11:37:02', '2021-02-07 11:37:02'),
(23, 23, 'Wala', 4, 4, 2, '2021-02-07 11:37:07', '2021-02-07 11:37:07'),
(24, 24, 'Meron', 4, 4, 2, '2021-02-07 11:37:10', '2021-02-07 11:37:10'),
(25, 25, 'Meron', 4, 4, 2, '2021-02-07 11:37:13', '2021-02-07 11:37:13'),
(26, 26, 'Wala', 4, 4, 2, '2021-02-07 11:37:15', '2021-02-07 11:37:15'),
(27, 27, 'Meron', 4, 4, 2, '2021-02-07 11:37:18', '2021-02-07 11:37:18'),
(28, 28, 'Wala', 4, 4, 2, '2021-02-07 11:37:20', '2021-02-07 11:37:20'),
(29, 29, 'Wala', 4, 4, 2, '2021-02-07 11:37:22', '2021-02-07 11:37:22'),
(30, 30, 'Meron', 4, 4, 2, '2021-02-07 11:37:25', '2021-02-07 11:37:25'),
(31, 31, 'Wala', 4, 4, 2, '2021-02-07 11:37:27', '2021-02-07 11:37:27'),
(32, 32, 'Meron', 4, 4, 2, '2021-02-07 11:37:29', '2021-02-07 11:37:29'),
(33, 33, 'Wala', 4, 4, 2, '2021-02-07 11:37:31', '2021-02-07 11:37:31'),
(34, 34, 'Meron', 4, 4, 2, '2021-02-07 11:37:34', '2021-02-07 11:37:34'),
(35, 35, 'Meron', 4, 4, 2, '2021-02-07 11:37:36', '2021-02-07 11:37:36'),
(36, 36, 'Meron', 4, 4, 2, '2021-02-07 11:37:38', '2021-02-07 11:37:38'),
(37, 37, 'Meron', 4, 4, 2, '2021-02-07 11:37:42', '2021-02-07 11:37:42'),
(38, 38, 'Wala', 4, 4, 2, '2021-02-07 11:37:44', '2021-02-07 11:37:44'),
(39, 39, 'Wala', 4, 4, 2, '2021-02-07 11:37:46', '2021-02-07 11:37:46'),
(40, 40, 'Meron', 4, 4, 2, '2021-02-07 11:37:49', '2021-02-07 11:37:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(191) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
