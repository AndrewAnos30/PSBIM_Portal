-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 08:25 AM
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
-- Database: `psbim`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `role` enum('Super Admin','Admin','Contractual') NOT NULL,
  `status` enum('Active','Inactive','Suspended') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'pcp@admin', '$2y$10$zz46PMuitSPwQWfqpd/mae.o/5ATfTYrRBWC5iE2rzTZS9A6APzrK', 'johnandrew.anos@pcp.org.ph', 'Contractual', 'Active', '2025-10-08 01:47:53', '2025-10-08 02:45:29');

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `examination_id` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emails`
--

INSERT INTO `emails` (`id`, `subject`, `examination_id`, `created_at`, `updated_at`) VALUES
(1, 'PSBIM Examination Credentials', 'PSBIM2026', '2025-10-08 02:01:45', '2025-10-08 02:01:45');

-- --------------------------------------------------------

--
-- Table structure for table `examinations`
--

CREATE TABLE `examinations` (
  `id` varchar(50) NOT NULL,
  `title` varchar(150) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `examinations`
--

INSERT INTO `examinations` (`id`, `title`, `date`, `time`, `location`, `created_at`, `updated_at`) VALUES
('PSBIM2026', 'PSBIM Examination', '2026-03-04', '06:00:00', 'Pamantansan ng Lungsod ng Maynila', '2025-10-08 01:50:24', '2025-10-08 01:50:24');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `extensionname` varchar(50) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `examination_id` varchar(50) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `seat_number` varchar(20) NOT NULL,
  `status` enum('Pass','Failed','Awaiting Exam') DEFAULT 'Awaiting Exam',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `username`, `password`, `firstname`, `lastname`, `middlename`, `extensionname`, `email`, `examination_id`, `room_number`, `seat_number`, `status`, `created_at`, `updated_at`) VALUES
(1, 'JATA20260001', '$2y$10$yH5ec72U/6KF9ynReJaDSOAzup6b.c6QpZsWww2j/hF9gPWKQG0ga', 'John Andrew', 'Sona', 'Tuazon', 'Sr', 'andrewanos3001@gmail.com', 'PSBIM2026', 'Room A10', '2026-A10-0001', 'Awaiting Exam', '2025-10-08 01:52:38', '2025-10-08 07:42:44'),
(2, 'JBC2025002', '$2y$10$/J9Q3EuTajX5N2J.JnP9E.yvqtYJH3vwxJu9YJ9B9EAjLdXc6hS3q', 'John Benedict', 'Cueto', 'Sarmiento', '', 'johnbenedict.cueto@pcp.org.ph', 'PSBIM2026', 'Room A20', '2026-A20-0001', 'Awaiting Exam', '2025-10-08 01:59:34', '2025-10-08 02:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `sent`
--

CREATE TABLE `sent` (
  `id` int(11) NOT NULL,
  `examination_id` varchar(50) NOT NULL,
  `member_email` varchar(150) NOT NULL,
  `status` enum('sent','failed') NOT NULL DEFAULT 'sent',
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sent`
--

INSERT INTO `sent` (`id`, `examination_id`, `member_email`, `status`, `timestamp`) VALUES
(1, 'PSBIM2026', 'andrewanos3001@gmail.com', 'sent', '2025-10-08 10:24:15'),
(2, 'PSBIM2026', 'johnbenedict.cueto@pcp.org.ph', 'sent', '2025-10-08 10:24:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_emails_examination` (`examination_id`);

--
-- Indexes for table `examinations`
--
ALTER TABLE `examinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_members_examination` (`examination_id`);

--
-- Indexes for table `sent`
--
ALTER TABLE `sent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sent_examination` (`examination_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sent`
--
ALTER TABLE `sent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `emails`
--
ALTER TABLE `emails`
  ADD CONSTRAINT `fk_emails_examination` FOREIGN KEY (`examination_id`) REFERENCES `examinations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `fk_members_examination` FOREIGN KEY (`examination_id`) REFERENCES `examinations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sent`
--
ALTER TABLE `sent`
  ADD CONSTRAINT `fk_sent_examination` FOREIGN KEY (`examination_id`) REFERENCES `examinations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
