-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2026 at 06:01 PM
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
(1, 'upload_file.php', 5),
(3, 'edit_list.php', 99),
(4, 'delete_list.php', 99),
(5, 'search.php', 1),
(6, 'manage_users.php', 99),
(7, 'check_permission.php', 99),
(8, 'manage_grants.php', 99),
(9, 'view_logs.php', 99),
(10, 'optimize.php', 99);

-- --------------------------------------------------------

--
-- Table structure for table `loan_tb`
--

CREATE TABLE `loan_tb` (
  `id` int(11) NOT NULL,
  `member_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `member_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loan_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `loan_date_en` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `loan_tb`
--

INSERT INTO `loan_tb` (`id`, `member_id`, `member_name`, `loan_id`, `loan_date_en`, `created_at`, `file_name`) VALUES
(1, '12345', 'นายสมชาย ใจกล้า', 'สม6800001', '2025-05-01', '2026-02-26 15:53:03', '12345_สม6800001_01-05-2568.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `log_tb`
--

CREATE TABLE `log_tb` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ua` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `activity` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `log_tb`
--

INSERT INTO `log_tb` (`log_id`, `username`, `ip_address`, `ua`, `activity`, `date_time`) VALUES
(1, 'admin', 'localhost', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Sa', 'เข้าสู่ระบบสำเร็จ', '2025-01-26 15:33:15'),
(2, 'admin', 'localhost', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Sa', 'ออกจากระบบ', '2025-02-06 21:10:15'),
(3, 'admin', 'localhost', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Sa', 'เข้าสู่ระบบสำเร็จ', '2025-07-14 22:08:22'),
(4, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'เข้าสู่หน้าหลัก (index.php)', '2026-02-26 15:44:54'),
(5, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'เข้าสู่ระบบสำเร็จ', '2026-02-26 15:47:10'),
(6, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'เข้าสู่ระบบสำเร็จ', '2026-02-26 15:49:41'),
(7, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'ออกจากระบบสำเร็จ', '2026-02-26 15:50:41'),
(8, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'เปลี่ยนรหัสผ่านใหม่สำเร็จ', '2026-02-26 16:40:45'),
(9, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'ออกจากระบบสำเร็จ', '2026-02-26 16:40:47'),
(10, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'เข้าสู่ระบบสำเร็จ', '2026-02-26 16:40:56'),
(11, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'เปลี่ยนรหัสผ่านใหม่สำเร็จ', '2026-02-26 16:41:06'),
(12, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'ออกจากระบบสำเร็จ', '2026-02-26 16:41:07'),
(13, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'เข้าสู่ระบบสำเร็จ', '2026-02-26 16:41:13'),
(14, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'แก้ไขสัญญา สม6800001 สำเร็จ', '2026-02-26 16:52:51'),
(15, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'แก้ไขสัญญา สม6800001 สำเร็จ', '2026-02-26 16:53:03'),
(16, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'เข้าสู่ระบบสำเร็จ', '2026-02-26 17:27:58'),
(17, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'ออกจากระบบสำเร็จ', '2026-02-26 17:28:02'),
(18, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'เข้าสู่ระบบสำเร็จ', '2026-02-26 17:34:58'),
(19, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'ออกจากระบบสำเร็จ', '2026-02-26 17:48:42'),
(20, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'แก้ไขข้อมูลผู้ใช้งาน ID: 3', '2026-02-26 17:58:30'),
(21, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'แก้ไขข้อมูลผู้ใช้งาน ID: 3', '2026-02-26 17:58:35'),
(22, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'แก้ไขข้อมูลผู้ใช้งาน ID: 3', '2026-02-26 18:00:17'),
(23, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'เข้าสู่ระบบสำเร็จ', '2026-02-26 18:00:27'),
(24, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'ออกจากระบบสำเร็จ', '2026-02-26 18:00:30'),
(25, 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'แก้ไขข้อมูลผู้ใช้งาน ID: 3', '2026-02-26 18:00:40'),
(26, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'เข้าสู่ระบบสำเร็จ', '2026-02-26 18:00:47'),
(27, 'tar', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', 'ออกจากระบบสำเร็จ', '2026-02-26 18:00:50');

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
(3, 'tar', '81dc9bdb52d04dc20036dbd8313ed055', 'offline', 0, 5);

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
-- AUTO_INCREMENT for table `loan_tb`
--
ALTER TABLE `loan_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_tb`
--
ALTER TABLE `log_tb`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user_tb`
--
ALTER TABLE `user_tb`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
