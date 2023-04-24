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
-- Table structure for table `potmoney`
--

CREATE TABLE `potmoney` (
  `id` int(191) NOT NULL,
  `startingfight` int(191) NOT NULL,
  `amount` int(191) NOT NULL,
  `remaining` decimal(11,3) DEFAULT 0.000,
  `rake` decimal(11,3) DEFAULT 0.000,
  `payout` decimal(11,3) DEFAULT 0.000,
  `claim` int(191) DEFAULT 0,
  `event_id` int(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `potmoney`
--

INSERT INTO `potmoney` (`id`, `startingfight`, `amount`, `remaining`, `rake`, `payout`, `claim`, `event_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3400, '0.000', '170.000', '3230.000', 2, 4, '2021-02-07 07:35:28', '2021-02-07 11:22:50'),
(2, 21, 12200, '0.000', '610.000', '11590.000', 2, 4, '2021-02-07 11:26:14', '2021-02-07 11:37:57'),
(3, 41, 200, '0.000', '0.000', '0.000', 0, 4, '2021-02-07 11:54:39', '2021-02-07 11:54:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `potmoney`
--
ALTER TABLE `potmoney`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `potmoney`
--
ALTER TABLE `potmoney`
  MODIFY `id` int(191) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
