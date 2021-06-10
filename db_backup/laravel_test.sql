-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2021 at 04:09 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `constants` text DEFAULT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `slug`, `subject`, `message`, `constants`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Reset password admin', 'signup_invitation_link', 'Signup invitation link', 'Click below link to signup<br><br> <a href=\"{LINK}\">Link</a><br><br>Thank you\r\n', 'LINK', '1', '0', '2021-05-01 11:55:46', '2021-05-09 07:03:22'),
(2, 'Confirm OTP to verify account', 'otp_verification', 'Confirm OTP to verify account', 'Use below otp to verify your email address\r\n<br><br>\r\n{OTP}\r\n<br><br>\r\nThank you', 'OTP', '1', '0', '2021-05-01 11:55:46', '2021-05-09 07:03:22');

-- --------------------------------------------------------

--
-- Table structure for table `unregistered_users`
--

CREATE TABLE `unregistered_users` (
  `id` int(11) NOT NULL,
  `email` varchar(40) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Requested, 1=click but not signup',
  `is_active` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Not deleted, 1=Deleted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `email` varchar(40) NOT NULL,
  `user_role` enum('admin','user') NOT NULL DEFAULT 'user',
  `otp` varchar(6) DEFAULT NULL,
  `is_email_verified` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Not verified, 1=verified',
  `password` text DEFAULT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` enum('0','1') DEFAULT '1',
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `avatar`, `email`, `user_role`, `otp`, `is_email_verified`, `password`, `registered_at`, `created_at`, `updated_at`, `is_active`, `is_deleted`) VALUES
(1, 'Amit kumar', 'amitkumar1', '1623333685.png', 'amit_test2@getnada.com', 'user', NULL, '1', '$2y$10$YXxggh/BSwkYQkJ4cVFvXODxwzECyKLOBActBL.s7t3A4Q4ky4kpa', '2021-06-10 14:01:25', '2021-06-10 07:54:15', '2021-06-10 08:31:25', '1', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unregistered_users`
--
ALTER TABLE `unregistered_users`
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
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `unregistered_users`
--
ALTER TABLE `unregistered_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
