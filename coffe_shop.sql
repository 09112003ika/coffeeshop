-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2024 at 06:50 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffe_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `category`, `name`, `price`, `description`, `image`, `stock`) VALUES
(1, 'non-coffee', 'Red Velvet Latte', '25.00', 'Red Velvet Latte combines espresso, steamed milk, and red velvet flavor, topped with cream cheese froth for a sweet, indulgent treat.', NULL, 0),
(2, 'non-coffee', 'Mojito Mocktail', '23.00', 'Mojito Mocktail is a refreshing non-alcoholic drink made with lime, mint, sugar, and soda water.', 'C:\\xampp\\htdocs\\coffeshop\\mojito.jpeg', 0),
(3, 'non-coffee', 'Mojito Mocktail', '23.00', 'Mojito Mocktail is a refreshing non-alcoholic drink made with lime, mint, sugar, and soda water.', 'C:\\xampp\\htdocs\\coffeshop\\mojito.jpeg', 0),
(6, 'non-coffee', 'Matcha Latte', '25000.00', 'Matcha latte is a creamy, slightly bitter drink made from finely ground matcha green tea powder, steamed milk, and a touch of sweetness.', 'C:\\xampp\\htdocs\\coffeshop\\images\\mojito.jpeg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email_or_phone` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email_or_phone`, `created_at`, `role`) VALUES
(1, 'ika', '$2y$10$ujsZ9xn1ccV2ql7wRIQTrOJ7dM1xHzzX.1kPZ0/8MNz0.hDmX4zem', 'ikak@gmail.com', '2024-12-28 08:11:30', 'customer'),
(2, 'fan', '$2y$10$ZmAqhKDeWq7Xxxt5ivg/k.JhFrr5Laqp.gxdH258ba/JIOir1LXJC', '086748234733', '2024-12-28 08:16:04', 'customer'),
(4, 'admin12', '$2y$10$rNI2T7DvFY1x7TT.ysIwGuCCbk4N.6m5XXoYLbfitowxRC697nllG', 'ikak123@gmail.com', '2024-12-28 12:09:02', 'admin'),
(5, 'admin3', '$2y$10$rVyHqTtQ/oYw1F9YnEusd.iZCksfIT1VIN5NYjKlcBhlqsQFK3jim', 'admin3@gmail.com', '2024-12-28 14:34:48', 'admin'),
(6, 'ikak', '$2y$10$zBBTAkwLXcmJd9VHViIDLuiyp/ZPwSv7lpaXcqv/4FSdEqnTSaR7W', 'ikakk@gmail.com', '2024-12-28 17:48:28', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email_or_phone` (`email_or_phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
