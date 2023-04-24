-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2021 at 02:20 AM
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
-- Table structure for table `bet`
--

CREATE TABLE `bet` (
  `id` int(191) NOT NULL,
  `barcode` int(191) NOT NULL,
  `bet` varchar(191) DEFAULT NULL,
  `amount` decimal(11,3) NOT NULL,
  `event_id` int(191) DEFAULT NULL,
  `potmoney_id` int(191) DEFAULT NULL,
  `startingfight` int(191) DEFAULT NULL,
  `user_id` int(191) NOT NULL,
  `wins` int(191) DEFAULT NULL,
  `lose` int(191) DEFAULT NULL,
  `winner` int(191) NOT NULL DEFAULT 0 COMMENT '0 = pending\r\n3 = Loss\r\n1 = winner \r\n2 = jackpot',
  `result` decimal(11,3) DEFAULT NULL,
  `claimed` int(191) DEFAULT NULL,
  `turn` int(191) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bet`
--

INSERT INTO `bet` (`id`, `barcode`, `bet`, `amount`, `event_id`, `potmoney_id`, `startingfight`, `user_id`, `wins`, `lose`, `winner`, `result`, `claimed`, `turn`, `created_at`, `updated_at`) VALUES
(1, 683328, 'wwwwMMMMwwMwMwMMMMww', '200.000', 4, 1, 1, 8, 8, 12, 3, NULL, NULL, 20, '2021-02-07 07:35:28', '2021-02-07 11:22:45'),
(2, 683329, 'wwMwMMMMwMwMwMwwwwwM', '200.000', 4, 1, 1, 8, 3, 17, 3, NULL, NULL, 20, '2021-02-07 07:40:03', '2021-02-07 11:22:45'),
(3, 683330, 'MMMMwMMwwMwwMMMMMMMM', '200.000', 4, 1, 1, 8, 12, 8, 3, NULL, NULL, 20, '2021-02-07 08:36:05', '2021-02-07 11:22:45'),
(4, 683331, 'MwwMMMMwMMwwMwMMMMww', '200.000', 4, 1, 1, 8, 9, 11, 3, NULL, NULL, 20, '2021-02-07 08:36:09', '2021-02-07 11:22:45'),
(5, 683332, 'wwwwwMMwMwwwMwMMMwwM', '200.000', 4, 1, 1, 8, 9, 11, 3, NULL, NULL, 20, '2021-02-07 08:44:07', '2021-02-07 11:22:45'),
(6, 683333, 'MwwwwwMMwMMwMwwwwwMw', '200.000', 4, 1, 1, 8, 7, 13, 3, NULL, NULL, 20, '2021-02-07 08:44:14', '2021-02-07 11:22:45'),
(7, 683334, 'wwMMwMwwwMMwwMMwwMwM', '200.000', 4, 1, 1, 8, 9, 11, 3, NULL, NULL, 20, '2021-02-07 08:44:19', '2021-02-07 11:22:45'),
(8, 683335, 'wMMMwwMMwMwMwwMwwwMw', '200.000', 4, 1, 1, 8, 9, 11, 3, NULL, NULL, 20, '2021-02-07 09:18:38', '2021-02-07 11:22:45'),
(9, 683336, 'wwMwMwMwwMMwwMMwwMwM', '200.000', 4, 1, 1, 8, 8, 12, 3, NULL, NULL, 20, '2021-02-07 09:30:33', '2021-02-07 11:22:45'),
(10, 683337, 'MMMMwMMMwMMMwMMwwwwM', '200.000', 4, 1, 1, 8, 9, 11, 3, NULL, NULL, 20, '2021-02-07 10:54:12', '2021-02-07 11:22:45'),
(11, 683338, 'MwMMMMwwwwwwMMwwMMwM', '200.000', 4, 1, 1, 8, 8, 12, 3, NULL, NULL, 20, '2021-02-07 10:54:22', '2021-02-07 11:22:45'),
(12, 683339, 'wMMwMwwwwwMwMMMMMMww', '200.000', 4, 1, 1, 8, 10, 10, 3, NULL, NULL, 20, '2021-02-07 10:54:26', '2021-02-07 11:22:45'),
(13, 683340, 'wwMMMMMwMwMwwMMMMMMM', '200.000', 4, 1, 1, 8, 13, 7, 1, '3230.000', 1, 20, '2021-02-07 10:54:40', '2021-02-07 11:22:50'),
(14, 683341, 'wMwwwwwwMwMMwwMwwMwM', '200.000', 4, 1, 1, 8, 11, 9, 3, NULL, NULL, 20, '2021-02-07 10:54:52', '2021-02-07 11:22:45'),
(15, 683342, 'MwMwMMwMwMwMwwMMMMMM', '200.000', 4, 1, 1, 8, 10, 10, 3, NULL, NULL, 20, '2021-02-07 10:54:58', '2021-02-07 11:22:45'),
(16, 683343, 'MwwwMMwMMwwMwwMMwwwM', '200.000', 4, 1, 1, 8, 8, 12, 3, NULL, NULL, 20, '2021-02-07 10:55:04', '2021-02-07 11:22:45'),
(17, 683344, 'wMMMMwwwMwwwMwMMwMww', '200.000', 4, 1, 1, 8, 11, 9, 3, NULL, NULL, 20, '2021-02-07 11:19:48', '2021-02-07 11:22:45'),
(18, 683345, 'MMMMMwMwMMMMwwwwMMMw', '200.000', 4, 2, 21, 8, 11, 9, 3, NULL, NULL, 20, '2021-02-07 11:26:14', '2021-02-07 11:37:49'),
(19, 683346, 'MMwwMwwwwMwMMwMwwMMM', '200.000', 4, 2, 21, 8, 12, 8, 3, NULL, NULL, 20, '2021-02-07 11:26:20', '2021-02-07 11:37:49'),
(20, 683347, 'MwwMwwMwMMwMwwwMMwww', '200.000', 4, 2, 21, 8, 14, 6, 1, '11590.000', 1, 20, '2021-02-07 11:26:25', '2021-02-07 11:37:57'),
(21, 683348, 'wwwMwMMwwMwwMwMwMMwM', '200.000', 4, 2, 21, 8, 11, 9, 3, NULL, NULL, 20, '2021-02-07 11:26:34', '2021-02-07 11:37:49'),
(22, 683349, 'wMwwwMwMwwwwMwMwwMwM', '200.000', 4, 2, 21, 3, 7, 13, 3, NULL, NULL, 20, '2021-02-07 11:26:46', '2021-02-07 11:37:49'),
(23, 683350, 'MMwMwwwwMwMMwwMwMMww', '200.000', 4, 2, 21, 3, 11, 9, 3, NULL, NULL, 20, '2021-02-07 11:26:55', '2021-02-07 11:37:49'),
(24, 683351, 'MMMMMMMMMMMMMMMMMMMM', '200.000', 4, 2, 21, 3, 12, 8, 3, NULL, NULL, 20, '2021-02-07 11:27:00', '2021-02-07 11:37:49'),
(25, 683352, 'wMMwwMMMMwMwMwwwMwMM', '200.000', 4, 2, 21, 3, 5, 15, 3, NULL, NULL, 20, '2021-02-07 11:27:06', '2021-02-07 11:37:49'),
(26, 683353, 'wwMwwwwwwwMMwMwMwMww', '200.000', 4, 2, 21, 3, 8, 12, 3, NULL, NULL, 20, '2021-02-07 11:27:12', '2021-02-07 11:37:49'),
(27, 683354, 'wwMMwwwMwMwwwwMwwwMw', '200.000', 4, 2, 21, 3, 8, 12, 3, NULL, NULL, 20, '2021-02-07 11:27:18', '2021-02-07 11:37:49'),
(28, 683355, 'wwwMMMMMwwwMMMMwwwMw', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:27:24', '2021-02-07 11:37:49'),
(29, 683356, 'wMwMMwMMMwMMMwMMMMwM', '200.000', 4, 2, 21, 3, 12, 8, 3, NULL, NULL, 20, '2021-02-07 11:27:29', '2021-02-07 11:37:49'),
(30, 683357, 'wwwMwMwwwwMwwwMwwMMM', '200.000', 4, 2, 21, 3, 7, 13, 3, NULL, NULL, 20, '2021-02-07 11:27:35', '2021-02-07 11:37:49'),
(31, 683358, 'wwwMwMMwMwMMMMMMwwMM', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:27:40', '2021-02-07 11:37:49'),
(32, 683359, 'wMwMMMMwwMMwMMMwMwMw', '200.000', 4, 2, 21, 3, 12, 8, 3, NULL, NULL, 20, '2021-02-07 11:27:45', '2021-02-07 11:37:49'),
(33, 683360, 'wwMMMwMMwMMwwwwMMMMw', '200.000', 4, 2, 21, 3, 9, 11, 3, NULL, NULL, 20, '2021-02-07 11:27:52', '2021-02-07 11:37:49'),
(34, 683361, 'MMwwMwwMMwMMwMMMwMMw', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:27:59', '2021-02-07 11:37:49'),
(35, 683362, 'MMMMMMwMwwMwwMwMwMww', '200.000', 4, 2, 21, 3, 9, 11, 3, NULL, NULL, 20, '2021-02-07 11:28:05', '2021-02-07 11:37:49'),
(36, 683363, 'wMwMwwwwMMMMwwMwwwwM', '200.000', 4, 2, 21, 3, 12, 8, 3, NULL, NULL, 20, '2021-02-07 11:28:11', '2021-02-07 11:37:49'),
(37, 683364, 'MMwMwMwwwwMMwMMwwMwM', '200.000', 4, 2, 21, 3, 12, 8, 3, NULL, NULL, 20, '2021-02-07 11:28:26', '2021-02-07 11:37:49'),
(38, 683365, 'MMwMMMwwwwMMwMwMwMMM', '200.000', 4, 2, 21, 3, 12, 8, 3, NULL, NULL, 20, '2021-02-07 11:28:45', '2021-02-07 11:37:49'),
(39, 683366, 'wMwMwwMMMMwwwMMwMwMw', '200.000', 4, 2, 21, 3, 12, 8, 3, NULL, NULL, 20, '2021-02-07 11:28:51', '2021-02-07 11:37:49'),
(40, 683367, 'MwMwMMwMMwwwwMwMwMMw', '200.000', 4, 2, 21, 3, 6, 14, 3, NULL, NULL, 20, '2021-02-07 11:28:56', '2021-02-07 11:37:49'),
(41, 683368, 'wwMMMwwMwwMMMwMMMMMM', '200.000', 4, 2, 21, 3, 9, 11, 3, NULL, NULL, 20, '2021-02-07 11:29:01', '2021-02-07 11:37:49'),
(42, 683369, 'wMMMMMMMMMMMwwwMMwww', '200.000', 4, 2, 21, 3, 11, 9, 3, NULL, NULL, 20, '2021-02-07 11:29:09', '2021-02-07 11:37:49'),
(43, 683370, 'wMMwMMwMwwMMwMwMMMMw', '200.000', 4, 2, 21, 3, 8, 12, 3, NULL, NULL, 20, '2021-02-07 11:29:14', '2021-02-07 11:37:49'),
(44, 683371, 'wMwwMMMMwwwwMwMwMMww', '200.000', 4, 2, 21, 3, 9, 11, 3, NULL, NULL, 20, '2021-02-07 11:29:20', '2021-02-07 11:37:49'),
(45, 683372, 'MwMMMwMMMMMMwMMMwMMw', '200.000', 4, 2, 21, 3, 11, 9, 3, NULL, NULL, 20, '2021-02-07 11:29:27', '2021-02-07 11:37:49'),
(46, 683373, 'wMwwMwwMwwwMwwwwwwww', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:29:34', '2021-02-07 11:37:49'),
(47, 683374, 'wwMMwMMwwMMMwwwwMMMM', '200.000', 4, 2, 21, 3, 9, 11, 3, NULL, NULL, 20, '2021-02-07 11:29:40', '2021-02-07 11:37:49'),
(48, 683375, 'wMwwMMMMMwwMwwMMMwMw', '200.000', 4, 2, 21, 3, 11, 9, 3, NULL, NULL, 20, '2021-02-07 11:29:46', '2021-02-07 11:37:49'),
(49, 683376, 'wwwMwMwMMwwMwMMwMMMM', '200.000', 4, 2, 21, 3, 9, 11, 3, NULL, NULL, 20, '2021-02-07 11:30:14', '2021-02-07 11:37:49'),
(50, 683377, 'wwMwMwMMwwMMwwwMMMMw', '200.000', 4, 2, 21, 3, 8, 12, 3, NULL, NULL, 20, '2021-02-07 11:31:02', '2021-02-07 11:37:49'),
(51, 683378, 'MMwwMMwMMMwMwwwwwMMw', '200.000', 4, 2, 21, 3, 8, 12, 3, NULL, NULL, 20, '2021-02-07 11:31:08', '2021-02-07 11:37:49'),
(52, 683379, 'MMMMwwwMMwMwwMwwMMwM', '200.000', 4, 2, 21, 3, 9, 11, 3, NULL, NULL, 20, '2021-02-07 11:31:14', '2021-02-07 11:37:49'),
(53, 683380, 'MMwMwwwMMwwwwwMMwMwM', '200.000', 4, 2, 21, 3, 11, 9, 3, NULL, NULL, 20, '2021-02-07 11:31:19', '2021-02-07 11:37:49'),
(54, 683381, 'MMMMMwwMMMwMMwMwwMwM', '200.000', 4, 2, 21, 3, 11, 9, 3, NULL, NULL, 20, '2021-02-07 11:31:25', '2021-02-07 11:37:49'),
(55, 683382, 'wMMMwMMwMMwwwMMwwMMw', '200.000', 4, 2, 21, 3, 9, 11, 3, NULL, NULL, 20, '2021-02-07 11:31:48', '2021-02-07 11:37:49'),
(56, 683383, 'wMMwwwMMwwMwwMMMwwww', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:31:54', '2021-02-07 11:37:49'),
(57, 683384, 'MwMwMwMMwwwwwMMwwMMw', '200.000', 4, 2, 21, 3, 9, 11, 3, NULL, NULL, 20, '2021-02-07 11:31:59', '2021-02-07 11:37:49'),
(58, 683385, 'wMMMwwwMMwMwwMwMwMMw', '200.000', 4, 2, 21, 3, 6, 14, 3, NULL, NULL, 20, '2021-02-07 11:32:04', '2021-02-07 11:37:49'),
(59, 683386, 'wwwwMMwMMMMwMwMwMMww', '200.000', 4, 2, 21, 3, 6, 14, 3, NULL, NULL, 20, '2021-02-07 11:32:31', '2021-02-07 11:37:49'),
(60, 683387, 'MMwwwMwwMwMMwMwwMMwM', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:32:36', '2021-02-07 11:37:49'),
(61, 683388, 'wwwMMwMMwwwMwMMwwwMM', '200.000', 4, 2, 21, 3, 13, 7, 3, NULL, NULL, 20, '2021-02-07 11:32:42', '2021-02-07 11:37:49'),
(62, 683389, 'wwwwwwwwMMMwwMMMMwMw', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:32:47', '2021-02-07 11:37:49'),
(63, 683390, 'wwMMwwMMwMMwMMwwMMww', '200.000', 4, 2, 21, 3, 8, 12, 3, NULL, NULL, 20, '2021-02-07 11:32:53', '2021-02-07 11:37:49'),
(64, 683391, 'wwwMwwMwwwMwMwwwwMMM', '200.000', 4, 2, 21, 3, 7, 13, 3, NULL, NULL, 20, '2021-02-07 11:33:33', '2021-02-07 11:37:49'),
(65, 683392, 'wMwMMwwwMMwwMwMMwwMw', '200.000', 4, 2, 21, 3, 11, 9, 3, NULL, NULL, 20, '2021-02-07 11:33:39', '2021-02-07 11:37:49'),
(66, 683393, 'wwwMMMwMMwwMwwMwMwMM', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:33:44', '2021-02-07 11:37:49'),
(67, 683394, 'wwwMMwMwMMwMMwMMwwMM', '200.000', 4, 2, 21, 3, 13, 7, 3, NULL, NULL, 20, '2021-02-07 11:33:50', '2021-02-07 11:37:49'),
(68, 683395, 'wwMMMwwwMwMMMMwwMMMM', '200.000', 4, 2, 21, 3, 8, 12, 3, NULL, NULL, 20, '2021-02-07 11:34:11', '2021-02-07 11:37:49'),
(69, 683396, 'wwwMMMMMwMwMwwwwwMww', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:34:16', '2021-02-07 11:37:49'),
(70, 683397, 'MwMwMwMwMMwwMMMwMwMM', '200.000', 4, 2, 21, 3, 12, 8, 3, NULL, NULL, 20, '2021-02-07 11:34:21', '2021-02-07 11:37:49'),
(71, 683398, 'wMwwwwMwMwwMwMMMwwww', '200.000', 4, 2, 21, 3, 13, 7, 3, NULL, NULL, 20, '2021-02-07 11:34:39', '2021-02-07 11:37:49'),
(72, 683399, 'wMwMwMwwMwMwwwMMMMww', '200.000', 4, 2, 21, 3, 9, 11, 3, NULL, NULL, 20, '2021-02-07 11:34:45', '2021-02-07 11:37:49'),
(73, 683400, 'MwMwwMwMwMwMwwwMMMwM', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:35:52', '2021-02-07 11:37:49'),
(74, 683401, 'MwMMwwwMMMwMMMwwMwww', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:35:57', '2021-02-07 11:37:49'),
(75, 683402, 'wMMMwwMMwwMMMMMwMMMM', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:36:02', '2021-02-07 11:37:49'),
(76, 683403, 'MMwwwwwMwMwMwwMwMMwM', '200.000', 4, 2, 21, 3, 13, 7, 3, NULL, NULL, 20, '2021-02-07 11:36:09', '2021-02-07 11:37:49'),
(77, 683404, 'MMMMMwMwwwwMMwwwwwww', '200.000', 4, 2, 21, 3, 12, 8, 3, NULL, NULL, 20, '2021-02-07 11:36:15', '2021-02-07 11:37:49'),
(78, 683405, 'MwwMwMMwMwwMMwMMMMMw', '200.000', 4, 2, 21, 3, 10, 10, 3, NULL, NULL, 20, '2021-02-07 11:36:20', '2021-02-07 11:37:49'),
(79, 683406, 'wwwwMMMwMwwwMwMMMwwM', '200.000', 4, 3, 41, 3, NULL, NULL, 0, NULL, NULL, 0, '2021-02-07 11:54:39', '2021-02-07 11:54:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bet`
--
ALTER TABLE `bet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bet`
--
ALTER TABLE `bet`
  MODIFY `id` int(191) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
