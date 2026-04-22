-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2026 at 03:56 PM
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
-- Database: `medibuddy`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `created_at`) VALUES
(1, 3, '2026-04-17 10:49:03'),
(2, 2, '2026-04-17 18:11:56'),
(3, 5, '2026-04-18 03:30:09'),
(4, 8, '2026-04-21 08:02:41'),
(5, 9, '2026-04-21 11:30:12');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Cough & Cold'),
(2, 'Fever & Pain killer'),
(3, 'Antacid'),
(4, 'Hypertension'),
(5, 'Allergy'),
(6, 'Antibiotic'),
(7, 'Anti-Inflammatory'),
(8, 'Vitamins');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `medicine_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `prescription_required` tinyint(1) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `is_prescription_required` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`medicine_id`, `name`, `brand`, `price`, `stock`, `prescription_required`, `image`, `created_at`, `category_id`, `is_prescription_required`) VALUES
(1, 'Mirakof', 'Square', 42.00, 30, 0, 'mirakof.jpg', '2026-04-17 10:47:33', 1, 0),
(2, 'Napa 500mg', 'Beximco', 12.00, 100, 0, 'napa.jpg', '2026-04-17 10:47:33', 2, 0),
(3, 'Seclo 20mg', 'Square', 10.00, 80, 0, 'seclo.jpg', '2026-04-17 10:47:33', 3, 0),
(4, 'Histacin', 'ACI', 3.00, 50, 1, 'histacin.jpg', '2026-04-17 10:47:33', 5, 0),
(5, 'Maxpro', 'Square', 32.50, 23, 0, 'max.jpeg', '2026-04-17 17:57:23', 3, 0),
(6, 'Ceevit', 'Square', 17.00, 34, 0, 'ceevit.jpg', '2026-04-19 17:51:00', 8, 0),
(8, 'Angilock', 'Square', 13.00, 26, 1, 'Angilock.jpg', '2026-04-19 17:59:56', 4, 0),
(9, 'Dexotix', 'Incepta', 40.00, 55, 0, '1776749254_dexotix.png', '2026-04-21 05:27:34', 1, 0),
(10, 'Ambrox', 'Square', 55.00, 32, 0, '1776749352_Ambrox.jpg', '2026-04-21 05:29:12', 1, 0),
(11, 'Ambrox Syrup', 'Square', 53.50, 19, 0, '1776749453_Ambroxs.jpg', '2026-04-21 05:30:53', 1, 0),
(12, 'Dextrim', 'Incepta', 47.00, 7, 0, '1776749539_Dextrim.jpg', '2026-04-21 05:32:19', 1, 0),
(13, 'Napa One', 'Beximo', 27.00, 50, 0, '1776749692_napa_one.jpg', '2026-04-21 05:34:52', 2, 0),
(14, 'Ace Plus', 'Square', 22.00, 66, 0, '1776749871_ace.jpg', '2026-04-21 05:37:51', 2, 0),
(15, 'Entacyd', 'Square', 26.00, 33, 0, '1776750156_Entacyd.png', '2026-04-21 05:42:36', 3, 0),
(16, 'Bislol', 'Square', 14.00, 65, 0, '1776750348_Bislol,png', '2026-04-21 05:45:48', 3, 0),
(17, 'Famotack', 'Square', 18.00, 10, 0, '1776750451_famotack.jpg', '2026-04-21 05:47:31', 3, 0),
(18, 'Loratin', 'Square', 23.00, 8, 1, '1776750632_Loratin.jpg', '2026-04-21 05:50:32', 5, 0),
(19, 'Rupa', 'Aristopharma', 13.00, 5, 1, '1776750791_rupa.png', '2026-04-21 05:53:11', 5, 0),
(20, 'Bextram', 'ACI', 32.00, 33, 1, '1776752671_bextram.jpg', '2026-04-21 06:24:31', 8, 0),
(21, 'Ibuprofen', 'Incepta', 37.00, 20, 1, '1776752782_ibuprofen', '2026-04-21 06:26:22', 7, 0),
(22, 'Amlodipine', 'Ibne Sina', 27.00, 7, 1, '1776752914_Amlodipine.jpg', '2026-04-21 06:28:34', 4, 0),
(23, 'Amoxicillin', 'Incepta', 15.68, 42, 1, '1776769614_amox.png', '2026-04-21 11:06:54', 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `prescription_file` varchar(255) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `address`, `phone`, `total_amount`, `payment_method`, `status`, `prescription_file`, `order_date`, `total_price`) VALUES
(1, 3, NULL, NULL, 42.00, NULL, 'Pending', NULL, '2026-04-17 10:49:08', 0.00),
(2, 2, NULL, NULL, 32.50, NULL, 'Delivered', NULL, '2026-04-17 18:11:58', 32.00),
(3, 5, NULL, NULL, 12.00, NULL, 'Pending', NULL, '2026-04-18 03:30:11', 0.00),
(4, 5, NULL, NULL, 10.00, NULL, 'Delivered', NULL, '2026-04-18 04:33:58', 0.00),
(5, 5, NULL, NULL, 32.50, NULL, 'Processing', NULL, '2026-04-18 05:09:24', 0.00),
(6, 5, NULL, NULL, 32.50, NULL, 'Pending', NULL, '2026-04-19 18:10:04', 0.00),
(7, 8, NULL, NULL, 12.00, NULL, 'Pending', NULL, '2026-04-21 08:02:59', 0.00),
(8, 8, NULL, NULL, 12.00, NULL, 'Pending', NULL, '2026-04-21 08:07:30', 0.00),
(9, 8, NULL, NULL, 12.00, NULL, 'Pending', NULL, '2026-04-21 08:07:38', 0.00),
(10, 8, NULL, NULL, 42.00, NULL, 'Pending', NULL, '2026-04-21 08:10:21', 0.00),
(11, 8, NULL, NULL, 40.00, NULL, 'Pending', NULL, '2026-04-21 08:11:01', 0.00),
(12, 8, NULL, NULL, 13.00, NULL, 'Pending', NULL, '2026-04-21 08:11:12', 0.00),
(13, 8, 'abc,1223', '01987458321', 32.50, 'COD', 'Cancelled', NULL, '2026-04-21 10:12:47', 0.00),
(14, 8, 'panchlaish\r\n\r\n', '01987458321', 95.68, 'COD', 'Confirmed', 'DIRECT-PRES-1776767414.png', '2026-04-21 10:30:14', 0.00),
(15, 8, 'oxygen', '01987458321', 10.00, 'bKash', 'Pending', NULL, '2026-04-21 11:23:14', 0.00),
(16, 8, 'oxygen', '01987458321', 102.00, 'COD', 'Pending', NULL, '2026-04-21 11:24:35', 0.00),
(17, 9, 'muradpur', '01234567892', 29.00, 'COD', 'Pending', NULL, '2026-04-21 11:30:49', 0.00),
(18, 9, 'abnm\r\n', '01982288237', 3.00, 'COD', 'Pending', NULL, '2026-04-21 13:34:14', 0.00),
(19, 5, 'nhh', '9876543087', 32.50, 'COD', 'Pending', NULL, '2026-04-22 05:51:28', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `medicine_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 42.00),
(2, 2, 5, 1, 32.50),
(3, 3, 2, 1, 12.00),
(4, 4, 3, 1, 10.00),
(5, 5, 5, 1, 32.50),
(6, 6, 5, 1, 32.50),
(7, 7, 2, 1, 12.00),
(8, 8, 2, 1, 12.00),
(9, 9, 2, 1, 12.00),
(10, 10, 1, 1, 42.00),
(11, 11, 9, 1, 40.00),
(12, 12, 8, 1, 13.00),
(13, 13, 5, 1, 32.50),
(14, 15, 3, 1, 10.00),
(15, 16, 6, 6, 17.00),
(16, 17, 6, 1, 17.00),
(17, 17, 2, 1, 12.00),
(18, 18, 4, 1, 3.00),
(19, 19, 5, 1, 32.50);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `phone`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'Admin', '01700000000', 'admin@gmail.com', '$2y$10$.i6aAj3890O39tr/qpGUKONmyFOqCeit.Ajto15s9GdUl/upag/z6', '2026-04-17 17:26:37', 'admin'),
(2, 'Sadia Akter', '122222', NULL, '$2y$10$UTTdIIpZm77qByWSNy99ZOW0/McV.N.PpKxbTGvwcHicDnnZwl6Re', '2026-04-17 09:48:52', 'user'),
(5, 'samiha', '0987654321', 'samiha@gmail.com', '$2y$10$..K0eVvVVsE25MbVuR/GxuLRfnCB8Iy1n26CzQ./zjLJTzPIGh1bS', '2026-04-18 03:29:37', 'user'),
(6, 'mainnah', '0123456789', NULL, '$2y$10$bg26dgvRb3q5GfGXbcnqLOMzytZh/nDUExC5Fs/HxkSupuLAXyVg6', '2026-04-18 03:36:21', 'user'),
(7, 'Sami', '09876543211', 'sam@gmail.com', '$2y$10$Nm43gfTzUa5HJ5r5nHAUfeZBzwin64shE04UG9WUKwoSwIlSpW7Ki', '2026-04-20 16:56:38', 'user'),
(8, 'Elisa', '01987654321', 'm12@gmail.com', '$2y$10$3kF.ZZkIfvgrOBnrWTZV6eXeXRwxkJ3rxsxPlGb/2UBwPJ1FjZw5m', '2026-04-21 07:04:11', 'user'),
(9, 'asifa', '01234567883', 'asifa@gmail.com', '$2y$10$MRItGY07ZCQal3r57D52HOeRGG9W4YA81/EtBueaABn0woLuKFU7u', '2026-04-21 11:29:23', 'user'),
(10, 'Mainnah', '01234567899', 'mainnah99@gmail.com', '$2y$10$Q4QHIFp9byDRIhuwmtBsrO/7PBVdRsrOWqW3rq26LQuOoSTY8Ak72', '2026-04-22 05:00:58', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`medicine_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`medicine_id`);

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicines_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
