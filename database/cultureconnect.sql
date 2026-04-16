-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 01:18 PM
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
-- Database: `cultureconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `name`) VALUES
(1, 'London Central'),
(2, 'North London'),
(3, 'South London');

-- --------------------------------------------------------

--
-- Table structure for table `interests`
--

CREATE TABLE `interests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interests`
--

INSERT INTO `interests` (`id`, `name`) VALUES
(1, 'Visual Arts'),
(2, 'Theatre'),
(3, 'Music'),
(4, 'Digital Media'),
(5, 'Heritage'),
(6, 'Visual Arts'),
(7, 'Theatre'),
(8, 'Music'),
(9, 'Digital Media'),
(10, 'Heritage'),
(11, 'Literature'),
(12, 'Creative Writing'),
(13, 'Photography'),
(14, 'Graphic Design'),
(15, 'Performing Arts'),
(16, 'Local History');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `sme_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price_category` enum('Affordable','Moderate','Premium') DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `availability` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sme_id`, `name`, `description`, `category`, `price_category`, `price`, `availability`, `created_at`) VALUES
(1, 1, 'Painting Workshop', 'Learn painting basics', 'Art', 'Affordable', 50.00, 1, '2026-03-20 09:54:52'),
(2, 2, 'Guitar Lessons', 'Beginner guitar classes', 'Music', 'Moderate', 120.00, 1, '2026-03-20 09:54:52'),
(4, 5, 'Pixel', 'mobile', 'Technology ', 'Premium', 999.00, 1, '2026-03-26 18:39:58'),
(5, 1, 'Real world simulation Art', 'Hand made art', 'Painting', 'Affordable', 24.99, 1, '2026-04-16 07:53:30'),
(7, 9, 'Logo Design Package', 'Complete branding and logo design for startups.', 'Design', 'Premium', 499.99, 1, '2026-04-16 11:07:40'),
(8, 9, 'Social Media Templates', 'Editable templates for Instagram and Facebook.', 'Design', 'Affordable', 25.00, 1, '2026-04-16 11:07:40'),
(9, 10, 'Indoor Monstera Plant', 'Lush, healthy Monstera perfect for homes.', 'Home & Garden', 'Moderate', 45.00, 1, '2026-04-16 11:07:40'),
(10, 10, 'Herb Garden Starter Kit', 'Everything you need to grow your own herbs.', 'Home & Garden', 'Affordable', 15.50, 1, '2026-04-16 11:07:40'),
(11, 11, 'Artisan Bread Loaf', 'Freshly baked sourdough bread.', 'Food', 'Affordable', 5.50, 1, '2026-04-16 11:07:40'),
(12, 11, 'Gourmet Dinner Box', 'Meal kit for two with premium ingredients.', 'Food', 'Premium', 55.00, 1, '2026-04-16 11:07:40'),
(13, 12, 'Website Audit', 'Comprehensive SEO and UI/UX audit.', 'Technology', 'Premium', 250.00, 1, '2026-04-16 11:07:40'),
(14, 13, 'Handwoven Scarf', 'Merino wool scarf made locally.', 'Fashion', 'Moderate', 65.00, 1, '2026-04-16 11:07:40');

-- --------------------------------------------------------

--
-- Table structure for table `smes`
--

CREATE TABLE `smes` (
  `id` int(11) NOT NULL,
  `business_name` varchar(150) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `portfolio_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `smes`
--

INSERT INTO `smes` (`id`, `business_name`, `contact_email`, `phone`, `portfolio_link`, `created_at`) VALUES
(1, 'Creative Arts Studio', 'studio@example.com', '0712345678', 'http://portfolio.com', '2026-03-20 09:54:52'),
(2, 'Music Hub', 'music@example.com', '0723456789', 'http://music.com', '2026-03-20 09:54:52'),
(5, 'KD', 'dilushan@codebasys.com', '0771234567', 'https://www.kd.com', '2026-03-26 18:02:44'),
(8, 'new art', 'kopikaselvarasa1@gmail.com', '231153456', 'http://www.ac.com', '2026-04-16 10:00:13'),
(9, 'Apex Designs', 'contact@apexdesigns.com', '07800111222', 'https://apexdesigns.com', '2026-04-16 11:07:40'),
(10, 'Green Thumb Nursery', 'info@greenthumb.com', '07800222333', 'https://greenthumb.com', '2026-04-16 11:07:40'),
(11, 'Urban Bites Kitchen', 'hello@urbanbites.co.uk', '07800333444', 'https://urbanbites.co.uk', '2026-04-16 11:07:40'),
(12, 'TechNova Solutions', 'support@technova.com', '07800444555', 'https://technova.com', '2026-04-16 11:07:40'),
(13, 'Local Loom Textiles', 'hello@localloom.com', '07800555666', 'https://localloom.com', '2026-04-16 11:07:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `age_group` varchar(50) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `sme_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user','sme') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `reset_token`, `reset_expires`, `age_group`, `gender`, `area_id`, `sme_id`, `created_at`, `role`) VALUES
(1, 'John Doe', 'john@example.com', 'hashed_password', NULL, NULL, '18-25', 'Male', 1, NULL, '2026-03-20 09:54:52', 'user'),
(2, 'Jane Smith', 'jane@example.com', 'hashed_password', NULL, NULL, '26-35', 'Female', 2, NULL, '2026-03-20 09:54:52', 'user'),
(3, 'admin', 'admin@gmail.com', '$2y$10$qESiB7MYxVwG4F4IFV7df.emhlkBB4GaH4xfQflmAvGbkA7wRzua2', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-24 11:32:54', 'admin'),
(4, 'kopi', 'kopikaselvarasa81@gmail.com', '$2y$10$9sWRW5mvzllxRbwmfGlxE.gedBJ9FPbOqZVG3gFxNJjMdv.QB9LDS', NULL, NULL, '26-35', 'Female', 1, NULL, '2026-03-26 15:48:28', 'user'),
(7, 'dilu', 'dilushan@codebasys.com', '$2y$10$DeQvX/.xZCvybRvYy4n8jeAl1BufyLXytoUsHLaN/N99ezMb.Zo12', NULL, NULL, NULL, NULL, NULL, 5, '2026-03-26 18:02:44', 'sme'),
(8, 'tester', 'tester@gmail.com', '$2y$10$ET48QrNmA8IFC8SBrtoPn.NBgjjauEdKB6Ml/btzPnJrxWQYZt90K', NULL, NULL, '26-35', 'Male', 3, NULL, '2026-04-16 07:59:50', 'user'),
(11, 'dilu', 'dilu@gmail.com', '$2y$10$FatTJn1Xvz60/q1R1zXPnuohwmWQcdOuhXq9U2QnilTcmTyac5zDS', NULL, NULL, '18-25', 'Male', 1, NULL, '2026-04-16 09:42:05', 'user'),
(12, 'dilu5', 'dilushan0@gmail.com', '$2y$10$/DEINXwhdpzeWHsuZ4AKmO8UQ/OF8FrefMnbv4cZbIrngBBAZg0JW', NULL, NULL, '18-25', 'Male', 1, NULL, '2026-04-16 09:50:53', 'user'),
(13, 'dilushan1', 'kopikaselvarasa1@gmail.com', '$2y$10$/yWU.ONYUYJVwxPfuMCeUeqxc5sCPlAFlcrBEHyB7oYc.MPbSxyWW', NULL, NULL, NULL, NULL, NULL, 8, '2026-04-16 10:00:13', 'sme'),
(20, 'Alice Walker', 'alice@example.com', '$2y$10$rLyILTF3t4Zs2Ee/yfCtOuafJDqfxzVeUWwRKpC9Eoi1S3bJCkUUe', NULL, NULL, '26-35', 'Female', 1, NULL, '2026-04-16 11:07:40', 'user'),
(21, 'Bob Miller', 'bob@example.com', '$2y$10$rLyILTF3t4Zs2Ee/yfCtOuafJDqfxzVeUWwRKpC9Eoi1S3bJCkUUe', NULL, NULL, '18-25', 'Male', 2, NULL, '2026-04-16 11:07:40', 'user'),
(22, 'Charlie Davis', 'charlie@example.com', '$2y$10$rLyILTF3t4Zs2Ee/yfCtOuafJDqfxzVeUWwRKpC9Eoi1S3bJCkUUe', NULL, NULL, '36-45', 'Male', 1, NULL, '2026-04-16 11:07:40', 'user'),
(23, 'Diana Prince', 'diana@example.com', '$2y$10$rLyILTF3t4Zs2Ee/yfCtOuafJDqfxzVeUWwRKpC9Eoi1S3bJCkUUe', NULL, NULL, '46-60', 'Female', 2, NULL, '2026-04-16 11:07:40', 'user'),
(24, 'Evan Apex', 'evan@apexdesigns.com', '$2y$10$rLyILTF3t4Zs2Ee/yfCtOuafJDqfxzVeUWwRKpC9Eoi1S3bJCkUUe', NULL, NULL, '26-35', 'Male', 1, 9, '2026-04-16 11:07:40', 'sme'),
(25, 'Fiona Green', 'fiona@greenthumb.com', '$2y$10$rLyILTF3t4Zs2Ee/yfCtOuafJDqfxzVeUWwRKpC9Eoi1S3bJCkUUe', NULL, NULL, '36-45', 'Female', 2, 10, '2026-04-16 11:07:40', 'sme');

-- --------------------------------------------------------

--
-- Table structure for table `user_interests`
--

CREATE TABLE `user_interests` (
  `user_id` int(11) NOT NULL,
  `interest_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_interests`
--

INSERT INTO `user_interests` (`user_id`, `interest_id`) VALUES
(1, 1),
(1, 4),
(1, 5),
(1, 13),
(1, 16),
(4, 3),
(8, 2),
(8, 15),
(11, 4),
(12, 5),
(12, 9),
(12, 12),
(12, 14);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `vote` enum('Yes','No') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `user_id`, `product_id`, `vote`, `created_at`) VALUES
(1, 1, 1, 'Yes', '2026-03-20 09:54:52'),
(2, 2, 2, 'Yes', '2026-03-20 09:54:52'),
(3, 4, 1, 'Yes', '2026-03-26 16:16:16'),
(4, 4, 2, 'Yes', '2026-03-26 16:16:26'),
(12, 4, 4, 'No', '2026-03-26 18:41:37'),
(14, 8, 5, 'Yes', '2026-04-16 08:00:17'),
(15, 8, 4, 'No', '2026-04-16 08:00:21'),
(16, 8, 1, 'Yes', '2026-04-16 08:00:25'),
(17, 20, 7, 'Yes', '2026-04-16 11:07:40'),
(18, 20, 8, 'Yes', '2026-04-16 11:07:40'),
(19, 20, 9, 'Yes', '2026-04-16 11:07:40'),
(20, 20, 12, 'No', '2026-04-16 11:07:40'),
(21, 20, 13, 'No', '2026-04-16 11:07:40'),
(22, 20, 14, 'No', '2026-04-16 11:07:40'),
(23, 21, 7, 'Yes', '2026-04-16 11:07:40'),
(24, 21, 8, 'No', '2026-04-16 11:07:40'),
(25, 21, 9, 'Yes', '2026-04-16 11:07:40'),
(26, 21, 10, 'Yes', '2026-04-16 11:07:40'),
(27, 22, 7, 'No', '2026-04-16 11:07:40'),
(28, 22, 9, 'Yes', '2026-04-16 11:07:40'),
(29, 22, 10, 'Yes', '2026-04-16 11:07:40'),
(30, 22, 12, 'Yes', '2026-04-16 11:07:40'),
(31, 23, 7, 'Yes', '2026-04-16 11:07:40'),
(32, 23, 8, 'Yes', '2026-04-16 11:07:40'),
(33, 23, 10, 'Yes', '2026-04-16 11:07:40'),
(34, 23, 11, 'No', '2026-04-16 11:07:40'),
(35, 23, 13, 'Yes', '2026-04-16 11:07:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interests`
--
ALTER TABLE `interests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sme_id` (`sme_id`);

--
-- Indexes for table `smes`
--
ALTER TABLE `smes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `fk_user_sme` (`sme_id`);

--
-- Indexes for table `user_interests`
--
ALTER TABLE `user_interests`
  ADD PRIMARY KEY (`user_id`,`interest_id`),
  ADD KEY `interest_id` (`interest_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `interests`
--
ALTER TABLE `interests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `smes`
--
ALTER TABLE `smes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`sme_id`) REFERENCES `smes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_sme` FOREIGN KEY (`sme_id`) REFERENCES `smes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_interests`
--
ALTER TABLE `user_interests`
  ADD CONSTRAINT `user_interests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_interests_ibfk_2` FOREIGN KEY (`interest_id`) REFERENCES `interests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
