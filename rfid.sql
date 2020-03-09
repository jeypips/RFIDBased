-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 09, 2020 at 03:47 PM
-- Server version: 5.7.11
-- PHP Version: 7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rfid`
--

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `cour_id` int(11) NOT NULL,
  `cour_description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`cour_id`, `cour_description`) VALUES
(1, 'Learners'),
(2, 'Faculty');

-- --------------------------------------------------------

--
-- Table structure for table `logged_book`
--

CREATE TABLE `logged_book` (
  `logb_id` int(11) NOT NULL,
  `f_stud_id` int(11) NOT NULL,
  `logb_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `sect_id` int(11) NOT NULL,
  `f_year_id` int(11) NOT NULL,
  `sect_description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`sect_id`, `f_year_id`, `sect_description`) VALUES
(1, 1, 'A'),
(2, 1, 'B'),
(3, 1, 'C'),
(4, 1, 'D'),
(5, 1, 'E'),
(6, 1, 'F'),
(7, 2, 'A'),
(8, 2, 'B'),
(9, 2, 'C'),
(10, 2, 'D'),
(11, 2, 'E'),
(12, 2, 'F'),
(13, 3, 'A'),
(14, 3, 'B'),
(15, 3, 'C'),
(16, 3, 'D'),
(17, 3, 'E'),
(18, 3, 'F'),
(19, 4, 'A'),
(20, 4, 'B'),
(21, 4, 'C'),
(22, 4, 'D'),
(23, 4, 'E'),
(24, 4, 'F'),
(25, 5, 'A'),
(26, 5, 'B'),
(27, 5, 'C'),
(28, 5, 'D'),
(29, 5, 'E'),
(30, 5, 'F');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `stud_id` int(11) NOT NULL,
  `stud_studentID` int(50) DEFAULT NULL,
  `stud_RFID` int(50) DEFAULT NULL,
  `stud_fName` varchar(100) DEFAULT NULL,
  `stud_mName` varchar(100) DEFAULT NULL,
  `stud_lName` varchar(100) DEFAULT NULL,
  `stud_nEx` varchar(10) DEFAULT NULL,
  `stud_photo` varchar(100) DEFAULT NULL,
  `stud_address` varchar(255) DEFAULT NULL,
  `parent_contact_no` varchar(20) DEFAULT NULL,
  `adviser_contact_no` varchar(20) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_cour_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`stud_id`, `stud_studentID`, `stud_RFID`, `stud_fName`, `stud_mName`, `stud_lName`, `stud_nEx`, `stud_photo`, `stud_address`, `parent_contact_no`, `adviser_contact_no`, `date_added`, `f_cour_id`) VALUES
(1, 15149410, 9100543, 'Kyla marie', 'Boholano', 'Sarino', NULL, 'images/20170222115511.jpg', 'Villaba', '09192835421', '09192835421', '2020-03-09 15:46:09', 2),
(2, 15150974, NULL, 'Bianca', 'Seville', 'Cabahug', NULL, NULL, 'Washington', '09291823451', '09291823451', '2020-03-09 22:23:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `user_name`, `user_password`, `date_added`) VALUES
(1, 'John Paul Balanon', 'admin', 'admin', '2020-03-09 15:00:34'),
(2, 'Sly Flores', 'user', 'user', '2020-03-09 15:00:34'),
(3, 'Josephine Marcelo', 'josephine', 'josephine', '2020-03-09 19:49:14');

-- --------------------------------------------------------

--
-- Table structure for table `year`
--

CREATE TABLE `year` (
  `year_id` int(11) NOT NULL,
  `year_description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `year`
--

INSERT INTO `year` (`year_id`, `year_description`) VALUES
(1, 'I'),
(2, 'II'),
(3, 'III'),
(4, 'IV'),
(5, 'V'),
(6, 'Grade 7'),
(7, 'Grade 8'),
(8, 'Grade 9'),
(9, 'Grade 10'),
(10, 'Grade 11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`cour_id`);

--
-- Indexes for table `logged_book`
--
ALTER TABLE `logged_book`
  ADD PRIMARY KEY (`logb_id`),
  ADD KEY `f_stud_id` (`f_stud_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`sect_id`),
  ADD KEY `years_id` (`f_year_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stud_id`),
  ADD KEY `cour_id` (`f_cour_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `year`
--
ALTER TABLE `year`
  ADD PRIMARY KEY (`year_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `cour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `logged_book`
--
ALTER TABLE `logged_book`
  MODIFY `logb_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `sect_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `stud_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `year`
--
ALTER TABLE `year`
  MODIFY `year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `logged_book`
--
ALTER TABLE `logged_book`
  ADD CONSTRAINT `logged_book_ibfk_1` FOREIGN KEY (`f_stud_id`) REFERENCES `students` (`stud_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `section_ibfk_1` FOREIGN KEY (`f_year_id`) REFERENCES `year` (`year_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`f_cour_id`) REFERENCES `course` (`cour_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
