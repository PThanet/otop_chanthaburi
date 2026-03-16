-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 16, 2026 at 04:27 PM
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
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fullname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'superadmin',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `fullname`, `created_at`, `role`) VALUES
(1, 'admin', '$2y$10$Y79XWxqZhdTktr7ktDsmrO9Hrp2/NQisX8Y7RQeI3fuTGyEyhMoRS', 'ผู้ดูแลระบบสูงสุด', '2026-03-09 15:34:03', 'superadmin'),
(2, 'W', '$2y$10$rY3NlzkHmTH.9N8A8f8kmu6ND/BS3euaLB2NNoh9wz8otkvA4gfUS', 'ผู้ดูแลระบบสูงสุด', '2026-03-09 15:31:43', 'superadmin'),
(113, 'PThanet', '$2y$10$S2iSpOlJQad1Eymn/lJ.O.32Jbp8o/H3ODOmgmEg0mZA1UVXPK6b6', 'PThanet', '2026-03-10 17:28:06', 'superadmin'),
(117, '1', '$2y$10$PADjpm3W/x9UxIdLz97PwuIVYh23YlakNVU8EqGmIVjMhOueSkEAu', 'adsasdadsasdadsdsa', '2026-03-10 21:50:34', 'admin_travel'),
(120, 'a', '$2y$10$evuXa7enb0bdGDdTTo5E2ejOGTdABuGoz17FvZ/q/kyaO2JCzweV2', 'a a', '2026-03-16 15:47:55', 'admin_tradition'),
(121, 'b', '$2y$10$CH9uF3p9HFei/ZhZIX6WJuDswtn9NlusEoexCIukGklkU8OxFxwBy', 'b b', '2026-03-16 15:48:02', 'admin_product');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) DEFAULT '0.00',
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `customer_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `shipping_cost`, `status`, `payment_method`, `customer_name`, `customer_email`, `customer_phone`, `shipping_address`, `notes`, `created_at`, `updated_at`) VALUES
(1, NULL, 'ORD-20260310192116-C40F5B', 1167.00, 0.00, 'shipped', 'cash_on_delivery', 'fsad', 'fdsa@gmail.com', '0000000000', 'adwadw sdffsd sdfsdf sfdsfd 11111', 'gsea', '2026-03-10 19:21:16', '2026-03-10 19:32:23'),
(2, NULL, 'ORD-20260310193936-832242', 178.00, 0.00, 'confirmed', 'bank_transfer', 'ธนากร จินดา', 'mokgzi123@gmail.com', '0814263732', 'asdadsadw awd adw aswd 81000', 'adfesfe', '2026-03-10 19:39:36', '2026-03-10 20:56:54'),
(3, NULL, 'ORD-20260310194117-DD2B52', 150.00, 0.00, 'pending', 'bank_transfer', 'ธนากร จินดา', 'mokgzi123@gmail.com', '0814263732', 'ฟกไกฟไกฟไ awd adw aswd 81000', '', '2026-03-10 19:41:17', '2026-03-10 19:41:17'),
(4, NULL, 'ORD-20260310194347-3E10AD', 89.00, 0.00, 'pending', 'bank_transfer', 'ธนากร จินดา', 'mokgzi123@gmail.com', '0814263732', 'กไฟกไฟกฟไกไ awd adw aswd 81000', 'ไฟก', '2026-03-10 19:43:47', '2026-03-10 19:43:47'),
(5, NULL, 'ORD-20260310194449-1131B3', 450.00, 0.00, 'shipped', 'bank_transfer', 'ธนากร จินดา', 'mokgzi123@gmail.com', '0814263732', 'ฟหกฟหกฟหกฟหก awd adw aswd 81000', 'ฟหกฟหก', '2026-03-10 19:44:49', '2026-03-10 21:28:51'),
(6, NULL, 'ORD-20260310194844-C0E667', 450.00, 0.00, 'confirmed', 'qr_code', 'wasd', 'fdsa@gmail.com', '0000000000', 'wasd sdffsd wasd wasd 11111', 'wasd', '2026-03-10 19:48:44', '2026-03-10 20:52:04'),
(7, NULL, 'ORD-20260310195024-0E2C6F', 450.00, 0.00, 'shipped', 'bank_transfer', 'wasd', 'wasd@gmail.com', '0000000000', 'wasd wasd wasd wasd 11111', '', '2026-03-10 19:50:24', '2026-03-10 20:52:00'),
(8, NULL, 'ORD-20260310205505-9B81DE', 450.00, 0.00, 'shipped', 'bank_transfer', '1', 'wasd@gmail.com', '0000000000', '1 1 1 1 11111', '1', '2026-03-10 20:55:05', '2026-03-10 21:02:26'),
(9, NULL, 'ORD-20260310212758-E95418', 6750.00, 0.00, 'shipped', 'qr_code', 'ไฟหก', 'mokgzi123@gmail.com', '0814263732', 'ไฟหก awd adw aswd 81000', 'ไฟหก', '2026-03-10 21:27:58', '2026-03-10 21:29:27'),
(10, NULL, 'ORD-20260310214240-04F6E7', 9000.00, 0.00, 'shipped', 'qr_code', 'ไฟหก', 'mokgzi123@gmail.com', '0814263732', 'ไฟหก awd adw aswd 81000', 'ไฟหก', '2026-03-10 21:42:40', '2026-03-10 21:44:13'),
(11, 18, 'ORD-20260316133926-E7C46F', 7200.00, 0.00, 'pending', 'bank_transfer', 'ไฟหก', 'mokgzi123@gmail.com', '0814263732', '123 awd adw aswd 81000', 's', '2026-03-16 13:39:26', '2026-03-16 13:39:26'),
(12, 18, 'ORD-20260316161558-E0F877', 900.00, 0.00, 'pending', 'bank_transfer', 'wasd', 'fdsa@gmail.com', '0000000000', 'ฟกดฟกหดฟกด sdffsd sdfsdf wasd 11111', 'หกดหกด', '2026-03-16 16:15:58', '2026-03-16 16:15:58'),
(13, 1, 'ORD-20260316161715-B172F6', 89.00, 0.00, 'pending', 'cash_on_delivery', 'wasd', 'fdsa@gmail.com', '0000000000', 'dfbz bvc vbc bcxv 11111', 'dfasdfadfasdfa', '2026-03-16 16:17:15', '2026-03-16 16:17:15'),
(14, 21, 'ORD-20260316161803-B5CD2F', 89.00, 0.00, 'pending', 'qr_code', 'wasd', 'fdsa@gmail.com', '0000000000', 'afd fads afds fsd 11111', 'afds', '2026-03-16 16:18:03', '2026-03-16 16:18:03');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_price`, `quantity`, `subtotal`, `created_at`) VALUES
(1, 1, 3, 'เสื่อจันทบูร', 450.00, 2, 900.00, '2026-03-10 19:21:16'),
(2, 1, 2, 'น้ำพริกเผาลำไย', 89.00, 3, 267.00, '2026-03-10 19:21:16'),
(3, 2, 2, 'น้ำพริกเผาลำไย', 89.00, 2, 178.00, '2026-03-10 19:39:36'),
(4, 3, 1, 'สัปปะรดกรอบ', 150.00, 1, 150.00, '2026-03-10 19:41:17'),
(5, 4, 2, 'น้ำพริกเผาลำไย', 89.00, 1, 89.00, '2026-03-10 19:43:47'),
(6, 5, 3, 'เสื่อจันทบูร', 450.00, 1, 450.00, '2026-03-10 19:44:49'),
(7, 6, 3, 'เสื่อจันทบูร', 450.00, 1, 450.00, '2026-03-10 19:48:44'),
(8, 7, 3, 'เสื่อจันทบูร', 450.00, 1, 450.00, '2026-03-10 19:50:24'),
(9, 8, 3, 'เสื่อจันทบูร', 450.00, 1, 450.00, '2026-03-10 20:55:05'),
(10, 9, 3, 'เสื่อจันทบูร', 450.00, 15, 6750.00, '2026-03-10 21:27:58'),
(11, 10, 3, 'เสื่อจันทบูร', 450.00, 20, 9000.00, '2026-03-10 21:42:40'),
(12, 11, 3, 'เสื่อจันทบูร', 450.00, 16, 7200.00, '2026-03-16 13:39:26'),
(13, 12, 1, 'สัปปะรดกรอบ', 150.00, 6, 900.00, '2026-03-16 16:15:58'),
(14, 13, 2, 'น้ำพริกเผาลำไย', 89.00, 1, 89.00, '2026-03-16 16:17:15'),
(15, 14, 2, 'น้ำพริกเผาลำไย', 89.00, 1, 89.00, '2026-03-16 16:18:03');

-- --------------------------------------------------------

--
-- Table structure for table `otop_products`
--

DROP TABLE IF EXISTS `otop_products`;
CREATE TABLE IF NOT EXISTS `otop_products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `price` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image_url_2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_url_3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_url_4` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tag` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tag_color` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otop_products`
--

INSERT INTO `otop_products` (`id`, `name`, `price`, `description`, `image_url`, `image_url_2`, `image_url_3`, `image_url_4`, `tag`, `tag_color`) VALUES
(1, 'สัปปะรดกรอบ', '150', '', 'otop/CrispyPineapple2.png', 'uploads/otop/69b04ec88a0e5_image_file_2.jpg', '', '', 'ขายดี', 'bg-danger'),
(2, 'น้ำพริกเผาลำไย', '89', '', 'otop/ChiliSauce.jpg', 'uploads/otop/69b04e5a18132_image_file_2.png', 'uploads/otop/69b04e5a1837a_image_file_3.png', 'uploads/otop/69b052948c90e_image_file_4.jpg', 'แนะนำ', 'bg-warning text-dark'),
(3, 'เสื่อจันทบูร', '450', '', 'otop/Mat.jpg', 'uploads/otop/69b04e0fa78ff_image_file_2.png', '', '', 'Handmade', 'bg-success');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_addresses`
--

DROP TABLE IF EXISTS `shipping_addresses`;
CREATE TABLE IF NOT EXISTS `shipping_addresses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `fullname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `province` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `district` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `subdistrict` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `postal_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `traditions`
--

DROP TABLE IF EXISTS `traditions`;
CREATE TABLE IF NOT EXISTS `traditions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `event_date` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `event_details` text COLLATE utf8mb4_general_ci,
  `event_location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image_url_2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_url_3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_url_4` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `traditions`
--

INSERT INTO `traditions` (`id`, `name`, `description`, `event_date`, `event_details`, `event_location`, `image_url`, `image_url_2`, `image_url_3`, `image_url_4`) VALUES
(1, 'งานตากสินรำลึก', 'งานประจำปีเพื่อระลึกถึงพระมหากรุณาธิคุณของสมเด็จพระเจ้าตากสินมหาราชในการกอบกู้เอกราช มีการออกร้านและมหรสพมากมาย', NULL, NULL, NULL, 'uploads/traditions/69b80fa0d1738.jpg', NULL, NULL, NULL),
(2, 'งานของดีเมืองจันท์วันผลไม้', 'เทศกาลรวบรวมสุดยอดผลไม้เกรดพรีเมียมจากสวนทั่วจันทบุรี ทั้งทุเรียน มังคุด เงาะ และสินค้าเกษตรคุณภาพสูง', NULL, NULL, NULL, 'uploads/traditions/69b80f3155013.jpg', NULL, NULL, NULL),
(3, 'ประเพณีชักเย่อเกวียนพระบาท', 'ประเพณีท้องถิ่นที่สะท้อนถึงความสามัคคีและวิถีชีวิตของเกษตรกรชาวจันทบุรี มีการแข่งขันที่สนุกสนานและเป็นเอกลักษณ์', '17 เมษายน ของทุกปี', 'ประเพณีชักเย่อเกวียนพระบาท เป็นประเพณีโบราณกว่า 100 ปีของชาวจันทบุรี จัดขึ้นในช่วงเทศกาลสงกรานต์ (ประมาณวันที่ 13-17 เมษายนของทุกปี) เพื่อปัดเป่าโรคภัยและเป็นสิริมงคล โดยจะมีการอัญเชิญผ้าพระบาทจำลองแห่ไปรอบหมู่บ้านและจัดชักเย่อเกวียนจำลองกันอย่างสนุกสนาน', 'จันทบุรี', 'uploads/traditions/69b80f15c3ea1.jpg', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `travel_places`
--

DROP TABLE IF EXISTS `travel_places`;
CREATE TABLE IF NOT EXISTS `travel_places` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image_url_2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_url_3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_url_4` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tag` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `travel_places`
--

INSERT INTO `travel_places` (`id`, `name`, `description`, `image_url`, `image_url_2`, `image_url_3`, `image_url_4`, `tag`) VALUES
(1, 'น้ำตกพลิ้ว', 'สัมผัสน้ำตกสวยใสใจกลางป่า พร้อมฝูงปลาพลวงหินที่เป็นเอกลักษณ์', 'https://ik.imagekit.io/tvlk/blog/2024/11/namtok-phlio-national-park.jpg?tr=q-70,c-at_max,w-1000,h-600', NULL, NULL, NULL, 'ธรรมชาติ'),
(2, 'หาดเจ้าหลาว', 'ชายหาดทรายนวลละเอียด บรรยากาศเงียบสงบ เหมาะแก่การพักผ่อนชมพระอาทิตย์ตก', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80', NULL, NULL, NULL, 'ทะเล'),
(3, 'คุกขี้ไก่', 'ร่องรอยประวัติศาสตร์สมัย ร.ศ. 112 โบราณสถานที่สำคัญของเมืองจันทบุรี', 'https://www.thepeakchan.co.th/wp-content/uploads/2024/03/%E0%B8%84%E0%B8%B8%E0%B8%81%E0%B8%82%E0%B8%B5%E0%B9%89%E0%B9%84%E0%B8%81%E0%B9%88.jpg', NULL, NULL, NULL, 'ประวัติศาสตร์'),
(4, 'ศูนย์ป่าชายเลน', 'เดินศึกษาธรรมชาติบนสะพานไม้ผ่านผืนป่าชายเลนที่อุดมสมบูรณ์ที่สุดแห่งหนึ่ง', 'https://cbtthailand.dasta.or.th/upload-file-api/Resources/RelateAttraction/Images/RAT770187/1.jpeg', NULL, NULL, NULL, 'การเรียนรู้');

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `created_at`) VALUES
(1, 'Thanakorn', '$2y$10$RGdxF/JsawwUzj7b9WH7V.0OYyPW/1MNnUgglaw3V/wzdTjKc05tW', 'ธนากร จินดา', '2026-01-12 02:25:28'),
(18, 'ไฟหก', '$2y$10$vU5i6BZHd1DCvBTQVnhBWeYH58/oAvSwanPLa175Q2QKj2Zfv9CJC', 'ไฟหก ไฟหก', '2026-03-10 21:56:48'),
(21, 'c', '$2y$10$M5SjgoL1if9l4CG8Teo2EeMRjimIEXWuJ7nZ1Tbg2txNVzwCNoDCq', 'c c', '2026-03-16 15:47:08'),
(22, 'd', '$2y$10$iqEw6cVG7v.LuwnVQro/ZetsErdGfTMHmWxWuIpusBl998/TqLdrq', 'd d', '2026-03-16 15:47:18'),
(23, 'e', '$2y$10$Kt0YvP.MaeyRrdHkUcgwAOcHNeGPnmsDPfd3445GyS49nLSQ2Kebm', 'e e', '2026-03-16 15:47:34');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `otop_products` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD CONSTRAINT `shipping_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
