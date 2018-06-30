-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 30, 2018 at 10:54 AM
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
(70, 'Import Products :: 2018-06-28 11:45:48', '8ef9b067ac6681233c510f6e501799cb', 'App\\Http\\Controllers\\Import\\ImportProductsController::process', 1, '2018-06-28 09:45:48', '2018-06-28 09:45:48');

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
(73, 200, 'INFO', ':_> LOG terminado', '[]', NULL, NULL, '2018-06-28 11:52:29', '498357', 70, '2018-06-28 09:52:29', '2018-06-28 09:52:29');

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
(1, 'Dirección Principal', NULL, 'Mutronic', 'C/ Liberacion 2', 'Pol.Ind. Las Monjas', '28033', 'Madrid', NULL, NULL, 'Conchi', 'Martín', 'dp@qwer.com', '+34 913 810 532', NULL, NULL, NULL, 1, NULL, NULL, 1, 'App\\Customer', 32, 1, '2018-04-19 08:14:05', '2018-06-03 12:44:41', NULL),
(2, 'Almacén', NULL, 'Mutronic', 'C/Otoño, 23', 'Pol.Ind. Las Monjas', '28850', 'Torrejón de Ardoz', NULL, NULL, 'Aljandro', 'Sanz', NULL, ' 91 660 03 47', NULL, '+34 91 660 04 73', NULL, 1, NULL, NULL, 1, 'App\\Customer', 32, 1, '2018-04-19 08:14:05', '2018-04-19 08:14:05', NULL),
(3, 'Dirección Principal COPIA', NULL, 'Mutronic', 'C/ Liberacion 2', 'Pol.Ind. Las Monjas', '28033', 'Madrid', NULL, NULL, 'Conchi', 'Martín', NULL, '+34 913 810 532', NULL, NULL, NULL, 1, NULL, NULL, 1, 'App\\CustomerOther', 32, 1, '2018-04-19 08:14:05', '2018-04-19 08:14:05', NULL),
(6, 'LA EXTRANATURAL', NULL, 'LA EXTRANATURAL', 'C/ RODRIGUEZ DE LA FUENTE 18', NULL, '41310', 'BRENES', NULL, NULL, 'Lidia', 'Martinez', 'lidiamartinez@laextranatural.es', NULL, '692 813 253', NULL, NULL, 1, NULL, NULL, 2, 'App\\Company', 42, 1, '2017-09-13 07:05:36', '2018-06-05 15:39:43', NULL),
(7, '1431-Billing', '68d72b0eb5a259203a96dfa9c42ac338', 'Zatro', 'Carrer de Pinar del Rio 24-26 1º 7ª', '', '08027', 'Barcelona', 'Barcelona', 'España', 'Sandra', 'Parra', 'sparramo@gmail.com', '690271825', NULL, NULL, NULL, 1, NULL, NULL, 2, 'App\\Customer', 10, 1, '2018-04-30 15:21:36', '2018-04-30 15:21:36', NULL),
(8, '1420-Billing', '5158768b5597a5bad9d9b30444d72089', 'EVA QUINTANAR CASTELLANOS', 'CARRER DE MARTÍ GRAJALES, 9', '', '46011', 'Valencia', 'València', 'España', 'EVA', 'QUINTANAR', 'lesherbetes@gmail.com', '963811803', NULL, NULL, NULL, 1, NULL, NULL, 3, 'App\\Customer', 47, 1, '2018-05-02 09:48:12', '2018-05-02 09:48:12', NULL),
(9, '1421-Billing', '59d6e458dd2d9a0a78df1d30ff288436', 'Miriam Estévez', 'Calle trigo número 3, piso 3, puerta 8', '', '28340', 'Valdemoro', 'Madrid', 'España', 'Miriam', 'Estévez', 'naomisoria@icloud.com', '605226801', NULL, NULL, NULL, 1, NULL, NULL, 4, 'App\\Customer', 32, 1, '2018-05-02 10:23:59', '2018-05-02 10:23:59', NULL),
(10, 'CONG', NULL, 'Almecén Congelado', '1th, Fake St.', NULL, NULL, 'Alcafrán', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Congelados en pallets', 1, NULL, NULL, 4, 'App\\Warehouse', 32, 1, '2018-06-04 08:56:00', '2018-06-04 08:56:00', NULL),
(11, 'FRESH', NULL, 'Almecén de Fresco', '1th, Fake St.', NULL, NULL, 'Alcafrán', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Congelados en pallets', 1, NULL, NULL, 2, 'App\\Warehouse', 32, 1, '2018-06-04 08:56:00', '2018-06-04 08:56:00', NULL),
(12, 'GEN', NULL, 'Almecén General', '1th, Fake St.', NULL, NULL, 'Alcafrán', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, 'App\\Warehouse', 32, 1, '2018-06-04 08:56:00', '2018-06-04 09:18:27', NULL),
(13, '12345', NULL, 'dfbadfbadfbadfbadfbadfb', 'xc <xc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 5, 'App\\Warehouse', 12, 1, '2018-06-16 14:31:51', '2018-06-16 19:49:33', '2018-06-16 19:49:33');

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
  `value` text COLLATE utf8mb4_unicode_ci,
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
(54, 'WOOC_USE_LOCAL_PRODUCT_NAME', 'Use local Product & Combination names in documents, instead of WooCommerce Shop names', '1', '2017-12-10 12:19:41', '2017-12-10 13:15:45'),
(55, 'WOOC_DEF_TAX', 'Default Tax for not found products', '1', '2017-12-10 13:05:16', '2017-12-10 13:05:16'),
(56, 'WOOC_TAXES_CACHE', NULL, '[{"slug":"standard","name":"Tarifa est\\u00e1ndar","_links":{"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/taxes\\/classes"}]}},{"slug":"reduced-rate","name":"Reduced Rate","_links":{"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/taxes\\/classes"}]}},{"slug":"r-e","name":"R.E.","_links":{"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/taxes\\/classes"}]}}]', '2017-12-11 12:37:15', '2017-12-11 12:37:15'),
(57, 'WOOC_TAX_STANDARD', NULL, '1', '2017-12-11 12:57:57', '2017-12-11 12:57:57'),
(58, 'WOOC_TAX_REDUCED-RATE', NULL, '2', '2017-12-11 12:57:57', '2017-12-11 12:57:57'),
(59, 'WOOC_TAX_R-E', NULL, '2', '2017-12-11 12:57:57', '2017-12-11 12:57:57'),
(60, 'WOOC_TAXES_DICTIONARY_CACHE', NULL, '{"standard":"1","reduced-rate":"2","r-e":"2"}', '2017-12-11 13:44:38', '2017-12-11 13:44:38'),
(61, 'TAX_BASED_ON_SHIPPING_ADDRESS', 'Tax calculation based on: 1.- delivery address (default) 0.- invoice address', '0', '2017-12-12 10:16:03', '2017-12-12 10:18:47'),
(62, 'WOOC_DEF_SHIPPING_TAX', 'Default Tax for shipping expenses. It is a WooCommerce Store Setting.', '1', '2017-12-12 14:01:51', '2017-12-12 14:41:10'),
(63, 'WOOC_DECIMAL_PLACES', 'Number of decimal places WooCommerce works with. It is a WooCommerce Store Setting.', '2', '2017-12-12 14:40:41', '2017-12-12 14:40:41'),
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
(75, 'WOOC_ORDERS_PER_PAGE', 'Los pedidos de WooCommerce se recuperan en esta cantidad por página', '10', '2018-02-28 10:11:18', '2018-03-03 19:02:35'),
(76, 'CUSTOMER_ORDERS_NEED_VALIDATION', '1.- Customer Orders will be created with status = \'draft\'\r\n0.- Customer Orders will be created with status = \'confirmed\'', '0', '2018-04-16 16:55:27', '2018-04-16 16:55:27'),
(77, 'WOOC_DEF_ORDERS_SEQUENCE', 'Sequence for Customer Orders imported from WooCommerce', '3', '2018-04-30 14:31:37', '2018-04-30 14:31:37'),
(78, 'USE_CUSTOM_THEME', 'Custom theme lives in folder /resources/theme/.', '0', '2018-05-05 09:53:56', '2018-06-15 10:52:10'),
(79, 'NEW_PRODUCT_TO_ALL_PRICELISTS', '1: New Product is registered in all Price Lists. Price is calculated according to Price List type.\r\n\r\n0: New Products should be added manually to Price Lists.', '1', '2018-05-27 16:25:06', '2018-05-27 16:25:06'),
(80, 'PRODUCT_NOT_IN_PRICELIST', 'block - disallow sales.\r\npricelist - calculate price according to Price list type.\r\nproduct - take price from Product data', 'block', '2018-05-27 16:37:10', '2018-06-20 16:57:55'),
(81, 'NEW_PRICE_LIST_POPULATE', '1: When a Price List is created, all Products are added. Price is calculated according to Price List type. 0: Products should be added manually to Price Lists.', '0', '2018-06-10 08:44:38', '2018-06-10 08:44:38');

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
(1, 'España', 'ES', 1, 1, '2015-04-01 16:40:22', '2017-09-20 16:40:22', NULL),
(2, 'Estados Unidos', 'US', 1, 1, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL);

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

INSERT INTO `customers` (`id`, `company_id`, `user_id`, `name_fiscal`, `name_commercial`, `website`, `identification`, `webshop_id`, `accounting_id`, `payment_days`, `no_payment_month`, `outstanding_amount_allowed`, `outstanding_amount`, `unresolved_amount`, `notes`, `customer_logo`, `sales_equalization`, `allow_login`, `accept_einvoice`, `blocked`, `active`, `sales_rep_id`, `currency_id`, `language_id`, `customer_group_id`, `payment_method_id`, `invoice_template_id`, `carrier_id`, `price_list_id`, `direct_debit_account_id`, `invoicing_address_id`, `shipping_address_id`, `secure_key`, `import_key`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, 0, 'Mutronic, S.A.', 'Mutronic', NULL, NULL, NULL, NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', NULL, NULL, 0, 0, 0, 0, 1, NULL, 15, 0, NULL, NULL, NULL, NULL, NULL, NULL, 2, 2, '9df6f35cbe7de3cc5748abae9cc32d08', '', '2018-04-19 08:14:05', '2018-06-03 12:45:45', NULL),
(2, 0, 0, 'Zatro', 'Zatro', NULL, '45787045W', '156', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', NULL, NULL, 0, 0, 1, 0, 1, NULL, 15, 2, NULL, NULL, NULL, NULL, NULL, NULL, 7, 7, '299d6ef8f6b1401adfe1df614b18d9d7', '', '2018-04-30 15:21:36', '2018-04-30 15:24:01', NULL),
(3, 0, 0, 'EVA QUINTANAR CASTELLANOS', 'EVA QUINTANAR CASTELLANOS', NULL, '06259847y', '6', NULL, NULL, 0, '3.000000', '0.000000', '0.000000', NULL, NULL, 1, 0, 1, 0, 1, 0, 15, 2, 1, 0, NULL, 0, 1, NULL, 8, 8, 'b8599eb0912503721b7e18a0ed71f355', '', '2018-05-02 09:48:12', '2018-06-05 10:18:12', NULL),
(4, 0, 0, 'Miriam Estévez', 'Miriam Estévez', NULL, '54054575k', '71', NULL, NULL, 0, '3000.000000', '0.000000', '0.000000', NULL, NULL, 0, 0, 1, 0, 1, NULL, 15, 2, NULL, NULL, NULL, NULL, NULL, NULL, 9, 9, '47b60c39101a0f452297b2ecc11f7863', '', '2018-05-02 10:23:58', '2018-05-02 13:20:19', NULL);

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
(13, 20, 'product', 21, 0, '414', 'Hachicoria para empastes', '2.000000', 1, '45.000000', '100.000000', '90.000000', '90.000000', '81.000000', '89.100000', 1, '10.000', '0.000000', '0.000000', '180.470000', '162.000000', '10.000', '0.000', NULL, 0, 2, 2, NULL, '2018-06-25 11:03:40', '2018-06-27 11:48:45');

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
(1, 'Tarifa 1 inicial', 'discount', 0, '10.000000', 15, '2018-05-26 10:51:32', '2018-05-26 10:51:59'),
(2, 'Tarifa DOS', 'margin', 0, '20.000000', 16, '2018-05-27 18:22:06', '2018-05-30 08:07:26'),
(3, 'VIP Tarifa', 'margin', 0, '8.000000', 15, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(5, 'Bootleggers', 'price', 0, '0.000000', 15, '2018-06-10 07:52:12', '2018-06-10 07:52:12');

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
(42, 1, '0.000000', 3, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(43, 2, '0.000000', 3, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(44, 3, '0.000000', 3, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(45, 4, '0.000000', 3, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(46, 5, '0.000000', 3, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(47, 6, '0.000000', 3, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(48, 7, '0.000000', 3, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(49, 8, '0.000000', 3, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(50, 9, '0.000000', 3, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(51, 10, '0.000000', 3, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(52, 11, '0.000000', 3, '2018-05-28 06:56:05', '2018-05-28 06:56:05'),
(53, 12, '0.000000', 3, '2018-05-28 06:56:06', '2018-05-28 06:56:06'),
(54, 13, '0.000000', 3, '2018-05-28 06:56:06', '2018-05-28 06:56:06'),
(55, 15, '0.000000', 3, '2018-05-28 06:56:06', '2018-05-28 06:56:06'),
(56, 16, '0.000000', 3, '2018-05-28 06:56:06', '2018-05-28 06:56:06'),
(57, 17, '0.000000', 3, '2018-05-28 06:56:06', '2018-05-28 06:56:06'),
(58, 18, '0.000000', 3, '2018-05-28 06:56:06', '2018-05-28 06:56:06'),
(59, 19, '0.000000', 3, '2018-05-28 06:56:06', '2018-05-28 06:56:06'),
(60, 20, '0.000000', 3, '2018-05-28 06:56:06', '2018-05-28 06:56:06'),
(61, 21, '48.913043', 3, '2018-05-28 06:56:06', '2018-05-28 06:56:06'),
(62, 22, '99.000000', 1, '2018-05-31 11:45:41', '2018-05-31 11:45:41'),
(63, 22, '123.123400', 2, '2018-05-31 11:45:41', '2018-06-09 12:24:59'),
(64, 22, '0.000000', 3, '2018-05-31 11:45:41', '2018-05-31 11:45:41'),
(87, 21, '22.000000', 5, '2018-06-10 08:14:26', '2018-06-10 08:14:26');

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
(3, 'Pedidos de Cliente TEST', 'CustomerOrder', '', 'POT', 4, '-', 52, '2018-06-04 15:04:28', 1, '2018-04-18 08:47:37', '2018-06-04 15:04:28', NULL);

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
(105, 'Wyoming', 'WY', 1, 2, '2015-04-01 16:40:29', '2017-09-20 16:40:29', NULL);

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
(1, 'wasp', 'admin@abillander.com', '$2y$10$b3bC4GwpaXr2Si/.jFHp.O/NTw1mGop/jv8ECybAYCbsxuI/5qamu', '/', 'Lara', 'Billander', 'hTao6ZDK0ZA5DLtG4SaNv0cnbO3B65UvFIIgGm3C7I1JAV57QdihrcgtiC3q', 1, 1, 1, '2018-02-07 16:42:55', '2018-02-07 16:42:55', NULL),
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `activity_loggers_dist`
--
ALTER TABLE `activity_loggers_dist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `activity_logger_lines`
--
ALTER TABLE `activity_logger_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
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
