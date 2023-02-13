-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2023 at 03:58 AM
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
-- Database: `kfood`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminId` int(11) NOT NULL,
  `adminUsername` varchar(255) NOT NULL,
  `adminPass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminId`, `adminUsername`, `adminPass`) VALUES
(1, 'admin123', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `productQuantity` int(11) NOT NULL,
  `productNote` text DEFAULT NULL,
  `orderId` int(11) DEFAULT NULL,
  `custId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `custId` int(11) NOT NULL,
  `custUsername` varchar(255) NOT NULL,
  `custPass` varchar(255) NOT NULL,
  `custContact` varchar(255) NOT NULL,
  `custAddress` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_table`
--

CREATE TABLE `order_table` (
  `orderId` int(11) NOT NULL,
  `orderCreatedDate` date NOT NULL DEFAULT current_timestamp(),
  `orderStatus` varchar(255) NOT NULL,
  `runnerId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productId` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `productPrice` double NOT NULL,
  `productStock` int(11) NOT NULL,
  `productImage` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productId`, `productName`, `productPrice`, `productStock`, `productImage`) VALUES
(10, 'Jjajangmyeon', 10.9, 14, '63e9a3b4dafa3.png'),
(11, 'Kimbap', 9.9, 20, '63e9a3c4e3c3c.png'),
(12, 'Fried Kimbap', 10.9, 25, '63e98d5de6a3b.png'),
(13, 'Bulgogi Bibimbap', 12, 20, '63e98d7244be7.png'),
(14, 'Tteokboki', 8.9, 15, '63e98d8439a88.png'),
(15, 'Dakmyeon', 10.9, 20, '63e98d9cbbb3d.png'),
(16, 'Korean Chicken (6pcs)', 11.9, 15, '63e98dc314dc3.png'),
(17, 'Mentai Rice', 14, 12, '63e98dd963f6e.png'),
(18, 'Corndog', 6, 30, '63e98de754356.png'),
(19, 'Gogimyeon', 8.9, 10, '63e98e196d1a7.png'),
(20, 'Yanggugsu', 15.2, 12, '63e98e3a96d96.png'),
(21, '100 Plus Drink', 2, 50, '63e98ed582bec.png'),
(22, 'Coke', 2, 50, '63e98ee11e3f3.png'),
(23, 'Blue Lagoon', 5, 50, '63e98eefe3cf0.png'),
(24, 'Lime Soda', 5, 50, '63e98f185a501.png'),
(25, 'Kiwi Sour', 5, 50, '63e98f2d108a3.png'),
(26, 'Strawberry Soda', 5, 50, '63e98f54d7dfe.png'),
(27, 'Original Uyu', 5, 35, '63e98f65f2a56.png'),
(28, 'Banana Uyu', 5, 35, '63e98f71a1c94.png'),
(29, 'Strawberry Uyu', 5, 40, '63e98f9110642.png'),
(30, 'Chocolate Uyu', 5, 35, '63e98fa134207.png');

-- --------------------------------------------------------

--
-- Table structure for table `runner`
--

CREATE TABLE `runner` (
  `runnerId` int(11) NOT NULL,
  `runnerUsername` varchar(255) NOT NULL,
  `runnerPass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminId`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`),
  ADD KEY `cart_product` (`productId`),
  ADD KEY `cart_order` (`orderId`),
  ADD KEY `cart_cust` (`custId`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`custId`);

--
-- Indexes for table `order_table`
--
ALTER TABLE `order_table`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `order_runner` (`runnerId`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productId`);

--
-- Indexes for table `runner`
--
ALTER TABLE `runner`
  ADD PRIMARY KEY (`runnerId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `custId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_table`
--
ALTER TABLE `order_table`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `runner`
--
ALTER TABLE `runner`
  MODIFY `runnerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_cust` FOREIGN KEY (`custId`) REFERENCES `customer` (`custId`),
  ADD CONSTRAINT `cart_order` FOREIGN KEY (`orderId`) REFERENCES `order_table` (`orderId`),
  ADD CONSTRAINT `cart_product` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`);

--
-- Constraints for table `order_table`
--
ALTER TABLE `order_table`
  ADD CONSTRAINT `order_runner` FOREIGN KEY (`runnerId`) REFERENCES `runner` (`runnerId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
