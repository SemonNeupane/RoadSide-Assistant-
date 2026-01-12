-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2026 at 11:36 PM
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
-- Database: `db_rsa`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `agent_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `approved_by_admin` int(10) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `disabled_by_admin` int(10) DEFAULT NULL,
  `disabled_remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`agent_id`, `user_id`, `status`, `approved_by_admin`, `approved_date`, `disabled_by_admin`, `disabled_remarks`) VALUES
(1, 2, 'active', NULL, '2026-01-09 14:06:37', NULL, ''),
(2, 3, 'active', 1, '2026-01-11 18:38:51', NULL, ''),
(3, 5, 'active', 1, '2026-01-11 20:21:26', NULL, ''),
(4, 6, 'active', 1, '2026-01-11 20:43:11', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `agent_location`
--

CREATE TABLE `agent_location` (
  `agent_city_id` int(10) NOT NULL,
  `agent_id` int(10) NOT NULL,
  `city_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent_location`
--

INSERT INTO `agent_location` (`agent_city_id`, `agent_id`, `city_id`) VALUES
(1, 2, 1),
(2, 3, 1),
(3, 4, 1),
(4, 1, 1),
(5, 2, 1),
(6, 3, 2),
(7, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `agent_service`
--

CREATE TABLE `agent_service` (
  `agent_service_id` int(10) NOT NULL,
  `agent_id` int(10) NOT NULL,
  `service_id` int(10) NOT NULL,
  `agent_city_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent_service`
--

INSERT INTO `agent_service` (`agent_service_id`, `agent_id`, `service_id`, `agent_city_id`) VALUES
(1, 1, 2, NULL),
(2, 1, 1, NULL),
(3, 2, 1, NULL),
(4, 3, 2, NULL),
(5, 4, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `vehicle_id` int(10) NOT NULL,
  `service_id` int(10) NOT NULL,
  `city_id` int(10) NOT NULL,
  `created_at` date NOT NULL,
  `status` enum('pending','active','completed') DEFAULT 'pending',
  `report_details` varchar(255) NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `landmark` varchar(255) NOT NULL,
  `user_location_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `agent_id`, `vehicle_id`, `service_id`, `city_id`, `created_at`, `status`, `report_details`, `completed_at`, `landmark`, `user_location_id`) VALUES
(3, 4, NULL, 7, 4, 1, '2026-01-11', 'pending', '', NULL, 'jamungachi', 9),
(4, 4, NULL, 8, 4, 1, '2026-01-11', 'pending', '', NULL, 'jamungachi', 10),
(5, 4, NULL, 9, 4, 1, '2026-01-11', 'pending', '', NULL, 'jamungachi', 11),
(6, 4, NULL, 10, 5, 1, '2026-01-11', 'pending', '', NULL, 'near biratnursing college', 13),
(7, 1, NULL, 11, 2, 1, '2026-01-11', 'pending', '', NULL, 'near biratnursing college', 14),
(8, 1, NULL, 12, 2, 1, '2026-01-11', 'pending', '', NULL, 'pipalchowk', 15),
(9, 1, NULL, 13, 1, 1, '2026-01-11', 'pending', '', NULL, 'pipalchowk', 16),
(10, 6, NULL, 14, 1, 1, '2026-01-11', 'pending', '', NULL, 'pipalchowk', 17),
(11, 6, 4, 15, 2, 1, '2026-01-11', 'active', '', NULL, 'pipalchowk', 18);

-- --------------------------------------------------------

--
-- Table structure for table `booking_requests`
--

CREATE TABLE `booking_requests` (
  `request_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected','disabled') DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `responded_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_requests`
--

INSERT INTO `booking_requests` (`request_id`, `booking_id`, `agent_id`, `status`, `created_at`, `responded_at`) VALUES
(1, 11, 3, 'disabled', '2026-01-11 19:31:36', '2026-01-11 19:35:24'),
(2, 11, 4, 'accepted', '2026-01-11 19:31:36', '2026-01-11 19:35:24'),
(3, 11, 1, 'disabled', '2026-01-11 19:31:36', '2026-01-11 19:35:24');

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `city_id` int(10) NOT NULL,
  `city_name` varchar(50) NOT NULL,
  `district_id` int(10) DEFAULT NULL,
  `district_id_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`city_id`, `city_name`, `district_id`, `district_id_id`) VALUES
(1, 'Biratnagar', 6, NULL),
(2, 'Dharan', 10, NULL),
(3, 'Itahari', 10, NULL),
(4, 'Bhadrapur', 4, NULL),
(5, 'Birgunj', 4, NULL),
(6, 'Janakpur', 2, NULL),
(7, 'Rajbiraj', 6, NULL),
(8, 'Jaleshwar', 8, NULL),
(9, 'Kathmandu', 6, NULL),
(10, 'Lalitpur', 7, NULL),
(11, 'Bhaktapur', 1, NULL),
(12, 'Hetauda', 8, NULL),
(13, 'Dhulikhel', 5, NULL),
(14, 'Pokhara', 3, NULL),
(15, 'Baglung', 1, NULL),
(16, 'Gorkha', 2, NULL),
(17, 'Besisahar', 4, NULL),
(18, 'Butwal', 11, NULL),
(19, 'Tansen', 8, NULL),
(20, 'Ghorahi', 4, NULL),
(21, 'Nepalgunj', 2, NULL),
(22, 'Birendranagar', 8, NULL),
(23, 'Jumla', 4, NULL),
(24, 'Dullu', 7, NULL),
(25, 'Dhangadhi', 8, NULL),
(26, 'Mahendranagar', 9, NULL),
(27, 'Tikapur', 8, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `district_id` int(10) NOT NULL,
  `district_name` varchar(50) DEFAULT NULL,
  `province_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`district_id`, `district_name`, `province_id`) VALUES
(1, 'Bhojpur', 1),
(2, 'Dhankuta', 1),
(3, 'Ilam', 1),
(4, 'Jhapa', 1),
(5, 'Khotang', 1),
(6, 'Morang', 1),
(7, 'Okhaldhunga', 1),
(8, 'Panchthar', 1),
(9, 'Sankhuwasabha', 1),
(10, 'Sunsari', 1),
(11, 'Taplejung', 1),
(12, 'Terhathum', 1),
(13, 'Udayapur', 1),
(14, 'Bara', 2),
(15, 'Dhanusha', 2),
(16, 'Mahottari', 2),
(17, 'Parsa', 2),
(18, 'Rautahat', 2),
(19, 'Saptari', 2),
(20, 'Sarlahi', 2),
(21, 'Siraha', 2),
(22, 'Bhaktapur', 3),
(23, 'Chitwan', 3),
(24, 'Dhading', 3),
(25, 'Dolakha', 3),
(26, 'Kabhrepalanchok', 3),
(27, 'Kathmandu', 3),
(28, 'Lalitpur', 3),
(29, 'Makwanpur', 3),
(30, 'Nuwakot', 3),
(31, 'Ramechhap', 3),
(32, 'Rasuwa', 3),
(33, 'Sindhuli', 3),
(34, 'Sindhupalchok', 3),
(35, 'Baglung', 4),
(36, 'Gorkha', 4),
(37, 'Kaski', 4),
(38, 'Lamjung', 4),
(39, 'Manang', 4),
(40, 'Mustang', 4),
(41, 'Myagdi', 4),
(42, 'Parbat', 4),
(43, 'Syangja', 4),
(44, 'Tanahun', 4),
(45, 'Arghakhanchi', 5),
(46, 'Banke', 5),
(47, 'Bardiya', 5),
(48, 'Dang', 5),
(49, 'Gulmi', 5),
(50, 'Kapilvastu', 5),
(51, 'Nawalparasi', 5),
(52, 'Palpa', 5),
(53, 'Pyuthan', 5),
(54, 'Rolpa', 5),
(55, 'Rupandehi', 5),
(56, 'Dailekh', 6),
(57, 'Dolpa', 6),
(58, 'Jajarkot', 6),
(59, 'Jumla', 6),
(60, 'Kalikot', 6),
(61, 'Mugu', 6),
(62, 'Salyan', 6),
(63, 'Surkhet', 6),
(64, 'Western Rukum', 6),
(65, 'Achham', 7),
(66, 'Baitadi', 7),
(67, 'Bajhang', 7),
(68, 'Bajura', 7),
(69, 'Dadeldhura', 7),
(70, 'Darchula', 7),
(71, 'Doti', 7),
(72, 'Kailali', 7),
(73, 'Kanchanpur', 7);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(10) NOT NULL,
  `booking_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `rating` int(5) DEFAULT NULL,
  `comments` varchar(100) DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE `province` (
  `province_id` int(10) NOT NULL,
  `province_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `province`
--

INSERT INTO `province` (`province_id`, `province_name`) VALUES
(1, 'Province No. 1'),
(2, 'Province No. 2'),
(3, 'Bagmati Province'),
(4, 'Gandaki Province'),
(5, 'Lumbini Province'),
(6, 'Karnali Province'),
(7, 'Sudurpashchim Province');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(10) NOT NULL,
  `service_name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `description`) VALUES
(1, 'Fuel Delivery', 'Deliver fuel to your vehicle at your location'),
(2, 'Battery Replacement', 'Replace your vehicle battery on site'),
(3, 'Towing Service', 'Tow your vehicle to garage safely'),
(4, 'EV Charging', 'Electric vehicle charging at your location'),
(5, 'Flat Tier', 'Puncture repair at your location'),
(6, 'Minor Repair', 'On-site minor repair services');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('admin','user','agent') DEFAULT 'user',
  `registration_date` date NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `phone`, `role`, `registration_date`, `status`) VALUES
(1, 'ramesh', 'ramesh@gmail.com', 'f5e791dd11f34b74ac1c37d6fee6d910', '9842059298', 'user', '2026-01-08', 'active'),
(2, 'haresh', 'haresh@gmail.com', '807034c386763cad02253762dda79952', '9842246773', 'agent', '2026-01-09', 'active'),
(3, 'ramesh@gmail.com', 'ramesh1@gmail.com', 'f5e791dd11f34b74ac1c37d6fee6d910', '9842059295', 'agent', '2026-01-11', 'active'),
(4, 'joe', 'joe@gmail.com', '6516f50f2a6675a8cbd4b9c805d58e19', '9842324381', 'user', '2026-01-11', 'active'),
(5, 'joee', 'joee@gmail.com', '0bde290a39ac0fc6d31a1e4abe5e58cb', '9842324371', 'agent', '2026-01-11', 'active'),
(6, 'jon', 'jon@gmail.com', '34deb9f4207a71145aa95b5a89e26d10', '9842324366', 'agent', '2026-01-11', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user_location`
--

CREATE TABLE `user_location` (
  `user_location_id` int(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `city_id` int(10) DEFAULT NULL,
  `province_id` int(10) DEFAULT NULL,
  `district_id` int(10) DEFAULT NULL,
  `landmark` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_location`
--

INSERT INTO `user_location` (`user_location_id`, `created_at`, `user_id`, `city_id`, `province_id`, `district_id`, `landmark`) VALUES
(1, '2026-01-08 19:13:37', 1, 1, 1, 6, 'pipalchowk'),
(2, '2026-01-08 19:30:33', 1, 10, 3, 27, 'near biratnursing college'),
(3, '2026-01-08 19:34:42', 1, 23, 3, 2, 'pipalchowk'),
(4, '2026-01-08 19:41:00', 1, 1, 3, 70, 'new building'),
(5, '2026-01-08 19:45:45', 1, 1, 3, 24, 'new building'),
(6, '2026-01-11 14:34:00', 1, 8, 6, 23, 'new building'),
(7, '2026-01-11 14:38:24', 3, 1, 1, 6, ''),
(8, '2026-01-11 16:21:14', 5, 1, 1, 6, ''),
(9, '2026-01-11 16:23:11', 4, 1, 1, 6, 'jamungachi'),
(10, '2026-01-11 16:24:04', 4, 1, 1, 6, 'jamungachi'),
(11, '2026-01-11 16:39:39', 4, 1, 1, 6, 'jamungachi'),
(12, '2026-01-11 16:42:15', 6, 1, 1, 6, ''),
(13, '2026-01-11 16:43:57', 4, 1, 1, 6, 'near biratnursing college'),
(14, '2026-01-11 16:54:39', 1, 1, 1, 6, 'near biratnursing college'),
(15, '2026-01-11 16:59:06', 1, 1, 1, 6, 'pipalchowk'),
(16, '2026-01-11 17:01:33', 1, 1, 1, 6, 'pipalchowk'),
(17, '2026-01-11 19:27:40', 6, 1, 1, 6, 'pipalchowk'),
(18, '2026-01-11 19:31:36', 6, 1, 1, 6, 'pipalchowk');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `vehicle_id` int(10) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `registration_no` varchar(50) NOT NULL,
  `user_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`vehicle_id`, `vehicle_type`, `model`, `registration_no`, `user_id`) VALUES
(1, 'Fuel Vehicle', 'mahendra xuv 700', '', 1),
(2, 'EV', 'byd dolphin', '', 1),
(3, 'Fuel', 'mahendra xuv 700', '', 1),
(4, 'Fuel Vehicle', 'mahendra xuv 700', '', 1),
(5, 'Fuel Vehicle', 'mahendra xuv 700', '', 1),
(6, 'Fuel Vehicle', 'byd dolphin', '', 1),
(7, 'Fuel Vehicle', 'byd dolphin', '', 4),
(8, 'EV Vehicle', 'byd dolphin', '', 4),
(9, 'EV Vehicle', 'byd dolphin', '', 4),
(10, 'Fuel Vehicle', 'mahendra xuv 700', '', 4),
(11, 'Fuel Vehicle', 'mahendra xuv 700', '', 1),
(12, 'Fuel Vehicle', 'byd dolphin', '', 1),
(13, 'Fuel Vehicle', 'honda', '', 1),
(14, 'Fuel Vehicle', 'honda', '', 6),
(15, 'Fuel Vehicle', 'honda', '', 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`agent_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_approved_by_admin` (`approved_by_admin`),
  ADD KEY `fk_disabled_by_admin` (`disabled_by_admin`);

--
-- Indexes for table `agent_location`
--
ALTER TABLE `agent_location`
  ADD PRIMARY KEY (`agent_city_id`),
  ADD KEY `agent_id` (`agent_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `agent_service`
--
ALTER TABLE `agent_service`
  ADD PRIMARY KEY (`agent_service_id`),
  ADD KEY `agent_id` (`agent_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `fk_agent_city_id` (`agent_city_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `agent_id` (`agent_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `user_location_id` (`user_location_id`);

--
-- Indexes for table `booking_requests`
--
ALTER TABLE `booking_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`),
  ADD KEY `fk_district_id` (`district_id`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`district_id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`province_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_location`
--
ALTER TABLE `user_location`
  ADD PRIMARY KEY (`user_location_id`),
  ADD KEY `fk_user_id_user` (`user_id`),
  ADD KEY `fk_city_id_city` (`city_id`),
  ADD KEY `fk_province_id_prvince` (`province_id`),
  ADD KEY `fk_district_id_district` (`district_id`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `agent_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `agent_location`
--
ALTER TABLE `agent_location`
  MODIFY `agent_city_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `agent_service`
--
ALTER TABLE `agent_service`
  MODIFY `agent_service_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `booking_requests`
--
ALTER TABLE `booking_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `district_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `province`
--
ALTER TABLE `province`
  MODIFY `province_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_location`
--
ALTER TABLE `user_location`
  MODIFY `user_location_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `vehicle_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `agent_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_approved_by_admin` FOREIGN KEY (`approved_by_admin`) REFERENCES `admin` (`admin_id`),
  ADD CONSTRAINT `fk_disabled_by_admin` FOREIGN KEY (`disabled_by_admin`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `agent_location`
--
ALTER TABLE `agent_location`
  ADD CONSTRAINT `agent_location_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`),
  ADD CONSTRAINT `agent_location_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`);

--
-- Constraints for table `agent_service`
--
ALTER TABLE `agent_service`
  ADD CONSTRAINT `agent_service_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`),
  ADD CONSTRAINT `agent_service_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`),
  ADD CONSTRAINT `fk_agent_city_id` FOREIGN KEY (`agent_city_id`) REFERENCES `agent_location` (`agent_city_id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`),
  ADD CONSTRAINT `booking_ibfk_4` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`),
  ADD CONSTRAINT `booking_ibfk_5` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`),
  ADD CONSTRAINT `booking_ibfk_6` FOREIGN KEY (`user_location_id`) REFERENCES `user_location` (`user_location_id`);

--
-- Constraints for table `booking_requests`
--
ALTER TABLE `booking_requests`
  ADD CONSTRAINT `fk_agent` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`),
  ADD CONSTRAINT `fk_booking` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`);

--
-- Constraints for table `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `fk_district_id` FOREIGN KEY (`district_id`) REFERENCES `district` (`district_id`);

--
-- Constraints for table `district`
--
ALTER TABLE `district`
  ADD CONSTRAINT `district_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `province` (`province_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_location`
--
ALTER TABLE `user_location`
  ADD CONSTRAINT `fk_city_id_city` FOREIGN KEY (`city_id`) REFERENCES `city` (`city_id`),
  ADD CONSTRAINT `fk_district_id_district` FOREIGN KEY (`district_id`) REFERENCES `district` (`district_id`),
  ADD CONSTRAINT `fk_province_id_prvince` FOREIGN KEY (`province_id`) REFERENCES `province` (`province_id`),
  ADD CONSTRAINT `fk_user_id_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
