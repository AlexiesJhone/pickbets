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
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(191) NOT NULL,
  `event_name` varchar(191) NOT NULL,
  `fights` int(191) NOT NULL,
  `currentfight` int(191) DEFAULT NULL,
  `pick` int(191) DEFAULT NULL,
  `amount` int(191) DEFAULT NULL,
  `control` varchar(191) NOT NULL,
  `startingfight` int(191) DEFAULT NULL,
  `status` int(191) DEFAULT NULL,
  `rake` int(191) DEFAULT NULL,
  `venue` varchar(191) NOT NULL,
  `fightdate` datetime DEFAULT NULL,
  `totalrake` decimal(11,3) DEFAULT 0.000,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `fights`, `currentfight`, `pick`, `amount`, `control`, `startingfight`, `status`, `rake`, `venue`, `fightdate`, `totalrake`, `created_at`, `updated_at`) VALUES
(4, 'Bagong Event', 600, 40, 20, 200, 'Open', 41, 1, 5, 'Pasig Cockpit Arena (PCA)', '2021-02-07 15:34:00', '780.000', '2021-02-07 07:34:57', '2021-02-07 11:37:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(191) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
