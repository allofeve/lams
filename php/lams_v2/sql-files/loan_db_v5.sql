-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 10:38 AM
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
  `level` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `grant_tb`
--

INSERT INTO `grant_tb` (`gid`, `page_name`, `level`) VALUES
(1, 'manage_grants.php', 99),
(2, 'upload_file.php', 5),
(3, 'search.php', 1),
(4, 'edit_list.php', 10),
(5, 'delete_list.php', 10),
(6, 'manage_users.php', 99),
(7, 'optimize.php', 1),
(8, 'view_logs.php', 99);

-- --------------------------------------------------------

--
-- Table structure for table `loan_tb`
--

CREATE TABLE `loan_tb` (
  `table_id` int(10) NOT NULL,
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
(1, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 02:14:48'),
(2, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'ออกจากระบบสำเร็จ', '2026-02-27 02:17:27'),
(3, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 02:17:32'),
(4, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'ออกจากระบบสำเร็จ', '2026-02-27 02:20:54'),
(5, 'admin', '10.1.1.215', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 02:22:56'),
(6, 'admin', '10.1.1.215', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'ออกจากระบบสำเร็จ', '2026-02-27 03:04:40'),
(7, 'admin', '10.4.14.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 03:10:11'),
(8, 'admin', '10.1.1.215', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 03:15:41'),
(9, 'admin', '10.1.1.215', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'เพิ่มผู้ใช้งานใหม่: tar', '2026-02-27 03:17:22'),
(10, 'admin', '10.1.1.215', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'ออกจากระบบสำเร็จ', '2026-02-27 03:17:28'),
(11, 'tar', '10.1.1.215', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 03:17:33'),
(12, 'tar', '10.1.1.215', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'ออกจากระบบสำเร็จ', '2026-02-27 03:18:07'),
(13, 'tar', '10.1.1.215', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 03:21:23'),
(14, 'tar', '10.1.1.215', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'ออกจากระบบสำเร็จ', '2026-02-27 03:21:43'),
(15, 'tar', '10.10.0.151', 'Mozilla/5.0 (Linux; Android 15; V2319 Build/AP3A.240905.015.A2; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/144.0.7559.132 Mobile Safari/537.36 Line/26.2.0/IAB', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 04:00:46'),
(16, 'tar', '10.10.0.151', 'Mozilla/5.0 (Linux; Android 15; V2319 Build/AP3A.240905.015.A2; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/144.0.7559.132 Mobile Safari/537.36 Line/26.2.0/IAB', 'ออกจากระบบสำเร็จ', '2026-02-27 04:01:03'),
(17, 'admin', '10.4.14.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 04:12:12'),
(18, 'admin', '10.10.0.98', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 04:44:19'),
(19, 'admin', '10.10.0.98', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'ออกจากระบบสำเร็จ', '2026-02-27 04:45:36'),
(20, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 10:32:01'),
(21, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'ออกจากระบบสำเร็จ', '2026-02-27 10:32:11'),
(22, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'เข้าสู่ระบบสำเร็จ', '2026-02-27 10:32:22');

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
  `level` int(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_tb`
--

INSERT INTO `user_tb` (`id`, `username`, `password`, `user_status`, `login_fail`, `level`) VALUES
(1, 'admin', '81dc9bdb52d04dc20036dbd8313ed055', 'online', 0, 99),
(2, 'tar', '81dc9bdb52d04dc20036dbd8313ed055', 'offline', 0, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grant_tb`
--
ALTER TABLE `grant_tb`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `loan_tb`
--
ALTER TABLE `loan_tb`
  ADD PRIMARY KEY (`table_id`);

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
  MODIFY `gid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `loan_tb`
--
ALTER TABLE `loan_tb`
  MODIFY `table_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_tb`
--
ALTER TABLE `log_tb`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_tb`
--
ALTER TABLE `user_tb`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
