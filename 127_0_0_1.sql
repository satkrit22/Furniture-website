-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 06:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `comtech`
--
CREATE DATABASE IF NOT EXISTS `comtech` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `comtech`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '$2y$10$KbZ4U19kbb5BL6m1V1kkzuP91ZdskKDcaKLf0uT0xn4Ja4ueyK.9m', '2025-05-02 06:46:27');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'ALL', '2025-05-02 07:51:10'),
(2, 'Link PC', '2025-05-02 07:51:10'),
(3, 'Computer Accessories', '2025-05-02 07:51:10'),
(4, 'Laptop & Accessories', '2025-05-02 07:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `total_products` text NOT NULL,
  `total_price` int(11) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `status`, `created_at`) VALUES
(1, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash on delivery', 'Address. jorpati, kathmandu, Province No. 1, Nepal', 'Dell Inspirion 3430 (90000 x 1) - ', 90000, 'pending', '2025-05-03 15:27:52'),
(2, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'cash on delivery', 'Address. jorpati, kathmandu, Province No. 2, Nepal', 'Laptop Cooler (1500 x 1) - Link PC+ (13500 x 1) - ', 15150, 'pending', '2025-05-05 03:54:38'),
(3, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'cash on delivery', 'Address. jorpati, kathmandu, Province No. 1, Nepal', 'Link PC (10500 x 10) - ', 105150, 'pending', '2025-05-05 03:59:04'),
(4, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'cash on delivery', 'Address. jorpati, kathmandu, Province No. 2, Nepal', 'Link PC+ (13500 x 1) - ', 13650, 'pending', '2025-05-05 04:07:17'),
(5, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'cash on delivery', 'Address. jorpati, kathmandu, Province No. 1, Nepal', 'Link PC+ (13500 x 5) - ', 67650, 'pending', '2025-05-05 04:11:06'),
(6, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'cash on delivery', 'Address. jorpati, kathmandu, Bagmati Province, Nepal', 'Dell Inspirion 3430 (90000 x 0) - Mouse (1500 x 1) - Link PC (10500 x 2) - ', 22500, 'pending', '2025-05-05 05:01:36'),
(7, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'Cash on Delivery', 'Address. jorpati, kathmandu, Bagmati Province, Nepal', 'Laptop Cooler (1500 x 1) - ', 1500, 'pending', '2025-05-05 13:27:43'),
(8, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'Cash on Delivery', 'Address: jorpati, kathmandu, Bagmati Province, Nepal', 'Link PC (10500 x 1) - Link PC+ (13500 x 1) - ', 24000, '', '2025-05-06 03:48:23'),
(9, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'Cash on Delivery', 'Address: jorpati, kathmandu, Other, Nepal', 'Link PC+ (13500 x 2) - Laptop Cooler (1500 x 1) - ', 28700, 'cancelled', '2025-05-06 10:40:14'),
(10, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'Cash on Delivery', 'Address: jorpati, kathmandu, Other, Nepal', 'Caddy (500 x 3) - NVME SSD 128GB (3000 x 1) - ', 4700, 'pending', '2025-05-07 04:47:29'),
(11, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'Cash on Delivery', 'Address: jorpati, kathmandu, Other, Nepal', 'Laptop Cooler (1500 x 1) - HDMI to VGA Converter (500 x 1) - ', 2200, 'pending', '2025-05-07 11:17:11'),
(12, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'Esewa', 'Address: jorpati, kathmandu, Other, Nepal', 'Link PC+ (13500 x 1) - ', 13700, 'pending', '2025-05-16 04:50:08'),
(13, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'Khalti', 'Address: jorpati, kathmandu, Bagmati Province, Nepal', 'Link PC (10500 x 1) - ', 10500, 'completed', '2025-05-18 04:24:42'),
(14, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'Khalti', 'Address: jorpati, kathmandu, Bagmati Province, Nepal', 'Link PC+ (13500 x 1) - Laptop Cooler (1500 x 1) - ', 15000, 'pending', '2025-05-18 04:41:18'),
(15, 3, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'Khalti', 'Address: jorpati, kathmandu, Other, Nepal', 'Link PC+ (13500 x 1) - ', 13700, 'completed', '2025-05-18 04:42:16');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 8, 1, 1, 10500.00),
(2, 8, 2, 1, 13500.00),
(3, 9, 2, 2, 13500.00),
(4, 9, 3, 1, 1500.00),
(5, 10, 17, 3, 500.00),
(6, 10, 15, 1, 3000.00),
(7, 11, 3, 1, 1500.00),
(8, 11, 21, 1, 500.00),
(9, 12, 2, 1, 13500.00),
(10, 13, 1, 1, 10500.00),
(11, 14, 2, 1, 13500.00),
(12, 14, 3, 1, 1500.00),
(13, 15, 2, 1, 13500.00);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`, `used`, `created_at`) VALUES
(1, 3, '1b14dc3e2b2fcb2470d2eb799e6d03912161362ad8a081f9bfe6d49d8eb13016', '2025-05-15 15:00:15', 0, '2025-05-15 12:00:15');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image`, `category_id`, `created_at`) VALUES
(1, 'Link PC', 'Basic Thin client device for everyday tasks', 10500, 186, 'menu-item-1.jpg', 2, '2025-05-03 11:23:59'),
(2, 'Link PC+', 'Thin client device for everyday tasks with better performance', 13500, 139, 'menu-item-1.jpg', 2, '2025-05-03 11:23:59'),
(3, 'Laptop Cooler', 'Cooling pad for laptops', 1500, 26, 'menu-item-6.jpg', 4, '2025-05-03 11:23:59'),
(4, 'Mouse', 'Standard optical USB mouse', 1500, 24, 'menu-item-4.png', 3, '2025-05-03 11:23:59'),
(5, 'Clamper', 'Cable organizer clamp', 1300, 50, 'menu-item-5.jpg', 3, '2025-05-03 11:23:59'),
(6, 'Keyboard', 'Wired USB keyboard', 3200, 10, 'menu-item-3.jpg', 3, '2025-05-03 11:23:59'),
(7, 'Normal Server', 'Entry-level server system', 90500, 18, 'prod-445355-desktop-optiplex-7010-sff-inspiron-3020-no-odd-800x620.png', 2, '2025-05-03 11:23:59'),
(8, 'Network Cable', 'High-quality Ethernet cable', 19500, 12, 'DHU7060_DH-PFM920I-5EUN_product-image_1.png', 2, '2025-05-03 11:23:59'),
(9, 'Solid State Drive 128GB', '128GB SATA SSD storage', 2200, 25, 'DAHUA-SATA-256GB-3 (1).png', 3, '2025-05-03 11:23:59'),
(10, 'Dell Monitor', 'Dell 18.5-inch HD monitor', 15500, 22, 'Dell-D1918H.jpg', 3, '2025-05-03 11:23:59'),
(11, 'Dell Keyboard & Mouse', 'Dell wired keyboard & mouse combo', 3200, 16, 'kb216-ms116-kbm-01-bk-1.png', 3, '2025-05-03 11:23:59'),
(12, 'Power Supply', 'Standard 350W power supply unit', 1500, 8, 'FSP350-60EPN80-lg__34378.jpg', 3, '2025-05-03 11:23:59'),
(13, 'External Harddisk 1TB', '1TB external storage device', 5700, 14, '5e47a7e207605426186502e15be08e22.jpg', 3, '2025-05-03 11:23:59'),
(14, 'Dell Inspirion 3430', 'Dell Inspirion laptop model 3430', 90000, 0, '3430_.jpg', 4, '2025-05-03 11:23:59'),
(15, 'NVME SSD 128GB', '128GB high-speed NVME SSD', 3000, 6, 'HP_1TB_SSD.jpg', 3, '2025-05-03 11:23:59'),
(16, 'Headphone', 'Wired over-ear headphones', 1500, 9, '84cf6d5739a034f0b28023fb91453a2e.jpg', 4, '2025-05-03 11:23:59'),
(17, 'Caddy', 'Laptop HDD/SSD mounting caddy', 500, 17, 'hdd_caddy_1.jpg', 3, '2025-05-03 11:23:59'),
(18, 'Wifi Dongle', 'USB wireless network adapter', 500, 11, '4050158915.jpg', 3, '2025-05-03 11:23:59'),
(19, 'Ethernet Adapter', 'USB to Ethernet network adapter', 800, 13, '71-E1Mu48WL._AC_SL1500_.jpg', 3, '2025-05-03 11:23:59'),
(20, 'CPU Fan', 'Cooling fan for processors', 500, 6, 'main-qimg-a372bdcb21705db51641bf33a8c4dc72-lq.jpeg', 3, '2025-05-03 11:23:59'),
(21, 'HDMI to VGA Converter', 'HDMI to VGA video converter', 500, 26, 'hdmi.jpg', 3, '2025-05-03 11:23:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Name`, `Email`, `Phone`, `password`, `created_at`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'Satkrit Bhandari', 'satkritbhandari11@gmail.com', '9818400974', '$2y$10$l.OmEkVFbd.f/z0/CY5SM.kGEpaI0qogYZmfNRXdELnOEFoE4e3km', '2025-05-02 04:38:39', NULL, NULL),
(3, 'satkrit', 'satkrit15@gmail.com', '9767934698', '$2y$10$95/39CIbPuUJk.MFYYc.O.8j1plP9BOiganPpvE5wBkMGogEC4TpO', '2025-05-05 03:52:47', 'f4e48bc8a74d6c76b425cb8db9a10a973b0daab0f95101bd6234174f625165995397492883d3bac6eafbd69e38431c4a5bd2', '2025-05-06 07:25:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Phone` (`Phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
--
-- Database: `furniture`
--
CREATE DATABASE IF NOT EXISTS `furniture` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `furniture`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'default-avatar.png',
  `role` enum('super_admin','admin','editor') DEFAULT 'admin',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `phone`, `profile_image`, `role`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@furniture.com', '$2y$10$9uSKT7fxb7WPvNeHN1wuTO9n7XoMfNhpbkbYg9xJjbWVLexKxry1S', '1234567890', 'default-avatar.png', 'super_admin', '2025-06-13 03:24:42', '2025-05-11 11:42:02', '2025-06-13 03:24:42');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'ALL', '2025-05-10 04:21:44'),
(2, 'Kitchen', '2025-05-10 04:21:44'),
(3, 'Drawing Room', '2025-05-10 04:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `total_products` text NOT NULL,
  `total_price` int(11) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `status`, `created_at`) VALUES
(14, 2, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'cash_on_delivery', 'jorpati, kathmandu', 'Study Table (1)', 700, 'processing', '2025-05-11 11:14:50'),
(15, 2, 'satkrit', '9767934698', 'satkrit15@gmail.com', 'cash_on_delivery', 'kathmadnu, kathmandu', 'Gaming Chair (1)', 15700, 'completed', '2025-05-11 11:54:16'),
(16, 3, 'satkrit bhandari', '9818400974', 'satkrit25@gmail.com', 'cash_on_delivery', 'jorpati, kathmandu', 'Aquarium Stand (2)', 3200, 'cancelled', '2025-05-20 03:38:56'),
(17, 2, 'satkrit bhandari', '9767934698', 'satkrit15@gmail.com', 'cash_on_delivery', 'jorpati, kathmandu', 'Aquarium Stand (1)', 1700, 'cancelled', '2025-06-12 08:25:25'),
(18, 2, 'satkrit bhandari', '9767934698', 'satkrit15@gmail.com', 'cash_on_delivery', 'jorpati, kathmandu', 'Aquarium Stand (1)', 1700, 'pending', '2025-06-13 03:55:15');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(9, 14, 1, 1, 500.00),
(10, 15, 4, 1, 15500.00),
(11, 16, 3, 2, 1500.00),
(12, 17, 3, 1, 1500.00),
(16, 18, 3, 1, 1500.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image`, `category_id`, `created_at`) VALUES
(1, 'Study Table', 'A versatile study table designed for modern workspaces, featuring ample surface area and storage options for books and gadgets.', 500, 190, 'small study table.jpg', 1, '2025-05-10 05:34:18'),
(2, 'Dining Table', 'A sleek and sturdy dining table that provides a perfect spot for family meals, made from premium materials for durability.', 20000, 150, 'dining table.jpeg', 2, '2025-05-10 05:34:18'),
(3, 'Aquarium Stand', 'A stable and stylish aquarium stand that accommodates small to medium-sized tanks, designed for both aesthetics and functionality.', 1500, 21, 'aquorium.jpg', 1, '2025-05-10 05:34:18'),
(4, 'Gaming Chair', 'An ergonomic gaming chair designed for long gaming sessions, featuring adjustable armrests and a comfortable reclining function.', 15500, 24, 'gaming chair.webp', 1, '2025-05-10 05:34:18'),
(5, 'Comfortable chair', 'A compact and comfortable chair perfect for long hours of sitting, with padded seating and supportive backrest.', 5500, 50, 'new_product_img_1.png', 1, '2025-05-10 05:34:18'),
(6, 'Chaise Longue', 'A luxurious chaise longue for relaxation, with a sleek design and soft cushioning, ideal for lounging or reading.', 3000, 10, 'featured_deals_img_2.png', 3, '2025-05-10 05:34:18'),
(7, 'Cupboard', 'A spacious and functional cupboard that provides ample storage space, perfect for organizing clothes and accessories in any room.', 17500, 18, 'featured_deals_img_3.png', 2, '2025-05-10 05:34:18'),
(8, 'Wood Bar Stool', 'A high-quality wood bar stool with a comfortable seat, perfect for home bars or kitchen counters.', 1000, 12, 'featured_deals_img_4.png', 2, '2025-05-10 05:34:18'),
(9, 'Sofa', 'A plush sofa designed for comfort and style, featuring high-density foam cushions and soft upholstery for your living room.', 19000, 25, 'featured_deals_img_1.png', 3, '2025-05-10 05:34:18'),
(10, 'BedRoom Decoration', 'A decorative set for your bedroom that includes stylish accessories like lamps, vases, and frames to add personality to your space.', 2500, 22, 'new_product_img_2.png', 3, '2025-05-10 05:34:18'),
(11, 'Decor for Drawing Room', 'A complete set of decorative items for your drawing room, including elegant furniture and eye-catching art pieces to enhance your living space.', 500, 16, 'new_product_img_4.png', 3, '2025-05-10 05:34:18'),
(12, 'Sofa With Table', 'A compact sofa set with a built-in coffee table, perfect for smaller living rooms or apartments, offering both style and practicality.', 35500, 8, 'sofa with table.png', 1, '2025-05-10 05:34:18'),
(13, 'Computer Table', 'A modern computer table with a minimalist design, perfect for both office and home use. Includes a dedicated area for a keyboard and mouse.', 6500, 14, 'computer table.jpg', 1, '2025-05-10 05:34:18'),
(14, 'Center Table', 'A sophisticated center table with a glass top and a sleek design that complements any living room or office setup.', 3500, 12, 'center table.webp', 3, '2025-05-10 05:34:18'),
(15, 'Bed', 'A luxurious bed with a sturdy frame and a plush mattress, designed for maximum comfort and durability for a restful sleep.', 3500, 7, 'bed.jpg', 1, '2025-05-10 05:34:18'),
(16, 'Shelf Tower', 'A stylish shelf tower that provides ample storage for books, decorations, and small appliances, ideal for any room in the house.', 7500, 15, 'shelf.jpg', 3, '2025-05-10 05:34:18'),
(17, 'Makeup Table', 'A practical and elegant makeup table with a large mirror, designed to keep your beauty essentials organized and easily accessible.', 6500, 20, 'makeuo Table.jpg', 2, '2025-05-10 05:34:18'),
(18, 'Office Chair', 'An ergonomic office chair with adjustable height and lumbar support, ideal for long hours of work or study at your desk.', 6500, 11, 'office chair.webp', 1, '2025-05-10 05:34:18'),
(19, 'Cloth Cupboard', 'A durable cloth cupboard that offers quick and easy storage for clothes, shoes, and other essentials, perfect for small spaces.', 12500, 13, 'clothvupboard.webp', 1, '2025-05-10 05:34:18'),
(20, 'Single Bed', 'A comfortable and compact single bed, ideal for children’s rooms or guest rooms, with a sturdy frame and comfortable mattress.', 1500, 77, 'single bed.jpg', 1, '2025-05-10 05:34:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `created_at`, `updated_at`) VALUES
(2, 'satkrit bhandari', 'satkrit15@gmail.com', '9767934698', '$2y$10$EJ6ilAA.llyTJWFhFt86lOWAwFOiW0ByjPtxwykQVj7VXWVbZW8Ja', '2025-05-11 11:13:09', '2025-06-12 08:03:39'),
(3, 'satkrit bhandari', 'satkrit25@gmail.com', '9818400974', '$2y$10$Kt1jPfS310xvVeqzVsqtB.R6b5T6mudTAyfKJOj.zrxSsy1FYzGiq', '2025-05-20 03:38:01', '2025-05-20 03:38:01'),
(4, 'arjun tiwari', 'arjun@gmail.com', '9874565412', '$2y$10$NjpcW8At8.w.lU39bacRC.mbTnJPSnkcW9dVltxvculQpT7lKOB8a', '2025-06-12 07:37:01', '2025-06-12 07:37:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
--
-- Database: `gym_db`
--
CREATE DATABASE IF NOT EXISTS `gym_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gym_db`;

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin_tb`
--

CREATE TABLE `adminlogin_tb` (
  `a_login_id` int(11) NOT NULL,
  `a_name` varchar(60) NOT NULL,
  `a_email` varchar(60) NOT NULL,
  `a_password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `adminlogin_tb`
--

INSERT INTO `adminlogin_tb` (`a_login_id`, `a_name`, `a_email`, `a_password`) VALUES
(1, 'Admin Kumar', 'admin@gmail.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `memberlogin_tb`
--

CREATE TABLE `memberlogin_tb` (
  `m_login_id` int(11) NOT NULL,
  `m_name` varchar(60) NOT NULL,
  `m_email` varchar(60) NOT NULL,
  `m_password` varchar(60) NOT NULL,
  `status` varchar(10) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `memberlogin_tb`
--

INSERT INTO `memberlogin_tb` (`m_login_id`, `m_name`, `m_email`, `m_password`, `status`) VALUES
(31, 'satkrit15', 'satkrit15@gmail.com', '$2y$10$ToPTSeSgDrrYHJDYKXSWAeqPCM8LaZhjDxH4fXNUxBCtv.g8R4gMm', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `submitbookingt_tb`
--

CREATE TABLE `submitbookingt_tb` (
  `Booking_id` int(11) NOT NULL,
  `member_name` varchar(90) DEFAULT NULL,
  `member_email` varchar(90) DEFAULT NULL,
  `booking_type` varchar(90) DEFAULT NULL,
  `trainer` varchar(90) DEFAULT NULL,
  `member_mobile` varchar(90) DEFAULT NULL,
  `member_add1` varchar(90) DEFAULT NULL,
  `member_date` date DEFAULT NULL,
  `subscription_months` int(11) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `subscription_start_date` date DEFAULT NULL,
  `subscription_end_date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `submitbookingt_tb`
--

INSERT INTO `submitbookingt_tb` (`Booking_id`, `member_name`, `member_email`, `booking_type`, `trainer`, `member_mobile`, `member_add1`, `member_date`, `subscription_months`, `payment_status`, `subscription_start_date`, `subscription_end_date`) VALUES
(12, 'satkrit15', 'satkrit15@gmail.com', 'Yoga class', 'Aashish Thapa (4:00AM-9:00AM)', '9818400974', 'asda', '2025-06-02', 12, 'paid', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bookings`
--

CREATE TABLE `tbl_bookings` (
  `id` int(11) NOT NULL,
  `member_email` varchar(255) NOT NULL,
  `class_id` int(11) NOT NULL,
  `class_title` varchar(255) NOT NULL,
  `class_date` date NOT NULL,
  `class_time` time NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','cancelled') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bookings`
--

INSERT INTO `tbl_bookings` (`id`, `member_email`, `class_id`, `class_title`, `class_date`, `class_time`, `booking_date`, `status`) VALUES
(1, 'satkrit15@gmail.com', 3, 'Personal Training', '2025-06-10', '14:19:00', '2025-06-01 04:30:51', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `trainer` varchar(255) DEFAULT NULL,
  `capacity` int(11) DEFAULT 20,
  `color` varchar(7) DEFAULT '#17a2b8',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_events`
--

INSERT INTO `tbl_events` (`id`, `title`, `start`, `end`, `trainer`, `capacity`, `color`, `description`, `created_at`, `updated_at`) VALUES
(3, 'Personal Training', '2025-06-10 14:19:00', '2025-06-10 16:21:00', 'Aashish Thapa', 20, '#dc3545', 'asdasd', '2025-06-01 04:30:23', '2025-06-01 04:30:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminlogin_tb`
--
ALTER TABLE `adminlogin_tb`
  ADD PRIMARY KEY (`a_email`);

--
-- Indexes for table `memberlogin_tb`
--
ALTER TABLE `memberlogin_tb`
  ADD PRIMARY KEY (`m_login_id`);

--
-- Indexes for table `submitbookingt_tb`
--
ALTER TABLE `submitbookingt_tb`
  ADD PRIMARY KEY (`Booking_id`);

--
-- Indexes for table `tbl_bookings`
--
ALTER TABLE `tbl_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking` (`member_email`,`class_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `memberlogin_tb`
--
ALTER TABLE `memberlogin_tb`
  MODIFY `m_login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `submitbookingt_tb`
--
ALTER TABLE `submitbookingt_tb`
  MODIFY `Booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_bookings`
--
ALTER TABLE `tbl_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_events`
--
ALTER TABLE `tbl_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_bookings`
--
ALTER TABLE `tbl_bookings`
  ADD CONSTRAINT `tbl_bookings_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `tbl_events` (`id`) ON DELETE CASCADE;
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"furniture\",\"table\":\"orders\"},{\"db\":\"furniture\",\"table\":\"users\"},{\"db\":\"furniture\",\"table\":\"products\"},{\"db\":\"furniture\",\"table\":\"categories\"},{\"db\":\"furniture\",\"table\":\"cart\"},{\"db\":\"furniture\",\"table\":\"admins\"},{\"db\":\"gym_db\",\"table\":\"adminlogin_tb\"},{\"db\":\"gym_db\",\"table\":\"submitbookingt_tb\"},{\"db\":\"gym_db\",\"table\":\"memberlogin_tb\"},{\"db\":\"gym_db\",\"table\":\"tbl_bookings\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2025-06-13 04:10:58', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `seedtoseason`
--
CREATE DATABASE IF NOT EXISTS `seedtoseason` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `seedtoseason`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `FirstName`, `LastName`, `Email`, `password`) VALUES
(1, 'satkrit', 'bhandari', 'satkrit15@gmail.com', 'P@ssw0rd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Database: `shrionlinefurniture`
--
CREATE DATABASE IF NOT EXISTS `shrionlinefurniture` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shrionlinefurniture`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '$2y$10$BJyreb3zF/zmETufjWzD4.rOXcbm39htNab29WWEfH0tZZWLYXZ1i', '2025-03-16 05:17:20');

-- --------------------------------------------------------

--
-- Table structure for table `screenshots`
--

CREATE TABLE `screenshots` (
  `id` int(11) NOT NULL,
  `screenshot_name` varchar(255) NOT NULL,
  `screenshot_data` longblob NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Name`, `Email`, `password`, `created_at`) VALUES
(1, 'satkrit bhandari', 'satkrit15@gmail.com', '$2y$10$AupQumsDnyCtK6qadWcdNOpBwyjXRDj.8iGwyyTEOti4Lh0XEBo4W', '2025-03-18 12:38:43'),
(2, 'Laxmi Nepal', 'laxminepal@gmail.com', '$2y$10$Ruapam1TiRSrvwnt91p35.41ssPsYuX1oZYxO3DpTQcRDo3lLYSRW', '2025-03-19 09:34:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `screenshots`
--
ALTER TABLE `screenshots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `screenshots`
--
ALTER TABLE `screenshots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
