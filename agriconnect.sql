-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 09:14 AM
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
-- Database: `agriconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'tyvub', 'he@gmail.com', 'fewg', '2025-04-05 22:52:38'),
(2, 'tyvub', 'he@gmail.com', 'fewg', '2025-04-05 22:52:49'),
(3, 'tyvub', 'he@gmail.com', 'fewg', '2025-04-05 22:53:18'),
(4, 'tyvub', 'he@gmail.com', 'fewg', '2025-04-05 22:53:37'),
(5, 'tyvub', 'he@gmail.com', 'fewg', '2025-04-05 22:55:32'),
(6, 'gregrfwae', 'efwaef@gmail.com', 'afef', '2025-04-05 22:56:55'),
(7, 'gregrfwae', 'efwaef@gmail.com', 'afef', '2025-04-05 22:57:46'),
(8, 'gregrfwae', 'efwaef@gmail.com', 'afef', '2025-04-05 22:58:20'),
(9, 'fewaffe', 'fewf@fefe.com', 'fefa', '2025-04-05 23:10:21'),
(10, 'fewaffe', 'fewf@fefe.com', 'fefa', '2025-04-05 23:14:26'),
(11, 'fef', 'fef@gmail.com', 'feaf', '2025-04-05 23:14:38'),
(12, 'fe', 'gfew@gmail.com', 'fewa', '2025-04-05 23:15:42'),
(13, 'e', 'e@gmail.com', 'geawg', '2025-04-05 23:18:40'),
(14, 'e', 'e@gmail.com', 'geawg', '2025-04-05 23:19:03'),
(15, 'e', 'e@gmail.com', 'geawg', '2025-04-05 23:19:04'),
(16, 'e', 'e@gmail.com', 'geawg', '2025-04-05 23:19:04'),
(17, 'e', 'e@gmail.com', 'geawg', '2025-04-05 23:19:04'),
(18, 'gresg', 'gfseg@gmail.com', 'gasgea', '2025-04-05 23:41:32'),
(19, 'geg', 'geaw@gmail.comfeag', 'gae', '2025-04-05 23:42:27');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `price`, `quantity`, `description`, `user_id`, `user_name`, `created_at`) VALUES
(1, 'potatoes', 100.00, 2, 'fewga', 3, 'rr', '2025-04-06 08:46:12'),
(2, 'fearg', 24.00, 3, '', 3, 'rr', '2025-04-06 08:55:10'),
(3, 'grapes', 323.00, 3, '', 3, 'rr', '2025-04-06 09:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `purchase_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `product_id`, `buyer_id`, `purchase_date`) VALUES
(1, 2, 4, '2025-04-06 09:07:25'),
(2, 1, 4, '2025-04-06 09:07:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(3, 'rr', 'rr@gmail.com', '$2y$10$YdOEKrOy2/ZI5iy3IRTbVu1pR1zvNo7uGpo2H0gkWGLZ4LrJmvKG.', 'farmer', '2025-04-05 19:47:08'),
(4, 'r1', 'r1@gmail.com', '$2y$10$NRcoRFMZqI/J5WzGNVbxpexGEkQ7HJIgqiYL.S576xbU96ILXowb6', 'buyer', '2025-04-05 19:47:40'),
(5, 'r2', 'r2@gmail.com', '$2y$10$n8BGvUBO3sIODePEGIEHi.1mb5OoqrDoulKzfbZSan/q8O/eiEeYa', 'buyer', '2025-04-05 19:52:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_purchase` (`product_id`,`buyer_id`),
  ADD KEY `buyer_id` (`buyer_id`);

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
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
