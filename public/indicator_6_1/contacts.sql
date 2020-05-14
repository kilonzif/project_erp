-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2020 at 03:03 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ace-impact`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `position_id` tinyint(11) NOT NULL,
  `person_title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mailing_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mailing_phone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mailing_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institution` tinyint(10) DEFAULT NULL,
  `ace` tinyint(10) DEFAULT NULL,
  `country` tinyint(10) DEFAULT NULL,
  `thematic_field` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_contact` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `position_id`, `person_title`, `mailing_name`, `gender`, `mailing_phone`, `mailing_email`, `institution`, `ace`, `country`, `thematic_field`, `new_contact`, `created_at`, `updated_at`) VALUES
(43, 4, 'Mr', 'John Doe', 'male', '0560223058', 'johndoe@gmail.com', NULL, NULL, 1, NULL, 1, '2020-05-08 11:59:57', '2020-05-08 11:59:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
