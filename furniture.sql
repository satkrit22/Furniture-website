-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 04:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `furniture`
--

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
(1, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', 'Aquarium Stand (2)', 3590, 'pending', '2025-05-10 13:55:45'),
(2, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', 'Aquarium Stand (2)', 3590, 'pending', '2025-05-10 13:56:55'),
(3, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', 'Aquarium Stand (2)', 3590, 'pending', '2025-05-10 14:01:12'),
(4, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', 'Aquarium Stand (2)', 3590, 'pending', '2025-05-10 14:02:09'),
(5, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', 'Aquarium Stand (2)', 3590, 'pending', '2025-05-10 14:03:48'),
(6, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', '', 3200, 'pending', '2025-05-10 14:06:34'),
(7, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', 'Aquarium Stand (2)', 3200, 'pending', '2025-05-10 14:08:40'),
(8, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', 'Aquarium Stand (2)', 3200, 'pending', '2025-05-10 14:22:07'),
(9, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', '2', 31200, 'pending', '2025-05-10 14:33:06'),
(10, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', '2', 31200, 'pending', '2025-05-10 14:35:01'),
(11, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', 'Gaming Chair (2)', 31200, 'pending', '2025-05-10 14:39:31'),
(12, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', 'Gaming Chair (2)', 31200, 'pending', '2025-05-10 14:41:06'),
(13, 1, 'Satkrit Bhandari', '9818400974', 'satkritbhandari11@gmail.com', 'cash_on_delivery', 'Kathmandu, kathmandu', 'Sofa With Table (4), Study Table (1)', 142700, 'pending', '2025-05-10 14:41:51');

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
(1, 'Study Table', 'A versatile study table designed for modern workspaces, featuring ample surface area and storage options for books and gadgets.', 500, 198, 'small study table.jpg', 1, '2025-05-10 05:34:18'),
(2, 'Dining Table', 'A sleek and sturdy dining table that provides a perfect spot for family meals, made from premium materials for durability.', 20000, 150, 'dining table.jpeg', 2, '2025-05-10 05:34:18'),
(3, 'Aquarium Stand', 'A stable and stylish aquarium stand that accommodates small to medium-sized tanks, designed for both aesthetics and functionality.', 1500, 28, 'aquorium.jpg', 1, '2025-05-10 05:34:18'),
(4, 'Gaming Chair', 'An ergonomic gaming chair designed for long gaming sessions, featuring adjustable armrests and a comfortable reclining function.', 15500, 25, 'gaming chair.webp', 1, '2025-05-10 05:34:18'),
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
(16, 'Shelf Tower', 'A stylish shelf tower that provides ample storage for books, decorations, and small appliances, ideal for any room in the house.', 7500, 9, 'shelf.jpg', 3, '2025-05-10 05:34:18'),
(17, 'Makeup Table', 'A practical and elegant makeup table with a large mirror, designed to keep your beauty essentials organized and easily accessible.', 6500, 20, 'makeuo Table.jpg', 2, '2025-05-10 05:34:18'),
(18, 'Office Chair', 'An ergonomic office chair with adjustable height and lumbar support, ideal for long hours of work or study at your desk.', 6500, 11, 'office chair.webp', 1, '2025-05-10 05:34:18'),
(19, 'Cloth Cupboard', 'A durable cloth cupboard that offers quick and easy storage for clothes, shoes, and other essentials, perfect for small spaces.', 12500, 13, 'clothvupboard.webp', 1, '2025-05-10 05:34:18'),
(20, 'Single Bed', 'A comfortable and compact single bed, ideal for children’s rooms or guest rooms, with a sturdy frame and comfortable mattress.', 1500, 6, 'single bed.jpg', 1, '2025-05-10 05:34:18');

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
(1, 'Satkrit Bhandari', 'satkritbhandari11@gmail.com', '9818400974', '$2y$10$gaZe8UFr4Bglo8L08gNxHOf/7PoX5s9W9UNPW/2lUl1LNrIRKJbti', '2025-05-10 04:08:20', '2025-05-10 04:08:20');

--
-- Indexes for dumped tables
--

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
