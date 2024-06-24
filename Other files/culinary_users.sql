-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gazdă: localhost
-- Timp de generare: iun. 24, 2024 la 09:36 PM
-- Versiune server: 10.4.28-MariaDB
-- Versiune PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `culinary_users`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL COMMENT 'ID of the admin',
  `username` varchar(255) NOT NULL COMMENT 'Admin''s username',
  `password` varchar(255) NOT NULL COMMENT 'Admin''s passwrod'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`) VALUES
(1, '1', '1');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `Preferences`
--

CREATE TABLE `Preferences` (
  `id` int(10) NOT NULL,
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `Preferences`
--

INSERT INTO `Preferences` (`id`, `name`) VALUES
(1, 'Vegan'),
(2, 'Non-dairy'),
(3, 'Meat'),
(4, 'Seafood'),
(5, 'Fruits'),
(6, 'Vegetarian'),
(7, 'Junk'),
(8, 'Pasta'),
(9, 'Gluten-Free'),
(10, 'Keto'),
(11, 'Low-Carb'),
(12, 'Paleo'),
(13, 'Low-Sodium'),
(14, 'Sugar-Free'),
(15, 'High-Protein'),
(16, 'Diabetic-Friendly'),
(17, 'Dairy-Free'),
(18, 'Organic'),
(19, 'Halal'),
(20, 'Kosher'),
(21, 'Whole30'),
(22, 'Low-Fat'),
(23, 'Nut-Free'),
(24, 'Soy-Free'),
(25, 'Shellfish-Free'),
(26, 'Pescatarian'),
(27, 'Flexitarian'),
(28, 'FODMAP-Friendly'),
(29, 'Mediterranean'),
(30, 'Plant-Based');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL COMMENT 'A unique identifier for each user.',
  `username` varchar(20) NOT NULL COMMENT 'Text set by the user to represent themselves.',
  `password` varchar(100) NOT NULL COMMENT 'The key to access a user''s account.',
  `email` varchar(40) NOT NULL COMMENT 'Email of a user to contact them.',
  `fname` varchar(20) NOT NULL COMMENT 'Fist name of a user.',
  `lname` varchar(20) NOT NULL COMMENT 'Last name of a user.',
  `age` int(11) NOT NULL COMMENT 'Age of user.',
  `gender` int(11) NOT NULL COMMENT 'Gender of a user.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `fname`, `lname`, `age`, `gender`) VALUES
(1, '1', '$2y$10$voB6pSG54mc0WpnLtZqFX./dei09mWfkZSTlphwoWYs9wUnpOT7L.', '1', '1', '1', 1, 1),
(12, '3', '$2y$10$4Tby3sAUwwVxpLMT2Gako.gi1EBheMmvJtxDMGMwLeim4IuaPgNKW', 'a@a.c', 'Ds', 'das', 1, 1),
(14, '345', '$2y$10$Yd.MAMAHhaStz9mYkvgZgeRyvNc.djRKuH4uH/SRdvkE5zCPWRAnO', 'd@z.c', 'sda', 'dsa', 21, 1),
(15, '41', '$2y$10$S3ELEs1skqsUdte3lF62HeOoiVHaxms75PqXolt8m77kxcaMxltU2', 'dsa@dsa.c', 'A', 'd', 21, 1),
(16, 'Alex', '$2y$10$RINGfrw/LbzHhecM.M6Vc.wVHGwnLR8f27KDAifpCp0prm4c0RVTm', 'biliuti@gmail.com', 'Alex', 'Biliuti', 22, 1),
(17, 'Andrei', '$2y$10$0d4URU2Gw9PkfSXAtQyVzeRCa1aRObcTy12LhnULV69bVu5K6xsx6', 'bili@gmail.com', 'Andrei', 'Biliuti', 22, 1);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` int(11) NOT NULL COMMENT 'A unique identifier for each preference.',
  `user_id` int(11) NOT NULL COMMENT 'The unique identifier for each user in table users.',
  `preference_id` int(11) NOT NULL COMMENT 'The culinary preference a user has'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `user_preferences`
--

INSERT INTO `user_preferences` (`id`, `user_id`, `preference_id`) VALUES
(35, 1, 10),
(37, 1, 16),
(15, 14, 3),
(16, 14, 6),
(17, 14, 7),
(18, 15, 4),
(19, 15, 5),
(38, 16, 1),
(39, 16, 6),
(40, 17, 4),
(41, 17, 5);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `user_shopping_list`
--

CREATE TABLE `user_shopping_list` (
  `item_id` int(11) NOT NULL COMMENT 'Item id from shopping list',
  `user_id` int(11) NOT NULL COMMENT 'User''s ID from users table',
  `item_name` varchar(255) NOT NULL COMMENT 'The item from the shopping list',
  `quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Quantity of items'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `user_shopping_list`
--

INSERT INTO `user_shopping_list` (`item_id`, `user_id`, `item_name`, `quantity`) VALUES
(25, 1, '2 burgers vegetaliens', 1),
(26, 15, 'Tuna Chunks In Brine', 1),
(27, 1, 'Brownie Mix, Choc Flavoured', 2),
(28, 1, 'hamburger', 1);

--
-- Indexuri pentru tabele eliminate
--

--
-- Indexuri pentru tabele `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexuri pentru tabele `Preferences`
--
ALTER TABLE `Preferences`
  ADD PRIMARY KEY (`id`);

--
-- Indexuri pentru tabele `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexuri pentru tabele `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`preference_id`),
  ADD KEY `FOREIGN KEY user_id` (`user_id`) USING BTREE,
  ADD KEY `fk_preferinte_id` (`preference_id`);

--
-- Indexuri pentru tabele `user_shopping_list`
--
ALTER TABLE `user_shopping_list`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fk_user_shopping_list_user_id` (`user_id`);

--
-- AUTO_INCREMENT pentru tabele eliminate
--

--
-- AUTO_INCREMENT pentru tabele `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of the admin', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pentru tabele `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'A unique identifier for each user.', AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pentru tabele `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'A unique identifier for each preference.', AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pentru tabele `user_shopping_list`
--
ALTER TABLE `user_shopping_list`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Item id from shopping list', AUTO_INCREMENT=29;

--
-- Constrângeri pentru tabele eliminate
--

--
-- Constrângeri pentru tabele `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `fk_preferinte_id` FOREIGN KEY (`preference_id`) REFERENCES `Preferences` (`id`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constrângeri pentru tabele `user_shopping_list`
--
ALTER TABLE `user_shopping_list`
  ADD CONSTRAINT `fk_user_shopping_list_user_id` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
