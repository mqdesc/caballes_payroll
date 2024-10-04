-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table payroll_db.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `name` varchar(250) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table payroll_db.departments: ~2 rows (approximately)
INSERT INTO `departments` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'ITD', 'Information Technology Department', '2022-06-23 09:07:05', '2022-06-23 09:07:05'),
	(2, 'SD', 'Sales Department', '2022-06-23 09:07:05', '2024-07-09 14:06:01');

-- Dumping structure for table payroll_db.designations
CREATE TABLE IF NOT EXISTS `designations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `name` varchar(250) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table payroll_db.designations: ~5 rows (approximately)
INSERT INTO `designations` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'IT Staff', 'IT Staff', '2022-06-23 09:13:21', '2024-07-09 14:07:27'),
	(4, 'Jr. Web Dev.', 'Junior Web Developer', '2022-06-23 09:13:21', '2022-06-23 09:13:21'),
	(7, 'Manage', 'Manager', '2022-06-23 09:13:21', '2022-06-23 09:13:21'),
	(8, 'IDH', 'IT Department Head', '2022-06-23 09:13:21', '2024-07-09 14:10:02'),
	(10, 'Sr. Network Specialist', 'Senior Network Specialist', '2024-07-09 14:09:16', '2024-07-09 14:09:16');

-- Dumping structure for table payroll_db.employees
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `department_id` int unsigned NOT NULL,
  `designation_id` int unsigned NOT NULL,
  `code` varchar(100) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `middle_name` varchar(250) DEFAULT '',
  `last_name` varchar(250) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(50) NOT NULL,
  `email` varchar(250) NOT NULL,
  `date_hired` date NOT NULL,
  `salary` float(12,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`),
  KEY `designation_id` (`designation_id`),
  CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employees_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table payroll_db.employees: ~4 rows (approximately)
INSERT INTO `employees` (`id`, `department_id`, `designation_id`, `code`, `first_name`, `middle_name`, `last_name`, `dob`, `gender`, `email`, `date_hired`, `salary`, `status`, `created_at`, `updated_at`) VALUES
	(30, 1, 4, '1', 'John Derricke', '', 'Lajot', '2000-12-29', 'Male', 'john@mail.com', '2024-12-02', 10000.00, 1, '2024-07-06 11:57:03', '2024-07-06 11:59:33'),
	(31, 1, 1, '2', 'Mariele', '', 'Descallar', '2000-02-02', 'Female', 'mariele@mail.com', '2024-07-05', 2400.00, 1, '2024-07-06 13:23:34', '2024-07-10 10:57:53'),
	(33, 1, 10, '3', 'Kim', '', 'Macahis', '2024-07-09', 'Male', 'kim@mail.com', '2024-07-09', 15000.00, 1, '2024-07-09 14:19:34', '2024-07-09 14:19:34'),
	(34, 1, 1, '4', 'Jemar', '', 'Intea', '2024-07-15', 'Male', 'jemar@mail.com', '2024-07-15', 0.00, 1, '2024-07-15 08:30:08', '2024-07-15 08:30:08');

-- Dumping structure for table payroll_db.loans
CREATE TABLE IF NOT EXISTS `loans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `loan_name` varchar(255) NOT NULL,
  `loan_amount` float(12,2) NOT NULL DEFAULT '0.00',
  `interest` float(12,2) NOT NULL DEFAULT '0.00',
  `weeks_pay` float(12,2) NOT NULL DEFAULT '0.00',
  `total_loan` float(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

-- Dumping data for table payroll_db.loans: ~9 rows (approximately)
INSERT INTO `loans` (`id`, `employee_id`, `loan_name`, `loan_amount`, `interest`, `weeks_pay`, `total_loan`, `created_at`) VALUES
	(31, 30, 'grocery loan', 5000.00, 2.00, 6.00, 0.00, '2024-07-19 06:46:58'),
	(32, 31, 'emergency loan', 1500.00, 3.00, 5.00, 1509.00, '2024-07-19 07:23:17'),
	(33, 33, 'car loan', 1000.00, 2.00, 6.00, 170.00, '2024-07-19 07:25:36'),
	(35, 33, 'Office Loan', 1000.00, 2.00, 7.00, 145.71, '2024-07-22 00:49:10'),
	(36, 34, 'test loan', 100000.00, 2.00, 18.00, 205555.56, '2024-07-22 08:43:10'),
	(37, 30, 'Office Loan', 4500.00, 2.00, 15.00, 9300.00, '2024-07-22 08:54:36'),
	(38, 31, 'loan 2', 1000.00, 2.00, 15.00, 2066.67, '2024-07-22 08:55:43'),
	(39, 33, 'loan 3', 72000.00, 2.00, 36.00, 3440.00, '2024-07-22 09:07:02'),
	(40, 30, 'Office Loan', 1000.00, 2.00, 10.00, 120.00, '2024-07-25 08:23:24');

-- Dumping structure for table payroll_db.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table payroll_db.migrations: ~8 rows (approximately)
INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
	(1, '2022-06-18-005419', 'App\\Database\\Migrations\\Authentication', 'default', 'App', 1655945989, 1),
	(2, '2022-06-23-004252', 'App\\Database\\Migrations\\Department', 'default', 'App', 1655945989, 1),
	(3, '2022-06-23-004521', 'App\\Database\\Migrations\\Designation', 'default', 'App', 1655945989, 1),
	(4, '2022-06-23-005222', 'App\\Database\\Migrations\\Employee', 'default', 'App', 1655945990, 1),
	(5, '2022-06-23-034207', 'App\\Database\\Migrations\\Payroll', 'default', 'App', 1655956354, 2),
	(6, '2022-06-23-040112', 'App\\Database\\Migrations\\Payslip', 'default', 'App', 1655964506, 3),
	(7, '2022-06-23-050647', 'App\\Database\\Migrations\\PayslipEarnings', 'default', 'App', 1655964506, 3),
	(8, '2022-06-23-050657', 'App\\Database\\Migrations\\PayslipDeductions', 'default', 'App', 1655964507, 3);

-- Dumping structure for table payroll_db.payrolls
CREATE TABLE IF NOT EXISTS `payrolls` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `title` varchar(250) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table payroll_db.payrolls: ~3 rows (approximately)
INSERT INTO `payrolls` (`id`, `code`, `title`, `from_date`, `to_date`, `created_at`, `updated_at`) VALUES
	(7, 'Payroll 1001', 'June 15-30, 2024', '2024-06-15', '2024-06-30', '2024-07-09 14:24:33', '2024-07-09 14:25:08'),
	(8, 'test 1', 'test 1', '2024-07-01', '2024-07-06', '2024-07-12 10:26:14', '2024-07-12 10:26:14'),
	(9, 'test 2', '09/15-09/30', '2024-09-15', '2024-09-30', '2024-09-28 09:22:40', '2024-09-28 09:22:40');

-- Dumping structure for table payroll_db.payroll_deductions
CREATE TABLE IF NOT EXISTS `payroll_deductions` (
  `payslip_id` int unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `amount` float(12,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `payslip_id` (`payslip_id`),
  CONSTRAINT `payroll_deductions_payslip_id_foreign` FOREIGN KEY (`payslip_id`) REFERENCES `payslips` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table payroll_db.payroll_deductions: ~6 rows (approximately)
INSERT INTO `payroll_deductions` (`payslip_id`, `name`, `amount`, `created_at`, `updated_at`) VALUES
	(41, 'SSS', 213.00, '2024-07-15 10:26:06', '2024-07-15 10:26:06'),
	(41, 'PHILHEALTH', 200.00, '2024-07-15 10:26:06', '2024-07-15 10:26:06'),
	(41, 'PAGIBIG', 100.00, '2024-07-15 10:26:06', '2024-07-15 10:26:06'),
	(42, 'SSS', 213.00, '2024-07-15 12:38:38', '2024-07-15 12:38:38'),
	(42, 'PHILHEALTH', 200.00, '2024-07-15 12:38:38', '2024-07-15 12:38:38'),
	(42, 'PAGIBIG', 100.00, '2024-07-15 12:38:38', '2024-07-15 12:38:38');

-- Dumping structure for table payroll_db.payroll_earnings
CREATE TABLE IF NOT EXISTS `payroll_earnings` (
  `payslip_id` int unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `amount` float(12,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `payslip_id` (`payslip_id`),
  CONSTRAINT `payroll_earnings_payslip_id_foreign` FOREIGN KEY (`payslip_id`) REFERENCES `payslips` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table payroll_db.payroll_earnings: ~0 rows (approximately)

-- Dumping structure for table payroll_db.payslips
CREATE TABLE IF NOT EXISTS `payslips` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `payroll_id` int unsigned NOT NULL,
  `employee_id` int unsigned NOT NULL,
  `salary` float(12,2) NOT NULL DEFAULT '0.00',
  `present` float(12,2) NOT NULL DEFAULT '0.00',
  `ot_rate` float(12,2) NOT NULL DEFAULT '0.00',
  `ot_hr` float(12,2) NOT NULL DEFAULT '0.00',
  `leg_rate` float(12,2) NOT NULL DEFAULT '0.00',
  `leg_hr` float(12,2) NOT NULL DEFAULT '0.00',
  `sp_rate` float(12,2) NOT NULL DEFAULT '0.00',
  `sp_hr` float(12,2) NOT NULL DEFAULT '0.00',
  `late_undertime` float(12,2) NOT NULL DEFAULT '0.00',
  `witholding_tax` float(12,2) NOT NULL DEFAULT '0.00',
  `net` float(12,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `late_undertime_deduction` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `payroll_id` (`payroll_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `payslips_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payslips_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table payroll_db.payslips: ~7 rows (approximately)
INSERT INTO `payslips` (`id`, `payroll_id`, `employee_id`, `salary`, `present`, `ot_rate`, `ot_hr`, `leg_rate`, `leg_hr`, `sp_rate`, `sp_hr`, `late_undertime`, `witholding_tax`, `net`, `created_at`, `updated_at`, `late_undertime_deduction`) VALUES
	(30, 7, 31, 5000.00, 48.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 5000.00, '2024-07-10 11:58:24', '2024-07-10 11:58:24', 0.00),
	(31, 7, 33, 5000.00, 48.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 5000.00, '2024-07-10 11:59:17', '2024-07-10 11:59:17', 0.00),
	(32, 7, 30, 2400.00, 40.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 40.00, 0.00, 1966.67, '2024-07-10 14:33:41', '2024-07-10 14:33:41', 33.33),
	(38, 8, 30, 3000.00, 48.00, 60.00, 2.00, 50.00, 3.00, 45.00, 4.00, 45.00, 0.00, 3403.12, '2024-07-13 10:14:47', '2024-07-13 10:14:47', 46.88),
	(40, 8, 34, 4500.00, 45.00, 60.00, 3.00, 50.00, 8.00, 30.00, 4.00, 45.00, 0.00, 4848.44, '2024-07-15 08:32:01', '2024-07-15 08:32:01', 70.31),
	(41, 8, 31, 5000.00, 45.00, 60.00, 2.00, 50.00, 3.00, 45.00, 4.00, 30.00, 0.00, 4572.42, '2024-07-15 10:26:06', '2024-07-15 10:26:06', 52.08),
	(42, 8, 33, 3000.00, 45.00, 60.00, 2.00, 50.00, 4.00, 45.00, 4.00, 40.00, 0.00, 2757.83, '2024-07-15 12:38:38', '2024-07-15 12:38:38', 41.67);

-- Dumping structure for table payroll_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table payroll_db.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
	(1, 'Administrator', 'admin@mail.com', '$2y$10$KDBSRIVimyaM8Ig5RV9IaOOIpWpTdGPZuU3sjT32x4Y9dpY28t56C', '2022-06-23 09:35:38', '2022-06-23 09:35:38'),
	(2, 'john', 'john@mail.com', '$2y$10$UaUDYZoaVDeDphQv3KbdnuFSh.HubMtTDQEzv1tKQMxr4EMO7oe1O', '2024-07-06 13:16:52', '2024-07-06 13:16:52'),
	(3, 'mariele', 'mariele@mail.com', '$2y$10$AvFgzUvSZMHMH.jd.MXl2e/E6yySQ1sH8TierGbPiXBCLbGosu8QC', '2024-07-08 11:24:39', '2024-07-08 11:24:39');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
