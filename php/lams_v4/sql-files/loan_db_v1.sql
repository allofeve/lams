-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2026 at 05:03 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `grant_tb`
--

CREATE TABLE `grant_tb` (
  `gid` int(10) UNSIGNED NOT NULL,
  `page_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `level_id` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `grant_tb`
--

INSERT INTO `grant_tb` (`gid`, `page_name`, `level_id`) VALUES
(1, 'manage_grants.php', 4),
(2, 'upload_file.php', 2),
(3, 'search.php', 1),
(4, 'edit_list.php', 3),
(5, 'delete_list.php', 3),
(6, 'manage_users.php', 4),
(7, 'optimize.php', 1),
(8, 'view_logs.php', 3),
(9, 'manage_unlock.php', 4),
(10, 'manage_level.php', 4);

-- --------------------------------------------------------

--
-- Table structure for table `level_tb`
--

CREATE TABLE `level_tb` (
  `level_id` int(10) UNSIGNED NOT NULL,
  `grant_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `level_num` int(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `level_tb`
--

INSERT INTO `level_tb` (`level_id`, `grant_name`, `level_num`) VALUES
(1, 'พนักงาน', 1),
(2, 'เจ้าหน้าที่', 5),
(3, 'หัวหน้า', 10),
(4, 'ผู้ดูแลระบบ', 99);

-- --------------------------------------------------------

--
-- Table structure for table `loan_tb`
--

CREATE TABLE `loan_tb` (
  `id` int(10) NOT NULL,
  `member_id` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `member_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `loan_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `loan_date_en` date NOT NULL,
  `file_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_tb`
--

CREATE TABLE `log_tb` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ua` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activity` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `log_tb`
--

INSERT INTO `log_tb` (`log_id`, `username`, `ip_address`, `ua`, `activity`, `date_time`) VALUES
(1, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ลบไฟล์สัญญา 1234_สม6900001_01-01-2569.pdf สำเร็จ', '2026-04-09 04:50:58'),
(2, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ออกจากระบบสำเร็จ', '2026-04-09 05:01:20'),
(3, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-04-09 05:01:27'),
(4, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ออกจากระบบสำเร็จ', '2026-04-09 05:01:38'),
(5, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-04-09 05:01:43'),
(6, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'แก้ไขข้อมูลผู้ใช้งาน ID: 2', '2026-04-09 05:01:55'),
(7, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ออกจากระบบสำเร็จ', '2026-04-09 05:01:59'),
(8, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-04-09 05:02:04'),
(9, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ออกจากระบบสำเร็จ', '2026-04-09 05:02:55'),
(10, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-04-09 05:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `user_tb`
--

CREATE TABLE `user_tb` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `user_status` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'offline',
  `login_fail` int(2) NOT NULL DEFAULT '0',
  `level_id` int(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_tb`
--

INSERT INTO `user_tb` (`id`, `username`, `password`, `user_status`, `login_fail`, `level_id`) VALUES
(1, 'admin', '81dc9bdb52d04dc20036dbd8313ed055', 'online', 0, 4),
(2, 'tar', '81dc9bdb52d04dc20036dbd8313ed055', 'offline', 0, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grant_tb`
--
ALTER TABLE `grant_tb`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `level_tb`
--
ALTER TABLE `level_tb`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `loan_tb`
--
ALTER TABLE `loan_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_tb`
--
ALTER TABLE `log_tb`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `user_tb`
--
ALTER TABLE `user_tb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grant_tb`
--
ALTER TABLE `grant_tb`
  MODIFY `gid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `level_tb`
--
ALTER TABLE `level_tb`
  MODIFY `level_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `loan_tb`
--
ALTER TABLE `loan_tb`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_tb`
--
ALTER TABLE `log_tb`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_tb`
--
ALTER TABLE `user_tb`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
