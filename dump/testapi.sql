-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 13, 2018 at 08:03 PM
-- Server version: 5.7.24-0ubuntu0.16.04.1
-- PHP Version: 5.6.38-3+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `appIdentifier`
--

CREATE TABLE `appIdentifier` (
  `id` int(12) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `appid` varchar(50) NOT NULL,
  `appsecret` varchar(50) NOT NULL,
  `type` int(1) NOT NULL,
  `createdAt` datetime NOT NULL,
  `createdBy` int(5) NOT NULL,
  `updatedAt` datetime NOT NULL,
  `updatedBy` int(5) NOT NULL,
  `lastip` varchar(15) NOT NULL,
  `trash` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appIdentifier`
--

INSERT INTO `appIdentifier` (`id`, `name`, `description`, `appid`, `appsecret`, `type`, `createdAt`, `createdBy`, `updatedAt`, `updatedBy`, `lastip`, `trash`) VALUES
(1, 'WEB App', 'api key for web app', 'Geeseicha5x', 'Reev4Ieyu5nohxoo7Phip', 1, '2018-12-13 00:00:00', 1, '2018-12-13 00:00:00', 1, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(12) NOT NULL,
  `name` varchar(100) NOT NULL,
  `taxcode` enum('1','2','3') NOT NULL,
  `price` double(12,4) NOT NULL,
  `trash` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `taxcode`, `price`, `trash`) VALUES
(1, 'Lucky Stretch', '2', 1000.0000, 0),
(2, 'Big Mac', '1', 1000.0000, 0),
(3, 'Movie', '2', 150.0000, 0),
(4, 'Djarum Super', '2', 15.0000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(5) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `trash` int(1) DEFAULT '0',
  `createdAt` datetime DEFAULT NULL,
  `createdBy` int(5) DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `updatedBy` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `code`, `name`, `trash`, `createdAt`, `createdBy`, `updatedAt`, `updatedBy`) VALUES
(1, 'root', 'Root', 0, '2018-10-23 00:00:00', 1, '2018-10-23 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `pp` varchar(255) DEFAULT NULL,
  `salt` varchar(8) NOT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role` int(5) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `nameCall` varchar(100) NOT NULL,
  `nik` varchar(100) DEFAULT NULL,
  `pob` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` int(1) DEFAULT NULL COMMENT '1=male, 2=female',
  `nationality` int(1) DEFAULT NULL COMMENT '1=wni,2=wna',
  `country` varchar(200) NOT NULL,
  `dailyLang` varchar(200) NOT NULL,
  `trash` int(1) DEFAULT '0',
  `createdAt` datetime DEFAULT NULL,
  `createdBy` int(5) DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `updatedBy` int(5) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `lastip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `pp`, `salt`, `password`, `role`, `name`, `nameCall`, `nik`, `pob`, `dob`, `gender`, `nationality`, `country`, `dailyLang`, `trash`, `createdAt`, `createdBy`, `updatedAt`, `updatedBy`, `lastlogin`, `lastip`) VALUES
(1, 'root', NULL, 'lYnO&', '6a83619819dcb0fe4b6481df827e35d6', 1, '', '', NULL, NULL, NULL, NULL, NULL, '', '', 0, '2018-11-02 15:45:19', 1, '2018-11-09 23:43:16', 1, '2018-12-11 00:59:26', '::1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appIdentifier`
--
ALTER TABLE `appIdentifier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appid` (`appid`),
  ADD UNIQUE KEY `appsecret` (`appsecret`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appIdentifier`
--
ALTER TABLE `appIdentifier`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
