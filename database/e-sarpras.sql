-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 23, 2026 at 07:13 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-sarpras`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessibility_features`
--

CREATE TABLE `accessibility_features` (
  `id` int(10) UNSIGNED NOT NULL,
  `building_id` int(10) UNSIGNED DEFAULT NULL,
  `room_id` int(10) UNSIGNED DEFAULT NULL,
  `feature_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `announcement_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Informasi',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `published_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `apar`
--

CREATE TABLE `apar` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `building_id` int(10) UNSIGNED DEFAULT NULL,
  `floor_id` int(10) UNSIGNED DEFAULT NULL,
  `room_id` int(10) UNSIGNED DEFAULT NULL,
  `apar_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `last_service_date` date DEFAULT NULL,
  `next_service_date` date DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baik',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` int(10) UNSIGNED NOT NULL,
  `approval_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approval_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` int(10) UNSIGNED DEFAULT NULL,
  `approver_user_id` int(10) UNSIGNED DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `note` text COLLATE utf8mb4_unicode_ci,
  `signature_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int(10) UNSIGNED NOT NULL,
  `asset_category_id` int(10) UNSIGNED NOT NULL,
  `building_id` int(10) UNSIGNED DEFAULT NULL,
  `room_id` int(10) UNSIGNED DEFAULT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acquisition_date` date DEFAULT NULL,
  `acquisition_value` decimal(18,2) NOT NULL DEFAULT '0.00',
  `useful_life_years` int(11) DEFAULT NULL,
  `salvage_value` decimal(15,2) DEFAULT NULL,
  `condition` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baik',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `ownership` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warranty_expiry` date DEFAULT NULL,
  `is_borrowable` tinyint(1) NOT NULL DEFAULT '1',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_attachments`
--

CREATE TABLE `asset_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `asset_id` int(10) UNSIGNED NOT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_categories`
--

CREATE TABLE `asset_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_categories`
--

INSERT INTO `asset_categories` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'GEDUNG', 'Gedung', 'Bangunan fisik kampus', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(2, 'PRASARANA', 'Prasarana', 'Infrastruktur pendukung kampus', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(3, 'ELEKTRONIK', 'Alat Elektronik', 'Perangkat elektronik dan teknologi informasi', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(4, 'MEBEL', 'Mebel', 'Meja, kursi, lemari, dan perabot', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(5, 'KENDARAAN', 'Kendaraan', 'Kendaraan operasional kampus', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(6, 'LAB', 'Alat Laboratorium', 'Peralatan laboratorium dan praktik', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(7, 'KANTOR', 'Alat Kantor', 'Peralatan administrasi dan perkantoran', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(8, 'K3', 'K3 dan Keselamatan', 'Peralatan keselamatan dan mitigasi', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(9, 'JARINGAN', 'Jaringan dan Telekomunikasi', 'Perangkat jaringan dan komunikasi', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(10, 'LAINNYA', 'Lainnya', 'Aset lain yang belum terkategori', '2026-08-24 19:06:23', '2026-08-24 19:06:23');

-- --------------------------------------------------------

--
-- Table structure for table `asset_condition_histories`
--

CREATE TABLE `asset_condition_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `asset_id` int(10) UNSIGNED NOT NULL,
  `old_condition` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_condition` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `changed_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_disposals`
--

CREATE TABLE `asset_disposals` (
  `id` int(10) UNSIGNED NOT NULL,
  `asset_id` int(10) UNSIGNED NOT NULL,
  `disposal_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disposal_date` date DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `condition` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_labels`
--

CREATE TABLE `asset_labels` (
  `id` int(10) UNSIGNED NOT NULL,
  `asset_id` int(10) UNSIGNED NOT NULL,
  `label_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_code` text COLLATE utf8mb4_unicode_ci,
  `barcode` text COLLATE utf8mb4_unicode_ci,
  `printed_at` datetime DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_values` longtext COLLATE utf8mb4_unicode_ci,
  `new_values` longtext COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `module`, `record_id`, `old_values`, `new_values`, `ip_address`, `created_at`) VALUES
(1, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-24 20:42:38'),
(2, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-24 22:06:57'),
(3, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-24 22:06:59'),
(4, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-24 22:08:09'),
(5, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 06:41:11'),
(6, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 06:47:40'),
(7, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 07:03:03'),
(8, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 07:26:09'),
(9, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 07:27:57'),
(10, 1, 'stock.create', 'stock', '1', NULL, '{\"item_code\":\"001\"}', '::1', '2026-08-25 07:30:14'),
(11, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 07:31:48'),
(12, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 07:32:39'),
(13, 1, 'building.create', 'building', '1', NULL, '{\"code\":\"DJA 1\"}', '::1', '2026-08-25 07:34:53'),
(14, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 07:34:56'),
(15, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 07:34:57'),
(16, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 07:34:58'),
(17, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 07:37:07'),
(18, 1, 'building.update', 'building', '1', '{\"id\":1,\"code\":\"DJA 1\",\"name\":\"Gedung Djazman Alkindi 1\",\"address\":\"Lantai 1 Djazman Alkindi\",\"ownership_status\":\"Milik Sendiri\",\"land_area\":\"100.00\",\"building_area\":\"95.00\",\"year_built\":2024,\"condition\":\"Baik\",\"is_disability_friendly\":1,\"has_ramp\":0,\"has_disability_toilet\":1,\"has_lift\":0,\"has_guide_path\":1,\"photo\":null,\"notes\":\"Mohon menjaga ruangan dengan sebaik-baiknya, jangan di coret dan jagalah kebersihan\",\"created_by\":null,\"created_at\":\"2026-08-25 07:34:53\",\"updated_at\":\"2026-08-25 07:34:53\"}', '{\"_token\":\"d9c0b206942e85ecf68a92456f14d4a94ff543066c0ea5bdf4e267560abc5196\",\"code\":\"DJA\",\"name\":\"Gedung Djazman Alkindi\",\"address\":\"Universitas Muhammadiyah Maumere\",\"ownership_status\":\"Milik Sendiri\",\"year_built\":\"2024\",\"land_area\":\"100.00\",\"building_area\":\"95.00\",\"condition\":\"Baik\",\"is_disability_friendly\":\"on\",\"has_disability_toilet\":\"on\",\"has_guide_path\":\"on\",\"notes\":\"Mohon menjaga ruangan dengan sebaik-baiknya, jangan di coret dan jagalah kebersihan\"}', '::1', '2026-08-25 07:37:43'),
(19, 1, 'room.create', 'room', '1', NULL, '{\"code\":\"DJA 1\"}', '::1', '2026-08-25 07:38:44'),
(20, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 07:39:40'),
(21, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 08:08:56'),
(22, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 08:57:23'),
(23, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 08:57:40'),
(24, 1, 'survey.create', 'survey', '1', NULL, NULL, '::1', '2026-08-25 08:58:26'),
(25, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 08:58:31'),
(26, NULL, 'survey.response', 'survey', '1', NULL, NULL, '::1', '2026-08-25 08:59:30'),
(27, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 08:59:37'),
(28, 1, 'survey.question.add', 'survey', '1', NULL, NULL, '::1', '2026-08-25 09:01:20'),
(29, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 09:01:26'),
(30, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 09:14:27'),
(31, 1, 'report.print', 'report', NULL, NULL, '{\"type\":\"assets\"}', '::1', '2026-08-25 09:14:45'),
(32, 1, 'backup.view', 'backup', NULL, NULL, NULL, '::1', '2026-08-25 09:37:33'),
(33, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 09:37:59'),
(34, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 09:38:41'),
(35, 1, 'backup.view', 'backup', NULL, NULL, NULL, '::1', '2026-08-25 09:38:43'),
(36, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 09:50:13'),
(37, NULL, 'survey.response', 'survey', '1', NULL, NULL, '::1', '2026-08-25 12:13:29'),
(38, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 13:59:39'),
(39, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 15:50:16'),
(40, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 15:50:43'),
(41, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 15:53:56'),
(42, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 15:57:32'),
(43, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:01:06'),
(44, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:04:10'),
(45, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:06:14'),
(46, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:06:43'),
(47, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:07:47'),
(48, 2, 'login.success', 'auth', '2', NULL, NULL, '::1', '2026-08-25 16:08:16'),
(49, 2, 'login.logout', 'auth', '2', NULL, NULL, '::1', '2026-08-25 16:10:28'),
(50, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:10:41'),
(51, 1, 'settings.update', 'settings', NULL, NULL, NULL, '::1', '2026-08-25 16:12:03'),
(52, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:12:08'),
(53, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:12:43'),
(54, 1, 'settings.update', 'settings', NULL, NULL, NULL, '::1', '2026-08-25 16:14:16'),
(55, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:14:20'),
(56, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:14:31'),
(57, 1, 'floor.create', 'floor', '1', NULL, '{\"building_id\":\"1\",\"level\":\"2\"}', '::1', '2026-08-25 16:19:28'),
(58, 1, 'building.update', 'building', '1', '{\"id\":1,\"code\":\"DJA\",\"name\":\"Gedung Djazman Alkindi\",\"address\":\"Universitas Muhammadiyah Maumere\",\"ownership_status\":\"Milik Sendiri\",\"land_area\":\"100.00\",\"building_area\":\"95.00\",\"year_built\":2024,\"condition\":\"Baik\",\"is_disability_friendly\":1,\"has_ramp\":0,\"has_disability_toilet\":1,\"has_lift\":0,\"has_guide_path\":1,\"photo\":null,\"notes\":\"Mohon menjaga ruangan dengan sebaik-baiknya, jangan di coret dan jagalah kebersihan\",\"created_by\":null,\"created_at\":\"2026-08-25 07:34:53\",\"updated_at\":\"2026-08-25 07:37:43\"}', '{\"_token\":\"3d847f5c755b89fbed08969699bd4c7fe662e6db0479d0c8b3b1c88521233dec\",\"code\":\"DA-001-26-PERPUS\",\"name\":\"Gedung Perpustakaan Lepolima\",\"address\":\"Lingkar Luar-Lepolima\",\"ownership_status\":\"Milik Sendiri\",\"year_built\":\"2024\",\"land_area\":\"100.00\",\"building_area\":\"95.00\",\"condition\":\"Baik\",\"is_disability_friendly\":\"on\",\"has_disability_toilet\":\"on\",\"has_guide_path\":\"on\",\"notes\":\"Mohon menjaga ruangan dengan sebaik-baiknya, jangan di coret dan jagalah kebersihan\"}', '::1', '2026-08-25 16:20:34'),
(59, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:20:38'),
(60, NULL, 'room_booking.create', 'room_booking', '1', NULL, '{\"booking_code\":\"RBM-20260825-0001\"}', '::1', '2026-08-25 16:23:10'),
(61, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:23:14'),
(62, 1, 'room_booking.disetujui', 'room_booking', '1', NULL, NULL, '::1', '2026-08-25 16:23:38'),
(63, 1, 'room.update', 'room', '1', '{\"id\":1,\"building_id\":1,\"floor_id\":null,\"code\":\"DJA 1\",\"name\":\"Gedung Djazman Alkindi 1\",\"room_type\":\"Ruang Kelas\",\"capacity\":25,\"area\":\"100.02\",\"condition\":\"Baik\",\"status\":\"Aktif\",\"facilities\":\"Proyektor\",\"is_disability_friendly\":1,\"photo\":null,\"notes\":\"\",\"created_by\":null,\"created_at\":\"2026-08-25 07:38:44\",\"updated_at\":\"2026-08-25 07:38:44\"}', '{\"_token\":\"d88da754672817cc56ec212ebb45082918ab051adf2b5f32d5c354eba8758c1a\",\"code\":\"DA-001-26-PERPUS\",\"name\":\"Gedung Perpustakaan Lepolima\",\"building_id\":\"1\",\"floor_id\":\"1\",\"room_type\":\"Ruang Kelas\",\"capacity\":\"25\",\"area\":\"100.02\",\"condition\":\"Baik\",\"status\":\"Aktif\",\"is_disability_friendly\":\"on\",\"facilities\":\"Proyektor, Wifi, Ac\",\"notes\":\"Gunakan ruangan ini dengan sebaik-baiknya\"}', '::1', '2026-08-25 16:25:01'),
(64, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:27:05'),
(65, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:28:45'),
(66, 1, 'settings.update', 'settings', NULL, NULL, NULL, '::1', '2026-08-25 16:45:48'),
(67, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:45:55'),
(68, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:49:03'),
(69, 1, 'settings.update', 'settings', NULL, NULL, NULL, '::1', '2026-08-25 16:49:17'),
(70, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:49:20'),
(71, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:49:37'),
(72, 1, 'room.update', 'room', '1', '{\"id\":1,\"building_id\":1,\"floor_id\":1,\"code\":\"DA-001-26-PERPUS\",\"name\":\"Gedung Perpustakaan Lepolima\",\"room_type\":\"Ruang Kelas\",\"capacity\":25,\"area\":\"100.02\",\"condition\":\"Baik\",\"status\":\"Aktif\",\"facilities\":\"Proyektor, Wifi, Ac\",\"is_disability_friendly\":1,\"photo\":null,\"notes\":\"Gunakan ruangan ini dengan sebaik-baiknya\",\"created_by\":null,\"created_at\":\"2026-08-25 07:38:44\",\"updated_at\":\"2026-08-25 16:25:01\"}', '{\"_token\":\"37d43342d2e5480825ea1d981f3f7243b8e83bb9ca8b15c02ab715831d5fc8b4\",\"code\":\"DA-001-26-PERPUS\",\"name\":\"Gedung Perpustakaan Lepolima\",\"building_id\":\"1\",\"floor_id\":\"1\",\"room_type\":\"Ruang Kelas\",\"capacity\":\"25\",\"area\":\"100.02\",\"condition\":\"Baik\",\"status\":\"Aktif\",\"is_disability_friendly\":\"on\",\"facilities\":\"Proyektor, Wifi, Ac\",\"notes\":\"Gunakan ruangan ini dengan sebaik-baiknya\"}', '::1', '2026-08-25 16:55:28'),
(73, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 16:55:31'),
(74, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 18:09:19'),
(75, 1, 'template.update', 'letter', '6', NULL, NULL, '::1', '2026-08-25 18:11:22'),
(76, 1, 'template.update', 'letter', '4', NULL, NULL, '::1', '2026-08-25 18:11:51'),
(77, 1, 'letter.outgoing', 'letter', '1', NULL, '{\"number\":\"B\\/001\\/SARPRAS\\/VIII\\/2026\"}', '::1', '2026-08-25 18:13:42'),
(78, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 18:31:56'),
(79, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 18:39:55'),
(80, 1, 'room.update', 'room', '1', '{\"id\":1,\"building_id\":1,\"floor_id\":1,\"code\":\"DA-001-26-PERPUS\",\"name\":\"Gedung Perpustakaan Lepolima\",\"room_type\":\"Ruang Kelas\",\"capacity\":25,\"area\":\"100.02\",\"condition\":\"Baik\",\"status\":\"Aktif\",\"facilities\":\"Proyektor, Wifi, Ac\",\"is_disability_friendly\":1,\"photo\":null,\"notes\":\"Gunakan ruangan ini dengan sebaik-baiknya\",\"created_by\":null,\"created_at\":\"2026-08-25 07:38:44\",\"updated_at\":\"2026-08-25 16:25:01\"}', '{\"_token\":\"02bdb245c24d4990097dfe0456dcf59d2fffcca442c8be62e3d2d7b67e0d79c2\",\"code\":\"DA-001-26-PERPUS\",\"name\":\"Gedung Perpustakaan Lepolima\",\"building_id\":\"1\",\"floor_id\":\"1\",\"room_type\":\"Ruang Kelas\",\"capacity\":\"25\",\"area\":\"100.02\",\"condition\":\"Baik\",\"status\":\"Aktif\",\"is_disability_friendly\":\"on\",\"facilities\":\"Proyektor, Wifi, Ac\",\"notes\":\"Gunakan ruangan ini dengan sebaik-baiknya\"}', '::1', '2026-08-25 18:40:15'),
(81, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 18:42:38'),
(82, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 18:56:05'),
(83, 1, 'building.update', 'building', '1', '{\"id\":1,\"code\":\"DA-001-26-PERPUS\",\"name\":\"Gedung Perpustakaan Lepolima\",\"address\":\"Lingkar Luar-Lepolima\",\"ownership_status\":\"Milik Sendiri\",\"land_area\":\"100.00\",\"building_area\":\"95.00\",\"year_built\":2024,\"condition\":\"Baik\",\"is_disability_friendly\":1,\"has_ramp\":0,\"has_disability_toilet\":1,\"has_lift\":0,\"has_guide_path\":1,\"photo\":null,\"notes\":\"Mohon menjaga ruangan dengan sebaik-baiknya, jangan di coret dan jagalah kebersihan\",\"created_by\":null,\"created_at\":\"2026-08-25 07:34:53\",\"updated_at\":\"2026-08-25 16:20:34\"}', '{\"_token\":\"41471aed006bf52531758341c9e1f71dca5ae0e6dfd6a7c6165ecec3354da5ab\",\"code\":\"DA-001-26-PERPUS\",\"name\":\"Gedung Perpustakaan Lepolima\",\"address\":\"Lingkar Luar-Lepolima\",\"ownership_status\":\"Milik Sendiri\",\"year_built\":\"2024\",\"land_area\":\"100.00\",\"building_area\":\"95.00\",\"condition\":\"Baik\",\"is_disability_friendly\":\"on\",\"has_disability_toilet\":\"on\",\"has_guide_path\":\"on\",\"notes\":\"Mohon menjaga ruangan dengan sebaik-baiknya, jangan di coret dan jagalah kebersihan\"}', '::1', '2026-08-25 19:02:32'),
(84, 1, 'building.update', 'building', '1', '{\"id\":1,\"code\":\"DA-001-26-PERPUS\",\"name\":\"Gedung Perpustakaan Lepolima\",\"address\":\"Lingkar Luar-Lepolima\",\"ownership_status\":\"Milik Sendiri\",\"land_area\":\"100.00\",\"building_area\":\"95.00\",\"year_built\":2024,\"condition\":\"Baik\",\"is_disability_friendly\":1,\"has_ramp\":0,\"has_disability_toilet\":1,\"has_lift\":0,\"has_guide_path\":1,\"photo\":null,\"notes\":\"Mohon menjaga ruangan dengan sebaik-baiknya, jangan di coret dan jagalah kebersihan\",\"created_by\":null,\"created_at\":\"2026-08-25 07:34:53\",\"updated_at\":\"2026-08-25 16:20:34\"}', '{\"_token\":\"41471aed006bf52531758341c9e1f71dca5ae0e6dfd6a7c6165ecec3354da5ab\",\"code\":\"DA-001-26-PERPUS\",\"name\":\"Gedung Perpustakaan Lepolima\",\"address\":\"Lingkar Luar-Lepolima\",\"ownership_status\":\"Milik Sendiri\",\"year_built\":\"2024\",\"land_area\":\"100.00\",\"building_area\":\"95.00\",\"condition\":\"Baik\",\"is_disability_friendly\":\"on\",\"has_disability_toilet\":\"on\",\"has_lift\":\"on\",\"has_guide_path\":\"on\",\"notes\":\"Mohon menjaga ruangan dengan sebaik-baiknya, jangan di coret dan jagalah kebersihan\"}', '::1', '2026-08-25 19:02:47'),
(85, 1, 'room.update', 'room', '1', '{\"id\":1,\"building_id\":1,\"floor_id\":1,\"code\":\"DA-001-26-PERPUS\",\"name\":\"Gedung Perpustakaan Lepolima\",\"room_type\":\"Ruang Kelas\",\"capacity\":25,\"area\":\"100.02\",\"condition\":\"Baik\",\"status\":\"Aktif\",\"facilities\":\"Proyektor, Wifi, Ac\",\"is_disability_friendly\":1,\"photo\":null,\"notes\":\"Gunakan ruangan ini dengan sebaik-baiknya\",\"created_by\":null,\"created_at\":\"2026-08-25 07:38:44\",\"updated_at\":\"2026-08-25 16:25:01\"}', '{\"_token\":\"41471aed006bf52531758341c9e1f71dca5ae0e6dfd6a7c6165ecec3354da5ab\",\"code\":\"DA-001-26-PERPUS\",\"name\":\"Ruangan Perpustakaan Lepolima\",\"building_id\":\"1\",\"floor_id\":\"1\",\"room_type\":\"Perpustakaan\",\"capacity\":\"25\",\"area\":\"100.02\",\"condition\":\"Baik\",\"status\":\"Aktif\",\"is_disability_friendly\":\"on\",\"facilities\":\"Proyektor, Wifi, Ac\",\"notes\":\"Gunakan ruangan ini dengan sebaik-baiknya\"}', '::1', '2026-08-25 19:03:36'),
(86, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 19:03:43'),
(87, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 20:22:25'),
(88, 1, 'settings.update', 'settings', NULL, NULL, NULL, '::1', '2026-08-25 20:41:44'),
(89, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 20:41:51'),
(90, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 20:45:38'),
(91, 1, 'vendor.create', 'ticket', '1', NULL, '{\"name\":\"Rizal\"}', '::1', '2026-08-25 20:46:37'),
(92, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 20:52:30'),
(93, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-08-25 20:55:34'),
(94, 1, 'backup.view', 'backup', NULL, NULL, NULL, '::1', '2026-08-25 20:57:32'),
(95, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-08-25 20:57:35'),
(96, NULL, 'login.failed', 'auth', NULL, NULL, '{\"identifier\":\"admin\"}', '::1', '2026-09-22 19:48:52'),
(97, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-09-22 19:48:59'),
(98, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-09-22 19:52:42'),
(99, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-09-22 19:52:45'),
(100, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-09-22 20:32:50'),
(101, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-09-22 21:48:39'),
(102, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-09-22 21:56:46'),
(103, 1, 'login.success', 'auth', '1', NULL, NULL, '::1', '2026-09-23 12:21:27'),
(104, 1, 'login.logout', 'auth', '1', NULL, NULL, '::1', '2026-09-23 12:21:55');

-- --------------------------------------------------------

--
-- Table structure for table `basts`
--

CREATE TABLE `basts` (
  `id` int(10) UNSIGNED NOT NULL,
  `bast_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bast_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Serah Terima Barang',
  `reference_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int(10) UNSIGNED DEFAULT NULL,
  `bast_date` date DEFAULT NULL,
  `first_party` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_party` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrow_items`
--

CREATE TABLE `borrow_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `borrow_request_id` int(10) UNSIGNED NOT NULL,
  `asset_id` int(10) UNSIGNED DEFAULT NULL,
  `stock_id` int(10) UNSIGNED DEFAULT NULL,
  `item_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT '1.00',
  `returned_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `condition_before` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition_after` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrow_requests`
--

CREATE TABLE `borrow_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `borrow_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `borrower_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dosen',
  `borrower_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `borrow_date` datetime DEFAULT NULL,
  `expected_return_date` datetime DEFAULT NULL,
  `actual_return_date` datetime DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Verifikasi',
  `approval_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int(10) UNSIGNED NOT NULL,
  `fiscal_year` smallint(6) NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budget_amount` decimal(18,2) NOT NULL DEFAULT '0.00',
  `realized_amount` decimal(18,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `ownership_status` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `land_area` decimal(12,2) DEFAULT NULL,
  `building_area` decimal(12,2) DEFAULT NULL,
  `year_built` smallint(6) DEFAULT NULL,
  `condition` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baik',
  `is_disability_friendly` tinyint(1) NOT NULL DEFAULT '0',
  `has_ramp` tinyint(1) NOT NULL DEFAULT '0',
  `has_disability_toilet` tinyint(1) NOT NULL DEFAULT '0',
  `has_lift` tinyint(1) NOT NULL DEFAULT '0',
  `has_guide_path` tinyint(1) NOT NULL DEFAULT '0',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `code`, `name`, `address`, `ownership_status`, `land_area`, `building_area`, `year_built`, `condition`, `is_disability_friendly`, `has_ramp`, `has_disability_toilet`, `has_lift`, `has_guide_path`, `photo`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'DA-001-26-PERPUS', 'Gedung Perpustakaan Lepolima', 'Lingkar Luar-Lepolima', 'Milik Sendiri', 100.00, 95.00, 2024, 'Baik', 1, 0, 1, 1, 1, 'buildings/12d36bfa27fc5590f9ef456c5c401dbd1787659367.jpg', 'Mohon menjaga ruangan dengan sebaik-baiknya, jangan di coret dan jagalah kebersihan', NULL, '2026-08-25 07:34:53', '2026-08-25 19:02:47');

-- --------------------------------------------------------

--
-- Table structure for table `campus_maps`
--

CREATE TABLE `campus_maps` (
  `id` int(11) NOT NULL,
  `building_id` int(11) NOT NULL,
  `floor_level` int(11) NOT NULL DEFAULT '1',
  `svg_content` longtext COLLATE utf8mb4_unicode_ci,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baru',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disaster_drills`
--

CREATE TABLE `disaster_drills` (
  `id` int(10) UNSIGNED NOT NULL,
  `drill_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drill_date` date DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `participant_count` int(11) NOT NULL DEFAULT '0',
  `result` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispositions`
--

CREATE TABLE `dispositions` (
  `id` int(10) UNSIGNED NOT NULL,
  `disposition_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letter_id` int(10) UNSIGNED NOT NULL,
  `disposition_date` date DEFAULT NULL,
  `from_user_id` int(10) UNSIGNED DEFAULT NULL,
  `to_user_id` int(10) UNSIGNED DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `instruction` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `floors`
--

CREATE TABLE `floors` (
  `id` int(10) UNSIGNED NOT NULL,
  `building_id` int(10) UNSIGNED NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `floors`
--

INSERT INTO `floors` (`id`, `building_id`, `level`, `name`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Layanan Sirkulasi', NULL, '2026-08-25 16:19:28', '2026-08-25 16:19:28');

-- --------------------------------------------------------

--
-- Table structure for table `item_requests`
--

CREATE TABLE `item_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ATK',
  `requester_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dosen',
  `requester_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `unit_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci,
  `needed_date` date DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Verifikasi',
  `approval_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `total_estimated_cost` decimal(18,2) NOT NULL DEFAULT '0.00',
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_request_items`
--

CREATE TABLE `item_request_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_request_id` int(10) UNSIGNED NOT NULL,
  `stock_id` int(10) UNSIGNED DEFAULT NULL,
  `item_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text COLLATE utf8mb4_unicode_ci,
  `quantity` decimal(12,2) NOT NULL DEFAULT '1.00',
  `unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_price` decimal(18,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `letters`
--

CREATE TABLE `letters` (
  `id` int(10) UNSIGNED NOT NULL,
  `letter_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letter_date` date DEFAULT NULL,
  `direction` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'incoming',
  `letter_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Permohonan',
  `sender_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_unit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_unit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Masuk',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letters`
--

INSERT INTO `letters` (`id`, `letter_number`, `letter_date`, `direction`, `letter_type`, `sender_name`, `sender_unit`, `recipient_name`, `recipient_unit`, `subject`, `content`, `status`, `file_path`, `related_type`, `related_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'B/001/SARPRAS/VIII/2026', '2026-08-25', 'outgoing', 'Surat Peminjaman Barang', NULL, NULL, 'Yolis', 'Gankz', 'Penolakan Permohonan', '\r\n\r\n\r\nNomor      : B/001/SARPRAS/VIII/2026\r\nLampiran   : 1\r\nPerihal    : Permohonan Peminjaman Barang\r\n\r\nKepada Yth.\r\nYolis - Gankz\n\ndi Maumere\r\n\r\nDengan hormat,\r\n\r\nBerdasarkan permohonan yang diajukan, bersama ini kami sampaikan permohonan peminjaman barang sebagai berikut:\r\n\r\nPermintaan pengajuan barang kami tolak\r\n\r\nDemikian surat ini kami sampaikan. Atas perhatian dan kerja sama yang baik, kami ucapkan terima kasih.\r\n\r\nMaumere, Selasa, 25 Agustus 2026\r\nKepala Sarpras,\r\n\r\n(paraf)\r\nYolis Libman\r\nNIP. NIP Kepala Sarpras', 'Terkirim', NULL, NULL, NULL, 1, '2026-08-25 18:13:42', '2026-08-25 18:13:42');

-- --------------------------------------------------------

--
-- Table structure for table `letter_attachments`
--

CREATE TABLE `letter_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `letter_id` int(10) UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `letter_templates`
--

CREATE TABLE `letter_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letter_templates`
--

INSERT INTO `letter_templates` (`id`, `code`, `name`, `subject`, `content`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'SURAT_PEMINJAMAN_BARANG', 'Surat Peminjaman Barang', 'Permohonan Peminjaman Barang', '[LOGO_KAMPUS]\r\n[KOP_KAMPUS]\r\n\r\nNomor      : [NOMOR_SURAT]\r\nLampiran   : [LAMPIRAN]\r\nPerihal    : Permohonan Peminjaman Barang\r\n\r\nKepada Yth.\r\n[PENERIMA]\n\ndi [TEMPAT]\r\n\r\nDengan hormat,\r\n\r\nBerdasarkan permohonan yang diajukan, bersama ini kami sampaikan permohonan peminjaman barang sebagai berikut:\r\n\r\n[ISI_SURAT]\r\n\r\nDemikian surat ini kami sampaikan. Atas perhatian dan kerja sama yang baik, kami ucapkan terima kasih.\r\n\r\n[TEMPAT], [TANGGAL_SURAT]\r\nKepala Sarpras,\r\n\r\n[PARAF_KEPALA_SARPRAS]\r\n[NAMA_KEPALA_SARPRAS]\r\nNIP. [NIP_KEPALA_SARPRAS]', 1, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(2, 'SURAT_PEMINJAMAN_RUANG', 'Surat Peminjaman Ruangan', 'Permohonan Peminjaman Ruangan', '[LOGO_KAMPUS]\r\n[KOP_KAMPUS]\r\n\r\nNomor      : [NOMOR_SURAT]\r\nLampiran   : [LAMPIRAN]\r\nPerihal    : Permohonan Peminjaman Ruangan\r\n\r\nKepada Yth.\r\n[PENERIMA]\r\ndi [TEMPAT]\r\n\r\nDengan hormat,\r\n\r\nBerdasarkan permohonan yang diajukan, bersama ini kami sampaikan permohonan peminjaman ruangan sebagai berikut:\r\n\r\n[ISI_SURAT]\r\n\r\nDemikian surat ini kami sampaikan. Atas perhatian dan kerja sama yang baik, kami ucapkan terima kasih.\r\n\r\n[TEMPAT], [TANGGAL_SURAT]\r\nKepala Sarpras,\r\n\r\n[PARAF_KEPALA_SARPRAS]\r\n[NAMA_KEPALA_SARPRAS]\r\nNIP. [NIP_KEPALA_SARPRAS]', 1, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(3, 'SURAT_PERSETUJUAN', 'Surat Persetujuan', 'Persetujuan Permohonan', '[LOGO_KAMPUS]\r\n[KOP_KAMPUS]\r\n\r\nNomor      : [NOMOR_SURAT]\r\nLampiran   : [LAMPIRAN]\r\nPerihal    : Persetujuan Permohonan\r\n\r\nKepada Yth.\r\n[PENERIMA]\r\ndi [TEMPAT]\r\n\r\nDengan hormat,\r\n\r\nSetelah dilakukan peninjauan terhadap permohonan yang diajukan, bersama ini kami sampaikan bahwa permohonan tersebut:\r\n\r\n[ISI_SURAT]\r\n\r\nDemikian surat persetujuan ini disampaikan untuk dapat dipergunakan sebagaimana mestinya.\r\n\r\n[TEMPAT], [TANGGAL_SURAT]\r\nKepala Sarpras,\r\n\r\n[PARAF_KEPALA_SARPRAS]\r\n[NAMA_KEPALA_SARPRAS]\r\nNIP. [NIP_KEPALA_SARPRAS]', 1, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(4, 'SURAT_PENOLAKAN', 'Surat Penolakan', 'Penolakan Permohonan', '[LOGO_KAMPUS]\r\n[KOP_KAMPUS]\r\n\r\nNomor      : [NOMOR_SURAT]\r\nLampiran   : [LAMPIRAN]\r\nPerihal    : Penolakan Permohonan\r\n\r\nKepada Yth.\r\n[PENERIMA]\r\ndi [TEMPAT]\r\n\r\nDengan hormat,\r\n\r\nSetelah dilakukan peninjauan terhadap permohonan yang diajukan, bersama ini kami sampaikan bahwa permohonan tersebut belum dapat disetujui dengan alasan sebagai berikut:\r\n\r\n[ISI_SURAT]\r\n\r\nDemikian surat ini disampaikan. Atas perhatian dan pengertian yang baik, kami ucapkan terima kasih.\r\n\r\n[TEMPAT], [TANGGAL_SURAT]\r\nKepala Sarpras,\r\n\r\n[PARAF_KEPALA_SARPRAS]\r\n[NAMA_KEPALA_SARPRAS]\r\nNIP. [NIP_KEPALA_SARPRAS]', 1, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(5, 'SURAT_DISPOSISI', 'Surat Disposisi', 'Disposisi Surat', '[LOGO_KAMPUS]\r\n[KOP_KAMPUS]\r\n\r\nNomor      : [NOMOR_SURAT]\r\nTanggal    : [TANGGAL_SURAT]\r\nPerihal    : Disposisi Surat\r\n\r\nDari       : [PENGIRIM]\r\nKepada     : [PENERIMA]\r\n\r\nIsi Disposisi:\r\n\r\n[ISI_SURAT]\r\n\r\nDemikian disposisi ini dibuat untuk ditindaklanjuti sebagaimana mestinya.\r\n\r\n[TEMPAT], [TANGGAL_SURAT]\r\n[PENGIRIM],\r\n\r\n[PARAF_KEPALA_SARPRAS]\r\n[NAMA_KEPALA_SARPRAS]\r\nNIP. [NIP_KEPALA_SARPRAS]', 1, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(6, 'BAST_BARANG', 'Berita Acara Serah Terima Barang', 'Berita Acara Serah Terima Barang', '[LOGO_KAMPUS]\r\n[KOP_KAMPUS]\r\n\r\nBERITA ACARA SERAH TERIMA BARANG\r\nNomor: [NOMOR_SURAT]\r\n\r\nPada hari ini, [HARI], tanggal [TANGGAL_SURAT], bertempat di [TEMPAT], telah dilakukan serah terima barang sebagai berikut:\r\n\r\n[ISI_SURAT]\r\n\r\nDemikian berita acara serah terima ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.\r\n\r\nPihak Pertama,\r\n\r\n\r\n[NAMA_PIHAK_PERTAMA]\r\n\r\nPihak Kedua,\r\n\r\n\r\n[NAMA_PIHAK_KEDUA]\r\n\r\nMengetahui,\r\nKepala Sarpras,\r\n\r\n[PARAF_KEPALA_SARPRAS]\r\n[NAMA_KEPALA_SARPRAS]\r\nNIP. [NIP_KEPALA_SARPRAS]', 1, '2026-08-24 19:06:23', '2026-08-24 19:06:23');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_logs`
--

CREATE TABLE `maintenance_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `maintenance_schedule_id` int(10) UNSIGNED DEFAULT NULL,
  `asset_id` int(10) UNSIGNED DEFAULT NULL,
  `room_id` int(10) UNSIGNED DEFAULT NULL,
  `technician_id` int(10) UNSIGNED DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED DEFAULT NULL,
  `maintenance_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Perbaikan',
  `action_date` date NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Selesai',
  `cost` decimal(18,2) NOT NULL DEFAULT '0.00',
  `sparepart` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_schedules`
--

CREATE TABLE `maintenance_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `asset_id` int(10) UNSIGNED DEFAULT NULL,
  `room_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Umum',
  `schedule_date` date NOT NULL,
  `frequency` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Terjadwal',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.view', 'Melihat dashboard', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(2, 'user.manage', 'Manajemen pengguna', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(3, 'role.manage', 'Manajemen role', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(4, 'permission.manage', 'Manajemen permission', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(5, 'setting.manage', 'Pengaturan sistem', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(6, 'audit.view', 'Lihat audit log', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(7, 'backup.manage', 'Backup sistem', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(8, 'report.view', 'Lihat laporan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(9, 'report.export', 'Ekspor laporan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(10, 'building.view', 'Lihat gedung', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(11, 'building.create', 'Tambah gedung', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(12, 'building.update', 'Ubah gedung', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(13, 'building.delete', 'Hapus gedung', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(14, 'floor.view', 'Lihat lantai', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(15, 'floor.create', 'Tambah lantai', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(16, 'floor.update', 'Ubah lantai', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(17, 'floor.delete', 'Hapus lantai', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(18, 'room.view', 'Lihat ruangan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(19, 'room.create', 'Tambah ruangan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(20, 'room.update', 'Ubah ruangan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(21, 'room.delete', 'Hapus ruangan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(22, 'asset.view', 'Lihat aset', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(23, 'asset.create', 'Tambah aset', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(24, 'asset.update', 'Ubah aset', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(25, 'asset.delete', 'Hapus aset', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(26, 'asset.label', 'Kelola label aset', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(27, 'asset.disposal', 'Kelola penghapusan aset', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(28, 'ticket.view', 'Lihat tiket', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(29, 'ticket.create', 'Buat tiket', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(30, 'ticket.update', 'Ubah tiket', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(31, 'ticket.delete', 'Hapus tiket', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(32, 'ticket.assign', 'Tugaskan tiket', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(33, 'ticket.close', 'Tutup tiket', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(34, 'maintenance.view', 'Lihat pemeliharaan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(35, 'maintenance.create', 'Tambah pemeliharaan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(36, 'maintenance.update', 'Ubah pemeliharaan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(37, 'maintenance.delete', 'Hapus pemeliharaan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(38, 'procurement.view', 'Lihat pengadaan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(39, 'procurement.create', 'Buat pengadaan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(40, 'procurement.update', 'Ubah pengadaan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(41, 'procurement.delete', 'Hapus pengadaan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(42, 'approval.view', 'Lihat persetujuan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(43, 'approval.approve', 'Beri persetujuan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(44, 'budget.view', 'Lihat anggaran', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(45, 'budget.manage', 'Kelola anggaran', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(46, 'borrow.view', 'Lihat peminjaman', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(47, 'borrow.create', 'Buat peminjaman', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(48, 'borrow.update', 'Ubah peminjaman', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(49, 'borrow.delete', 'Hapus peminjaman', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(50, 'borrow.approve', 'Setujui peminjaman', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(51, 'borrow.return', 'Proses pengembalian', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(52, 'room_booking.view', 'Lihat peminjaman ruangan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(53, 'room_booking.create', 'Buat peminjaman ruangan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(54, 'room_booking.update', 'Ubah peminjaman ruangan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(55, 'room_booking.delete', 'Hapus peminjaman ruangan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(56, 'room_booking.approve', 'Setujui peminjaman ruangan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(57, 'item_request.view', 'Lihat permintaan barang', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(58, 'item_request.create', 'Buat permintaan barang', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(59, 'item_request.update', 'Ubah permintaan barang', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(60, 'item_request.delete', 'Hapus permintaan barang', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(61, 'item_request.approve', 'Setujui permintaan barang', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(62, 'stock.view', 'Lihat stok', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(63, 'stock.create', 'Tambah stok', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(64, 'stock.update', 'Ubah stok', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(65, 'stock.delete', 'Hapus stok', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(66, 'letter.view', 'Lihat surat', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(67, 'letter.create', 'Buat surat', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(68, 'letter.update', 'Ubah surat', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(69, 'letter.delete', 'Hapus surat', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(70, 'letter.archive', 'Arsipkan surat', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(71, 'disposition.view', 'Lihat disposisi', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(72, 'disposition.create', 'Buat disposisi', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(73, 'disposition.update', 'Ubah disposisi', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(74, 'disposition.send', 'Kirim disposisi', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(75, 'template.manage', 'Kelola template surat', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(76, 'k3.view', 'Lihat K3L', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(77, 'k3.create', 'Tambah K3L', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(78, 'k3.update', 'Ubah K3L', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(79, 'k3.delete', 'Hapus K3L', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(80, 'survey.view', 'Lihat survei', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(81, 'survey.create', 'Buat survei', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(82, 'survey.update', 'Ubah survei', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(83, 'survey.delete', 'Hapus survei', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(84, 'survey.response', 'Isi survei', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(85, 'announcement.manage', 'Kelola pengumuman', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(86, 'notification.manage', 'Kelola notifikasi', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(87, 'contact.manage', 'Kelola pesan kontak', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(88, 'utilization.view', 'Lihat utilitas ruangan', NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23');

-- --------------------------------------------------------

--
-- Table structure for table `procurements`
--

CREATE TABLE `procurements` (
  `id` int(10) UNSIGNED NOT NULL,
  `procurement_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget_id` int(10) UNSIGNED DEFAULT NULL,
  `request_unit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `approval_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `total_estimated_cost` decimal(18,2) NOT NULL DEFAULT '0.00',
  `total_actual_cost` decimal(18,2) NOT NULL DEFAULT '0.00',
  `requested_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procurement_items`
--

CREATE TABLE `procurement_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `procurement_id` int(10) UNSIGNED NOT NULL,
  `item_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text COLLATE utf8mb4_unicode_ci,
  `quantity` decimal(12,2) NOT NULL DEFAULT '1.00',
  `unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_price` decimal(18,2) NOT NULL DEFAULT '0.00',
  `actual_price` decimal(18,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'super-admin-sarpras', 'Super Admin Sarpras', 'Akses penuh sistem e-Sarpras', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(2, 'admin-sarpras', 'Admin Sarpras', 'Pengelola operasional harian Sarpras', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(3, 'teknisi', 'Teknisi', 'Petugas teknis lapangan', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(4, 'pimpinan', 'Pimpinan', 'Pimpinan universitas atau unit terkait', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(5, 'dekan', 'Dekan', 'Pimpinan fakultas', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(6, 'staf-unit', 'Staf Unit', 'Staf prodi, fakultas, atau unit kampus', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(7, 'auditor', 'Auditor', 'Akses baca untuk audit dan laporan', '2026-08-24 19:06:23', '2026-08-24 19:06:23');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`) VALUES
(1, 1, 85, '2026-08-24 19:06:23'),
(2, 1, 43, '2026-08-24 19:06:23'),
(3, 1, 42, '2026-08-24 19:06:23'),
(4, 1, 23, '2026-08-24 19:06:23'),
(5, 1, 25, '2026-08-24 19:06:23'),
(6, 1, 27, '2026-08-24 19:06:23'),
(7, 1, 26, '2026-08-24 19:06:23'),
(8, 1, 24, '2026-08-24 19:06:23'),
(9, 1, 22, '2026-08-24 19:06:23'),
(10, 1, 6, '2026-08-24 19:06:23'),
(11, 1, 7, '2026-08-24 19:06:23'),
(12, 1, 50, '2026-08-24 19:06:23'),
(13, 1, 47, '2026-08-24 19:06:23'),
(14, 1, 49, '2026-08-24 19:06:23'),
(15, 1, 51, '2026-08-24 19:06:23'),
(16, 1, 48, '2026-08-24 19:06:23'),
(17, 1, 46, '2026-08-24 19:06:23'),
(18, 1, 45, '2026-08-24 19:06:23'),
(19, 1, 44, '2026-08-24 19:06:23'),
(20, 1, 11, '2026-08-24 19:06:23'),
(21, 1, 13, '2026-08-24 19:06:23'),
(22, 1, 12, '2026-08-24 19:06:23'),
(23, 1, 10, '2026-08-24 19:06:23'),
(24, 1, 87, '2026-08-24 19:06:23'),
(25, 1, 1, '2026-08-24 19:06:23'),
(26, 1, 72, '2026-08-24 19:06:23'),
(27, 1, 74, '2026-08-24 19:06:23'),
(28, 1, 73, '2026-08-24 19:06:23'),
(29, 1, 71, '2026-08-24 19:06:23'),
(30, 1, 15, '2026-08-24 19:06:23'),
(31, 1, 17, '2026-08-24 19:06:23'),
(32, 1, 16, '2026-08-24 19:06:23'),
(33, 1, 14, '2026-08-24 19:06:23'),
(34, 1, 61, '2026-08-24 19:06:23'),
(35, 1, 58, '2026-08-24 19:06:23'),
(36, 1, 60, '2026-08-24 19:06:23'),
(37, 1, 59, '2026-08-24 19:06:23'),
(38, 1, 57, '2026-08-24 19:06:23'),
(39, 1, 77, '2026-08-24 19:06:23'),
(40, 1, 79, '2026-08-24 19:06:23'),
(41, 1, 78, '2026-08-24 19:06:23'),
(42, 1, 76, '2026-08-24 19:06:23'),
(43, 1, 70, '2026-08-24 19:06:23'),
(44, 1, 67, '2026-08-24 19:06:23'),
(45, 1, 69, '2026-08-24 19:06:23'),
(46, 1, 68, '2026-08-24 19:06:23'),
(47, 1, 66, '2026-08-24 19:06:23'),
(48, 1, 35, '2026-08-24 19:06:23'),
(49, 1, 37, '2026-08-24 19:06:23'),
(50, 1, 36, '2026-08-24 19:06:23'),
(51, 1, 34, '2026-08-24 19:06:23'),
(52, 1, 86, '2026-08-24 19:06:23'),
(53, 1, 4, '2026-08-24 19:06:23'),
(54, 1, 39, '2026-08-24 19:06:23'),
(55, 1, 41, '2026-08-24 19:06:23'),
(56, 1, 40, '2026-08-24 19:06:23'),
(57, 1, 38, '2026-08-24 19:06:23'),
(58, 1, 9, '2026-08-24 19:06:23'),
(59, 1, 8, '2026-08-24 19:06:23'),
(60, 1, 3, '2026-08-24 19:06:23'),
(61, 1, 56, '2026-08-24 19:06:23'),
(62, 1, 53, '2026-08-24 19:06:23'),
(63, 1, 55, '2026-08-24 19:06:23'),
(64, 1, 54, '2026-08-24 19:06:23'),
(65, 1, 52, '2026-08-24 19:06:23'),
(66, 1, 19, '2026-08-24 19:06:23'),
(67, 1, 21, '2026-08-24 19:06:23'),
(68, 1, 20, '2026-08-24 19:06:23'),
(69, 1, 18, '2026-08-24 19:06:23'),
(70, 1, 5, '2026-08-24 19:06:23'),
(71, 1, 63, '2026-08-24 19:06:23'),
(72, 1, 65, '2026-08-24 19:06:23'),
(73, 1, 64, '2026-08-24 19:06:23'),
(74, 1, 62, '2026-08-24 19:06:23'),
(75, 1, 81, '2026-08-24 19:06:23'),
(76, 1, 83, '2026-08-24 19:06:23'),
(77, 1, 84, '2026-08-24 19:06:23'),
(78, 1, 82, '2026-08-24 19:06:23'),
(79, 1, 80, '2026-08-24 19:06:23'),
(80, 1, 75, '2026-08-24 19:06:23'),
(81, 1, 32, '2026-08-24 19:06:23'),
(82, 1, 33, '2026-08-24 19:06:23'),
(83, 1, 29, '2026-08-24 19:06:23'),
(84, 1, 31, '2026-08-24 19:06:23'),
(85, 1, 30, '2026-08-24 19:06:23'),
(86, 1, 28, '2026-08-24 19:06:23'),
(87, 1, 2, '2026-08-24 19:06:23'),
(88, 1, 88, '2026-08-24 19:06:23'),
(128, 2, 85, '2026-08-24 19:06:23'),
(129, 2, 43, '2026-08-24 19:06:23'),
(130, 2, 42, '2026-08-24 19:06:23'),
(131, 2, 23, '2026-08-24 19:06:23'),
(132, 2, 25, '2026-08-24 19:06:23'),
(133, 2, 27, '2026-08-24 19:06:23'),
(134, 2, 26, '2026-08-24 19:06:23'),
(135, 2, 24, '2026-08-24 19:06:23'),
(136, 2, 22, '2026-08-24 19:06:23'),
(137, 2, 50, '2026-08-24 19:06:23'),
(138, 2, 47, '2026-08-24 19:06:23'),
(139, 2, 49, '2026-08-24 19:06:23'),
(140, 2, 51, '2026-08-24 19:06:23'),
(141, 2, 48, '2026-08-24 19:06:23'),
(142, 2, 46, '2026-08-24 19:06:23'),
(143, 2, 45, '2026-08-24 19:06:23'),
(144, 2, 44, '2026-08-24 19:06:23'),
(145, 2, 11, '2026-08-24 19:06:23'),
(146, 2, 13, '2026-08-24 19:06:23'),
(147, 2, 12, '2026-08-24 19:06:23'),
(148, 2, 10, '2026-08-24 19:06:23'),
(149, 2, 87, '2026-08-24 19:06:23'),
(150, 2, 1, '2026-08-24 19:06:23'),
(151, 2, 72, '2026-08-24 19:06:23'),
(152, 2, 74, '2026-08-24 19:06:23'),
(153, 2, 73, '2026-08-24 19:06:23'),
(154, 2, 71, '2026-08-24 19:06:23'),
(155, 2, 15, '2026-08-24 19:06:23'),
(156, 2, 17, '2026-08-24 19:06:23'),
(157, 2, 16, '2026-08-24 19:06:23'),
(158, 2, 14, '2026-08-24 19:06:23'),
(159, 2, 61, '2026-08-24 19:06:23'),
(160, 2, 58, '2026-08-24 19:06:23'),
(161, 2, 60, '2026-08-24 19:06:23'),
(162, 2, 59, '2026-08-24 19:06:23'),
(163, 2, 57, '2026-08-24 19:06:23'),
(164, 2, 77, '2026-08-24 19:06:23'),
(165, 2, 79, '2026-08-24 19:06:23'),
(166, 2, 78, '2026-08-24 19:06:23'),
(167, 2, 76, '2026-08-24 19:06:23'),
(168, 2, 70, '2026-08-24 19:06:23'),
(169, 2, 67, '2026-08-24 19:06:23'),
(170, 2, 69, '2026-08-24 19:06:23'),
(171, 2, 68, '2026-08-24 19:06:23'),
(172, 2, 66, '2026-08-24 19:06:23'),
(173, 2, 35, '2026-08-24 19:06:23'),
(174, 2, 37, '2026-08-24 19:06:23'),
(175, 2, 36, '2026-08-24 19:06:23'),
(176, 2, 34, '2026-08-24 19:06:23'),
(177, 2, 86, '2026-08-24 19:06:23'),
(178, 2, 39, '2026-08-24 19:06:23'),
(179, 2, 41, '2026-08-24 19:06:23'),
(180, 2, 40, '2026-08-24 19:06:23'),
(181, 2, 38, '2026-08-24 19:06:23'),
(182, 2, 9, '2026-08-24 19:06:23'),
(183, 2, 8, '2026-08-24 19:06:23'),
(184, 2, 56, '2026-08-24 19:06:23'),
(185, 2, 53, '2026-08-24 19:06:23'),
(186, 2, 55, '2026-08-24 19:06:23'),
(187, 2, 54, '2026-08-24 19:06:23'),
(188, 2, 52, '2026-08-24 19:06:23'),
(189, 2, 19, '2026-08-24 19:06:23'),
(190, 2, 21, '2026-08-24 19:06:23'),
(191, 2, 20, '2026-08-24 19:06:23'),
(192, 2, 18, '2026-08-24 19:06:23'),
(193, 2, 63, '2026-08-24 19:06:23'),
(194, 2, 65, '2026-08-24 19:06:23'),
(195, 2, 64, '2026-08-24 19:06:23'),
(196, 2, 62, '2026-08-24 19:06:23'),
(197, 2, 81, '2026-08-24 19:06:23'),
(198, 2, 83, '2026-08-24 19:06:23'),
(199, 2, 84, '2026-08-24 19:06:23'),
(200, 2, 82, '2026-08-24 19:06:23'),
(201, 2, 80, '2026-08-24 19:06:23'),
(202, 2, 75, '2026-08-24 19:06:23'),
(203, 2, 32, '2026-08-24 19:06:23'),
(204, 2, 33, '2026-08-24 19:06:23'),
(205, 2, 29, '2026-08-24 19:06:23'),
(206, 2, 31, '2026-08-24 19:06:23'),
(207, 2, 30, '2026-08-24 19:06:23'),
(208, 2, 28, '2026-08-24 19:06:23'),
(209, 2, 88, '2026-08-24 19:06:23'),
(255, 3, 1, '2026-08-24 19:06:23'),
(256, 3, 36, '2026-08-24 19:06:23'),
(257, 3, 34, '2026-08-24 19:06:23'),
(258, 3, 32, '2026-08-24 19:06:23'),
(259, 3, 33, '2026-08-24 19:06:23'),
(260, 3, 30, '2026-08-24 19:06:23'),
(261, 3, 28, '2026-08-24 19:06:23'),
(262, 4, 43, '2026-08-24 19:06:23'),
(263, 4, 42, '2026-08-24 19:06:23'),
(264, 4, 22, '2026-08-24 19:06:23'),
(265, 4, 46, '2026-08-24 19:06:23'),
(266, 4, 44, '2026-08-24 19:06:23'),
(267, 4, 1, '2026-08-24 19:06:23'),
(268, 4, 71, '2026-08-24 19:06:23'),
(269, 4, 57, '2026-08-24 19:06:23'),
(270, 4, 76, '2026-08-24 19:06:23'),
(271, 4, 66, '2026-08-24 19:06:23'),
(272, 4, 34, '2026-08-24 19:06:23'),
(273, 4, 38, '2026-08-24 19:06:23'),
(274, 4, 9, '2026-08-24 19:06:23'),
(275, 4, 8, '2026-08-24 19:06:23'),
(276, 4, 52, '2026-08-24 19:06:23'),
(277, 4, 62, '2026-08-24 19:06:23'),
(278, 4, 80, '2026-08-24 19:06:23'),
(279, 4, 28, '2026-08-24 19:06:23'),
(280, 4, 88, '2026-08-24 19:06:23'),
(293, 5, 42, '2026-08-24 19:06:23'),
(294, 5, 22, '2026-08-24 19:06:23'),
(295, 5, 46, '2026-08-24 19:06:23'),
(296, 5, 1, '2026-08-24 19:06:23'),
(297, 5, 71, '2026-08-24 19:06:23'),
(298, 5, 57, '2026-08-24 19:06:23'),
(299, 5, 66, '2026-08-24 19:06:23'),
(300, 5, 38, '2026-08-24 19:06:23'),
(301, 5, 8, '2026-08-24 19:06:23'),
(302, 5, 52, '2026-08-24 19:06:23'),
(303, 5, 28, '2026-08-24 19:06:23'),
(304, 5, 88, '2026-08-24 19:06:23'),
(308, 6, 47, '2026-08-24 19:06:23'),
(309, 6, 46, '2026-08-24 19:06:23'),
(310, 6, 1, '2026-08-24 19:06:23'),
(311, 6, 58, '2026-08-24 19:06:23'),
(312, 6, 57, '2026-08-24 19:06:23'),
(313, 6, 53, '2026-08-24 19:06:23'),
(314, 6, 52, '2026-08-24 19:06:23'),
(315, 6, 84, '2026-08-24 19:06:23'),
(316, 6, 29, '2026-08-24 19:06:23'),
(317, 6, 28, '2026-08-24 19:06:23'),
(323, 7, 42, '2026-08-24 19:06:23'),
(324, 7, 22, '2026-08-24 19:06:23'),
(325, 7, 6, '2026-08-24 19:06:23'),
(326, 7, 46, '2026-08-24 19:06:23'),
(327, 7, 44, '2026-08-24 19:06:23'),
(328, 7, 10, '2026-08-24 19:06:23'),
(329, 7, 1, '2026-08-24 19:06:23'),
(330, 7, 71, '2026-08-24 19:06:23'),
(331, 7, 14, '2026-08-24 19:06:23'),
(332, 7, 57, '2026-08-24 19:06:23'),
(333, 7, 76, '2026-08-24 19:06:23'),
(334, 7, 66, '2026-08-24 19:06:23'),
(335, 7, 34, '2026-08-24 19:06:23'),
(336, 7, 38, '2026-08-24 19:06:23'),
(337, 7, 9, '2026-08-24 19:06:23'),
(338, 7, 8, '2026-08-24 19:06:23'),
(339, 7, 52, '2026-08-24 19:06:23'),
(340, 7, 18, '2026-08-24 19:06:23'),
(341, 7, 62, '2026-08-24 19:06:23'),
(342, 7, 80, '2026-08-24 19:06:23'),
(343, 7, 28, '2026-08-24 19:06:23'),
(344, 7, 88, '2026-08-24 19:06:23');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(10) UNSIGNED NOT NULL,
  `building_id` int(10) UNSIGNED NOT NULL,
  `floor_id` int(10) UNSIGNED DEFAULT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ruang Kelas',
  `capacity` int(11) NOT NULL DEFAULT '0',
  `area` decimal(10,2) DEFAULT NULL,
  `condition` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baik',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `facilities` longtext COLLATE utf8mb4_unicode_ci,
  `is_disability_friendly` tinyint(1) NOT NULL DEFAULT '0',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `building_id`, `floor_id`, `code`, `name`, `room_type`, `capacity`, `area`, `condition`, `status`, `facilities`, `is_disability_friendly`, `photo`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'DA-001-26-PERPUS', 'Ruangan Perpustakaan Lepolima', 'Perpustakaan', 25, 100.02, 'Baik', 'Aktif', 'Proyektor, Wifi, Ac', 1, 'rooms/36c2d441addd6d81df9d90d3e5c7d9061787659416.jpeg', 'Gunakan ruangan ini dengan sebaik-baiknya', NULL, '2026-08-25 07:38:44', '2026-08-25 19:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `room_bookings`
--

CREATE TABLE `room_bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `borrower_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dosen',
  `borrower_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `participant_count` int(11) NOT NULL DEFAULT '0',
  `facilities_needed` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Verifikasi',
  `approval_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_bookings`
--

INSERT INTO `room_bookings` (`id`, `booking_code`, `room_id`, `user_id`, `borrower_type`, `borrower_name`, `unit_name`, `activity_name`, `start_at`, `end_at`, `participant_count`, `facilities_needed`, `status`, `approval_status`, `approved_by`, `approved_at`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'RBM-20260825-0001', 1, NULL, 'Mahasiswa', 'Yolis Libman', '', 'Baca buku', '2026-08-25 16:22:00', '2026-08-25 18:00:00', 10, 'Wifi, Proyektor', 'Disetujui', 'Disetujui', 1, '2026-08-25 16:23:38', NULL, NULL, '2026-08-25 16:23:10', '2026-08-25 16:23:38');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'e-Sarpras', 'Nama aplikasi', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(2, 'app_version', '1.0.0', 'Versi aplikasi', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(3, 'campus_name', 'Universitas Maumere Jaya', 'Nama kampus', '2026-08-24 19:06:23', '2026-08-25 16:12:03'),
(4, 'campus_address', 'Lingkar Luar Lepo Lima', 'Alamat kampus', '2026-08-24 19:06:23', '2026-08-25 16:12:03'),
(5, 'campus_phone', '0000000000', 'Nomor telepon kampus', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(6, 'campus_email', 'info@kampus.ac.id', 'Email kampus', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(7, 'app_logo', 'settings/d382543d56c7eec26dc033f5fcaddc6a1787651357.png', 'Path logo kampus', '2026-08-24 19:06:23', '2026-08-25 16:49:17'),
(8, 'sarpras_head_name', 'Yolis Libman', 'Nama Kepala Sarpras untuk surat resmi', '2026-08-24 19:06:23', '2026-08-25 16:12:03'),
(9, 'sarpras_head_nip', 'NIP Kepala Sarpras', 'NIP Kepala Sarpras untuk surat resmi', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(10, 'letter_city', 'Maumere', 'Kota untuk tanggal surat', '2026-08-24 19:06:23', '2026-08-25 16:12:03'),
(11, 'letter_prefix', 'B', 'Prefix nomor surat keluar', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(12, 'default_timezone', 'Asia/Jakarta', 'Zona waktu aplikasi', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(13, 'max_upload_mb', '2', 'Batas maksimal upload dalam MB', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(14, 'allowed_upload_extensions', 'jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx', 'Ekstensi file yang diizinkan', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(15, 'maintenance_alert_days', '7', 'Jumlah hari peringatan jadwal pemeliharaan', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(16, 'app_favicon', 'settings/356f71e899bdcf2f5b5760ef9c4993db1787665304.png', NULL, '2026-08-25 20:41:44', '2026-08-25 20:41:44');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(10) UNSIGNED NOT NULL,
  `stock_category_id` int(10) UNSIGNED NOT NULL,
  `item_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text COLLATE utf8mb4_unicode_ci,
  `unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `minimum_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baik',
  `is_borrowable` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `stock_category_id`, `item_code`, `item_name`, `specification`, `unit`, `quantity`, `minimum_quantity`, `location`, `condition`, `is_borrowable`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, '001', 'Kabel HDMI', 'Kabel HDMI', 'PCS', 10.00, 1.00, 'Ruangan Sarpras', 'Baik', 1, 'Mohon digunakan sebaik-baiknya', NULL, '2026-08-25 07:30:14', '2026-08-25 07:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `stock_categories`
--

CREATE TABLE `stock_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_categories`
--

INSERT INTO `stock_categories` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'ATK', 'Alat Tulis Kantor', 'Barang habis pakai untuk administrasi', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(2, 'ELEKTRONIK', 'Barang Elektronik', 'Barang elektronik yang dapat diminta atau dipinjam', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(3, 'RUMAH_TANGGA', 'Perlengkapan Rumah Tangga', 'Perlengkapan kebersihan dan rumah tangga', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(4, 'LAB', 'Perlengkapan Laboratorium', 'Bahan dan alat laboratorium', '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(5, 'UMUM', 'Umum', 'Barang umum lainnya', '2026-08-24 19:06:23', '2026-08-24 19:06:23');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(10) UNSIGNED NOT NULL,
  `stock_id` int(10) UNSIGNED NOT NULL,
  `movement_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in',
  `quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `movement_date` datetime NOT NULL,
  `reference_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int(10) UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `stock_id`, `movement_type`, `quantity`, `movement_date`, `reference_type`, `reference_id`, `notes`, `created_by`, `created_at`) VALUES
(1, 1, 'in', 10.00, '2026-08-25 07:30:14', 'initial', NULL, 'Stok awal', 1, '2026-08-25 07:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `target_audience` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`id`, `title`, `slug`, `description`, `target_audience`, `start_date`, `end_date`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Survey layanan SARPRAS', 'survey-layanan-sarpras-1787623106', 'Mohon berikan masukan/usul yang bermanfaat', '', NULL, NULL, 1, 1, '2026-08-25 08:58:26', '2026-08-25 08:58:26');

-- --------------------------------------------------------

--
-- Table structure for table `survey_answers`
--

CREATE TABLE `survey_answers` (
  `id` int(10) UNSIGNED NOT NULL,
  `survey_response_id` int(10) UNSIGNED NOT NULL,
  `survey_question_id` int(10) UNSIGNED NOT NULL,
  `answer_text` longtext COLLATE utf8mb4_unicode_ci,
  `rating_value` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `survey_answers`
--

INSERT INTO `survey_answers` (`id`, `survey_response_id`, `survey_question_id`, `answer_text`, `rating_value`, `created_at`) VALUES
(1, 2, 1, NULL, 5, '2026-08-25 12:13:29');

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions`
--

CREATE TABLE `survey_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `survey_id` int(10) UNSIGNED NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `options` longtext COLLATE utf8mb4_unicode_ci,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `survey_questions`
--

INSERT INTO `survey_questions` (`id`, `survey_id`, `question`, `question_type`, `options`, `sort_order`, `is_required`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bagaimana sistem pelayanan yang kalian rasakan?', 'rating', NULL, 0, 1, '2026-08-25 09:01:20', '2026-08-25 09:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `survey_responses`
--

CREATE TABLE `survey_responses` (
  `id` int(10) UNSIGNED NOT NULL,
  `survey_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `respondent_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `respondent_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Selesai',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `survey_responses`
--

INSERT INTO `survey_responses` (`id`, `survey_id`, `user_id`, `respondent_type`, `respondent_name`, `email`, `rating`, `status`, `created_at`) VALUES
(1, 1, NULL, 'Mahasiswa', 'Antonius', NULL, NULL, 'Selesai', '2026-08-25 08:59:30'),
(2, 1, NULL, NULL, NULL, NULL, NULL, 'Selesai', '2026-08-25 12:13:29');

-- --------------------------------------------------------

--
-- Table structure for table `technicians`
--

CREATE TABLE `technicians` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `employee_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reported_by_user_id` int(10) UNSIGNED DEFAULT NULL,
  `reporter_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reporter_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dosen',
  `reporter_contact` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `building_id` int(10) UNSIGNED DEFAULT NULL,
  `room_id` int(10) UNSIGNED DEFAULT NULL,
  `asset_id` int(10) UNSIGNED DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Umum',
  `priority` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Normal',
  `sla_deadline` datetime DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Verifikasi',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `incident_date` datetime DEFAULT NULL,
  `assigned_technician_id` int(10) UNSIGNED DEFAULT NULL,
  `assigned_vendor_id` int(10) UNSIGNED DEFAULT NULL,
  `estimated_completion` date DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_attachments`
--

CREATE TABLE `ticket_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_histories`
--

CREATE TABLE `ticket_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `old_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `identity_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `user_type`, `identity_number`, `phone`, `avatar`, `is_active`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'superadmin@e-sarpras.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Admin e-Sarpras', 'admin', '19800101', '081200000001', NULL, 1, '2026-09-23 12:21:27', '2026-08-24 19:06:23', '2026-09-23 12:21:27'),
(2, 'pimpinan', 'pimpinan@e-sarpras.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pimpinan Kampus', 'pimpinan', '19750101', '081200000002', NULL, 1, '2026-08-25 16:08:16', '2026-08-24 19:06:23', '2026-08-25 16:08:16'),
(3, 'sarpras', 'sarpras@e-sarpras.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Sarpras', 'tendik', '19850101', '081200000003', NULL, 1, NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23'),
(4, 'teknisi', 'teknisi@e-sarpras.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Teknisi Sarpras', 'tendik', '19900101', '081200000004', NULL, 1, NULL, '2026-08-24 19:06:23', '2026-08-24 19:06:23');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`) VALUES
(1, 1, 1, '2026-08-24 19:06:23'),
(2, 2, 4, '2026-08-24 19:06:23'),
(3, 3, 2, '2026-08-24 19:06:23'),
(4, 4, 3, '2026-08-24 19:06:23');

-- --------------------------------------------------------

--
-- Table structure for table `utilization_logs`
--

CREATE TABLE `utilization_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `activity_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `participant_count` int(11) NOT NULL DEFAULT '0',
  `source_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_id` int(10) UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `utilization_logs`
--

INSERT INTO `utilization_logs` (`id`, `room_id`, `activity_name`, `start_at`, `end_at`, `participant_count`, `source_type`, `source_id`, `notes`, `created_by`, `created_at`) VALUES
(1, 1, 'Baca buku', '2026-08-25 16:22:00', '2026-08-25 18:00:00', 10, 'room_booking', 1, NULL, 1, '2026-08-25 16:23:38');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `services` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `contact_person`, `phone`, `email`, `address`, `services`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Rizal', 'Yolis Libman', '082147201903', NULL, NULL, 'Service WiFI Indihome', NULL, '2026-08-25 20:46:37', '2026-08-25 20:46:37');

-- --------------------------------------------------------

--
-- Table structure for table `waste_logs`
--

CREATE TABLE `waste_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `waste_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `volume` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handling_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handling_date` date DEFAULT NULL,
  `vendor_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accessibility_features`
--
ALTER TABLE `accessibility_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `building_id` (`building_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_announcements_slug` (`slug`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `apar`
--
ALTER TABLE `apar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_apar_code` (`code`),
  ADD KEY `building_id` (`building_id`),
  ADD KEY `floor_id` (`floor_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_approvals_code` (`approval_code`),
  ADD KEY `approver_user_id` (`approver_user_id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_assets_code` (`code`),
  ADD KEY `idx_assets_status` (`status`),
  ADD KEY `idx_assets_condition` (`condition`),
  ADD KEY `asset_category_id` (`asset_category_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_room` (`room_id`),
  ADD KEY `idx_building` (`building_id`);

--
-- Indexes for table `asset_attachments`
--
ALTER TABLE `asset_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `asset_categories`
--
ALTER TABLE `asset_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_asset_categories_code` (`code`);

--
-- Indexes for table `asset_condition_histories`
--
ALTER TABLE `asset_condition_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `changed_by` (`changed_by`);

--
-- Indexes for table `asset_disposals`
--
ALTER TABLE `asset_disposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `asset_labels`
--
ALTER TABLE `asset_labels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_asset_labels_code` (`label_code`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_logs_module` (`module`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `basts`
--
ALTER TABLE `basts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `borrow_items`
--
ALTER TABLE `borrow_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrow_request_id` (`borrow_request_id`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `stock_id` (`stock_id`);

--
-- Indexes for table `borrow_requests`
--
ALTER TABLE `borrow_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_borrow_requests_code` (`borrow_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_budgets_year_code` (`fiscal_year`,`code`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_buildings_code` (`code`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `campus_maps`
--
ALTER TABLE `campus_maps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_building_floor` (`building_id`,`floor_level`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disaster_drills`
--
ALTER TABLE `disaster_drills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `dispositions`
--
ALTER TABLE `dispositions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `letter_id` (`letter_id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `floors`
--
ALTER TABLE `floors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_floor_building_level` (`building_id`,`level`);

--
-- Indexes for table `item_requests`
--
ALTER TABLE `item_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_item_requests_code` (`request_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `item_request_items`
--
ALTER TABLE `item_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_request_id` (`item_request_id`),
  ADD KEY `stock_id` (`stock_id`);

--
-- Indexes for table `letters`
--
ALTER TABLE `letters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_letters_direction` (`direction`),
  ADD KEY `idx_letters_status` (`status`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `letter_attachments`
--
ALTER TABLE `letter_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `letter_id` (`letter_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `letter_templates`
--
ALTER TABLE `letter_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_letter_templates_code` (`code`);

--
-- Indexes for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_schedule_id` (`maintenance_schedule_id`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `technician_id` (`technician_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `maintenance_schedules`
--
ALTER TABLE `maintenance_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_maintenance_schedule_date` (`schedule_date`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_password_resets_email` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_permissions_code` (`code`);

--
-- Indexes for table `procurements`
--
ALTER TABLE `procurements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_procurements_code` (`procurement_code`),
  ADD KEY `budget_id` (`budget_id`),
  ADD KEY `requested_by` (`requested_by`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `procurement_items`
--
ALTER TABLE `procurement_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procurement_id` (`procurement_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_roles_code` (`code`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_role_permission` (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_rooms_code` (`code`),
  ADD KEY `idx_rooms_type` (`room_type`),
  ADD KEY `building_id` (`building_id`),
  ADD KEY `floor_id` (`floor_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `room_bookings`
--
ALTER TABLE `room_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_room_bookings_code` (`booking_code`),
  ADD KEY `idx_room_bookings_start` (`start_at`),
  ADD KEY `idx_room_bookings_end` (`end_at`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_status_start` (`status`,`start_at`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_sessions_token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_settings_key` (`key`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_stocks_item_code` (`item_code`),
  ADD KEY `stock_category_id` (`stock_category_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `stock_categories`
--
ALTER TABLE `stock_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_stock_categories_code` (`code`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_id` (`stock_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_surveys_slug` (`slug`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `survey_answers`
--
ALTER TABLE `survey_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_response_id` (`survey_response_id`),
  ADD KEY `idx_question` (`survey_question_id`);

--
-- Indexes for table `survey_questions`
--
ALTER TABLE `survey_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_id` (`survey_id`);

--
-- Indexes for table `survey_responses`
--
ALTER TABLE `survey_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_id` (`survey_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `technicians`
--
ALTER TABLE `technicians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_tickets_code` (`ticket_code`),
  ADD KEY `idx_tickets_status` (`status`),
  ADD KEY `idx_tickets_priority` (`priority`),
  ADD KEY `reported_by_user_id` (`reported_by_user_id`),
  ADD KEY `building_id` (`building_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `assigned_technician_id` (`assigned_technician_id`),
  ADD KEY `assigned_vendor_id` (`assigned_vendor_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_ticket_code` (`ticket_code`),
  ADD KEY `idx_status_created` (`status`,`created_at`),
  ADD KEY `idx_code` (`ticket_code`);

--
-- Indexes for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `ticket_histories`
--
ALTER TABLE `ticket_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_ticket` (`ticket_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_username` (`username`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_role` (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `utilization_logs`
--
ALTER TABLE `utilization_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_utilization_logs_start` (`start_at`),
  ADD KEY `idx_utilization_logs_end` (`end_at`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `waste_logs`
--
ALTER TABLE `waste_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accessibility_features`
--
ALTER TABLE `accessibility_features`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `apar`
--
ALTER TABLE `apar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_attachments`
--
ALTER TABLE `asset_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_categories`
--
ALTER TABLE `asset_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `asset_condition_histories`
--
ALTER TABLE `asset_condition_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_disposals`
--
ALTER TABLE `asset_disposals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_labels`
--
ALTER TABLE `asset_labels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `basts`
--
ALTER TABLE `basts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrow_items`
--
ALTER TABLE `borrow_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrow_requests`
--
ALTER TABLE `borrow_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `campus_maps`
--
ALTER TABLE `campus_maps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disaster_drills`
--
ALTER TABLE `disaster_drills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dispositions`
--
ALTER TABLE `dispositions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `floors`
--
ALTER TABLE `floors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `item_requests`
--
ALTER TABLE `item_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_request_items`
--
ALTER TABLE `item_request_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `letters`
--
ALTER TABLE `letters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `letter_attachments`
--
ALTER TABLE `letter_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `letter_templates`
--
ALTER TABLE `letter_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_schedules`
--
ALTER TABLE `maintenance_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `procurements`
--
ALTER TABLE `procurements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procurement_items`
--
ALTER TABLE `procurement_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=345;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `room_bookings`
--
ALTER TABLE `room_bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_categories`
--
ALTER TABLE `stock_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `survey_answers`
--
ALTER TABLE `survey_answers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `survey_questions`
--
ALTER TABLE `survey_questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `survey_responses`
--
ALTER TABLE `survey_responses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `technicians`
--
ALTER TABLE `technicians`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_histories`
--
ALTER TABLE `ticket_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `utilization_logs`
--
ALTER TABLE `utilization_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `waste_logs`
--
ALTER TABLE `waste_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accessibility_features`
--
ALTER TABLE `accessibility_features`
  ADD CONSTRAINT `accessibility_features_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accessibility_features_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `apar`
--
ALTER TABLE `apar`
  ADD CONSTRAINT `apar_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `apar_ibfk_2` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `apar_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `apar_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_ibfk_1` FOREIGN KEY (`approver_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`asset_category_id`) REFERENCES `asset_categories` (`id`),
  ADD CONSTRAINT `assets_ibfk_2` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `asset_attachments`
--
ALTER TABLE `asset_attachments`
  ADD CONSTRAINT `asset_attachments_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asset_attachments_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `asset_condition_histories`
--
ALTER TABLE `asset_condition_histories`
  ADD CONSTRAINT `asset_condition_histories_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asset_condition_histories_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `asset_disposals`
--
ALTER TABLE `asset_disposals`
  ADD CONSTRAINT `asset_disposals_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asset_disposals_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `asset_disposals_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `asset_labels`
--
ALTER TABLE `asset_labels`
  ADD CONSTRAINT `asset_labels_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asset_labels_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `basts`
--
ALTER TABLE `basts`
  ADD CONSTRAINT `basts_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `borrow_items`
--
ALTER TABLE `borrow_items`
  ADD CONSTRAINT `borrow_items_ibfk_1` FOREIGN KEY (`borrow_request_id`) REFERENCES `borrow_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_items_ibfk_2` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `borrow_items_ibfk_3` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `borrow_requests`
--
ALTER TABLE `borrow_requests`
  ADD CONSTRAINT `borrow_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `borrow_requests_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `borrow_requests_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `buildings`
--
ALTER TABLE `buildings`
  ADD CONSTRAINT `buildings_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `disaster_drills`
--
ALTER TABLE `disaster_drills`
  ADD CONSTRAINT `disaster_drills_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dispositions`
--
ALTER TABLE `dispositions`
  ADD CONSTRAINT `dispositions_ibfk_1` FOREIGN KEY (`letter_id`) REFERENCES `letters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dispositions_ibfk_2` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dispositions_ibfk_3` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dispositions_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `floors`
--
ALTER TABLE `floors`
  ADD CONSTRAINT `floors_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_requests`
--
ALTER TABLE `item_requests`
  ADD CONSTRAINT `item_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `item_requests_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `item_requests_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `item_request_items`
--
ALTER TABLE `item_request_items`
  ADD CONSTRAINT `item_request_items_ibfk_1` FOREIGN KEY (`item_request_id`) REFERENCES `item_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_request_items_ibfk_2` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `letters`
--
ALTER TABLE `letters`
  ADD CONSTRAINT `letters_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `letter_attachments`
--
ALTER TABLE `letter_attachments`
  ADD CONSTRAINT `letter_attachments_ibfk_1` FOREIGN KEY (`letter_id`) REFERENCES `letters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `letter_attachments_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD CONSTRAINT `maintenance_logs_ibfk_1` FOREIGN KEY (`maintenance_schedule_id`) REFERENCES `maintenance_schedules` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `maintenance_logs_ibfk_2` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `maintenance_logs_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `maintenance_logs_ibfk_4` FOREIGN KEY (`technician_id`) REFERENCES `technicians` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `maintenance_logs_ibfk_5` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `maintenance_logs_ibfk_6` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `maintenance_schedules`
--
ALTER TABLE `maintenance_schedules`
  ADD CONSTRAINT `maintenance_schedules_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `maintenance_schedules_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `maintenance_schedules_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `procurements`
--
ALTER TABLE `procurements`
  ADD CONSTRAINT `procurements_ibfk_1` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `procurements_ibfk_2` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `procurements_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `procurements_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `procurement_items`
--
ALTER TABLE `procurement_items`
  ADD CONSTRAINT `procurement_items_ibfk_1` FOREIGN KEY (`procurement_id`) REFERENCES `procurements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rooms_ibfk_2` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rooms_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `room_bookings`
--
ALTER TABLE `room_bookings`
  ADD CONSTRAINT `room_bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_bookings_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `room_bookings_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`stock_category_id`) REFERENCES `stock_categories` (`id`),
  ADD CONSTRAINT `stocks_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `surveys`
--
ALTER TABLE `surveys`
  ADD CONSTRAINT `surveys_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `survey_answers`
--
ALTER TABLE `survey_answers`
  ADD CONSTRAINT `survey_answers_ibfk_1` FOREIGN KEY (`survey_response_id`) REFERENCES `survey_responses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `survey_answers_ibfk_2` FOREIGN KEY (`survey_question_id`) REFERENCES `survey_questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `survey_questions`
--
ALTER TABLE `survey_questions`
  ADD CONSTRAINT `survey_questions_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `survey_responses`
--
ALTER TABLE `survey_responses`
  ADD CONSTRAINT `survey_responses_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `survey_responses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `technicians`
--
ALTER TABLE `technicians`
  ADD CONSTRAINT `technicians_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`reported_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_4` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_5` FOREIGN KEY (`assigned_technician_id`) REFERENCES `technicians` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_6` FOREIGN KEY (`assigned_vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_7` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_8` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  ADD CONSTRAINT `ticket_attachments_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_attachments_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ticket_histories`
--
ALTER TABLE `ticket_histories`
  ADD CONSTRAINT `ticket_histories_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_histories_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `utilization_logs`
--
ALTER TABLE `utilization_logs`
  ADD CONSTRAINT `utilization_logs_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `utilization_logs_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `waste_logs`
--
ALTER TABLE `waste_logs`
  ADD CONSTRAINT `waste_logs_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
