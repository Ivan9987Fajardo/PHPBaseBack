-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 19, 2020 at 05:54 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `BaseDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `Authentication`
--

CREATE TABLE `Authentication` (
  `User` varchar(256) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `Hash` varchar(256) NOT NULL,
  `CreatedDate` varchar(256) NOT NULL,
  `ExpiryDate` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Inventory`
--

CREATE TABLE `Inventory` (
  `ItemId` varchar(256) NOT NULL,
  `Name` varchar(256) NOT NULL,
  `Quantity` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `UserId` varchar(256) NOT NULL,
  `Name` varchar(256) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `IsLoggedIn` tinyint(1) NOT NULL,
  `CreatedAt` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`UserId`, `Name`, `Password`, `IsLoggedIn`, `CreatedAt`) VALUES
('Ivan', 'Ivan', '$2y$10$XCRJXcWp7CQ63dO4DdxmpOkxHQ/3Xkd2cgN45WjioXzNvg/Q8AZU6', 0, '2020-08-19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Authentication`
--
ALTER TABLE `Authentication`
  ADD PRIMARY KEY (`User`);

--
-- Indexes for table `Inventory`
--
ALTER TABLE `Inventory`
  ADD PRIMARY KEY (`ItemId`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
