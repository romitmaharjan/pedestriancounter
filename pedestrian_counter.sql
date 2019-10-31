-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2019 at 02:46 AM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pedestrian_counter`
--

-- --------------------------------------------------------

--
-- Table structure for table `cyclist`
--

CREATE TABLE `cyclist` (
  `cIndex` int(5) NOT NULL,
  `sensorID` varchar(5) NOT NULL,
  `cycCount` int(5) NOT NULL,
  `hour` varchar(6) NOT NULL,
  `date` date NOT NULL,
  `leftCycCount` int(11) NOT NULL,
  `rightCycCount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cyclist`
--

INSERT INTO `cyclist` (`cIndex`, `sensorID`, `cycCount`, `hour`, `date`, `leftCycCount`, `rightCycCount`) VALUES
(1, 'A001', 15, '0', '2019-10-25', 8, 7),
(2, 'A001', 5, '1', '2019-10-25', 2, 3),
(3, 'A001', 60, '2', '2019-10-25', 40, 20),
(4, 'A001', 0, '3', '2019-10-25', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `heatmap`
--

CREATE TABLE `heatmap` (
  `sensorID` varchar(5) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(6) NOT NULL,
  `value` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `heatmap`
--

INSERT INTO `heatmap` (`sensorID`, `date`, `time`, `value`) VALUES
('A001', '2019-10-09', '12 AM', 5),
('A002', '2019-10-09', '12 AM', 12),
('A003', '2019-10-09', '12 AM', 0);

-- --------------------------------------------------------

--
-- Table structure for table `hours`
--

CREATE TABLE `hours` (
  `hoursID` int(2) NOT NULL,
  `hours` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hours`
--

INSERT INTO `hours` (`hoursID`, `hours`) VALUES
(1, '1 AM'),
(2, '2 AM'),
(3, '3 AM'),
(4, '4 AM'),
(5, '5 AM'),
(6, '6 AM'),
(7, '7 AM'),
(8, '8 AM'),
(9, '9 AM'),
(10, '10 AM'),
(11, '11 AM'),
(12, '12 AM'),
(13, '1 PM'),
(14, '2 PM'),
(15, '3 PM'),
(16, '4 PM'),
(17, '5 PM'),
(18, '6 PM'),
(19, '7 PM'),
(20, '8 PM'),
(21, '9 PM'),
(22, '10 PM'),
(23, '11 PM'),
(24, '12 PM');

-- --------------------------------------------------------

--
-- Table structure for table `pedestrian`
--

CREATE TABLE `pedestrian` (
  `pIndex` int(5) NOT NULL,
  `sensorID` varchar(5) DEFAULT NULL,
  `hour` varchar(6) NOT NULL,
  `date` date NOT NULL,
  `pedCount` int(5) NOT NULL,
  `leftPed` int(11) NOT NULL,
  `rightPed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pedestrian`
--

INSERT INTO `pedestrian` (`pIndex`, `sensorID`, `hour`, `date`, `pedCount`, `leftPed`, `rightPed`) VALUES
(1, 'A001', '0', '2019-10-25', 5, 2, 3),
(2, 'A001', '1', '2019-10-25', 30, 21, 9),
(3, 'A001', '2', '2019-10-25', 100, 75, 25),
(4, 'A001', '3', '2019-10-25', 40, 18, 22);

-- --------------------------------------------------------

--
-- Table structure for table `sensor`
--

CREATE TABLE `sensor` (
  `sensorID` varchar(5) NOT NULL,
  `name` text NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `totalCount` int(5) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sensor`
--

INSERT INTO `sensor` (`sensorID`, `name`, `lat`, `lng`, `totalCount`, `date`) VALUES
('A001', 'Chum Street', -36.762928, 144.254242, 100, '2019-10-25'),
('A002', 'Alder Street', -36.783619, 144.240585, 50, '2019-10-25'),
('A003', 'Breen Street', -36.774029, 144.265854, 3000, '2019-10-25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cyclist`
--
ALTER TABLE `cyclist`
  ADD PRIMARY KEY (`cIndex`),
  ADD KEY `sensorID` (`sensorID`);

--
-- Indexes for table `heatmap`
--
ALTER TABLE `heatmap`
  ADD PRIMARY KEY (`sensorID`);

--
-- Indexes for table `hours`
--
ALTER TABLE `hours`
  ADD PRIMARY KEY (`hoursID`);

--
-- Indexes for table `pedestrian`
--
ALTER TABLE `pedestrian`
  ADD PRIMARY KEY (`pIndex`),
  ADD KEY `sensorID` (`sensorID`);

--
-- Indexes for table `sensor`
--
ALTER TABLE `sensor`
  ADD PRIMARY KEY (`sensorID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cyclist`
--
ALTER TABLE `cyclist`
  MODIFY `cIndex` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hours`
--
ALTER TABLE `hours`
  MODIFY `hoursID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pedestrian`
--
ALTER TABLE `pedestrian`
  MODIFY `pIndex` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cyclist`
--
ALTER TABLE `cyclist`
  ADD CONSTRAINT `cyclist_ibfk_1` FOREIGN KEY (`sensorID`) REFERENCES `sensor` (`sensorID`);

--
-- Constraints for table `heatmap`
--
ALTER TABLE `heatmap`
  ADD CONSTRAINT `heatmap_ibfk_1` FOREIGN KEY (`sensorID`) REFERENCES `sensor` (`sensorID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pedestrian`
--
ALTER TABLE `pedestrian`
  ADD CONSTRAINT `pedestrian_ibfk_1` FOREIGN KEY (`sensorID`) REFERENCES `sensor` (`sensorID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
