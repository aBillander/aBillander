-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 06, 2018 at 01:17 PM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xtranat55`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_loggers`
--

CREATE TABLE `activity_loggers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `signature` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_loggers`
--

INSERT INTO `activity_loggers` (`id`, `name`, `signature`, `description`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(33, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(34, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(35, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(36, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(37, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(38, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(39, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(40, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(41, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(42, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(43, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(44, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(45, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(46, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(47, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(48, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(49, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(50, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(51, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(52, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(53, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(54, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(55, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(56, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(57, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(58, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(59, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(60, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(63, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(64, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(65, 'Import Products :: 2018-06-27 18:16:44', 'ec53aa9605b0fc6638ab9da0582186c2', 'Import Products :: 2018-06-27 18:16:44', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(67, 'Import Products :: 2018-06-27 20:52:50', '876314891f5fc19bbd091760cfdff93c', 'Import Products :: 2018-06-27 20:52:50', 1, '2018-06-27 18:52:50', '2018-06-27 18:52:50'),
(70, 'Import Products :: 2018-06-28 11:45:48', '8ef9b067ac6681233c510f6e501799cb', 'App\\Http\\Controllers\\Import\\ImportProductsController::process', 1, '2018-06-28 09:45:48', '2018-06-28 09:45:48'),
(73, 'Import WooCommerce Orders', '75f631fa4eb9fe649f760dcb24145035', 'aBillander\\WooConnect\\WooOrderImporter', 1, '2018-07-01 19:17:08', '2018-07-01 19:17:08'),
(75, 'Import Customers', 'b79a73149549bdad34186d08c1d8636d', 'App\\Http\\Controllers\\Import\\ImportCustomersController::process', 1, '2018-07-02 16:51:01', '2018-07-02 16:51:01'),
(76, 'Import Price List', '56aeb75b2409e60d3621f0b75e0891f8', 'App\\Http\\Controllers\\Import\\ImportPriceListsController::process', 1, '2018-07-06 10:07:50', '2018-07-06 10:07:50');

-- --------------------------------------------------------

--
-- Table structure for table `activity_loggers_dist`
--

CREATE TABLE `activity_loggers_dist` (
  `id` int(10) UNSIGNED NOT NULL,
  `log_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` smallint(6) NOT NULL DEFAULT '0',
  `level_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` text COLLATE utf8mb4_unicode_ci,
  `loggable_id` int(10) UNSIGNED DEFAULT NULL,
  `loggable_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `secs_added` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_loggers_dist`
--

INSERT INTO `activity_loggers_dist` (`id`, `log_name`, `description`, `level`, `level_name`, `message`, `context`, `loggable_id`, `loggable_type`, `user_id`, `date_added`, `secs_added`, `created_at`, `updated_at`) VALUES
(10, 'aBillander_messenger', 'aBillander project updates', 200, 'INFO', 'aBillander project updates!', '[]', NULL, NULL, 1, '2018-06-21 19:35:25', '495975', '2018-06-21 17:35:25', '2018-06-21 17:35:25'),
(13, 'aBillander_messenger', 'aBillander project updates', 0, 'PENDING', 'aBillander Tarea pendiente!', '[]', NULL, NULL, 1, '2018-06-21 20:13:28', '810680', '2018-06-21 18:13:28', '2018-06-21 18:13:28'),
(17, 'Import Products :: 2018-06-26 20:18:13', '', 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, 1, '2018-06-26 20:18:13', '010769', '2018-06-26 18:18:13', '2018-06-26 18:18:13'),
(18, 'Import Products :: 2018-06-26 20:18:13', '', 300, 'WARNING', 'No se encontraton datos de Productos en el fichero', '[]', NULL, NULL, 1, '2018-06-26 20:18:13', '047128', '2018-06-26 18:18:13', '2018-06-26 18:18:13'),
(19, 'Import Products :: 2018-06-26 20:18:13', '', 200, 'INFO', 'LOG terminado', '[]', NULL, NULL, 1, '2018-06-26 20:18:13', '077774', '2018-06-26 18:18:13', '2018-06-26 18:18:13'),
(20, 'Import Products :: 2018-06-26 20:25:59', '', 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, 1, '2018-06-26 20:25:59', '129966', '2018-06-26 18:25:59', '2018-06-26 18:25:59'),
(21, 'Import Products :: 2018-06-26 20:26:35', '', 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, 1, '2018-06-26 20:26:35', '167421', '2018-06-26 18:26:35', '2018-06-26 18:26:35'),
(22, 'Import Products :: 2018-06-26 20:26:35', '', 300, 'WARNING', 'No se encontraton datos de Productos en el fichero', '[]', NULL, NULL, 1, '2018-06-26 20:26:35', '217894', '2018-06-26 18:26:35', '2018-06-26 18:26:35'),
(23, 'Import Products :: 2018-06-26 20:26:35', '', 200, 'INFO', 'LOG terminado', '[]', NULL, NULL, 1, '2018-06-26 20:26:35', '246153', '2018-06-26 18:26:35', '2018-06-26 18:26:35'),
(24, 'Import Products :: 2018-06-26 21:00:35', '', 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, 1, '2018-06-26 21:00:35', '821828', '2018-06-26 19:00:35', '2018-06-26 19:00:35'),
(25, 'Import Products :: 2018-06-26 21:00:35', '', 200, 'INFO', 'Se han borrado todos los Productos antes de la Importación.', '[]', NULL, NULL, 1, '2018-06-26 21:00:35', '860936', '2018-06-26 19:00:35', '2018-06-26 19:00:35'),
(26, 'Import Products :: 2018-06-26 21:00:35', '', 200, 'INFO', 'LOG terminado', '[]', NULL, NULL, 1, '2018-06-26 21:00:35', '897792', '2018-06-26 19:00:35', '2018-06-26 19:00:35'),
(27, 'Import Products :: 2018-06-26 21:04:17', '', 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, 1, '2018-06-26 21:04:17', '832025', '2018-06-26 19:04:17', '2018-06-26 19:04:17'),
(28, 'Import Products :: 2018-06-26 21:04:17', '', 400, 'ERROR', 'Se ha producido un error:<br />Could not open /var/www/html/enatural//tmp/phpt9QuXnnnn for reading! File does not exist.', '[]', NULL, NULL, 1, '2018-06-26 21:04:17', '879401', '2018-06-26 19:04:17', '2018-06-26 19:04:17'),
(29, 'Import Products :: 2018-06-26 21:04:17', '', 200, 'INFO', 'LOG terminado', '[]', NULL, NULL, 1, '2018-06-26 21:04:17', '910053', '2018-06-26 19:04:17', '2018-06-26 19:04:17'),
(30, 'Import Products :: 2018-06-26 21:20:04', '', 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, 1, '2018-06-26 21:20:04', '067132', '2018-06-26 19:20:04', '2018-06-26 19:20:04'),
(31, 'Import Products :: 2018-06-26 21:20:04', '', 200, 'INFO', 'Se han borrado todos los Productos antes de la Importación. En total 115 Productos.', '{"nbr":115}', NULL, NULL, 1, '2018-06-26 21:20:04', '340176', '2018-06-26 19:20:04', '2018-06-26 19:20:04'),
(32, 'Import Products :: 2018-06-26 21:20:04', '', 200, 'INFO', 'Se han creado 116 Productos.', '{"i":116}', NULL, NULL, 1, '2018-06-26 21:20:08', '838917', '2018-06-26 19:20:08', '2018-06-26 19:20:08'),
(33, 'Import Products :: 2018-06-26 21:20:04', '', 200, 'INFO', 'LOG terminado', '[]', NULL, NULL, 1, '2018-06-26 21:20:08', '872501', '2018-06-26 19:20:08', '2018-06-26 19:20:08'),
(34, 'Import Products :: 2018-06-27 14:23:11', '', 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, 1, '2018-06-27 14:23:11', '609656', '2018-06-27 12:23:11', '2018-06-27 12:23:11'),
(35, 'Import Products :: 2018-06-27 14:23:11', '', 200, 'INFO', 'Se cargatán los Productos del Fichero: ANA_Copia de Listados Elaborados Abillander La Extranatural copia.xlsx.xlsx .', '{"file":"ANA_Copia de Listados Elaborados Abillander La Extranatural copia.xlsx.xlsx"}', NULL, NULL, 1, '2018-06-27 14:23:11', '646511', '2018-06-27 12:23:11', '2018-06-27 12:23:11'),
(36, 'Import Products :: 2018-06-27 14:28:42', '', 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, 1, '2018-06-27 14:28:42', '900547', '2018-06-27 12:28:42', '2018-06-27 12:28:42'),
(37, 'Import Products :: 2018-06-27 14:28:42', '', 200, 'INFO', 'Se cargatán los Productos del Fichero: <span class="log-showoff-format">ANA_Copia de Listados Elaborados Abillander La Extranatural copia.xlsx</span> .', '{"file":"ANA_Copia de Listados Elaborados Abillander La Extranatural copia.xlsx"}', NULL, NULL, 1, '2018-06-27 14:28:42', '947789', '2018-06-27 12:28:42', '2018-06-27 12:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logger_lines`
--

CREATE TABLE `activity_logger_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `level` smallint(6) NOT NULL DEFAULT '0',
  `level_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` text COLLATE utf8mb4_unicode_ci,
  `loggable_id` int(10) UNSIGNED DEFAULT NULL,
  `loggable_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `secs_added` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '000000',
  `activity_logger_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logger_lines`
--

INSERT INTO `activity_logger_lines` (`id`, `level`, `level_name`, `message`, `context`, `loggable_id`, `loggable_type`, `date_added`, `secs_added`, `activity_logger_id`, `created_at`, `updated_at`) VALUES
(1, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(2, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(3, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(4, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(5, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(6, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(7, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(8, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(9, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(10, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(11, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(12, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(13, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(14, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(15, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(16, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(17, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(18, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(19, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(20, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(21, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(22, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(23, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(24, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(25, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(26, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(27, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(28, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(29, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(30, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(31, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(32, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(33, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(34, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(35, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(36, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(37, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(38, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 18:16:44', '218132', 1, '2018-06-27 16:16:44', '2018-06-27 16:16:44'),
(39, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 20:52:50', '551396', 67, '2018-06-27 18:52:50', '2018-06-27 18:52:50'),
(40, 200, 'ERROR', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 20:52:50', '551396', 67, '2018-06-27 18:52:50', '2018-06-27 18:52:50'),
(41, 200, 'WARNING', 'LOG iniciado', '[]', NULL, NULL, '2018-06-27 20:52:50', '551396', 67, '2018-06-27 18:52:50', '2018-06-27 18:52:50'),
(42, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-28 11:30:43', '561221', 68, '2018-06-28 09:30:43', '2018-06-28 09:30:43'),
(43, 200, 'INFO', 'Se cargatán los Productos desde el Fichero: <br /><span class="log-showoff-format">ANA_Copia de Listados Elaborados Abillander La Extranatural copia.xlsx</span> .', '{"file":"ANA_Copia de Listados Elaborados Abillander La Extranatural copia.xlsx"}', NULL, NULL, '2018-06-28 11:30:43', '672822', 68, '2018-06-28 09:30:43', '2018-06-28 09:30:43'),
(44, 200, 'INFO', 'Se han creado / actualizado 116 Productos.', '{"i":116}', NULL, NULL, '2018-06-28 11:30:44', '593595', 68, '2018-06-28 09:30:44', '2018-06-28 09:30:44'),
(45, 200, 'INFO', 'Se han procesado 116 Productos.', '{"i":116}', NULL, NULL, '2018-06-28 11:30:44', '660762', 68, '2018-06-28 09:30:44', '2018-06-28 09:30:44'),
(46, 200, 'INFO', 'LOG terminado', '[]', NULL, NULL, '2018-06-28 11:30:44', '724875', 68, '2018-06-28 09:30:44', '2018-06-28 09:30:44'),
(52, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-28 11:45:48', '330171', 70, '2018-06-28 09:45:48', '2018-06-28 09:45:48'),
(53, 200, 'INFO', 'Se cargatán los Productos desde el Fichero: <br /><span class="log-showoff-format">ANA_Copia de Listados Elaborados Abillander La Extranatural copia.xlsx</span> .', '{"file":"ANA_Copia de Listados Elaborados Abillander La Extranatural copia.xlsx"}', NULL, NULL, '2018-06-28 11:45:48', '454153', 70, '2018-06-28 09:45:48', '2018-06-28 09:45:48'),
(54, 200, 'INFO', 'Se han creado / actualizado 116 Productos.', '{"i":116}', NULL, NULL, '2018-06-28 11:45:48', '827409', 70, '2018-06-28 09:45:48', '2018-06-28 09:45:48'),
(55, 200, 'INFO', 'Se han procesado 116 Productos.', '{"i":116}', NULL, NULL, '2018-06-28 11:45:48', '886138', 70, '2018-06-28 09:45:48', '2018-06-28 09:45:48'),
(56, 200, 'INFO', 'LOG terminado', '[]', NULL, NULL, '2018-06-28 11:45:48', '950917', 70, '2018-06-28 09:45:48', '2018-06-28 09:45:48'),
(57, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-28 11:47:10', '531547', 70, '2018-06-28 09:47:10', '2018-06-28 09:47:10'),
(58, 200, 'INFO', 'Se cargatán los Productos desde el Fichero: <br /><span class="log-showoff-format">empty.csv</span> .', '{"file":"empty.csv"}', NULL, NULL, '2018-06-28 11:47:10', '601608', 70, '2018-06-28 09:47:10', '2018-06-28 09:47:10'),
(59, 300, 'WARNING', 'No se encontraton datos de Productos en el fichero.', '[]', NULL, NULL, '2018-06-28 11:47:10', '871995', 70, '2018-06-28 09:47:10', '2018-06-28 09:47:10'),
(60, 400, 'ERROR', 'Se ha producido un error:<br />Undefined variable: i_ok', '[]', NULL, NULL, '2018-06-28 11:47:10', '983299', 70, '2018-06-28 09:47:10', '2018-06-28 09:47:11'),
(61, 200, 'INFO', 'LOG terminado', '[]', NULL, NULL, '2018-06-28 11:47:11', '057284', 70, '2018-06-28 09:47:11', '2018-06-28 09:47:11'),
(62, 200, 'INFO', 'LOG iniciado', '[]', NULL, NULL, '2018-06-28 11:50:30', '064631', 70, '2018-06-28 09:50:30', '2018-06-28 09:50:30'),
(63, 200, 'INFO', 'Se cargatán los Productos desde el Fichero: <br /><span class="log-showoff-format">empty.csv</span> .', '{"file":"empty.csv"}', NULL, NULL, '2018-06-28 11:50:30', '150652', 70, '2018-06-28 09:50:30', '2018-06-28 09:50:30'),
(64, 300, 'WARNING', 'No se encontraton datos de Productos en el fichero.', '[]', NULL, NULL, '2018-06-28 11:50:30', '248862', 70, '2018-06-28 09:50:30', '2018-06-28 09:50:30'),
(65, 200, 'INFO', 'Se han creado / actualizado 0 Productos.', '{"i":0}', NULL, NULL, '2018-06-28 11:50:30', '335953', 70, '2018-06-28 09:50:30', '2018-06-28 09:50:30'),
(66, 200, 'INFO', 'Se han procesado 0 Productos.', '{"i":0}', NULL, NULL, '2018-06-28 11:50:30', '418443', 70, '2018-06-28 09:50:30', '2018-06-28 09:50:30'),
(67, 200, 'INFO', 'LOG terminado', '[]', NULL, NULL, '2018-06-28 11:50:30', '521109', 70, '2018-06-28 09:50:30', '2018-06-28 09:50:30'),
(68, 200, 'INFO', ':_> LOG iniciado', '[]', NULL, NULL, '2018-06-28 11:52:29', '164178', 70, '2018-06-28 09:52:29', '2018-06-28 09:52:29'),
(69, 200, 'INFO', 'Se cargatán los Productos desde el Fichero: <br /><span class="log-showoff-format">empty.csv</span> .', '{"file":"empty.csv"}', NULL, NULL, '2018-06-28 11:52:29', '240882', 70, '2018-06-28 09:52:29', '2018-06-28 09:52:29'),
(70, 300, 'WARNING', 'No se encontraton datos de Productos en el fichero.', '[]', NULL, NULL, '2018-06-28 11:52:29', '314746', 70, '2018-06-28 09:52:29', '2018-06-28 09:52:29'),
(71, 200, 'INFO', 'Se han creado / actualizado 0 Productos.', '{"i":0}', NULL, NULL, '2018-06-28 11:52:29', '374559', 70, '2018-06-28 09:52:29', '2018-06-28 09:52:29'),
(72, 200, 'INFO', 'Se han procesado 0 Productos.', '{"i":0}', NULL, NULL, '2018-06-28 11:52:29', '436616', 70, '2018-06-28 09:52:29', '2018-06-28 09:52:29'),
(73, 200, 'INFO', ':_> LOG terminado', '[]', NULL, NULL, '2018-06-28 11:52:29', '498357', 70, '2018-06-28 09:52:29', '2018-06-28 09:52:29'),
(193, 200, 'INFO', ':_> LOG iniciado', '[]', NULL, NULL, '2018-07-02 13:11:40', '968188', 73, '2018-07-02 11:11:40', '2018-07-02 11:11:41'),
(194, 200, 'INFO', 'Se descargará el Pedido: <span class="log-showoff-format">5078</span> .', '{"oid":"5078"}', NULL, NULL, '2018-07-02 13:11:41', '061421', 73, '2018-07-02 11:11:41', '2018-07-02 11:11:41'),
(195, 200, 'INFO', 'A new Customer <span class="log-showoff-format">[13] LOLA MIR</span> has been created for Order number <span class="log-showoff-format">5078</span>.', '{"cid":13,"oid":5078,"name":"LOLA MIR"}', NULL, NULL, '2018-07-02 13:11:42', '305961', 73, '2018-07-02 11:11:42', '2018-07-02 11:11:42'),
(196, 200, 'INFO', ':_> LOG terminado', '[]', NULL, NULL, '2018-07-02 13:11:42', '367053', 73, '2018-07-02 11:11:42', '2018-07-02 11:11:42'),
(2529, 200, 'INFO', ':_> LOG iniciado', '[]', NULL, NULL, '2018-07-04 12:41:13', '079363', 75, '2018-07-04 10:41:13', '2018-07-04 10:41:13'),
(2530, 200, 'INFO', 'Se cargarán los Clientes desde el Fichero: <br /><span class="log-showoff-format">ANA_Clientes 3 Abillander_LaExtranatural.xlsx</span> .', '{"file":"ANA_Clientes 3 Abillander_LaExtranatural.xlsx"}', NULL, NULL, '2018-07-04 12:41:13', '172593', 75, '2018-07-04 10:41:13', '2018-07-04 10:41:13'),
(2531, 200, 'INFO', 'Se han borrado todos los Clientes antes de la Importación. En total 292 Clientes.', '{"nbr":292}', NULL, NULL, '2018-07-04 12:41:13', '761544', 75, '2018-07-04 10:41:13', '2018-07-04 10:41:13'),
(2532, 400, 'ERROR', 'Cliente [<span class="log-showoff-format">90</span>] <span class="log-showoff-format">Postres y Lácteos Mare Nostrum</span>:<br />El campo \'firstname\' es demasiado largo (32). Eugenia Madrid o Maria. Contabilidad Lola Montaño', '[]', NULL, NULL, '2018-07-04 12:41:18', '459216', 75, '2018-07-04 10:41:18', '2018-07-04 10:41:18'),
(2533, 300, 'WARNING', 'Cliente [<span class="log-showoff-format">90</span>] <span class="log-showoff-format">Postres y Lácteos Mare Nostrum</span>:<br />El campo \'notes\' es: Eugenia Madrid o Maria. Contabilidad Lola Montaño', '[]', NULL, NULL, '2018-07-04 12:41:18', '521366', 75, '2018-07-04 10:41:18', '2018-07-04 10:41:18'),
(2534, 400, 'ERROR', 'Cliente [<span class="log-showoff-format">93</span>] <span class="log-showoff-format">VIÑEDOS CIGARRAL ADOLFO, S.L</span>:<br />El campo \'firstname\' es demasiado largo (32). facturacion@adolfo-toledo.com Francisco', '[]', NULL, NULL, '2018-07-04 12:41:18', '706517', 75, '2018-07-04 10:41:18', '2018-07-04 10:41:18'),
(2535, 300, 'WARNING', 'Cliente [<span class="log-showoff-format">93</span>] <span class="log-showoff-format">VIÑEDOS CIGARRAL ADOLFO, S.L</span>:<br />El campo \'notes\' es: facturacion@adolfo-toledo.com Francisco', '[]', NULL, NULL, '2018-07-04 12:41:18', '768765', 75, '2018-07-04 10:41:18', '2018-07-04 10:41:18'),
(2536, 400, 'ERROR', 'Cliente [<span class="log-showoff-format">123</span>] <span class="log-showoff-format">Wurst&burguer Maximiliam S.L.</span>:<br />El campo \'firstname\' es demasiado largo (32). 605897067 Mariano Mauri y 645739121 Jenny Polo', '[]', NULL, NULL, '2018-07-04 12:41:20', '243297', 75, '2018-07-04 10:41:20', '2018-07-04 10:41:20'),
(2537, 300, 'WARNING', 'Cliente [<span class="log-showoff-format">123</span>] <span class="log-showoff-format">Wurst&burguer Maximiliam S.L.</span>:<br />El campo \'notes\' es: 605897067 Mariano Mauri y 645739121 Jenny Polo', '[]', NULL, NULL, '2018-07-04 12:41:20', '305272', 75, '2018-07-04 10:41:20', '2018-07-04 10:41:20'),
(2538, 400, 'ERROR', 'Cliente [<span class="log-showoff-format">146</span>] <span class="log-showoff-format">Jose Antonio Fernandez Diaz</span>:<br />El campo \'firstname\' es demasiado largo (32). Mª Jose (antigua profesora en el PUA)', '[]', NULL, NULL, '2018-07-04 12:41:21', '273749', 75, '2018-07-04 10:41:21', '2018-07-04 10:41:21'),
(2539, 300, 'WARNING', 'Cliente [<span class="log-showoff-format">146</span>] <span class="log-showoff-format">Jose Antonio Fernandez Diaz</span>:<br />El campo \'notes\' es: Mª Jose (antigua profesora en el PUA)', '[]', NULL, NULL, '2018-07-04 12:41:21', '335870', 75, '2018-07-04 10:41:21', '2018-07-04 10:41:21'),
(2540, 400, 'ERROR', 'Cliente [<span class="log-showoff-format">172</span>] <span class="log-showoff-format">Maria Nieves Segarra Villas</span>:<br />El campo \'firstname\' es demasiado largo (32). Ma Nieves Segarra - Victor Diaz Hernandez', '[]', NULL, NULL, '2018-07-04 12:41:22', '490045', 75, '2018-07-04 10:41:22', '2018-07-04 10:41:22'),
(2541, 300, 'WARNING', 'Cliente [<span class="log-showoff-format">172</span>] <span class="log-showoff-format">Maria Nieves Segarra Villas</span>:<br />El campo \'notes\' es: Ma Nieves Segarra - Victor Diaz Hernandez', '[]', NULL, NULL, '2018-07-04 12:41:22', '572607', 75, '2018-07-04 10:41:22', '2018-07-04 10:41:22'),
(2542, 400, 'ERROR', 'Cliente [<span class="log-showoff-format">174</span>] <span class="log-showoff-format">Asociación Landare</span>:<br />El campo \'firstname\' es demasiado largo (32). 948121308 ext4 Leyre, ext1 Patricia, ext3 Amaia', '[]', NULL, NULL, '2018-07-04 12:41:22', '757939', 75, '2018-07-04 10:41:22', '2018-07-04 10:41:22'),
(2543, 300, 'WARNING', 'Cliente [<span class="log-showoff-format">174</span>] <span class="log-showoff-format">Asociación Landare</span>:<br />El campo \'notes\' es: 948121308 ext4 Leyre, ext1 Patricia, ext3 Amaia', '[]', NULL, NULL, '2018-07-04 12:41:22', '840534', 75, '2018-07-04 10:41:22', '2018-07-04 10:41:22'),
(2544, 400, 'ERROR', 'Cliente [<span class="log-showoff-format">237</span>] <span class="log-showoff-format">DEZA</span>:<br />El campo \'firstname\' es demasiado largo (32). frescos2@deza-sa.com de José Gomez', '[]', NULL, NULL, '2018-07-04 12:41:25', '665661', 75, '2018-07-04 10:41:25', '2018-07-04 10:41:25'),
(2545, 300, 'WARNING', 'Cliente [<span class="log-showoff-format">237</span>] <span class="log-showoff-format">DEZA</span>:<br />El campo \'notes\' es: frescos2@deza-sa.com de José Gomez', '[]', NULL, NULL, '2018-07-04 12:41:25', '738049', 75, '2018-07-04 10:41:25', '2018-07-04 10:41:25'),
(2546, 200, 'INFO', 'Se han creado / actualizado 250 Clientes.', '{"i":250}', NULL, NULL, '2018-07-04 12:41:26', '819678', 75, '2018-07-04 10:41:26', '2018-07-04 10:41:26'),
(2547, 200, 'INFO', 'Se han procesado 250 Clientes.', '{"i":250}', NULL, NULL, '2018-07-04 12:41:26', '881731', 75, '2018-07-04 10:41:26', '2018-07-04 10:41:26'),
(2548, 200, 'INFO', 'Se han creado / actualizado 42 Clientes.', '{"i":42}', NULL, NULL, '2018-07-04 12:41:28', '892143', 75, '2018-07-04 10:41:28', '2018-07-04 10:41:28'),
(2549, 200, 'INFO', 'Se han procesado 42 Clientes.', '{"i":42}', NULL, NULL, '2018-07-04 12:41:28', '954394', 75, '2018-07-04 10:41:28', '2018-07-04 10:41:28'),
(2550, 200, 'INFO', ':_> LOG terminado', '[]', NULL, NULL, '2018-07-04 12:41:29', '017332', 75, '2018-07-04 10:41:29', '2018-07-04 10:41:29'),
(2684, 200, 'INFO', ':_> LOG iniciado', '[]', NULL, NULL, '2018-07-06 12:51:28', '087672', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:28'),
(2685, 200, 'INFO', 'Se cargará la Tarifa [3] CONSUMIDOR FINAL desde el Fichero: <br /><span class="log-showoff-format">ANA_Tarifa_3_ConsumidorFinal.xlsx</span> .', '{"name":"[3] CONSUMIDOR FINAL","file":"ANA_Tarifa_3_ConsumidorFinal.xlsx"}', NULL, NULL, '2018-07-06 12:51:28', '180992', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:28'),
(2686, 300, 'WARNING', 'Modo SIMULACION. Se mostrarán errores, pero no se cargará nada en la base de datos.', '[]', NULL, NULL, '2018-07-06 12:51:28', '348799', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:28'),
(2687, 400, 'ERROR', 'La fila (00990, , 2.42, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:28', '411840', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:28'),
(2688, 400, 'ERROR', 'La fila (00991, , 3.63, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:28', '472415', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:28'),
(2689, 400, 'ERROR', 'La fila (00992, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:28', '535176', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:28'),
(2690, 400, 'ERROR', 'La fila (00995, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:28', '598044', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:28'),
(2691, 400, 'ERROR', 'La fila (1000, 8437013381314, 3.201, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:28', '669440', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:28'),
(2692, 400, 'ERROR', 'La fila (1001, 8437013381260, 3.399, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:28', '753435', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:28'),
(2693, 400, 'ERROR', 'La fila (1002, 8437013381277, 3.399, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:28', '833547', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:28'),
(2694, 400, 'ERROR', 'La fila (1003, 8437013381253, 2.695, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:28', '916730', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:28'),
(2695, 400, 'ERROR', 'La fila (1004, 8437013381284, 2.849, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:28', '998809', 76, '2018-07-06 10:51:28', '2018-07-06 10:51:29'),
(2696, 400, 'ERROR', 'La fila (1005, 8437013381291, 2.849, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '082427', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2697, 400, 'ERROR', 'La fila (1006, 8437013381307, 2.695, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '164594', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2698, 400, 'ERROR', 'La fila (1010, 8437013381239, 4.95, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '288580', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2699, 400, 'ERROR', 'La fila (1011, 8437013381185, 5.302, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '369617', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2700, 400, 'ERROR', 'La fila (1012, 8437013381192, 5.302, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '453262', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2701, 400, 'ERROR', 'La fila (1013, 8437013381178, 3.949, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '514332', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2702, 400, 'ERROR', 'La fila (1015, 8437013381215, 4.202, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '578903', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2703, 400, 'ERROR', 'La fila (1100, 8437013381123, 2.4024, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '659466', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2704, 400, 'ERROR', 'La fila (1101, 8437013381130, 2.849, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '741678', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2705, 400, 'ERROR', 'La fila (1102, 8437013381147, 2.849, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '802810', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2706, 400, 'ERROR', 'La fila (1104, 8437013381154, 1.404, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '864951', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2707, 400, 'ERROR', 'La fila (1106, 8437013381161, 0.5512, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '926853', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:29'),
(2708, 400, 'ERROR', 'La fila (1500, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:29', '989437', 76, '2018-07-06 10:51:29', '2018-07-06 10:51:30'),
(2709, 400, 'ERROR', 'La fila (2001, 8437013381512, 1.903, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '050574', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2710, 400, 'ERROR', 'La fila (2002, 8437013381550, 1.903, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '112399', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2711, 400, 'ERROR', 'La fila (2003, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '174492', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2712, 400, 'ERROR', 'La fila (2012, 8437013381406, 2.398, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '238069', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2713, 400, 'ERROR', 'La fila (2031, 8437013381475, 5.951, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '297941', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2714, 400, 'ERROR', 'La fila (2032, 8437013381482, 5.951, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '359786', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2715, 400, 'ERROR', 'La fila (2041, 8437013381499, 8.898999999999999, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '421015', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2716, 400, 'ERROR', 'La fila (2042, 8437013381505, 8.898999999999999, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '504686', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2717, 400, 'ERROR', 'La fila (2051, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '565855', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2718, 400, 'ERROR', 'La fila (2052, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '628427', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2719, 400, 'ERROR', 'La fila (2100, 8437013381536, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '690076', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2720, 400, 'ERROR', 'La fila (2101, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '752052', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2721, 400, 'ERROR', 'La fila (2102, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '813965', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2722, 400, 'ERROR', 'La fila (2111, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '875719', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2723, 400, 'ERROR', 'La fila (2112, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '936411', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:30'),
(2724, 400, 'ERROR', 'La fila (3000, 8437013381741, 1.991, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:30', '999564', 76, '2018-07-06 10:51:30', '2018-07-06 10:51:31'),
(2725, 400, 'ERROR', 'La fila (3001, 8437013381758, 1.991, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '062058', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2726, 400, 'ERROR', 'La fila (3002, 8437013381789, 1.991, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '123811', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2727, 400, 'ERROR', 'La fila (3003, 8437013381765, 1.991, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '184893', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2728, 400, 'ERROR', 'La fila (3004, 8437013381772, 1.991, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '247439', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2729, 400, 'ERROR', 'La fila (3010, 8437013381901, 1.991, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '331272', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2730, 400, 'ERROR', 'La fila (3011, 8437013381918, 1.991, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '412901', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2731, 400, 'ERROR', 'La fila (3012, 84370131925, 1.991, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '496532', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2732, 400, 'ERROR', 'La fila (3013, 8437013381932, 1.991, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '578455', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2733, 400, 'ERROR', 'La fila (3014, 8437013381949, 1.991, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '660344', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2734, 400, 'ERROR', 'La fila (3020, 8437013381802, 5.005, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '742817', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2735, 400, 'ERROR', 'La fila (3021, 8437013381819, 5.005, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '825283', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2736, 400, 'ERROR', 'La fila (3022, 8437013381796, 5.005, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:31', '938213', 76, '2018-07-06 10:51:31', '2018-07-06 10:51:31'),
(2737, 400, 'ERROR', 'La fila (3024, 8437013381833, 4.994, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '023139', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2738, 400, 'ERROR', 'La fila (3031, 8437013381963, 5.005, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '095736', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2739, 400, 'ERROR', 'La fila (3032, 8437013381970, 5.005, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '155232', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2740, 400, 'ERROR', 'La fila (3033, 8437013381987, 5.005, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '216621', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2741, 400, 'ERROR', 'La fila (3034, 8437013381994, 5.005, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '280272', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2742, 400, 'ERROR', 'La fila (3040, 8437013381864, 5.005, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '341176', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2743, 400, 'ERROR', 'La fila (3041, 8437013381857, 5.005, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '403150', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2744, 400, 'ERROR', 'La fila (3060, 8437013381840, 3.498, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '464304', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2745, 400, 'ERROR', 'La fila (3070, , 17.996, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '527026', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2746, 400, 'ERROR', 'La fila (3080, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '589254', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2747, 400, 'ERROR', 'La fila (3900, , 2.002, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '649672', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2748, 400, 'ERROR', 'La fila (3901, , 2.002, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '712776', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2749, 400, 'ERROR', 'La fila (3902, , 2.002, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '774497', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2750, 400, 'ERROR', 'La fila (3903, 8437013381659, 5.005, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '836148', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2751, 400, 'ERROR', 'La fila (4002, 8437013381635, 4.202, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '900693', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2752, 400, 'ERROR', 'La fila (4011, 8437013381666, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:32', '964644', 76, '2018-07-06 10:51:32', '2018-07-06 10:51:32'),
(2753, 400, 'ERROR', 'La fila (4023, , 6.303, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '039051', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2754, 400, 'ERROR', 'La fila (5001, 8437017305217, 1.144, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '104174', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2755, 400, 'ERROR', 'La fila (5002, 8437017305033, 1.144, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '166840', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2756, 400, 'ERROR', 'La fila (6000, 8437017305040, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '229063', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2757, 400, 'ERROR', 'La fila (6001, 8437017305026, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '300209', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2758, 400, 'ERROR', 'La fila (6002, 8437017305064, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '361494', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2759, 400, 'ERROR', 'La fila (6003, 8437017305071, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '423826', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2760, 400, 'ERROR', 'La fila (6004, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '486577', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2761, 400, 'ERROR', 'La fila (6005, 8437017305057, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '557890', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2762, 400, 'ERROR', 'La fila (6010, 8437017305187, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '619885', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2763, 400, 'ERROR', 'La fila (6011, 8437017305194, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '681404', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2764, 400, 'ERROR', 'La fila (6012, 8437017305200, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '744869', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2765, 400, 'ERROR', 'La fila (6013, 8437017305019, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '815836', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2766, 400, 'ERROR', 'La fila (6014, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '879037', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2767, 400, 'ERROR', 'La fila (6020, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:33', '950479', 76, '2018-07-06 10:51:33', '2018-07-06 10:51:33'),
(2768, 400, 'ERROR', 'La fila (6021, 8437017305125, 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '012218', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2769, 400, 'ERROR', 'La fila (6022, 8437017305118, 5.5, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '073942', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2770, 400, 'ERROR', 'La fila (6023, 8437017305132, 5.5, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '135963', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2771, 400, 'ERROR', 'La fila (6024, 8437017305101, 5.5, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '219721', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2772, 400, 'ERROR', 'La fila (6030, 8437017305149, 5.5, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '302439', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2773, 400, 'ERROR', 'La fila (6031, 8437017305156, 5.5, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '384473', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2774, 400, 'ERROR', 'La fila (6032, 8437017305163, 5.5, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '447419', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2775, 400, 'ERROR', 'La fila (6033, 8437017305170, 5.5, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '508740', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2776, 400, 'ERROR', 'La fila (6034, , 5.5, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '569944', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2777, 400, 'ERROR', 'La fila (7001, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '632167', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2778, 400, 'ERROR', 'La fila (8001, , 3.498, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '694593', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2779, 400, 'ERROR', 'La fila (8002, , 3.3072, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '756145', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2780, 400, 'ERROR', 'La fila (8003, , 3.498, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '818242', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2781, 400, 'ERROR', 'La fila (8004, , 3.498, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '880237', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2782, 400, 'ERROR', 'La fila (8010, , 1.6016, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:34', '941969', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:34'),
(2783, 400, 'ERROR', 'La fila (8011, , 1.6016, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:35', '003676', 76, '2018-07-06 10:51:34', '2018-07-06 10:51:35'),
(2784, 400, 'ERROR', 'La fila (8012, , 1.6016, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:35', '066072', 76, '2018-07-06 10:51:35', '2018-07-06 10:51:35'),
(2785, 400, 'ERROR', 'La fila (8013, , 3.0992, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:35', '128533', 76, '2018-07-06 10:51:35', '2018-07-06 10:51:35'),
(2786, 400, 'ERROR', 'La fila (8014, , 3.2968, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:35', '188905', 76, '2018-07-06 10:51:35', '2018-07-06 10:51:35'),
(2787, 400, 'ERROR', 'La fila (8050, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:35', '251266', 76, '2018-07-06 10:51:35', '2018-07-06 10:51:35'),
(2788, 400, 'ERROR', 'La fila (8100, , 3.498, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:35', '313219', 76, '2018-07-06 10:51:35', '2018-07-06 10:51:35'),
(2789, 400, 'ERROR', 'La fila (8101, , 3.498, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:35', '374508', 76, '2018-07-06 10:51:35', '2018-07-06 10:51:35'),
(2790, 400, 'ERROR', 'La fila (8200, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:35', '436738', 76, '2018-07-06 10:51:35', '2018-07-06 10:51:35'),
(2791, 400, 'ERROR', 'La fila (9999, , 0, 3) no corresponde a ningún Producto.', '[]', NULL, NULL, '2018-07-06 12:51:35', '845525', 76, '2018-07-06 10:51:35', '2018-07-06 10:51:35'),
(2792, 200, 'INFO', 'Se han creado / actualizado 12 Líneas de Tarifa.', '{"i":12}', NULL, NULL, '2018-07-06 12:51:35', '959507', 76, '2018-07-06 10:51:35', '2018-07-06 10:51:35'),
(2793, 200, 'INFO', 'Se han procesado 12 Líneas de Tarifa.', '{"i":12}', NULL, NULL, '2018-07-06 12:51:36', '021379', 76, '2018-07-06 10:51:36', '2018-07-06 10:51:36'),
(2794, 200, 'INFO', ':_> LOG terminado', '[]', NULL, NULL, '2018-07-06 12:51:36', '083547', 76, '2018-07-06 10:51:36', '2018-07-06 10:51:36');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `webshop_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_commercial` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_mobile` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `latitude` double(8,2) DEFAULT NULL,
  `longitude` double(8,2) DEFAULT NULL,
  `addressable_id` int(11) NOT NULL,
  `addressable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `country_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `alias`, `webshop_id`, `name_commercial`, `address1`, `address2`, `postcode`, `city`, `state_name`, `country_name`, `firstname`, `lastname`, `email`, `phone`, `phone_mobile`, `fax`, `notes`, `active`, `latitude`, `longitude`, `addressable_id`, `addressable_type`, `state_id`, `country_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'Dirección Principal COPIA', NULL, 'Mutronic', 'C/ Liberacion 2', 'Pol.Ind. Las Monjas', '28033', 'Madrid', NULL, NULL, 'Conchi', 'Martín', NULL, '+34 913 810 532', NULL, NULL, NULL, 1, NULL, NULL, 1, 'App\\CustomerOther', 32, 1, '2018-04-19 08:14:05', '2018-04-19 08:14:05', NULL),
(6, 'LA EXTRANATURAL', NULL, 'LA EXTRANATURAL', 'C/ RODRIGUEZ DE LA FUENTE 18', NULL, '41310', 'BRENES', NULL, NULL, 'Lidia', 'Martinez', 'lidiamartinez@laextranatural.es', NULL, '692 813 253', NULL, NULL, 1, NULL, NULL, 2, 'App\\Company', 42, 1, '2017-09-13 07:05:36', '2018-06-05 15:39:43', NULL),
(10, 'CONG', NULL, 'Almecén Congelado', '1th, Fake St.', NULL, NULL, 'Alcafrán', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Congelados en pallets', 1, NULL, NULL, 4, 'App\\Warehouse', 32, 1, '2018-06-04 08:56:00', '2018-06-04 08:56:00', NULL),
(11, 'FRESH', NULL, 'Almecén de Fresco', '1th, Fake St.', NULL, NULL, 'Alcafrán', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Congelados en pallets', 1, NULL, NULL, 2, 'App\\Warehouse', 32, 1, '2018-06-04 08:56:00', '2018-06-04 08:56:00', NULL),
(12, 'GEN', NULL, 'Almecén General', '1th, Fake St.', NULL, NULL, 'Alcafrán', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, 'App\\Warehouse', 32, 1, '2018-06-04 08:56:00', '2018-06-04 09:18:27', NULL),
(13, '12345', NULL, 'dfbadfbadfbadfbadfbadfb', 'xc <xc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 5, 'App\\Warehouse', 12, 1, '2018-06-16 14:31:51', '2018-06-16 19:49:33', '2018-06-16 19:49:33'),
(23, 'Dirección Principal', '', 'Gaia', 'C/ Jose Payan Garrido Nº2', NULL, '41920', 'San Juan de Aznalfarache', NULL, NULL, 'Mª Jose Lira', NULL, 'ppgaia@hotmail.com', '954561831', '610 767 640', NULL, '', 1, NULL, NULL, 1, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(24, 'Dirección Principal', '', 'Panacea', 'C/ Regina, nº 15 Acc E', NULL, '41003', 'Sevilla', NULL, NULL, 'Ma José', NULL, 'comercioconconciencia@gmail.com', NULL, '616628148', NULL, '', 0, NULL, NULL, 2, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(25, 'Dirección Principal', '', 'Ecologico y local', 'C/ Francisco Noa Marquez, 14', NULL, '41310', 'Brenes', NULL, NULL, 'Montse Castro', NULL, 'ecologicoylocal@gmail.com', NULL, '686 731 111', NULL, '', 1, NULL, NULL, 3, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(26, 'Dirección Principal', '', 'Ana Relator', 'C/ Relator, 51', NULL, '41003', 'Sevilla', NULL, NULL, NULL, NULL, 'anaconypunto@hotmail.com', '954 901 385', NULL, NULL, '', 1, NULL, NULL, 4, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(27, 'Dirección Principal', '', 'La Ortiga', 'C/ Cristo del Buen Fin,  nº 4', NULL, '41002', 'Sevilla', NULL, NULL, 'Fernando y Marcos', NULL, 'fernando@laortiga.com', '954 906 306 ', '633 430 515 ', NULL, '', 1, NULL, NULL, 5, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(28, 'Dirección Principal', '', 'Padipan', 'C/ Jimenez Aranda Nº 19', NULL, '41018', 'Sevilla', NULL, NULL, '679450395 Lala - 675837213 Lidia', NULL, NULL, '954 534 999 ', '679450395', NULL, '', 1, NULL, NULL, 6, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(29, 'Dirección Principal', '', 'La Espiga Luis', 'C/ Marques del Nervion, 95', NULL, '41005', 'Sevilla', NULL, NULL, 'Luis', NULL, 'promotexandalucia@gmail.com', NULL, '616 155 986', NULL, '', 1, NULL, NULL, 7, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(30, 'Dirección Principal', '', 'La Alacena de la Nena', 'Bar Las Palmeras Plaza Pintor Amalio García del Moral, 10 local 3', NULL, '41005', 'Sevilla', NULL, NULL, 'Julieta y Roberto', NULL, 'laalacenadelanena@gmail.com', '955 521 966', '655 775 454', NULL, '', 1, NULL, NULL, 8, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(31, 'Dirección Principal', '', 'Mr Cake', 'C/ Santa Barbara, 12 local', NULL, '41002', 'Sevilla', NULL, NULL, NULL, NULL, NULL, NULL, '605 634 494', NULL, '', 1, NULL, NULL, 9, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(32, 'Dirección Principal', '', 'Mas sano que una pera', 'C/ San Luis Nº 78 local 3', NULL, '41003', 'Sevilla', NULL, NULL, '955494235', NULL, NULL, '955288937', '617685126', NULL, '', 1, NULL, NULL, 10, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(33, 'Dirección Principal', '', 'Sonia Gallardo Trujillo', 'C/ Velazquez, 20', NULL, '41657', 'Los Corrales', NULL, NULL, NULL, NULL, 'soniaygloria@hotmail.com', NULL, '676240267', NULL, '', 1, NULL, NULL, 11, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(34, 'Dirección Principal', '', 'Zarabanda', 'C/ Padre Tarín, nº 6', NULL, '41002', 'Sevilla', NULL, NULL, 'Angela ', NULL, 'reservas@zarabandasevilla.es', '954903080', '657506534', NULL, '', 1, NULL, NULL, 12, 'App\\Customer', 42, 1, '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(35, 'Dirección Principal', '', 'El Arbol', 'C/ San Hermeregildo, 16', NULL, '41003', 'Sevilla', NULL, NULL, 'Sara Robles', NULL, 'sararobles@enelarbol.com', NULL, '627610156', NULL, '', 1, NULL, NULL, 13, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(36, 'Dirección Principal', '', 'Mª Cruz Irrebertegui', 'Casa Misericordia. Vuelta del Castillo 1', NULL, '31007', 'Pamplona', NULL, NULL, NULL, NULL, 'gurugarrues@hotmail.com', NULL, '669 155 295', NULL, '', 1, NULL, NULL, 14, 'App\\Customer', 35, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(37, 'Dirección Principal', '', 'A falta de pan', 'C/ Relator nº 23', NULL, '41002', 'Sevilla', NULL, NULL, 'Mª Angeles', NULL, 'jimenez.lamela@gmail.com', NULL, '687088300', NULL, '', 1, NULL, NULL, 15, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(38, 'Dirección Principal', '', 'Frescum', 'C/ Ortí Lara, 13  1  E', NULL, '14003', 'Córdoba', NULL, NULL, NULL, NULL, 'carlosperez@frescum.es', '957941710', NULL, NULL, '', 1, NULL, NULL, 16, 'App\\Customer', 18, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(39, 'Dirección Principal', '', 'La Rendija', 'C/ San Hermeregildo nº1', NULL, '41003', 'Sevilla', NULL, NULL, 'Margarita?', NULL, NULL, '955 226 007', NULL, NULL, '', 1, NULL, NULL, 17, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(40, 'Dirección Principal', '', 'Milk away', 'C/ Perez Galdós, 32', NULL, '41004', 'Sevilla', NULL, NULL, NULL, NULL, NULL, NULL, '600 213 747', NULL, '', 1, NULL, NULL, 18, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(41, 'Dirección Principal', '', 'Organic 49', 'C/ Urbieta,49', NULL, '20006', 'San Sebastian', NULL, NULL, 'Blanca o Noelia', NULL, 'tienda@organic49.com', '943 110 010', NULL, NULL, '', 1, NULL, NULL, 19, 'App\\Customer', 20, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(42, 'Dirección Principal', '', 'Bodegas Rojo', 'C/ Polvoranca, 13 Zona Carabanchel Alto', NULL, '28044', 'Madrid', NULL, NULL, 'Emilio Rojo', NULL, 'rojocruz1960@hotmail.com', NULL, '615 926 129', NULL, '', 1, NULL, NULL, 20, 'App\\Customer', 32, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(43, 'Dirección Principal', '', 'Silvia Lander Erdozain', 'Oficina de Empleo - Urb Kanpondoa, 45', NULL, '31430', 'Aoiz,Pamplona', NULL, NULL, NULL, NULL, 'silvialander@gmail.com', NULL, '649 005 120', NULL, '', 1, NULL, NULL, 21, 'App\\Customer', 35, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(44, 'Dirección Principal', '', 'Mercadosin', 'C/ Divina Pastora Edif 1 Portal 1 loc 7', NULL, '11402', 'Jerez', NULL, NULL, NULL, NULL, 'isabelyjuan82@gmail.com', NULL, '652149178', NULL, '', 1, NULL, NULL, 22, 'App\\Customer', 14, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(45, 'Dirección Principal', '', 'Panificadora Santa Verenia', 'C/ Mateos Muñoz, 27', NULL, '41310', 'Brenes', NULL, NULL, NULL, NULL, NULL, '954 796 523', NULL, NULL, '', 1, NULL, NULL, 23, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(46, 'Dirección Principal', '', 'La Andalusi', 'C/ Perez Galdós, 9-11', NULL, '41800', 'Sanlucar la Mayor', NULL, NULL, 'Antonio, Mohamed', NULL, 'panaderia.la.andalusi@gmail.com', '672239570', '655 173 174', NULL, '', 1, NULL, NULL, 24, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(47, 'Dirección Principal', '', 'Dieta Ecologica', 'C/ Fray Bartolomé de las Casas, 24', NULL, '41520', 'El Viso del Alcor', NULL, NULL, 'Jose Joaquin ', NULL, 'ventas@dietaecologica.com', NULL, '687 563 979', NULL, '', 1, NULL, NULL, 25, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(48, 'Dirección Principal', '', 'Mas que lechugas', 'C/ Butrón, 9 Bajo B', NULL, '41003', 'Sevilla', NULL, NULL, NULL, NULL, 'cestasecologicas@masquelechugas.com', '622188337', '655545375', NULL, '', 1, NULL, NULL, 26, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(49, 'Dirección Principal', '', 'Red Verde', 'C/ Relator, 44', NULL, '41003', 'Sevilla', NULL, NULL, 'Conchi', NULL, 'redverde@ymail.com', NULL, '678 639 215', NULL, '', 1, NULL, NULL, 27, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(50, 'Dirección Principal', '', 'Restaurante Fargo', 'C/ Mendoza de los Rios Nº 7', NULL, '41002', 'Sevilla', NULL, NULL, 'Yann', NULL, 'fargobio@gmail.com', '644 294 572', NULL, NULL, '', 1, NULL, NULL, 28, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(51, 'Dirección Principal', '', 'La Despensa Ecologica', 'C/ Regina, 24', NULL, '41003', 'Sevilla', NULL, NULL, NULL, NULL, 'ladespensaecologica@gmail.com', ' 954 221 593', '651 310 116', NULL, '', 1, NULL, NULL, 29, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(52, 'Dirección Principal', '', 'Eslaveco ', 'C/ Horizonte, 7 Calle 2 Nave 1 PI Pisa', NULL, '41927', 'Mairena del Aljarafe', NULL, NULL, NULL, NULL, 'eslaveco@eslaveco.com', '955 600 325', NULL, NULL, '', 1, NULL, NULL, 30, 'App\\Customer', 42, 1, '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(53, 'Dirección Principal', '', 'A x pan', 'C/ Pablo Iglesias Nº 3', NULL, '41009', 'Salteras', NULL, NULL, 'Paco', NULL, 'gastrotiendaaxpan@gmail.com', '955 708 382', NULL, NULL, '', 1, NULL, NULL, 31, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(54, 'Dirección Principal', '', 'NO EXISTE', 'C/ Nuncio Viejo, 4', NULL, '45001', 'Toledo', NULL, NULL, '609689070 Adolfo Toledo', NULL, 'facturacion@adolfo-toledo.com   Francisco', '952 252 991', NULL, NULL, '', 0, NULL, NULL, 32, 'App\\Customer', 46, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(55, 'Dirección Principal', '', 'Torres Pablos', 'C/ Alhelí, nº 1', NULL, '41008', 'Sevilla', NULL, NULL, NULL, NULL, NULL, NULL, '626 109 115', NULL, '', 0, NULL, NULL, 33, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(56, 'Dirección Principal', '', 'Food in Company', 'Avda De Los Arces, 12 PORTAL E 2D', NULL, '28042', 'Madrid', NULL, NULL, NULL, NULL, 'sevillaycadiz@gmail.com', NULL, '655 661 635', NULL, '', 1, NULL, NULL, 34, 'App\\Customer', 32, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(57, 'Dirección Principal', '', 'Verdy', 'Avda Paseo de Europa, 27, local 2', NULL, '41012', 'Sevilla', NULL, NULL, NULL, NULL, 'rocio@verdy.es', '955 641 558', '649 652 145', NULL, '', 1, NULL, NULL, 35, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(58, 'Dirección Principal', '', 'La Despensa Sana Villaverde', 'Pasaje de la Cruz, 6', NULL, '41318', 'Villaverde del Rio', NULL, NULL, NULL, NULL, NULL, NULL, '610 719 658', NULL, '', 0, NULL, NULL, 36, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(59, 'Dirección Principal', '', 'ANA', 'C/ PORTO NUM 5 BAJOS 3', NULL, '8206', 'SABADELL', NULL, NULL, NULL, NULL, 'anamarisbd40@gmail.com', NULL, '670285032', NULL, '', 1, NULL, NULL, 38, 'App\\Customer', 10, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(60, 'Dirección Principal', '', 'rosalino', 'C/ Rodriguez de la fuente, 18', NULL, '41310', 'brenes', NULL, NULL, NULL, NULL, 'rosalinokr@yahoo.com', NULL, '637541887', NULL, '', 1, NULL, NULL, 39, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(61, 'Dirección Principal', '', 'María Auxiliadora', 'C/ Simón, 20. 1ºA', NULL, '21410', 'Isla Cristina', NULL, NULL, NULL, NULL, 'auxibiedma@gmail.com', NULL, '635672777', NULL, '', 1, NULL, NULL, 40, 'App\\Customer', 24, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(62, 'Dirección Principal', '', 'ISABEL', 'C/ Conde Cifuentes 6, portal C, 4ª Dcha.', NULL, '41004', 'Sevilla', NULL, NULL, NULL, NULL, 'ialer@us.es', NULL, '667669599', NULL, '', 1, NULL, NULL, 41, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(63, 'Dirección Principal', '', 'Yolanda', 'Rella Kaesmacher 31 A', NULL, '21600', 'Valverde del Camino', NULL, NULL, NULL, NULL, 'ymantero@hotmail.es', NULL, '696015048', NULL, '', 1, NULL, NULL, 42, 'App\\Customer', 24, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(64, 'Dirección Principal', '', 'Myriam', 'C/ Hijuela de la canaleja,13,bloque 5 3ºB', NULL, '11406', 'Jerez de la frontera', NULL, NULL, NULL, NULL, 'myriameady@hotmail.com', NULL, '630117455', NULL, '', 1, NULL, NULL, 43, 'App\\Customer', 14, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(65, 'Dirección Principal', '', 'Silvia', 'Pza Estanislao Goñi, 6 bajo izda', NULL, '31400', 'Sangüesa', NULL, NULL, NULL, NULL, 'soo@movistar.es', NULL, '626869844', NULL, '', 1, NULL, NULL, 44, 'App\\Customer', 35, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(66, 'Dirección Principal', '', 'Aurora', 'C/ Rafael Alberti, 2', NULL, '29560', 'Pizarra', NULL, NULL, NULL, NULL, 'aurop-@hotmail.com', NULL, '618786275', NULL, '', 1, NULL, NULL, 45, 'App\\Customer', 33, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(67, 'Dirección Principal', '', 'monserrat', 'Avenida diego martinez barrios, 10, planta 9', NULL, '41013', 'sevilla', NULL, NULL, NULL, NULL, 'malvarez@notarriado.org', NULL, '608166425', NULL, '', 1, NULL, NULL, 46, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(68, 'Dirección Principal', '', 'Beni', 'C/ Jose vidal de torres', NULL, '41740', 'Lebrija', NULL, NULL, NULL, NULL, 'benisanco@hotmail.com', NULL, '651060882', NULL, '', 1, NULL, NULL, 47, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(69, 'Dirección Principal', '', 'GLORIA', 'C/ Ignacio fernandez castro, 8', NULL, '41807', 'ESPARTINAS', NULL, NULL, NULL, NULL, 'gloriacalzado@gmail.com', NULL, '661250127', NULL, '', 1, NULL, NULL, 48, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(70, 'Dirección Principal', '', 'PALOMA', 'AVDA. DE LA FILOSOFÍA, Nº9 PTAL 1, 1º D', NULL, '41927', 'MAIRENA DEL ALJARAFE', NULL, NULL, NULL, NULL, 'palomarodriglop@gmail.com', NULL, '653121358', NULL, '', 1, NULL, NULL, 49, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(71, 'Dirección Principal', '', 'José miguel', 'Av Portugal , 11', NULL, '21230', 'Cortegana', NULL, NULL, NULL, NULL, 'martinljm@hotmail.com', NULL, '627568897', NULL, '', 1, NULL, NULL, 50, 'App\\Customer', 24, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(72, 'Dirección Principal', '', 'Montse', 'Carrer cremat 20', NULL, '8221', 'Terrassa', NULL, NULL, NULL, NULL, 'ssahuqu@yahoo.es', NULL, '610514626', NULL, '', 1, NULL, NULL, 51, 'App\\Customer', 10, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(73, 'Dirección Principal', '', 'ALICIA', 'C/ JOSE LUIS VEGA, 11', NULL, '41970', 'SANTIPONCE', NULL, NULL, NULL, NULL, 'alicasban@andaluciajuntal.es', NULL, '676296262', NULL, '', 1, NULL, NULL, 52, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(74, 'Dirección Principal', '', 'Carlos', 'Avenida De la Cruz roja, 12, 3ºA', NULL, '41008', 'Sevilla', NULL, NULL, NULL, NULL, 'carliorfeo@hotmail.com', NULL, '657509182', NULL, '', 1, NULL, NULL, 53, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(75, 'Dirección Principal', '', 'monserrat', 'Avenida Diego Martinez Barrio, 10 planta 9', NULL, '41013', 'sevilla', NULL, NULL, NULL, NULL, 'malvarez@correonotarial.org', NULL, '608166425', NULL, '', 1, NULL, NULL, 54, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(76, 'Dirección Principal', '', 'Ana maria', 'Concepción soto,7 7A', NULL, '41219', 'Las Pajanosas/Guillena', NULL, NULL, NULL, NULL, 'anamariasuerogonzalez@gmail.com', NULL, '600660413', NULL, '', 1, NULL, NULL, 55, 'App\\Customer', 42, 1, '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(77, 'Dirección Principal', '', 'ELENA', 'C/ Corrales nº 10', NULL, '41520', 'EL VISO DEL ALCOR', NULL, NULL, NULL, NULL, 'elena.rodle@gmail.com', NULL, '653566639', NULL, '', 1, NULL, NULL, 56, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(78, 'Dirección Principal', '', 'Irune', 'C/ Señorio de Egulbati 10, 4B', NULL, '31016', 'Pamplona', NULL, NULL, NULL, NULL, 'irune_zubicaray@yahoo.es', NULL, '606248005', NULL, '', 1, NULL, NULL, 57, 'App\\Customer', 35, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(79, 'Dirección Principal', '', 'Aurora', 'C/Rafael Alberti 2', NULL, '29560', 'Pizarrra', NULL, NULL, NULL, NULL, 'almop21@gmail.com', NULL, '618786275', NULL, '', 1, NULL, NULL, 58, 'App\\Customer', 33, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(80, 'Dirección Principal', '', 'Ana Maria', 'Avenida de las Ciencias 10, portal 3 - 7ºB', NULL, '41020', 'Sevilla', NULL, NULL, NULL, NULL, 'ana.maria.moya11@gmail.com', NULL, '622 058 661', NULL, '', 1, NULL, NULL, 59, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(81, 'Dirección Principal', '', 'Luna', 'C/ Artesanía, N°6, 3D', NULL, '47005', 'Valladolid', NULL, NULL, NULL, NULL, 'lunafb16@gmail.com', NULL, '636350919', NULL, '', 1, NULL, NULL, 60, 'App\\Customer', 48, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(82, 'Dirección Principal', '', 'M. Carmen', 'C/ Donantes nº 7', NULL, '41568', 'El Rubio', NULL, NULL, NULL, NULL, 'mcpg82@hotmail.com', NULL, '695528067', NULL, '', 1, NULL, NULL, 61, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(83, 'Dirección Principal', '', 'EVA', 'C/ MARIO VARGAS LLOSA, 17', NULL, '28229', 'VILLANUEVA DEL PARDILLO', NULL, NULL, NULL, NULL, 'nevado.eva@gmail.com', NULL, '651986778', NULL, '', 1, NULL, NULL, 62, 'App\\Customer', 32, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(84, 'Dirección Principal', '', 'ALICIA', 'C/ JOSE LUIS VEGA, 11', NULL, '41970', 'SANTIPONCE', NULL, NULL, NULL, NULL, 'alicasban@andaluciajunta.es', NULL, '676296262', NULL, '', 1, NULL, NULL, 63, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(85, 'Dirección Principal', '', 'Biomarket Olivar', 'C/ Blas Cabrera 2 P3 3º A', NULL, '28660', 'Boadilla del Monte', NULL, NULL, NULL, NULL, 'maribelsanchezvernalte@gmail.com', NULL, '659721783', NULL, '', 1, NULL, NULL, 64, 'App\\Customer', 32, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(86, 'Dirección Principal', '', 'Paco Artesa', 'C/ Isaac Peral, 7-28 Pol. El Peral', NULL, '11630', 'Arcos de la Frontera', NULL, NULL, NULL, NULL, 'pacoruizsalguero@gmail.com', NULL, '615650345', NULL, '', 1, NULL, NULL, 65, 'App\\Customer', 14, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(87, 'Dirección Principal', '', 'Carmen', 'C/ Azucena, número 12, bajo', NULL, '41010', 'Sevilla', NULL, NULL, NULL, NULL, 'cqllqc@gmail.com', NULL, '649010608', NULL, '', 1, NULL, NULL, 67, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(88, 'Dirección Principal', '', 'EVA', 'C/MARQUES DE ESTELLA,8 2ºB', NULL, '41018', 'SEVILLA', NULL, NULL, NULL, NULL, 'landinoster@hotmail.com', NULL, '649918899', NULL, '', 1, NULL, NULL, 68, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(89, 'Dirección Principal', '', 'monserrat', 'Avda. Diego Martinez Barrio, 10, planta 9', NULL, '41013', 'sevilla', NULL, NULL, NULL, NULL, 'jimenolisl@gmail.com', NULL, '608166425', NULL, '', 1, NULL, NULL, 69, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(90, 'Dirección Principal', '', 'Alejandro Blanco A3 Asesores', 'Av Diego Mtnez Barrios,  4 Viapol Center Planta 4º Modulo 3', NULL, '41013', NULL, NULL, NULL, NULL, NULL, 'ablanco@grupoatres.es', NULL, '651814807', NULL, '', 1, NULL, NULL, 70, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(91, 'Dirección Principal', '', 'Almocafre ', 'Avda de los Custodios, 5', NULL, '14004', NULL, NULL, NULL, NULL, NULL, 'almocafre@almocafre.com', '957414050', '606384332', NULL, '', 1, NULL, NULL, 71, 'App\\Customer', 18, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(92, 'Dirección Principal', '', 'El puesto ecológico', 'C/ Animas Plaza de Abastos Puestos 1-2', NULL, '41530', 'Morón de la Frontera', NULL, NULL, NULL, NULL, 'elpuestoecologico@hotmail.com', NULL, '658927576', NULL, '', 1, NULL, NULL, 72, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(93, 'Dirección Principal', '', 'Cerro Viejo', 'C/ Antonio Delgado Nº 4, 1º derecha', NULL, '41005', 'Sevilla', NULL, NULL, 'Alvaro, Pilar, Jeny', NULL, 'gestion@cerroviejo.org', NULL, '650545606', NULL, '', 0, NULL, NULL, 73, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(94, 'Dirección Principal', '', 'Das Brot', 'C/ Vico nº 2', NULL, '11391', 'Facinas, Tarifa', NULL, NULL, 'Araceli, Birgit', NULL, 'birgit.dasbrot@gmx.de', '954386498', '693 808 197', NULL, '', 1, NULL, NULL, 74, 'App\\Customer', 14, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(95, 'Dirección Principal', '', 'Biotienda', 'C/ Lopez de Vega, 7', NULL, '41003', 'Sevilla', NULL, NULL, 'Juan Carlos, Patricia', NULL, 'info@biotienda.net', '954534048', '629446548', NULL, '', 1, NULL, NULL, 75, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(96, 'Dirección Principal', '', 'Eman e Hijos, SL', 'C/ Manuel Santa Ella, 31 La Higuerita Finca España ', NULL, '38206', 'La Laguna', NULL, NULL, 'Emilio Estevez del Castillo', NULL, 'emanehijos@hotmail.com', '922311707', NULL, NULL, '', 1, NULL, NULL, 76, 'App\\Customer', 40, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(97, 'Dirección Principal', '', 'Ma José Martinez Arjona', 'Avda Blas Infante, 76 local 11-B', NULL, '41310', 'Brenes', NULL, NULL, NULL, NULL, NULL, '673899214', '673899214', NULL, '', 1, NULL, NULL, 77, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(98, 'Dirección Principal', '', 'Sohiscert', 'Finca La Cañada Ctra Sevilla-Utrera km 20,8', NULL, '41710', 'Utrera', NULL, NULL, NULL, NULL, 'manuel@agroecosistema.org', '955868144', NULL, NULL, '', 1, NULL, NULL, 78, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(99, 'Dirección Principal', '', 'Cortijo Vistalegre', 'Ctra Cazalla-Real de la Jara, KM 0,5', NULL, '41370', 'Cazalla de la Sierra', NULL, NULL, 'Enrica Basilico', NULL, 'enricabasilico@gmail.com', '954883513', '687920007', NULL, '', 1, NULL, NULL, 79, 'App\\Customer', 42, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(100, 'Dirección Principal', '', 'Cafeteria Peregrino', 'Plaza del Concejo nº 3', NULL, '31001', 'Pamplona', NULL, NULL, NULL, NULL, 'ggracielg@gmail.com', '948212096', NULL, NULL, '', 1, NULL, NULL, 80, 'App\\Customer', 35, 1, '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(101, 'Dirección Principal', '', 'Tiendas ná!', 'C/ Asunción, nº 65B 4B', NULL, '41011', NULL, NULL, NULL, 'Macarena', NULL, 'cerogrupo@gmail.com', '954280258', '652670555', NULL, '', 1, NULL, NULL, 81, 'App\\Customer', 42, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(102, 'Dirección Principal', '', 'El Gato de Molino', 'Carretera Bética, 96', NULL, '41300', 'San José de la Rinconada', NULL, NULL, 'Juani Cantón', NULL, 'alimentacionelgatodelmolino@gmail.com', NULL, '620 478 630', NULL, '', 1, NULL, NULL, 82, 'App\\Customer', 42, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(103, 'Dirección Principal', '', 'Antonio', 'C/ Feria, 35', NULL, '14700', 'Palma del Río', NULL, NULL, NULL, NULL, 'antoniohigueras.na@gmail.com', NULL, '625331239', NULL, '', 1, NULL, NULL, 83, 'App\\Customer', 18, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(104, 'Dirección Principal', '', 'Miguel Ángel', 'Camino de los Guindales S/n', NULL, '41380', 'Alanis', NULL, NULL, NULL, NULL, 'miguelangelmv@yahoo.es', NULL, '667415189', NULL, '', 1, NULL, NULL, 84, 'App\\Customer', 42, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(105, 'Dirección Principal', '', 'EVA', 'C/ MARQUES DE ESTELLA 8 2 B', NULL, '41018', 'SEVILLA', NULL, NULL, NULL, NULL, 'landinoster@gmail.com', NULL, '649918899', NULL, '', 1, NULL, NULL, 85, 'App\\Customer', 42, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(106, 'Dirección Principal', '', 'Lidia', 'C/ Rodriguez de la fuente, 18', NULL, '41310', 'Brenes', NULL, NULL, NULL, NULL, 'lidiamartinez@laextranatural.es', NULL, NULL, NULL, '', 1, NULL, NULL, 86, 'App\\Customer', 42, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(107, 'Dirección Principal', '', 'Nueva Era', 'C/ Amor de Dios nº 1', NULL, '41002', 'Sevilla', NULL, NULL, NULL, NULL, 'nuevaerasevilla@hotmail.com', NULL, '620877024', NULL, '', 1, NULL, NULL, 87, 'App\\Customer', 42, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(108, 'Dirección Principal', '', 'Estraperlo', 'C/ Santa Rosa,4', NULL, '41013', 'Sevilla', NULL, NULL, NULL, NULL, 'estraperlo@estraperlosevilla.com', '954963538', '615061718', NULL, '', 1, NULL, NULL, 88, 'App\\Customer', 42, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(109, 'Dirección Principal', '', 'Mamafante y Papaposa', 'C/ Puerta de Cordoba, 1, portal 3, 1C', NULL, '41003', 'Sevilla', NULL, NULL, NULL, NULL, 'mamafanteypapaposa@gmail.com', NULL, '616060884', NULL, '', 1, NULL, NULL, 89, 'App\\Customer', 42, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(110, 'Dirección Principal', '', 'Mare Nostrum', 'Crta. 5405, km 17', NULL, '41230', 'Castilblanco de los Arroyos', NULL, NULL, 'Eugenia Madrid o Maria. Contabil', NULL, 'contabilidad@lacteos-mare-nostrum.com ', NULL, '663919118', NULL, 'Eugenia Madrid o Maria. Contabilidad Lola Montaño', 1, NULL, NULL, 90, 'App\\Customer', 42, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(111, 'Dirección Principal', '', 'Ana Mª Dominguez Silva', 'C/ Mañueta nº 16 - 1º D', NULL, '31001', 'Pamplona', NULL, NULL, NULL, NULL, 'anasil43@gmail.com', NULL, '654576517', NULL, '', 1, NULL, NULL, 91, 'App\\Customer', 35, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(112, 'Dirección Principal', '', 'Maria Santamaria Nieto', 'Avda Portugal nº 1', NULL, '41440', 'Lora del Rio', NULL, NULL, NULL, NULL, NULL, NULL, '606813743', NULL, '', 1, NULL, NULL, 92, 'App\\Customer', 42, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(113, 'Dirección Principal', '', 'Adolfo Toledo 2017', 'C/ CERRO DEL EMPERADOS S/N', NULL, '45002', NULL, NULL, NULL, 'facturacion@adolfo-toledo.com Fr', NULL, 'pedidos@adolfo-toledo.com', '952 252 991', '609 689 070', NULL, 'facturacion@adolfo-toledo.com Francisco', 1, NULL, NULL, 93, 'App\\Customer', 46, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(114, 'Dirección Principal', '', 'Ortzadar', 'C/ Felipe Gorriti nº 10', NULL, '31004', 'Pamplona', NULL, NULL, 'Antonio Elizagaray', NULL, 'biodenda_ortzadar@yahoo.es', '948150529', NULL, NULL, '', 1, NULL, NULL, 94, 'App\\Customer', 35, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(115, 'Dirección Principal', '', 'Lantana Herbolario', 'C/ Llerena nº 14', NULL, '41008', 'Sevilla', NULL, NULL, NULL, NULL, NULL, NULL, '657660157', NULL, '', 1, NULL, NULL, 95, 'App\\Customer', 42, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(116, 'Dirección Principal', '', 'Hierros y Aceros Rocio, SL', 'PI El Tomillar Parcela 62', NULL, '21730', 'Almonte', NULL, NULL, NULL, NULL, NULL, NULL, '657875179', NULL, '', 1, NULL, NULL, 96, 'App\\Customer', 24, 1, '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(117, 'Dirección Principal', '', 'Kombucheria', 'C/ Martin Alonso de Mesa, 1', NULL, '11150', 'Vejer de la Frontera', NULL, NULL, 'Katrin', NULL, NULL, '956451780', '62142716', NULL, '', 1, NULL, NULL, 97, 'App\\Customer', 14, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(118, 'Dirección Principal', '', 'La Carica Jerez', 'Plaza Doctor Antonio Valencia, 4 3D', NULL, '11404', 'Jerez de la Frontera', NULL, NULL, 'Carlos Camacho', NULL, 'fincalacarica@hotmail.com', NULL, '696644556', NULL, '', 1, NULL, NULL, 98, 'App\\Customer', 14, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(119, 'Dirección Principal', '', 'Sattva Herbolario', 'C/ Zaragoza, 17', NULL, '41001', 'Sevilla', NULL, NULL, 'Inma Ferrer', NULL, 'inmaferrer40@gmail.com', '954222250', NULL, NULL, '', 1, NULL, NULL, 100, 'App\\Customer', 42, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(120, 'Dirección Principal', '', 'Rocio', 'C/Temporal 8', NULL, '11510', 'Puerto Real-Cádiz', NULL, NULL, NULL, NULL, 'rrarozarena@gmail.com', NULL, '630915359', NULL, '', 1, NULL, NULL, 101, 'App\\Customer', 14, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(121, 'Dirección Principal', '', 'BIO PEPE, S.L.', 'C/ Laguna Dalga nº 10', NULL, '28021', 'Villaverde Alto', NULL, NULL, NULL, NULL, 'Biopepe08@yahoo.es', NULL, '615313357', NULL, '', 1, NULL, NULL, 102, 'App\\Customer', 32, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(122, 'Dirección Principal', '', 'Naturolia Essence, S.L.', 'C/ Feria, nº 51', NULL, '41003', 'Sevilla', NULL, NULL, 'José María Barroso', NULL, 'jmbarrosodiaz69@gmail.com', NULL, '625 98 86 65', NULL, '', 1, NULL, NULL, 103, 'App\\Customer', 42, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(123, 'Dirección Principal', '', 'Celia Jimenez ', 'Avda Primero de Mayo, 6 Bloque 1, 1ºE', NULL, '28521', 'Rivas Vaciamadrid', NULL, NULL, NULL, NULL, 'celiajive@gmail.com', NULL, '696772641', NULL, '', 1, NULL, NULL, 104, 'App\\Customer', 32, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(124, 'Dirección Principal', '', 'MERCEDES', 'GLORIETA DE LETONIA Nº 1', NULL, '41012', 'SEVILLA', NULL, NULL, NULL, NULL, 'mroncerodiaz@gmail.com', NULL, '667520322', NULL, '', 1, NULL, NULL, 105, 'App\\Customer', 42, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(125, 'Dirección Principal', '', 'Cristobal Medina Tirado', 'C/ Nueva, 20', NULL, '23700', 'Linares', NULL, NULL, NULL, NULL, NULL, NULL, '677379151', NULL, '', 1, NULL, NULL, 106, 'App\\Customer', 26, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(126, 'Dirección Principal', '', 'Ecovida', 'C/ Muñoz Capilla, 8', NULL, '41001', 'Sevilla', NULL, NULL, 'Alvaro', NULL, 'info@ecovidacordoba.com', NULL, '646292733', NULL, '', 1, NULL, NULL, 107, 'App\\Customer', 18, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(127, 'Dirección Principal', '', 'Maria Ronte Esteban Matovell', 'Avda Primero de Junio nº 56 4A', NULL, '34200', 'Venta de Baños', NULL, NULL, NULL, NULL, 'ronte26@hotmal.com', NULL, '647619487', NULL, '', 1, NULL, NULL, 108, 'App\\Customer', 37, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(128, 'Dirección Principal', '', 'Cristina Macias Delgado', 'C/ Corredera Nº 49 local pasillo', NULL, '11630', 'Arcos de la Frontera', NULL, NULL, NULL, NULL, 'cristi_macias@hotmail.com', NULL, '655758403', NULL, '', 1, NULL, NULL, 109, 'App\\Customer', 14, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(129, 'Dirección Principal', '', 'Betis Sport Bar', 'Avda de Italia s/n', NULL, '41012', 'Sevilla', NULL, NULL, NULL, NULL, NULL, NULL, '605658612', NULL, '', 1, NULL, NULL, 110, 'App\\Customer', 42, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(130, 'Dirección Principal', '', 'Biolanuza', 'Cami del Poble Nou de Sant Rafael, 75A', NULL, '3820', 'CONCENTAINA', NULL, NULL, 'Gaspar Mayor', NULL, 'info@biolanuza.es', '965806465', '601364358', NULL, '', 1, NULL, NULL, 111, 'App\\Customer', 2, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(131, 'Dirección Principal', '', 'Maria Hernandez Gallego', 'Paseo Santo Tomás 12, 1, 2', NULL, '5003', 'Avila', NULL, NULL, NULL, NULL, 'batestmh@gmail.com ', NULL, '637305173', NULL, '', 1, NULL, NULL, 112, 'App\\Customer', 7, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(132, 'Dirección Principal', '', 'La Plaza Verde', 'C/ La Raíz de Arriba nº 2A', NULL, '33936', 'Siero', NULL, NULL, '686 34 35 12', NULL, 'laplazaverde@hotmail.com', '984 49 73 10', '630 19 81 73', NULL, '', 1, NULL, NULL, 113, 'App\\Customer', 6, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(133, 'Dirección Principal', '', 'Biocentro Badajoz', 'Avda José Maria Alcaraz y Alenda nº 34 - 3ºB', NULL, '6011', 'Badajoz', NULL, NULL, NULL, NULL, 'biocentrobadajoz@gmail.com', '924 25 64 80', NULL, NULL, '', 1, NULL, NULL, 114, 'App\\Customer', 8, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(134, 'Dirección Principal', '', 'Manuel Jerez Distribuidor', 'Plaza Castañuela Bloque 1 Portal 1 Pta B', NULL, '11404', 'Jerez de la Frontera', NULL, NULL, NULL, NULL, 'fenixdistribucion029@gmail.com', NULL, '635117092', NULL, '', 1, NULL, NULL, 115, 'App\\Customer', 14, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(135, 'Dirección Principal', '', 'Mari Luz Eizaguirre', 'C/ Iurramendi Ibiltokia, 9-B', NULL, '20400', 'Tolosa', NULL, NULL, NULL, NULL, 'oiermi@gmail.com', NULL, '677681496', NULL, '', 1, NULL, NULL, 116, 'App\\Customer', 20, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(136, 'Dirección Principal', '', 'Carlos Vega Simón', 'C/ Navas nº 66', NULL, '41450', 'Constantina', NULL, NULL, NULL, NULL, 'vizentvegal@gmail.com', NULL, '686522304', NULL, '', 1, NULL, NULL, 117, 'App\\Customer', 42, 1, '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(137, 'Dirección Principal', '', 'Maria José Carrero', 'C/ Antonio Machado nº 81', NULL, '41657', 'Los Corrales', NULL, NULL, NULL, NULL, 'marijoe_cz@hotmail.com', NULL, '620883212', NULL, '', 1, NULL, NULL, 118, 'App\\Customer', 42, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(138, 'Dirección Principal', '', 'Galicia Sostenible', 'Avda Castelos nº 54', NULL, '36210', 'Vigo', NULL, NULL, 'fran@galiciasostenible.com', NULL, NULL, NULL, '698164291', NULL, '', 1, NULL, NULL, 119, 'App\\Customer', 38, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(139, 'Dirección Principal', '', 'Maria Dolores Vales Bravo', 'C/ Libertad nº 10', NULL, '6892', 'Trujillanos', NULL, NULL, NULL, NULL, 'mdolores777@gmail.com', NULL, '659041539', NULL, '', 1, NULL, NULL, 120, 'App\\Customer', 8, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(140, 'Dirección Principal', '', 'Itziar Pitillas Pellicer', 'C/ Irunlarrea, 28 4º Derecha', NULL, '31008', 'Pamplona', NULL, NULL, NULL, NULL, 'itzipp@hotmail.com', NULL, '696795702', NULL, '', 1, NULL, NULL, 121, 'App\\Customer', 35, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(141, 'Dirección Principal', '', 'Tere  A3 Asesores', 'Av Diego Mtnez Barrios,  4 Viapol Center Planta 4º Modulo 3', NULL, '41013', NULL, NULL, NULL, NULL, NULL, 'tgomez@grupoatres.es', NULL, '695333006', NULL, '', 1, NULL, NULL, 122, 'App\\Customer', 42, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(142, 'Dirección Principal', '', 'Wurst&burguer Maximiliam S.L.', 'C/ San Pablo n°22', NULL, '41001', 'Sevilla', NULL, NULL, '605897067 Mariano Mauri y 645739', NULL, 'mmauriargudo@hotmail.com', '685098242', '605897067', NULL, '605897067 Mariano Mauri y 645739121 Jenny Polo', 1, NULL, NULL, 123, 'App\\Customer', 42, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(143, 'Dirección Principal', '', 'Cinta Cabeza Barroso', 'C/Dulce Chacón 10', NULL, '41940', 'Tomares', NULL, NULL, NULL, NULL, 'cintacb@yahoo.es', NULL, NULL, NULL, '', 1, NULL, NULL, 124, 'App\\Customer', 42, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(144, 'Dirección Principal', '', 'Ecosur', 'C/ Sevilla, 84', NULL, '4410', 'Benahadux', NULL, NULL, 'Nieves Lozano', NULL, 'nieveslozano@ecosur.com', '950952997', NULL, NULL, '', 1, NULL, NULL, 125, 'App\\Customer', 5, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(145, 'Dirección Principal', '', 'José Manuel Durán Trujillo', 'C/ Córdoba, 2 (tienda Coviran)', NULL, '41370', 'Cazalla de la Sierra', NULL, NULL, NULL, NULL, 'jmduran@outlook.es', NULL, '680141192', NULL, '', 1, NULL, NULL, 126, 'App\\Customer', 42, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(146, 'Dirección Principal', '', 'Diana López Jiménez', 'C/ Gloria Fuertes, 21 Portal 1, 1-A', NULL, '28100', 'Alcobendas', NULL, NULL, NULL, NULL, 'dianitagsxr@hotmail.com', NULL, '655444746', NULL, '', 1, NULL, NULL, 127, 'App\\Customer', 32, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(147, 'Dirección Principal', '', 'RICARDO GARCIA BORREGO', 'CALLE ROSARIO 2 SEGUNDA PLANTA', NULL, '41001', 'Sevilla', NULL, NULL, NULL, NULL, 'ricardogarciaborrego@gmail.com', NULL, '675540528', NULL, '', 1, NULL, NULL, 128, 'App\\Customer', 42, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(148, 'Dirección Principal', '', 'Etelvino Alvarez Jara', 'C/ Fernando Belmonte nº 26', NULL, '21620', 'trigueros', NULL, NULL, NULL, NULL, 'atelvi@hotmail.com', NULL, '679380883', NULL, '', 1, NULL, NULL, 129, 'App\\Customer', 24, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(149, 'Dirección Principal', '', 'El Obrador Ecologico', 'Ctra Corella nº 7', NULL, '31500', 'Tudela', NULL, NULL, 'Ana Carmona', NULL, 'elobradorecologico@gmail.com', NULL, '686368735', NULL, '', 1, NULL, NULL, 131, 'App\\Customer', 35, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(150, 'Dirección Principal', '', 'Biosabor ', 'Ctra San José, Km 2', NULL, '4117', 'San Isidro-Níjar', NULL, NULL, 'Rafael Guareño', NULL, 'lmartin@biosabor.com', NULL, '603517724', NULL, '', 1, NULL, NULL, 132, 'App\\Customer', 5, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(151, 'Dirección Principal', '', 'Coq&roll', 'C/ Jimios nº 2', NULL, '41001', 'Sevilla', NULL, NULL, NULL, NULL, 'coqrollmarket@coqrollmarket.com', NULL, '670620040', NULL, '', 1, NULL, NULL, 133, 'App\\Customer', 42, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(152, 'Dirección Principal', '', 'Jose María Fernández', 'Instituto Odiel. Calle Góngora s/n', NULL, '21500', 'Gibraleón', NULL, NULL, 'Jose María Fernández', NULL, 'fernandezvaleo@yahoo.es', NULL, '692564185', NULL, '', 1, NULL, NULL, 134, 'App\\Customer', 24, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(153, 'Dirección Principal', '', 'Herboteca Sevilla', 'c/ Pedro Pérez Fernández 25 Local B', NULL, '41011', 'sevilla', NULL, NULL, NULL, NULL, NULL, '954285591', '955132530', NULL, '', 1, NULL, NULL, 135, 'App\\Customer', 42, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(154, 'Dirección Principal', '', 'Juan Estudillo Ramirez', 'Camino Cerro de la Espartosa-12', NULL, '11130', 'Chiclana', NULL, NULL, 'Juan Estudillo', NULL, 'elecodistribuidor@gmail.com', NULL, '670 48 43 22', NULL, '', 1, NULL, NULL, 136, 'App\\Customer', 14, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(155, 'Dirección Principal', '', 'Rafael Joaquin', 'C/ Luis Fuentes Bejarano Nº50 Bloque 19 letra B-izq', NULL, '41020', 'Sevilla', NULL, NULL, NULL, NULL, 'rjgddios@gmail.com', NULL, '686925769', NULL, '', 1, NULL, NULL, 137, 'App\\Customer', 42, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(156, 'Dirección Principal', '', 'Domingo Gaviño San Jerónimo', 'C/ Riopiedras, 6 piso 3 pta.Izquierda', NULL, '41015', 'Barrio de San Jerónimo', NULL, NULL, 'Domingo Gaviño ', NULL, 'sanoycercano@gmail.com', NULL, '679751328', NULL, '', 1, NULL, NULL, 138, 'App\\Customer', 42, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(157, 'Dirección Principal', '', 'Il canapaio', 'Calle Amor de Dios, 41 local 1', NULL, '41002', 'Sevilla', NULL, NULL, 'Luciano Sileo', NULL, 'ilcanapaio.se@gmail.com', '954 87 14 29', NULL, NULL, '', 1, NULL, NULL, 139, 'App\\Customer', 42, 1, '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(158, 'Dirección Principal', '', 'La Tuya Herbolario', 'C/ Madre Isabel Moreno, 19 ', NULL, '41005', 'Sevilla', NULL, NULL, 'Rosa Cabotá Gimeno', NULL, 'latuya@herboristerialatuya.com', '954656083', NULL, NULL, '', 1, NULL, NULL, 140, 'App\\Customer', 42, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(159, 'Dirección Principal', '', 'Farmasol Dos Hermanas', 'C/ Mijail Gorbachov nº 7', NULL, '41002', 'Sevilla', NULL, NULL, 'Esperanza', NULL, 'solanopharmadistributions@gmail.com', '954725772', NULL, NULL, '', 1, NULL, NULL, 141, 'App\\Customer', 42, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(160, 'Dirección Principal', '', 'Victoria y Lola Diputación', 'C/ Menedez Pelayo, 32', NULL, '41004', 'Sevilla', NULL, NULL, 'Victoria 658 102 180', NULL, NULL, NULL, '658102180', NULL, '', 1, NULL, NULL, 142, 'App\\Customer', 42, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(161, 'Dirección Principal', '', 'Font de Salut Herbolario', 'C/ La Font, 29', NULL, '8720', 'Vilafranca del Penedès', NULL, NULL, 'Rosario', NULL, 'info@fontdesalut.com', '938 92 42 40', '605824697', NULL, '', 1, NULL, NULL, 143, 'App\\Customer', 10, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(162, 'Dirección Principal', '', 'Supernatura', 'C/ Molinos Alta, 1 6ºE', NULL, '14001', 'Córdoba', NULL, NULL, 'Pilar Salamanca', NULL, 'forcamar@forcamar.com', '957482061', NULL, NULL, '', 1, NULL, NULL, 144, 'App\\Customer', 18, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(163, 'Dirección Principal', '', 'El Encinar Granada', 'C/ Margarita Xirgú, nº 5, Bajo.', NULL, '18007', NULL, NULL, NULL, 'Sole', NULL, 'gestioncompras@asociacionelencinar.org', '958819432', NULL, NULL, '', 1, NULL, NULL, 145, 'App\\Customer', 22, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(164, 'Dirección Principal', '', 'Larios Herboristeria', 'C/ del mar, 15', NULL, '29700', NULL, NULL, NULL, 'Mª Jose (antigua profesora en el', NULL, 'larios0209@hotmail.com', '952501947', NULL, NULL, 'Mª Jose (antigua profesora en el PUA)', 1, NULL, NULL, 146, 'App\\Customer', 33, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(165, 'Dirección Principal', '', 'BIO ALVERDE SL', 'PLAZA SAN MARTIN DE PORRES, 7', NULL, '41011', 'Sevilla', NULL, NULL, 'LOLA OLIVERA', NULL, 'mdolivera@bioalverde.com', '954120900', NULL, NULL, '', 1, NULL, NULL, 147, 'App\\Customer', 42, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(166, 'Dirección Principal', '', 'Maria del Carmen Sosa Fuentemilla', 'C/ La Montoncilla nº 17', NULL, '21730', 'Almonte', NULL, NULL, NULL, NULL, 'casofu@hotmail.com ', NULL, '655109148', NULL, '', 1, NULL, NULL, 148, 'App\\Customer', 24, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(167, 'Dirección Principal', '', 'MARIA AUXILIADORA CUESTA ESCORESCA', 'C/ALBAHACA S/N', NULL, '41220', 'BURGUILLOS', NULL, NULL, NULL, NULL, NULL, NULL, '605943825', NULL, '', 1, NULL, NULL, 149, 'App\\Customer', 42, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(168, 'Dirección Principal', '', 'Rosalia Sabores Alemania', 'Prozessionsweg, 20', NULL, '48346', 'Osteverm', NULL, NULL, NULL, NULL, 'rosalina.pascua@gmail.com', '491523417046', NULL, NULL, '', 1, NULL, NULL, 150, 'App\\Customer', 108, 5, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(169, 'Dirección Principal', '', 'Angeles Zarco Guzman', 'C/ Manuel Altolaguirre, 16 ', NULL, '41940', 'TOMARES', NULL, NULL, 'Angeles Zarco', NULL, 'angelesjavier1@hotmail.com', NULL, '625549994', NULL, '', 1, NULL, NULL, 151, 'App\\Customer', 42, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(170, 'Dirección Principal', '', 'Pilar Torres Rodriguez', 'Urbanización el duende nº 7 unifamiliar', NULL, '11405', 'Jerez de la Frontera', NULL, NULL, NULL, NULL, 'pilart7@gmail.com', NULL, '609973431', NULL, '', 1, NULL, NULL, 152, 'App\\Customer', 14, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(171, 'Dirección Principal', '', 'Love Organic ', 'Avda Ciudad de Melilla, 26 BL. 8 L3A', NULL, '29631', 'Arroyo de la Miel', NULL, NULL, 'Bruno', NULL, 'infomieloliva@gmail.com', NULL, '663601550', NULL, '', 1, NULL, NULL, 153, 'App\\Customer', 33, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(172, 'Dirección Principal', '', 'LA ERA ECOLOGICA', 'CALLE SANTA MARGARITA Nº 158', NULL, '29740', 'TORRE DEL MAR', NULL, NULL, 'elena- oscar- juan de dios', NULL, 'info@laeraecologica.com', NULL, '644385854', NULL, '', 1, NULL, NULL, 154, 'App\\Customer', 33, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(173, 'Dirección Principal', '', 'Ecologico de Abastos Dos Hermanas', 'Plaza de Abastos de Dos Hermanas, Puesto Nº 16 Plaza del Emigrante S/N', NULL, '41700', 'Dos Hermanas', NULL, NULL, 'Eva', NULL, 'Krastica@hotmail.com', NULL, '652574852', NULL, '', 1, NULL, NULL, 155, 'App\\Customer', 42, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(174, 'Dirección Principal', '', 'PIDEBIO', 'C/ Confianza nº 9', NULL, '29560', 'Pizarra', NULL, NULL, 'MANUEL RUIZ MIER LANZAC', NULL, 'pedidos@pidebioandalucia.com', NULL, '610210544', NULL, '', 1, NULL, NULL, 156, 'App\\Customer', 33, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(175, 'Dirección Principal', '', 'José Antonio Neto', 'Avda Alemania nº 27, 4E', NULL, '21002', NULL, NULL, NULL, 'José Antonio Neto', NULL, 'hello@iamjaneto.com ', NULL, '622751018', NULL, '', 1, NULL, NULL, 157, 'App\\Customer', 24, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(176, 'Dirección Principal', '', 'Vicente Manzano-Arrondo', 'C/ Ganado, 45 1ºC', NULL, '11500', 'El Puerto de Santa Maria', NULL, NULL, NULL, NULL, 'vmanzano@us.es', '856922016', NULL, NULL, '', 1, NULL, NULL, 158, 'App\\Customer', 14, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(177, 'Dirección Principal', '', 'ATMANA', 'plaza de las Infantas, 42A', NULL, '11540', 'SANLUCAR DE  BARRAMEDA', NULL, NULL, 'VALENTINA G. PLATA', NULL, 'valentina@shantala.es', NULL, '678579224', NULL, '', 1, NULL, NULL, 159, 'App\\Customer', 14, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(178, 'Dirección Principal', '', 'Fernando Hoyos Mielgo', 'Gaztelumendi 11B bajo E', NULL, '48991', 'ALGORTA', NULL, NULL, NULL, NULL, 'fhoyosm@gmail.com ', NULL, '619481380', NULL, '', 1, NULL, NULL, 161, 'App\\Customer', 11, 1, '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(179, 'Dirección Principal', '', 'Lourdes Rodriguez', 'C/ Afrodita, 10 escalera 2, 9-1', NULL, '41014', 'Sevilla', NULL, NULL, 'Lourdes Rodriguez', NULL, 'zonalourdes@gmail.com', NULL, '610910909', NULL, '', 1, NULL, NULL, 162, 'App\\Customer', 42, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(180, 'Dirección Principal', '', 'La Grana ', 'CR Manresa - Abrera, Km 21', NULL, '8295', 'Sant Vicent de Castellet', NULL, NULL, 'Ruth ', NULL, 'info@la-grana.com', '938331300', NULL, NULL, '', 1, NULL, NULL, 163, 'App\\Customer', 10, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(181, 'Dirección Principal', '', 'La Sostenible', 'C/ San Pedro,16', NULL, '11004', NULL, NULL, NULL, 'Ismael Riscart Franco', NULL, 'lasostenible@gmail.com', NULL, '670002111', NULL, '', 1, NULL, NULL, 164, 'App\\Customer', 14, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(182, 'Dirección Principal', '', 'Rosa Sáenz Moriche', 'Calle Almirante Nelson nº7', NULL, '11150', 'Vejer de la Frontera', NULL, NULL, NULL, NULL, 'guadiana76@hotmail.com', NULL, '615899105', NULL, '', 1, NULL, NULL, 165, 'App\\Customer', 14, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(183, 'Dirección Principal', '', 'Alfonso Moreno', 'calle Londres 106', NULL, '41012', 'Sevilla', NULL, NULL, NULL, NULL, 'amorgir@live.com ', NULL, '695217362', NULL, '', 1, NULL, NULL, 166, 'App\\Customer', 42, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(184, 'Dirección Principal', '', 'La Ventana Natural', 'C/Poeta Miguel Hernández, 23 BAJO ', NULL, '3201', 'ELCHE', NULL, NULL, NULL, NULL, 'laventana@lomasnatural.com', '965 997 970', NULL, NULL, '', 1, NULL, NULL, 167, 'App\\Customer', 2, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(185, 'Dirección Principal', '', 'Israel Moreno Rodríguez', 'calle puerta de córdoba nº6', NULL, '41930', 'Bormujos', NULL, NULL, NULL, NULL, 'arscantus@hotmail.com ', NULL, '615564900', NULL, '', 1, NULL, NULL, 168, 'App\\Customer', 42, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(186, 'Dirección Principal', '', 'Rosa María Chaves Miranda', 'Calle María Vazquez Ponce 53-3B', NULL, '41300', 'San José de la Rinconada', NULL, NULL, NULL, NULL, 'ccomercial@rmpromocionate.com', NULL, '605195744', NULL, '', 1, NULL, NULL, 169, 'App\\Customer', 42, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(187, 'Dirección Principal', '', 'Laura Parrilla Gomez', 'C/ Navas, 66A', NULL, '41450', 'Constantina', NULL, NULL, 'Laura Parrilla', NULL, 'mancheguita_puerto@hotmail.com', NULL, '686285471', NULL, '', 1, NULL, NULL, 170, 'App\\Customer', 42, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL);
INSERT INTO `addresses` (`id`, `alias`, `webshop_id`, `name_commercial`, `address1`, `address2`, `postcode`, `city`, `state_name`, `country_name`, `firstname`, `lastname`, `email`, `phone`, `phone_mobile`, `fax`, `notes`, `active`, `latitude`, `longitude`, `addressable_id`, `addressable_type`, `state_id`, `country_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(188, 'Dirección Principal', '', 'Pidebio Madrid', 'plataforma baja, nave beta 9 Calle diez. Mercamadrid', NULL, '28053', 'Madrid', NULL, NULL, 'Maite', NULL, 'administracion@pidebio.com', NULL, NULL, NULL, '', 1, NULL, NULL, 171, 'App\\Customer', 32, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(189, 'Dirección Principal', '', 'Segarra Villas CB', 'Pasaje de los Poetas, 1', NULL, '28670', 'Villaviciosa de Odón', NULL, NULL, 'Ma Nieves Segarra - Victor Diaz ', NULL, 'nieves.segarra@hotmail.es', NULL, '651723508', NULL, 'Ma Nieves Segarra - Victor Diaz Hernandez', 1, NULL, NULL, 172, 'App\\Customer', 32, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(190, 'Dirección Principal', '', 'Supermercado Ecológico el Vergel', 'Paseo de la Florida 53', NULL, '28008', 'Madrid', NULL, NULL, 'GLORIA', NULL, 'glo@el-vergel.es', '915471952', NULL, NULL, '', 1, NULL, NULL, 173, 'App\\Customer', 32, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(191, 'Dirección Principal', '', 'Landare', 'Travesía Bernardino Tirapu 6 bajo', NULL, '31014', 'Pamplona', NULL, NULL, '948121308 ext4 Leyre, ext1 Patri', NULL, 'pedidos@landare.org', '948121308', NULL, NULL, '948121308 ext4 Leyre, ext1 Patricia, ext3 Amaia', 1, NULL, NULL, 174, 'App\\Customer', 35, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(192, 'Dirección Principal', '', 'El Ecosúper', 'C/ Molinos, 52, bajo', NULL, '18009', NULL, NULL, NULL, 'Fernan', NULL, 'elecosuper@gmail.com', '858988761', NULL, NULL, '', 1, NULL, NULL, 175, 'App\\Customer', 22, 1, '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(193, 'Dirección Principal', '', 'Gumendi', 'C/ Los Cabezos, S/N', NULL, '31580', 'Lodosa', NULL, NULL, 'Elena Romero (compras)', NULL, 'facturas@gumendi.es', '948693043', NULL, NULL, '', 1, NULL, NULL, 177, 'App\\Customer', 35, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(194, 'Dirección Principal', '', 'Aaron Rojas', 'C/ Ocho de Marzo, 2 Portal 9 2ºB', NULL, '28922', 'Alcorcón ', NULL, NULL, NULL, NULL, 'a.d.rojasroldan@gmail.com', NULL, '675925352', NULL, '', 1, NULL, NULL, 178, 'App\\Customer', 32, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(195, 'Dirección Principal', '', 'ORGANIC FOOD & CAFE', 'Between 2nd and 3rd Interchange             Sheikh Zayed Road', NULL, '49337', 'Al Quoz Industrial area', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, 179, 'App\\Customer', 109, 6, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(196, 'Dirección Principal', '', 'LA ECOCINA', 'C/ MARQUES DE URQUIJO, 47', NULL, '28008', 'Madrid', NULL, NULL, 'Mª Carmen Trapero', NULL, 'mcarmen.trapero@laecocina.es', NULL, '638384256', NULL, '', 1, NULL, NULL, 180, 'App\\Customer', 32, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(197, 'Dirección Principal', '', 'Montserrat Alvarez', 'C/ Camino, 5 Bajo', NULL, '20004', 'San Sebastián', NULL, NULL, 'Montserrat Alvarez', NULL, 'xantigh@gmail.com', NULL, '628122900', NULL, '', 1, NULL, NULL, 181, 'App\\Customer', 20, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(198, 'Dirección Principal', '', 'guzman gastronomia', 'C/ Longitudinal 5, nº 53 – Mercabarna -', NULL, '8040', 'barcelona', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, 182, 'App\\Customer', 10, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(199, 'Dirección Principal', '', 'Bea Oneca Uriz', 'Bar Imperio Plaza Oriente, 2', NULL, '31490', 'Caseda', NULL, NULL, 'Bea Oneca', NULL, 'beaoneca@hotmail.com', '948879062', NULL, NULL, '', 1, NULL, NULL, 183, 'App\\Customer', 35, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(200, 'Dirección Principal', '', 'Flor de la Vida Herbolario Bellavista', 'C/ Guadalajara, 63 local 2', NULL, '41014', 'Bellavista', NULL, NULL, 'Carolina y Laura', NULL, 'hflordelavida@gmail.com', '955130454', '693739246', NULL, '', 1, NULL, NULL, 184, 'App\\Customer', 42, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(201, 'Dirección Principal', '', 'Rosario Perez del Amo', 'UNIA Monasterio de la Cartuja-Calle Americo Vespucio nº2', NULL, '41092', 'SEVILLA', NULL, NULL, NULL, NULL, 'rosdelamo@hotmail.com', NULL, '620307029', NULL, '', 1, NULL, NULL, 185, 'App\\Customer', 42, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(202, 'Dirección Principal', '', 'herbolario jazmín y Azahar', 'C/ José María de Mena, 2 local D ', NULL, '41008', 'Sevilla', NULL, NULL, 'Rosa María Sarrió', NULL, 'herbolariojazminyazahar@gmail.com', '955322835', '651647504', NULL, '', 1, NULL, NULL, 186, 'App\\Customer', 42, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(203, 'Dirección Principal', '', 'Laura Lobato', 'Parque Comercial San Jerónimo Calle B Nave 25', NULL, '41015', 'Barrio de San Jerónimo', NULL, NULL, NULL, NULL, 'comercial@tatamba.com', NULL, '625331518', NULL, '', 1, NULL, NULL, 187, 'App\\Customer', 42, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(204, 'Dirección Principal', '', 'Alicia Olmo Nuñez', 'Jose María Olazabal, 21 Urbanización Hato Verde', NULL, '41219', 'Las Pajanosas/Guillena', NULL, NULL, NULL, NULL, 'aliolmo2006@hotmail.com', NULL, '617048124', NULL, '', 1, NULL, NULL, 188, 'App\\Customer', 42, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(205, 'Dirección Principal', '', 'Momentos', 'C/ Regina, 15', NULL, '41003', 'Sevilla', NULL, NULL, 'Mª Carmen Manzano', NULL, 'j6madrid@gmail.com', NULL, '650873029', NULL, '', 1, NULL, NULL, 189, 'App\\Customer', 42, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(206, 'Dirección Principal', '', 'Bar Los Gemelos', 'Avda Hermanos Noain s/n', NULL, '31013', 'Ansoain', NULL, NULL, NULL, NULL, 'susana.ciga@unavarra.es', NULL, '669563710', NULL, '', 1, NULL, NULL, 190, 'App\\Customer', 35, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(207, 'Dirección Principal', '', 'Galizia Sostenible', 'Avda Castrelos nº 161', NULL, '36210', 'Vigo', NULL, NULL, 'Francisco José Ruiz Perez', NULL, 'fran@galiciasostenible.com', NULL, '698164291', NULL, '', 1, NULL, NULL, 191, 'App\\Customer', 38, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(208, 'Dirección Principal', '', 'Mª del Valle', 'Barriada Huerta del Patrocinio nº3', NULL, '41318', 'Villaverde del Rio', NULL, NULL, NULL, NULL, NULL, NULL, '635509785', NULL, '', 1, NULL, NULL, 192, 'App\\Customer', 42, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(209, 'Dirección Principal', '', 'El Mastrén Málaga', 'C/ Residencial Reina, 10', NULL, '29713', 'Los Romanes', NULL, NULL, 'Miguel Ángel Reina García', NULL, 'info@elmastren.es', NULL, '618247995', NULL, '', 1, NULL, NULL, 193, 'App\\Customer', 33, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(210, 'Dirección Principal', '', 'Teresa Monrea Perez de Ciriza', 'Plaza de los Fueros nº 3 Ayuntamiento de Noain (Valle de Elorz) ', NULL, '31110', 'Noain', NULL, NULL, NULL, NULL, 'mtomonreal@gmail.com', NULL, '693137208', NULL, '', 1, NULL, NULL, 194, 'App\\Customer', 35, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(211, 'Dirección Principal', '', 'venta contado', 'C/ Rodriguez de la fuente, 18', NULL, '41310', 'Brenes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, 195, 'App\\Customer', 42, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(212, 'Dirección Principal', '', 'Macarena Rodriguez Cuesta', 'C/ Jose Luis de Casso nº 50', NULL, '41005', 'Sevilla', NULL, NULL, 'Macarena Rodriguez Cuesta', NULL, 'macrodcu@gmail.com', NULL, '670694501', NULL, '', 1, NULL, NULL, 196, 'App\\Customer', 42, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(213, 'Dirección Principal', '', 'Carolina Sanz Badallo', 'C/ Respaldo Lope de Vega nº 8 - 2C', NULL, '47400', 'Medina del Campo', NULL, NULL, 'Carolina Sanz Badallo', NULL, 'carolsanz_b@hotmail.com ', NULL, '653106162', NULL, '', 1, NULL, NULL, 197, 'App\\Customer', 48, 1, '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(214, 'Dirección Principal', '', 'Kimetzbelardenda', 'Ctra Leiza nº3 Bajo', NULL, '31740', 'Doneztebe', NULL, NULL, 'Lorea Aguirre', NULL, 'info@kimetzbelardenda.com', '948451473', NULL, NULL, '', 1, NULL, NULL, 198, 'App\\Customer', 35, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(215, 'Dirección Principal', '', 'Lola Caraballo Matito', 'Avda de la Barzola Nº 28 - 2º A Izquierda', NULL, '41008', 'Sevilla', NULL, NULL, 'Lola Caraballo Matita', NULL, 'lolacaraballo@gmail.com', NULL, '615074427', NULL, '', 1, NULL, NULL, 199, 'App\\Customer', 42, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(216, 'Dirección Principal', '', 'Casa Santiveri Tarifa', 'Calle San Julián, 2 Local', NULL, '11380', 'Tarifa', NULL, NULL, 'Pepi-Elsa', NULL, 'pedidos@tarifanatural.com', '956681628', '648445114', NULL, '', 1, NULL, NULL, 200, 'App\\Customer', 14, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(217, 'Dirección Principal', '', 'Oreka Nutrizioa eta Dietetika Belardenda', 'Arretxea, 11 - 4. Behea', NULL, '31770', 'Lesaka', NULL, NULL, 'Sandra Legasa Rosa', NULL, 'orekanutrizioa@hotmail.com', '948637373', NULL, NULL, '', 1, NULL, NULL, 201, 'App\\Customer', 35, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(218, 'Dirección Principal', '', 'Jose Antonio Arroyo Pais', 'Calle Real nº 56', NULL, '47152', 'Puente Duero', NULL, NULL, 'Jose Antonio Arroyo Pais', NULL, 'jaarroyopa@yahoo.es', NULL, '648011457', NULL, '', 1, NULL, NULL, 202, 'App\\Customer', 48, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(219, 'Dirección Principal', '', 'Joaquín Luna García', 'Jose María Olazabal 21. Urb. Hato Verde', NULL, '41219', 'Las Pajanosas/Guillena', NULL, NULL, 'Joaquín Luna García', NULL, 'quimluna@hotmail.com', NULL, '617345818', NULL, '', 1, NULL, NULL, 203, 'App\\Customer', 42, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(220, 'Dirección Principal', '', 'GL Asesores', 'Ansoain 1-3 B', NULL, '31014', 'Pamplona', NULL, NULL, 'Ainhoa Esparza Santesteban', NULL, 'larconada@hotmail.com', NULL, '639394500', NULL, '', 1, NULL, NULL, 204, 'App\\Customer', 35, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(221, 'Dirección Principal', '', 'Inmaculada Andrades Pineda ', 'C/ Aldebarán nº 86', NULL, '41111', 'Almensilla', NULL, NULL, 'Inmaculada Andrades Pineda ', NULL, 'atheneaglaukopis@gmail.com', NULL, '689374248', NULL, '', 1, NULL, NULL, 205, 'App\\Customer', 42, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(222, 'Dirección Principal', '', 'Freefood', 'C/ Salvador Mundi, 2', NULL, '8017', 'Barcelona', NULL, NULL, 'David', NULL, 'info@freefood.es', '932806603', NULL, NULL, '', 1, NULL, NULL, 206, 'App\\Customer', 10, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(223, 'Dirección Principal', '', 'Rosario Perez Perez', 'C/ Constelación  Osa Menor, 55', NULL, '41710', 'Utrera', NULL, NULL, 'Rosario Perez Perez', NULL, 'rosario_perezperez@yahoo.es', NULL, '645982202', NULL, '', 1, NULL, NULL, 207, 'App\\Customer', 42, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(224, 'Dirección Principal', '', 'Francisca Garrido Estepa', 'Mercado Sanchez Peña, Puesto 2 y 3', NULL, '14002', 'Cordoba', NULL, NULL, 'Paqui Garrido', NULL, 'franciscagarrido1967@gmail.com', NULL, '654033606', NULL, '', 1, NULL, NULL, 208, 'App\\Customer', 18, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(225, 'Dirección Principal', '', 'Ecobox Food', 'Avda Moncloa, 3', NULL, '28003', NULL, NULL, NULL, 'Pablo Moraleda o Magali', NULL, 'pablo@ecoboxfood.com; magali@ecoboxfood.com', NULL, '678545150', NULL, '', 1, NULL, NULL, 209, 'App\\Customer', 32, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(226, 'Dirección Principal', '', 'Finca Fuentillezjos', 'Ctra. Alarcos a Corral de Calatrava Km 4,5', NULL, '13195', 'Poblete', NULL, NULL, 'Concha /Mario C. García', NULL, 'export@manchegobio.com', NULL, '664469101', NULL, '', 1, NULL, NULL, 210, 'App\\Customer', 17, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(227, 'Dirección Principal', '', 'Ana Ciga Romero', 'C/ Ronda de las Ventas, 21', NULL, '31610', NULL, NULL, NULL, 'Ana Ciga Romero', NULL, 'anaiosucj@gmail.com', NULL, '639859290', NULL, '', 1, NULL, NULL, 211, 'App\\Customer', 35, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(228, 'Dirección Principal', '', 'Lola Bayod Mir', 'C/ Mayor, 16', NULL, '44643', 'La Ginebrosa', NULL, NULL, 'Lola Bayod', NULL, 'mdbayod@gmail.com', NULL, '659091267', NULL, '', 1, NULL, NULL, 212, 'App\\Customer', 45, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(229, 'Dirección Principal', '', 'Rosario Vera ', 'C/ Higuera de la Sierra, 5 - 2º B', NULL, '41008', 'Sevilla', NULL, NULL, 'Rosario Vera ', NULL, 'roop2310@hotmail.com', NULL, '662329506', NULL, '', 1, NULL, NULL, 213, 'App\\Customer', 42, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(230, 'Dirección Principal', '', 'Elena Sanchez del Coso', 'Pasaje Costa de Almeria nº 10 Portal 7 Esc 1, 4º-1', NULL, '4720', 'Aguadulce', NULL, NULL, NULL, NULL, 'elenadelcoso@hotmail.com', NULL, '665366951', NULL, '', 1, NULL, NULL, 214, 'App\\Customer', 5, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(231, 'Dirección Principal', '', 'Marta Romero Superregui JMJ Asesores', 'C/ Orfebre Dominguez Vazquez nº 1 - 2 D', NULL, '41015', 'Barrio de San Jerónimo', NULL, NULL, NULL, NULL, 'marosus@hotmail.com', NULL, '630268501', NULL, '', 1, NULL, NULL, 215, 'App\\Customer', 42, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(232, 'Dirección Principal', '', 'TRANSPORTES FRIGORIFICOS NARVAL SEVILLA', 'P.I. ZAL 2. CALLE DE LA EXCLUSA S/N', NULL, '41001', 'Sevilla', NULL, NULL, 'MARIA TORNERO', NULL, 'ATC@NARVAL', '917867049', NULL, NULL, '', 1, NULL, NULL, 216, 'App\\Customer', 42, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(233, 'Dirección Principal', '', 'Mercedes Linares', 'C/ San Vicente, 58 1º D', NULL, '41002', 'Sevilla', NULL, NULL, NULL, NULL, 'mercedeslgdp@us.es', NULL, '615421201', NULL, '', 1, NULL, NULL, 217, 'App\\Customer', 42, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(234, 'Dirección Principal', '', 'María Rodríguez Fernández', 'C/ Rafael Montesinos Urb La Retama II, Bajo 2', NULL, '21100', 'Punta Umbría', NULL, NULL, 'María Rodríguez ', NULL, 'maia.rguez@yahoo.es', NULL, '662432036', NULL, '', 1, NULL, NULL, 218, 'App\\Customer', 24, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(235, 'Dirección Principal', '', 'María Isabel Siles Cantalejo', 'C/ Ducado, 12', NULL, '41006', NULL, NULL, NULL, NULL, NULL, 'msilescantalejo@gmail.com', NULL, '697907891', NULL, '', 1, NULL, NULL, 219, 'App\\Customer', 42, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(236, 'Dirección Principal', '', 'Botellas y Latas', 'C/ Regina, 15', NULL, '41003', 'Sevilla', NULL, NULL, 'Carlos Calzada', NULL, 'botellaslatas@hotmail.com', '954293122', NULL, NULL, '', 1, NULL, NULL, 220, 'App\\Customer', 42, 1, '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(237, 'Dirección Principal', '', 'Carmen Martínez Moya', 'C/ Rubén Dario, 8, 4º A', NULL, '18800', 'Baza', NULL, NULL, 'Carmen Martinez', NULL, 'carmenkini3@gmail.com', NULL, '655333602', NULL, '', 1, NULL, NULL, 221, 'App\\Customer', 22, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(238, 'Dirección Principal', '', 'Aida Poveda Escudero', 'C/ Ventarich nº 9', NULL, '3204', 'Elche', NULL, NULL, 'Aida Poveda ', NULL, 'aidika86@hotmail.com', NULL, '622298110', NULL, '', 1, NULL, NULL, 222, 'App\\Customer', 2, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(239, 'Dirección Principal', '', 'Andrés Madueño Baena', 'C/ Andrés Carrasco Rebollo nº 18', NULL, '41980', 'La Algaba', NULL, NULL, 'Andrés Maadueño', NULL, 'ambaena75@gmail.com', NULL, '606523074', NULL, '', 1, NULL, NULL, 223, 'App\\Customer', 42, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(240, 'Dirección Principal', '', 'Margarita Patilla Villuendas', 'C/ Sevilla, nº 52 - Buzón 143 Urbanización Tarazona', NULL, '41300', 'San José de la Rinconada', NULL, NULL, 'Margarita Patilla ', NULL, 'fincahogar@me.com', NULL, '696602026', NULL, '', 1, NULL, NULL, 224, 'App\\Customer', 42, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(241, 'Dirección Principal', '', 'Agustina Barrera Escobar', 'Palacio de San Telmo - Area de Protocolo - Paseo de Roma S/N', NULL, '41013', 'sevilla', NULL, NULL, NULL, NULL, 'gaditina@gmail.com', NULL, '671569820', NULL, '', 1, NULL, NULL, 225, 'App\\Customer', 42, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(242, 'Dirección Principal', '', 'Noelia López Sánchez', 'FCC Aqualia SA Conjunto El Carmen Edif San Antonio Bajo Nº 17', NULL, '29700', 'Velez- Málaga', NULL, NULL, 'Noelia López Sánchez', NULL, 'noe3lopez@gmail.com', NULL, '677361199', NULL, '', 1, NULL, NULL, 226, 'App\\Customer', 33, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(243, 'Dirección Principal', '', 'Teresa Oliveras', 'Avda Buhaira, 18 Escalera 4-3D', NULL, '41018', 'Sevilla', NULL, NULL, NULL, NULL, 'teresa.oliveras@gmail.com', NULL, '667434050', NULL, '', 1, NULL, NULL, 227, 'App\\Customer', 42, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(244, 'Dirección Principal', '', 'Vantana Bar', 'C/ Cuna nº 14', NULL, '41004', 'Sevilla', NULL, NULL, 'Pepe Osorno', NULL, 'vantanabar@gmail.com', NULL, '607600642', NULL, '', 1, NULL, NULL, 228, 'App\\Customer', 42, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(245, 'Dirección Principal', '', 'Beatriz Cáceres ', 'Avda Medicos sin Fronteras nº 29 local 1', NULL, '41020', 'Sevilla', NULL, NULL, NULL, NULL, 'beacaceres29@gmail.com', NULL, '666273793', NULL, '', 1, NULL, NULL, 229, 'App\\Customer', 42, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(246, 'Dirección Principal', '', 'Alfredo Serrano Yarza', 'C/ Illueca, nº 4 - 6ºD piso', NULL, '50008', NULL, NULL, NULL, 'Alfredo Serrano Yarza', NULL, 'aseyarza@hotmail.com', NULL, '699163774', NULL, '', 1, NULL, NULL, 230, 'App\\Customer', 50, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(247, 'Dirección Principal', '', 'Juan José Muñoz Acosta', 'C/ Virgen de la Oliva, 7 (conserje)', NULL, '41011', 'Sevilla', NULL, NULL, 'Juan José Muñoz Acosta', NULL, 'jjmunoza@hotmail.com', NULL, '650918183', NULL, '', 1, NULL, NULL, 231, 'App\\Customer', 42, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(248, 'Dirección Principal', '', 'El Consentido', 'C/ Rodriguez de la Fuente, 18', NULL, '41310', 'Brenes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 1, NULL, NULL, 232, 'App\\Customer', 42, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(249, 'Dirección Principal', '', 'Maria Sanchez Gonzalez', 'C/ Ainielle nº 8 - 1º F', NULL, '22005', 'Huesca', NULL, NULL, 'Maria Sanchez Gonzalez', NULL, 'sangonm@hotmail.com', NULL, '652234638', NULL, '', 1, NULL, NULL, 233, 'App\\Customer', 25, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(250, 'Dirección Principal', '', 'Esperanza March Aguiló', 'Avda Ramón y Cajal nº 5 - 4º-2ª', NULL, '41005', 'Sevilla', NULL, NULL, 'Esperanza March Aguiló', NULL, 'seta300@hotmail.com', NULL, '675852499', NULL, '', 1, NULL, NULL, 234, 'App\\Customer', 42, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(251, 'Dirección Principal', '', 'La Nau Organic', 'C/ San Vicente Mártir, 363', NULL, '46017', NULL, NULL, NULL, 'Josep Bonet', NULL, 'jbonet@lanauorganic.com', '960063027', '679848623', NULL, '', 1, NULL, NULL, 235, 'App\\Customer', 47, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(252, 'Dirección Principal', '', 'L\'affineur de Fromage', 'C/ Costa del Sol nº 15 - 3º- 4º', NULL, '28033', NULL, NULL, NULL, 'Esther', NULL, 'affineurdefromage@affineurdefromage.com', '663810599', '659370307', NULL, '', 1, NULL, NULL, 236, 'App\\Customer', 32, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(253, 'Dirección Principal', '', 'DEZA', 'PI Las Quemada, parcela 116', NULL, '14014', NULL, NULL, NULL, 'frescos2@deza-sa.com de José Gom', NULL, 'mluisa@deza-sa.com', NULL, NULL, NULL, 'frescos2@deza-sa.com de José Gomez', 1, NULL, NULL, 237, 'App\\Customer', 18, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(254, 'Dirección Principal', '', 'Amparo de Luque Gonzalez', 'C/ Marques de Paradas, 21 local bajo', NULL, '41001', 'Sevilla', NULL, NULL, 'Amparo de Luque', NULL, 'laluquita@hotmail.com', NULL, '607946408', NULL, '', 1, NULL, NULL, 238, 'App\\Customer', 42, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(255, 'Dirección Principal', '', 'Latorre Simon', 'C/ Las Bombardas, 15', NULL, '4617', 'Palomares-Cuevas de Almanzora', NULL, NULL, 'Mª Dolores', NULL, 'reposteria@latorresimon.com', '950467523', '687748738', NULL, '', 1, NULL, NULL, 239, 'App\\Customer', 5, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(256, 'Dirección Principal', '', 'La Nau Organic', 'C/ Perello, 130 - Poligono Mas del Juez', NULL, '46909', 'Torrente ', NULL, NULL, NULL, NULL, NULL, NULL, '679848623', NULL, '', 1, NULL, NULL, 240, 'App\\Customer', 47, 1, '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(257, 'Dirección Principal', '', 'Hariberica', 'Ctra del Copero s/n -  Darsena del Cuarto ', NULL, '41080', 'Puerto de Sevilla', NULL, NULL, 'Antonio Dominguez', NULL, 'adominguez@haribericas.es', NULL, '667089911', NULL, '', 1, NULL, NULL, 241, 'App\\Customer', 42, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(258, 'Dirección Principal', '', 'La Ecotienda de Mila', 'C/ Covadonga, 27 Bajo', NULL, '33530', 'Infiesto ', NULL, NULL, 'Mila', NULL, 'milagros.amma@yahoo.es', NULL, '609735325', NULL, '', 1, NULL, NULL, 242, 'App\\Customer', 6, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(259, 'Dirección Principal', '', 'Sabores ', '13, allée Margarite Duras', NULL, '77420', 'Champs-sur-Marne', NULL, NULL, 'Mario Ramos Cueca', NULL, 'mario.ramos@sabores.fr', '33623442023', NULL, NULL, '', 1, NULL, NULL, 243, 'App\\Customer', 107, 4, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(260, 'Dirección Principal', '', 'La Tahona del Artesano', 'C/ San Onofre, 7 Local 2A', NULL, '11100', 'San Fernando', NULL, NULL, 'Mario Jiménez', NULL, 'latahonadelartesano@gmail.com', NULL, '674608742', NULL, '', 1, NULL, NULL, 244, 'App\\Customer', 14, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(261, 'Dirección Principal', '', 'Ecocenter Tarifa', 'C/ San Sebastián, 6', NULL, '11380', 'Tarifa', NULL, NULL, 'Johnny Ecocenter Taifa', NULL, 'tarifaecocenter@gmail.com', '956927456', NULL, NULL, '', 1, NULL, NULL, 245, 'App\\Customer', 14, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(262, 'Dirección Principal', '', 'Esencias', 'C/ Triana, 18 ', NULL, '21730', 'Almonte', NULL, NULL, NULL, NULL, 'tereypf@hotmail.com', NULL, '656 74 95 22', NULL, '', 1, NULL, NULL, 246, 'App\\Customer', 24, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(263, 'Dirección Principal', '', 'GRINFOOD', 'Carretera de Canillas, 24 esquina Canet de Mar', NULL, '28043', NULL, NULL, NULL, 'Charo Abadia', NULL, 'grinfood@gmail.com', '913884670', '628490667', NULL, '', 1, NULL, NULL, 247, 'App\\Customer', 32, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(264, 'Dirección Principal', '', 'La Biohteca Supermercado Montequinto', 'C/ Timanfaya, 19 ', NULL, '41701', 'Dos Hermanas', NULL, NULL, 'Mauricio J. López Madroñero', NULL, 'info@labiohteca.com', NULL, '635404451', NULL, '', 1, NULL, NULL, 248, 'App\\Customer', 42, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(265, 'Dirección Principal', '', 'Prodetur, SA', 'C/ Leonardo Da Vinci, 16', NULL, '41092', 'SEVILLA', NULL, NULL, 'Charo Gil', NULL, 'chgil@prodetur.es', '954486666', NULL, NULL, '', 1, NULL, NULL, 249, 'App\\Customer', 42, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(266, 'Dirección Principal', '', 'Casma Salud', 'C/ Abedul, 38', NULL, '28522', 'RIVAS VACIAMADRID', NULL, NULL, 'Pilar Caballero', NULL, 'pilarcaballero@casmasalud.com', '955672434', '646805596', NULL, '', 1, NULL, NULL, 250, 'App\\Customer', 32, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(267, 'Dirección Principal', '', 'Teresa Monreal Perez de Ciriza', 'Plaza de San Miguel nº 1 - 3º A', NULL, '31110', 'NOAIN', NULL, NULL, 'Teresa Monreal Perez de Ciriza', NULL, 'mtmonreal@gmail.com', NULL, '696137280', NULL, '', 1, NULL, NULL, 251, 'App\\Customer', 35, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(268, 'Dirección Principal', '', 'Aljareco', 'Avda. de San Juan, 26 Local 3', NULL, '41927', 'Mairena del Aljarafe', NULL, NULL, 'Alejandro Lourenço Míguez', NULL, 'aljareco@gmail.com', NULL, '644 225 537', NULL, '', 1, NULL, NULL, 253, 'App\\Customer', 42, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(269, 'Dirección Principal', '', 'Cucharas', 'C/ Baños, 34 Bajo', NULL, '41002', 'Sevilla', NULL, NULL, 'Santiago y Leonor', NULL, 'nora2005marzo@hotmail.com', '601064119', '655927329', NULL, '', 1, NULL, NULL, 254, 'App\\Customer', 42, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(270, 'Dirección Principal', '', 'Javi La Esencia', 'Avda Civilizaciones, 95 Portal 5 3ºB', NULL, '41927', 'Mairena del Aljarafe', NULL, NULL, 'Javi ', NULL, NULL, NULL, '665439722', NULL, '', 1, NULL, NULL, 255, 'App\\Customer', 42, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(271, 'Dirección Principal', '', 'FIBA Catering', 'Rua dos pazos, 119', NULL, '36360', 'Nigran', NULL, NULL, 'Victor o Marta', NULL, 'marta@pazodatouza.info', '656357606', '669548403', NULL, '', 1, NULL, NULL, 256, 'App\\Customer', 38, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(272, 'Dirección Principal', '', 'Las Comadres ', 'C/ León XIII, 61', NULL, '41009', 'Sevilla', NULL, NULL, 'Begoña y Vanesa', NULL, 'lascomadres@lascomadres.es', NULL, '676426708', NULL, '', 1, NULL, NULL, 257, 'App\\Customer', 42, 1, '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(273, 'Dirección Principal', '', 'LA DESPENSA DE IGERETXE', 'Las Mercedes,31 1º DPTO 5-6 ', NULL, '48930', 'Las Arenas', NULL, NULL, 'Cristina Hueda', NULL, 'chueda@gmail.com', NULL, '627476260', NULL, '', 1, NULL, NULL, 258, 'App\\Customer', 11, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(274, 'Dirección Principal', '', 'Herbolario Gran Plaza', 'Pasaje Comercial Gran Plaza, Letra T', NULL, '41006', 'Sevilla', NULL, NULL, 'Antonio Perez', NULL, 'herbolariogranplaza@hotmail.com', '693376686', '622269987', NULL, '', 1, NULL, NULL, 259, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(275, 'Dirección Principal', '', 'Arima Hotel', 'Avda Tolosa 21 - 5º ', NULL, '20018', 'Donostia', NULL, NULL, 'Conchi Andres', NULL, 'economato@sensosansebastian.com', '943566637', '639993706', NULL, '', 1, NULL, NULL, 260, 'App\\Customer', 20, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(276, 'Dirección Principal', '', 'Elena Segura Quijada', 'C/ Tabladilla nº 7 portal B - 8B', NULL, '41013', 'sevilla', NULL, NULL, 'Elena Segura', NULL, 'elena.segura@hotmail.com', NULL, '609814021', NULL, '', 1, NULL, NULL, 261, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(277, 'Dirección Principal', '', 'Herbobio', 'Avda de Jerez nº 55, local Esquina', NULL, '41014', 'Bellavista', NULL, NULL, NULL, NULL, 'tienda@elhebobiosaludable.es', '955317650', '640290526', NULL, '', 1, NULL, NULL, 262, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(278, 'Dirección Principal', '', 'Ayuntamiento de Brenes', 'C/ Real nº 21', NULL, '41310', 'Brenes', NULL, NULL, 'Rocio Gutierrez', NULL, 'cmimbrenes@gmail.com', '955655988', NULL, NULL, '', 1, NULL, NULL, 263, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(279, 'Dirección Principal', '115', 'Lola Mir', 'C/ Portillo, 9 ', NULL, '44564', 'MAS DE LAS MATAS', NULL, NULL, 'LOLA MIR', NULL, 'mdbayod@gmail.com', NULL, '659091267', NULL, '', 1, NULL, NULL, 50115, 'App\\Customer', 45, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(280, 'Dirección Principal', '139', 'GRUPO ATRES SCA', 'AVENIDA DIEGO MARTINEZ BARRIO, NUMERO 4 PLANTA 4 MODULO 8 ', NULL, '41013', 'SEVILLA', NULL, NULL, 'TERESA GOMEZ HIDALGO', NULL, 'tgomez@grupoatres.es', NULL, '695333006', NULL, '', 1, NULL, NULL, 50139, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(281, 'Dirección Principal', '141', 'Beatriz Cáceres', 'Avenida médicos sin fronteras 29 local 1 ', NULL, '41020', 'Sevilla', NULL, NULL, 'Beatriz Cáceres', NULL, 'beitxu_pitxu@hotmail.com', NULL, '666273793', NULL, '', 1, NULL, NULL, 50141, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(282, 'Dirección Principal', '142', 'Rica Esaguy', 'Anselmo Clavé 13-3-4', NULL, '8150', 'PARETS DEL VALLES', NULL, NULL, 'Rica Esaguy', NULL, 'robertyrica@gmail.com', NULL, '670224139', NULL, '', 1, NULL, NULL, 50142, 'App\\Customer', 10, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(283, 'Dirección Principal', '143', 'Lucía Ruiz', 'Calle Lionel Carvallo nº2 Piso 7ºA ', NULL, '41005', 'Sevilla', NULL, NULL, 'Lucía Ruiz', NULL, 'luciaruizbernal@gmail.com', NULL, '647297397', NULL, '', 1, NULL, NULL, 50143, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(284, 'Dirección Principal', '144', 'Yolanda Antolín Jiménez', 'C/ Andrés Carrasco Rebollo, nº 18 ', NULL, '41980', 'La Algaba', NULL, NULL, 'Yolanda Antolín Jiménez', NULL, 'yolandaantolin@andaluciajunta.es', NULL, '630419616', NULL, '', 1, NULL, NULL, 50144, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(285, 'Dirección Principal', '145', 'Begoña Baltar', 'C/Ernesto Feria, num 2, 2B ', NULL, '21500', 'Gibraleón', NULL, NULL, 'Begoña Baltar', NULL, 'begocalvi@gmail.com', NULL, '615693944', NULL, '', 1, NULL, NULL, 50145, 'App\\Customer', 24, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(286, 'Dirección Principal', '147', 'Cristina Ugas', 'Sol, 75, 2o 3a ', NULL, '8201', 'Sabadell', NULL, NULL, 'Cristina Ugas', NULL, 'crisugas@gmail.com', NULL, '609441267', NULL, '', 1, NULL, NULL, 50147, 'App\\Customer', 10, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(287, 'Dirección Principal', '149', 'Elisa Fernández Pareja', 'Cardenal bueno Monreal, 33, bl 3, 2c ', NULL, '41013', 'Sevilla', NULL, NULL, 'Elisa Fernández Pareja', NULL, 'elisafernandezpareja@gmail.com', NULL, '607653043', NULL, '', 1, NULL, NULL, 50149, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(288, 'Dirección Principal', '150', 'Jesús Bujalance Hoyos', 'C/ Camelia 75. Urb. Golf Resort Torrequebrada, Bq 9B. 2ºB ', NULL, '29630', 'Benalmádena', NULL, NULL, 'Jesús Bujalance Hoyos', NULL, 'jesbh@yahoo.es', NULL, '691139172', NULL, '', 1, NULL, NULL, 50150, 'App\\Customer', 33, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(289, 'Dirección Principal', '151', 'Wonkandy, SL', 'C/ Camino de Mozarabes, Naves 2 y 4 PI Parque Plata', NULL, '41900', 'Camas', NULL, NULL, 'Fátima Moreno', NULL, '1974fpm@gmail.com', NULL, '676661509', NULL, '', 1, NULL, NULL, 50151, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(290, 'Dirección Principal', '152', 'Macarena Palma Martinez', 'Felipe checa 36 local Estudio de pilates', NULL, '6001', 'Badajoz', NULL, NULL, 'Macarena Palma martinez', NULL, 'macapalmar@hotmail.com', NULL, '609451534', NULL, '', 1, NULL, NULL, 50152, 'App\\Customer', 8, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(291, 'Dirección Principal', '153', 'Rafael Lora del Castillo', 'Calle José Díaz Velázquez,40 Bloque 5 Bajo D', NULL, '41980', 'La Algaba', NULL, NULL, 'Rafael Lora del Castillo', NULL, 'rafa921130@gmail.com', NULL, '664010147', NULL, '', 1, NULL, NULL, 50153, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(292, 'Dirección Principal', '155', 'Rocío Delgado Sánchez', 'C/El Pino de Sta. Clara, Casa 1. 3º2. Urb. Hábitat 71 ', NULL, '41007', 'Sevilla', NULL, NULL, 'Rocío Delgado Sánchez', NULL, 'r.delgado.sanchez@gmail.com', NULL, '652302954', NULL, '', 1, NULL, NULL, 50155, 'App\\Customer', 42, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(293, 'Dirección Principal', '156', 'Jamones Ecologicos de Jabugo,S.L.', 'Jamones Ecologicos Nave Corami Poligono el Pontón ', NULL, '21230', 'Cortegana', NULL, NULL, 'Eduardo Carlos Donato Florensa', NULL, 'dehesamaladua@hotmail.com', NULL, '959503228', NULL, '', 1, NULL, NULL, 50156, 'App\\Customer', 24, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(294, 'Dirección Principal', '157', 'María Teresa González Moreno', 'Avenida de la paz n°8 ', NULL, '14700', 'Palma del rio', NULL, NULL, 'María Teresa González Moreno', NULL, 'mariateresagonzalezmoreno225@gmail.com', NULL, '678828426', NULL, '', 1, NULL, NULL, 50157, 'App\\Customer', 18, 1, '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(295, 'Dirección Principal', '158', 'Guillermina Jiménez Noguera', 'C/ Reino Unido 9 ', NULL, '41309', 'La Rinconada', NULL, NULL, 'GUILLERMINA Jiménez Noguera', NULL, 'willmi.j@hotmail.com', NULL, '652956288', NULL, '', 1, NULL, NULL, 50158, 'App\\Customer', 42, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(296, 'Dirección Principal', '159', 'Manuela Lucena Campos', 'Calle Cádiz Salvatierra, portal número 5, 2B ', NULL, '21003', 'Huelva', NULL, NULL, 'Manuela Lucena Campos', NULL, 'manoli_lucena@hotmail.com', NULL, '649413888', NULL, '', 1, NULL, NULL, 50159, 'App\\Customer', 24, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(297, 'Dirección Principal', '160', 'Teresa Monreal Pérez de Ciriza', 'Plaza San Miguel, nº 1 - 3º A ', NULL, '31110', 'Noáin', NULL, NULL, 'Teresa Monreal Pérez de Ciriza', NULL, 'mtmonreal@gmail.com', NULL, '696137280', NULL, '', 1, NULL, NULL, 50160, 'App\\Customer', 35, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(298, 'Dirección Principal', '161', 'Raquel Jarque', 'Guadaira,5 ', NULL, '41018', 'Sevilla', NULL, NULL, 'Raquel Jarque', NULL, 'raqueljarpaz@hotmail.com', NULL, '677497562', NULL, '', 1, NULL, NULL, 50161, 'App\\Customer', 42, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(299, 'Dirección Principal', '162', 'Pilar Rodríguez Rodríguez', 'Calle Cueva de Menga n.3, bloque 14. 3º C. ', NULL, '41020', 'Sevilla', NULL, NULL, 'Pilar Rodríguez Rodríguez', NULL, 'pilitarr@hotmail.com', NULL, '616111145', NULL, '', 1, NULL, NULL, 50162, 'App\\Customer', 42, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(300, 'Dirección Principal', '163', 'M Rosa Zamora Cobo', 'C/ Juan XXIII N. 8 8', NULL, '41130', 'La Puebla del Río', NULL, NULL, 'M Rosa Zamora Cobo', NULL, 'shorbydos@hotmail.com', NULL, '637353533', NULL, '', 1, NULL, NULL, 50163, 'App\\Customer', 42, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(301, 'Dirección Principal', '164', 'Pilar Moreno molina', 'Joan Pfaff, 21-B, bajos ', NULL, '8830', 'Sant Boi de Llobregat', NULL, NULL, 'Pilar Moreno molina', NULL, 'p.moreno1@hotmail.com', NULL, '665328565', NULL, '', 1, NULL, NULL, 50164, 'App\\Customer', 10, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(302, 'Dirección Principal', '165', 'Pilar Rodríguez Rodríguez', 'Calle Cueva de Menga n.3, bloque 14. 3º C. ', NULL, '41020', 'Sevilla', NULL, NULL, 'Pilar Rodríguez Rodríguez', NULL, 'pilarrguezrguez@gmail.com', NULL, '616111145', NULL, '', 1, NULL, NULL, 50165, 'App\\Customer', 42, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(303, 'Dirección Principal', '166', 'David Fernández Fernández', 'Avenida chamberi 16 bajo ', NULL, '33860', 'Salas', NULL, NULL, 'David Fernández Fernández', NULL, 'david_mamina@hotmail.com', NULL, '637893165', NULL, '', 1, NULL, NULL, 50166, 'App\\Customer', 6, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(304, 'Dirección Principal', '167', 'Francisco Vargas Fernández', 'Ctra.de Almeria, 25-Edif. Trebol bajo, B (Peluquería) ', NULL, '29793', 'El Morche', NULL, NULL, 'Francisco Vargas Fernández', NULL, 'walnito@hotmail.com', NULL, '639838370', NULL, '', 1, NULL, NULL, 50167, 'App\\Customer', 33, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(305, 'Dirección Principal', '168', 'Esmeralda Noguero Tarriño', 'Nuestra señora de botoa, 11, 4°-B ', NULL, '6007', 'Badajoz', NULL, NULL, 'Esmeralda Noguero Tarriño', NULL, 'esmeralda5-7@hotmail.com', NULL, '637316304', NULL, '', 1, NULL, NULL, 50168, 'App\\Customer', 8, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(306, 'Dirección Principal', '169', 'Raquel Paz', 'Guadaira 4 ', NULL, '41018', 'Sevilla', NULL, NULL, 'Raquel Paz', NULL, 'hugoldcj@gmail.com', NULL, '646171222', NULL, '', 1, NULL, NULL, 50169, 'App\\Customer', 42, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(307, 'Dirección Principal', '170', 'reyes hellin', 'calle rivero 3 ', NULL, '41004', 'SEVILLA', NULL, NULL, 'REYES HELLIN aguilar', NULL, 'reyes@reyeshellin.es', NULL, '630043838', NULL, '', 1, NULL, NULL, 50170, 'App\\Customer', 42, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(308, 'Dirección Principal', '171', 'Nexe The way of change', 'Rambla de Catalunya, 111, 3° 1a ', NULL, '08008', 'Barcelona', NULL, NULL, 'Silvia Dilate Fernández', NULL, 'sdonate@nexe.com', NULL, '652969136', NULL, '', 1, NULL, NULL, 50171, 'App\\Customer', 10, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(309, 'Dirección Principal', '172', 'Maria Jimenez negrette', 'Cartagena, 23 Farmacia La Atunara', NULL, '11300', 'La Linea de la concepcion', NULL, NULL, 'Maria Jimenez negrette', NULL, 'mariajnegrette@gmail.com', NULL, '667512520', NULL, '', 1, NULL, NULL, 50172, 'App\\Customer', 14, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(310, 'Dirección Principal', '174', 'CARMEN RODRIGUEZ GUTIERREZ', 'CALLE VIRGEN DE LA OLIVA S/N OFICINAS CENTRALES LIPASAM', NULL, '41011', 'SEVILLA', NULL, NULL, 'CARMEN RODRIGUEZ GUTIERREZ', NULL, 'rodriguezc@lipasam.es', NULL, '678944262', NULL, '', 1, NULL, NULL, 50174, 'App\\Customer', 42, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(311, 'Dirección Principal', '175', 'Manuel Ferrazzano Jiménez', 'Salvador Dali 11 3A', NULL, '41008', 'Sevilla', NULL, NULL, 'Manuel Ferrazzano Jiménez', NULL, 'manuelferrazzano@gmail.com', NULL, '627522484', NULL, '', 1, NULL, NULL, 50175, 'App\\Customer', 42, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(312, 'Dirección Principal', '176', 'Gloria Ortiz', 'Miguel Angel Martin 2 Casa urb Hato verde', NULL, '41219', 'Las Pajanosas', NULL, NULL, 'Gloria Ortiz Mejias', NULL, 'gloria.ortiz@es.nestle.com', NULL, '609752936', NULL, '', 1, NULL, NULL, 50176, 'App\\Customer', 42, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(313, 'Dirección Principal', '177', 'CARMEN LLAMAS GARCIA', 'C/HORNO CABELLO N.6 BAJO B LUCENA', NULL, '14900', 'LUCENA', NULL, NULL, 'CARMEN LLAMAS GARCIA', NULL, 'mcarmen260478@gmail.com', NULL, '627956900', NULL, '', 1, NULL, NULL, 50177, 'App\\Customer', 18, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(314, 'Dirección Principal', '178', 'Susanna Vidal Garcia', 'Esports, 1 1er 2ona ', NULL, '08960', 'Sant Just Desvern', NULL, NULL, 'Susanna Vidal Garcia', NULL, 'sus_vid@hotmail.com', NULL, '667798279', NULL, '', 1, NULL, NULL, 50178, 'App\\Customer', 10, 1, '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iban` varchar(34) COLLATE utf8mb4_unicode_ci NOT NULL,
  `swift` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mandate_reference` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mandate_date` date DEFAULT NULL,
  `first_recurring_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `bank_accountable_id` int(10) UNSIGNED NOT NULL,
  `bank_accounable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `b_o_m_items`
--

CREATE TABLE `b_o_m_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_bom_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(20,6) NOT NULL DEFAULT '1.000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `b_o_m_items`
--

INSERT INTO `b_o_m_items` (`id`, `product_id`, `product_bom_id`, `quantity`, `created_at`, `updated_at`) VALUES
(2, 1, 2, '24.000000', '2018-02-24 12:11:24', '2018-02-24 12:11:24'),
(3, 8, 2, '1.000000', '2018-03-05 17:37:01', '2018-03-05 17:37:01'),
(4, 12, 2, '1.000000', '2018-03-05 20:55:40', '2018-03-05 20:55:40'),
(5, 15, 2, '2.000000', '2018-03-07 10:44:38', '2018-03-07 10:44:38'),
(6, 6, 2, '12.000000', '2018-03-07 10:45:08', '2018-03-07 10:45:08'),
(7, 5, 8, '1.000000', '2018-03-07 13:03:42', '2018-03-07 13:03:42'),
(8, 7, 9, '1.000000', '2018-03-07 13:06:38', '2018-03-07 13:06:38'),
(10, 13, 10, '1.000000', '2018-03-07 17:20:13', '2018-03-07 17:20:13'),
(11, 19, 11, '1.000000', '2018-03-07 17:21:06', '2018-03-07 17:21:06'),
(12, 20, 2, '1.000000', '2018-04-09 09:54:37', '2018-04-09 09:54:37'),
(15, 21, 2, '1.000000', '2018-05-29 09:34:09', '2018-05-29 09:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `carriers`
--

CREATE TABLE `carriers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int(10) UNSIGNED NOT NULL,
  `publish_to_web` tinyint(4) NOT NULL DEFAULT '0',
  `webshop_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_root` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `position`, `publish_to_web`, `webshop_id`, `is_root`, `active`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Panes Integrales', 0, 0, NULL, 0, 1, 0, '2018-05-26 16:45:44', '2018-05-26 16:45:44'),
(2, 'Panes Integrales con Semillas', 0, 0, NULL, 0, 1, 1, '2018-05-26 16:46:14', '2018-05-26 16:46:14'),
(3, 'Panes Integrales Básicos', 0, 0, NULL, 0, 1, 1, '2018-05-26 16:46:36', '2018-05-26 16:46:36'),
(4, 'Bollería', 0, 0, NULL, 0, 1, 0, '2018-05-26 16:59:01', '2018-05-26 16:59:01');

-- --------------------------------------------------------

--
-- Table structure for table `combinations`
--

CREATE TABLE `combinations` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ean13` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `measure_unit` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_decimal_places` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `quantity_onhand` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_onorder` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_allocated` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_onorder_mfg` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_allocated_mfg` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `reorder_point` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `maximum_stock` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `last_purchase_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cost_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cost_average` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `supplier_reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supply_lead_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `location` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` decimal(20,6) DEFAULT '0.000000',
  `height` decimal(20,6) DEFAULT '0.000000',
  `depth` decimal(20,6) DEFAULT '0.000000',
  `weight` decimal(20,6) DEFAULT '0.000000',
  `warranty_period` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `publish_to_web` tinyint(4) NOT NULL DEFAULT '0',
  `blocked` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `is_default` tinyint(4) NOT NULL DEFAULT '0',
  `product_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `combination_option`
--

CREATE TABLE `combination_option` (
  `id` int(10) UNSIGNED NOT NULL,
  `combination_id` int(10) UNSIGNED NOT NULL,
  `option_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `combination_warehouse`
--

CREATE TABLE `combination_warehouse` (
  `id` int(10) UNSIGNED NOT NULL,
  `combination_id` int(10) UNSIGNED NOT NULL,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name_fiscal` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_commercial` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identification` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apply_RE` tinyint(4) NOT NULL DEFAULT '0',
  `website` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_logo` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name_fiscal`, `name_commercial`, `identification`, `apply_RE`, `website`, `company_logo`, `notes`, `currency_id`, `created_at`, `updated_at`) VALUES
(2, 'LA EXTRANATURAL S.L.', 'LA EXTRANATURAL', 'B90096728', 0, 'www.laextranatural.es', '1529152621.png', NULL, 15, '2017-09-13 07:05:36', '2018-06-16 12:37:01');

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

CREATE TABLE `configurations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configurations`
--

INSERT INTO `configurations` (`id`, `name`, `description`, `value`, `created_at`, `updated_at`) VALUES
(1, 'SW_VERSION', NULL, '0.2.2', '2015-03-31 09:12:55', '2017-07-18 09:58:20'),
(3, 'MARGIN_METHOD', '\'CST\' => Sales Margin is related to Product Cost Price,  // Markup Percentage = (Sales Price – Unit Cost)/Unit Cost X 100\r\n\'PRC\' => Sales Margin is related to Product Sales Price,  // Gross Margin Percentage = (Gross Profit/Sales Price) X 100\r\nGross Profit = Sales Price – Unit Cost', 'PRC', '2015-03-31 09:12:55', '2018-06-19 11:10:41'),
(4, 'ALLOW_SALES_WITHOUT_STOCK', NULL, '0', '2015-03-31 09:12:55', '2015-05-19 15:14:47'),
(5, 'ALLOW_SALES_RISK_EXCEEDED', NULL, '0', '2015-03-31 09:12:55', '2015-05-19 15:14:47'),
(6, 'DEF_LANGUAGE', NULL, '2', '2015-03-31 09:12:55', '2017-07-18 09:48:49'),
(7, 'DEF_COUNTRY', '', '1', '2015-03-31 09:12:55', '2017-08-16 17:05:13'),
(8, 'DEF_CUSTOMER_INVOICE_SEQUENCE', NULL, '2', '2015-03-31 09:12:55', '2017-10-09 13:19:52'),
(9, 'DEF_CUSTOMER_INVOICE_TEMPLATE', NULL, '1', '2015-03-31 09:12:55', '2017-10-30 09:16:03'),
(10, 'DEF_CUSTOMER_PAYMENT_METHOD', NULL, '1', '2015-03-31 09:12:55', '2017-10-10 10:35:14'),
(11, 'DEF_OUTSTANDING_AMOUNT', NULL, '3000.0', '2015-03-31 09:12:55', '2015-03-31 09:12:55'),
(12, 'DEF_CARRIER', NULL, '0', '2015-03-31 09:12:55', '2015-03-31 09:12:55'),
(13, 'DEF_WAREHOUSE', NULL, '1', '2015-03-31 09:12:55', '2018-05-28 09:54:07'),
(14, 'DEF_ITEMS_PERPAGE', NULL, '8', '2015-03-31 09:12:55', '2018-03-06 10:42:42'),
(15, 'DEF_ITEMS_PERAJAX', 'Number of items (maximum) returned by an ajax request', '10', '2015-03-31 09:12:55', '2018-02-28 10:07:31'),
(16, 'TIMEZONE', NULL, 'Europe/Madrid', '2015-04-01 20:00:00', '2015-04-01 20:00:00'),
(17, 'DEF_COMPANY', NULL, '2', '2015-04-03 05:54:13', '2017-10-19 10:58:40'),
(18, 'DEF_PERCENT_DECIMALS', NULL, '2', '2015-04-18 15:58:22', '2015-04-18 16:01:54'),
(19, 'DEF_CURRENCY', NULL, '15', '2015-05-08 10:03:37', '2017-09-22 14:29:16'),
(21, 'SUPPORT_CENTER_EMAIL', NULL, 'dcomobject@hotmail.com', '2015-07-09 08:13:38', '2017-09-28 08:28:47'),
(22, 'SUPPORT_CENTER_NAME', NULL, 'aBillander Support Center', '2015-07-09 08:14:11', '2015-07-09 08:14:11'),
(24, 'ENABLE_COMBINATIONS', NULL, '1', '2017-07-18 19:02:52', '2017-07-18 19:04:17'),
(25, 'ENABLE_WAREHOUSE', NULL, '1', '2017-07-20 08:37:48', '2017-07-20 08:37:48'),
(26, 'QUOTES_EXPIRE_AFTER', NULL, '15', '2017-07-25 15:38:02', '2017-07-25 15:38:02'),
(27, 'SW_DATABASE_VERSION', NULL, '0.2.2', '2017-07-26 05:35:19', '2017-07-26 05:35:19'),
(29, 'ENABLE_WEBSHOP_CONNECTOR', NULL, '1', '2017-07-26 10:30:47', '2017-10-16 10:01:02'),
(30, 'ALLOW_PRODUCT_SUBCATEGORIES', '=1 : Permite subcategorías. Los productos se asocian entonces a subcategorías.\r\n=0 : Los productos se asocian a categorías.\r\nNOTA: las subcategorías son "Categorías-hijas"', '1', '2017-08-19 07:37:57', '2018-06-19 11:10:41'),
(31, 'HEADER_TITLE', 'Texto en la barra de menús, a la izquierda', '<span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> LXVII</span>', '2017-08-31 05:00:28', '2017-10-16 10:02:15'),
(32, 'DEF_QUANTITY_DECIMALS', 'Default decimal positions for quantities (stock, etc.)', '2', '2017-09-10 10:19:49', '2017-09-10 10:19:49'),
(33, 'DEF_MEASURE_UNIT_FOR_PRODUCTS', 'Default measure unit for products and combinations', '1', '2017-09-12 08:53:07', '2018-05-27 16:37:48'),
(34, 'DEF_WEIGHT_UNIT', 'The default weight unit for your shop (e.g."kg" for kilograms, "lbs" for pound-mass, etc.).', 'kg', '2017-09-12 09:05:10', '2017-09-12 09:05:10'),
(35, 'DEF_DISTANCE_UNIT', 'The default distance unit for your shop (e.g. "km" for kilometer, "mi" for mile, etc.).', 'km', '2017-09-12 09:06:34', '2017-09-12 09:06:34'),
(36, 'DEF_VOLUME_UNIT', 'The default volume unit for your shop (e.g. "L" for liter, "gal" for gallon, etc.).', 'cl', '2017-09-12 09:07:39', '2017-09-12 09:07:39'),
(37, 'DEF_DIMENSION_UNIT', 'The default dimension unit for your shop (e.g. "cm" for centimeter, "in" for inch, etc.).', 'cm', '2017-09-12 09:08:26', '2017-09-12 09:08:26'),
(39, 'DEF_TAX', 'Default Tax for Products', '1', '2017-09-16 19:31:45', '2017-09-16 19:31:45'),
(41, 'STOCK_COUNT_IN_PROGRESS', 'Stock count is in progress. Routes / Controlleres that modify stock are voided.', '0', '2017-09-29 16:36:03', '2017-09-29 16:36:03'),
(42, 'PRICES_ENTERED_WITH_TAX', '1.- I will enter prices inclusive of tax\r\n0.- I will enter prices exclusive of tax\r\nChanging this option will not update existing products', '0', '2017-10-15 09:02:16', '2018-06-25 11:02:37'),
(43, 'ROUND_PRICES_WITH_TAX', '1.- \r\na: Round price inclusive of tax b: Calculate and round price exclusive of tax c: Tax is the difference (no round needed)\r\n0.- \r\na: Round price exclusive of tax b: Calculate and round price inclusive of tax c: Tax is the difference (no round needed)', '1', '2017-10-15 09:10:24', '2017-10-27 09:49:23'),
(44, 'SKU_PREFIX_OFFSET', 'This value will be added to the Product ID to obtain an unique SKU', '100', '2017-11-11 14:47:07', '2017-11-11 15:37:38'),
(45, 'SKU_PREFIX_LENGTH', 'Product ID will be (zero padded) this length (minimum) as SKU prefix.', '4', '2017-11-11 14:48:34', '2017-11-11 17:08:27'),
(46, 'SKU_SUFFIX_LENGTH', 'Combination ID will be (zero padded) this length (minimum) as SKU suffix.', '1', '2017-11-11 14:49:50', '2017-11-11 17:07:06'),
(47, 'SKU_SEPARATOR', 'This will be placed between SKU Prefix and SKU Suffix', '-', '2017-11-11 14:52:01', '2017-11-11 14:52:01'),
(48, 'SKU_AUTOGENERATE', 'Auto-generate a SKU if none is given', '0', '2017-11-11 15:40:40', '2018-06-20 08:46:50'),
(49, 'WOOC_DEF_CURRENCY', 'WooCommerce Currency ID (within aBillander)', '15', '2017-12-02 13:55:24', '2017-12-02 13:55:24'),
(51, 'WOOC_DEF_LANGUAGE', 'WooCommerce Language ID (within aBillander)', '2', '2017-12-08 13:46:13', '2017-12-08 18:02:53'),
(52, 'WOOC_DEF_INVOICES_SEQUENCE', 'Invoiced WooCommerce Orders will go to this Sequence', '2', '2017-12-10 09:25:23', '2018-04-30 14:32:25'),
(53, 'WOOC_SAVE_INVOICE_AS_DRAFT', 'Invoices after WooCommerce Orders will be saved with status \'draft\' (1) or \'pending\' (0)', '1', '2017-12-10 09:30:04', '2017-12-10 09:31:59'),
(54, 'WOOC_USE_LOCAL_PRODUCT_NAME', 'Use local Product & Combination names in documents, instead of WooCommerce Shop names', '1', '2017-12-10 12:19:41', '2018-07-03 19:40:42'),
(55, 'WOOC_DEF_TAX', 'Default Tax for not found products', '1', '2017-12-10 13:05:16', '2017-12-10 13:05:16'),
(56, 'WOOC_TAXES_CACHE', NULL, '[{"slug":"standard","name":"Tarifa est\\u00e1ndar","_links":{"collection":[{"href":"https:\\/\\/www.laextranatural.com\\/wp-json\\/wc\\/v2\\/taxes\\/classes"}]}},{"slug":"4-con-re","name":"4% con RE","_links":{"collection":[{"href":"https:\\/\\/www.laextranatural.com\\/wp-json\\/wc\\/v2\\/taxes\\/classes"}]}},{"slug":"10-con-re","name":"10% con RE","_links":{"collection":[{"href":"https:\\/\\/www.laextranatural.com\\/wp-json\\/wc\\/v2\\/taxes\\/classes"}]}},{"slug":"iva-normal-con-re","name":"IVA Normal con RE","_links":{"collection":[{"href":"https:\\/\\/www.laextranatural.com\\/wp-json\\/wc\\/v2\\/taxes\\/classes"}]}},{"slug":"10","name":"10%","_links":{"collection":[{"href":"https:\\/\\/www.laextranatural.com\\/wp-json\\/wc\\/v2\\/taxes\\/classes"}]}},{"slug":"4","name":"4%","_links":{"collection":[{"href":"https:\\/\\/www.laextranatural.com\\/wp-json\\/wc\\/v2\\/taxes\\/classes"}]}}]', '2017-12-11 12:37:15', '2018-07-02 11:18:24'),
(57, 'WOOC_TAX_STANDARD', NULL, '1', '2017-12-11 12:57:57', '2017-12-11 12:57:57'),
(58, 'WOOC_TAX_REDUCED-RATE', NULL, '2', '2017-12-11 12:57:57', '2017-12-11 12:57:57'),
(59, 'WOOC_TAX_R-E', NULL, '2', '2017-12-11 12:57:57', '2017-12-11 12:57:57'),
(60, 'WOOC_TAXES_DICTIONARY_CACHE', NULL, '{"standard":"1","reduced-rate":"2","r-e":"2"}', '2017-12-11 13:44:38', '2017-12-11 13:44:38'),
(61, 'TAX_BASED_ON_SHIPPING_ADDRESS', 'Tax calculation based on: 1.- delivery address (default) 0.- invoice address', '0', '2017-12-12 10:16:03', '2017-12-12 10:18:47'),
(62, 'WOOC_DEF_SHIPPING_TAX', 'Default Tax for shipping expenses. It is a WooCommerce Store Setting.', '1', '2017-12-12 14:01:51', '2017-12-12 14:41:10'),
(63, 'WOOC_DECIMAL_PLACES', 'Number of decimal places WooCommerce works with. It is a WooCommerce Store Setting.', '2', '2017-12-12 14:40:41', '2018-07-03 19:40:04'),
(64, 'WOOC_PAYMENT_GATEWAYS_CACHE', NULL, '[{"id":"redsys","title":"Tarjeta de cr\\u00e9dito\\/d\\u00e9bito","description":"Paga con tu tarjeta de cr\\u00e9dito o de d\\u00e9bito","order":0,"enabled":true,"method_title":"Pago con Tarjeta (REDSYS)","method_description":"Esta es la opci\\u00f3n de la pasarela de pago de Redsys.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/redsys"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}},{"id":"iupay","title":"IUPAY","description":"Paga con Iupay","order":1,"enabled":true,"method_title":"Pago con Tarjeta (IUPAY)","method_description":"Esta es la opci\\u00f3n de la pasarela de pago de Iupay.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/iupay"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}},{"id":"paypal","title":"PayPal","description":"Pagar con PayPal; puedes pagar con tu tarjeta de cr\\u00e9dito si no tienes una cuenta de PayPal.","order":2,"enabled":true,"method_title":"PayPal","method_description":"PayPal est\\u00e1ndar funciona enviando al usuario a PayPal para que introduzca su informaci\\u00f3n de pago. La IPN de PayPal IPN requiere compatibilidad con fsockopen\\/cURL para actualizar el estado del pedido despu\\u00e9s del pago. Revisa la p\\u00e1gina del <a href=\\"http:\\/\\/localhost\\/wooc\\/wp-admin\\/admin.php?page=wc-status\\">estado del sistema<\\/a> para m\\u00e1s detalles.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/paypal"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}},{"id":"bacs","title":"Transferencia bancaria","description":"Realiza tu pago directamente en nuestra cuenta bancaria. Por favor usa la referencia del pedido como referencia de pago. El pedido no ser\\u00e1 enviado hasta que el importe completo haya sido recibido en nuestra cuenta.","order":3,"enabled":false,"method_title":"Transferencia bancaria","method_description":"Permite el pago mediante transferencia bancaria.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/bacs"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}},{"id":"cheque","title":"Giro a 30 d\\u00edas","description":"El importe facturado se cargar\\u00e1 en su cuenta bancaria 30 d\\u00edas despu\\u00e9s de la fecha de factura. Solo disponible para clientes que hayan firmado la ORDEN DE DOMICILIACI\\u00d3N SEPA.","order":4,"enabled":true,"method_title":"Pagos por cheque","method_description":"Permite el pago por cheque. \\u00bfPor qu\\u00e9 aceptar cheques a estas alturas? Probablemente no deber\\u00edas pero te permite hacer compras de prueba para probar los correos electr\\u00f3nicos de pedido, las p\\u00e1ginas de \'conseguido\', etc.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/cheque"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}},{"id":"cod","title":"Contra reembolso","description":"Pagar en efectivo al momento de la entrega.","order":5,"enabled":false,"method_title":"Contra reembolso","method_description":"Permite que sus clientes paguen en efectivo (o por otros medios) cuando se entrega el producto.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/cod"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}}]', '2017-12-13 09:40:42', '2018-04-30 15:34:07'),
(65, 'WOOC_PAYMENT_GATEWAY_REDSYS', NULL, '1', '2017-12-13 10:21:29', '2017-12-13 10:21:29'),
(66, 'WOOC_PAYMENT_GATEWAY_IUPAY', NULL, '1', '2017-12-13 10:21:29', '2017-12-13 10:21:29'),
(67, 'WOOC_PAYMENT_GATEWAY_PAYPAL', NULL, '1', '2017-12-13 10:21:29', '2017-12-13 10:21:29'),
(68, 'WOOC_PAYMENT_GATEWAY_BACS', NULL, '2', '2017-12-13 10:21:29', '2017-12-13 10:21:29'),
(69, 'WOOC_PAYMENT_GATEWAY_CHEQUE', NULL, '2', '2017-12-13 10:21:29', '2017-12-13 10:21:29'),
(70, 'WOOC_PAYMENT_GATEWAY_COD', NULL, '2', '2017-12-13 10:21:30', '2017-12-13 10:21:30'),
(71, 'WOOC_PAYMENT_GATEWAYS_DICTIONARY_CACHE', NULL, '{"redsys":"1","iupay":"1","paypal":"1","bacs":"2","cheque":"2","cod":"2"}', '2017-12-13 10:21:30', '2017-12-13 10:21:30'),
(72, 'WOOC_CONFIGURATIONS_CACHE', NULL, '[{"id":"woocommerce_default_country","description":"Esto es d\\u00f3nde est\\u00e1 situado tu negocio. Los impuestos estar\\u00e1n basados en este pa\\u00eds.","value":"ES:SE"},{"id":"woocommerce_allowed_countries","description":"Con esta opci\\u00f3n puedes elegir a qu\\u00e9 pa\\u00edses quieres vender.","value":"specific"},{"id":"woocommerce_all_except_countries","description":"","value":[]},{"id":"woocommerce_specific_allowed_countries","description":"","value":["ES"]},{"id":"woocommerce_ship_to_countries","description":"Elige a qu\\u00e9 pa\\u00edses quieres enviar, o elige enviar a todos los lugares que vendes.","value":""},{"id":"woocommerce_specific_ship_to_countries","description":"","value":[]},{"id":"woocommerce_default_customer_address","description":"","value":"geolocation"},{"id":"woocommerce_calc_taxes","description":"Activa los impuestos y los c\\u00e1lculos de impuestos","value":"yes"},{"id":"woocommerce_demo_store","description":"Activa un aviso de texto en toda la tienda","value":"no"},{"id":"woocommerce_demo_store_notice","description":"","value":"This is a demo store for testing purposes &mdash; no orders shall be fulfilled."},{"id":"woocommerce_currency","description":"Esto controla en qu\\u00e9 moneda se listan los precios en el cat\\u00e1logo y en qu\\u00e9 moneda se realizar\\u00e1n los pagos a trav\\u00e9s de las opciones de pago.","value":"EUR"},{"id":"woocommerce_currency_pos","description":"Esto controla la posici\\u00f3n del s\\u00edmbolo de moneda.","value":"right"},{"id":"woocommerce_price_thousand_sep","description":"Esto establece el separador de miles de los precios mostrados.","value":"."},{"id":"woocommerce_price_decimal_sep","description":"Esto establece el separador decimal de los precios mostrados.","value":","},{"id":"woocommerce_price_num_decimals","description":"Esto establece el n\\u00famero de decimales que se muestran en los precios mostrados.","value":"2"},{"id":"woocommerce_weight_unit","description":"Esto controla en qu\\u00e9 unidad definir\\u00e1s los pesos.","value":"kg"},{"id":"woocommerce_dimension_unit","description":"Esto controla en qu\\u00e9 unidad definir\\u00e1s las longitudes.","value":"cm"},{"id":"woocommerce_enable_reviews","description":"Activar valoraciones de producto","value":"yes"},{"id":"woocommerce_review_rating_verification_label","description":"Mostrar la etiqueta \\"propietario verificado\\" en las valoraciones de los clientes","value":"yes"},{"id":"woocommerce_review_rating_verification_required","description":"Las rese\\u00f1as solo las pueden dejar \\"propietarios verificados\\"","value":"no"},{"id":"woocommerce_enable_review_rating","description":"Activar valoraciones con estrellas en las rese\\u00f1as","value":"yes"},{"id":"woocommerce_review_rating_required","description":"Las valoraciones con estrellas deber\\u00e1n ser obligatorias, no opcionales","value":"yes"},{"id":"woocommerce_shop_page_display","description":"Esto controla lo que se muestra en el archivo de productos.","value":""},{"id":"woocommerce_category_archive_display","description":"Esto controla lo que se muestra en Archivo de la categor\\u00eda.","value":""},{"id":"woocommerce_default_catalog_orderby","description":"Esto controla el orden de clasificaci\\u00f3n por defecto del cat\\u00e1logo.","value":"menu_order"},{"id":"woocommerce_cart_redirect_after_add","description":"Redirigir a la p\\u00e1gina del carrito tras a\\u00f1adir productos con \\u00e9xito","value":"no"},{"id":"woocommerce_enable_ajax_add_to_cart","description":"Activar botones AJAX de a\\u00f1adir al carrito en los archivos","value":"yes"},{"id":"shop_catalog_image_size","description":"Este tama\\u00f1o se utiliza generalmente en los listados de productos. (W x H)","value":{"width":247,"height":300,"crop":false}},{"id":"shop_single_image_size","description":"Este es el tama\\u00f1o utilizado en la imagen principal de la p\\u00e1gina del producto. (W x H)","value":{"width":600,"height":902,"crop":false}},{"id":"shop_thumbnail_image_size","description":"Este tama\\u00f1o se utiliza generalmente para la galer\\u00eda de im\\u00e1genes en la p\\u00e1gina del producto. (W x H)","value":{"width":114,"height":130,"crop":false}},{"id":"woocommerce_manage_stock","description":"Activar la gesti\\u00f3n de inventario","value":"no"},{"id":"woocommerce_hold_stock_minutes","description":"Mantener el inventario (para pedidos pendientes de pago) durante x minutos. Cuando se alcance este l\\u00edmite se cancelar\\u00e1 el pedido pendiente. D\\u00e9jalo en blanco para desactivarlo.","value":"60"},{"id":"woocommerce_notify_low_stock","description":"Activar avisos de pocas existencias","value":"no"},{"id":"woocommerce_notify_no_stock","description":"Activar avisos de inventario agotado","value":"no"},{"id":"woocommerce_stock_email_recipient","description":"Introduce destinatarios (separados por coma) que recibir\\u00e1n este aviso.","value":"lidiamartinez@laextranatural.es"},{"id":"woocommerce_notify_low_stock_amount","description":"Cuando el inventario del producto alcance esta cantidad ser\\u00e1s notificado por correo electr\\u00f3nico.","value":"2"},{"id":"woocommerce_notify_no_stock_amount","description":"Cuando el inventario del producto alcanza esta cantidad el estado del inventario cambiar\\u00e1 a \\"sin existencias\\" y recibir\\u00e1s un aviso por correo electr\\u00f3nico. Este ajuste no afecta a los productos \\"con existencias\\".","value":"0"},{"id":"woocommerce_hide_out_of_stock_items","description":"Ocultar en el cat\\u00e1logo los art\\u00edculos agotados","value":"no"},{"id":"woocommerce_stock_format","description":"Esto controla c\\u00f3mo se muestran las cantidades del inventario en la tienda.","value":"no_amount"},{"id":"woocommerce_file_download_method","description":"Forzar descargas mantendr\\u00e1 ocultas las URLs, pero puede que algunos servidores sirvan archivos grandes de manera poco segura. Si es compatible, puedes usar en su lugar <code>X-Accel-Redirect<\\/code> \\/ <code>X-Sendfile<\\/code> para servir descargas (el servidor requiere <code>mod_xsendfile<\\/code> ).","value":"force"},{"id":"woocommerce_downloads_require_login","description":"Las descargas requieren inicio de sesi\\u00f3n","value":"no"},{"id":"woocommerce_downloads_grant_access_after_payment","description":"Permitir acceso a los productos descargables despu\\u00e9s del pago","value":"yes"},{"id":"woocommerce_prices_include_tax","description":"","value":"no"},{"id":"woocommerce_tax_based_on","description":"","value":"shipping"},{"id":"woocommerce_shipping_tax_class","description":"Control opcional para la tasa de impuesto por env\\u00edo, o d\\u00e9jalo tal cual si el impuesto por env\\u00edo est\\u00e1 basado en los productos del carrito.","value":""},{"id":"woocommerce_tax_round_at_subtotal","description":"Redondeo de impuesto en el subtotal, en lugar de redondeo por cada l\\u00ednea","value":"no"},{"id":"woocommerce_tax_classes","description":"","value":"4% con RE\\r\\n10% con RE\\r\\nIVA Normal con RE\\r\\n10%\\r\\n4%"},{"id":"woocommerce_tax_display_shop","description":"","value":"incl"},{"id":"woocommerce_tax_display_cart","description":"","value":"incl"},{"id":"woocommerce_price_display_suffix","description":"","value":""},{"id":"woocommerce_tax_total_display","description":"","value":"itemized"},{"id":"woocommerce_enable_shipping_calc","description":"Activar la calculadora de env\\u00edos en la p\\u00e1gina de compra","value":"no"},{"id":"woocommerce_shipping_cost_requires_address","description":"Ocultar los gastos de env\\u00edo hasta que se introduzca una direcci\\u00f3n","value":"yes"},{"id":"woocommerce_ship_to_destination","description":"Esto controla qu\\u00e9 direcci\\u00f3n de env\\u00edo se usa por defecto.","value":"shipping"},{"id":"woocommerce_shipping_debug_mode","description":"Activar el modo de depuraci\\u00f3n","value":"no"},{"id":"woocommerce_enable_coupons","description":"Activa el uso de cupones","value":"yes"},{"id":"woocommerce_calc_discounts_sequentially","description":"Calcular descuentos de cupones secuencialmente","value":"no"},{"id":"woocommerce_enable_guest_checkout","description":"Permitir finalizar compra como invitado","value":"no"},{"id":"woocommerce_force_ssl_checkout","description":"Forzar el pago seguro","value":"no"},{"id":"woocommerce_checkout_pay_endpoint","description":"Variable de la p\\u00e1gina de \\"Finalizar compra &rarr; Pagar\\".","value":"order-pay"},{"id":"woocommerce_checkout_order_received_endpoint","description":"Variable de la p\\u00e1gina \\"Finalizar compra &rarr; Pedido recibido\\".","value":"order-received"},{"id":"woocommerce_myaccount_add_payment_method_endpoint","description":"Variable de la p\\u00e1gina \\"Finalizar compra &rarr; A\\u00f1adir m\\u00e9todo de pago\\".","value":"add-payment-method"},{"id":"woocommerce_myaccount_delete_payment_method_endpoint","description":"Variable para la p\\u00e1gina de eliminar m\\u00e9todo de pago","value":"delete-payment-method"},{"id":"woocommerce_myaccount_set_default_payment_method_endpoint","description":"Variable para establecer una p\\u00e1gina de m\\u00e9todo de pago por defecto","value":"set-default-payment-method"},{"id":"woocommerce_enable_signup_and_login_from_checkout","description":"Permitir el alta como cliente en la p\\u00e1gina \\"Finalizar compra\\"","value":"yes"},{"id":"woocommerce_enable_myaccount_registration","description":"Permitir el alta como cliente en la p\\u00e1gina \\"Mi cuenta\\"","value":"no"},{"id":"woocommerce_enable_checkout_login_reminder","description":"Muestra un recordatorio de acceso al cliente ya registrado en la p\\u00e1gina de \\"Finalizar compra\\"","value":"yes"},{"id":"woocommerce_registration_generate_username","description":"Genera autom\\u00e1ticamente el nombre de usuario a partir del correo electr\\u00f3nico del cliente","value":"yes"},{"id":"woocommerce_registration_generate_password","description":"Genera autom\\u00e1ticamente la contrase\\u00f1a del cliente","value":"yes"},{"id":"woocommerce_myaccount_orders_endpoint","description":"Variable para la p\\u00e1gina \\"Mi cuenta &rarr; Pedidos\\".","value":"orders"},{"id":"woocommerce_myaccount_view_order_endpoint","description":"Variable de la p\\u00e1gina \\"Mi cuenta &rarr; Ver pedido\\".","value":"view-order"},{"id":"woocommerce_myaccount_downloads_endpoint","description":"Variable para la p\\u00e1gina \\"Mi cuenta &rarr; Descargas\\".","value":"downloads"},{"id":"woocommerce_myaccount_edit_account_endpoint","description":"Variable de la p\\u00e1gina \\"Mi cuenta &rarr; Editar cuenta\\".","value":"edit-account"},{"id":"woocommerce_myaccount_edit_address_endpoint","description":"Variable para la p\\u00e1gina \\"Mi cuenta &rarr; Direcciones\\".","value":"edit-address"},{"id":"woocommerce_myaccount_payment_methods_endpoint","description":"Variable para la p\\u00e1gina \\"Mi cuenta &rarr; M\\u00e9todos de pago\\".","value":"payment-methods"},{"id":"woocommerce_myaccount_lost_password_endpoint","description":"Variable de la p\\u00e1gina \\"Mi cuenta &rarr; Contrase\\u00f1a perdida\\".","value":"lost-password"},{"id":"woocommerce_logout_endpoint","description":"Variable para forzar la desconexi\\u00f3n. Puedes a\\u00f1adir esto a tus men\\u00fas con un enlace personalizado: tusitio.com\\/?customer-logout=true","value":"customer-logout"},{"id":"woocommerce_email_from_name","description":"C\\u00f3mo aparece el nombre del remitente en los correos electr\\u00f3nicos salientes de WooCommerce.","value":"La Extranatural"},{"id":"woocommerce_email_from_address","description":"C\\u00f3mo aparece la direcci\\u00f3n de correo electr\\u00f3nico del remitente en los correos salientes de WooCommerce.","value":"laextranatural@laextranatural.es"},{"id":"woocommerce_email_header_image","description":"URL de la imagen que quieres mostrar en la cabecera del correo electr\\u00f3nico. Sube las im\\u00e1genes utilizando la subida de multimedia (Administrador > Multimedia).","value":""},{"id":"woocommerce_email_footer_text","description":"El texto que aparece en el pie de p\\u00e1gina de mensajes de correo electr\\u00f3nico WooCommerce.","value":"La Extranatural"},{"id":"woocommerce_email_base_color","description":"El color base para las plantillas de correo electr\\u00f3nico de WooCommerce. Por defecto <code>#96588a<\\/code>.","value":"#557da1"},{"id":"woocommerce_email_background_color","description":"El color de fondo para las plantillas de correo electr\\u00f3nico de WooCommerce. Por defecto <code>#f7f7f7<\\/code>.","value":"#f5f5f5"},{"id":"woocommerce_email_body_background_color","description":"El color principal del fondo de la p\\u00e1gina. Por defecto <code>#ffffff<\\/code>.","value":"#fdfdfd"},{"id":"woocommerce_email_text_color","description":"El color principal del texto de la p\\u00e1gina. Por defecto <code>#3c3c3c<\\/code>.","value":"#505050"},{"id":"woocommerce_api_enabled","description":"Activa la API REST","value":"yes"},{"id":"enabled","description":"","value":"yes"},{"id":"recipient","description":"Introduce los destinatarios (separados por comas) de este correo electr\\u00f3nico. Por defecto es <code>laextranatural@laextranatural.es<\\/code>.","value":"laextranatural@laextranatural.com, laextranatural@laextranatural.es"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"[{site_title}] Nuevo pedido ({order_number}) - {order_date}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Nuevo pedido de un cliente"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"recipient","description":"Introduce los destinatarios (separados por comas) de este correo electr\\u00f3nico. Por defecto es <code>laextranatural@laextranatural.es<\\/code>.","value":"laextranatural@laextranatural.com, laextranatural@laextranatural.es"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"[{site_title}] - Cancelada la orden ({order_number})"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Pedido cancelado"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"recipient","description":"Introduce los destinatarios (separados por comas) de este correo electr\\u00f3nico. Por defecto es <code>laextranatural@laextranatural.es<\\/code>.","value":"laextranatural@laextranatural.com, laextranatural@laextranatural.es"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"[{site_title}] Pedido fallido ({order_number})"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Pedido fallido"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":""},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":""},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Recibo de tu pedido en {site_title} del {order_date}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Gracias por tu pedido"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Se ha completado tu pedido en {site_title} del {order_date}."},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"El pedido se ha completado"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject_full","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Tu pedido de {site_title} desde {order_date} ha sido reembolsado"},{"id":"subject_partial","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Tu pedido de {site_title} desde {order_date} ha sido parcialmente reembolsado"},{"id":"heading_full","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Tu pedido ha sido totalmente devuelto"},{"id":"heading_partial","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Tu pedido ha sido devuelto parcialmente"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Factura del pedido # {order_number} de {order_date}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Recibo del pedido {order_number}"},{"id":"subject_paid","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Tu pedido en {site_title} del {order_date}"},{"id":"heading_paid","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Informaci\\u00f3n del pedido {order_number} "},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Nota a\\u00f1adida a tu pedido en {site_title} del {order_date}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Se ha a\\u00f1adido una nota a tu pedido"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Restablecer contrase\\u00f1a en {site_title}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Instrucciones para reestablecer la contrase\\u00f1a"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Tu cuenta en {site_title}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Bienvenido a {site_title}"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"}]', '2017-12-16 14:01:58', '2018-02-28 00:04:33'),
(74, 'DEF_MEASURE_UNIT_FOR_BOMS', 'Default measure unit for BOMs', '1', '2018-02-24 16:50:01', '2018-06-19 17:25:25'),
(75, 'WOOC_ORDERS_PER_PAGE', 'Los pedidos de WooCommerce se recuperan en esta cantidad por página', '2', '2018-02-28 10:11:18', '2018-07-01 17:07:40'),
(76, 'CUSTOMER_ORDERS_NEED_VALIDATION', '1.- Customer Orders will be created with status = \'draft\'\r\n0.- Customer Orders will be created with status = \'confirmed\'', '0', '2018-04-16 16:55:27', '2018-04-16 16:55:27'),
(77, 'WOOC_DEF_ORDERS_SEQUENCE', 'Sequence for Customer Orders imported from WooCommerce', '3', '2018-04-30 14:31:37', '2018-04-30 14:31:37'),
(78, 'USE_CUSTOM_THEME', 'Custom theme lives in folder /resources/theme/.', '1', '2018-05-05 09:53:56', '2018-07-01 09:33:09'),
(79, 'NEW_PRODUCT_TO_ALL_PRICELISTS', '1: New Product is registered in all Price Lists. Price is calculated according to Price List type.\r\n\r\n0: New Products should be added manually to Price Lists.', '1', '2018-05-27 16:25:06', '2018-05-27 16:25:06'),
(80, 'PRODUCT_NOT_IN_PRICELIST', 'block - disallow sales.\r\npricelist - calculate price according to Price list type.\r\nproduct - take price from Product data', 'block', '2018-05-27 16:37:10', '2018-06-20 16:57:55'),
(81, 'NEW_PRICE_LIST_POPULATE', '1: When a Price List is created, all Products are added. Price is calculated according to Price List type. 0: Products should be added manually to Price Lists.', '0', '2018-06-10 08:44:38', '2018-06-10 08:44:38'),
(82, 'WOOC_DEF_CUSTOMER_GROUP', 'Imported Customers will be asigned to this Group', '1', '2018-07-01 17:38:05', '2018-07-03 19:48:57'),
(83, 'WOOC_DEF_CUSTOMER_PRICE_LIST', 'Imported Customers will be asigned this Price List', '0', '2018-07-01 19:25:42', '2018-07-03 19:40:42'),
(84, 'WOOC_ORDER_NIF_META', 'Order Meta field name to store Spanish NIF/CIF/NIE.', '', '2018-07-02 10:22:49', '2018-07-03 19:40:42'),
(85, 'DEF_LOGS_PERPAGE', NULL, '1000', '2018-07-02 16:48:35', '2018-07-02 16:48:35');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contains_states` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `iso_code`, `contains_states`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(0, 'Sin asignar', '', 1, 1, NULL, NULL, NULL),
(1, 'España', 'ES', 1, 1, '2015-04-01 16:40:22', '2017-09-20 16:40:22', NULL),
(2, 'Estados Unidos', 'US', 1, 1, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(4, 'Francia', 'FR', 1, 1, '2018-07-03 15:00:14', '2018-07-03 15:43:17', NULL),
(5, 'Alemania', 'DE', 1, 1, '2018-07-03 15:45:00', '2018-07-03 15:45:13', NULL),
(6, 'Emiratos Arabes Unidos', 'AE', 1, 1, '2018-07-03 15:46:01', '2018-07-03 15:46:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_code_num` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sign` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signPlacement` tinyint(4) NOT NULL DEFAULT '1',
  `thousandsSeparator` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '.',
  `decimalSeparator` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ',',
  `decimalPlaces` tinyint(4) NOT NULL DEFAULT '2',
  `blank` tinyint(4) NOT NULL DEFAULT '0',
  `conversion_rate` decimal(20,6) NOT NULL DEFAULT '1.000000',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `iso_code`, `iso_code_num`, `sign`, `signPlacement`, `thousandsSeparator`, `decimalSeparator`, `decimalPlaces`, `blank`, `conversion_rate`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(15, 'Euro', 'EUR', '978', '€', 1, '.', ',', 2, 0, '1.000000', 1, '2017-09-22 14:29:16', '2017-09-22 14:29:16', NULL),
(16, 'Dollar', 'USD', '840', '$', 0, ',', '.', 2, 0, '1.220000', 1, '2017-09-22 14:29:16', '2017-09-22 14:29:16', NULL),
(17, 'Pound Sterling', 'GBP', '826', '£', 0, ',', '.', 2, 0, '0.880000', 1, '2017-09-22 14:29:16', '2017-09-22 14:29:16', NULL),
(18, 'Yen', 'JPY', '392', '¥', 0, ',', '', 0, 0, '130.000000', 1, '2017-09-22 14:29:16', '2017-09-22 14:29:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currency_conversion_rates`
--

CREATE TABLE `currency_conversion_rates` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `conversion_rate` decimal(20,6) NOT NULL DEFAULT '1.000000',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name_fiscal` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_commercial` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identification` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `webshop_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_external` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accounting_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_days` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_payment_month` tinyint(4) NOT NULL DEFAULT '0',
  `outstanding_amount_allowed` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `outstanding_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unresolved_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `customer_logo` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_equalization` tinyint(4) NOT NULL DEFAULT '0',
  `allow_login` tinyint(4) NOT NULL DEFAULT '0',
  `accept_einvoice` tinyint(4) NOT NULL DEFAULT '0',
  `blocked` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `sales_rep_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `language_id` int(10) UNSIGNED NOT NULL,
  `customer_group_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_method_id` int(10) UNSIGNED DEFAULT NULL,
  `invoice_template_id` int(10) UNSIGNED DEFAULT NULL,
  `carrier_id` int(10) UNSIGNED DEFAULT NULL,
  `price_list_id` int(10) UNSIGNED DEFAULT NULL,
  `direct_debit_account_id` int(10) UNSIGNED DEFAULT NULL,
  `invoicing_address_id` int(10) UNSIGNED NOT NULL,
  `shipping_address_id` int(10) UNSIGNED DEFAULT NULL,
  `secure_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `import_key` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `company_id`, `user_id`, `name_fiscal`, `name_commercial`, `website`, `identification`, `webshop_id`, `reference_external`, `accounting_id`, `payment_days`, `no_payment_month`, `outstanding_amount_allowed`, `outstanding_amount`, `unresolved_amount`, `notes`, `customer_logo`, `sales_equalization`, `allow_login`, `accept_einvoice`, `blocked`, `active`, `sales_rep_id`, `currency_id`, `language_id`, `customer_group_id`, `payment_method_id`, `invoice_template_id`, `carrier_id`, `price_list_id`, `direct_debit_account_id`, `invoicing_address_id`, `shipping_address_id`, `secure_key`, `import_key`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, 0, 'Semilleria Herboristeria Gaia El Tornillo, SL', 'Gaia', NULL, 'B41576893', '', '1', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 23, 23, 'c2fe986286e8f3d49ae7bf2535d23593', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(2, 0, 0, 'Maria José Fernandez Gonzalez', 'Panacea', NULL, '28719727H', '', '2', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 0, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 24, 24, '2471c7e436eabbde8eaa2bc31a19e6be', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(3, 0, 0, 'Montserrat Castro Rodriguez', 'Ecologico y local', NULL, '28953709K', '', '3', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 1, 1, NULL, 25, 25, 'cdd1dc6889754c7b48e0cb4ea0492028', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(4, 0, 0, 'Ana Isabel Fernandez Alvarez', 'Ana Relator', NULL, '09008695D', '', '4', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 26, 26, '4175efba79e4ec68cacba02e7fc59578', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(5, 0, 0, 'Ecoortiga, SCA', 'La Ortiga', NULL, 'F91166314', '', '5', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 27, 27, '33ddc364ab6eaeabeb67e4add0d71530', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(6, 0, 0, 'Panificadora PADIPAN, SL', 'Padipan', NULL, 'B41211608', '', '6', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 28, 28, 'cc8ac4bed5257f9436cf3e316fdf42c6', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(7, 0, 0, 'Luis Sanchez Trujillo', 'La Espiga Luis', NULL, '00661982L', '', '7', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 29, 29, '336018ae2737b5e67efbb480d108eca0', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(8, 0, 0, 'La Alacena de la Nena, SL', 'La Alacena de la Nena', NULL, NULL, '', '8', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 30, 30, '6fd87375469913dc6e8366a72671188d', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(9, 0, 0, 'Rafael Jose Vera Ruiz', 'Mr Cake', NULL, '27311914N', '', '9', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 31, 31, '0fce7bee539d4ed9c09c8cd27d43ab76', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(10, 0, 0, 'Teresa Mª Quesada Maireles', 'Mas sano que una pera', NULL, '28780270W', '', '10', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 32, 32, '0e30396f9a391ace3a0807ec5eb7c1b0', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(11, 0, 0, 'Sonia Gallardo Trujillo', 'Sonia Gallardo Trujillo', NULL, 'X00000000', '', '11', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 33, 33, '7d2a900d7193054c603884d676093251', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(12, 0, 0, 'Zarabanda, SL', 'Zarabanda', NULL, 'B90189051', '', '12', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 34, 34, '99b5d983feab7ada3226c0f2c001f585', '', '2018-07-04 10:41:14', '2018-07-04 10:41:14', NULL),
(13, 0, 0, 'Sara Robles Rodriguez', 'El Arbol', NULL, '52698673S', '', '13', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 35, 35, '7c1220a9b804bc78b48fa771fe8cdba6', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(14, 0, 0, 'Mª Cruz Irrebertegui', 'Mª Cruz Irrebertegui', NULL, 'X00000000', '', '14', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 36, 36, '1117cc5d7ce0ba06964bdca4a142e206', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(15, 0, 0, 'Mª Angeles Jimenez Lamela', 'A falta de pan', NULL, '25570303F', '', '15', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 37, 37, '9cb1f18e11b2924043314b467c26ffae', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(16, 0, 0, 'FRESCUM ON LINE, SL', 'Frescum', NULL, 'B14948004', '', '16', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 38, 38, 'b2ff7b636d4b7a8a5f63a0ce2aac4275', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(17, 0, 0, 'Asoc Mercado Local La Rendija', 'La Rendija', NULL, 'G90030792', '', '17', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 39, 39, '54eff9902082f043eb78044dfe8652f7', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(18, 0, 0, 'Martina Kovanova', 'Milk away', NULL, 'Y174732W', '', '18', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 40, 40, '1053f98546ba40eb5b724014e0dcfdfb', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(19, 0, 0, 'SOLO ECOLOGICO, SL', 'Organic 49', NULL, 'B25686379', '', '19', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 41, 41, '1ae8f37078ff86381ad418c2014ce5bd', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(20, 0, 0, 'Emilio Rojo', 'Bodegas Rojo', NULL, 'B05202077', '', '20', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 42, 42, '8e1913726d398d18ac262295f2efe47c', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(21, 0, 0, 'Silvia Lander Erdozain', 'Silvia Lander Erdozain', NULL, 'X0000000', '', '21', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 43, 43, '2e450444efc4b27a7b8e69c28742b77e', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(22, 0, 0, 'Isabel Alvarez Trurrillo', 'Mercadosin', NULL, '31690142Y', '', '22', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 44, 44, 'bf56c0874df220c794c8930af1bb4904', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(23, 0, 0, 'Panificadora Santa Verenia, SL', 'Panificadora Santa Verenia', NULL, 'B41084294', '', '23', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 1, NULL, 1, 3, NULL, 45, 45, '18dbc8dc711dafa1b58f16f7dfda2552', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(24, 0, 0, 'Panaderia Artesana La Andalusi, SL', 'La Andalusi', NULL, 'B91620971', '', '24', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 1, 2, NULL, 46, 46, '4cf9fe25ef71916bc8a8fa2760aa88b4', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(25, 0, 0, 'Bioalcores, SL', 'Dieta Ecologica', NULL, 'B90225483', '', '25', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 1, 2, NULL, 47, 47, 'fbc629024f2aa4a6712c57bd9dba5267', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(26, 0, 0, 'Salade Lorenzo y Silva, SL', 'Mas que lechugas', NULL, 'B90095258', '', '26', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 1, 1, NULL, 48, 48, 'ba9c502972aebd4db6275bc316542982', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(27, 0, 0, 'Concepcion Ponti Galindo', 'Red Verde', NULL, '30225473T', '', '27', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 49, 49, '5eb2f95e85a4662729f47c2e5b2e275c', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(28, 0, 0, 'Fargobio, SL', 'Restaurante Fargo', NULL, 'B90114430', '', '28', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 50, 50, '69100b158c27303aa4b725462b809b35', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(29, 0, 0, 'Silvia Ledesma Molina', 'La Despensa Ecologica', NULL, '45654426D', '', '29', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 51, 51, 'ae95596a41637a92102fe71f18ab853f', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(30, 0, 0, 'Eslaveco On-Line, SL', 'Eslaveco ', NULL, 'B90089475', '', '30', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 1, 1, NULL, 52, 52, '36e285412d402a5ac23d6e2feeca482b', '', '2018-07-04 10:41:15', '2018-07-04 10:41:15', NULL),
(31, 0, 0, 'Francisco Jimenez Masero', 'A x pan', NULL, '28468835X', '', '31', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 53, 53, 'd43b8056285f77e33fee42f297653597', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(32, 0, 0, 'Inver. y Gestión Turística de Toledo AIE       ESTA EMPRESA YA NO EXISTE. NUEVA: 93 VIÑEDOS CIGARRAL', 'NO EXISTE', NULL, 'V45613684', '', '32', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 1, 0, NULL, 15, 2, 1, 1, NULL, NULL, 1, NULL, 54, 54, '6bbc4df96d9d0f45505cc2b55e2ee43c', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(33, 0, 0, 'Dolores Pablos Martin', 'Torres Pablos', NULL, '28703539E', '', '33', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 0, NULL, 15, 2, 1, 1, NULL, 1, 5, NULL, 55, 55, '2ebfce8324078553bd9a6411b2781b31', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(34, 0, 0, 'Maria Escribano', 'Food in Company', NULL, 'X0000000', '', '34', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 1, NULL, 3, 3, NULL, 56, 56, 'c098e1fb3f1e9d76271fbbb73095b380', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(35, 0, 0, 'Rocio Verde Sanchez', 'Verdy', NULL, '28916129T', '', '35', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 57, 57, '02e443bd15104e22637db53f3c12f8c6', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(36, 0, 0, 'Santiago Jimenez Torres', 'La Despensa Sana Villaverde', NULL, '28716663J', '', '36', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 0, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 58, 58, 'ee182e1f9ac03d748139b98a73411db4', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(38, 0, 0, 'ANA LOPEZ LOPEZ', 'ANA', NULL, NULL, '', '38', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 59, 59, 'f716685d50459033720eb7b8ee60c7fa', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(39, 0, 0, 'rosalino daza del barco', 'rosalino', NULL, NULL, '', '39', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 1, NULL, 1, 3, NULL, 60, 60, '15d43d5748e2234db8657b16249c4bf3', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(40, 0, 0, 'María Auxiliadora Biedma Martín', 'María Auxiliadora', NULL, NULL, '', '40', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 61, 61, '2f956e75359179f0e2e43805957890a1', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(41, 0, 0, 'ISABEL ALER', 'ISABEL', NULL, NULL, '', '41', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 62, 62, '58cffff973580512c8ff245cae9e930c', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(42, 0, 0, 'Yolanda Mantero Portela', 'Yolanda', NULL, NULL, '', '42', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 63, 63, '510efb71c95d5dd46a8ad8f4ec5c54af', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(43, 0, 0, 'Myriam Eady', 'Myriam', NULL, NULL, '', '43', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 64, 64, 'd3352ad1beac5b78a45031c130e00e79', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(44, 0, 0, 'Silvia Oroz Oset', 'Silvia', NULL, NULL, '', '44', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 65, 65, 'fc28feca2c07a263ba3d172eb339f0c9', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(45, 0, 0, 'Aurora Pedroso Rosas', 'Aurora', NULL, NULL, '', '45', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 66, 66, '9e56a78c52bff3aeb14b71ca752427f8', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(46, 0, 0, 'monserrat Alvarez sanchez', 'monserrat', NULL, NULL, '', '46', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 67, 67, '84e979481447c5b5515e1b18a665fc6e', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(47, 0, 0, 'Beni Sanchez cordero', 'Beni', NULL, NULL, '', '47', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 68, 68, 'e2b2bdd6aaead37ddd9671e7e1917047', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(48, 0, 0, 'GLORIA CALZADO GUTIERREZ', 'GLORIA', NULL, NULL, '', '48', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 69, 69, '25d3b7a5d11ef2cc67a2d9c215bec58e', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(49, 0, 0, 'PALOMA RODRIGUEZ LOPEZ', 'PALOMA', NULL, NULL, '', '49', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 70, 70, '42ec4f457872611903310854e3bf4387', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(50, 0, 0, 'José miguel Martín lópez', 'José miguel', NULL, NULL, '', '50', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 71, 71, 'c1a0717dfd2384a73523a50c8d6f9618', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(51, 0, 0, 'Montse Sahuquillo', 'Montse', NULL, NULL, '', '51', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 72, 72, '5c8c78363118f0a14e019d3fa1744d53', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(52, 0, 0, 'ALICIA CASAS DE LA BANDERA', 'ALICIA', NULL, NULL, '', '52', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 73, 73, '793973355f59fc1bdc5e67251eae2b35', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(53, 0, 0, 'Carlos Mora', 'Carlos', NULL, NULL, '', '53', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 74, 74, '38f5866fc287e7d7cd6fd34dceef52af', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(54, 0, 0, 'monserrat alvarez sanchez', 'monserrat', NULL, NULL, '', '54', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 75, 75, 'd6a9dbb1c4c8852f1a701a1c64066207', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(55, 0, 0, 'Ana maria Suero gonzalez', 'Ana maria', NULL, NULL, '', '55', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 76, 76, 'd8eb9ed98a08355e5bbf4536a6fcff67', '', '2018-07-04 10:41:16', '2018-07-04 10:41:16', NULL),
(56, 0, 0, 'ELENA RODRIGUEZ LEON', 'ELENA', NULL, NULL, '', '56', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 77, 77, '5f435ecbec7394c602df6cb5a130e44e', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(57, 0, 0, 'Irune Zubicaray', 'Irune', NULL, NULL, '', '57', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 78, 78, '94d79f1aca8465abbf0991bbb68d4371', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(58, 0, 0, 'Aurora Pedroso Rosas', 'Aurora', NULL, NULL, '', '58', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 79, 79, 'f6ac1b2b9ee227e152e24ee5eade40bb', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(59, 0, 0, 'Ana Maria Moya Gonzalez', 'Ana Maria', NULL, NULL, '', '59', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 1, 3, NULL, 80, 80, '5987d4ed48b3585921d47053cac205ff', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(60, 0, 0, 'Luna Fernandez', 'Luna', NULL, NULL, '', '60', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 81, 81, '3a496e85dead7fc0257d9343e22decf6', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(61, 0, 0, 'M. Carmen Perez Gómez', 'M. Carmen', NULL, NULL, '', '61', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 82, 82, '53fc88a79e47e3fc5ac6a4b2191bdd77', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(62, 0, 0, 'EVA NEVADO MECINA', 'EVA', NULL, NULL, '', '62', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 83, 83, '2608ecdd5035455c0ae2262279668068', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(63, 0, 0, 'ALICIA CASAS DE LA BANDERA', 'ALICIA', NULL, NULL, '', '63', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 84, 84, '3b354210cf9cd383026519f81a35c3da', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(64, 0, 0, 'María Isabel Sánchez Vernalte', 'Biomarket Olivar', NULL, '50759220Z', '', '64', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 6, NULL, 3, 5, NULL, 85, 85, '461bb60cde7fd3883f5a16a248d194dd', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(65, 0, 0, 'Francisco Ruiz Salguero', 'Paco Artesa', NULL, '31680700V', '', '65', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 6, NULL, 3, 1, NULL, 86, 86, 'c33c551d2cccdaddc31beaebc9a76e93', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(67, 0, 0, 'Carmen Quijada', 'Carmen', NULL, NULL, '', '67', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 6, NULL, 3, 1, NULL, 87, 87, '796e3b3e52bd808201880aafc6f6fc7b', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(68, 0, 0, 'EVA BONILLA BOZA', 'EVA', NULL, NULL, '', '68', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 88, 88, 'a42fad2e5d7b4132fd81c0646b96fc7a', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(69, 0, 0, 'monserrat alvarez sanchez', 'monserrat', NULL, NULL, '', '69', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 89, 89, '46846cf42fcefc8931bc64dd667b2808', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(70, 0, 0, 'Alejandro Blanco ', 'Alejandro Blanco A3 Asesores', NULL, NULL, '', '70', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 1, 3, NULL, 90, 90, '0169571a54c601fbb5803f65ce1e7f44', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(71, 0, 0, 'Almocafre SC And de Consumo Ecologico', 'Almocafre ', NULL, 'F14546329', '', '71', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 91, 91, '5d7dd1676552ceef6759e8bee65f6c17', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(72, 0, 0, 'Ana M Franconetti Martinez', 'El puesto ecológico', NULL, '52295729P', '', '72', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 92, 92, 'b8ea6eb242bf5168c809c04a1fb198f6', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(73, 0, 0, 'Alvaro Miguel Fernandez-Blanco Barreto', 'Cerro Viejo', NULL, '48963416G', '', '73', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 0, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 93, 93, '1145d119274b3bc59ba82a432ce77157', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(74, 0, 0, 'Birgit Auschner', 'Das Brot', NULL, 'X2431491T', '', '74', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 94, 94, '9ca46506d3aff89c46c7de3958aeb467', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(75, 0, 0, 'Biotienda, SL', 'Biotienda', NULL, NULL, '', '75', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 95, 95, 'ce61520d20526eb8ec1fe7c6215fcac0', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(76, 0, 0, 'Distribuciones Eman e Hijos, SL', 'Eman e Hijos, SL', NULL, 'B38549143', '', '76', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 2, 1, NULL, 96, 96, 'dd5aae2b6ab2722b8ccff0fb12b3d346', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(77, 0, 0, 'Ma José Martinez Arjona', 'Ma José Martinez Arjona', NULL, '77536707G', '', '77', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 97, 97, '36daef1789c283b32400e2f4bd30fe1d', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(78, 0, 0, 'Sohiscert', 'Sohiscert', NULL, 'A82070269', '', '78', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 98, 98, 'd6c02dc7fbc37f8a5b4d05d780e84587', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(79, 0, 0, 'Cortijo Vistalegre, SL', 'Cortijo Vistalegre', NULL, 'B90221599', '', '79', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 99, 99, '8fb6ba4379c12bbfefbcc58073c59a82', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(80, 0, 0, 'El Rincón de Gretel, SL', 'Cafeteria Peregrino', NULL, 'B71153118', '', '80', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 100, 100, '1b4cdef0da50ade7b8d84a0d74ad966d', '', '2018-07-04 10:41:17', '2018-07-04 10:41:17', NULL),
(81, 0, 0, 'GRUPOCEROYNAESLOMISMO, SL', 'Tiendas ná!', NULL, 'B91988881', '', '81', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 101, 101, '72c3657eac77dfcdb53bbd57e0586586', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(82, 0, 0, 'Juani Moyano Cantón', 'El Gato de Molino', NULL, '45658112F', '', '82', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 102, 102, '4294ccdd3d3645dddb3d3e162f093911', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(83, 0, 0, 'Antonio Higueras Navarro', 'Antonio', NULL, NULL, '', '83', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 103, 103, 'beedd78bd132b0174d242ec92adb3fb2', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(84, 0, 0, 'Miguel Ángel Moreno Vela', 'Miguel Ángel', NULL, NULL, '', '84', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 104, 104, 'bed60296d40116d396b5e42599a1e690', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(85, 0, 0, 'EVA BONILLA', 'EVA', NULL, NULL, '', '85', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 105, 105, '85db5e65257bb871a90f2a08e7a2427d', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(86, 0, 0, 'Lidia Martinez', 'Lidia', NULL, NULL, '', '86', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 1, 3, NULL, 106, 106, '6c59c2314246d3903df9812992a165ae', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(87, 0, 0, 'Giulietta Sacco', 'Nueva Era', NULL, 'X2017457N', '', '87', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 1, 1, NULL, 107, 107, '56d656c6b0e457891930f9501fe908a8', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(88, 0, 0, 'Estraperlo SCA', 'Estraperlo', NULL, 'F90286972', '', '88', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 1, 1, NULL, 108, 108, '1d767e774c2a57b48db81338d97c5f93', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(89, 0, 0, 'Ana Garcia Florido', 'Mamafante y Papaposa', NULL, '28497428Z', '', '89', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 109, 109, 'd802b3c3d4a2d839596f1f87e1101d1c', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(90, 0, 0, 'Postres y Lácteos Mare Nostrum', 'Mare Nostrum', NULL, 'B91285114', '', '90', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', 'Eugenia Madrid o Maria. Contabilidad Lola Montaño', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 1, 1, NULL, 110, 110, 'e84719d623f827abae98a12381535fdb', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(91, 0, 0, 'Ana Mª Dominguez Silva', 'Ana Mª Dominguez Silva', NULL, 'X0000001', '', '91', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 111, 111, '5c1930e3d09b74cff84dabfe31026019', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(92, 0, 0, 'Maria Santamaria Nieto', 'Maria Santamaria Nieto', NULL, '47202044H', '', '92', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 112, 112, 'e2527d694d53394278228dafba23d738', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(93, 0, 0, 'VIÑEDOS CIGARRAL ADOLFO, S.L', 'Adolfo Toledo 2017', NULL, 'B45754579', '', '93', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', 'facturacion@adolfo-toledo.com Francisco', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 4, NULL, 3, 1, NULL, 113, 113, '4f1d1fc280ba73091d0d955f05c31d03', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(94, 0, 0, 'Antonio Elizagaray Larumbe', 'Ortzadar', NULL, '15809729N', '', '94', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 114, 114, 'd486754b4d3f69c169cb03846027c3d4', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(95, 0, 0, 'José Enrique de Vargas Castillo', 'Lantana Herbolario', NULL, '28480810W', '', '95', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 3, 5, NULL, 115, 115, '9586eea01256c6e08ea2a6f7aa10f540', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(96, 0, 0, 'Rocio Caro de la Rosa', 'Hierros y Aceros Rocio, SL', NULL, '34058560E', '', '96', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 116, 116, 'e18a599490dfb271ad8e312497b706d4', '', '2018-07-04 10:41:18', '2018-07-04 10:41:18', NULL),
(97, 0, 0, 'Kombucheria Lirona SL', 'Kombucheria', NULL, NULL, '', '97', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 117, 117, '4de1a390c5c1653d565d0d9decedb9f6', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(98, 0, 0, 'Carlos Camacho del Álamo', 'La Carica Jerez', NULL, '50874436T', '', '98', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 118, 118, 'f277fec894f7e3f19b423d3fe9a6f9e3', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(100, 0, 0, 'Gonzalo Alvarez de Toledo Marvizon', 'Sattva Herbolario', NULL, '28327629R', '', '100', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 3, 5, NULL, 119, 119, '9831efae8ed465466c685644605e7e43', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(101, 0, 0, 'Rocio Robles Arozarena', 'Rocio', NULL, NULL, '', '101', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 120, 120, 'f0c847e9d84a4d02a659920a59e164ca', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(102, 0, 0, 'BIO PEPE, S.L.', 'BIO PEPE, S.L.', NULL, 'B84178854', '', '102', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 121, 121, 'eec5a412bb542656a6a9a74355a02193', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(103, 0, 0, 'Naturolia Essence, S.L.', 'Naturolia Essence, S.L.', NULL, 'B90198839', '', '103', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 122, 122, '504ae9cb85513e5913122c7f55b71955', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(104, 0, 0, 'Celia Jimenez ', 'Celia Jimenez ', NULL, '01178374S', '', '104', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 123, 123, 'af3d9de6ad2bb7973a24c7a8d4e07bf0', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(105, 0, 0, 'MERCEDES RONCERO DIAZ', 'MERCEDES', NULL, NULL, '', '105', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 124, 124, '3d1f70384fe2c45c99e2d852f9163f2f', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(106, 0, 0, 'Cristobal Medina Tirado', 'Cristobal Medina Tirado', NULL, '26219064F', '', '106', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 125, 125, '9b5402679d6fe335244410b8fe59dc74', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(107, 0, 0, 'Alvaro Castro Hidalgo', 'Ecovida', NULL, '45889754Q', '', '107', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 126, 126, '974a964e432fde486df9060022dadf47', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(108, 0, 0, 'Maria Ronte Esteban Matovell', 'Maria Ronte Esteban Matovell', NULL, '12774299F', '', '108', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 127, 127, '2f7d11b9fc6ff4ce69f6b8ecae1519ff', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(109, 0, 0, 'Cristina Macias Delgado', 'Cristina Macias Delgado', NULL, NULL, '', '109', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 128, 128, '1d8408caf860890e2318686a9801e2d8', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(110, 0, 0, 'Esencia de Jabugo, SL', 'Betis Sport Bar', NULL, 'B21510029', '', '110', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 3, 1, NULL, 129, 129, '2e8379c5f59cf48e7eb52065b91ed5d6', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(111, 0, 0, 'Vicente Mayor Brotons', 'Biolanuza', NULL, '21693446', '', '111', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 130, 130, '46a7900252deaed3e1c7ef24cdda0ead', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(112, 0, 0, 'Maria Hernandez Gallego', 'Maria Hernandez Gallego', NULL, NULL, '', '112', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 131, 131, 'f83141067be61e884fde560dd83c7763', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(113, 0, 0, ' Mª Carmen García Arias   ', 'La Plaza Verde', NULL, '09387708M', '', '113', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 132, 132, '8e8161bbbe4eea152cce82abbfff60ab', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(114, 0, 0, 'Maria del Pilar Labrador Moreno', 'Biocentro Badajoz', NULL, '6997032H', '', '114', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 133, 133, 'ccf251e8d1b0407e0d83b8e6aab67eaa', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(115, 0, 0, 'Luis David Gonzalez Naranjo', 'Manuel Jerez Distribuidor', NULL, NULL, '', '115', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 134, 134, '54b33b39dea96740d72d0319cf8b9b62', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(116, 0, 0, 'Mari Luz Eizaguirre', 'Mari Luz Eizaguirre', NULL, NULL, '', '116', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 135, 135, 'edc63163942876e65c62f7adb9c4becd', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(117, 0, 0, 'Carlos Vega Simón', 'Carlos Vega Simón', NULL, '12390721T', '', '117', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 136, 136, '171183bbbe4b0b0b681b6443cccec335', '', '2018-07-04 10:41:19', '2018-07-04 10:41:19', NULL),
(118, 0, 0, 'Maria José Carrero', 'Maria José Carrero', NULL, '14614703', '', '118', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 137, 137, 'bef69da67a4be88bd2db176404b0ebf9', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(119, 0, 0, 'Maria Victoria Perez Cancelas', 'Galicia Sostenible', NULL, '35979433G', '', '119', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 138, 138, '92b58edccb8c1515a48289754f4403ad', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(120, 0, 0, 'Maria Dolores Vales Bravo', 'Maria Dolores Vales Bravo', NULL, NULL, '', '120', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 139, 139, '38e1057b1a9fde04f1565640b8045445', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(121, 0, 0, 'Itziar Pitillas Pellicer', 'Itziar Pitillas Pellicer', NULL, NULL, '', '121', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 140, 140, 'a5430d0a7559d81fc1288a6e7ed2f410', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(122, 0, 0, 'Teresa  Gomez', 'Tere  A3 Asesores', NULL, NULL, '', '122', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 1, 3, NULL, 141, 141, '03723bacb34553d14dc72eb40411fc51', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(123, 0, 0, 'Wurst&burguer Maximiliam S.L.', 'Wurst&burguer Maximiliam S.L.', NULL, NULL, '', '123', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '605897067 Mariano Mauri y 645739121 Jenny Polo', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 142, 142, 'f32c7754e33fb073e7691f3ed3a2af46', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(124, 0, 0, 'Cinta Cabeza Barroso', 'Cinta Cabeza Barroso', NULL, NULL, '', '124', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 143, 143, '408e412bdc1c74905fc625c81ce23800', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(125, 0, 0, 'Productos Ecológicos del Sur, SL', 'Ecosur', NULL, 'B04357265', '', '125', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 144, 144, 'c54ae00678963a9a770a44458833aaae', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(126, 0, 0, 'José Manuel Durán Trujillo', 'José Manuel Durán Trujillo', NULL, '28397246C', '', '126', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 145, 145, '613a317e3156a8b7048ddcd5a8caf2f9', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(127, 0, 0, 'Diana López Jiménez', 'Diana López Jiménez', NULL, '46893169X', '', '127', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 146, 146, '30eb9e63e0bee08bce25f7752bc3a08d', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(128, 0, 0, 'RICARDO GARCIA BORREGO', 'RICARDO GARCIA BORREGO', NULL, NULL, '', '128', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 147, 147, 'ac6c5f8f39cffd6c265139579d787a7d', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(129, 0, 0, 'Etelvino Alvarez Jara', 'Etelvino Alvarez Jara', NULL, NULL, '', '129', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 148, 148, '2dcb3328388e64e096a713c6970c96f9', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(131, 0, 0, 'Ana Carmona Rueda', 'El Obrador Ecologico', NULL, '78759776W', '', '131', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 149, 149, '728cbfd4b0325149450caf325dfe9570', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(132, 0, 0, 'Biosabor Nature, SL', 'Biosabor ', NULL, 'B04736757', '', '132', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 4, NULL, 1, 1, NULL, 150, 150, '65776258bc06c826781c05b9936b9d5d', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(133, 0, 0, 'Carmen Castellanos Alvarez', 'Coq&roll', NULL, '41751165Y', '', '133', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 3, 1, NULL, 151, 151, '2cd4ea3ae502b4033cf97ec1eb999dfb', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(134, 0, 0, 'Jose María Fernández', 'Jose María Fernández', NULL, NULL, '', '134', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 152, 152, '73a15a584b65cfb1123fe843b6a4b1f0', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(135, 0, 0, 'Doble H Natural S.L.', 'Herboteca Sevilla', NULL, 'B90089855', '', '135', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 153, 153, '647192d249e87e750a93fb1c8e45491b', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(136, 0, 0, 'Juan Estudillo Ramirez', 'Juan Estudillo Ramirez', NULL, '52300302G', '', '136', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 154, 154, '327f3e9a6003c4c39a6e91b562850624', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(137, 0, 0, 'Rafael Joaquin Gonzalez de Dios', 'Rafael Joaquin', NULL, NULL, '', '137', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 6, NULL, 3, 1, NULL, 155, 155, '1847d187f01a942ea85ba9eed2c12ba8', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(138, 0, 0, 'Joaquina Murillo Murillo', 'Domingo Gaviño San Jerónimo', NULL, '28469932A', '', '138', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 156, 156, '83680d948579f48be81df184338a2840', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(139, 0, 0, 'Luciano Sileo', 'Il canapaio', NULL, 'Y0220597H', '', '139', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 3, 5, NULL, 157, 157, 'a6d5d71eb66b61e6ec49a6b1139a84a0', '', '2018-07-04 10:41:20', '2018-07-04 10:41:20', NULL),
(140, 0, 0, 'Rosa Cabotá Gimeno', 'La Tuya Herbolario', NULL, '28853649B', '', '140', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 158, 158, '445353cfcd27cfcc6f6727588c4c94aa', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(141, 0, 0, 'Solano Pharma Distributions, SL', 'Farmasol Dos Hermanas', NULL, 'B91960534', '', '141', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 3, 1, NULL, 159, 159, 'd7f39680add273c83495edda28781a2f', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(142, 0, 0, 'Victoria y Lola Diputación', 'Victoria y Lola Diputación', NULL, NULL, '', '142', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 2, NULL, 3, 3, NULL, 160, 160, 'c741d102106ab8fa19ee8b9cdb650b6d', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(143, 0, 0, 'Roser Ruiz Écija', 'Font de Salut Herbolario', NULL, '43394667S', '', '143', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 161, 161, '8dcb66f0d3658758f688c617edbcf28d', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(144, 0, 0, 'Forcamar, SL', 'Supernatura', NULL, 'B14699177', '', '144', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 162, 162, '9873b3c40b9fcab2c8e424329f171466', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(145, 0, 0, 'Asociación El Encinar', 'El Encinar Granada', NULL, 'G18386839', '', '145', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 163, 163, '10f9cadccd93addbdecfa30a32b46ddd', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(146, 0, 0, 'Jose Antonio Fernandez Diaz', 'Larios Herboristeria', NULL, '5258573G', '', '146', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', 'Mª Jose (antigua profesora en el PUA)', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 164, 164, '3f34f469cea56606799b52da243b3bdc', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(147, 0, 0, 'BIO ALVERDE SL', 'BIO ALVERDE SL', NULL, 'B90205337', '', '147', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 1, 1, NULL, 165, 165, '13baf40932f94aafd23dab021270804c', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(148, 0, 0, 'Maria del Carmen Sosa Fuentemilla', 'Maria del Carmen Sosa Fuentemilla', NULL, NULL, '', '148', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 166, 166, 'e7f6a580da818ff946dc7ede6b29d2ab', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(149, 0, 0, 'MARIA AUXILIADORA CUESTA ESCORESCA', 'MARIA AUXILIADORA CUESTA ESCORESCA', NULL, NULL, '', '149', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 167, 167, '01b39d2edada9494185ca85f8c8bf21e', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(150, 0, 0, 'Rosalia Gomez Gonzalez', 'Rosalia Sabores Alemania', NULL, 'DE308028537', '', '150', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 168, 168, 'bab4ad469975ebce30714ece2fd6079b', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(151, 0, 0, 'Angeles Zarco Guzman', 'Angeles Zarco Guzman', NULL, '31826067R', '', '151', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 169, 169, 'e6c3559f5ffa2115fd32728d936b581a', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(152, 0, 0, 'Pilar Torres Rodriguez', 'Pilar Torres Rodriguez', NULL, NULL, '', '152', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 170, 170, 'effd4068e5a16a4a42a4bd55d7ed7bce', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(153, 0, 0, 'Lucy Marie Coley', 'Love Organic ', NULL, 'X1636530B', '', '153', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 171, 171, '84039116af5bad05a072ae4ef090a2f5', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(154, 0, 0, 'Oscar Garcia Pedraza', 'LA ERA ECOLOGICA', NULL, '26811857K', '', '154', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 3, NULL, 3, 5, NULL, 172, 172, 'b4b86d4c9a7240f399ee744c1cdc3834', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(155, 0, 0, 'Eva Maria Chia Cardeñosa', 'Ecologico de Abastos Dos Hermanas', NULL, NULL, '', '155', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 3, 5, NULL, 173, 173, '2c9a5f08a914f264a0ef6d289d0a6855', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(156, 0, 0, 'BIO LANZAC SL', 'PIDEBIO', NULL, 'B93397974', '', '156', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 3, 1, NULL, 174, 174, '30339378ae642998cf89e73f7bba257c', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(157, 0, 0, 'José Antonio Neto', 'José Antonio Neto', NULL, NULL, '', '157', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 175, 175, '8efe48aeb13da4c6c402b811c1698be2', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(158, 0, 0, 'Vicente Manzano-Arrondo', 'Vicente Manzano-Arrondo', NULL, NULL, '', '158', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 176, 176, '564747c1385e47cb05a3cb4a8fbf54e8', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(159, 0, 0, 'Asociación ATMANA', 'ATMANA', NULL, 'G72218282', '', '159', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 177, 177, '770852b2c0e7a625cd252638d1131b26', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(161, 0, 0, 'Fernando Hoyos Mielgo', 'Fernando Hoyos Mielgo', NULL, NULL, '', '161', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 178, 178, '5685d48efb0f4eea8e5fe5080b3fb08a', '', '2018-07-04 10:41:21', '2018-07-04 10:41:21', NULL),
(162, 0, 0, 'Lourdes Rodriguez', 'Lourdes Rodriguez', NULL, NULL, '', '162', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 179, 179, '992abd534b10c24380ae7012012a978a', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(163, 0, 0, 'La Grana Ecológica, SL', 'La Grana ', NULL, NULL, '', '163', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 180, 180, 'ffe5b74d21b445bffc3e35dfa8f1293f', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(164, 0, 0, 'Ismael Riscart Franco ', 'La Sostenible', NULL, NULL, '', '164', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 181, 181, '04b8e699eea24a0cf0d192c3d1b8f517', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(165, 0, 0, 'Rosa Sáenz Moriche', 'Rosa Sáenz Moriche', NULL, NULL, '', '165', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 182, 182, '00d927bc45c55426245dfb0757e293b8', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(166, 0, 0, 'Alfonso Moreno', 'Alfonso Moreno', NULL, '02538762E', '', '166', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 183, 183, '648ffbbecd92c87d88499fd3d06eff78', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(167, 0, 0, 'David Agulló Sansano', 'La Ventana Natural', NULL, '22003921M', '', '167', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 184, 184, 'edcdd940b7e78808f10633d7994502a5', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(168, 0, 0, 'Israel Moreno Rodríguez', 'Israel Moreno Rodríguez', NULL, '28747351L', '', '168', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 185, 185, 'de9e8c9611412abfaa217c4ec315dba2', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(169, 0, 0, 'Rosa María Chaves Miranda', 'Rosa María Chaves Miranda', NULL, '33892702V', '', '169', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 186, 186, 'ceefd736dacd11033d3470fdc65d96a1', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL);
INSERT INTO `customers` (`id`, `company_id`, `user_id`, `name_fiscal`, `name_commercial`, `website`, `identification`, `webshop_id`, `reference_external`, `accounting_id`, `payment_days`, `no_payment_month`, `outstanding_amount_allowed`, `outstanding_amount`, `unresolved_amount`, `notes`, `customer_logo`, `sales_equalization`, `allow_login`, `accept_einvoice`, `blocked`, `active`, `sales_rep_id`, `currency_id`, `language_id`, `customer_group_id`, `payment_method_id`, `invoice_template_id`, `carrier_id`, `price_list_id`, `direct_debit_account_id`, `invoicing_address_id`, `shipping_address_id`, `secure_key`, `import_key`, `created_at`, `updated_at`, `deleted_at`) VALUES
(170, 0, 0, 'Laura Parrilla Gomez', 'Laura Parrilla Gomez', NULL, NULL, '', '170', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 187, 187, '25033f04722feaa855b80c380ff1816d', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(171, 0, 0, 'Plataforma Ibérica de Distribución Ecológica S.L.', 'Pidebio Madrid', NULL, 'B87308698', '', '171', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 188, 188, 'a2eac2f1ea0abba0f1bc8cd63665227e', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(172, 0, 0, 'Maria Nieves Segarra Villas', 'Segarra Villas CB', NULL, 'E87598645', '', '172', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', 'Ma Nieves Segarra - Victor Diaz Hernandez', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 189, 189, '2ee117e2a180bad79478d4d093fdf9fb', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(173, 0, 0, 'El Vergel de la Villa S.L.', 'Supermercado Ecológico el Vergel', NULL, 'B83309583', '', '173', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 190, 190, 'f4824277afa123b831d0e560446d95df', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(174, 0, 0, 'Asociación Landare', 'Landare', NULL, 'G31428303', '', '174', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '948121308 ext4 Leyre, ext1 Patricia, ext3 Amaia', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 191, 191, '75e5178666bab98679c63974f84cff36', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(175, 0, 0, 'El Ecosúper, C.B. ', 'El Ecosúper', NULL, 'E19544030', '', '175', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 192, 192, '0e2fb9b6cc783c7587e9086fd7f43dde', '', '2018-07-04 10:41:22', '2018-07-04 10:41:22', NULL),
(177, 0, 0, 'Gumiel y Mendia, SL', 'Gumendi', NULL, 'B31436470', '', '177', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 3, NULL, 3, 2, NULL, 193, 193, 'ffbf92b36dcc78161e9476f8c3354af9', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(178, 0, 0, 'Aaron Rojas', 'Aaron Rojas', NULL, NULL, '', '178', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 1, NULL, 3, 3, NULL, 194, 194, 'fd6bfdb2b1ddb2ee8d74c502c9207f72', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(179, 0, 0, 'ORGANIC FOOD & CAFE', 'ORGANIC FOOD & CAFE', NULL, NULL, '', '179', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 195, 195, '888d0acea220f74a91047422137f802f', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(180, 0, 0, 'MERCADO DE SAN LUCAS S.L. LA ECOCINA', 'LA ECOCINA', NULL, 'B86789724', '', '180', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 196, 196, 'e7c5cb9250f69de1e0826b001c5a7589', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(181, 0, 0, 'Montserrat Alvarez', 'Montserrat Alvarez', NULL, '33288906V', '', '181', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 197, 197, '1e461ae7e780a7e413eeed08484f1dc3', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(182, 0, 0, 'guzman gastronomia', 'guzman gastronomia', NULL, NULL, '', '182', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 198, 198, 'c9d61b30917912377a5e06903fb4eb44', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(183, 0, 0, 'Bea Oneca Uriz', 'Bea Oneca Uriz', NULL, NULL, '', '183', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 199, 199, '939da5d461e5676eebeb1426af949c8f', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(184, 0, 0, 'Carolina Guerrero Peral', 'Flor de la Vida Herbolario Bellavista', NULL, NULL, '', '184', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 3, 5, NULL, 200, 200, 'ced30546be665095b97777f712eacc52', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(185, 0, 0, 'Rosario Perez del Amo', 'Rosario Perez del Amo', NULL, '28947206G', '', '185', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 201, 201, '0ccec957ff0b32232c088e39a4c4e2a9', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(186, 0, 0, 'Rosa María Sarrió Cabello', 'herbolario jazmín y Azahar', NULL, '30222288N', '', '186', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 3, 5, NULL, 202, 202, '13b1304c0f92113cf862d616648b0e87', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(187, 0, 0, 'Laura Lobato', 'Laura Lobato', NULL, NULL, '', '187', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 203, 203, '9ef8a2353e32ab7201a4eb96185bc846', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(188, 0, 0, 'Alicia Olmo Nuñez', 'Alicia Olmo Nuñez', NULL, '52295983D', '', '188', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 204, 204, '70093fcfbfbb117310092a6a11330f11', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(189, 0, 0, 'Carmen Manzano Prieto', 'Momentos', NULL, '42811692K', '', '189', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 6, NULL, 3, 1, NULL, 205, 205, '54682aa2ccd71a6e5b9751c7ef144dee', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(190, 0, 0, 'Susana Ciga', 'Bar Los Gemelos', NULL, NULL, '', '190', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 206, 206, '321f50a514503a55c1ea02d0f3786fad', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(191, 0, 0, 'Galizia Sostenible, SL', 'Galizia Sostenible', NULL, 'B27842830', '', '191', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 207, 207, '2907910bbe963d32271ed049c34c03ba', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(192, 0, 0, 'Maria del Valle Moyano Orejuela', 'Mª del Valle', NULL, NULL, '', '192', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 208, 208, '0a6a1a777d80cb7b1500f7624519d75d', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(193, 0, 0, 'Miguel Ángel Reina Garcia', 'El Mastrén Málaga', NULL, '52586265P', '', '193', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 209, 209, '4a5a7ed14ef5bdda55cba24be1cc796c', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(194, 0, 0, 'Teresa Monrea Perez de Ciriza', 'Teresa Monrea Perez de Ciriza', NULL, '18193111L', '', '194', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 210, 210, '481854b60df12281883b6e314d1f7c29', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(195, 0, 0, 'venta contado', 'venta contado', NULL, NULL, '', '195', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 2, NULL, 1, 3, NULL, 211, 211, 'e37fc2bdbc2f1792b130694c338b94e0', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(196, 0, 0, 'Macarena Rodriguez Cuesta', 'Macarena Rodriguez Cuesta', NULL, '28908014G', '', '196', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 212, 212, 'c635b487cdebbf3db036404b2515cf8c', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(197, 0, 0, 'Carolina Sanz Badallo', 'Carolina Sanz Badallo', NULL, NULL, '', '197', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 3, NULL, 3, 3, NULL, 213, 213, 'd6a80ca33aafe669f872d263d35e4d01', '', '2018-07-04 10:41:23', '2018-07-04 10:41:23', NULL),
(198, 0, 0, 'Lorea Agirre Iribarren', 'Kimetzbelardenda', NULL, '72685114P', '', '198', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 5, 6, NULL, 3, 5, NULL, 214, 214, 'e99a6edd9424aaae9da75f4a19e5ebdc', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(199, 0, 0, 'Lola Caraballo Matito', 'Lola Caraballo Matito', NULL, NULL, '', '199', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 215, 215, '9606d87255aa24a424266eff68a2423f', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(200, 0, 0, 'Josefa Guerrero Lara', 'Casa Santiveri Tarifa', NULL, '31829915P', '', '200', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 216, 216, '762cf76c13efbf59398d6527b1b21f64', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(201, 0, 0, 'Sandra Legasa Rosa', 'Oreka Nutrizioa eta Dietetika Belardenda', NULL, '72677514K', '', '201', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 217, 217, 'ea9a70511ba9cde65130f8a990e25a89', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(202, 0, 0, 'Jose Antonio Arroyo Pais', 'Jose Antonio Arroyo Pais', NULL, NULL, '', '202', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 218, 218, '299a8a7876a855322eec2349510e8b20', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(203, 0, 0, 'Joaquín Luna García', 'Joaquín Luna García', NULL, NULL, '', '203', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 219, 219, 'd60039a3fcbea4c35e5bcf86b506bc73', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(204, 0, 0, 'Ainhoa Esparza Santesteban', 'GL Asesores', NULL, NULL, '', '204', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 220, 220, '59a388b8142dd8c7ef8a26ac40f167b1', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(205, 0, 0, 'Inmaculada Andrades Pineda ', 'Inmaculada Andrades Pineda ', NULL, NULL, '', '205', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 221, 221, '8e0e8ccec214fdd0ecd02e7feded48ad', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(206, 0, 0, 'Damalora\'s Home SL', 'Freefood', NULL, 'B65725228', '', '206', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 3, NULL, 3, 1, NULL, 222, 222, 'aaac628c4d5a058af4fcd0a03f6bf5d3', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(207, 0, 0, 'Rosario Perez Perez', 'Rosario Perez Perez', NULL, '08856066P', '', '207', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 223, 223, '1cf63457d41459f93660291207839ae0', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(208, 0, 0, 'Francisca Garrido Estepa', 'Francisca Garrido Estepa', NULL, '30525061J', '', '208', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 6, NULL, 3, 5, NULL, 224, 224, 'd37327d453e33ffd77ab3982440262ec', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(209, 0, 0, 'Adalup Licensing', 'Ecobox Food', NULL, 'B86555646', '', '209', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 225, 225, '17fa9d08f0990235b0b8060f0507a00f', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(210, 0, 0, 'Finca Fuentillezjos Alimentos Ecológicos SL', 'Finca Fuentillezjos', NULL, 'B13383245', '', '210', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 226, 226, '9e3f8b9aaeced0079697bdbdecbff89f', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(211, 0, 0, 'Ana Ciga Romero', 'Ana Ciga Romero', NULL, NULL, '', '211', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 227, 227, '1f42efacbda910053514dcf92286f110', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(212, 0, 0, 'Lola Bayod Mir', 'Lola Bayod Mir', NULL, NULL, '', '212', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 228, 228, 'acbe8118068670d951bd60c0c7613799', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(213, 0, 0, 'Rosario Vera ', 'Rosario Vera ', NULL, NULL, '', '213', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 229, 229, '54d2f3212249e00e29a3d2d8ce4f57b0', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(214, 0, 0, 'Elena Sanchez del Coso', 'Elena Sanchez del Coso', NULL, '75262732Q', '', '214', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 230, 230, 'f32403074886abc184108250404ef7eb', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(215, 0, 0, 'Marta Romero Superregui', 'Marta Romero Superregui JMJ Asesores', NULL, '28491254G', '', '215', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 231, 231, 'a9ea17d47ee16befea345433ef561918', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(216, 0, 0, 'TRANSPORTES FRIGORIFICOS NARVAL SEVILLA', 'TRANSPORTES FRIGORIFICOS NARVAL SEVILLA', NULL, 'B81971186', '', '216', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 1, 1, NULL, 232, 232, '43599f666eaf31db5fddc1c83fd5657f', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(217, 0, 0, 'Mercedes Linares Arquitecta', 'Mercedes Linares', NULL, NULL, '', '217', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 233, 233, 'e08a2f63a67870289cb6ad216dc154b5', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(218, 0, 0, 'María Rodríguez Fernández', 'María Rodríguez Fernández', NULL, NULL, '', '218', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 234, 234, 'a7c93210d27ffaba456a49f89208a535', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(219, 0, 0, 'María Isabel Siles Cantalejo', 'María Isabel Siles Cantalejo', NULL, NULL, '', '219', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 235, 235, '96b060b6f330ef7db63f345042784e17', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(220, 0, 0, 'Carlos Calzada de las Heras', 'Botellas y Latas', NULL, '08985040K', '', '220', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 236, 236, 'b3f5917280107f6a560e78179e719975', '', '2018-07-04 10:41:24', '2018-07-04 10:41:24', NULL),
(221, 0, 0, 'Carmen Martínez Moya', 'Carmen Martínez Moya', NULL, NULL, '', '221', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 237, 237, 'a9ec1fcfd5205d30054f31e35c33cb2e', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(222, 0, 0, 'Aida Poveda Escudero', 'Aida Poveda Escudero', NULL, NULL, '', '222', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 238, 238, '3cea87f557aa59f48ce4ba71d9241a90', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(223, 0, 0, 'Andrés Madueño Baena', 'Andrés Madueño Baena', NULL, '30825175E', '', '223', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 239, 239, 'f412bed01f9ef7299bd9341b4b773aaa', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(224, 0, 0, 'Margarita Patilla Villuendas', 'Margarita Patilla Villuendas', NULL, '30546761R', '', '224', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 240, 240, '4a6d556151daa551013399cf3de0d287', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(225, 0, 0, 'Agustina Barrera Escobar', 'Agustina Barrera Escobar', NULL, '44039880D', '', '225', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 241, 241, 'd50d21ee8c62bf416e6853eeb5e43ee1', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(226, 0, 0, 'Noelia López Sánchez', 'Noelia López Sánchez', NULL, '53158343F', '', '226', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 242, 242, '86de12875ea4aeca036f300bdd76f6ca', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(227, 0, 0, 'Teresa Oliveras', 'Teresa Oliveras', NULL, '27303746D', '', '227', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 243, 243, '5235428f6bb903eca5b12faee025f8f2', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(228, 0, 0, 'Verde Kaki, SL', 'Vantana Bar', NULL, 'B91954990', '', '228', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 244, 244, '0ba09d1ba03260e9cb7138eaddc8b93e', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(229, 0, 0, 'Beatriz Cáceres ', 'Beatriz Cáceres ', NULL, NULL, '', '229', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 245, 245, '307f90b0f2f72d1e2ce3bef4b4d3b11f', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(230, 0, 0, 'Alfredo Serrano Yarza', 'Alfredo Serrano Yarza', NULL, NULL, '', '230', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 246, 246, '3fc5b70846d84283cd40012e5efbe943', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(231, 0, 0, 'Juan José Muñoz Acosta', 'Juan José Muñoz Acosta', NULL, '52691632N', '', '231', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 247, 247, 'ea2ef3f54a1f38028398c3cbe4172518', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(232, 0, 0, 'Productos Con Sentido, SL', 'El Consentido', NULL, 'B90236803', '', '232', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 1, 2, NULL, 248, 248, 'b61cdff62a2c3239eed27268bc83d6c1', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(233, 0, 0, 'Maria Sanchez Gonzalez', 'Maria Sanchez Gonzalez', NULL, NULL, '', '233', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 249, 249, 'cca00456edfbea4a0824bb7d9d764c8c', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(234, 0, 0, 'Esperanza March Aguiló', 'Esperanza March Aguiló', NULL, NULL, '', '234', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 250, 250, '11ed33a07e22ba11385e0931625bb2cd', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(235, 0, 0, 'La Nau Organic', 'La Nau Organic', NULL, NULL, '', '235', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 251, 251, 'efbdf14334172bf35a0a44b47bb4947f', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(236, 0, 0, 'Tomás Asensio Levy', 'L\'affineur de Fromage', NULL, '50873187Q', '', '236', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 252, 252, '147ef67c6770f6c4aba9df2aeb876e87', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(237, 0, 0, 'DEZA', 'DEZA', NULL, NULL, '', '237', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', 'frescos2@deza-sa.com de José Gomez', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 253, 253, '626fbd7d661e4141b0977c8300f04bf6', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(238, 0, 0, 'Amparo de Luque Gonzalez', 'Amparo de Luque Gonzalez', NULL, NULL, '', '238', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 254, 254, 'd9d5ac654c6d43e98b7d2b1faa3789c0', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(239, 0, 0, 'Reposteria Latorre Simon, SL', 'Latorre Simon', NULL, 'B04182465', '', '239', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 255, 255, 'a207f0af669f1566e79143c8d64fbccb', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(240, 0, 0, 'La Nau Organic / Odisea Att Rosa', 'La Nau Organic', NULL, NULL, '', '240', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 256, 256, '2b641cd31677085fde2a55a3f15204bd', '', '2018-07-04 10:41:25', '2018-07-04 10:41:25', NULL),
(241, 0, 0, 'Haribericas  XXI, S.L.', 'Hariberica', NULL, 'B64939341', '', '241', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 257, 257, '35f1ba5a05d72f9b9f08824317f042ee', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(242, 0, 0, 'Milagros del Puerto Herrero', 'La Ecotienda de Mila', NULL, '02874193K', '', '242', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 258, 258, '82328ae778ab5286e02b04869875f640', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(243, 0, 0, 'Sabores sarl', 'Sabores ', NULL, 'FR08520944836', '', '243', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 2, 1, NULL, 3, 2, NULL, 259, 259, 'c9b60039f927f4bba7a2e7780ba8e9c9', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(244, 0, 0, 'Mario Jiménez Morera', 'La Tahona del Artesano', NULL, '75755506S', '', '244', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 260, 260, '96b5d441c3759d594efa81a1b0d50a90', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(245, 0, 0, 'Juan Azpiricueta Rodriguez-Valdés', 'Ecocenter Tarifa', NULL, '51391778A', '', '245', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 261, 261, '1214e08671c2d189bcbd78edb4aa0a7f', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(246, 0, 0, 'Teresa Pérez Soltero', 'Esencias', NULL, '75545460M', '', '246', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 5, NULL, 262, 262, 'bc07373a5bc3704ea7764dd29df898d7', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(247, 0, 0, 'ABADIA & GRIN, SL', 'GRINFOOD', NULL, 'B87312906', '', '247', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 263, 263, 'c97fa85b9fb8f55175a18a90c792ad2a', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(248, 0, 0, 'Mauricio J. López Madroñero', 'La Biohteca Supermercado Montequinto', NULL, NULL, '', '248', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 1, 1, NULL, 264, 264, 'a0610d67a8172dd1a8ffa7acd8519349', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(249, 0, 0, 'Prodetur, SA', 'Prodetur, SA', NULL, 'A41555749', '', '249', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 1, NULL, 1, 3, NULL, 265, 265, '93edcac974fcbefb42e69485cbd13800', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(250, 0, 0, 'Casma Salud, SL', 'Casma Salud', NULL, 'B85995363', '', '250', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 6, NULL, 1, 1, NULL, 266, 266, '692d276a63b22bf05092a154e92ccfd4', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(251, 0, 0, 'Teresa Monreal Perez de Ciriza', 'Teresa Monreal Perez de Ciriza', NULL, NULL, '', '251', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 267, 267, 'e3aa8d53e494dd6245737b1f22cb7ca5', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(253, 0, 0, 'Alejandro Lourenço Míguez', 'Aljareco', NULL, '45807570B', '', '253', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 268, 268, 'aee6433cba439947442a22d71ae16177', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(254, 0, 0, 'Elama Sanleo, SL', 'Cucharas', NULL, 'B91559880', '', '254', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 1, 1, NULL, 269, 269, 'afbe69fda7b3c7818aea291d8d296999', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(255, 0, 0, 'La Esencia Pan Artesanal, SL', 'Javi La Esencia', NULL, 'B90309204', '', '255', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 1, 1, NULL, 270, 270, 'b1e078fec8733a2a2950fa94ab9c926c', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(256, 0, 0, 'FIBA Catering, SL', 'FIBA Catering', NULL, 'B36854305', '', '256', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 3, NULL, 3, 1, NULL, 271, 271, '92f3f2ad25c425c795921f34deeed360', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(257, 0, 0, 'Las Comadres SCA', 'Las Comadres ', NULL, 'F90273731', '', '257', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 272, 272, 'e6ca0183cba571a0eb0d83a7ac98c343', '', '2018-07-04 10:41:26', '2018-07-04 10:41:26', NULL),
(258, 0, 0, 'LA DESPENSA DE IGERETXE, SL', 'LA DESPENSA DE IGERETXE', NULL, NULL, '', '258', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 1, NULL, 3, 1, NULL, 273, 273, '64e8508f96503c84a4a309f9e5d72f54', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(259, 0, 0, 'Antonio Perez Capote', 'Herbolario Gran Plaza', NULL, '28731077Y', '', '259', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 1, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 5, NULL, 274, 274, '4ad976a102968f4e17959320c2dbf3ac', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(260, 0, 0, 'CLIVING15 SLU', 'Arima Hotel', NULL, 'B75131383', '', '260', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 4, NULL, 3, 1, NULL, 275, 275, '05734b32b4214ea85822e4912a024243', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(261, 0, 0, 'Elena Segura Quijada', 'Elena Segura Quijada', NULL, NULL, '', '261', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 276, 276, 'aa24ab396bef880cc6e9ed68ece56a4a', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(262, 0, 0, 'El Herbolario Bio, SL', 'Herbobio', NULL, 'B90373473', '', '262', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 1, 2, NULL, 1, 1, NULL, 277, 277, 'bb34ec4ae321bebcae178ba2561d9508', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(263, 0, 0, 'Ayuntamiento de Brenes', 'Ayuntamiento de Brenes', NULL, 'P4101800C', '', '263', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 2, NULL, 1, 3, NULL, 278, 278, '9edf5fbc7b86202dad69aa9591a06e25', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50115, 0, 0, 'Lola Mir', 'Lola Mir', NULL, NULL, '115', '50115', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 279, 279, 'db9cae8fd843276ccbaae452fcbb7921', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50139, 0, 0, 'Teresa Gomez Hidalgo', 'GRUPO ATRES SCA', NULL, NULL, '139', '50139', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 1, 3, NULL, 280, 280, '019d17c978da7c2050438364bad511f9', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50141, 0, 0, 'Beatriz Cáceres', 'Beatriz Cáceres', NULL, NULL, '141', '50141', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 281, 281, '3688aa14822c9cb939ea2c62f6c1c5b3', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50142, 0, 0, 'Rica Esaguy', 'Rica Esaguy', NULL, NULL, '142', '50142', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 282, 282, 'b958efabe14302dde1cfb2432e93e5ff', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50143, 0, 0, 'Lucía Ruiz', 'Lucía Ruiz', NULL, NULL, '143', '50143', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 283, 283, '9ed5bf65988dfba055006807a1102092', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50144, 0, 0, 'Yolanda Antolín Jiménez', 'Yolanda Antolín Jiménez', NULL, NULL, '144', '50144', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 284, 284, '60bef8cebbe30cc42efc8c88271d09e5', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50145, 0, 0, 'Begoña Baltar', 'Begoña Baltar', NULL, NULL, '145', '50145', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 285, 285, 'b778e275fe7c5c7b007bcd53cff9192e', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50147, 0, 0, 'Cristina Ugas', 'Cristina Ugas', NULL, NULL, '147', '50147', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 286, 286, 'e272c6c0f46ac2346d21afd0bfef90bf', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50149, 0, 0, 'Elisa Fernández Pareja', 'Elisa Fernández Pareja', NULL, NULL, '149', '50149', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 287, 287, '11b27a6616a14346eb22d47377f524d2', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50150, 0, 0, 'Jesús Bujalance Hoyos', 'Jesús Bujalance Hoyos', NULL, NULL, '150', '50150', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 288, 288, '0256f2a8bc8b2d1fdccc9605fac23f33', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50151, 0, 0, 'Fátima Moreno', 'Wonkandy, SL', NULL, NULL, '151', '50151', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 289, 289, '51800bbd230d3f5b50969ed20be681df', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50152, 0, 0, 'Macarena Palma Martinez', 'Macarena Palma Martinez', NULL, NULL, '152', '50152', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 290, 290, '2b53d293a26fc4b919de1c7c89ac8f80', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50153, 0, 0, 'Rafael Lora del Castillo', 'Rafael Lora del Castillo', NULL, NULL, '153', '50153', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 291, 291, '59c28bcfea4dc0861ebd842df40e4ec2', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50155, 0, 0, 'Rocío Delgado Sánchez', 'Rocío Delgado Sánchez', NULL, NULL, '155', '50155', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 292, 292, 'f0d6efd5334673d78c5ebb428cec3eda', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50156, 0, 0, 'Jamones Ecologicos de Jabugo,S.L.', 'Jamones Ecologicos de Jabugo,S.L.', NULL, NULL, '156', '50156', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 293, 293, 'cea6d08c9266114a5cb0e550be08062e', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50157, 0, 0, 'María Teresa González Moreno', 'María Teresa González Moreno', NULL, NULL, '157', '50157', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 294, 294, 'd17155be151b210254819036b66ae1dd', '', '2018-07-04 10:41:27', '2018-07-04 10:41:27', NULL),
(50158, 0, 0, 'Guillermina Jiménez Noguera', 'Guillermina Jiménez Noguera', NULL, NULL, '158', '50158', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 295, 295, 'c50ecd8730570d7ef762b279a3506a98', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50159, 0, 0, 'Manuela Lucena Campos', 'Manuela Lucena Campos', NULL, NULL, '159', '50159', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 296, 296, '46fbb4e7918e7ae27a979cfaa67fd537', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50160, 0, 0, 'Teresa Monreal Pérez de Ciriza', 'Teresa Monreal Pérez de Ciriza', NULL, NULL, '160', '50160', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 297, 297, 'cd0b0fddc0e3239af1b02ef0d12ab491', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50161, 0, 0, 'Raquel Jarque', 'Raquel Jarque', NULL, NULL, '161', '50161', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 298, 298, '3a71e65c5f41bd6a39eb5060486548a9', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50162, 0, 0, 'Pilar Rodríguez Rodríguez', 'Pilar Rodríguez Rodríguez', NULL, NULL, '162', '50162', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 299, 299, 'aa4f2a2734ec6fdc710117acc62957cd', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50163, 0, 0, 'M Rosa Zamora Cobo', 'M Rosa Zamora Cobo', NULL, NULL, '163', '50163', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 300, 300, 'f9449d003292334264c72d9456218bde', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50164, 0, 0, 'Pilar Moreno molina', 'Pilar Moreno molina', NULL, NULL, '164', '50164', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 301, 301, 'ac4f9faff86951ef9eaa5a7ecee96c55', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50165, 0, 0, 'Pilar Rodríguez Rodríguez', 'Pilar Rodríguez Rodríguez', NULL, NULL, '165', '50165', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 302, 302, '6c11d8204c80a20dbc5eb64d61f7f914', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50166, 0, 0, 'David Fernández Fernández', 'David Fernández Fernández', NULL, NULL, '166', '50166', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 303, 303, '4e17bb6c30c01e1356f54162202dbe9a', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50167, 0, 0, 'Francisco Vargas Fernández', 'Francisco Vargas Fernández', NULL, NULL, '167', '50167', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 304, 304, '70d063d1a95658754286d3dd26492491', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50168, 0, 0, 'Esmeralda Noguero Tarriño', 'Esmeralda Noguero Tarriño', NULL, NULL, '168', '50168', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 305, 305, 'b6bf6c48fb2d0017c295c4229a383e29', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50169, 0, 0, 'Raquel Paz', 'Raquel Paz', NULL, NULL, '169', '50169', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 306, 306, 'a8b7c25f189cbc599e361ebf3dbace80', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50170, 0, 0, 'reyes hellin', 'reyes hellin', NULL, NULL, '170', '50170', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 307, 307, '4a3294aaafe04028c07ee711a5a52a49', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50171, 0, 0, 'Nexe The way of change', 'Nexe The way of change', NULL, NULL, '171', '50171', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 308, 308, '108879eb82e224519ef8ad2be3c7cc74', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50172, 0, 0, 'Maria Jimenez negrette', 'Maria Jimenez negrette', NULL, NULL, '172', '50172', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 309, 309, 'e10674f16e08800250c027be20ba89e6', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50174, 0, 0, 'CARMEN RODRIGUEZ GUTIERREZ', 'CARMEN RODRIGUEZ GUTIERREZ', NULL, NULL, '174', '50174', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 310, 310, '1b53143bd9dd87c053055d902a098075', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50175, 0, 0, 'Manuel Ferrazzano Jiménez', 'Manuel Ferrazzano Jiménez', NULL, NULL, '175', '50175', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 311, 311, '03e71b967441abdd8d154c1765b3943e', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50176, 0, 0, 'Gloria Ortiz', 'Gloria Ortiz', NULL, NULL, '176', '50176', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 312, 312, '22906c0d301bae22fc78c57c700645b6', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50177, 0, 0, 'CARMEN LLAMAS GARCIA', 'CARMEN LLAMAS GARCIA', NULL, NULL, '177', '50177', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 313, 313, 'ded388da33465248730d7eb197b05fd6', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL),
(50178, 0, 0, 'Susanna Vidal Garcia', 'Susanna Vidal Garcia', NULL, NULL, '178', '50178', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', '', NULL, 0, 0, 0, 0, 1, NULL, 15, 2, 3, 6, NULL, 3, 3, NULL, 314, 314, '6ec6133e4cccb7b8aecc86c0f3c7eceb', '', '2018-07-04 10:41:28', '2018-07-04 10:41:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_groups`
--

CREATE TABLE `customer_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `webshop_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `invoice_template_id` int(10) UNSIGNED DEFAULT NULL,
  `price_list_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_groups`
--

INSERT INTO `customer_groups` (`id`, `company_id`, `user_id`, `name`, `webshop_id`, `active`, `invoice_template_id`, `price_list_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, 0, 'Amiguetes', NULL, 1, NULL, 1, '2018-05-28 10:12:35', '2018-05-28 10:12:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_invoices`
--

CREATE TABLE `customer_invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `sequence_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `document_prefix` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `document_reference` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_discount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `document_date` date NOT NULL,
  `valid_until_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `delivery_date_real` date DEFAULT NULL,
  `next_due_date` date DEFAULT NULL,
  `printed_at` date DEFAULT NULL,
  `edocument_sent_at` date DEFAULT NULL,
  `customer_viewed_at` date DEFAULT NULL,
  `posted_at` date DEFAULT NULL,
  `number_of_packages` smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  `shipping_conditions` text COLLATE utf8mb4_unicode_ci,
  `tracking_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prices_entered_with_tax` tinyint(4) NOT NULL DEFAULT '0',
  `round_prices_with_tax` tinyint(4) NOT NULL DEFAULT '0',
  `currency_conversion_rate` decimal(20,6) NOT NULL DEFAULT '1.000000',
  `down_payment` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `open_balance` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_discounts_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_discounts_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_products_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_products_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_other_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_other_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `commission_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `notes_to_customer` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoicing_address_id` int(10) UNSIGNED NOT NULL,
  `shipping_address_id` int(10) UNSIGNED DEFAULT NULL,
  `warehouse_id` int(10) UNSIGNED DEFAULT NULL,
  `carrier_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_rep_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `payment_method_id` int(10) UNSIGNED NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `secure_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_invoice_lines`
--

CREATE TABLE `customer_invoice_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `line_sort_order` int(11) DEFAULT NULL,
  `line_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `combination_id` int(10) UNSIGNED DEFAULT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(20,6) NOT NULL,
  `cost_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_customer_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_final_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_final_price_tax_inc` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `sales_equalization` tinyint(4) NOT NULL DEFAULT '0',
  `discount_percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `discount_amount_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `discount_amount_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `tax_percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `commission_percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `locked` tinyint(4) NOT NULL DEFAULT '0',
  `customer_invoice_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `sales_rep_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_invoice_line_taxes`
--

CREATE TABLE `customer_invoice_line_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_rule_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taxable_base` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_line_tax` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `customer_invoice_line_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `tax_rule_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sequence_id` int(10) UNSIGNED DEFAULT NULL,
  `document_prefix` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `document_reference` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_customer` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_external` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_via` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'webshop',
  `document_date` datetime NOT NULL,
  `payment_date` datetime DEFAULT NULL,
  `validation_date` datetime DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `delivery_date_real` datetime DEFAULT NULL,
  `close_date` datetime DEFAULT NULL,
  `document_discount_percent` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `document_discount_amount_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `document_discount_amount_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `number_of_packages` smallint(5) UNSIGNED NOT NULL DEFAULT '1',
  `shipping_conditions` text COLLATE utf8mb4_unicode_ci,
  `tracking_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_conversion_rate` decimal(20,6) NOT NULL DEFAULT '1.000000',
  `down_payment` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_discounts_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_discounts_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_products_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_products_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_other_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_other_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_lines_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_lines_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `commission_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `notes_from_customer` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `notes_to_customer` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `locked` tinyint(4) NOT NULL DEFAULT '0',
  `invoicing_address_id` int(10) UNSIGNED NOT NULL,
  `shipping_address_id` int(10) UNSIGNED DEFAULT NULL,
  `warehouse_id` int(10) UNSIGNED DEFAULT NULL,
  `carrier_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_rep_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `payment_method_id` int(10) UNSIGNED NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `parent_document_id` int(10) UNSIGNED DEFAULT NULL,
  `production_sheet_id` int(10) UNSIGNED DEFAULT NULL,
  `secure_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `import_key` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_orders`
--

INSERT INTO `customer_orders` (`id`, `company_id`, `customer_id`, `user_id`, `sequence_id`, `document_prefix`, `document_id`, `document_reference`, `reference`, `reference_customer`, `reference_external`, `created_via`, `document_date`, `payment_date`, `validation_date`, `delivery_date`, `delivery_date_real`, `close_date`, `document_discount_percent`, `document_discount_amount_tax_incl`, `document_discount_amount_tax_excl`, `number_of_packages`, `shipping_conditions`, `tracking_number`, `currency_conversion_rate`, `down_payment`, `total_discounts_tax_incl`, `total_discounts_tax_excl`, `total_products_tax_incl`, `total_products_tax_excl`, `total_shipping_tax_incl`, `total_shipping_tax_excl`, `total_other_tax_incl`, `total_other_tax_excl`, `total_lines_tax_incl`, `total_lines_tax_excl`, `total_tax_incl`, `total_tax_excl`, `commission_amount`, `notes_from_customer`, `notes`, `notes_to_customer`, `status`, `locked`, `invoicing_address_id`, `shipping_address_id`, `warehouse_id`, `carrier_id`, `sales_rep_id`, `currency_id`, `payment_method_id`, `template_id`, `parent_document_id`, `production_sheet_id`, `secure_key`, `import_key`, `created_at`, `updated_at`) VALUES
(1, 0, 3, 0, 3, 'POT', 48, 'POT-0048', NULL, NULL, NULL, 'manual', '2018-05-28 00:00:00', NULL, NULL, NULL, NULL, NULL, '20.000000', '0.000000', '0.000000', 1, NULL, NULL, '1.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '234.500000', '210.500000', '187.600000', '168.400000', '0.000000', NULL, NULL, NULL, 'confirmed', 0, 8, 8, 1, NULL, NULL, 15, 0, NULL, NULL, NULL, '4513fca3da2fc35f1a6b0c3ee2420dbc', '', '2018-05-28 10:14:37', '2018-06-08 16:11:11'),
(2, 0, 3, 0, 3, 'POT', 49, 'POT-0049', NULL, NULL, NULL, 'manual', '2018-05-29 00:00:00', NULL, NULL, NULL, NULL, NULL, '0.000000', '0.000000', '0.000000', 1, NULL, NULL, '1.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '270.700000', '243.000000', '270.700000', '243.000000', '0.000000', NULL, NULL, NULL, 'confirmed', 0, 8, 8, 1, 0, 0, 15, 1, NULL, NULL, NULL, 'cad69cabe30b5cfda8a951651c308948', '', '2018-05-29 09:40:38', '2018-06-27 11:48:45'),
(3, 0, 2, 0, 3, 'POT', 50, 'POT-0050', NULL, NULL, NULL, 'manual', '2018-06-02 00:00:00', NULL, NULL, NULL, NULL, NULL, '10.000000', '0.000000', '0.000000', 1, NULL, NULL, '1.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '530.200000', '482.000000', '477.180000', '433.800000', '0.000000', NULL, NULL, NULL, 'confirmed', 0, 7, 7, 1, NULL, NULL, 15, 1, NULL, NULL, NULL, 'ad9d20558d312a37a54c4e4d705b4f5b', '', '2018-06-02 12:48:59', '2018-06-24 12:50:45'),
(4, 0, 3, 0, 3, 'POT', 51, 'POT-0051', NULL, NULL, NULL, 'manual', '2018-06-04 00:00:00', NULL, NULL, NULL, NULL, NULL, '0.000000', '0.000000', '0.000000', 1, NULL, NULL, '1.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, NULL, NULL, 'confirmed', 0, 8, 8, 1, 0, 0, 15, 1, NULL, NULL, NULL, 'd9572913836e2249e5679562ca277ead', '', '2018-06-04 15:04:28', '2018-06-04 15:04:28');

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders_dist`
--

CREATE TABLE `customer_orders_dist` (
  `id` int(10) UNSIGNED NOT NULL,
  `sequence_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `document_prefix` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `document_reference` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_via` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'webshop',
  `date_created` datetime NOT NULL,
  `date_paid` datetime NOT NULL,
  `delivery_date` date DEFAULT NULL,
  `total` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer` text COLLATE utf8mb4_unicode_ci,
  `customer_note` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `notes_to_customer` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `production_at` datetime DEFAULT NULL,
  `production_sheet_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_orders_dist`
--

INSERT INTO `customer_orders_dist` (`id`, `sequence_id`, `customer_id`, `document_prefix`, `document_id`, `document_reference`, `reference`, `created_via`, `date_created`, `date_paid`, `delivery_date`, `total`, `customer`, `customer_note`, `notes`, `notes_to_customer`, `status`, `production_at`, `production_sheet_id`, `created_at`, `updated_at`) VALUES
(4, NULL, NULL, NULL, 0, NULL, '4964', 'webshop', '2018-02-25 14:28:13', '2018-02-25 14:29:16', NULL, '18.22', 'a:10:{s:10:"first_name";s:10:"Inmaculada";s:9:"last_name";s:15:"Andrades Pineda";s:7:"company";s:0:"";s:9:"address_1";s:19:"Calle Aldebarán 86";s:9:"address_2";s:0:"";s:4:"city";s:10:"Almensilla";s:5:"state";s:2:"SE";s:8:"postcode";s:5:"41111";s:7:"country";s:2:"ES";s:5:"phone";s:9:"689374248";}', '', NULL, NULL, NULL, NULL, 5, '2018-02-28 19:57:01', '2018-03-05 11:01:17'),
(5, NULL, NULL, NULL, 0, NULL, '4968', 'webshop', '2018-03-01 10:44:21', '2018-03-01 10:47:35', NULL, '33.12', 'a:10:{s:10:"first_name";s:6:"Teresa";s:9:"last_name";s:24:"Monreal Pérez de Ciriza";s:7:"company";s:0:"";s:9:"address_1";s:31:"Plaza San Miguel, nº 1 - 3º A";s:9:"address_2";s:0:"";s:4:"city";s:6:"Noáin";s:5:"state";s:2:"NA";s:8:"postcode";s:5:"31110";s:7:"country";s:2:"ES";s:5:"phone";s:9:"696137280";}', 'Por favor mandarme mensaja para saber la hora de llegada del pedido', NULL, NULL, NULL, NULL, 4, '2018-03-02 13:44:41', '2018-03-02 13:44:41'),
(6, NULL, NULL, NULL, 0, NULL, '4965', 'webshop', '2018-02-26 09:36:38', '2018-02-26 09:38:41', NULL, '39.92', 'a:11:{s:10:"first_name";s:6:"amparo";s:9:"last_name";s:17:"de luque gonzalez";s:7:"company";s:24:"amparo de luque gonzalez";s:9:"address_1";s:34:"marques de paradas 21, local bajo.";s:9:"address_2";s:22:"Clinica Dres. de Luque";s:4:"city";s:7:"sevilla";s:5:"state";s:2:"SE";s:8:"postcode";s:5:"41001";s:7:"country";s:2:"ES";s:5:"phone";s:9:"607946408";s:10:"state_name";s:7:"Sevilla";}', '', NULL, NULL, NULL, NULL, 5, '2018-03-02 19:35:44', '2018-03-05 10:58:02'),
(7, NULL, NULL, NULL, 0, NULL, '4963', 'webshop', '2018-02-24 18:05:43', '2018-02-24 18:07:21', NULL, '34.72', 'a:11:{s:10:"first_name";s:8:"Macarena";s:9:"last_name";s:14:"Palma Martinez";s:7:"company";s:0:"";s:9:"address_1";s:21:"Felipe checa 36 local";s:9:"address_2";s:18:"Estudio de pilates";s:4:"city";s:7:"Badajoz";s:5:"state";s:2:"BA";s:8:"postcode";s:5:"06001";s:7:"country";s:2:"ES";s:5:"phone";s:9:"609451534";s:10:"state_name";s:7:"Badajoz";}', 'por favor si la entrega es el miércoles, el horario es de 12h a 13h o de 18h a 21h, en otro horario tengo cerrado, gracias.', NULL, NULL, NULL, NULL, 6, '2018-03-03 18:04:27', '2018-03-03 18:04:27'),
(11, NULL, NULL, NULL, 0, NULL, '4969', 'webshop', '2018-03-04 21:51:04', '2018-03-04 21:51:55', NULL, '31.81', 'a:11:{s:10:"first_name";s:9:"Ana Maria";s:9:"last_name";s:13:"Moya Gonzalez";s:7:"company";s:0:"";s:9:"address_1";s:43:"Avenida de las Ciencias 10, portal 3 - 7ºB";s:9:"address_2";s:0:"";s:4:"city";s:7:"Sevilla";s:5:"state";s:2:"SE";s:8:"postcode";s:5:"41020";s:7:"country";s:2:"ES";s:5:"phone";s:11:"622 058 661";s:10:"state_name";s:7:"Sevilla";}', '', NULL, NULL, NULL, NULL, 8, '2018-03-07 10:52:39', '2018-03-07 10:52:39'),
(12, NULL, NULL, NULL, 0, NULL, '4962', 'webshop', '2018-02-19 09:43:07', '2018-02-19 09:47:08', NULL, '42.02', 'a:11:{s:10:"first_name";s:7:"Manuela";s:9:"last_name";s:13:"Lucena Campos";s:7:"company";s:0:"";s:9:"address_1";s:46:"Calle Cádiz Salvatierra, portal número 5, 2B";s:9:"address_2";s:0:"";s:4:"city";s:6:"Huelva";s:5:"state";s:1:"H";s:8:"postcode";s:5:"21003";s:7:"country";s:2:"ES";s:5:"phone";s:9:"649413888";s:10:"state_name";s:6:"Huelva";}', '', NULL, NULL, NULL, NULL, 9, '2018-03-07 15:35:39', '2018-03-07 15:35:39'),
(13, NULL, NULL, NULL, 0, NULL, '4961', 'webshop', '2018-02-18 20:33:52', '2018-02-18 20:35:47', NULL, '32.20', 'a:11:{s:10:"first_name";s:5:"MARIA";s:9:"last_name";s:19:"RODRIGUEZ FERNANDEZ";s:7:"company";s:12:"IES BITACORA";s:9:"address_1";s:25:"AVENIDA DE LA MARINA, S/N";s:9:"address_2";s:0:"";s:4:"city";s:12:"PUNTA UMBRIA";s:5:"state";s:1:"H";s:8:"postcode";s:5:"21100";s:7:"country";s:2:"ES";s:5:"phone";s:9:"662432036";s:10:"state_name";s:6:"Huelva";}', '', NULL, NULL, NULL, NULL, 9, '2018-03-07 16:10:35', '2018-03-07 16:10:35'),
(14, NULL, NULL, NULL, 0, NULL, '5000', 'webshop', '2018-04-08 18:50:20', '2018-04-08 18:52:42', NULL, '37.82', 'a:11:{s:10:"first_name";s:11:"jose manuel";s:9:"last_name";s:14:"duran trujillo";s:7:"company";s:0:"";s:9:"address_1";s:15:"calle cordoba 2";s:9:"address_2";s:0:"";s:4:"city";s:20:"cazalla de la sierra";s:5:"state";s:2:"SE";s:8:"postcode";s:5:"41370";s:7:"country";s:2:"ES";s:5:"phone";s:9:"680141192";s:10:"state_name";s:7:"Sevilla";}', '', NULL, NULL, NULL, NULL, 13, '2018-04-09 08:51:32', '2018-04-09 08:51:32');

-- --------------------------------------------------------

--
-- Table structure for table `customer_order_lines`
--

CREATE TABLE `customer_order_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `line_sort_order` int(11) DEFAULT NULL,
  `line_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `combination_id` int(10) UNSIGNED DEFAULT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(20,6) NOT NULL,
  `measure_unit_id` int(10) UNSIGNED NOT NULL,
  `cost_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_customer_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_customer_final_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_final_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_final_price_tax_inc` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `sales_equalization` tinyint(4) NOT NULL DEFAULT '0',
  `discount_percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `discount_amount_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `discount_amount_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `tax_percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `commission_percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `locked` tinyint(4) NOT NULL DEFAULT '0',
  `customer_order_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `sales_rep_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_order_lines`
--

INSERT INTO `customer_order_lines` (`id`, `line_sort_order`, `line_type`, `product_id`, `combination_id`, `reference`, `name`, `quantity`, `measure_unit_id`, `cost_price`, `unit_price`, `unit_customer_price`, `unit_customer_final_price`, `unit_final_price`, `unit_final_price_tax_inc`, `sales_equalization`, `discount_percent`, `discount_amount_tax_incl`, `discount_amount_tax_excl`, `total_tax_incl`, `total_tax_excl`, `tax_percent`, `commission_percent`, `notes`, `locked`, `customer_order_id`, `tax_id`, `sales_rep_id`, `created_at`, `updated_at`) VALUES
(1, 10, 'product', 5, 0, '4499', 'Bocadillo de Txorizo', '1.000000', 0, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', 1, '0.000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000', '0.000', NULL, 0, 1, 2, NULL, '2018-05-29 09:42:09', '2018-06-08 08:44:37'),
(2, 20, 'product', 12, 0, '4001', 'Pan de Arroz con masa madre ECO SG pack 4 uds', '1.000000', 0, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', 1, '0.000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000', '0.000', NULL, 0, 1, 2, NULL, '2018-05-29 09:42:33', '2018-06-08 08:44:37'),
(3, 30, 'product', 21, 0, '414', 'Hachicoria', '1.000000', 1, '45.000000', '100.000000', '80.000000', '88.000000', '80.000000', '88.000000', 1, '0.000', '0.000000', '0.000000', '89.120000', '80.000000', '10.000', '0.000', NULL, 0, 1, 2, NULL, '2018-05-30 07:06:00', '2018-06-08 08:45:02'),
(4, 40, 'product', 21, 0, '414', 'Hachicoria', '1.000000', 1, '45.000000', '100.000000', '90.000000', '90.000000', '85.500000', '94.050000', 1, '5.000', '0.000000', '0.000000', '95.250000', '85.500000', '10.000', '0.000', NULL, 0, 1, 2, NULL, '2018-06-07 10:04:18', '2018-06-08 08:45:02'),
(5, 50, 'product', 21, 0, '414', 'Hachicoria', '1.000000', 1, '45.000000', '100.000000', '90.000000', '50.000000', '45.000000', '49.500000', 1, '10.000', '0.000000', '0.000000', '50.130000', '45.000000', '10.000', '0.000', NULL, 0, 1, 2, NULL, '2018-06-07 10:06:04', '2018-06-07 10:06:05'),
(6, 10, 'product', 21, 0, '414', 'Hachicoria', '2.000000', 1, '45.000000', '100.000000', '100.000000', '110.000000', '91.000000', '100.100000', 1, '9.000', '0.000000', '0.000000', '200.200000', '182.000000', '10.000', '0.000', NULL, 0, 3, 2, NULL, '2018-06-21 06:26:01', '2018-06-21 16:31:20'),
(7, 20, 'product', 21, 0, '414', 'Hachicoria', '1.000000', 1, '45.000000', '100.000000', '100.000000', '110.000000', '100.000000', '110.000000', 1, '0.000', '0.000000', '0.000000', '110.000000', '100.000000', '10.000', '0.000', NULL, 0, 3, 2, NULL, '2018-06-21 06:28:26', '2018-06-21 16:31:20'),
(10, 30, 'product', 21, 0, '414', 'Hachicoria', '1.000000', 1, '45.000000', '100.000000', '100.000000', '110.000000', '100.000000', '110.000000', 0, '0.000', '0.000000', '0.000000', '110.000000', '100.000000', '10.000', '0.000', NULL, 0, 3, 2, NULL, '2018-06-21 06:55:41', '2018-06-21 16:31:08'),
(11, 40, 'product', 21, 0, '414', 'Hachicoria', '1.000000', 1, '45.000000', '100.000000', '100.000000', '110.000000', '100.000000', '110.000000', 0, '0.000', '0.000000', '0.000000', '110.000000', '100.000000', '10.000', '0.000', NULL, 0, 3, 2, NULL, '2018-06-24 12:50:45', '2018-06-24 12:50:45'),
(12, 10, 'product', 21, 0, '414', 'Hachicoria', '1.000000', 1, '45.000000', '100.000000', '90.000000', '90.000000', '81.000000', '89.100000', 1, '10.000', '0.000000', '0.000000', '90.230000', '81.000000', '10.000', '0.000', NULL, 0, 2, 2, NULL, '2018-06-25 11:01:44', '2018-06-25 11:01:44'),
(13, 20, 'product', 21, 0, '414', 'Hachicoria para empastes', '2.000000', 1, '45.000000', '100.000000', '90.000000', '90.000000', '81.000000', '89.100000', 1, '10.000', '0.000000', '0.000000', '180.470000', '162.000000', '10.000', '0.000', NULL, 0, 2, 2, NULL, '2018-06-25 11:03:40', '2018-06-27 11:48:45'),
(14, 10, 'product', 21, 0, '414', 'Hachicoria', '1.000000', 1, '45.000000', '100.000000', '90.000000', '90.000000', '90.000000', '99.000000', 1, '0.000', '0.000000', '0.000000', '99.000000', '90.000000', '10.000', '0.000', NULL, 0, 4, 2, 0, '2018-06-30 09:28:42', '2018-06-30 09:28:42'),
(15, 10, 'product', 21, 0, '414', 'Hachicoria', '1.000000', 1, '45.000000', '100.000000', '90.000000', '90.000000', '90.000000', '99.000000', 1, '0.000', '0.000000', '0.000000', '99.000000', '90.000000', '10.000', '0.000', NULL, 0, 4, 2, 0, '2018-06-30 09:30:44', '2018-06-30 09:30:44'),
(16, 10, 'product', 21, 0, '414', 'Hachicoria', '1.000000', 1, '45.000000', '100.000000', '90.000000', '90.000000', '90.000000', '99.000000', 1, '0.000', '0.000000', '0.000000', '99.000000', '90.000000', '10.000', '0.000', NULL, 0, 4, 2, 0, '2018-06-30 09:35:10', '2018-06-30 09:35:10'),
(17, 10, 'product', 21, 0, '414', 'Hachicoria', '1.000000', 1, '45.000000', '100.000000', '90.000000', '90.000000', '90.000000', '99.000000', 1, '0.000', '0.000000', '0.000000', '99.000000', '90.000000', '10.000', '0.000', NULL, 0, 4, 2, 0, '2018-06-30 09:36:39', '2018-06-30 09:36:39');

-- --------------------------------------------------------

--
-- Table structure for table `customer_order_lines_dist`
--

CREATE TABLE `customer_order_lines_dist` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `woo_product_id` int(10) UNSIGNED DEFAULT NULL,
  `woo_variation_id` int(10) UNSIGNED DEFAULT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(20,6) NOT NULL,
  `customer_order_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_order_lines_dist`
--

INSERT INTO `customer_order_lines_dist` (`id`, `product_id`, `woo_product_id`, `woo_variation_id`, `reference`, `name`, `quantity`, `customer_order_id`, `created_at`, `updated_at`) VALUES
(16, 9, 4539, 0, '1016', 'Pan integral de centeno 100% con copos de centeno ECO 900g', '4.000000', 4, '2018-02-28 19:57:01', '2018-02-28 19:57:01'),
(17, 7, 2929, 0, '4022', 'Pan integral de sarraceno con hierbas provenzales ECO SG 500G', '7.000000', 4, '2018-02-28 19:45:22', '2018-02-28 19:45:22'),
(18, 8, 212, 0, '4021', 'Pan integral de Sarraceno con semillas de sésamo ECO SG 500G', '3.000000', 5, '2018-03-02 13:44:41', '2018-03-02 13:44:41'),
(19, 10, 2930, 0, '3023', 'Bizcocho casero de manzana y arándanos plum SG', '1.000000', 5, '2018-03-02 13:44:41', '2018-03-02 13:44:41'),
(20, 11, 2931, 0, '3030', 'Bizcocho vegano de zanahoria, manzana y nueces plum SG', '1.000000', 5, '2018-03-02 13:44:41', '2018-03-02 13:44:41'),
(21, 6, 4500, 0, '4003', 'Mollete Artesano ECO SG pack 4 uds', '1.000000', 5, '2018-03-02 13:44:41', '2018-03-02 13:44:41'),
(22, 8, 212, 0, '4021', 'Pan integral de Sarraceno con semillas de sésamo ECO SG 500G', '3.000000', 6, '2018-03-02 19:35:44', '2018-03-02 19:35:44'),
(23, 6, 4500, 0, '4003', 'Mollete Artesano ECO SG pack 4 uds', '5.000000', 6, '2018-03-02 19:35:44', '2018-03-02 19:35:44'),
(24, 12, 4506, 0, '4001', 'Pan de Arroz con masa madre ECO SG pack 4 uds', '3.000000', 7, '2018-03-03 18:04:27', '2018-03-03 18:04:28'),
(25, 8, 212, 0, '4021', 'Pan integral de Sarraceno con semillas de sésamo ECO SG 500G', '3.000000', 7, '2018-03-03 18:04:28', '2018-03-03 18:04:28'),
(26, 13, 2926, 0, '4006', 'Barra de maiz ECO SG 270g', '1.000000', 7, '2018-03-03 18:04:28', '2018-03-03 18:04:28'),
(27, 6, 4500, 0, '4003', 'Mollete Artesano ECO SG pack 4 uds', '7.000000', 8, '2018-03-07 10:31:51', '2018-03-07 10:31:51'),
(32, 6, 4500, 0, '4003', 'Mollete Artesano ECO SG pack 4 uds', '7.000000', 11, '2018-03-07 10:52:39', '2018-03-07 10:52:39'),
(33, 15, 197, 0, '2011', 'Regañás integrales de 100% espelta ECO 125 g', '1.000000', 11, '2018-03-07 10:52:39', '2018-03-07 10:52:39'),
(34, 7, 2929, 0, '4022', 'Pan integral de sarraceno con hierbas provenzales ECO SG 500G', '3.000000', 12, '2018-03-07 15:35:39', '2018-03-07 15:35:40'),
(35, 6, 4500, 0, '4003', 'Mollete Artesano ECO SG pack 4 uds', '3.000000', 12, '2018-03-07 15:35:40', '2018-03-07 15:35:40'),
(36, 12, 4506, 0, '4001', 'Pan de Arroz con masa madre ECO SG pack 4 uds', '1.000000', 12, '2018-03-07 15:35:40', '2018-03-07 15:35:40'),
(37, 8, 212, 0, '4021', 'Pan integral de Sarraceno con semillas de sésamo ECO SG 500G', '1.000000', 12, '2018-03-07 15:35:40', '2018-03-07 15:35:40'),
(38, 13, 2926, 0, '4006', 'Barra de maiz ECO SG 270g', '3.000000', 13, '2018-03-07 16:10:35', '2018-03-07 16:10:35'),
(39, 6, 4500, 0, '4003', 'Mollete Artesano ECO SG pack 4 uds', '3.000000', 13, '2018-03-07 16:10:35', '2018-03-07 16:10:35'),
(40, 19, 4509, 0, '4012', 'Pan de Molde de Maíz ECO SG 500g', '2.000000', 13, '2018-03-07 16:10:35', '2018-03-07 16:10:35'),
(41, 20, 4527, 0, '1014', 'Pan integral de trigo con semillas de la tierra ECO 900g.', '9.000000', 14, '2018-04-09 08:51:32', '2018-04-09 08:51:32');

-- --------------------------------------------------------

--
-- Table structure for table `customer_order_line_taxes`
--

CREATE TABLE `customer_order_line_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_rule_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taxable_base` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_line_tax` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `customer_order_line_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `tax_rule_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_order_line_taxes`
--

INSERT INTO `customer_order_line_taxes` (`id`, `name`, `tax_rule_type`, `taxable_base`, `percent`, `amount`, `total_line_tax`, `position`, `customer_order_line_id`, `tax_id`, `tax_rule_id`, `created_at`, `updated_at`) VALUES
(1, 'IVA Reducido | IVA Reducido (10.0%)', 'sales', '0.000000', '10.000', '0.000000', '0.000000', 10, 1, 2, 3, '2018-05-29 09:42:10', '2018-05-29 09:42:10'),
(2, 'IVA Reducido | Recargo de Equivalencia (1.4%)', 'sales_equalization', '0.000000', '1.400', '0.000000', '0.000000', 20, 1, 2, 4, '2018-05-29 09:42:10', '2018-05-29 09:42:10'),
(3, 'IVA Reducido | IVA Reducido (10.0%)', 'sales', '0.000000', '10.000', '0.000000', '0.000000', 10, 2, 2, 3, '2018-05-29 09:42:33', '2018-05-29 09:42:33'),
(4, 'IVA Reducido | Recargo de Equivalencia (1.4%)', 'sales_equalization', '0.000000', '1.400', '0.000000', '0.000000', 20, 2, 2, 4, '2018-05-29 09:42:33', '2018-05-29 09:42:33'),
(5, 'IVA Reducido | IVA Reducido (10.0%)', 'sales', '80.000000', '10.000', '0.000000', '8.000000', 10, 3, 2, 3, '2018-05-30 07:06:01', '2018-05-30 07:06:01'),
(6, 'IVA Reducido | Recargo de Equivalencia (1.4%)', 'sales_equalization', '80.000000', '1.400', '0.000000', '1.120000', 20, 3, 2, 4, '2018-05-30 07:06:01', '2018-05-30 07:06:01'),
(7, 'IVA Reducido | IVA Reducido (10.0%)', 'sales', '85.500000', '10.000', '0.000000', '8.550000', 10, 4, 2, 3, '2018-06-07 10:04:19', '2018-06-07 10:04:19'),
(8, 'IVA Reducido | Recargo de Equivalencia (1.4%)', 'sales_equalization', '85.500000', '1.400', '0.000000', '1.200000', 20, 4, 2, 4, '2018-06-07 10:04:19', '2018-06-07 10:04:20'),
(9, 'IVA Reducido | IVA Reducido (10.0%)', 'sales', '45.000000', '10.000', '0.000000', '4.500000', 10, 5, 2, 3, '2018-06-07 10:06:05', '2018-06-07 10:06:05'),
(10, 'IVA Reducido | Recargo de Equivalencia (1.4%)', 'sales_equalization', '45.000000', '1.400', '0.000000', '0.630000', 20, 5, 2, 4, '2018-06-07 10:06:05', '2018-06-07 10:06:05'),
(11, 'IVA Reducido | IVA Reducido (10.0%)', 'sales', '182.000000', '10.000', '0.000000', '18.200000', 10, 6, 2, 3, '2018-06-21 06:26:01', '2018-06-21 06:26:01'),
(12, 'IVA Reducido | IVA Reducido (10.0%)', 'sales', '100.000000', '10.000', '0.000000', '10.000000', 10, 7, 2, 3, '2018-06-21 06:28:27', '2018-06-21 06:28:27'),
(15, 'IVA Reducido | IVA Reducido (10.0%)', 'sales', '100.000000', '10.000', '0.000000', '10.000000', 10, 10, 2, 3, '2018-06-21 06:55:41', '2018-06-21 06:55:41'),
(16, 'IVA Reducido | IVA Reducido (10.0%)', 'sales', '100.000000', '10.000', '0.000000', '10.000000', 10, 11, 2, 3, '2018-06-24 12:50:45', '2018-06-24 12:50:45'),
(17, 'IVA Reducido | IVA Reducido (10.0%)', 'sales', '81.000000', '10.000', '0.000000', '8.100000', 10, 12, 2, 3, '2018-06-25 11:01:44', '2018-06-25 11:01:44'),
(18, 'IVA Reducido | Recargo de Equivalencia (1.4%)', 'sales_equalization', '81.000000', '1.400', '0.000000', '1.130000', 20, 12, 2, 4, '2018-06-25 11:01:44', '2018-06-25 11:01:44'),
(23, 'IVA Reducido | IVA Reducido (10.0%)', 'sales', '162.000000', '10.000', '0.000000', '16.200000', 10, 13, 2, 3, '2018-06-27 11:48:45', '2018-06-27 11:48:45'),
(24, 'IVA Reducido | Recargo de Equivalencia (1.4%)', 'sales_equalization', '162.000000', '1.400', '0.000000', '2.270000', 20, 13, 2, 4, '2018-06-27 11:48:45', '2018-06-27 11:48:45');

-- --------------------------------------------------------

--
-- Table structure for table `customer_users`
--

CREATE TABLE `customer_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `language_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_users`
--

INSERT INTO `customer_users` (`id`, `name`, `email`, `password`, `firstname`, `lastname`, `remember_token`, `active`, `language_id`, `customer_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'hellocusto', 'hello@customer.com', '$2y$10$c5btGiUimhywXv6EMTMiEejYaf7cocXZuY7.Enx1/fmqVU3kk8uIy', 'Markus', 'Queridiam', 'MGe3KYllezEvvkI1KDF1FFAKCjSq0RN1RrI8TXGQa9XxoeD4Ju9rskWgvNHF', 1, 2, 9, '2018-01-18 12:14:22', '2018-01-18 12:14:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fsx_loggers`
--

CREATE TABLE `fsx_loggers` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL,
  `secs_added` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fsx_loggers`
--

INSERT INTO `fsx_loggers` (`id`, `type`, `message`, `date_added`, `secs_added`, `created_at`, `updated_at`) VALUES
(1, 'INFO', 'LOG reiniciado', '2018-05-16 13:54:49', '564767', NULL, NULL),
(2, 'INFO', 'LOG iniciado', '2018-05-16 13:54:49', '626743', NULL, NULL),
(3, 'INFO', 'Hola', '2018-05-16 13:54:49', '657536', NULL, NULL),
(4, 'INFO', 'LOG terminado', '2018-05-16 13:54:49', '719373', NULL, NULL),
(5, 'ERROR', 'Hola', '2018-05-16 13:54:49', '781560', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(10) UNSIGNED NOT NULL,
  `caption` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `imageable_id` int(10) UNSIGNED NOT NULL,
  `imageable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `caption`, `extension`, `position`, `is_featured`, `active`, `imageable_id`, `imageable_type`, `created_at`, `updated_at`) VALUES
(5, 'All of Us', 'jpg', 0, 0, 1, 21, 'App\\Product', '2018-05-27 17:41:27', '2018-05-30 17:23:27'),
(6, 'WawA', 'jpg', 0, 0, 1, 21, 'App\\Product', '2018-05-27 17:41:41', '2018-05-30 17:22:12'),
(7, 'Flower Attac', 'jpeg', 0, 1, 1, 21, 'App\\Product', '2018-05-30 17:23:27', '2018-05-30 17:23:27');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_format_lite` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_format_full` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_format_lite_view` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_format_full_view` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `iso_code`, `language_code`, `date_format_lite`, `date_format_full`, `date_format_lite_view`, `date_format_full_view`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'English', 'en', 'en-en', 'm/j/Y', 'm/j/Y H:i:s', 'm/j/Y', 'm/j/Y H:i:s', 1, '2017-09-20 16:39:27', '2017-09-20 16:39:27', NULL),
(2, 'Español', 'es', 'es-es', 'd/m/Y', 'd/m/Y H:i:s', 'd/m/yy', 'd/m/yy H:i:s', 1, '2017-09-20 16:39:28', '2017-09-26 10:24:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE `manufacturers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `measure_units`
--

CREATE TABLE `measure_units` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Quantity',
  `sign` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimalPlaces` tinyint(4) NOT NULL DEFAULT '2',
  `conversion_rate` decimal(20,6) NOT NULL DEFAULT '1.000000',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `measure_units`
--

INSERT INTO `measure_units` (`id`, `type`, `sign`, `name`, `decimalPlaces`, `conversion_rate`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Quantity', 'ud.', 'Unidad(es)', 2, '1.000000', 1, NULL, '2018-06-19 18:55:30', NULL),
(2, 'Dry Volume', 'cda rasa', 'Cucharada rasa', 0, '1.000000', 1, '2018-02-21 15:05:01', '2018-02-21 15:05:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2013_11_15_074910_create_sequences_table', 1),
(2, '2013_11_15_100934_create_configurations_table', 1),
(3, '2014_10_12_000000_create_users_table', 1),
(4, '2014_10_12_100000_create_password_resets_table', 1),
(5, '2014_11_23_100432_create_currencies_table', 1),
(6, '2015_01_04_072748_create_languages_table', 1),
(7, '2018_02_10_100432_create_measure_units_table', 2),
(8, '2018_02_10_100657_create_work_centers_table', 2),
(10, '2018_02_15_085643_create_product_b_o_ms_table', 4),
(11, '2018_02_15_090526_create_product_b_o_m_lines_table', 4),
(14, '2018_02_15_090948_create_b_o_m_items_table', 5),
(20, '2018_02_27_120350_create_production_sheets_table', 7),
(24, '2020_02_27_120350_update_production_sheets_table', 10),
(25, '2018_02_27_120210_create_production_orders_table', 11),
(26, '2018_02_27_120244_create_production_order_lines_table', 11),
(33, '2017_08_16_182147_create_countries_table', 13),
(34, '2017_08_16_182217_create_states_table', 13),
(35, '2013_08_04_061308_create_taxes_table', 14),
(36, '2013_08_12_093955_create_addresses_table', 14),
(37, '2013_08_12_094033_create_customers_table', 14),
(38, '2014_11_23_100509_create_customer_groups_table', 14),
(39, '2014_11_23_100540_create_payment_methods_table', 14),
(40, '2017_08_07_123701_create_tax_rules_table', 14),
(45, '2014_11_23_100356_create_sales_reps_table', 15),
(46, '2014_11_23_100657_create_warehouses_table', 15),
(47, '2014_11_23_100731_create_carriers_table', 15),
(48, '2015_02_23_080022_create_companies_table', 16),
(51, '2014_11_23_091806_create_price_lists_table', 19),
(52, '2015_05_29_073823_create_price_list_lines_table', 19),
(53, '2017_08_22_121018_create_images_table', 19),
(54, '2013_08_07_104152_create_categories_table', 20),
(56, '2018_04_14_100934_create_fsx_loggers_table', 21),
(58, '2013_08_07_094826_create_combinations_table', 22),
(59, '2014_11_23_100836_create_bank_accounts_table', 22),
(60, '2014_12_13_053846_create_customer_invoices_table', 22),
(61, '2014_12_13_054830_create_customer_invoice_lines_table', 22),
(62, '2015_02_19_105939_create_templates_table', 22),
(63, '2015_02_19_165649_create_manufacturers_table', 22),
(64, '2015_03_23_154330_create_option_groups_table', 22),
(65, '2015_03_23_154423_create_options_table', 22),
(66, '2015_03_24_099826_create_combination_option_table', 22),
(67, '2015_03_25_173101_create_stock_movements_table', 22),
(68, '2015_03_26_073823_create_product_warehouse_table', 22),
(69, '2015_03_27_150000_create_combination_warehouse_table', 22),
(70, '2015_04_12_103953_create_contact_messages_table', 22),
(71, '2015_06_16_093819_create_payments_table', 22),
(72, '2017_08_01_133642_create_customer_invoice_line_taxes_table', 22),
(73, '2017_08_27_134548_create_combination_image_table', 22),
(74, '2017_09_29_180827_create_stock_counts_table', 22),
(75, '2017_09_29_181055_create_stock_count_lines_table', 22),
(76, '2017_09_29_181439_create_currency_conversion_rates_table', 22),
(77, '2017_12_07_120400_create_woo_orders_table', 22),
(78, '2017_12_18_073823_create_parent_child_table', 22),
(79, '2018_01_18_094239_create_customer_users_table', 22),
(83, '2013_08_07_094814_create_products_table', 23),
(84, '2018_02_27_120305_create_customer_orders_table', 23),
(85, '2018_02_27_120331_create_customer_order_lines_table', 23),
(86, '2018_02_27_120344_create_customer_order_line_taxes_table', 23),
(89, '2014_11_23_100394_create_suppliers_table', 25),
(90, '2018_05_25_114319_create_activity_loggers_table', 26),
(91, '2018_05_25_124420_create_activity_logger_lines_table', 26);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `webshop_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_group_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `option_groups`
--

CREATE TABLE `option_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `public_name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `webshop_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parent_child`
--

CREATE TABLE `parent_child` (
  `id` int(10) UNSIGNED NOT NULL,
  `parentable_id` int(11) NOT NULL,
  `parentable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `childable_id` int(11) NOT NULL,
  `childable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date NOT NULL,
  `payment_date` date DEFAULT NULL,
  `amount` decimal(20,6) NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `currency_conversion_rate` decimal(20,6) NOT NULL DEFAULT '1.000000',
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `paymentable_id` int(11) NOT NULL,
  `paymentable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_reference` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paymentorable_id` int(11) NOT NULL,
  `paymentorable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deadlines` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_is_cash` tinyint(4) NOT NULL DEFAULT '0',
  `auto_direct_debit` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `deadlines`, `payment_is_cash`, `auto_direct_debit`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '30/60/90', 'a:3:{i:0;a:2:{s:4:"slot";s:2:"30";s:10:"percentage";s:2:"33";}i:1;a:2:{s:4:"slot";s:2:"60";s:10:"percentage";s:2:"33";}i:2;a:2:{s:4:"slot";s:2:"90";s:10:"percentage";s:2:"34";}}', 0, 0, 1, '2018-05-29 09:36:46', '2018-05-29 09:36:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `price_lists`
--

CREATE TABLE `price_lists` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_is_tax_inc` tinyint(4) NOT NULL DEFAULT '0',
  `amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `currency_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `price_lists`
--

INSERT INTO `price_lists` (`id`, `name`, `type`, `price_is_tax_inc`, `amount`, `currency_id`, `created_at`, `updated_at`) VALUES
(1, 'TIENDAS', 'price', 0, '0.000000', 15, '2018-05-26 10:51:32', '2018-07-04 10:07:51'),
(2, 'DISTRIBUIDOR', 'price', 0, '0.000000', 15, '2018-05-27 18:22:06', '2018-07-04 10:09:03'),
(3, 'CONSUMIDOR FINAL', 'price', 1, '0.000000', 15, '2018-05-28 06:56:05', '2018-07-04 10:09:43'),
(5, 'TIENDA CON RE', 'price', 0, '0.000000', 15, '2018-06-10 07:52:12', '2018-07-06 11:07:08');

-- --------------------------------------------------------

--
-- Table structure for table `price_list_lines`
--

CREATE TABLE `price_list_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `price_list_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `price_list_lines`
--

INSERT INTO `price_list_lines` (`id`, `product_id`, `price`, `price_list_id`, `created_at`, `updated_at`) VALUES
(1, 1, '0.000000', 1, '2018-05-26 10:51:32', '2018-05-26 10:51:32'),
(2, 2, '0.000000', 1, '2018-05-26 10:51:32', '2018-05-26 10:51:32'),
(3, 3, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(4, 4, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(5, 5, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(6, 6, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(7, 7, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(8, 8, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(9, 9, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(10, 10, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(11, 11, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(12, 12, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(13, 13, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(14, 15, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(15, 16, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(16, 17, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(17, 18, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(18, 19, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(19, 20, '0.000000', 1, '2018-05-26 10:51:33', '2018-05-26 10:51:33'),
(20, 21, '90.000000', 1, '2018-05-26 16:47:19', '2018-05-28 06:42:52'),
(21, 1, '0.000000', 2, '2018-05-27 18:22:06', '2018-05-27 18:22:06'),
(22, 2, '0.000000', 2, '2018-05-27 18:22:06', '2018-05-27 18:22:06'),
(23, 3, '0.100000', 2, '2018-05-27 18:22:06', '2018-06-09 13:34:42'),
(24, 4, '0.000000', 2, '2018-05-27 18:22:06', '2018-05-27 18:22:06'),
(25, 5, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(26, 6, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(27, 7, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(28, 8, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(29, 9, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(30, 10, '17.000000', 2, '2018-05-27 18:22:07', '2018-06-09 13:23:26'),
(31, 11, '0.000000', 2, '2018-05-27 18:22:07', '2018-06-10 07:34:56'),
(32, 12, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(33, 13, '33.500000', 2, '2018-05-27 18:22:07', '2018-06-09 12:27:17'),
(34, 15, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(35, 16, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(36, 17, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(37, 18, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(38, 19, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(39, 20, '0.000000', 2, '2018-05-27 18:22:07', '2018-05-27 18:22:07'),
(41, 21, '80.000000', 22, '2018-05-27 19:23:43', '2018-05-28 06:55:19'),
(62, 22, '99.000000', 1, '2018-05-31 11:45:41', '2018-05-31 11:45:41'),
(63, 22, '123.123400', 2, '2018-05-31 11:45:41', '2018-06-09 12:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `production_orders`
--

CREATE TABLE `production_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `sequence_id` int(10) UNSIGNED DEFAULT NULL,
  `document_prefix` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `document_reference` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_via` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'manual',
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'released',
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `combination_id` int(10) UNSIGNED DEFAULT NULL,
  `product_reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `planned_quantity` decimal(20,6) NOT NULL,
  `product_bom_id` int(10) UNSIGNED DEFAULT NULL,
  `due_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `work_center_id` int(10) UNSIGNED DEFAULT NULL,
  `warehouse_id` int(10) UNSIGNED DEFAULT NULL,
  `production_sheet_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_orders`
--

INSERT INTO `production_orders` (`id`, `sequence_id`, `document_prefix`, `document_id`, `document_reference`, `reference`, `created_via`, `status`, `product_id`, `combination_id`, `product_reference`, `product_name`, `planned_quantity`, `product_bom_id`, `due_date`, `notes`, `work_center_id`, `warehouse_id`, `production_sheet_id`, `created_at`, `updated_at`) VALUES
(25, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 6, NULL, '4003', 'Mollete Artesano ECO SG pack 4 uds', '5.000000', 1, '2018-03-03', NULL, 2, NULL, 1, '2018-03-01 21:28:30', '2018-03-01 21:28:30'),
(26, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 7, NULL, '4022', 'Pan integral de sarraceno con hierbas provenzales ECO SG 500G', '8.000000', 1, '2018-03-03', NULL, 2, NULL, 1, '2018-03-01 21:28:30', '2018-03-01 21:28:30'),
(27, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 8, NULL, '4021', 'Pan integral de Sarraceno con semillas de sésamo ECO SG 500G', '1.000000', 1, '2018-03-03', NULL, 2, NULL, 1, '2018-03-01 21:28:30', '2018-03-01 21:28:30'),
(28, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 9, NULL, '1016', 'Pan integral de centeno 100% con copos de centeno ECO 900g', '4.000000', 1, '2018-03-03', NULL, 2, NULL, 1, '2018-03-01 21:28:30', '2018-03-01 21:28:30'),
(35, NULL, NULL, 0, NULL, NULL, 'manual', 'released', 1, NULL, 'FAGESF', 'Fagodio Esforulante', '4.000000', 2, '2018-03-06', NULL, 2, NULL, 6, '2018-03-03 18:20:30', '2018-03-05 20:50:57'),
(36, NULL, NULL, 0, NULL, NULL, 'manual', 'released', 1, NULL, 'FAGESF', 'Fagodio Esforulante', '10.000000', 2, '2018-03-07', NULL, 0, NULL, 7, '2018-03-03 18:22:24', '2018-03-03 18:22:24'),
(38, NULL, NULL, 0, NULL, NULL, 'manual', 'released', 1, NULL, 'FAGESF', 'Fagodio Esforulante', '4.000000', 2, '2018-03-21', 'Notas Culo', 0, NULL, 5, '2018-03-04 10:13:26', '2018-03-05 14:44:43'),
(43, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 8, NULL, '4021', 'Pan integral de Sarraceno con semillas de sésamo ECO SG 500G', '3.000000', 1, '2018-03-06', NULL, 2, NULL, 6, '2018-03-05 19:39:59', '2018-03-05 19:39:59'),
(44, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 13, NULL, '4006', 'Barra de maiz ECO SG 270g', '1.000000', 1, '2018-03-06', NULL, 2, NULL, 6, '2018-03-05 19:39:59', '2018-03-05 19:39:59'),
(45, NULL, NULL, 0, NULL, NULL, 'manual', 'released', 12, NULL, '4001', 'Pan de Arroz con masa madre ECO SG pack 4 uds', '9.000000', 2, '2018-03-06', NULL, 1, NULL, 6, '2018-03-05 20:55:44', '2018-03-05 20:55:44'),
(47, NULL, NULL, 0, NULL, NULL, 'manual', 'released', 10, NULL, '3023', 'Bizcocho casero de manzana y arándanos plum SG', '1.000000', 0, '2018-03-05', NULL, NULL, NULL, 4, '2018-03-06 14:44:21', '2018-03-06 14:44:21'),
(48, NULL, NULL, 0, NULL, NULL, 'manual', 'released', 1, NULL, 'FAGESF', 'Fagodio Esforulante', '50.000000', 2, '2018-03-05', NULL, 2, NULL, 4, '2018-03-06 14:58:22', '2018-03-06 15:11:23'),
(49, NULL, NULL, 0, NULL, NULL, 'manual', 'released', 6, NULL, '4003', 'Mollete Artesano ECO SG pack 4 uds', '14.000000', 2, '2018-03-10', NULL, 1, NULL, 8, '2018-03-07 10:54:15', '2018-03-07 11:04:09'),
(54, NULL, NULL, 0, NULL, NULL, 'manual', 'released', 15, NULL, '2011', 'Regañás integrales de 100% espelta ECO 125 g', '38.000000', 2, '2018-03-08', NULL, 2, NULL, 9, '2018-03-07 16:20:59', '2018-03-07 17:15:14'),
(70, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 7, NULL, '4022', 'Pan integral de sarraceno con hierbas provenzales ECO SG 500G', '3.000000', 9, '2018-03-08', '', NULL, NULL, 9, '2018-03-07 17:24:20', '2018-03-07 17:24:20'),
(71, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 7, NULL, '4022', 'Pan integral de sarraceno con hierbas provenzales ECO SG 500G', '3.000000', 9, '2018-03-08', '', NULL, NULL, 9, '2018-03-07 17:24:20', '2018-03-07 17:24:20'),
(72, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 6, NULL, '4003', 'Mollete Artesano ECO SG pack 4 uds', '6.000000', 2, '2018-03-08', '', NULL, NULL, 9, '2018-03-07 17:24:21', '2018-03-07 17:24:21'),
(73, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 6, NULL, '4003', 'Mollete Artesano ECO SG pack 4 uds', '3.000000', 2, '2018-03-08', NULL, NULL, NULL, 9, '2018-03-07 17:24:21', '2018-04-09 12:45:30'),
(76, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 8, NULL, '4021', 'Pan integral de Sarraceno con semillas de sésamo ECO SG 500G', '1.000000', 2, '2018-03-08', '', NULL, NULL, 9, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(77, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 8, NULL, '4021', 'Pan integral de Sarraceno con semillas de sésamo ECO SG 500G', '1.000000', 2, '2018-03-08', '', NULL, NULL, 9, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(78, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 13, NULL, '4006', 'Barra de maiz ECO SG 270g', '3.000000', 10, '2018-03-08', '', 1, NULL, 9, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(79, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 13, NULL, '4006', 'Barra de maiz ECO SG 270g', '3.000000', 10, '2018-03-08', '', 1, NULL, 9, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(80, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 19, NULL, '4012', 'Pan de Molde de Maíz ECO SG 500g', '2.000000', 11, '2018-03-08', '', NULL, NULL, 9, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(81, NULL, NULL, 0, NULL, NULL, 'webshop', 'released', 19, NULL, '4012', 'Pan de Molde de Maíz ECO SG 500g', '2.000000', 11, '2018-03-08', '', NULL, NULL, 9, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(82, NULL, NULL, 0, NULL, NULL, 'manual', 'released', 20, NULL, '1014', 'Pan integral de trigo con semillas de la tierra ECO 900g.', '17.000000', 2, '2018-04-10', NULL, NULL, NULL, 13, '2018-04-09 09:54:45', '2018-04-09 10:26:32'),
(83, NULL, NULL, 0, NULL, NULL, 'manual', 'released', 1, NULL, 'FAGESF', 'Fagodio Esforulante', '15.000000', 2, '2018-04-10', NULL, 2, NULL, 13, '2018-04-09 10:29:54', '2018-04-09 10:29:54');

-- --------------------------------------------------------

--
-- Table structure for table `production_order_lines`
--

CREATE TABLE `production_order_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product',
  `product_id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_quantity` decimal(20,6) NOT NULL,
  `required_quantity` decimal(20,6) NOT NULL,
  `warehouse_id` int(10) UNSIGNED DEFAULT NULL,
  `production_order_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_order_lines`
--

INSERT INTO `production_order_lines` (`id`, `type`, `product_id`, `reference`, `name`, `base_quantity`, `required_quantity`, `warehouse_id`, `production_order_id`, `created_at`, `updated_at`) VALUES
(1, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '118.656000', NULL, 38, '2018-03-04 10:13:26', '2018-03-04 10:13:26'),
(2, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '0.494400', NULL, 0, '2018-03-05 17:37:13', '2018-03-05 17:37:13'),
(5, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '4.449600', NULL, 45, '2018-03-05 20:55:44', '2018-03-05 20:55:44'),
(6, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.180000', NULL, 45, '2018-03-05 20:55:44', '2018-03-05 20:55:44'),
(15, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '593.280000', NULL, 48, '2018-03-06 15:11:23', '2018-03-06 15:11:23'),
(16, 'product', 3, 'SAL-C', 'Sal', '0.000000', '24.000000', NULL, 48, '2018-03-06 15:11:23', '2018-03-06 15:11:23'),
(23, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '0.988800', NULL, 51, '2018-03-07 10:59:16', '2018-03-07 10:59:16'),
(24, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.040000', NULL, 51, '2018-03-07 10:59:16', '2018-03-07 10:59:16'),
(25, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '17.798400', NULL, 50, '2018-03-07 11:01:55', '2018-03-07 11:01:55'),
(26, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.720000', NULL, 50, '2018-03-07 11:01:56', '2018-03-07 11:01:56'),
(31, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '83.059200', NULL, 49, '2018-03-07 11:04:09', '2018-03-07 11:04:09'),
(32, 'product', 3, 'SAL-C', 'Sal', '0.000000', '3.360000', NULL, 49, '2018-03-07 11:04:09', '2018-03-07 11:04:09'),
(33, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '37.574400', NULL, 54, '2018-03-07 16:21:00', '2018-03-07 16:21:00'),
(35, 'product', 3, 'SAL-C', 'Sal', '0.000000', '1.520000', NULL, 54, '2018-03-07 16:21:00', '2018-03-07 16:21:00'),
(37, 'product', 16, '10500', 'Masa madre de trigo blanca', '0.000000', '1650.000000', NULL, 56, '2018-03-07 17:15:28', '2018-03-07 17:15:28'),
(38, 'product', 17, '11103', 'Fécula de patata SG', '0.000000', '1998.000000', NULL, 56, '2018-03-07 17:15:28', '2018-03-07 17:15:28'),
(39, 'product', 16, '10500', 'Masa madre de trigo blanca', '0.000000', '1650.000000', NULL, 57, '2018-03-07 17:15:28', '2018-03-07 17:15:28'),
(40, 'product', 18, '20061', 'Lino tostado y molido', '0.000000', '231.000000', NULL, 56, '2018-03-07 17:15:28', '2018-03-07 17:15:28'),
(41, 'product', 17, '11103', 'Fécula de patata SG', '0.000000', '1998.000000', NULL, 57, '2018-03-07 17:15:28', '2018-03-07 17:15:28'),
(42, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '35.596800', NULL, 58, '2018-03-07 17:15:28', '2018-03-07 17:15:28'),
(43, 'product', 18, '20061', 'Lino tostado y molido', '0.000000', '231.000000', NULL, 57, '2018-03-07 17:15:28', '2018-03-07 17:15:28'),
(44, 'product', 3, 'SAL-C', 'Sal', '0.000000', '1.440000', NULL, 58, '2018-03-07 17:15:28', '2018-03-07 17:15:28'),
(45, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '35.596800', NULL, 59, '2018-03-07 17:15:29', '2018-03-07 17:15:29'),
(46, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '0.494400', NULL, 60, '2018-03-07 17:15:29', '2018-03-07 17:15:29'),
(47, 'product', 3, 'SAL-C', 'Sal', '0.000000', '1.440000', NULL, 59, '2018-03-07 17:15:29', '2018-03-07 17:15:29'),
(48, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.020000', NULL, 60, '2018-03-07 17:15:29', '2018-03-07 17:15:29'),
(49, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '0.494400', NULL, 61, '2018-03-07 17:15:29', '2018-03-07 17:15:29'),
(50, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '0.494400', NULL, 62, '2018-03-07 17:15:29', '2018-03-07 17:15:29'),
(51, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.020000', NULL, 61, '2018-03-07 17:15:29', '2018-03-07 17:15:29'),
(52, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.020000', NULL, 62, '2018-03-07 17:15:29', '2018-03-07 17:15:29'),
(53, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '0.494400', NULL, 63, '2018-03-07 17:15:29', '2018-03-07 17:15:29'),
(54, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.020000', NULL, 63, '2018-03-07 17:15:29', '2018-03-07 17:15:30'),
(55, 'product', 16, '10500', 'Masa madre de trigo blanca', '0.000000', '1650.000000', NULL, 64, '2018-03-07 17:22:15', '2018-03-07 17:22:15'),
(56, 'product', 17, '11103', 'Fécula de patata SG', '0.000000', '1998.000000', NULL, 64, '2018-03-07 17:22:15', '2018-03-07 17:22:15'),
(57, 'product', 18, '20061', 'Lino tostado y molido', '0.000000', '231.000000', NULL, 64, '2018-03-07 17:22:15', '2018-03-07 17:22:15'),
(58, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '35.596800', NULL, 65, '2018-03-07 17:22:16', '2018-03-07 17:22:16'),
(59, 'product', 3, 'SAL-C', 'Sal', '0.000000', '1.440000', NULL, 65, '2018-03-07 17:22:16', '2018-03-07 17:22:16'),
(60, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '0.494400', NULL, 66, '2018-03-07 17:22:16', '2018-03-07 17:22:16'),
(61, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.020000', NULL, 66, '2018-03-07 17:22:16', '2018-03-07 17:22:16'),
(62, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '0.494400', NULL, 67, '2018-03-07 17:22:16', '2018-03-07 17:22:16'),
(63, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.020000', NULL, 67, '2018-03-07 17:22:16', '2018-03-07 17:22:16'),
(64, 'product', 17, '11103', 'Fécula de patata SG', '0.000000', '27.540000', NULL, 68, '2018-03-07 17:22:16', '2018-03-07 17:22:16'),
(65, 'product', 16, '10500', 'Masa madre de trigo blanca', '0.000000', '270.000000', NULL, 68, '2018-03-07 17:22:16', '2018-03-07 17:22:16'),
(66, 'product', 18, '20061', 'Lino tostado y molido', '0.000000', '400.000000', NULL, 69, '2018-03-07 17:22:16', '2018-03-07 17:22:16'),
(67, 'product', 16, '10500', 'Masa madre de trigo blanca', '0.000000', '1650.000000', NULL, 70, '2018-03-07 17:24:20', '2018-03-07 17:24:20'),
(68, 'product', 16, '10500', 'Masa madre de trigo blanca', '0.000000', '1650.000000', NULL, 71, '2018-03-07 17:24:20', '2018-03-07 17:24:20'),
(69, 'product', 17, '11103', 'Fécula de patata SG', '0.000000', '1998.000000', NULL, 70, '2018-03-07 17:24:20', '2018-03-07 17:24:21'),
(70, 'product', 17, '11103', 'Fécula de patata SG', '0.000000', '1998.000000', NULL, 71, '2018-03-07 17:24:21', '2018-03-07 17:24:21'),
(71, 'product', 18, '20061', 'Lino tostado y molido', '0.000000', '231.000000', NULL, 70, '2018-03-07 17:24:21', '2018-03-07 17:24:21'),
(72, 'product', 18, '20061', 'Lino tostado y molido', '0.000000', '231.000000', NULL, 71, '2018-03-07 17:24:21', '2018-03-07 17:24:21'),
(73, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '35.596800', NULL, 72, '2018-03-07 17:24:21', '2018-03-07 17:24:21'),
(75, 'product', 3, 'SAL-C', 'Sal', '0.000000', '1.440000', NULL, 72, '2018-03-07 17:24:21', '2018-03-07 17:24:21'),
(81, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '0.494400', NULL, 76, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(82, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '0.494400', NULL, 77, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(83, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.020000', NULL, 76, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(84, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.020000', NULL, 77, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(85, 'product', 17, '11103', 'Fécula de patata SG', '0.000000', '27.540000', NULL, 78, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(86, 'product', 17, '11103', 'Fécula de patata SG', '0.000000', '27.540000', NULL, 79, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(87, 'product', 16, '10500', 'Masa madre de trigo blanca', '0.000000', '270.000000', NULL, 78, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(88, 'product', 16, '10500', 'Masa madre de trigo blanca', '0.000000', '270.000000', NULL, 79, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(89, 'product', 18, '20061', 'Lino tostado y molido', '0.000000', '400.000000', NULL, 80, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(90, 'product', 18, '20061', 'Lino tostado y molido', '0.000000', '400.000000', NULL, 81, '2018-03-07 17:24:22', '2018-03-07 17:24:22'),
(93, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '8.404800', NULL, 82, '2018-04-09 10:26:32', '2018-04-09 10:26:32'),
(94, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.340000', NULL, 82, '2018-04-09 10:26:32', '2018-04-09 10:26:32'),
(95, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '177.984000', NULL, 83, '2018-04-09 10:29:54', '2018-04-09 10:29:54'),
(96, 'product', 3, 'SAL-C', 'Sal', '0.000000', '7.200000', NULL, 83, '2018-04-09 10:29:54', '2018-04-09 10:29:54'),
(97, 'product', 2, 'MASA', 'Masa para moldeo', '0.000000', '17.798400', NULL, 73, '2018-04-09 12:45:30', '2018-04-09 12:45:30'),
(98, 'product', 3, 'SAL-C', 'Sal', '0.000000', '0.720000', NULL, 73, '2018-04-09 12:45:30', '2018-04-09 12:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `production_sheets`
--

CREATE TABLE `production_sheets` (
  `id` int(10) UNSIGNED NOT NULL,
  `sequence_id` int(10) UNSIGNED DEFAULT NULL,
  `document_prefix` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `document_reference` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_dirty` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_sheets`
--

INSERT INTO `production_sheets` (`id`, `sequence_id`, `document_prefix`, `document_id`, `document_reference`, `due_date`, `name`, `notes`, `created_at`, `updated_at`, `is_dirty`) VALUES
(1, NULL, NULL, 0, NULL, '2018-03-05', 'Sábado', NULL, NULL, '2018-03-07 16:48:05', 0),
(2, NULL, NULL, 0, NULL, '2018-02-28', 'Miércoles', NULL, NULL, NULL, 0),
(3, NULL, NULL, 0, NULL, '2018-02-27', 'Martes', NULL, NULL, NULL, 1),
(4, NULL, NULL, 0, NULL, '2018-03-05', NULL, '03-05', '2018-03-02 13:44:37', '2018-03-02 13:44:37', 1),
(5, NULL, NULL, 0, NULL, '2018-03-21', 'namex', 'notesx', '2018-03-02 17:31:08', '2018-03-03 18:24:29', 0),
(6, NULL, NULL, 0, NULL, '2018-03-06', 'Martes', NULL, '2018-03-03 18:04:19', '2018-03-03 18:04:19', 0),
(7, NULL, NULL, 0, NULL, '2018-03-07', 'Jueves', NULL, '2018-03-03 18:21:56', '2018-03-03 18:21:56', 0),
(8, NULL, NULL, 0, NULL, '2018-03-10', 'TEST Miércoles Duque', NULL, '2018-03-07 10:31:49', '2018-03-07 10:31:49', 0),
(9, NULL, NULL, 0, NULL, '2018-03-08', 'HuelgaDAY', NULL, '2018-03-07 15:35:38', '2018-03-07 15:35:38', 0),
(10, NULL, NULL, 0, NULL, '2018-04-01', NULL, NULL, '2018-03-08 09:43:15', '2018-03-08 09:43:15', 0),
(11, NULL, NULL, 0, NULL, '2018-03-31', NULL, NULL, '2018-03-08 09:55:53', '2018-03-08 09:55:53', 0),
(12, NULL, NULL, 0, NULL, '2018-03-31', NULL, NULL, '2018-03-08 09:58:11', '2018-03-08 09:58:11', 0),
(13, NULL, NULL, 0, NULL, '2018-04-10', 'TEST Finishin touches', NULL, '2018-04-09 08:51:31', '2018-04-09 08:51:31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'simple',
  `procurement_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'simple',
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ean13` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_short` text COLLATE utf8mb4_unicode_ci,
  `quantity_decimal_places` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `manufacturing_batch_size` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `quantity_onhand` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_onorder` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_allocated` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_onorder_mfg` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_allocated_mfg` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `reorder_point` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `maximum_stock` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `price_tax_inc` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `last_purchase_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cost_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cost_average` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `supplier_reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supply_lead_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `location` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` decimal(20,6) DEFAULT '0.000000',
  `height` decimal(20,6) DEFAULT '0.000000',
  `depth` decimal(20,6) DEFAULT '0.000000',
  `weight` decimal(20,6) DEFAULT '0.000000',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `stock_control` tinyint(4) NOT NULL DEFAULT '1',
  `phantom_assembly` tinyint(4) NOT NULL DEFAULT '0',
  `publish_to_web` tinyint(4) NOT NULL DEFAULT '0',
  `blocked` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `tax_id` int(10) UNSIGNED NOT NULL,
  `measure_unit_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `main_supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `work_center_id` int(10) UNSIGNED DEFAULT NULL,
  `route_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_type`, `procurement_type`, `name`, `reference`, `ean13`, `description`, `description_short`, `quantity_decimal_places`, `manufacturing_batch_size`, `quantity_onhand`, `quantity_onorder`, `quantity_allocated`, `quantity_onorder_mfg`, `quantity_allocated_mfg`, `reorder_point`, `maximum_stock`, `price`, `price_tax_inc`, `last_purchase_price`, `cost_price`, `cost_average`, `supplier_reference`, `supply_lead_time`, `location`, `width`, `height`, `depth`, `weight`, `notes`, `stock_control`, `phantom_assembly`, `publish_to_web`, `blocked`, `active`, `tax_id`, `measure_unit_id`, `category_id`, `main_supplier_id`, `work_center_id`, `route_notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'simple', 'manufacture', 'Fagodio Esforulante', 'FAGESF', '1234567890', NULL, NULL, 0, 5, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', 'Notas XX fagodias!!', 1, 0, 0, 0, 1, 2, 1, NULL, NULL, 2, '<h2>con <strong>cuidad&iacute;n</strong>, espero</h2>', '2018-02-12 13:29:56', '2018-02-25 07:28:15', NULL),
(2, 'simple', 'purchase', 'Masa para moldeo', 'MASA', NULL, NULL, NULL, 2, 5, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', 'Notas XX fagodias!!', 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-02-12 13:29:56', '2018-03-08 09:19:50', NULL),
(3, 'simple', 'purchase', 'Sal', 'SAL-C', '', NULL, NULL, 0, 0, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', 'De Mar', 1, 0, 0, 0, 1, 2, 2, NULL, NULL, NULL, NULL, '2018-02-12 13:29:56', '2018-02-12 15:47:52', NULL),
(4, 'simple', 'purchase', 'Sal rara', '1007', '', NULL, NULL, 0, 0, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', 'De Mar', 1, 0, 0, 0, 1, 2, 2, NULL, NULL, NULL, NULL, '2018-02-12 13:29:56', '2018-02-12 15:47:52', NULL),
(5, 'simple', 'manufacture', 'Bocadillo de Txorizo', '4499', NULL, NULL, NULL, 0, 4, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, 2, NULL, '2018-02-24 12:51:57', '2018-03-07 11:03:58', NULL),
(6, 'simple', 'manufacture', 'Mollete Artesano ECO SG pack 4 uds', '4003', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-02-28 12:07:22', '2018-02-28 12:07:22', NULL),
(7, 'simple', 'manufacture', 'Pan integral de sarraceno con hierbas provenzales ECO SG 500G', '4022', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-02-28 12:07:22', '2018-02-28 12:07:22', NULL),
(8, 'simple', 'manufacture', 'Pan integral de Sarraceno con semillas de sésamo ECO SG 500G', '4021', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-02-28 12:07:22', '2018-02-28 12:07:22', NULL),
(9, 'simple', 'manufacture', 'Pan integral de centeno 100% con copos de centeno ECO 900g', '1016', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-02-28 17:57:01', '2018-02-28 17:57:01', NULL),
(10, 'simple', 'manufacture', 'Bizcocho casero de manzana y arándanos plum SG', '3023', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-03-02 11:44:41', '2018-03-02 11:44:41', NULL),
(11, 'simple', 'manufacture', 'Bizcocho vegano de zanahoria, manzana y nueces plum SG', '3030', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-03-02 11:44:41', '2018-03-02 11:44:41', NULL),
(12, 'simple', 'manufacture', 'Pan de Arroz con masa madre ECO SG pack 4 uds', '4001', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-03-03 16:04:27', '2018-03-03 16:04:27', NULL),
(13, 'simple', 'manufacture', 'Barra de maiz ECO SG 270g', '4006', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, 1, NULL, '2018-03-03 16:04:28', '2018-03-07 15:20:30', NULL),
(14, 'simple', 'manufacture', 'Regañás integrales de 100% espelta ECO 125 g', '2011', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-03-07 08:31:51', '2018-03-07 08:42:01', '2018-03-07 08:42:01'),
(15, 'simple', 'manufacture', 'Regañás integrales de 100% espelta ECO 125 g', '2011', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-03-07 08:43:17', '2018-03-07 08:43:17', NULL),
(16, 'simple', 'purchase', 'Masa madre de trigo blanca', '10500', NULL, NULL, NULL, 0, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-03-07 10:53:18', '2018-03-07 10:53:18', NULL),
(17, 'simple', 'purchase', 'Fécula de patata SG', '11103', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-03-07 10:54:38', '2018-03-07 10:54:38', NULL),
(18, 'simple', 'purchase', 'Lino tostado y molido', '20061', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 2, NULL, NULL, NULL, NULL, '2018-03-07 10:55:30', '2018-03-07 10:55:30', NULL),
(19, 'simple', 'manufacture', 'Pan de Molde de Maíz ECO SG 500g', '4012', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-03-07 14:10:35', '2018-03-07 14:10:35', NULL),
(20, 'simple', 'manufacture', 'Pan integral de trigo con semillas de la tierra ECO 900g.', '1014', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, NULL, NULL, NULL, NULL, '2018-04-09 04:51:32', '2018-04-09 04:51:32', NULL),
(21, 'simple', 'manufacture', 'Hachicoria', '414', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '45.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, 3, NULL, NULL, NULL, '2018-05-26 14:47:19', '2018-06-15 08:21:52', NULL),
(22, 'simple', 'purchase', 'aerhaegaetr', 'wrthwrt', NULL, NULL, NULL, 2, 1, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '110.000000', '121.000000', '0.000000', '0.000000', '0.000000', NULL, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 1, 0, 0, 0, 1, 2, 1, 2, NULL, NULL, NULL, '2018-05-31 09:45:41', '2018-05-31 09:45:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products_dist`
--

CREATE TABLE `products_dist` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'simple',
  `procurement_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'simple',
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ean13` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_short` text COLLATE utf8mb4_unicode_ci,
  `quantity_decimal_places` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `manufacturing_batch_size` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `location` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` decimal(20,6) DEFAULT '0.000000',
  `height` decimal(20,6) DEFAULT '0.000000',
  `depth` decimal(20,6) DEFAULT '0.000000',
  `weight` decimal(20,6) DEFAULT '0.000000',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `blocked` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `measure_unit_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `work_center_id` int(10) UNSIGNED DEFAULT NULL,
  `route_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `quantity_onhand` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_onorder` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_allocated` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_onorder_mfg` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `quantity_allocated_mfg` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `reorder_point` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `maximum_stock` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `price` decimal(20,6) NOT NULL DEFAULT '100.000000',
  `price_tax_inc` decimal(20,6) NOT NULL DEFAULT '110.000000',
  `last_purchase_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `cost_price` decimal(20,6) NOT NULL DEFAULT '20.000000',
  `cost_average` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `supplier_reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supply_lead_time` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `warranty_period` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `stock_control` tinyint(4) NOT NULL DEFAULT '1',
  `publish_to_web` tinyint(4) NOT NULL DEFAULT '0',
  `tax_id` int(10) UNSIGNED NOT NULL DEFAULT '2',
  `main_supplier_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products_dist`
--

INSERT INTO `products_dist` (`id`, `product_type`, `procurement_type`, `name`, `reference`, `ean13`, `description`, `description_short`, `quantity_decimal_places`, `manufacturing_batch_size`, `location`, `width`, `height`, `depth`, `weight`, `notes`, `blocked`, `active`, `measure_unit_id`, `category_id`, `work_center_id`, `route_notes`, `created_at`, `updated_at`, `deleted_at`, `quantity_onhand`, `quantity_onorder`, `quantity_allocated`, `quantity_onorder_mfg`, `quantity_allocated_mfg`, `reorder_point`, `maximum_stock`, `price`, `price_tax_inc`, `last_purchase_price`, `cost_price`, `cost_average`, `supplier_reference`, `supply_lead_time`, `warranty_period`, `stock_control`, `publish_to_web`, `tax_id`, `main_supplier_id`) VALUES
(1, 'simple', 'manufacture', 'Fagodio Esforulante', 'FAGESF', '1234567890', NULL, NULL, 0, 5, NULL, '0.000000', '0.000000', '0.000000', '0.000000', 'Notas XX fagodias!!', 0, 1, 1, NULL, 2, '<h2>con <strong>cuidad&iacute;n</strong>, espero</h2>', '2018-02-12 15:29:56', '2018-02-25 09:28:15', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(2, 'simple', 'purchase', 'Masa para moldeo', 'MASA', NULL, NULL, NULL, 2, 5, NULL, '0.000000', '0.000000', '0.000000', '0.000000', 'Notas XX fagodias!!', 0, 1, 1, NULL, NULL, NULL, '2018-02-12 15:29:56', '2018-03-08 11:19:50', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(3, 'simple', 'purchase', 'Sal', 'SAL-C', '', NULL, NULL, 0, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', 'De Mar', 0, 1, 2, NULL, NULL, NULL, '2018-02-12 15:29:56', '2018-02-12 17:47:52', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(4, 'simple', 'purchase', 'Sal rara', '1007', '', NULL, NULL, 0, 0, NULL, '0.000000', '0.000000', '0.000000', '0.000000', 'De Mar', 0, 1, 2, NULL, NULL, NULL, '2018-02-12 15:29:56', '2018-02-12 17:47:52', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(5, 'simple', 'manufacture', 'Bocadillo de Txorizo', '4499', NULL, NULL, NULL, 0, 4, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, 2, NULL, '2018-02-24 14:51:57', '2018-03-07 13:03:58', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(6, 'simple', 'manufacture', 'Mollete Artesano ECO SG pack 4 uds', '4003', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-02-28 14:07:22', '2018-02-28 14:07:22', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(7, 'simple', 'manufacture', 'Pan integral de sarraceno con hierbas provenzales ECO SG 500G', '4022', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-02-28 14:07:22', '2018-02-28 14:07:22', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(8, 'simple', 'manufacture', 'Pan integral de Sarraceno con semillas de sésamo ECO SG 500G', '4021', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-02-28 14:07:22', '2018-02-28 14:07:22', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(9, 'simple', 'manufacture', 'Pan integral de centeno 100% con copos de centeno ECO 900g', '1016', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-02-28 19:57:01', '2018-02-28 19:57:01', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(10, 'simple', 'manufacture', 'Bizcocho casero de manzana y arándanos plum SG', '3023', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-03-02 13:44:41', '2018-03-02 13:44:41', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(11, 'simple', 'manufacture', 'Bizcocho vegano de zanahoria, manzana y nueces plum SG', '3030', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-03-02 13:44:41', '2018-03-02 13:44:41', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(12, 'simple', 'manufacture', 'Pan de Arroz con masa madre ECO SG pack 4 uds', '4001', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-03-03 18:04:27', '2018-03-03 18:04:27', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(13, 'simple', 'manufacture', 'Barra de maiz ECO SG 270g', '4006', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, 1, NULL, '2018-03-03 18:04:28', '2018-03-07 17:20:30', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(14, 'simple', 'manufacture', 'Regañás integrales de 100% espelta ECO 125 g', '2011', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-03-07 10:31:51', '2018-03-07 10:42:01', '2018-03-07 10:42:01', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(15, 'simple', 'manufacture', 'Regañás integrales de 100% espelta ECO 125 g', '2011', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-03-07 10:43:17', '2018-03-07 10:43:17', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(16, 'simple', 'purchase', 'Masa madre de trigo blanca', '10500', NULL, NULL, NULL, 0, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-03-07 12:53:18', '2018-03-07 12:53:18', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(17, 'simple', 'purchase', 'Fécula de patata SG', '11103', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-03-07 12:54:38', '2018-03-07 12:54:38', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(18, 'simple', 'purchase', 'Lino tostado y molido', '20061', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 2, NULL, NULL, NULL, '2018-03-07 12:55:30', '2018-03-07 12:55:30', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(19, 'simple', 'manufacture', 'Pan de Molde de Maíz ECO SG 500g', '4012', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-03-07 16:10:35', '2018-03-07 16:10:35', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(20, 'simple', 'manufacture', 'Pan integral de trigo con semillas de la tierra ECO 900g.', '1014', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-04-09 08:51:32', '2018-04-09 08:51:32', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL),
(21, 'simple', 'manufacture', 'Pan pa Comer', '1014B', NULL, NULL, NULL, 2, 1, NULL, '0.000000', '0.000000', '0.000000', '0.000000', NULL, 0, 1, 1, NULL, NULL, NULL, '2018-04-09 08:51:32', '2018-04-09 08:51:32', NULL, '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '100.000000', '110.000000', '0.000000', '20.000000', '0.000000', NULL, 0, 0, 1, 0, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_b_o_ms`
--

CREATE TABLE `product_b_o_ms` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(20,6) NOT NULL DEFAULT '1.000000',
  `measure_unit_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'certified',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_b_o_ms`
--

INSERT INTO `product_b_o_ms` (`id`, `alias`, `name`, `quantity`, `measure_unit_id`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'firstBOM-N', 'Primera Lista de Materiales o NO', '6.000000', 1, 'certified', 'hola', '2018-02-17 14:03:53', '2018-02-22 18:05:02'),
(2, 'PAN-SES', 'Panecillos sésamo', '50.000000', 1, 'certified', 'Sesame Street!', '2018-02-22 22:06:49', '2018-02-22 22:06:49'),
(6, 'firstBOM-N-COPIA', '[COPIA]Primera Lista de Materiales o NO', '6.000000', 1, 'certified', 'hola', '2018-02-25 10:27:00', '2018-02-25 10:27:00'),
(7, 'TRY-MyBOM1', 'TiraMISU', '4.000000', 1, 'certified', NULL, '2018-03-07 12:59:31', '2018-03-07 12:59:31'),
(8, 'TRY-MyBOM222', 'Bolinchetas', '2.000000', 1, 'certified', NULL, '2018-03-07 13:01:30', '2018-03-07 13:01:30'),
(9, '4022-MyBOM33', '[BOM]-Pan integral de sarraceno con hierbas provenzales ECO SG 500G', '1.000000', 1, 'certified', NULL, '2018-03-07 13:06:38', '2018-03-07 13:06:38'),
(10, '4006-BOM', 'Lista 4006', '1.000000', 1, 'certified', NULL, '2018-03-07 17:18:05', '2018-03-07 17:18:05'),
(11, '4012-BOM', '[BOM]-Pan de Molde de Maíz ECO SG 500g', '1.000000', 1, 'certified', NULL, '2018-03-07 17:21:06', '2018-03-07 17:21:06'),
(12, 'ehwehe', 'dfhsdhg', '1.000000', 2, 'certified', NULL, '2018-04-10 10:26:02', '2018-04-10 10:26:02'),
(13, '414-BOM', '[BOM]-Hachicoria', '1.000000', 1, 'certified', NULL, '2018-05-28 07:21:53', '2018-05-28 07:21:53'),
(14, '414-BOM', '[BOM]-Hachicoria', '1.000000', 1, 'certified', NULL, '2018-05-28 07:46:27', '2018-05-28 07:46:27'),
(15, 'wgfSF', 'FGAFGQGQERGQAER', '1.000000', 1, 'certified', NULL, '2018-06-15 12:21:10', '2018-06-15 12:21:10');

-- --------------------------------------------------------

--
-- Table structure for table `product_b_o_m_lines`
--

CREATE TABLE `product_b_o_m_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `line_sort_order` int(11) DEFAULT NULL,
  `line_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product',
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(20,6) NOT NULL DEFAULT '1.000000',
  `measure_unit_id` int(10) UNSIGNED NOT NULL,
  `scrap` decimal(8,3) NOT NULL DEFAULT '0.000',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `product_bom_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_b_o_m_lines`
--

INSERT INTO `product_b_o_m_lines` (`id`, `line_sort_order`, `line_type`, `product_id`, `quantity`, `measure_unit_id`, `scrap`, `notes`, `product_bom_id`, `created_at`, `updated_at`) VALUES
(1, 40, 'product', 2, '10.000000', 1, '10.000', 'Añadir después', 1, '2018-02-17 14:31:53', '2018-04-10 15:36:22'),
(2, 20, 'product', 2, '12.000000', 1, '15.000', 'ok', 1, '2018-02-17 15:04:17', '2018-04-10 15:34:37'),
(5, 10, 'product', 4, '3.000000', 2, '5.000', NULL, 1, '2018-02-22 21:14:19', '2018-04-10 15:34:37'),
(6, 10, 'product', 2, '24.000000', 1, '3.000', 'dfbadfb', 2, '2018-02-22 22:07:53', '2018-02-22 22:07:53'),
(7, 30, 'product', 2, '10.000000', 1, '10.000', 'Añadir después', 6, '2018-02-25 10:27:00', '2018-02-25 10:27:00'),
(8, 40, 'product', 2, '12.000000', 1, '15.000', 'ok', 6, '2018-02-25 10:27:00', '2018-02-25 10:27:00'),
(9, 50, 'product', 4, '3.000000', 2, '5.000', NULL, 6, '2018-02-25 10:27:00', '2018-02-25 10:27:00'),
(10, 20, 'product', 3, '1.000000', 2, '0.000', NULL, 2, '2018-03-05 19:01:41', '2018-03-05 19:01:41'),
(11, 10, 'product', 16, '3.000000', 1, '3.000', NULL, 7, '2018-03-07 13:00:03', '2018-03-07 13:00:03'),
(12, 20, 'product', 17, '5.000000', 1, '0.000', NULL, 7, '2018-03-07 13:00:27', '2018-03-07 13:00:27'),
(13, 10, 'product', 16, '1.000000', 1, '0.000', NULL, 8, '2018-03-07 13:02:01', '2018-03-07 13:02:01'),
(14, 20, 'product', 18, '7.000000', 2, '0.000', NULL, 8, '2018-03-07 13:02:21', '2018-03-07 13:02:21'),
(15, 10, 'product', 16, '550.000000', 1, '0.000', NULL, 9, '2018-03-07 13:07:12', '2018-03-07 13:07:12'),
(16, 20, 'product', 17, '666.000000', 1, '0.000', NULL, 9, '2018-03-07 13:07:32', '2018-03-07 13:07:32'),
(17, 30, 'product', 18, '77.000000', 2, '0.000', NULL, 9, '2018-03-07 13:07:46', '2018-03-07 13:07:46'),
(18, 10, 'product', 17, '9.000000', 1, '2.000', NULL, 10, '2018-03-07 17:18:43', '2018-03-07 17:18:43'),
(19, 20, 'product', 16, '90.000000', 2, '0.000', NULL, 10, '2018-03-07 17:19:13', '2018-03-07 17:19:13'),
(20, 10, 'product', 18, '200.000000', 1, '0.000', NULL, 11, '2018-03-07 17:21:35', '2018-03-07 17:21:35'),
(21, 30, 'product', 3, '23.000000', 2, '0.000', NULL, 1, '2018-04-10 15:36:12', '2018-04-10 15:36:22');

-- --------------------------------------------------------

--
-- Table structure for table `product_warehouse`
--

CREATE TABLE `product_warehouse` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_reps`
--

CREATE TABLE `sales_reps` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identification` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `commission_percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `max_discount_allowed` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `pitw` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sequences`
--

CREATE TABLE `sequences` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequenceable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `length` tinyint(3) UNSIGNED NOT NULL,
  `separator` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `next_id` int(10) UNSIGNED NOT NULL,
  `last_date_used` timestamp NULL DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sequences`
--

INSERT INTO `sequences` (`id`, `name`, `model_name`, `sequenceable_type`, `prefix`, `length`, `separator`, `next_id`, `last_date_used`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Recuentos de Stock', 'StockCount', '', 'REC', 6, '-', 2, '2017-10-02 09:30:50', 1, '2017-09-30 19:15:15', '2017-10-02 09:30:50', NULL),
(2, 'Facturas Nacional', 'CustomerInvoice', '', 'NAC', 5, '-', 12, '2018-01-30 13:38:37', 1, '2017-10-05 12:07:21', '2018-01-30 13:38:37', NULL),
(3, 'Pedidos de Cliente TEST', 'CustomerOrder', '', 'POT', 4, '-', 53, '2018-07-01 13:12:25', 1, '2018-04-18 08:47:37', '2018-07-01 13:12:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_code` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `country_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `iso_code`, `active`, `country_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(0, 'Sin asignar', NULL, 1, 0, NULL, NULL, NULL),
(1, 'A Coruña', 'ES-C', 1, 1, '2015-04-01 16:40:22', '2017-09-21 12:12:52', NULL),
(2, 'Alacant', 'ES-A', 1, 1, '2015-04-01 16:40:22', '2017-09-20 16:40:22', NULL),
(3, 'Álava', 'ES-VI', 1, 1, '2015-04-01 16:40:22', '2017-09-20 16:40:22', NULL),
(4, 'Albacete', 'ES-AB', 1, 1, '2015-04-01 16:40:22', '2017-09-20 16:40:22', NULL),
(5, 'Almería', 'ES-AL', 1, 1, '2015-04-01 16:40:22', '2017-09-20 16:40:22', NULL),
(6, 'Asturias', 'ES-O', 1, 1, '2015-04-01 16:40:22', '2017-09-20 16:40:22', NULL),
(7, 'Ávila', 'ES-AV', 1, 1, '2015-04-01 16:40:22', '2017-09-20 16:40:22', NULL),
(8, 'Badajoz', 'ES-BA', 1, 1, '2015-04-01 16:40:22', '2017-09-20 16:40:22', NULL),
(9, 'Balears', 'ES-PM', 1, 1, '2015-04-01 16:40:22', '2017-09-20 16:40:22', NULL),
(10, 'Barcelona', 'ES-B', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(11, 'Bizkaia', 'ES-BI', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(12, 'Burgos', 'ES-BU', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(13, 'Cáceres', 'ES-CC', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(14, 'Cádiz', 'ES-CA', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(15, 'Cantabria', 'ES-S', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(16, 'Castelló', 'ES-CS', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(17, 'Ciudad Real', 'ES-CR', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(18, 'Córdoba', 'ES-CO', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(19, 'Cuenca', 'ES-CU', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(20, 'Gipuzkoa', 'ES-SS', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(21, 'Girona', 'ES-GI', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:23', NULL),
(22, 'Granada', 'ES-GR', 1, 1, '2015-04-01 16:40:23', '2017-09-20 16:40:24', NULL),
(23, 'Guadalajara', 'ES-GU', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(24, 'Huelva', 'ES-H', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(25, 'Huesca', 'ES-HU', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(26, 'Jaén', 'ES-J', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(27, 'La Rioja', 'ES-LO', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(28, 'Las Palmas', 'ES-GC', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(29, 'León', 'ES-LE', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(30, 'Lleida', 'ES-L', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(31, 'Lugo', 'ES-LU', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(32, 'Madrid', 'ES-M', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(33, 'Málaga', 'ES-MA', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(34, 'Murcia', 'ES-MU', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(35, 'Nafarroa', 'ES-NA', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:24', NULL),
(36, 'Ourense', 'ES-OR', 1, 1, '2015-04-01 16:40:24', '2017-09-20 16:40:25', NULL),
(37, 'Palencia', 'ES-P', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(38, 'Pontevedra', 'ES-PO', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(39, 'Salamanca', 'ES-SA', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(40, 'Santa Cruz de Tenerife', 'ES-TF', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(41, 'Segovia', 'ES-SG', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(42, 'Sevilla', 'ES-SE', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(43, 'Soria', 'ES-SO', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(44, 'Tarragona', 'ES-T', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(45, 'Teruel', 'ES-TE', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(46, 'Toledo', 'ES-TO', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(47, 'València', 'ES-V', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(48, 'Valladolid', 'ES-VA', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(49, 'Zamora', 'ES-ZA', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(50, 'Zaragoza', 'ES-Z', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:25', NULL),
(51, 'Ceuta', 'ES-CE', 1, 1, '2015-04-01 16:40:25', '2017-09-20 16:40:26', NULL),
(52, 'Melilla', 'ES-ML', 1, 1, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(53, 'Alabama', 'AL', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(54, 'Alaska', 'AK', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(55, 'Arizona', 'AZ', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(56, 'Arkansas', 'AR', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(57, 'California', 'CA', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(58, 'Colorado', 'CO', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(59, 'Connecticut', 'CT', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(60, 'Delaware', 'DE', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(61, 'District of Columbia', 'DC', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(62, 'Florida', 'FL', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(63, 'Georgia', 'GA', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL),
(64, 'Hawaii', 'HI', 1, 2, '2015-04-01 16:40:26', '2017-09-20 16:40:27', NULL),
(65, 'Idaho', 'ID', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(66, 'Illinois', 'IL', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(67, 'Indiana', 'IN', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(68, 'Iowa', 'IA', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(69, 'Kansas', 'KS', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(70, 'Kentucky', 'KY', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(71, 'Louisiana', 'LA', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(72, 'Maine', 'ME', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(73, 'Maryland', 'MD', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(74, 'Massachusetts', 'MA', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(75, 'Michigan', 'MI', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(76, 'Minnesota', 'MN', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(77, 'Mississippi', 'MS', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(78, 'Missouri', 'MO', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(79, 'Montana', 'MT', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:27', NULL),
(80, 'Nebraska', 'NE', 1, 2, '2015-04-01 16:40:27', '2017-09-20 16:40:28', NULL),
(81, 'Nevada', 'NV', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(82, 'New Hampshire', 'NH', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(83, 'New Jersey', 'NJ', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(84, 'New Mexico', 'NM', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(85, 'New York', 'NY', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(86, 'North Carolina', 'NC', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(87, 'North Dakota', 'ND', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(88, 'Ohio', 'OH', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(89, 'Oklahoma', 'OK', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(90, 'Oregon', 'OR', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(91, 'Pennsylvania', 'PA', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(92, 'Puerto Rico', 'PR', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(93, 'Rhode Island', 'RI', 1, 2, '2015-04-01 16:40:28', '2017-09-20 16:40:28', NULL),
(94, 'South Carolina', 'SC', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(95, 'South Dakota', 'SD', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(96, 'Tennessee', 'TN', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(97, 'Texas', 'TX', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(98, 'US Virgin Islands', 'VI', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(99, 'Utah', 'UT', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(100, 'Vermont', 'VT', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(101, 'Virginia', 'VA', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(102, 'Washington', 'WA', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(103, 'West Virginia', 'WV', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(104, 'Wisconsin', 'WI', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(105, 'Wyoming', 'WY', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL),
(107, 'Champs-sur-Marne', NULL, 1, 4, '2018-07-03 15:43:17', '2018-07-03 15:43:17', NULL),
(108, 'Osteverm', NULL, 1, 5, '2018-07-03 15:45:13', '2018-07-03 15:45:13', NULL),
(109, 'Dubai', NULL, 1, 6, '2018-07-03 15:46:12', '2018-07-03 15:46:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock_counts`
--

CREATE TABLE `stock_counts` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_date` date NOT NULL,
  `sequence_id` int(10) UNSIGNED NOT NULL,
  `document_prefix` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `document_reference` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `initial_inventory` tinyint(4) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_count_lines`
--

CREATE TABLE `stock_count_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `quantity` decimal(20,6) NOT NULL,
  `quantity_counted` decimal(20,6) NOT NULL,
  `price` decimal(20,6) NOT NULL,
  `stock_count_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `combination_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stockmovementable_id` int(11) NOT NULL,
  `stockmovementable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stockmovementable_line_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `document_reference` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(20,6) NOT NULL,
  `price` decimal(20,6) NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `conversion_rate` decimal(20,6) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `product_id` int(10) UNSIGNED NOT NULL,
  `combination_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `movement_type_id` smallint(5) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_fiscal` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_commercial` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identification` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `currency_id` int(10) UNSIGNED NOT NULL,
  `language_id` int(10) UNSIGNED NOT NULL,
  `payment_method_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `alias`, `name_fiscal`, `name_commercial`, `website`, `identification`, `notes`, `active`, `currency_id`, `language_id`, `payment_method_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '', 'La Extranatural', NULL, NULL, NULL, NULL, 1, 15, 2, NULL, NULL, NULL, NULL),
(2, '', 'Panificadora Sta Verenia', NULL, NULL, NULL, NULL, 1, 15, 2, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `name`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'IVA Normal', 1, '2017-09-20 14:40:36', '2017-09-20 14:40:36', NULL),
(2, 'IVA Reducido', 1, '2017-09-20 14:40:36', '2017-09-20 14:40:36', NULL),
(3, 'IVA Super Reducido', 1, '2017-09-20 14:40:36', '2017-09-20 14:40:36', NULL),
(4, 'IVA Exento (0%)', 1, '2017-09-20 14:40:36', '2017-09-20 14:40:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tax_rules`
--

CREATE TABLE `tax_rules` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `state_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rule_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tax_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_rules`
--

INSERT INTO `tax_rules` (`id`, `country_id`, `state_id`, `rule_type`, `name`, `percent`, `amount`, `position`, `tax_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 0, 'sales', 'IVA Normal (21%)', '21.000', '0.000000', 10, 1, '2017-09-20 14:40:36', '2017-09-20 14:40:36', NULL),
(2, 1, 0, 'sales_equalization', 'Recargo de Equivalencia (5.2%)', '5.200', '0.000000', 20, 1, '2017-09-20 14:40:36', '2017-09-20 14:40:36', NULL),
(3, 1, 0, 'sales', 'IVA Reducido (10.0%)', '10.000', '0.000000', 10, 2, '2017-09-20 14:40:36', '2017-09-20 14:40:36', NULL),
(4, 1, 0, 'sales_equalization', 'Recargo de Equivalencia (1.4%)', '1.400', '0.000000', 20, 2, '2017-09-20 14:40:36', '2017-09-20 14:40:36', NULL),
(5, 1, 0, 'sales', 'IVA Super Reducido (4%)', '4.000', '0.000000', 10, 3, '2017-09-20 14:40:36', '2017-09-20 14:40:36', NULL),
(6, 1, 0, 'sales_equalization', 'Recargo de Equivalencia (0.5%)', '0.500', '0.000000', 20, 3, '2017-09-20 14:40:36', '2017-09-20 14:40:36', NULL),
(7, 1, 0, 'sales', 'IVA Exento', '0.000', '0.000000', 10, 4, '2017-09-20 14:40:36', '2017-09-20 14:40:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `folder` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paper` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A4',
  `orientation` enum('portrait','landscape') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'portrait',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `name`, `model_name`, `folder`, `file_name`, `paper`, `orientation`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ahadf', 'CustomerInvoicePdf', 'dv', 'sdvsdv', 'A4', 'portrait', '2018-06-05 16:57:44', '2018-06-05 16:57:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `home_page` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `language_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `home_page`, `firstname`, `lastname`, `remember_token`, `is_admin`, `active`, `language_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'wasp', 'admin@abillander.com', '$2y$10$b3bC4GwpaXr2Si/.jFHp.O/NTw1mGop/jv8ECybAYCbsxuI/5qamu', '/', 'Lara', 'Billander', 'hvjnToQWJEz9P24s4kZ2apgO4hJpj2pmKzhL9Cctl0MHvcUdZRhrUJcOuYSJ', 1, 1, 1, '2018-02-07 16:42:55', '2018-02-07 16:42:55', NULL),
(2, 'hornet', 'user@abillander.com', '$2y$10$4gyOweD9QX40nGtDIyZyt.49A1.IzOJeOnFRFxOgh/5YWbZgT99ka', '/', 'Markus', 'Billander', NULL, 0, 1, 1, '2017-09-20 14:39:13', '2017-09-20 14:39:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `alias`, `name`, `notes`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'GEN', 'Almacén General', '', 1, NULL, '2018-06-04 09:18:27', NULL),
(2, 'FRESH', 'Almacén de Fresco', 'Cajas y graneles', 1, NULL, NULL, NULL),
(4, 'CONG', 'Congelados en pallets', '', 1, '2018-06-04 08:56:00', '2018-06-04 08:56:00', NULL),
(5, '12345', 'asdfg', NULL, 1, '2018-06-16 14:31:51', '2018-06-16 19:49:33', '2018-06-16 19:49:33');

-- --------------------------------------------------------

--
-- Table structure for table `woo_orders`
--

CREATE TABLE `woo_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL,
  `date_paid` datetime NOT NULL,
  `date_abi_exported` datetime NOT NULL,
  `date_abi_invoiced` datetime NOT NULL,
  `total` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_tax` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_note` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_method_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_method_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `work_centers`
--

CREATE TABLE `work_centers` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_centers`
--

INSERT INTO `work_centers` (`id`, `alias`, `name`, `notes`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SG', 'Sin Gluten', 'xxxx', 1, NULL, '2018-02-27 20:39:07', NULL),
(2, 'PANI', 'Panificadora', 'es el CT 2', 1, NULL, '2018-02-27 20:38:33', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_loggers`
--
ALTER TABLE `activity_loggers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_loggers_signature_index` (`signature`);

--
-- Indexes for table `activity_loggers_dist`
--
ALTER TABLE `activity_loggers_dist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_loggers_log_name_index` (`log_name`);

--
-- Indexes for table `activity_logger_lines`
--
ALTER TABLE `activity_logger_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `b_o_m_items`
--
ALTER TABLE `b_o_m_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carriers`
--
ALTER TABLE `carriers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `combinations`
--
ALTER TABLE `combinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `combination_option`
--
ALTER TABLE `combination_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `combination_option_combination_id_index` (`combination_id`),
  ADD KEY `combination_option_option_id_index` (`option_id`);

--
-- Indexes for table `combination_warehouse`
--
ALTER TABLE `combination_warehouse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `combination_warehouse_combination_id_index` (`combination_id`),
  ADD KEY `combination_warehouse_warehouse_id_index` (`warehouse_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `configurations_name_unique` (`name`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `countries_iso_code_index` (`iso_code`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency_conversion_rates`
--
ALTER TABLE `currency_conversion_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_groups`
--
ALTER TABLE `customer_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_invoices`
--
ALTER TABLE `customer_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_invoice_lines`
--
ALTER TABLE `customer_invoice_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_invoice_line_taxes`
--
ALTER TABLE `customer_invoice_line_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_orders_dist`
--
ALTER TABLE `customer_orders_dist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_order_lines`
--
ALTER TABLE `customer_order_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_order_lines_dist`
--
ALTER TABLE `customer_order_lines_dist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_order_line_taxes`
--
ALTER TABLE `customer_order_line_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_users`
--
ALTER TABLE `customer_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_users_email_unique` (`email`);

--
-- Indexes for table `fsx_loggers`
--
ALTER TABLE `fsx_loggers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `measure_units`
--
ALTER TABLE `measure_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `option_groups`
--
ALTER TABLE `option_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parent_child`
--
ALTER TABLE `parent_child`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_lists`
--
ALTER TABLE `price_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_list_lines`
--
ALTER TABLE `price_list_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_orders`
--
ALTER TABLE `production_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_order_lines`
--
ALTER TABLE `production_order_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_sheets`
--
ALTER TABLE `production_sheets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_dist`
--
ALTER TABLE `products_dist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_b_o_ms`
--
ALTER TABLE `product_b_o_ms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_b_o_m_lines`
--
ALTER TABLE `product_b_o_m_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_warehouse`
--
ALTER TABLE `product_warehouse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_warehouse_product_id_index` (`product_id`),
  ADD KEY `product_warehouse_warehouse_id_index` (`warehouse_id`);

--
-- Indexes for table `sales_reps`
--
ALTER TABLE `sales_reps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sequences`
--
ALTER TABLE `sequences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sequences_prefix_index` (`prefix`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `states_iso_code_index` (`iso_code`);

--
-- Indexes for table `stock_counts`
--
ALTER TABLE `stock_counts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_count_lines`
--
ALTER TABLE `stock_count_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_rules`
--
ALTER TABLE `tax_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `woo_orders`
--
ALTER TABLE `woo_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_centers`
--
ALTER TABLE `work_centers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_loggers`
--
ALTER TABLE `activity_loggers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `activity_loggers_dist`
--
ALTER TABLE `activity_loggers_dist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `activity_logger_lines`
--
ALTER TABLE `activity_logger_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2795;
--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=315;
--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `b_o_m_items`
--
ALTER TABLE `b_o_m_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `carriers`
--
ALTER TABLE `carriers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `combinations`
--
ALTER TABLE `combinations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `combination_option`
--
ALTER TABLE `combination_option`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `combination_warehouse`
--
ALTER TABLE `combination_warehouse`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `currency_conversion_rates`
--
ALTER TABLE `currency_conversion_rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50179;
--
-- AUTO_INCREMENT for table `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customer_invoices`
--
ALTER TABLE `customer_invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_invoice_lines`
--
ALTER TABLE `customer_invoice_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_invoice_line_taxes`
--
ALTER TABLE `customer_invoice_line_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `customer_orders_dist`
--
ALTER TABLE `customer_orders_dist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `customer_order_lines`
--
ALTER TABLE `customer_order_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `customer_order_lines_dist`
--
ALTER TABLE `customer_order_lines_dist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `customer_order_line_taxes`
--
ALTER TABLE `customer_order_line_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `customer_users`
--
ALTER TABLE `customer_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `fsx_loggers`
--
ALTER TABLE `fsx_loggers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `measure_units`
--
ALTER TABLE `measure_units`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `option_groups`
--
ALTER TABLE `option_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `parent_child`
--
ALTER TABLE `parent_child`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `price_lists`
--
ALTER TABLE `price_lists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `price_list_lines`
--
ALTER TABLE `price_list_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- AUTO_INCREMENT for table `production_orders`
--
ALTER TABLE `production_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT for table `production_order_lines`
--
ALTER TABLE `production_order_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;
--
-- AUTO_INCREMENT for table `production_sheets`
--
ALTER TABLE `production_sheets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `products_dist`
--
ALTER TABLE `products_dist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `product_b_o_ms`
--
ALTER TABLE `product_b_o_ms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `product_b_o_m_lines`
--
ALTER TABLE `product_b_o_m_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `product_warehouse`
--
ALTER TABLE `product_warehouse`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales_reps`
--
ALTER TABLE `sales_reps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sequences`
--
ALTER TABLE `sequences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;
--
-- AUTO_INCREMENT for table `stock_counts`
--
ALTER TABLE `stock_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stock_count_lines`
--
ALTER TABLE `stock_count_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tax_rules`
--
ALTER TABLE `tax_rules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `work_centers`
--
ALTER TABLE `work_centers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
