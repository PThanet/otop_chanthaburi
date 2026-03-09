-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 09, 2026 at 04:37 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `otop_chanthaburi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `fullname`, `created_at`) VALUES
(1, 'admin', '$2y$10$Y79XWxqZhdTktr7ktDsmrO9Hrp2/NQisX8Y7RQeI3fuTGyEyhMoRS', 'ผู้ดูแลระบบสูงสุด', '2026-03-09 15:34:03'),
(2, 'W', '$2y$10$rY3NlzkHmTH.9N8A8f8kmu6ND/BS3euaLB2NNoh9wz8otkvA4gfUS', 'ผู้ดูแลระบบสูงสุด', '2026-03-09 15:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `created_at`) VALUES
(1, 'จินดา', '$2y$10$RGdxF/JsawwUzj7b9WH7V.0OYyPW/1MNnUgglaw3V/wzdTjKc05tW', 'ธนากร', '2026-01-12 02:25:28'),
(2, 'ธนากร', '$2y$10$GmaEPLqqfFFzTdgemfNtGuujHzcvEqseTF6Rvl/oS5Z/P5qzhCRvO', 'ธนากร', '2026-01-12 02:25:53'),
(3, 'Thanet', '$2y$10$Gg1xN1rJknDZM/BPQ0bIWOm7yR/t9dodqVLkrspoyfmvavSVBoRNW', 'Thanet Jinda', '2026-01-19 02:17:19'),
(4, 'Mix', '$2y$10$pDUpMbIzTWIZ2ubPvfPWvOX0NJ49xOAJIQKVc.JaYS1FELE4Nu.ie', 'Mix', '2026-03-09 14:27:55');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
