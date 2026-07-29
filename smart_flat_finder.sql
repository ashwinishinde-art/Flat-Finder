-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2026 at 11:47 PM
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
-- Database: `smart_flat_finder`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `room_id`, `customer_id`, `booking_date`, `status`) VALUES
(1, 1, 3, '2026-07-28', 'Approved'),
(2, 2, 5, '2026-07-28', 'Approved'),
(3, 4, 3, '2026-07-29', ''),
(4, 4, 3, '2026-07-29', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, '1 Bhk'),
(2, '2 Bhk'),
(3, '3 Bhk'),
(4, 'PG'),
(5, 'Single room'),
(6, 'Luxary Flat'),
(7, '1 RK');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `area` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `city`, `area`) VALUES
(1, 'Pune', 'Hadapsar'),
(2, 'Pune', 'Katraj'),
(3, 'Pune', 'Bibewadi'),
(4, 'Pune', 'Swargate'),
(5, 'Pune', 'Karvenagar'),
(6, 'Pune', 'Hinjewadi'),
(7, 'Pune', 'kondhwa'),
(8, 'Mumbai', 'Andheri East'),
(9, 'Mumbai', 'Andheri West'),
(10, 'Mumbai', 'Marol'),
(11, 'Mumbai', 'Bandra'),
(12, 'Mumbai', 'Goregaon'),
(13, 'Mumbai', 'Vile Parle'),
(14, 'Nanded', 'Deglur naka'),
(15, 'Nanded', 'Vazirabad'),
(16, 'Nanded', 'Hingoli Gate'),
(17, 'Nanded', 'Shivaji Nagar'),
(18, 'Nanded', 'Aanand nagar'),
(19, 'Latur', 'Narayan Nagar'),
(20, 'Latur', 'Ausa Road'),
(21, 'Latur', 'Ambajogai Road');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `room_title` varchar(200) NOT NULL,
  `room_type` varchar(100) NOT NULL,
  `rent` decimal(10,2) NOT NULL,
  `address` text DEFAULT NULL,
  `facilities` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Available','Booked') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `owner_id`, `category_id`, `location_id`, `room_title`, `room_type`, `rent`, `address`, `facilities`, `description`, `image`, `status`) VALUES
(1, 2, 1, 1, 'Flat', 'Room', 12000.00, NULL, NULL, '.', '1785307861_room3.jpg', 'Booked'),
(2, 4, 4, 5, 'Room', 'Room', 8000.00, NULL, NULL, 'Water Cooling(Filter)\r\nCctv Protection\r\nWashing Machine\r\nAlternate Day Cleaning', '1785307731_room2.jpg', 'Booked'),
(3, 9, 1, 2, 'Shri Ganesh Apartments', 'Room', 9000.00, NULL, NULL, 'Wifi , 24/7 water supply', '1785315632_1270.jpg', 'Available'),
(4, 9, 2, 2, 'Shri Ganesh Apartment', 'Room', 15000.00, NULL, NULL, 'WIFI and Water facilities', '1785315703_9670.png', 'Booked');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','owner','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `password`, `role`, `created_at`, `status`) VALUES
(1, 'Administrator', 'admin@gmail.com', '9736637673', '$2y$10$NN.FA4MrBPuPaXHyGGNj7eDBkdbWCJdSwQwT/oMQdUECkoz9EAygC', 'admin', '2026-07-22 16:16:07', 'Approved'),
(2, 'Parth Bhatkar', 'parthbhatkar@gmail.com', '9878968966', '$2y$10$3SaEXhnLdN5qm9uafEazoensTmdQgj6YjHNsqc65DLKR5tU6Ic.na', 'owner', '2026-07-28 13:38:14', 'Approved'),
(3, 'Durgesh Dahatonde', 'durgeshdahatonde@gmail.com', '8678678737', '$2y$10$DjB7..P6gRO96d0NJdjEReafxgadOA/VuTulwjcE6cUl3A3oeROg6', 'customer', '2026-07-28 13:44:03', 'Approved'),
(4, 'Rohit Kattalwad', 'rohitkattalwad@gmail.com', '8478436767', '$2y$10$fFDTsxo2IsJMI2v7R1nrSOffnUx6oM5Hq1E7G/D/hOdJT7iKFOp5m', 'owner', '2026-07-28 14:01:58', 'Approved'),
(5, 'Deep Rehpade', 'deeprehpade@gmail.com', '9783755325', '$2y$10$9upXp1YUqG2C/9GVvoy4T.XzQfusqRITY1mkDx0KQ5v.seZbbGdu6', 'customer', '2026-07-28 14:07:54', 'Approved'),
(6, 'aryash kapoor', 'aryash@gmail.com', '7058247208', '$2y$10$JysSV61hrOwcNnE0gaf0EeUZ9R7m1hHgC5rnnWrHyHzUNg9Xo9BkC', 'customer', '2026-07-29 05:13:33', 'Approved'),
(7, 'vikanshu singhania', 'vikanshu@gmail.com', '7532147896', '$2y$10$sFON/ZIaaC2/aFWw9.IzGOxnwVo12N91InsniGkGKFGupqQZ0QmDy', 'owner', '2026-07-29 05:20:17', 'Pending'),
(8, 'Geetam Gyan', 'geetamgyan@gmail.com', '9836554523', '$2y$10$A9IRh69rRcDil75qJ6k7t.E.Jn6MpPWL3GRzvsLf1O2.zWGxgZ7SW', 'customer', '2026-07-29 05:50:56', 'Approved'),
(9, 'Rohit Kattalwad', 'kattalwadrohit@gmail.com', '9403130434', '$2y$10$AXIBGj.7Tl55BlQ222DwE.R8VDt6EAYsFvlD38EtnaRrB6Oz.RH06', 'owner', '2026-07-29 08:57:20', 'Approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
