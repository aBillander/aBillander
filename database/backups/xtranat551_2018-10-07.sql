-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 07-10-2018 a las 13:50:20
-- Versión del servidor: 5.7.23-0ubuntu0.16.04.1
-- Versión de PHP: 7.0.32-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `xtranat551`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_loggers`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_logger_lines`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `addresses`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank_accounts`
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
-- Estructura de tabla para la tabla `b_o_m_items`
--

CREATE TABLE `b_o_m_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_bom_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(20,6) NOT NULL DEFAULT '1.000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carriers`
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
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int(10) UNSIGNED NOT NULL,
  `publish_to_web` tinyint(4) NOT NULL DEFAULT '0',
  `webshop_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_external` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_root` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combinations`
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
-- Estructura de tabla para la tabla `combination_option`
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
-- Estructura de tabla para la tabla `combination_warehouse`
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
-- Estructura de tabla para la tabla `companies`
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
  `language_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configurations`
--

CREATE TABLE `configurations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact_messages`
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
-- Estructura de tabla para la tabla `countries`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `currencies`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `currency_conversion_rates`
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
-- Estructura de tabla para la tabla `customers`
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
  `shipping_method_id` int(10) UNSIGNED DEFAULT NULL,
  `price_list_id` int(10) UNSIGNED DEFAULT NULL,
  `direct_debit_account_id` int(10) UNSIGNED DEFAULT NULL,
  `invoicing_address_id` int(10) UNSIGNED NOT NULL,
  `shipping_address_id` int(10) UNSIGNED DEFAULT NULL,
  `secure_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `import_key` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_groups`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_invoices`
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
-- Estructura de tabla para la tabla `customer_invoice_lines`
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
-- Estructura de tabla para la tabla `customer_invoice_line_taxes`
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
-- Estructura de tabla para la tabla `customer_orders`
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
  `total_currency_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_currency_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
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
  `shipping_method_id` int(10) UNSIGNED DEFAULT NULL,
  `carrier_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_rep_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `payment_method_id` int(10) UNSIGNED NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `parent_document_id` int(10) UNSIGNED DEFAULT NULL,
  `production_sheet_id` int(10) UNSIGNED DEFAULT NULL,
  `export_date` datetime DEFAULT NULL,
  `secure_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `import_key` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_order_lines`
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
  `prices_entered_with_tax` tinyint(4) NOT NULL DEFAULT '0',
  `cost_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_customer_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_customer_final_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_customer_final_price_tax_inc` decimal(20,6) NOT NULL DEFAULT '0.000000',
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_order_line_taxes`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_shipping_slips`
--

CREATE TABLE `customer_shipping_slips` (
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
  `total_currency_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_currency_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
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
  `shipping_method_id` int(10) UNSIGNED DEFAULT NULL,
  `carrier_id` int(10) UNSIGNED DEFAULT NULL,
  `sales_rep_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `payment_method_id` int(10) UNSIGNED NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `parent_document_id` int(10) UNSIGNED DEFAULT NULL,
  `production_sheet_id` int(10) UNSIGNED DEFAULT NULL,
  `export_date` datetime DEFAULT NULL,
  `secure_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `import_key` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_shipping_slip_lines`
--

CREATE TABLE `customer_shipping_slip_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `line_sort_order` int(11) DEFAULT NULL,
  `line_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `combination_id` int(10) UNSIGNED DEFAULT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(20,6) NOT NULL,
  `measure_unit_id` int(10) UNSIGNED NOT NULL,
  `prices_entered_with_tax` tinyint(4) NOT NULL DEFAULT '0',
  `cost_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_customer_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_customer_final_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_customer_final_price_tax_inc` decimal(20,6) NOT NULL DEFAULT '0.000000',
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
  `customer_shipping_slip_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `sales_rep_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_shipping_slip_line_taxes`
--

CREATE TABLE `customer_shipping_slip_line_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_rule_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taxable_base` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `percent` decimal(8,3) NOT NULL DEFAULT '0.000',
  `amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_line_tax` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `position` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `customer_shipping_slip_line_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `tax_rule_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_users`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fsx_loggers`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `images`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `languages`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `manufacturers`
--

CREATE TABLE `manufacturers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `measure_units`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2013_08_04_061308_create_taxes_table', 1),
(2, '2013_08_07_094814_create_products_table', 1),
(3, '2013_08_07_094826_create_combinations_table', 1),
(4, '2013_08_07_104152_create_categories_table', 1),
(5, '2013_08_12_093955_create_addresses_table', 1),
(6, '2013_08_12_094033_create_customers_table', 1),
(7, '2013_11_15_074910_create_sequences_table', 1),
(8, '2013_11_15_100934_create_configurations_table', 1),
(9, '2014_10_12_000000_create_users_table', 1),
(10, '2014_10_12_100000_create_password_resets_table', 1),
(11, '2014_11_23_091806_create_price_lists_table', 1),
(12, '2014_11_23_100356_create_sales_reps_table', 1),
(13, '2014_11_23_100394_create_suppliers_table', 1),
(14, '2014_11_23_100432_create_currencies_table', 1),
(15, '2014_11_23_100509_create_customer_groups_table', 1),
(16, '2014_11_23_100540_create_payment_methods_table', 1),
(17, '2014_11_23_100657_create_warehouses_table', 1),
(18, '2014_11_23_100731_create_carriers_table', 1),
(19, '2014_11_23_100836_create_bank_accounts_table', 1),
(20, '2014_12_13_053846_create_customer_invoices_table', 1),
(21, '2014_12_13_054830_create_customer_invoice_lines_table', 1),
(22, '2015_01_04_072748_create_languages_table', 1),
(23, '2015_02_19_105939_create_templates_table', 1),
(24, '2015_02_19_165649_create_manufacturers_table', 1),
(25, '2015_02_23_080022_create_companies_table', 1),
(26, '2015_03_23_154330_create_option_groups_table', 1),
(27, '2015_03_23_154423_create_options_table', 1),
(28, '2015_03_24_099826_create_combination_option_table', 1),
(29, '2015_03_25_173101_create_stock_movements_table', 1),
(30, '2015_03_26_073823_create_product_warehouse_table', 1),
(31, '2015_03_27_150000_create_combination_warehouse_table', 1),
(32, '2015_04_12_103953_create_contact_messages_table', 1),
(33, '2015_05_29_073823_create_price_list_lines_table', 1),
(34, '2015_06_16_093819_create_payments_table', 1),
(35, '2017_08_01_133642_create_customer_invoice_line_taxes_table', 1),
(36, '2017_08_07_123701_create_tax_rules_table', 1),
(37, '2017_08_16_182147_create_countries_table', 1),
(38, '2017_08_16_182217_create_states_table', 1),
(39, '2017_08_22_121018_create_images_table', 1),
(40, '2017_09_29_180827_create_stock_counts_table', 1),
(41, '2017_09_29_181055_create_stock_count_lines_table', 1),
(42, '2017_09_29_181439_create_currency_conversion_rates_table', 1),
(43, '2017_12_07_120400_create_woo_orders_table', 1),
(44, '2017_12_18_073823_create_parent_child_table', 1),
(45, '2018_01_18_094239_create_customer_users_table', 1),
(46, '2018_02_10_100432_create_measure_units_table', 1),
(47, '2018_02_10_100657_create_work_centers_table', 1),
(48, '2018_02_15_085643_create_product_b_o_ms_table', 1),
(49, '2018_02_15_090526_create_product_b_o_m_lines_table', 1),
(50, '2018_02_15_090948_create_b_o_m_items_table', 1),
(51, '2018_02_27_120210_create_production_orders_table', 1),
(52, '2018_02_27_120244_create_production_order_lines_table', 1),
(53, '2018_02_27_120305_create_customer_orders_table', 1),
(54, '2018_02_27_120331_create_customer_order_lines_table', 1),
(55, '2018_02_27_120344_create_customer_order_line_taxes_table', 1),
(56, '2018_02_27_120350_create_production_sheets_table', 1),
(57, '2018_04_14_100934_create_fsx_loggers_table', 1),
(58, '2018_05_25_114319_create_activity_loggers_table', 1),
(59, '2018_05_25_124420_create_activity_logger_lines_table', 1),
(60, '2018_07_19_100509_create_shipping_methods_table', 1),
(61, '2018_08_27_120305_create_customer_shipping_slips_table', 1),
(62, '2018_08_27_120331_create_customer_shipping_slip_lines_table', 1),
(63, '2018_08_27_120344_create_customer_shipping_slip_line_taxes_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `options`
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
-- Estructura de tabla para la tabla `option_groups`
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
-- Estructura de tabla para la tabla `parent_child`
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
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
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
-- Estructura de tabla para la tabla `payment_methods`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `price_lists`
--

CREATE TABLE `price_lists` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_external` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_is_tax_inc` tinyint(4) NOT NULL DEFAULT '0',
  `amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `currency_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `price_list_lines`
--

CREATE TABLE `price_list_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `price_list_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `production_orders`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `production_order_lines`
--

CREATE TABLE `production_order_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product',
  `product_id` int(10) UNSIGNED NOT NULL,
  `combination_id` int(10) UNSIGNED DEFAULT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_quantity` decimal(20,6) NOT NULL,
  `required_quantity` decimal(20,6) NOT NULL,
  `measure_unit_id` int(10) UNSIGNED NOT NULL,
  `warehouse_id` int(10) UNSIGNED DEFAULT NULL,
  `production_order_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `production_sheets`
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
  `is_dirty` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_b_o_ms`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_b_o_m_lines`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_warehouse`
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
-- Estructura de tabla para la tabla `sales_reps`
--

CREATE TABLE `sales_reps` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identification` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_mobile` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Estructura de tabla para la tabla `sequences`
--

CREATE TABLE `sequences` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length` tinyint(3) UNSIGNED NOT NULL,
  `separator` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_id` int(10) UNSIGNED NOT NULL,
  `last_date_used` timestamp NULL DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `webshop_id` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `carrier_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `states`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_counts`
--

CREATE TABLE `stock_counts` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_date` date NOT NULL,
  `initial_inventory` tinyint(4) NOT NULL DEFAULT '0',
  `processed` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_count_lines`
--

CREATE TABLE `stock_count_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(20,6) NOT NULL,
  `cost_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `stock_count_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `combination_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stockmovementable_id` int(11) NOT NULL,
  `stockmovementable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_reference` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_before_movement` decimal(20,6) NOT NULL,
  `quantity` decimal(20,6) NOT NULL,
  `quantity_after_movement` decimal(20,6) NOT NULL,
  `price` decimal(20,6) NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `conversion_rate` decimal(20,6) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `product_id` int(10) UNSIGNED NOT NULL,
  `combination_id` int(10) UNSIGNED DEFAULT NULL,
  `reference` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `warehouse_counterpart_id` int(10) UNSIGNED DEFAULT NULL,
  `movement_type_id` smallint(5) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `inventorycode` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suppliers`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taxes`
--

CREATE TABLE `taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tax_rules`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `templates`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `warehouses`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `woo_orders`
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
-- Estructura de tabla para la tabla `work_centers`
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
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activity_loggers`
--
ALTER TABLE `activity_loggers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_loggers_signature_index` (`signature`);

--
-- Indices de la tabla `activity_logger_lines`
--
ALTER TABLE `activity_logger_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `b_o_m_items`
--
ALTER TABLE `b_o_m_items`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `carriers`
--
ALTER TABLE `carriers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `combinations`
--
ALTER TABLE `combinations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `combination_option`
--
ALTER TABLE `combination_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `combination_option_combination_id_index` (`combination_id`),
  ADD KEY `combination_option_option_id_index` (`option_id`);

--
-- Indices de la tabla `combination_warehouse`
--
ALTER TABLE `combination_warehouse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `combination_warehouse_combination_id_index` (`combination_id`),
  ADD KEY `combination_warehouse_warehouse_id_index` (`warehouse_id`);

--
-- Indices de la tabla `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `configurations_name_unique` (`name`);

--
-- Indices de la tabla `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `countries_iso_code_index` (`iso_code`);

--
-- Indices de la tabla `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `currency_conversion_rates`
--
ALTER TABLE `currency_conversion_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_groups`
--
ALTER TABLE `customer_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_invoices`
--
ALTER TABLE `customer_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_invoice_lines`
--
ALTER TABLE `customer_invoice_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_invoice_line_taxes`
--
ALTER TABLE `customer_invoice_line_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_order_lines`
--
ALTER TABLE `customer_order_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_order_line_taxes`
--
ALTER TABLE `customer_order_line_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_shipping_slips`
--
ALTER TABLE `customer_shipping_slips`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_shipping_slip_lines`
--
ALTER TABLE `customer_shipping_slip_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_shipping_slip_line_taxes`
--
ALTER TABLE `customer_shipping_slip_line_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_users`
--
ALTER TABLE `customer_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_users_email_unique` (`email`);

--
-- Indices de la tabla `fsx_loggers`
--
ALTER TABLE `fsx_loggers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `measure_units`
--
ALTER TABLE `measure_units`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `option_groups`
--
ALTER TABLE `option_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `parent_child`
--
ALTER TABLE `parent_child`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `price_lists`
--
ALTER TABLE `price_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `price_list_lines`
--
ALTER TABLE `price_list_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `production_orders`
--
ALTER TABLE `production_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `production_order_lines`
--
ALTER TABLE `production_order_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `production_sheets`
--
ALTER TABLE `production_sheets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `product_b_o_ms`
--
ALTER TABLE `product_b_o_ms`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `product_b_o_m_lines`
--
ALTER TABLE `product_b_o_m_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `product_warehouse`
--
ALTER TABLE `product_warehouse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_warehouse_product_id_index` (`product_id`),
  ADD KEY `product_warehouse_warehouse_id_index` (`warehouse_id`);

--
-- Indices de la tabla `sales_reps`
--
ALTER TABLE `sales_reps`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sequences`
--
ALTER TABLE `sequences`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `states_iso_code_index` (`iso_code`);

--
-- Indices de la tabla `stock_counts`
--
ALTER TABLE `stock_counts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `stock_count_lines`
--
ALTER TABLE `stock_count_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tax_rules`
--
ALTER TABLE `tax_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `woo_orders`
--
ALTER TABLE `woo_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `work_centers`
--
ALTER TABLE `work_centers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activity_loggers`
--
ALTER TABLE `activity_loggers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `activity_logger_lines`
--
ALTER TABLE `activity_logger_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `b_o_m_items`
--
ALTER TABLE `b_o_m_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `carriers`
--
ALTER TABLE `carriers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `combinations`
--
ALTER TABLE `combinations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `combination_option`
--
ALTER TABLE `combination_option`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `combination_warehouse`
--
ALTER TABLE `combination_warehouse`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `currency_conversion_rates`
--
ALTER TABLE `currency_conversion_rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customer_invoices`
--
ALTER TABLE `customer_invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customer_invoice_lines`
--
ALTER TABLE `customer_invoice_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customer_invoice_line_taxes`
--
ALTER TABLE `customer_invoice_line_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customer_order_lines`
--
ALTER TABLE `customer_order_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customer_order_line_taxes`
--
ALTER TABLE `customer_order_line_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customer_shipping_slips`
--
ALTER TABLE `customer_shipping_slips`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customer_shipping_slip_lines`
--
ALTER TABLE `customer_shipping_slip_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customer_shipping_slip_line_taxes`
--
ALTER TABLE `customer_shipping_slip_line_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `customer_users`
--
ALTER TABLE `customer_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `fsx_loggers`
--
ALTER TABLE `fsx_loggers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `manufacturers`
--
ALTER TABLE `manufacturers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `measure_units`
--
ALTER TABLE `measure_units`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT de la tabla `options`
--
ALTER TABLE `options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `option_groups`
--
ALTER TABLE `option_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `parent_child`
--
ALTER TABLE `parent_child`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `price_lists`
--
ALTER TABLE `price_lists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `price_list_lines`
--
ALTER TABLE `price_list_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `production_orders`
--
ALTER TABLE `production_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `production_order_lines`
--
ALTER TABLE `production_order_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `production_sheets`
--
ALTER TABLE `production_sheets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `product_b_o_ms`
--
ALTER TABLE `product_b_o_ms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `product_b_o_m_lines`
--
ALTER TABLE `product_b_o_m_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `product_warehouse`
--
ALTER TABLE `product_warehouse`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `sales_reps`
--
ALTER TABLE `sales_reps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `sequences`
--
ALTER TABLE `sequences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `states`
--
ALTER TABLE `states`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `stock_counts`
--
ALTER TABLE `stock_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `stock_count_lines`
--
ALTER TABLE `stock_count_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tax_rules`
--
ALTER TABLE `tax_rules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `work_centers`
--
ALTER TABLE `work_centers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
