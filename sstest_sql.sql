-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 30, 2017 at 01:49 AM
-- Server version: 5.7.17-log
-- PHP Version: 7.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sstest`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_coupon`
--

CREATE TABLE `m_coupon` (
  `coupon_id` varchar(50) NOT NULL COMMENT 'id of coupon',
  `coupon_value` decimal(17,2) NOT NULL COMMENT 'value of coupon',
  `coupon_type` char(3) NOT NULL COMMENT 'type of coupon',
  `coupon_status` char(3) NOT NULL COMMENT 'status of coupon',
  `coupon_quantity` int(11) NOT NULL,
  `coupon_start_date` datetime NOT NULL COMMENT 'effective coupon start date',
  `coupon_expiry_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m_coupon`
--

INSERT INTO `m_coupon` (`coupon_id`, `coupon_value`, `coupon_type`, `coupon_status`, `coupon_quantity`, `coupon_start_date`, `coupon_expiry_date`) VALUES
('CP123', '100000.00', 'V', 'V', 1000, '2017-07-20 00:00:00', '2017-08-31 23:59:59'),
('CP345', '20.00', 'P', 'V', 1900, '2017-07-20 00:00:00', '2017-08-31 00:00:00'),
('CP456', '50.00', 'P', 'I', 2500, '2017-01-01 00:00:00', '2017-01-31 23:59:59'),
('CP789', '50000.00', 'V', 'V', 1500, '2017-09-01 00:00:00', '2017-09-30 23:59:59'),
('CP999', '10000.00', 'V', 'I', 500, '2017-07-01 00:00:00', '2017-07-10 23:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `m_product`
--

CREATE TABLE `m_product` (
  `product_id` varchar(50) NOT NULL COMMENT 'id of product',
  `product_name` varchar(255) DEFAULT NULL COMMENT 'name of product',
  `product_desc` text COMMENT 'description of product',
  `product_price` decimal(17,2) DEFAULT NULL COMMENT 'price of product',
  `product_status` char(3) DEFAULT NULL COMMENT 'status of product',
  `product_stock` int(11) DEFAULT NULL COMMENT 'stock of product'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m_product`
--

INSERT INTO `m_product` (`product_id`, `product_name`, `product_desc`, `product_price`, `product_status`, `product_stock`) VALUES
('MBA01', 'Apple Macbook Air MJVM2', '	Intel Core i5 - 1.6Ghz, RAM 4GB, HDD 128GB SSD, VGA Intel HD 6000, WebCam, Bluetooth, Screen 11\" Wide LED, OS X Yosemite', '50000.00', 'A', 33000000),
('MBA02', 'Apple MacBook Air MQD42', '1.8GHz dual-core Intel Core i5 (Turbo Boost up to 2.9GHz), RAM 8GB 1600MHz LPDDR3, 256GB SSD, 13.3-inch, Intel HD Graphics 6000, Mac OS Sierra', '60000.00', 'D', 0),
('MBP01', 'Apple Macbook Pro MJLT2 Pro Retina (Upgrade Version)', '2.8GHz quad-core Intel Core i7 (Turbo Boost up to 3.7GHz), 16GB 1600MHz memory; 1TB PCIe-based flash storage, 15 inch IPS Retina, Intel Iris Graphics, AMD Radeon R9 M370X with 2GB GDDR5 memory, Force Touch trackpad, OS X Yosemite', '100000.00', 'A', 270000),
('MBP02', 'Apple Macbook Pro MJLQ2 Pro Retina', '2.2GHz quad-core Intel Core i7 (Turbo Boost up to 3.4GHz), 16GB 1600MHz memory; 256 GB PCIe-based flash storage, 15 inch IPS Retina, Intel Iris Graphics, Force Touch trackpad, OS X Yosemite, Up to 9 Hours of Battery Life', '80000.00', 'D', 50000000),
('MBP03', 'Apple Macbook Pro MLL42 Pro Retina', '2.0GHz quad-core Intel Core i5 (Turbo Boost up to 3.1GHz), 8GB 1866MHz memory; 256 GB PCIe-based SSD1, 13.3 inch IPS Retina, Intel Iris Graphics 540, Two Thunderbolt 3 Ports, MacOS', '70000.00', 'A', 150000000);

-- --------------------------------------------------------

--
-- Table structure for table `t_order`
--

CREATE TABLE `t_order` (
  `order_id` varchar(50) NOT NULL COMMENT 'id of order',
  `order_created_date` datetime DEFAULT NULL COMMENT 'date of order creation',
  `order_processed_date` datetime DEFAULT NULL COMMENT 'date of order processing',
  `order_submitted_date` datetime DEFAULT NULL COMMENT 'date of order submission',
  `order_status` varchar(3) DEFAULT NULL COMMENT 'status of order',
  `order_total_amount` decimal(17,2) DEFAULT NULL COMMENT 'total amount of order',
  `order_coupon` varchar(50) DEFAULT NULL COMMENT 'coupon used on order',
  `customer_name` varchar(255) DEFAULT NULL COMMENT 'name of customer',
  `customer_phone` varchar(50) DEFAULT NULL COMMENT 'phone of customer',
  `customer_email` varchar(255) DEFAULT NULL COMMENT 'email of customer',
  `customer_address` varchar(45) DEFAULT NULL COMMENT 'address of customer',
  `shipping_id` varchar(255) DEFAULT NULL COMMENT 'shipping id',
  `shipping_status` char(3) DEFAULT NULL COMMENT 'shipping status'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_order`
--

INSERT INTO `t_order` (`order_id`, `order_created_date`, `order_processed_date`, `order_submitted_date`, `order_status`, `order_total_amount`, `order_coupon`, `customer_name`, `customer_phone`, `customer_email`, `customer_address`, `shipping_id`, `shipping_status`) VALUES
('ORD001', '2017-07-03 15:56:29', '2017-07-30 00:27:11', '2017-07-30 00:15:06', 'DV', '200000.00', 'CP345', 'Mr. Johnie Walker XXX', '+62812345678', 'johnsmith@somewhereunknown.co', 'Jl. Somewhere no.123', 'X7YHG', 'D'),
('ORD002', '2017-07-25 12:22:38', NULL, NULL, 'D', '100000.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_order_item`
--

CREATE TABLE `t_order_item` (
  `item_id` int(11) NOT NULL COMMENT 'autoincrement primary key',
  `order_id` varchar(50) NOT NULL COMMENT 'order id where this item belongs to',
  `product_id` varchar(50) NOT NULL,
  `product_quantity` int(11) DEFAULT NULL COMMENT 'quantity of item ordered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_order_item`
--

INSERT INTO `t_order_item` (`item_id`, `order_id`, `product_id`, `product_quantity`) VALUES
(1, 'ORD001', 'MBA01', 3),
(2, 'ORD001', 'MBP01', 1),
(3, 'ORD002', 'MBP01', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_coupon`
--
ALTER TABLE `m_coupon`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `m_product`
--
ALTER TABLE `m_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `t_order`
--
ALTER TABLE `t_order`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `UN_shipping_id` (`shipping_id`),
  ADD KEY `FK_order_coupon_idx` (`order_coupon`);

--
-- Indexes for table `t_order_item`
--
ALTER TABLE `t_order_item`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `UN_order_product` (`order_id`,`product_id`),
  ADD KEY `FK_order_id_idx` (`order_id`),
  ADD KEY `FK_product_id_idx` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_order_item`
--
ALTER TABLE `t_order_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'autoincrement primary key', AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_order`
--
ALTER TABLE `t_order`
  ADD CONSTRAINT `FK_order_coupon` FOREIGN KEY (`order_coupon`) REFERENCES `m_coupon` (`coupon_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `t_order_item`
--
ALTER TABLE `t_order_item`
  ADD CONSTRAINT `FK_order_id` FOREIGN KEY (`order_id`) REFERENCES `t_order` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_product_id` FOREIGN KEY (`product_id`) REFERENCES `m_product` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
