-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 10:58 PM
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
-- Database: `praujikom_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Makanan Berat'),
(2, 'Makanan Ringan'),
(3, 'Minuman Manis'),
(4, 'Makanan Penutup'),
(5, 'Minuman Soda'),
(6, 'Minuman Isotonik'),
(7, 'Air Mineral');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `change` decimal(10,2) NOT NULL,
  `code` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `change`, `code`, `status`, `amount`, `date`) VALUES
(4, 0.00, 'ORD280420250001', 1, 45000.00, '2025-04-28'),
(5, 26000.00, 'ORD280420250001', 1, 23000.00, '2025-04-28'),
(6, 792000.00, 'ORD280420250001', 1, 8000.00, '2025-04-28'),
(7, 200000.00, 'ORD280420250001', 1, 50000.00, '2025-04-28'),
(8, 0.00, 'ORD280420250001', 1, 40000.00, '2025-04-28'),
(9, 0.00, 'ORD280420250001', 1, 150000.00, '2025-04-28'),
(10, 2000.00, 'ORD280420250001', 1, 8000.00, '2025-04-28'),
(11, 2000.00, 'ORD280420250001', 1, 8000.00, '2025-04-28'),
(12, 0.00, 'ORD-28042025-0001', 1, 40000.00, '2025-04-28'),
(13, 0.00, 'ORD-28042025-0001', 1, 40000.00, '2025-04-28'),
(14, 8000.00, 'ORD_28042025_181302', 1, 12000.00, '2025-04-28');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `qty`, `price`, `subtotal`) VALUES
(1, 4, 2, 1, 30000.00, 30000.00),
(2, 4, 1, 1, 15000.00, 15000.00),
(3, 5, 4, 2, 11500.00, 23000.00),
(4, 6, 7, 1, 8000.00, 8000.00),
(5, 7, 6, 10, 5000.00, 50000.00),
(6, 8, 7, 5, 8000.00, 40000.00),
(7, 9, 2, 5, 30000.00, 150000.00),
(8, 10, 7, 1, 8000.00, 8000.00),
(9, 11, 7, 1, 8000.00, 8000.00),
(11, 13, 7, 5, 8000.00, 40000.00),
(12, 14, 3, 1, 12000.00, 12000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `stock` int(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `options` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `price`, `description`, `stock`, `image`, `options`) VALUES
(1, 'Lunpia', 1, 15000.00, 'Lunpia lezat', 10000000, 'Lunpia.jpg', NULL),
(2, 'Ayam Goreng Laos', 1, 30000.00, 'Ayam Goreng Laos lezat & gurih', 800000000, 'Ayam Goreng Laos.jpg', NULL),
(3, 'Donat Stroberi', 4, 12000.00, 'Donat rasa Stroberi dari Dunkin Donuts', 53000000, 'Donat Stroberi.png', NULL),
(4, 'Jus Apel', 3, 11500.00, 'Jus rasa Apel dengan perisa apel', 60000000, 'Jus apel.jpg', NULL),
(5, 'Es Krim Cokelat', 4, 16000.00, 'Es Krim rasa Cokelat', 80000000, 'Es Krim Cokelat.jpg', NULL),
(6, 'Coca-Cola', 5, 5000.00, ' Minuman Kola Dingin', 250000, 'Coca-Cola.jpg', NULL),
(7, 'Aqua', 7, 8000.00, 'Aqua kemasan botol', 90000000, 'Aqua.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `profile_picture`, `created_at`, `phone_number`) VALUES
(1, 'Dio Damar Danendra', 'diodamar14102000@gmail.com', 'b!Nu$!@N202350C5', 'Admin', 'DIO DAMAR DANENDRA_Blue.jpg', '2025-04-24 04:06:48', '085772111179'),
(2, 'Gendis Ayu', 'gendisayu@gmail.com', 'k40$Fr0z3n', 'Kasir', 'Elsa.png', '2025-04-24 04:08:52', '087871095885'),
(3, 'Faiha Wanda Nabilah', 'faihafaiha.email@gmail.com', 'c@r!p0k3m0N', 'Kasir', 'Pokemon.jpg', '2025-04-25 01:11:48', '0818718067');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_id` (`order_id`),
  ADD KEY `products_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id_to_categories_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `orders_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `category_id_to_categories_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
