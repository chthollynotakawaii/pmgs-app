-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 14, 2025 at 06:11 AM
-- Server version: 8.3.0
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pmgs_inventory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrowing_logs`
--

DROP TABLE IF EXISTS `borrowing_logs`;
CREATE TABLE IF NOT EXISTS `borrowing_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventory_record_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `custom_borrower` text COLLATE utf8mb3_unicode_ci,
  `location_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `returned_at` datetime DEFAULT NULL,
  `remarks` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrowing_logs_inventory_record_id_foreign` (`inventory_record_id`),
  KEY `borrowing_logs_user_id_foreign` (`user_id`),
  KEY `borrowing_logs_location_id_foreign` (`location_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `borrowing_logs`
--

INSERT INTO `borrowing_logs` (`id`, `inventory_record_id`, `user_id`, `custom_borrower`, `location_id`, `quantity`, `returned_at`, `remarks`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2, NULL, 2, 1, '2025-07-08 18:00:00', 0, '2025-07-08 10:35:09', '2025-07-08 10:35:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'LENOVO', '2025-07-06 19:49:16', '2025-07-06 19:49:16');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('property_inventory_management_system_cache_livewire-rate-limiter:98f8310cbc5e902318e40ea9eb99ab2f697ab7ed:timer', 'i:1751971509;', 1751971509),
('property_inventory_management_system_cache_livewire-rate-limiter:98f8310cbc5e902318e40ea9eb99ab2f697ab7ed', 'i:1;', 1751971509),
('property_inventory_management_system_cache_livewire-rate-limiter:0878dccf7128872a4b5b8d414a6e9962539e34c9:timer', 'i:1751969422;', 1751969422),
('property_inventory_management_system_cache_livewire-rate-limiter:0878dccf7128872a4b5b8d414a6e9962539e34c9', 'i:1;', 1751969422);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'EQUIPMENTS', '2025-07-06 19:52:37', '2025-07-06 19:52:37'),
(2, 'CONSUMABLES', '2025-07-08 10:01:31', '2025-07-08 10:01:31'),
(3, 'HEAVY EQUIPMENT', '2025-07-08 10:01:39', '2025-07-08 10:01:39');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'BSIS', '2025-07-06 18:14:41', '2025-07-06 18:14:41'),
(2, 'BLIS', '2025-07-08 10:01:51', '2025-07-08 10:01:51');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'PMGS', '2025-07-06 19:52:15', '2025-07-06 19:52:15'),
(2, 'CCLS', '2025-07-08 04:31:37', '2025-07-08 04:31:37'),
(3, 'CCJE', '2025-07-08 09:59:53', '2025-07-08 09:59:53'),
(4, 'CBTM', '2025-07-08 09:59:59', '2025-07-08 09:59:59'),
(5, 'BED', '2025-07-08 10:00:08', '2025-07-08 10:00:08'),
(6, 'CME', '2025-07-08 10:00:26', '2025-07-08 10:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `exports`
--

DROP TABLE IF EXISTS `exports`;
CREATE TABLE IF NOT EXISTS `exports` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_disk` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `exporter` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `processed_rows` int UNSIGNED NOT NULL DEFAULT '0',
  `total_rows` int UNSIGNED NOT NULL,
  `successful_rows` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exports_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `exports`
--

INSERT INTO `exports` (`id`, `completed_at`, `file_disk`, `file_name`, `exporter`, `processed_rows`, `total_rows`, `successful_rows`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '2025-07-08 11:16:07', 'local', 'export-1-inventory-records', 'App\\Filament\\Exports\\InventoryRecordExporter', 1, 1, 1, 1, '2025-07-08 11:16:04', '2025-07-08 11:16:07');

-- --------------------------------------------------------

--
-- Table structure for table `failed_import_rows`
--

DROP TABLE IF EXISTS `failed_import_rows`;
CREATE TABLE IF NOT EXISTS `failed_import_rows` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `data` json NOT NULL,
  `import_id` bigint UNSIGNED NOT NULL,
  `validation_error` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `failed_import_rows_import_id_foreign` (`import_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `imports`
--

DROP TABLE IF EXISTS `imports`;
CREATE TABLE IF NOT EXISTS `imports` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `file_path` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `importer` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `processed_rows` int UNSIGNED NOT NULL DEFAULT '0',
  `total_rows` int UNSIGNED NOT NULL,
  `successful_rows` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imports_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_backups`
--

DROP TABLE IF EXISTS `inventory_backups`;
CREATE TABLE IF NOT EXISTS `inventory_backups` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `qty` int UNSIGNED NOT NULL,
  `unit` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb3_unicode_ci,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `temp_serial` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `remarks` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `location_id` bigint UNSIGNED DEFAULT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `inventory_backups`
--

INSERT INTO `inventory_backups` (`id`, `qty`, `unit`, `description`, `brand_id`, `model_id`, `temp_serial`, `remarks`, `status`, `category_id`, `department_id`, `location_id`, `supplier_id`, `recorded_at`) VALUES
(1, 0, 'pcs', 'computer', 1, 1, 'abcd123', 'borrowed by Sir Patlunag', 'Functional', 1, 3, 2, 1, '2025-07-08 10:24:57'),
(2, 1, 'kg', NULL, 1, 1, '2321', NULL, 'Functional', 2, 2, 2, 1, '2025-07-08 10:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_records`
--

DROP TABLE IF EXISTS `inventory_records`;
CREATE TABLE IF NOT EXISTS `inventory_records` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `qty` int UNSIGNED NOT NULL,
  `unit` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb3_unicode_ci,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `temp_serial` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `remarks` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `location_id` bigint UNSIGNED DEFAULT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `borrowed` tinyint(1) NOT NULL DEFAULT '0',
  `recorded_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_records_brand_id_foreign` (`brand_id`),
  KEY `inventory_records_model_id_foreign` (`model_id`),
  KEY `inventory_records_category_id_foreign` (`category_id`),
  KEY `inventory_records_department_id_foreign` (`department_id`),
  KEY `inventory_records_location_id_foreign` (`location_id`),
  KEY `inventory_records_supplier_id_foreign` (`supplier_id`),
  KEY `inventory_records_status_index` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `inventory_records`
--

INSERT INTO `inventory_records` (`id`, `qty`, `unit`, `description`, `brand_id`, `model_id`, `temp_serial`, `remarks`, `status`, `category_id`, `department_id`, `location_id`, `supplier_id`, `borrowed`, `recorded_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, 'pcs', 'computer', 1, 1, 'abcd123', 'borrowed by Sir Patlunag', 'Functional', 1, 3, 2, 1, 1, '2025-07-07 18:23:22', '2025-07-08 10:24:57', '2025-07-08 10:49:47', '2025-07-08 10:49:47'),
(2, 1, 'kg', NULL, 1, 1, '2321', NULL, 'Functional', 2, 2, 2, 1, 0, '2025-07-08 18:37:27', '2025-07-08 10:39:02', '2025-07-08 10:39:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb3_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `job_batches`
--

INSERT INTO `job_batches` (`id`, `name`, `total_jobs`, `pending_jobs`, `failed_jobs`, `failed_job_ids`, `options`, `cancelled_at`, `created_at`, `finished_at`) VALUES
('9f574f03-5201-4fe8-9d60-24d169649071', '', 2, 0, 0, '[]', 'a:2:{s:13:\"allowFailures\";b:1;s:7:\"finally\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:7226:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:4:\"next\";O:46:\"Filament\\Actions\\Exports\\Jobs\\ExportCompletion\":7:{s:11:\"\0*\0exporter\";O:44:\"App\\Filament\\Exports\\InventoryRecordExporter\":3:{s:9:\"\0*\0export\";O:38:\"Filament\\Actions\\Exports\\Models\\Export\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";N;s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:1;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:44:\"App\\Filament\\Exports\\InventoryRecordExporter\";s:10:\"total_rows\";i:1;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-07-08 19:16:04\";s:10:\"created_at\";s:19:\"2025-07-08 19:16:04\";s:2:\"id\";i:1;s:9:\"file_name\";s:26:\"export-1-inventory-records\";}s:11:\"\0*\0original\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:44:\"App\\Filament\\Exports\\InventoryRecordExporter\";s:10:\"total_rows\";i:1;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-07-08 19:16:04\";s:10:\"created_at\";s:19:\"2025-07-08 19:16:04\";s:2:\"id\";i:1;s:9:\"file_name\";s:26:\"export-1-inventory-records\";}s:10:\"\0*\0changes\";a:1:{s:9:\"file_name\";s:26:\"export-1-inventory-records\";}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:12:\"completed_at\";s:9:\"timestamp\";s:14:\"processed_rows\";s:7:\"integer\";s:10:\"total_rows\";s:7:\"integer\";s:15:\"successful_rows\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}s:12:\"\0*\0columnMap\";a:17:{s:2:\"id\";s:2:\"ID\";s:3:\"qty\";s:8:\"Quantity\";s:4:\"unit\";s:4:\"Unit\";s:11:\"description\";s:11:\"Description\";s:10:\"brand.name\";s:5:\"Brand\";s:10:\"model.name\";s:5:\"Model\";s:11:\"temp_serial\";s:13:\"Serial Number\";s:7:\"remarks\";s:7:\"Remarks\";s:6:\"status\";s:6:\"Status\";s:13:\"category.name\";s:8:\"Category\";s:15:\"department.name\";s:10:\"Department\";s:13:\"location.name\";s:8:\"Location\";s:13:\"supplier.name\";s:8:\"Supplier\";s:8:\"borrowed\";s:8:\"Borrowed\";s:11:\"recorded_at\";s:13:\"Date Recorded\";s:10:\"created_at\";s:10:\"Created At\";s:10:\"updated_at\";s:10:\"Updated At\";}s:10:\"\0*\0options\";a:0:{}}s:9:\"\0*\0export\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:38:\"Filament\\Actions\\Exports\\Models\\Export\";s:2:\"id\";i:1;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:12:\"\0*\0columnMap\";a:17:{s:2:\"id\";s:2:\"ID\";s:3:\"qty\";s:8:\"Quantity\";s:4:\"unit\";s:4:\"Unit\";s:11:\"description\";s:11:\"Description\";s:10:\"brand.name\";s:5:\"Brand\";s:10:\"model.name\";s:5:\"Model\";s:11:\"temp_serial\";s:13:\"Serial Number\";s:7:\"remarks\";s:7:\"Remarks\";s:6:\"status\";s:6:\"Status\";s:13:\"category.name\";s:8:\"Category\";s:15:\"department.name\";s:10:\"Department\";s:13:\"location.name\";s:8:\"Location\";s:13:\"supplier.name\";s:8:\"Supplier\";s:8:\"borrowed\";s:8:\"Borrowed\";s:11:\"recorded_at\";s:13:\"Date Recorded\";s:10:\"created_at\";s:10:\"Created At\";s:10:\"updated_at\";s:10:\"Updated At\";}s:10:\"\0*\0formats\";a:2:{i:0;E:47:\"Filament\\Actions\\Exports\\Enums\\ExportFormat:Csv\";i:1;E:48:\"Filament\\Actions\\Exports\\Enums\\ExportFormat:Xlsx\";}s:10:\"\0*\0options\";a:0:{}s:7:\"chained\";a:1:{i:0;s:3264:\"O:44:\"Filament\\Actions\\Exports\\Jobs\\CreateXlsxFile\":4:{s:11:\"\0*\0exporter\";O:44:\"App\\Filament\\Exports\\InventoryRecordExporter\":3:{s:9:\"\0*\0export\";O:38:\"Filament\\Actions\\Exports\\Models\\Export\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";N;s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:1;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:44:\"App\\Filament\\Exports\\InventoryRecordExporter\";s:10:\"total_rows\";i:1;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-07-08 19:16:04\";s:10:\"created_at\";s:19:\"2025-07-08 19:16:04\";s:2:\"id\";i:1;s:9:\"file_name\";s:26:\"export-1-inventory-records\";}s:11:\"\0*\0original\";a:8:{s:7:\"user_id\";i:1;s:8:\"exporter\";s:44:\"App\\Filament\\Exports\\InventoryRecordExporter\";s:10:\"total_rows\";i:1;s:9:\"file_disk\";s:5:\"local\";s:10:\"updated_at\";s:19:\"2025-07-08 19:16:04\";s:10:\"created_at\";s:19:\"2025-07-08 19:16:04\";s:2:\"id\";i:1;s:9:\"file_name\";s:26:\"export-1-inventory-records\";}s:10:\"\0*\0changes\";a:1:{s:9:\"file_name\";s:26:\"export-1-inventory-records\";}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:12:\"completed_at\";s:9:\"timestamp\";s:14:\"processed_rows\";s:7:\"integer\";s:10:\"total_rows\";s:7:\"integer\";s:15:\"successful_rows\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:0:{}s:10:\"\0*\0guarded\";a:0:{}}s:12:\"\0*\0columnMap\";a:17:{s:2:\"id\";s:2:\"ID\";s:3:\"qty\";s:8:\"Quantity\";s:4:\"unit\";s:4:\"Unit\";s:11:\"description\";s:11:\"Description\";s:10:\"brand.name\";s:5:\"Brand\";s:10:\"model.name\";s:5:\"Model\";s:11:\"temp_serial\";s:13:\"Serial Number\";s:7:\"remarks\";s:7:\"Remarks\";s:6:\"status\";s:6:\"Status\";s:13:\"category.name\";s:8:\"Category\";s:15:\"department.name\";s:10:\"Department\";s:13:\"location.name\";s:8:\"Location\";s:13:\"supplier.name\";s:8:\"Supplier\";s:8:\"borrowed\";s:8:\"Borrowed\";s:11:\"recorded_at\";s:13:\"Date Recorded\";s:10:\"created_at\";s:10:\"Created At\";s:10:\"updated_at\";s:10:\"Updated At\";}s:10:\"\0*\0options\";a:0:{}}s:9:\"\0*\0export\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:38:\"Filament\\Actions\\Exports\\Models\\Export\";s:2:\"id\";i:1;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}s:12:\"\0*\0columnMap\";a:17:{s:2:\"id\";s:2:\"ID\";s:3:\"qty\";s:8:\"Quantity\";s:4:\"unit\";s:4:\"Unit\";s:11:\"description\";s:11:\"Description\";s:10:\"brand.name\";s:5:\"Brand\";s:10:\"model.name\";s:5:\"Model\";s:11:\"temp_serial\";s:13:\"Serial Number\";s:7:\"remarks\";s:7:\"Remarks\";s:6:\"status\";s:6:\"Status\";s:13:\"category.name\";s:8:\"Category\";s:15:\"department.name\";s:10:\"Department\";s:13:\"location.name\";s:8:\"Location\";s:13:\"supplier.name\";s:8:\"Supplier\";s:8:\"borrowed\";s:8:\"Borrowed\";s:11:\"recorded_at\";s:13:\"Date Recorded\";s:10:\"created_at\";s:10:\"Created At\";s:10:\"updated_at\";s:10:\"Updated At\";}s:10:\"\0*\0options\";a:0:{}}\";}s:19:\"chainCatchCallbacks\";a:0:{}}}s:8:\"function\";s:266:\"function (\\Illuminate\\Bus\\Batch $batch) use ($next) {\n                if (! $batch->cancelled()) {\n                    \\Illuminate\\Container\\Container::getInstance()->make(\\Illuminate\\Contracts\\Bus\\Dispatcher::class)->dispatch($next);\n                }\n            }\";s:5:\"scope\";s:27:\"Illuminate\\Bus\\ChainedBatch\";s:4:\"this\";N;s:4:\"self\";s:32:\"00000000000013d90000000000000000\";}\";s:4:\"hash\";s:44:\"QczgCVypVT7NGmOjhw6j3XbhvcWSA+nT3xQ5ZMLH1VU=\";}}}}', NULL, 1751973365, 1751973367);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ANNEX 1', '2025-07-06 19:52:05', '2025-07-06 19:52:05'),
(2, 'ADMIN', '2025-07-08 10:01:00', '2025-07-08 10:01:00'),
(3, 'OSAS', '2025-07-08 10:01:12', '2025-07-08 10:01:12');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_10_000000_create_uniform_types_table', 1),
(5, '2025_06_10_091719_create_inventory__records_table', 1),
(6, '2025_06_10_124908_create_brands_table', 1),
(7, '2025_06_10_124916_create_models_table', 1),
(8, '2025_06_10_124951_create_categories_table', 1),
(9, '2025_06_10_125007_create_locations_table', 1),
(10, '2025_06_10_125025_create_suppliers_table', 1),
(11, '2025_06_10_133604_create_departments_table', 1),
(12, '2025_06_13_031201_create_inventory_backups_table', 1),
(13, '2025_06_15_063324_create_borrowing_logs_table', 1),
(14, '2025_06_15_130650_create_uniform_inventories_table', 1),
(15, '2025_06_15_131621_create_uniform_distributions_table', 1),
(16, '2025_06_22_191004_create_imports_table', 1),
(17, '2025_06_22_191006_create_failed_import_rows_table', 1),
(18, '2025_06_22_191329_create_exports_table', 1),
(19, '2025_06_22_191529_create_notifications_table', 1),
(20, '2025_06_27_165722_create_uniform_sizes_table', 1),
(21, '2025_06_27_181318_create_courses_table', 1),
(22, '2025_07_07_020953_create_uniform_stock_summary_table', 2),
(23, '2024_01_10_000000_create_uniform_stocks_table', 3),
(24, '2025_07_07_145804_create_preventive_maintenances_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

DROP TABLE IF EXISTS `models`;
CREATE TABLE IF NOT EXISTS `models` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `models`
--

INSERT INTO `models` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ANV_4011', '2025-07-06 19:49:56', '2025-07-06 19:49:56');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `username` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preventive_maintenances`
--

DROP TABLE IF EXISTS `preventive_maintenances`;
CREATE TABLE IF NOT EXISTS `preventive_maintenances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventory_record_ids` json NOT NULL,
  `maintenance_type` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `scheduled_date` date NOT NULL,
  `remarks` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb3_unicode_ci,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('JAlbHYStWGzR8JycX6KeNWiR8pPbK4nHUObegNnl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibTlCVWJodVoxTjVnNkNyeXBrOXFwNEVTMnRpbDRjT2ZWMVhvZTlndyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2ludmVudG9yeS1yZWNvcmRzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fX0=', 1752066475),
('HR4tso5ZWVpM5fF7vgdJj6mlxDVAfUMON6QAvz6X', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFBhOUw3RlNQcVFTaGR0azI2T0JoWkIyRzd4QUFzQW9VQ2tMYjdERSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1752076873),
('bRdRaWERTQDfxiOv98bA1QXRb7TkEZJKwDN33fBA', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN3NkS01XN1FvTmxkdFR4THV2UjNyU09tU21YazNZZlY2V1lZYm1aTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMjoiaHR0cDovL2xvY2FsaG9zdC9hZG1pbiI7fX0=', 1752075980),
('2xEi8s3UXK2WYnT8yuEWNSUoMBI2l9qhXC67vzlv', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoidWhHM1ZXRU9GZlZXdkNTQ2lma0pQY3ltSmVqYlBPR0lvSkVDcFJDZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9sb2NhbGhvc3QvYWRtaW4iO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkZkhFOTQ4bmMxcm4zVjU0NlRVL2VRZTV3Z0d2SllrWHNya0ZCNEp0cHZWakIyamQ2VjRDVkciO3M6MTc6IkRhc2hib2FyZF9maWx0ZXJzIjtOO30=', 1751970150),
('nyVAgFERJnrgOrRV9Ss28H5qBEZd7OhGEN5YjlsT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia2VYWHoyd2RIZEZpTEVkQzJsWm04Q2pMWVhkNjY3SFZ0VUthdFlkQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fX0=', 1752076770),
('zkbOIr27dfL9mMp4UExFhjbd5m1RfJB6r9mJ4ols', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNTM3VkVWWkZaaml5NjkzRURhUFc3azNCWVAxTXAzbDM4V3pZTlJaVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1752076672),
('7ZEmwtKvbRmXcnWC9qxfyPyIuEEOrK9jmoL4Ihtb', NULL, '::1', 'Mozilla/5.0 (Linux; Android 15; 23049PCD8G Build/AQ3A.241006.001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.7151.115 Mobile Safari/537.36 OPX/2.8', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibHpVeGlROHVOWlhjNk5ZczZ1bno3bDNQS09oN3hmNkZ2bktBcVpnQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MjoiaHR0cDovL2FpcmVkYWxlLWdyYXRlZnVsLWh1Z2VseS5uZ3Jvay1mcmVlLmFwcC9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjU4OiJodHRwOi8vYWlyZWRhbGUtZ3JhdGVmdWwtaHVnZWx5Lm5ncm9rLWZyZWUuYXBwL2FkbWluL2xvZ2luIjt9fQ==', 1751969009),
('lGiCKmaOpgmyyhQuzsDkEZC6o4Q13NzLXnZtTQ2x', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiWkNYdU9qcDNMbnZvUUlERVV5cUFhTUNLR3BtSlJNUnJTTU05VkFIWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkZkhFOTQ4bmMxcm4zVjU0NlRVL2VRZTV3Z0d2SllrWHNya0ZCNEp0cHZWakIyamQ2VjRDVkciO3M6MTc6IkRhc2hib2FyZF9maWx0ZXJzIjtOO3M6ODoiZmlsYW1lbnQiO2E6MDp7fX0=', 1751973728),
('qIZVS6sBLJaf4VIKjo8cKBpI8At5EdKOmeh9R7wn', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiREVRVmVzeEx5M3dvc05kR0V5cUZwWGVTWm9UV3k0aGhRWEtRZTJNTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMjoiaHR0cDovL2xvY2FsaG9zdC9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwOi8vbG9jYWxob3N0L2FkbWluL2xvZ2luIjt9fQ==', 1752241161);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `address` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phone`, `email`, `address`, `created_at`, `updated_at`) VALUES
(1, 'BANANA COMPANY', NULL, NULL, NULL, '2025-07-06 19:51:43', '2025-07-06 19:51:43');

-- --------------------------------------------------------

--
-- Table structure for table `uniform_distributions`
--

DROP TABLE IF EXISTS `uniform_distributions`;
CREATE TABLE IF NOT EXISTS `uniform_distributions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_name_id` bigint UNSIGNED NOT NULL,
  `receipt_number` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `sizes_id` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniform_distributions_student_name_id_foreign` (`student_name_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uniform_inventories`
--

DROP TABLE IF EXISTS `uniform_inventories`;
CREATE TABLE IF NOT EXISTS `uniform_inventories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` bigint UNSIGNED NOT NULL,
  `details` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniform_inventories_course_id_foreign` (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uniform_sizes`
--

DROP TABLE IF EXISTS `uniform_sizes`;
CREATE TABLE IF NOT EXISTS `uniform_sizes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_name` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `student_identification` int UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `course_id` bigint UNSIGNED DEFAULT NULL,
  `sizes` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniform_sizes_department_id_foreign` (`department_id`),
  KEY `uniform_sizes_course_id_foreign` (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uniform_stock_summaries`
--

DROP TABLE IF EXISTS `uniform_stock_summaries`;
CREATE TABLE IF NOT EXISTS `uniform_stock_summaries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` bigint UNSIGNED NOT NULL,
  `uniform_type` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `size` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniform_stock_summaries_course_id_uniform_type_size_unique` (`course_id`,`uniform_type`,`size`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `username` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `role` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'admin',
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`username`),
  KEY `users_department_id_foreign` (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `role`, `department_id`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin', NULL, '$2y$12$fHE948nc1rn3V546TU/eQe5wgGvJYkXsrkFB4JtpvVjB2jd6V4CVG', NULL, '2025-07-06 17:48:36', '2025-07-06 17:48:36'),
(2, 'user', 'user', 'user', 2, '$2y$12$wm.4S07w6SCTFJ2KFum0MurFrmaO4/uNu0ntAD8DEsvAwzaGea3ka', NULL, '2025-07-08 04:32:09', '2025-07-08 04:32:09');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
