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
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(191) NOT NULL,
  `type` varchar(191) NOT NULL,
  `user_id` int(191) NOT NULL,
  `cashier_id` int(191) DEFAULT NULL,
  `message` varchar(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `type`, `user_id`, `cashier_id`, `message`, `created_at`, `updated_at`) VALUES
(1, 'Create_New_Event', 4, NULL, 'admin1 Create Bagong Event.', '2021-02-07 07:34:57', '2021-02-07 07:34:57'),
(2, 'Change_Status_Event', 4, NULL, 'admin1 Changed the status of Bagong Event to Active status.', '2021-02-07 07:35:01', '2021-02-07 07:35:01'),
(3, 'Moved_Fight', 2, NULL, 'Admin2 moved fight number to 1.', '2021-02-07 07:35:22', '2021-02-07 07:35:22'),
(4, 'Insert_Bet', 8, NULL, 'eros inserted bet wwwwMMMMwwMwMwMMMMww with the bet id of 1 Amount:200.00', '2021-02-07 07:35:29', '2021-02-07 07:35:29'),
(5, 'Insert_Bet', 8, NULL, 'eros inserted bet wwMwMMMMwMwMwMwwwwwM with the bet id of 2 Amount:200.00', '2021-02-07 07:40:03', '2021-02-07 07:40:03'),
(6, 'Insert_Bet', 8, NULL, 'eros inserted bet MMMMwMMwwMwwMMMMMMMM with the bet id of 3 Amount:200.00', '2021-02-07 08:36:05', '2021-02-07 08:36:05'),
(7, 'Insert_Bet', 8, NULL, 'eros inserted bet MwwMMMMwMMwwMwMMMMww with the bet id of 4 Amount:200.00', '2021-02-07 08:36:10', '2021-02-07 08:36:10'),
(8, 'Insert_Bet', 8, NULL, 'eros inserted bet wwwwwMMwMwwwMwMMMwwM with the bet id of 5 Amount:200.00', '2021-02-07 08:44:07', '2021-02-07 08:44:07'),
(9, 'Insert_Bet', 8, NULL, 'eros inserted bet MwwwwwMMwMMwMwwwwwMw with the bet id of 6 Amount:200.00', '2021-02-07 08:44:15', '2021-02-07 08:44:15'),
(10, 'Insert_Bet', 8, NULL, 'eros inserted bet wwMMwMwwwMMwwMMwwMwM with the bet id of 7 Amount:200.00', '2021-02-07 08:44:19', '2021-02-07 08:44:19'),
(11, 'Insert_Bet', 8, NULL, 'eros inserted bet wMMMwwMMwMwMwwMwwwMw with the bet id of 8 Amount:200.00', '2021-02-07 09:18:38', '2021-02-07 09:18:38'),
(12, 'Insert_Bet', 8, NULL, 'eros inserted bet wwMwMwMwwMMwwMMwwMwM with the bet id of 9 Amount:200.00', '2021-02-07 09:30:33', '2021-02-07 09:30:33'),
(13, 'Login', 8, NULL, 'Eros Jhonex logged in', '2021-02-07 10:54:05', '2021-02-07 10:54:05'),
(14, 'Insert_Bet', 8, NULL, 'eros inserted bet MMMMwMMMwMMMwMMwwwwM with the bet id of 10 Amount:200.00', '2021-02-07 10:54:12', '2021-02-07 10:54:12'),
(15, 'Insert_Bet', 8, NULL, 'eros inserted bet MwMMMMwwwwwwMMwwMMwM with the bet id of 11 Amount:200.00', '2021-02-07 10:54:22', '2021-02-07 10:54:22'),
(16, 'Insert_Bet', 8, NULL, 'eros inserted bet wMMwMwwwwwMwMMMMMMww with the bet id of 12 Amount:200.00', '2021-02-07 10:54:26', '2021-02-07 10:54:26'),
(17, 'Insert_Bet', 8, NULL, 'eros inserted bet wwMMMMMwMwMwwMMMMMMM with the bet id of 13 Amount:200.00', '2021-02-07 10:54:40', '2021-02-07 10:54:40'),
(18, 'Insert_Bet', 8, NULL, 'eros inserted bet wMwwwwwwMwMMwwMwwMwM with the bet id of 14 Amount:200.00', '2021-02-07 10:54:52', '2021-02-07 10:54:52'),
(19, 'Insert_Bet', 8, NULL, 'eros inserted bet MwMwMMwMwMwMwwMMMMMM with the bet id of 15 Amount:200.00', '2021-02-07 10:54:58', '2021-02-07 10:54:58'),
(20, 'Insert_Bet', 8, NULL, 'eros inserted bet MwwwMMwMMwwMwwMMwwwM with the bet id of 16 Amount:200.00', '2021-02-07 10:55:05', '2021-02-07 10:55:05'),
(21, 'Insert_Bet', 8, NULL, 'eros inserted bet wMMMMwwwMwwwMwMMwMww with the bet id of 17 Amount:200.00', '2021-02-07 11:19:48', '2021-02-07 11:19:48'),
(22, 'Login', 2, NULL, 'Admin2 logged in', '2021-02-07 11:21:46', '2021-02-07 11:21:46'),
(23, 'Close_Fight', 2, NULL, 'Admin2 Closed betting for starting fight number 1.', '2021-02-07 11:21:50', '2021-02-07 11:21:50'),
(24, 'Requested_Grade', 2, NULL, 'Admin2 Requested Meron for fight number 1', '2021-02-07 11:21:56', '2021-02-07 11:21:56'),
(25, 'Confirmed_Grade', 4, NULL, 'admin1 Confirmed Admin2, Meron for fight number 1', '2021-02-07 11:21:57', '2021-02-07 11:21:57'),
(26, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 2', '2021-02-07 11:21:59', '2021-02-07 11:21:59'),
(27, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 2', '2021-02-07 11:21:59', '2021-02-07 11:21:59'),
(28, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 3', '2021-02-07 11:22:01', '2021-02-07 11:22:01'),
(29, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 3', '2021-02-07 11:22:02', '2021-02-07 11:22:02'),
(30, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 4', '2021-02-07 11:22:04', '2021-02-07 11:22:04'),
(31, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 4', '2021-02-07 11:22:05', '2021-02-07 11:22:05'),
(32, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 5', '2021-02-07 11:22:06', '2021-02-07 11:22:06'),
(33, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 5', '2021-02-07 11:22:07', '2021-02-07 11:22:07'),
(34, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 6', '2021-02-07 11:22:08', '2021-02-07 11:22:08'),
(35, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 6', '2021-02-07 11:22:09', '2021-02-07 11:22:09'),
(36, 'Requested_Grade', 4, NULL, 'admin1 Requested Cancelled for fight number 7', '2021-02-07 11:22:11', '2021-02-07 11:22:11'),
(37, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Cancelled for fight number 7', '2021-02-07 11:22:12', '2021-02-07 11:22:12'),
(38, 'Requested_Grade', 4, NULL, 'admin1 Requested Draw for fight number 8', '2021-02-07 11:22:13', '2021-02-07 11:22:13'),
(39, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Draw for fight number 8', '2021-02-07 11:22:14', '2021-02-07 11:22:14'),
(40, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 9', '2021-02-07 11:22:15', '2021-02-07 11:22:15'),
(41, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 9', '2021-02-07 11:22:16', '2021-02-07 11:22:16'),
(42, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 10', '2021-02-07 11:22:18', '2021-02-07 11:22:18'),
(43, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 10', '2021-02-07 11:22:19', '2021-02-07 11:22:19'),
(44, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 11', '2021-02-07 11:22:20', '2021-02-07 11:22:20'),
(45, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 11', '2021-02-07 11:22:21', '2021-02-07 11:22:21'),
(46, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 12', '2021-02-07 11:22:23', '2021-02-07 11:22:23'),
(47, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 12', '2021-02-07 11:22:24', '2021-02-07 11:22:24'),
(48, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 13', '2021-02-07 11:22:25', '2021-02-07 11:22:25'),
(49, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 13', '2021-02-07 11:22:26', '2021-02-07 11:22:26'),
(50, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 14', '2021-02-07 11:22:30', '2021-02-07 11:22:30'),
(51, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 14', '2021-02-07 11:22:30', '2021-02-07 11:22:30'),
(52, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 15', '2021-02-07 11:22:32', '2021-02-07 11:22:32'),
(53, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 15', '2021-02-07 11:22:33', '2021-02-07 11:22:33'),
(54, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 16', '2021-02-07 11:22:35', '2021-02-07 11:22:35'),
(55, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 16', '2021-02-07 11:22:35', '2021-02-07 11:22:35'),
(56, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 17', '2021-02-07 11:22:37', '2021-02-07 11:22:37'),
(57, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 17', '2021-02-07 11:22:38', '2021-02-07 11:22:38'),
(58, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 18', '2021-02-07 11:22:39', '2021-02-07 11:22:39'),
(59, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 18', '2021-02-07 11:22:40', '2021-02-07 11:22:40'),
(60, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 19', '2021-02-07 11:22:42', '2021-02-07 11:22:42'),
(61, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 19', '2021-02-07 11:22:43', '2021-02-07 11:22:43'),
(62, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 20', '2021-02-07 11:22:44', '2021-02-07 11:22:44'),
(63, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 20', '2021-02-07 11:22:45', '2021-02-07 11:22:45'),
(64, 'Request_Open_Claiming_bets', 4, NULL, 'admin1 Requested open claimimg bets for startingfight 1.', '2021-02-07 11:22:48', '2021-02-07 11:22:48'),
(65, 'Confirmed_Open_Claiming_Bets', 8, NULL, 'eros wins 323, from startingfight number : 1 from Bagong Event event, [New balance : 199238.00]', '2021-02-07 11:22:50', '2021-02-07 11:22:50'),
(66, 'Confirmed_Open_Claiming_Bets', 2, NULL, 'Admin2 Confirmed admin1 for open claiming bet, for startingfight number 1.', '2021-02-07 11:22:50', '2021-02-07 11:22:50'),
(67, 'Moved_Fight', 4, NULL, 'admin1 moved fight number to 21.', '2021-02-07 11:24:06', '2021-02-07 11:24:06'),
(68, 'Insert_Bet', 8, NULL, 'eros inserted bet MMMMMwMwMMMMwwwwMMMw with the bet id of 18 Amount:200.00', '2021-02-07 11:26:14', '2021-02-07 11:26:14'),
(69, 'Insert_Bet', 8, NULL, 'eros inserted bet MMwwMwwwwMwMMwMwwMMM with the bet id of 19 Amount:200.00', '2021-02-07 11:26:21', '2021-02-07 11:26:21'),
(70, 'Insert_Bet', 8, NULL, 'eros inserted bet MwwMwwMwMMwMwwwMMwww with the bet id of 20 Amount:200.00', '2021-02-07 11:26:25', '2021-02-07 11:26:25'),
(71, 'Insert_Bet', 8, NULL, 'eros inserted bet wwwMwMMwwMwwMwMwMMwM with the bet id of 21 Amount:200.00', '2021-02-07 11:26:34', '2021-02-07 11:26:34'),
(72, 'Logout', 8, NULL, 'Eros Jhonex logged out', '2021-02-07 11:26:37', '2021-02-07 11:26:37'),
(73, 'Login', 3, NULL, 'teller1 logged in', '2021-02-07 11:26:41', '2021-02-07 11:26:41'),
(74, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMwwwMwMwwwwMwMwwMwM with the bet id of 22 Amount:200.00', '2021-02-07 11:26:46', '2021-02-07 11:26:46'),
(75, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMwMwwwwMwMMwwMwMMww with the bet id of 23 Amount:200.00', '2021-02-07 11:26:55', '2021-02-07 11:26:55'),
(76, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMMMMMMMMMMMMMMMMMMM with the bet id of 24 Amount:200.00', '2021-02-07 11:27:00', '2021-02-07 11:27:00'),
(77, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMMwwMMMMwMwMwwwMwMM with the bet id of 25 Amount:200.00', '2021-02-07 11:27:06', '2021-02-07 11:27:06'),
(78, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwMwwwwwwwMMwMwMwMww with the bet id of 26 Amount:200.00', '2021-02-07 11:27:12', '2021-02-07 11:27:12'),
(79, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwMMwwwMwMwwwwMwwwMw with the bet id of 27 Amount:200.00', '2021-02-07 11:27:18', '2021-02-07 11:27:18'),
(80, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwMMMMMwwwMMMMwwwMw with the bet id of 28 Amount:200.00', '2021-02-07 11:27:24', '2021-02-07 11:27:24'),
(81, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMwMMwMMMwMMMwMMMMwM with the bet id of 29 Amount:200.00', '2021-02-07 11:27:29', '2021-02-07 11:27:29'),
(82, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwMwMwwwwMwwwMwwMMM with the bet id of 30 Amount:200.00', '2021-02-07 11:27:35', '2021-02-07 11:27:35'),
(83, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwMwMMwMwMMMMMMwwMM with the bet id of 31 Amount:200.00', '2021-02-07 11:27:40', '2021-02-07 11:27:40'),
(84, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMwMMMMwwMMwMMMwMwMw with the bet id of 32 Amount:200.00', '2021-02-07 11:27:45', '2021-02-07 11:27:45'),
(85, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwMMMwMMwMMwwwwMMMMw with the bet id of 33 Amount:200.00', '2021-02-07 11:27:52', '2021-02-07 11:27:52'),
(86, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMwwMwwMMwMMwMMMwMMw with the bet id of 34 Amount:200.00', '2021-02-07 11:27:59', '2021-02-07 11:27:59'),
(87, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMMMMMwMwwMwwMwMwMww with the bet id of 35 Amount:200.00', '2021-02-07 11:28:06', '2021-02-07 11:28:06'),
(88, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMwMwwwwMMMMwwMwwwwM with the bet id of 36 Amount:200.00', '2021-02-07 11:28:11', '2021-02-07 11:28:11'),
(89, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMwMwMwwwwMMwMMwwMwM with the bet id of 37 Amount:200.00', '2021-02-07 11:28:26', '2021-02-07 11:28:26'),
(90, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMwMMMwwwwMMwMwMwMMM with the bet id of 38 Amount:200.00', '2021-02-07 11:28:45', '2021-02-07 11:28:45'),
(91, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMwMwwMMMMwwwMMwMwMw with the bet id of 39 Amount:200.00', '2021-02-07 11:28:51', '2021-02-07 11:28:51'),
(92, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MwMwMMwMMwwwwMwMwMMw with the bet id of 40 Amount:200.00', '2021-02-07 11:28:56', '2021-02-07 11:28:56'),
(93, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwMMMwwMwwMMMwMMMMMM with the bet id of 41 Amount:200.00', '2021-02-07 11:29:01', '2021-02-07 11:29:01'),
(94, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMMMMMMMMMMMwwwMMwww with the bet id of 42 Amount:200.00', '2021-02-07 11:29:09', '2021-02-07 11:29:09'),
(95, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMMwMMwMwwMMwMwMMMMw with the bet id of 43 Amount:200.00', '2021-02-07 11:29:14', '2021-02-07 11:29:14'),
(96, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMwwMMMMwwwwMwMwMMww with the bet id of 44 Amount:200.00', '2021-02-07 11:29:20', '2021-02-07 11:29:20'),
(97, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MwMMMwMMMMMMwMMMwMMw with the bet id of 45 Amount:200.00', '2021-02-07 11:29:27', '2021-02-07 11:29:27'),
(98, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMwwMwwMwwwMwwwwwwww with the bet id of 46 Amount:200.00', '2021-02-07 11:29:35', '2021-02-07 11:29:35'),
(99, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwMMwMMwwMMMwwwwMMMM with the bet id of 47 Amount:200.00', '2021-02-07 11:29:41', '2021-02-07 11:29:41'),
(100, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMwwMMMMMwwMwwMMMwMw with the bet id of 48 Amount:200.00', '2021-02-07 11:29:46', '2021-02-07 11:29:46'),
(101, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwMwMwMMwwMwMMwMMMM with the bet id of 49 Amount:200.00', '2021-02-07 11:30:14', '2021-02-07 11:30:14'),
(102, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwMwMwMMwwMMwwwMMMMw with the bet id of 50 Amount:200.00', '2021-02-07 11:31:03', '2021-02-07 11:31:03'),
(103, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMwwMMwMMMwMwwwwwMMw with the bet id of 51 Amount:200.00', '2021-02-07 11:31:08', '2021-02-07 11:31:08'),
(104, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMMMwwwMMwMwwMwwMMwM with the bet id of 52 Amount:200.00', '2021-02-07 11:31:14', '2021-02-07 11:31:14'),
(105, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMwMwwwMMwwwwwMMwMwM with the bet id of 53 Amount:200.00', '2021-02-07 11:31:19', '2021-02-07 11:31:19'),
(106, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMMMMwwMMMwMMwMwwMwM with the bet id of 54 Amount:200.00', '2021-02-07 11:31:25', '2021-02-07 11:31:25'),
(107, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMMMwMMwMMwwwMMwwMMw with the bet id of 55 Amount:200.00', '2021-02-07 11:31:48', '2021-02-07 11:31:48'),
(108, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMMwwwMMwwMwwMMMwwww with the bet id of 56 Amount:200.00', '2021-02-07 11:31:54', '2021-02-07 11:31:54'),
(109, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MwMwMwMMwwwwwMMwwMMw with the bet id of 57 Amount:200.00', '2021-02-07 11:31:59', '2021-02-07 11:31:59'),
(110, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMMMwwwMMwMwwMwMwMMw with the bet id of 58 Amount:200.00', '2021-02-07 11:32:04', '2021-02-07 11:32:04'),
(111, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwwMMwMMMMwMwMwMMww with the bet id of 59 Amount:200.00', '2021-02-07 11:32:31', '2021-02-07 11:32:31'),
(112, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMwwwMwwMwMMwMwwMMwM with the bet id of 60 Amount:200.00', '2021-02-07 11:32:37', '2021-02-07 11:32:37'),
(113, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwMMwMMwwwMwMMwwwMM with the bet id of 61 Amount:200.00', '2021-02-07 11:32:42', '2021-02-07 11:32:42'),
(114, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwwwwwwMMMwwMMMMwMw with the bet id of 62 Amount:200.00', '2021-02-07 11:32:47', '2021-02-07 11:32:47'),
(115, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwMMwwMMwMMwMMwwMMww with the bet id of 63 Amount:200.00', '2021-02-07 11:32:54', '2021-02-07 11:32:54'),
(116, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwMwwMwwwMwMwwwwMMM with the bet id of 64 Amount:200.00', '2021-02-07 11:33:33', '2021-02-07 11:33:33'),
(117, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMwMMwwwMMwwMwMMwwMw with the bet id of 65 Amount:200.00', '2021-02-07 11:33:39', '2021-02-07 11:33:39'),
(118, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwMMMwMMwwMwwMwMwMM with the bet id of 66 Amount:200.00', '2021-02-07 11:33:44', '2021-02-07 11:33:44'),
(119, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwMMwMwMMwMMwMMwwMM with the bet id of 67 Amount:200.00', '2021-02-07 11:33:50', '2021-02-07 11:33:50'),
(120, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwMMMwwwMwMMMMwwMMMM with the bet id of 68 Amount:200.00', '2021-02-07 11:34:11', '2021-02-07 11:34:11'),
(121, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwMMMMMwMwMwwwwwMww with the bet id of 69 Amount:200.00', '2021-02-07 11:34:16', '2021-02-07 11:34:16'),
(122, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MwMwMwMwMMwwMMMwMwMM with the bet id of 70 Amount:200.00', '2021-02-07 11:34:21', '2021-02-07 11:34:21'),
(123, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMwwwwMwMwwMwMMMwwww with the bet id of 71 Amount:200.00', '2021-02-07 11:34:40', '2021-02-07 11:34:40'),
(124, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMwMwMwwMwMwwwMMMMww with the bet id of 72 Amount:200.00', '2021-02-07 11:34:45', '2021-02-07 11:34:45'),
(125, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MwMwwMwMwMwMwwwMMMwM with the bet id of 73 Amount:200.00', '2021-02-07 11:35:52', '2021-02-07 11:35:52'),
(126, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MwMMwwwMMMwMMMwwMwww with the bet id of 74 Amount:200.00', '2021-02-07 11:35:57', '2021-02-07 11:35:57'),
(127, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wMMMwwMMwwMMMMMwMMMM with the bet id of 75 Amount:200.00', '2021-02-07 11:36:02', '2021-02-07 11:36:02'),
(128, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMwwwwwMwMwMwwMwMMwM with the bet id of 76 Amount:200.00', '2021-02-07 11:36:09', '2021-02-07 11:36:09'),
(129, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MMMMMwMwwwwMMwwwwwww with the bet id of 77 Amount:200.00', '2021-02-07 11:36:15', '2021-02-07 11:36:15'),
(130, 'Insert_Bet', 3, NULL, 'teller1 inserted bet MwwMwMMwMwwMMwMMMMMw with the bet id of 78 Amount:200.00', '2021-02-07 11:36:20', '2021-02-07 11:36:20'),
(131, 'Close_Fight', 4, NULL, 'admin1 Closed betting for starting fight number 21.', '2021-02-07 11:36:45', '2021-02-07 11:36:45'),
(132, 'Moved_Fight', 4, NULL, 'admin1 moved fight number to 41.', '2021-02-07 11:36:53', '2021-02-07 11:36:53'),
(133, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 21', '2021-02-07 11:36:56', '2021-02-07 11:36:56'),
(134, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 21', '2021-02-07 11:36:57', '2021-02-07 11:36:57'),
(135, 'Requested_Grade', 2, NULL, 'Admin2 Requested Meron for fight number 22', '2021-02-07 11:37:01', '2021-02-07 11:37:01'),
(136, 'Confirmed_Grade', 4, NULL, 'admin1 Confirmed Admin2, Meron for fight number 22', '2021-02-07 11:37:02', '2021-02-07 11:37:02'),
(137, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 23', '2021-02-07 11:37:07', '2021-02-07 11:37:07'),
(138, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 23', '2021-02-07 11:37:07', '2021-02-07 11:37:07'),
(139, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 24', '2021-02-07 11:37:09', '2021-02-07 11:37:09'),
(140, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 24', '2021-02-07 11:37:10', '2021-02-07 11:37:10'),
(141, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 25', '2021-02-07 11:37:12', '2021-02-07 11:37:12'),
(142, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 25', '2021-02-07 11:37:13', '2021-02-07 11:37:13'),
(143, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 26', '2021-02-07 11:37:15', '2021-02-07 11:37:15'),
(144, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 26', '2021-02-07 11:37:15', '2021-02-07 11:37:15'),
(145, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 27', '2021-02-07 11:37:17', '2021-02-07 11:37:17'),
(146, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 27', '2021-02-07 11:37:18', '2021-02-07 11:37:18'),
(147, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 28', '2021-02-07 11:37:19', '2021-02-07 11:37:19'),
(148, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 28', '2021-02-07 11:37:20', '2021-02-07 11:37:20'),
(149, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 29', '2021-02-07 11:37:22', '2021-02-07 11:37:22'),
(150, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 29', '2021-02-07 11:37:22', '2021-02-07 11:37:22'),
(151, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 30', '2021-02-07 11:37:24', '2021-02-07 11:37:24'),
(152, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 30', '2021-02-07 11:37:25', '2021-02-07 11:37:25'),
(153, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 31', '2021-02-07 11:37:26', '2021-02-07 11:37:26'),
(154, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 31', '2021-02-07 11:37:27', '2021-02-07 11:37:27'),
(155, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 32', '2021-02-07 11:37:28', '2021-02-07 11:37:28'),
(156, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 32', '2021-02-07 11:37:29', '2021-02-07 11:37:29'),
(157, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 33', '2021-02-07 11:37:31', '2021-02-07 11:37:31'),
(158, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 33', '2021-02-07 11:37:31', '2021-02-07 11:37:31'),
(159, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 34', '2021-02-07 11:37:33', '2021-02-07 11:37:33'),
(160, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 34', '2021-02-07 11:37:34', '2021-02-07 11:37:34'),
(161, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 35', '2021-02-07 11:37:35', '2021-02-07 11:37:35'),
(162, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 35', '2021-02-07 11:37:36', '2021-02-07 11:37:36'),
(163, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 36', '2021-02-07 11:37:38', '2021-02-07 11:37:38'),
(164, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 36', '2021-02-07 11:37:38', '2021-02-07 11:37:38'),
(165, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 37', '2021-02-07 11:37:41', '2021-02-07 11:37:41'),
(166, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 37', '2021-02-07 11:37:42', '2021-02-07 11:37:42'),
(167, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 38', '2021-02-07 11:37:43', '2021-02-07 11:37:43'),
(168, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 38', '2021-02-07 11:37:44', '2021-02-07 11:37:44'),
(169, 'Requested_Grade', 4, NULL, 'admin1 Requested Wala for fight number 39', '2021-02-07 11:37:46', '2021-02-07 11:37:46'),
(170, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Wala for fight number 39', '2021-02-07 11:37:46', '2021-02-07 11:37:46'),
(171, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 40', '2021-02-07 11:37:48', '2021-02-07 11:37:48'),
(172, 'Confirmed_Grade', 2, NULL, 'Admin2 Confirmed admin1, Meron for fight number 40', '2021-02-07 11:37:49', '2021-02-07 11:37:49'),
(173, 'Requested_Grade', 4, NULL, 'admin1 Requested Meron for fight number 41', '2021-02-07 11:37:50', '2021-02-07 11:37:50'),
(174, 'Request_Open_Claiming_bets', 2, NULL, 'Admin2 Requested open claimimg bets for startingfight 21.', '2021-02-07 11:37:56', '2021-02-07 11:37:56'),
(175, 'Confirmed_Open_Claiming_Bets', 8, NULL, 'eros wins 1159, from startingfight number : 21 from Bagong Event event, [New balance : 210028.00]', '2021-02-07 11:37:57', '2021-02-07 11:37:57'),
(176, 'Confirmed_Open_Claiming_Bets', 4, NULL, 'admin1 Confirmed Admin2 for open claiming bet, for startingfight number 21.', '2021-02-07 11:37:57', '2021-02-07 11:37:57'),
(177, 'Insert_Bet', 3, NULL, 'teller1 inserted bet wwwwMMMwMwwwMwMMMwwM with the bet id of 79 Amount:200.00', '2021-02-07 11:54:40', '2021-02-07 11:54:40'),
(178, 'Logout', 4, NULL, 'admin1 logged out', '2021-02-07 12:00:32', '2021-02-07 12:00:32'),
(179, 'Login', 4, NULL, 'admin1 logged in', '2021-02-08 00:28:06', '2021-02-08 00:28:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(191) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
