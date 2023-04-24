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
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` varchar(191) NOT NULL,
  `location` varchar(191) NOT NULL,
  `active` int(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `location`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Alex Group', 'awd\nawd\nawd\nawd', 'Pasig', 1, '2021-01-23 23:12:37', '2021-02-03 01:45:30'),
(2, 'Fiax Group', 'For testing\nonly!', 'Mandaluyong', 1, '2021-01-24 14:01:36', '2021-01-24 14:01:36'),
(3, 'Test Group', 'Big Jaaahh', 'Quezon Province', 1, '2021-01-24 14:02:30', '2021-01-24 14:02:30'),
(4, 'AWD Group', 'For testings', 'Mandaluyong', 1, '2021-01-24 14:41:46', '2021-01-24 14:41:46'),
(5, 'Group 2', 'wadawda', 'awdawd', 1, '2021-01-24 14:41:54', '2021-01-28 17:28:31'),
(6, 'dwa', 'awd', 'dwa', 1, '2021-01-24 14:42:00', '2021-01-24 14:42:00'),
(7, 'awd', 'awd', 'awd', 1, '2021-01-24 14:42:05', '2021-01-24 14:42:05'),
(8, 'awd', 'awd', 'awd', 1, '2021-01-24 14:42:11', '2021-01-24 14:42:11'),
(9, 'awdawdawd', 'awdawdaw', 'awdawd', 1, '2021-01-24 14:42:19', '2021-01-24 14:42:19'),
(10, 'Alex`s Group', 'For testing', 'Pasig', 1, '2021-01-24 14:42:25', '2021-02-06 19:04:48'),
(11, 'Group 1', 'awdawdawd', 'awdawd', 1, '2021-01-24 14:42:33', '2021-01-28 17:28:16'),
(12, 'sampledaw', '123654', 'pasig', 0, '2021-02-04 21:12:28', '2021-02-04 21:12:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(191) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
