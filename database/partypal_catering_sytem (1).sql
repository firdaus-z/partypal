-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3310
-- Generation Time: Aug 20, 2024 at 12:38 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `partypal_catering_sytem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` enum('admin','','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `email`, `password`, `role`) VALUES
(1, 'amina@gmail.com', '1f02707a142d8d10b265d4d613efa194212c1cc8', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `caterers`
--

CREATE TABLE `caterers` (
  `catererID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `address` varchar(11) NOT NULL,
  `password` varchar(200) NOT NULL,
  `phone` int(11) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `caterers`
--

INSERT INTO `caterers` (`catererID`, `Name`, `address`, `password`, `phone`, `email`) VALUES
(15, 'Fat-hiya', 'Amani', '1f02707a142d8d10b265d4d613efa194212c1cc8', 776563999, 'fat-hiya@gmail.com'),
(16, 'fahima', 'mahonda', '1f02707a142d8d10b265d4d613efa194212c1cc8', 625607213, 'fahima@gmail.com'),
(17, 'Alannour ', 'mlandege', '1f02707a142d8d10b265d4d613efa194212c1cc8', 776234578, 'alannour@gmail.com'),
(18, 'hotpot', 'bububu', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 777712341, 'hotpot@gmail.com'),
(20, 'Fatma', 'Nungwi', '5e927503d30f50bd44c9a31c6625984c442b78ae', 775234516, 'Fatma@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `password` varchar(50) NOT NULL,
  `phone` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `role` enum('customer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `firstName`, `lastName`, `password`, `phone`, `email`, `address`, `role`) VALUES
(1, 'Firdaus', 'Mohamed', '1f02707a142d8d10b265d4d613efa194212c1cc8', 687142051, 'firdaus@gmail.com', 'fuoni', 'customer'),
(2, 'Farhat', 'Abdallah', '1f02707a142d8d10b265d4d613efa194212c1cc8', 688657643, 'farhat@gmail.com', 'jan\'gombe', 'customer'),
(3, 'Hanan', 'masoud', '1f02707a142d8d10b265d4d613efa194212c1cc8', 776563999, 'hananmasoud@gmail.com', 'beitras', 'customer'),
(4, 'Khairat', 'Issa', '7c222fb2927d828af22f592134e8932480637c0d', 626204526, 'khairat@gmail.com', 'nyarugusu', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `customerName` varchar(20) NOT NULL,
  `comment` varchar(100) NOT NULL,
  `feedbackDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackID`, `OrderID`, `customerName`, `comment`, `feedbackDate`) VALUES
(1, 2, '', 'it was good', '2024-08-16 13:15:54'),
(2, 9, '', 'it was awesome', '2024-08-16 16:17:11'),
(3, 3, 'Firdaus mohammed', 'it was awesome', '2024-08-17 11:51:53');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `FoodID` int(11) NOT NULL,
  `catererID` int(11) NOT NULL,
  `FoodName` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `Description` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`FoodID`, `catererID`, `FoodName`, `price`, `Description`, `quantity`) VALUES
(2, 15, 'pilau', '0.00', 'pilau nyama', 1),
(3, 15, 'wali kuku', '2000.00', 'kilo moja ina chukua wat wa 5', 1),
(4, 16, 'wali kuku', '2000.00', 'kilo moja watu 5', 1),
(5, 16, 'wali kuku', '2000.00', 'kwa watu 2', 1),
(6, 16, 'wali kuku', '200.00', 'kilo 1 watu 5', 1),
(7, 16, 'biriani nyama', '100000.00', 'ni kwa ajili ya watu 5', 1),
(8, 17, 'biriani kuku', '2000.00', 'ikg for 7 people', 1),
(11, 18, 'chipsi kuku', '2000.00', 'chipsi na mshkaki', 1),
(13, 16, 'biriani', '2000.00', 'biriani nya tsh 200 one pish in chukua wat 5 kwa pish moja', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orderfood`
--

CREATE TABLE `orderfood` (
  `OrderFoodID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `FoodID` int(11) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderfood`
--

INSERT INTO `orderfood` (`OrderFoodID`, `OrderID`, `FoodID`, `totalPrice`, `quantity`) VALUES
(9, 1, 2, '0.00', 1),
(10, 2, 2, '0.00', 1),
(11, 2, 3, '0.00', 1),
(12, 2, 2, '0.00', 1),
(13, 2, 4, '0.00', 1),
(14, 3, 2, '0.00', 1),
(15, 4, 2, '0.00', 1),
(16, 4, 2, '0.00', 1),
(17, 5, 2, '0.00', 1),
(18, 6, 2, '0.00', 1),
(19, 6, 2, '0.00', 1),
(20, 7, 2, '0.00', 1),
(21, 8, 2, '0.00', 1),
(22, 8, 2, '0.00', 1),
(23, 8, 2, '0.00', 1),
(24, 8, 2, '0.00', 1),
(25, 8, 2, '0.00', 1),
(28, 21, 2, '0.00', 1),
(29, 21, 3, '0.00', 1),
(30, 21, 4, '0.00', 1),
(31, 21, 5, '0.00', 1),
(32, 21, 6, '0.00', 1),
(33, 21, 7, '0.00', 1),
(34, 21, 8, '0.00', 1),
(36, 55, 2, '0.00', 1),
(37, 55, 2, '0.00', 1),
(38, 55, 11, '0.00', 1),
(39, 55, 2, '0.00', 1),
(40, 56, 3, '0.00', 1),
(41, 57, 3, '0.00', 1),
(42, 58, 11, '0.00', 1),
(43, 59, 2, '0.00', 1),
(44, 59, 2, '0.00', 1),
(45, 59, 3, '0.00', 1),
(46, 59, 11, '0.00', 1),
(47, 60, 2, '0.00', 1),
(48, 60, 2, '0.00', 1),
(49, 61, 2, '0.00', 1),
(50, 62, 11, '0.00', 3),
(51, 62, 3, '0.00', 7),
(52, 63, 11, '0.00', 4),
(53, 70, 11, '8000.00', 4),
(54, 71, 8, '8000.00', 4),
(55, 72, 3, '22000.00', 11),
(56, 73, 3, '34000.00', 17),
(57, 74, 2, '0.00', 1),
(58, 75, 8, '6000.00', 3),
(59, 76, 13, '8000.00', 4),
(60, 77, 2, '0.00', 1),
(61, 78, 3, '14000.00', 7),
(62, 79, 3, '8000.00', 4),
(63, 80, 2, '0.00', 1),
(64, 81, 3, '16000.00', 8),
(65, 82, 2, '0.00', 1),
(66, 83, 2, '0.00', 1),
(67, 83, 2, '0.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `totalPrice` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `Status` enum('pending','Received','','') NOT NULL DEFAULT 'pending',
  `paymentStatus` enum('paid','unpaid','','') NOT NULL DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `CustomerID`, `OrderDate`, `totalPrice`, `quantity`, `Status`, `paymentStatus`) VALUES
(1, 1, '2024-08-13 12:19:05', '0.00', 1, '', 'paid'),
(2, 2, '2024-08-14 09:37:20', '0.00', 1, 'Received', 'paid'),
(3, 1, '2024-08-14 10:34:26', '0.00', 1, '', 'paid'),
(4, 1, '2024-08-14 10:45:31', '0.00', 1, '', 'paid'),
(5, 1, '2024-08-14 10:49:33', '0.00', 1, '', 'paid'),
(6, 1, '2024-08-14 10:52:42', '0.00', 1, 'pending', 'paid'),
(7, 3, '2024-08-14 11:34:12', '0.00', 1, '', 'paid'),
(8, 3, '2024-08-14 11:49:39', '0.00', 1, 'pending', 'paid'),
(9, 2, '2024-08-14 12:39:24', '0.00', 1, 'pending', 'paid'),
(10, 2, '2024-08-14 12:40:55', '0.00', 1, 'pending', 'paid'),
(11, 2, '2024-08-14 12:41:36', '0.00', 1, 'pending', 'paid'),
(12, 2, '2024-08-14 12:42:04', '0.00', 1, 'pending', 'paid'),
(13, 2, '2024-08-14 12:49:06', '0.00', 1, 'pending', 'paid'),
(14, 2, '2024-08-14 12:55:13', '0.00', 1, 'pending', 'paid'),
(15, 2, '2024-08-14 12:57:05', '0.00', 1, 'pending', 'paid'),
(16, 2, '2024-08-14 12:58:09', '0.00', 1, 'pending', 'paid'),
(17, 2, '2024-08-14 13:01:55', '0.00', 1, 'pending', 'paid'),
(18, 2, '2024-08-14 13:48:14', '0.00', 1, 'pending', 'paid'),
(19, 2, '2024-08-14 14:16:12', '0.00', 1, 'pending', 'paid'),
(20, 2, '2024-08-14 14:17:09', '0.00', 1, 'pending', 'paid'),
(21, 2, '2024-08-14 14:27:45', '0.00', 1, 'Received', 'paid'),
(22, 2, '2024-08-14 14:59:51', '0.00', 1, 'pending', 'paid'),
(23, 2, '2024-08-14 15:00:07', '0.00', 1, 'pending', 'paid'),
(24, 2, '2024-08-14 15:20:51', '0.00', 1, 'pending', 'paid'),
(25, 2, '2024-08-14 15:23:45', '0.00', 1, 'pending', 'paid'),
(26, 2, '2024-08-14 18:13:56', '0.00', 1, 'pending', 'paid'),
(27, 2, '2024-08-15 08:37:45', '0.00', 1, 'pending', 'paid'),
(28, 2, '2024-08-15 08:38:05', '0.00', 1, 'pending', 'paid'),
(29, 2, '2024-08-15 08:38:50', '0.00', 1, 'pending', 'paid'),
(30, 2, '2024-08-15 09:00:29', '0.00', 1, 'pending', 'paid'),
(31, 2, '2024-08-15 09:02:11', '0.00', 1, 'pending', 'paid'),
(32, 2, '2024-08-15 09:07:21', '0.00', 1, 'pending', 'paid'),
(33, 2, '2024-08-15 09:09:39', '0.00', 1, 'pending', 'paid'),
(34, 2, '2024-08-15 09:22:14', '0.00', 1, 'pending', 'paid'),
(35, 2, '2024-08-15 09:50:01', '0.00', 1, 'pending', 'paid'),
(36, 2, '2024-08-15 09:53:33', '0.00', 1, 'pending', 'paid'),
(37, 2, '2024-08-15 09:56:22', '0.00', 1, 'pending', 'paid'),
(38, 2, '2024-08-15 09:56:33', '0.00', 1, 'pending', 'paid'),
(39, 2, '2024-08-15 09:56:36', '0.00', 1, 'pending', 'paid'),
(40, 2, '2024-08-15 09:56:52', '0.00', 1, 'pending', 'paid'),
(41, 2, '2024-08-15 09:56:53', '0.00', 1, 'pending', 'paid'),
(42, 2, '2024-08-15 09:56:53', '0.00', 1, 'pending', 'paid'),
(43, 2, '2024-08-15 09:56:53', '0.00', 1, 'pending', 'paid'),
(44, 2, '2024-08-15 09:56:53', '0.00', 1, 'pending', 'paid'),
(45, 2, '2024-08-15 09:56:54', '0.00', 1, 'pending', 'paid'),
(46, 2, '2024-08-15 09:56:54', '0.00', 1, 'pending', 'paid'),
(47, 2, '2024-08-15 09:57:36', '0.00', 1, 'pending', 'paid'),
(48, 2, '2024-08-15 09:58:05', '0.00', 1, 'pending', 'paid'),
(49, 2, '2024-08-15 10:40:47', '0.00', 1, 'pending', 'paid'),
(50, 2, '2024-08-15 10:40:54', '0.00', 1, 'pending', 'paid'),
(51, 2, '2024-08-15 10:40:55', '0.00', 1, 'pending', 'paid'),
(52, 2, '2024-08-15 11:29:03', '0.00', 1, 'pending', 'paid'),
(53, 2, '2024-08-15 12:34:52', '0.00', 1, 'pending', 'paid'),
(54, 2, '2024-08-15 12:47:10', '0.00', 1, 'pending', 'paid'),
(55, 2, '2024-08-15 12:47:54', '0.00', 1, 'pending', 'paid'),
(56, 2, '2024-08-15 12:52:32', '0.00', 1, 'pending', 'paid'),
(57, 2, '2024-08-15 13:00:19', '0.00', 1, 'pending', 'paid'),
(58, 3, '2024-08-15 13:48:27', '0.00', 1, 'pending', 'paid'),
(59, 3, '2024-08-15 14:13:48', '0.00', 1, 'pending', 'paid'),
(60, 2, '2024-08-17 07:45:31', '0.00', 1, 'pending', 'unpaid'),
(61, 2, '2024-08-17 08:19:22', '0.00', 1, 'pending', 'unpaid'),
(62, 3, '2024-08-17 09:30:26', '0.00', 1, 'pending', 'unpaid'),
(63, 3, '2024-08-17 09:44:23', '0.00', 1, 'pending', 'unpaid'),
(64, 2, '2024-08-17 10:29:09', '0.00', 1, '', 'unpaid'),
(65, 2, '2024-08-17 10:38:12', '0.00', 1, '', 'unpaid'),
(66, 2, '2024-08-17 10:38:18', '0.00', 1, '', 'unpaid'),
(67, 2, '2024-08-17 10:45:47', '8000.00', 1, 'pending', 'unpaid'),
(68, 2, '2024-08-17 10:45:48', '8000.00', 1, 'pending', 'unpaid'),
(69, 2, '2024-08-17 10:45:50', '8000.00', 1, 'pending', 'unpaid'),
(70, 2, '2024-08-17 10:46:39', '8000.00', 1, 'pending', 'unpaid'),
(71, 1, '2024-08-17 10:54:03', '8000.00', 1, 'Received', 'paid'),
(72, 1, '2024-08-17 11:05:32', '22000.00', 1, 'pending', 'paid'),
(73, 1, '2024-08-17 11:22:51', '34000.00', 17, '', 'paid'),
(74, 2, '2024-08-17 12:58:06', '0.00', 1, '', 'unpaid'),
(75, 2, '2024-08-17 14:27:17', '6000.00', 3, 'Received', 'paid'),
(76, 2, '2024-08-18 16:59:23', '8000.00', 4, 'Received', 'paid'),
(77, 2, '2024-08-18 19:04:36', '0.00', 1, '', 'unpaid'),
(78, 2, '2024-08-18 19:14:41', '14000.00', 7, '', 'paid'),
(79, 2, '2024-08-18 19:17:11', '8000.00', 4, '', 'unpaid'),
(80, 1, '2024-08-19 10:53:37', '0.00', 1, '', 'unpaid'),
(81, 1, '2024-08-19 12:20:32', '16000.00', 8, '', 'unpaid'),
(82, 1, '2024-08-19 12:39:39', '0.00', 1, '', 'unpaid'),
(83, 1, '2024-08-19 12:52:25', '0.00', 2, '', 'unpaid');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `paymentDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Amount` decimal(10,2) NOT NULL,
  `paymentMethod` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentID`, `OrderID`, `paymentDate`, `Amount`, `paymentMethod`) VALUES
(4, 56, '2024-08-15 11:52:00', '2.00', 'Credit Card'),
(5, 57, '2024-08-15 12:00:00', '2.00', 'Credit Card'),
(6, 58, '2024-08-15 12:48:00', '2.00', 'Credit Card'),
(7, 59, '2024-08-15 13:13:00', '4.00', 'Credit Card'),
(8, 60, '2024-08-17 06:45:00', '0.00', 'PayPal'),
(9, 62, '2024-08-17 08:30:00', '20.00', 'Debit Card'),
(10, 63, '2024-08-17 08:44:00', '8.00', 'Credit Card'),
(11, 71, '2024-08-17 09:54:00', '8.00', 'Credit Card'),
(12, 72, '2024-08-17 10:05:00', '22.00', 'Credit Card'),
(13, 73, '2024-08-17 10:22:00', '34.00', 'Credit Card'),
(14, 75, '2024-08-17 13:27:00', '6.00', 'Bank Transfer'),
(15, 76, '2024-08-18 15:59:00', '8.00', 'Debit Card'),
(16, 78, '2024-08-18 18:14:00', '14.00', 'PayPal');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','caterers','customer','') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'firdaus@gmail.com', '8c163329e5fe18799021794f9cbefcf0f85d8d0b', 'customer', '2024-07-11 11:02:21'),
(2, 'farhat@gmail.com', '06067f84d9745fcd2d570436a550a084b2d3aaca', 'customer', '2024-07-11 11:06:45'),
(3, 'hananmasoud@gmail.com', '1f02707a142d8d10b265d4d613efa194212c1cc8', 'customer', '2024-07-11 11:22:39'),
(9, 'amina@gmail.com', '1234', 'admin', '2024-07-10 11:44:22'),
(10, 'aisha@gmail.com', '0361fe97c7c02ef86800dedecb1220f5cf5f3320', 'customer', '2024-07-11 10:04:04'),
(11, 'wahida@gmail.com', 'da9d97cc63e5ff817fe27974f369aa080e1975c3', 'admin', '2024-07-11 10:11:08'),
(15, 'fat-hiya@gmail.com', '1f02707a142d8d10b265d4d613efa194212c1cc8', 'caterers', '2024-07-15 09:49:43'),
(16, 'fahima@gmail.com', 'f61f2c6e65101f976faae2024da2e175397f0b75', 'caterers', '2024-07-15 10:09:58'),
(17, 'alannour@gmail.com', '1f02707a142d8d10b265d4d613efa194212c1cc8', 'caterers', '2024-08-10 11:49:22'),
(18, 'hotpot@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'caterers', '2024-08-10 11:57:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `caterers`
--
ALTER TABLE `caterers`
  ADD PRIMARY KEY (`catererID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedbackID`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`FoodID`),
  ADD KEY `caterer` (`catererID`);

--
-- Indexes for table `orderfood`
--
ALTER TABLE `orderfood`
  ADD PRIMARY KEY (`OrderFoodID`),
  ADD KEY `order` (`OrderID`),
  ADD KEY `food` (`FoodID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `customer` (`CustomerID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentID`),
  ADD KEY `Order` (`OrderID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `caterers`
--
ALTER TABLE `caterers`
  MODIFY `catererID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `FoodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orderfood`
--
ALTER TABLE `orderfood`
  MODIFY `OrderFoodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `food_ibfk_1` FOREIGN KEY (`catererID`) REFERENCES `caterers` (`catererID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orderfood`
--
ALTER TABLE `orderfood`
  ADD CONSTRAINT `orderfood_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderfood_ibfk_2` FOREIGN KEY (`FoodID`) REFERENCES `food` (`FoodID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
