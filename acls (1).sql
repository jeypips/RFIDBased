-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 04, 2020 at 12:27 AM
-- Server version: 5.7.11
-- PHP Version: 7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `acls`
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
(1, 'Humanities and Social Sciences Strand (HUMMS)'),
(2, 'General Academic Strand (GAS)');

-- --------------------------------------------------------

--
-- Table structure for table `logged_book`
--

CREATE TABLE `logged_book` (
  `logb_id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `logb_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logged_book`
--

INSERT INTO `logged_book` (`logb_id`, `stud_id`, `logb_login`) VALUES
(1, 1, '2020-03-13 00:00:00'),
(4, 1, '2020-03-10 08:00:00'),
(5, 1, '2020-03-10 00:00:00'),
(6, 3, '2020-03-13 05:29:53'),
(7, 3, '2020-04-21 05:12:24'),
(8, 3, '2020-04-22 08:08:24'),
(9, 3, '2020-04-22 08:13:30'),
(10, 3, '2020-04-22 08:13:32'),
(11, 3, '2020-04-22 08:13:34'),
(12, 8, '2020-04-28 00:32:02'),
(13, 3, '2020-04-28 00:33:50'),
(14, 8, '2020-04-28 04:50:46'),
(15, 8, '2020-04-28 04:51:15'),
(16, 8, '2020-04-28 04:57:41'),
(17, 1, '2020-04-28 05:00:54'),
(18, 1, '2020-04-28 05:01:14'),
(19, 1, '2020-04-28 05:01:36'),
(20, 1, '2020-04-28 05:01:49'),
(21, 4, '2020-04-28 05:04:05'),
(22, 4, '2020-04-28 05:04:21'),
(23, 3, '2020-04-28 05:07:42'),
(24, 3, '2020-04-28 05:08:04'),
(25, 3, '2020-04-28 05:08:44'),
(26, 3, '2020-04-22 08:13:32'),
(27, 3, '2020-04-22 01:08:24'),
(28, 3, '2020-04-21 05:30:24'),
(29, 3, '2020-04-22 05:13:32');

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
(7, 2, 'A'),
(8, 2, 'B'),
(9, 2, 'C'),
(12, 2, 'None'),
(13, 3, 'A'),
(14, 3, 'B'),
(15, 3, 'C'),
(18, 3, 'None'),
(19, 4, 'A'),
(20, 4, 'B'),
(21, 4, 'C'),
(24, 4, 'None'),
(25, 5, 'A'),
(26, 5, 'B'),
(27, 5, 'C'),
(30, 5, 'None'),
(31, 1, 'None'),
(32, 11, 'A'),
(33, 11, 'B'),
(34, 6, 'A'),
(36, 6, 'B'),
(37, 6, 'C'),
(38, 6, 'None'),
(39, 7, 'A'),
(40, 7, 'B'),
(41, 7, 'C'),
(42, 7, 'None'),
(43, 8, 'A'),
(44, 8, 'B'),
(45, 8, 'C'),
(46, 8, 'None'),
(47, 9, 'A'),
(48, 9, 'B'),
(49, 9, 'C'),
(50, 9, 'None'),
(51, 10, 'A'),
(52, 10, 'B'),
(53, 10, 'C'),
(54, 10, 'None'),
(55, 12, 'A'),
(56, 12, 'B'),
(57, 12, 'C'),
(58, 12, 'None'),
(59, 11, 'C'),
(60, 11, 'None');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `stud_id` int(11) NOT NULL,
  `stud_studentID` varchar(50) DEFAULT NULL,
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
  `f_cour_id` int(11) DEFAULT NULL,
  `stud_year_id` int(11) DEFAULT NULL,
  `stud_sect_id` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`stud_id`, `stud_studentID`, `stud_RFID`, `stud_fName`, `stud_mName`, `stud_lName`, `stud_nEx`, `stud_photo`, `stud_address`, `parent_contact_no`, `adviser_contact_no`, `date_added`, `f_cour_id`, `stud_year_id`, `stud_sect_id`, `is_deleted`) VALUES
(1, '0014053008', 14053008, 'Josephine', 'Valdez', 'Marcelo', NULL, 'pictures/1.png', 'Paagan', '09976717173', '09976717173', '2020-03-09 15:46:09', 2, 12, 55, 0),
(2, '0007324648', 7324648, 'Geraldine Joy', 'Nicanor', 'Nisperos', NULL, 'pictures/2.PNG', 'Bacnotan La Union', '9484556985', '9945568695', '2020-03-12 16:26:11', 1, 11, 32, 0),
(3, '0012644224', 12644224, 'Rhoda', 'M.', 'Lilan', NULL, 'pictures/3.PNG', 'Bacnotan La Union', '09075514023', '09075514023', '2020-03-13 12:31:47', 1, 11, 32, 0),
(4, '0013003096', 13003096, 'Alma', 'Valdez', 'Marcelo', NULL, 'pictures/4.PNG', 'Santol La Union', '09278832467', '09278832467', '2020-04-27 13:53:53', 2, 12, 55, 0),
(5, '0007068096', 7068096, 'Jessie', 'Bautista', 'Vallecera', NULL, NULL, 'Bauang, La Union', '09128524168', '09128524168', '2020-04-27 14:24:32', 1, 11, 32, 0),
(6, '0007463283', 7463283, 'Genesis', 'Omo', 'Omo', NULL, NULL, 'Sapilang, La Union', '09807968', '0897857', '2020-04-27 14:26:23', 2, 11, 32, 0),
(7, '0007324602', 7324602, 'Gale', 'Valdez', 'Oyando', NULL, 'pictures/7.jpg', 'Balaoan, La Union', '09158565816', '09158565816', '2020-04-27 14:28:08', 2, 11, 32, 0),
(8, '0012995511', 12995511, 'Cedric', 'Oyando', 'Miranda', NULL, 'pictures/8.png', 'Santol, La Union', '09659708809', '09659708809', '2020-04-28 08:00:08', 1, 11, 32, 0),
(9, '0012997034', 12997034, 'Lawrence', 'Oyando', 'Miranda', NULL, NULL, 'Santol, La Union', '09355785760', '09355785760', '2020-04-28 08:01:50', 1, 12, 55, 0),
(10, '0012983756', 12983756, 'Raffy', 'Valdez', 'Marcelo', NULL, NULL, 'Balaoan', '09771268474', '09771268474', '2020-04-28 08:05:24', 2, 11, 32, 0),
(11, '0007460641', 7460641, 'Richard', 'Richard', 'Marcelo', NULL, NULL, 'Balaoan,La Union', '09383524370', '09383524370', '2020-04-28 08:14:25', 1, 11, 32, 0),
(12, '0014431647', 14431647, 'Kathy', 'Salting', 'Flores', NULL, 'pictures/12.jpg', 'Bacnotan, La union', '0909455308703', '0909455308703', '2020-04-28 08:20:29', 2, 11, 32, 0),
(13, '0014426618', 14426618, 'Edelita', 'Corpuz', 'Ebuenga', NULL, 'pictures/13.jpg', 'Bacnotan, La Union', '09128534168', '09128534168', '2020-04-28 08:28:15', 2, 11, 32, 0),
(14, '0014427290', 14427290, 'Maricel', 'Oficiar', 'Pre', NULL, NULL, 'Bacnotan, La Union', '0998989988', '0998989988', '2020-04-28 08:37:46', 1, 12, 55, 0),
(15, '0014420871', 14420871, 'Zenaida', 'Valdez', 'Oyando', NULL, NULL, 'Santol, La Union', '0910285454', '0910285454', '2020-04-28 08:41:49', 1, 12, 55, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `groups` varchar(50) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `user_name`, `user_password`, `groups`, `date_added`) VALUES
(1, 'Josephine Marcelo', 'admin', 'admin', 'Super Admin', '2020-03-09 15:00:34'),
(2, 'Geraldine', 'user', 'user', 'Admin', '2020-03-09 15:00:34'),
(3, 'Rhoda', 'teacher', 'teacher', 'Teacher', '2020-03-13 09:23:04');

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
(6, 'VI'),
(7, 'Grade 7'),
(8, 'Grade 8'),
(9, 'Grade 9'),
(10, 'Grade 10'),
(11, 'Grade 11'),
(12, 'Grade 12');

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
  ADD KEY `f_stud_id` (`stud_id`);

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
  ADD KEY `cour_id` (`f_cour_id`),
  ADD KEY `stud_year_id` (`stud_year_id`);

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
  MODIFY `logb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `sect_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `stud_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `year`
--
ALTER TABLE `year`
  MODIFY `year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `logged_book`
--
ALTER TABLE `logged_book`
  ADD CONSTRAINT `logged_book_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `students` (`stud_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `section_ibfk_1` FOREIGN KEY (`f_year_id`) REFERENCES `year` (`year_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`f_cour_id`) REFERENCES `course` (`cour_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`stud_year_id`) REFERENCES `year` (`year_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
