-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 07-03-2018 a las 17:52:18
-- Versión del servidor: 5.7.21-0ubuntu0.16.04.1
-- Versión de PHP: 7.0.25-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `xtranat55`
--

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

--
-- Volcado de datos para la tabla `b_o_m_items`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configurations`
--

CREATE TABLE `configurations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(254) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configurations`
--

INSERT INTO `configurations` (`id`, `name`, `description`, `value`, `created_at`, `updated_at`) VALUES
(1, 'SW_VERSION', NULL, '0.2.2', '2015-03-31 09:12:55', '2017-07-18 09:58:20'),
(3, 'MARGIN_METHOD', '\'CST\' => Sales Margin is related to Product Cost Price,  // Markup Percentage = (Sales Price – Unit Cost)/Unit Cost X 100\r\n\'PRC\' => Sales Margin is related to Product Sales Price,  // Gross Margin Percentage = (Gross Profit/Sales Price) X 100\r\nGross Profit = Sales Price – Unit Cost', 'PRC', '2015-03-31 09:12:55', '2017-11-14 12:19:49'),
(4, 'ALLOW_SALES_WITHOUT_STOCK', NULL, '0', '2015-03-31 09:12:55', '2015-05-19 15:14:47'),
(5, 'ALLOW_SALES_RISK_EXCEEDED', NULL, '0', '2015-03-31 09:12:55', '2015-05-19 15:14:47'),
(6, 'DEF_LANGUAGE', NULL, '2', '2015-03-31 09:12:55', '2017-07-18 09:48:49'),
(7, 'DEF_COUNTRY', '', '1', '2015-03-31 09:12:55', '2017-08-16 17:05:13'),
(8, 'DEF_CUSTOMER_INVOICE_SEQUENCE', NULL, '2', '2015-03-31 09:12:55', '2017-10-09 13:19:52'),
(9, 'DEF_CUSTOMER_INVOICE_TEMPLATE', NULL, '1', '2015-03-31 09:12:55', '2017-10-30 09:16:03'),
(10, 'DEF_CUSTOMER_PAYMENT_METHOD', NULL, '1', '2015-03-31 09:12:55', '2017-10-10 10:35:14'),
(11, 'DEF_OUTSTANDING_AMOUNT', NULL, '3000.0', '2015-03-31 09:12:55', '2015-03-31 09:12:55'),
(12, 'DEF_CARRIER', NULL, '0', '2015-03-31 09:12:55', '2015-03-31 09:12:55'),
(13, 'DEF_WAREHOUSE', NULL, '5', '2015-03-31 09:12:55', '2017-09-28 12:54:36'),
(14, 'DEF_ITEMS_PERPAGE', NULL, '8', '2015-03-31 09:12:55', '2018-03-06 10:42:42'),
(15, 'DEF_ITEMS_PERAJAX', 'Number of items (maximum) returned by an ajax request', '10', '2015-03-31 09:12:55', '2018-02-28 10:07:31'),
(16, 'TIMEZONE', NULL, 'Europe/Madrid', '2015-04-01 20:00:00', '2015-04-01 20:00:00'),
(17, 'DEF_COMPANY', NULL, '2', '2015-04-03 05:54:13', '2017-10-19 10:58:40'),
(18, 'DEF_PERCENT_DECIMALS', NULL, '2', '2015-04-18 15:58:22', '2015-04-18 16:01:54'),
(19, 'DEF_CURRENCY', NULL, '15', '2015-05-08 10:03:37', '2017-09-22 14:29:16'),
(20, 'DEF_CUSTOMER_PAYMENT_DAY', NULL, '1', '2015-06-22 15:54:06', '2015-06-22 15:54:06'),
(21, 'SUPPORT_CENTER_EMAIL', NULL, 'dcomobject@hotmail.com', '2015-07-09 08:13:38', '2017-09-28 08:28:47'),
(22, 'SUPPORT_CENTER_NAME', NULL, 'aBillander Support Center', '2015-07-09 08:14:11', '2015-07-09 08:14:11'),
(23, 'ENABLE_SALES_EQUALIZATION', NULL, '1', '2017-07-18 19:00:10', '2017-07-18 19:00:10'),
(24, 'ENABLE_COMBINATIONS', NULL, '1', '2017-07-18 19:02:52', '2017-07-18 19:04:17'),
(25, 'ENABLE_WAREHOUSE', NULL, '1', '2017-07-20 08:37:48', '2017-07-20 08:37:48'),
(26, 'QUOTES_EXPIRE_AFTER', NULL, '15', '2017-07-25 15:38:02', '2017-07-25 15:38:02'),
(27, 'SW_DATABASE_VERSION', NULL, '0.2.2', '2017-07-26 05:35:19', '2017-07-26 05:35:19'),
(29, 'ENABLE_WEBSHOP_CONNECTOR', NULL, '1', '2017-07-26 10:30:47', '2017-10-16 10:01:02'),
(30, 'ALLOW_PRODUCT_SUBCATEGORIES', '=1 : Permite subcategorías. Los productos se asocian a subcategorías.\r\n=0 : Los productos se asocian a categorías.\r\nNOTA: las subcategorías son "Categorías-hijas"', '1', '2017-08-19 07:37:57', '2017-09-16 10:58:41'),
(31, 'HEADER_TITLE', 'Texto en la barra de menús, a la izquierda', '<span style="color:#bbb"><i class="fa fa-bolt"></i> Lar<span style="color:#fff">aBillander</span> LXVII</span>', '2017-08-31 05:00:28', '2017-10-16 10:02:15'),
(32, 'DEF_QUANTITY_DECIMALS', 'Default decimal positions for quantities (stock, etc.)', '2', '2017-09-10 10:19:49', '2017-09-10 10:19:49'),
(33, 'DEF_MEASURE_UNIT_4_PRODUCTS', 'Default measure unit for products and combinations', '1', '2017-09-12 08:53:07', '2018-02-28 13:59:58'),
(34, 'DEF_WEIGHT_UNIT', 'The default weight unit for your shop (e.g."kg" for kilograms, "lbs" for pound-mass, etc.).', 'kg', '2017-09-12 09:05:10', '2017-09-12 09:05:10'),
(35, 'DEF_DISTANCE_UNIT', 'The default distance unit for your shop (e.g. "km" for kilometer, "mi" for mile, etc.).', 'km', '2017-09-12 09:06:34', '2017-09-12 09:06:34'),
(36, 'DEF_VOLUME_UNIT', 'The default volume unit for your shop (e.g. "L" for liter, "gal" for gallon, etc.).', 'cl', '2017-09-12 09:07:39', '2017-09-12 09:07:39'),
(37, 'DEF_DIMENSION_UNIT', 'The default dimension unit for your shop (e.g. "cm" for centimeter, "in" for inch, etc.).', 'cm', '2017-09-12 09:08:26', '2017-09-12 09:08:26'),
(39, 'DEF_TAX', 'Default Tax for Products', '1', '2017-09-16 19:31:45', '2017-09-16 19:31:45'),
(40, 'DEF_PRICELIST', 'Default Price List for products', '1', '2017-09-18 07:28:21', '2017-09-18 07:28:21'),
(41, 'STOCK_COUNT_IN_PROGRESS', 'Stock count is in progress. Routes / Controlleres that modify stock are voided.', '0', '2017-09-29 16:36:03', '2017-09-29 16:36:03'),
(42, 'PRICES_ENTERED_WITH_TAX', '1.- I will enter prices inclusive of tax\r\n0.- I will enter prices exclusive of tax\r\nChanging this option will not update existing products', '0', '2017-10-15 09:02:16', '2017-10-16 09:55:16'),
(43, 'ROUND_PRICES_WITH_TAX', '1.- \r\na: Round price inclusive of tax b: Calculate and round price exclusive of tax c: Tax is the difference (no round needed)\r\n0.- \r\na: Round price exclusive of tax b: Calculate and round price inclusive of tax c: Tax is the difference (no round needed)', '1', '2017-10-15 09:10:24', '2017-10-27 09:49:23'),
(44, 'SKU_PREFIX_OFFSET', 'This value will be added to the Product ID to obtain an unique SKU', '100', '2017-11-11 14:47:07', '2017-11-11 15:37:38'),
(45, 'SKU_PREFIX_LENGTH', 'Product ID will be (zero padded) this length (minimum) as SKU prefix.', '4', '2017-11-11 14:48:34', '2017-11-11 17:08:27'),
(46, 'SKU_SUFFIX_LENGTH', 'Combination ID will be (zero padded) this length (minimum) as SKU suffix.', '1', '2017-11-11 14:49:50', '2017-11-11 17:07:06'),
(47, 'SKU_SEPARATOR', 'This will be placed between SKU Prefix and SKU Suffix', '-', '2017-11-11 14:52:01', '2017-11-11 14:52:01'),
(48, 'SKU_AUTOGENERATE', 'Auto-generate a SKU if none is given', '1', '2017-11-11 15:40:40', '2017-11-11 15:40:40'),
(49, 'WOOC_DEF_CURRENCY', 'WooCommerce Currency ID (within aBillander)', '15', '2017-12-02 13:55:24', '2017-12-02 13:55:24'),
(51, 'WOOC_DEF_LANGUAGE', 'WooCommerce Language ID (within aBillander)', '2', '2017-12-08 13:46:13', '2017-12-08 18:02:53'),
(52, 'WOOC_DEF_INVOICE_SEQUENCE', 'Invoiced WooCommerce Orders will go to this Sequence', '2', '2017-12-10 09:25:23', '2017-12-10 09:26:27'),
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
(64, 'WOOC_PAYMENT_GATEWAYS_CACHE', NULL, '[{"id":"redsys","title":"Tarjeta de cr\\u00e9dito\\/d\\u00e9bito","description":"Paga con tu tarjeta de cr\\u00e9dito o de d\\u00e9bito","order":0,"enabled":true,"method_title":"Pago con Tarjeta (REDSYS)","method_description":"Esta es la opci\\u00f3n de la pasarela de pago de Redsys.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/redsys"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}},{"id":"iupay","title":"IUPAY","description":"Paga con Iupay","order":1,"enabled":true,"method_title":"Pago con Tarjeta (IUPAY)","method_description":"Esta es la opci\\u00f3n de la pasarela de pago de Iupay.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/iupay"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}},{"id":"paypal","title":"PayPal","description":"Pagar con PayPal; puedes pagar con tu tarjeta de cr\\u00e9dito si no tienes una cuenta de PayPal.","order":2,"enabled":true,"method_title":"PayPal","method_description":"PayPal est\\u00e1ndar funciona enviando al usuario a PayPal para que introduzca su informaci\\u00f3n de pago. La IPN de PayPal IPN requiere compatibilidad con fsockopen\\/cURL para actualizar el estado del pedido despu\\u00e9s del pago. Revisa la p\\u00e1gina del <a href=\\"http:\\/\\/localhost\\/wooc\\/wp-admin\\/admin.php?page=wc-status\\">estado del sistema<\\/a> para m\\u00e1s detalles.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/paypal"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}},{"id":"bacs","title":"Transferencia bancaria","description":"Realiza tu pago directamente en nuestra cuenta bancaria. Por favor usa la referencia del pedido como referencia de pago. El pedido no ser\\u00e1 enviado hasta que el importe completo haya sido recibido en nuestra cuenta.","order":3,"enabled":false,"method_title":"Transferencia bancaria","method_description":"Permite el pago mediante transferencia bancaria.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/bacs"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}},{"id":"cheque","title":"Giro a 30 d\\u00edas","description":"El importe facturado se cargar\\u00e1 en su cuenta bancaria 30 d\\u00edas despu\\u00e9s de la fecha de factura. Solo disponible para clientes que hayan firmado la ORDEN DE DOMICILIACI\\u00d3N SEPA.","order":4,"enabled":true,"method_title":"Pagos por cheque","method_description":"Permite el pago por cheque. \\u00bfPor qu\\u00e9 aceptar cheques a estas alturas? Probablemente no deber\\u00edas pero te permite hacer compras de prueba para probar los correos electr\\u00f3nicos de pedido, las p\\u00e1ginas de \'conseguido\', etc.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/cheque"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}},{"id":"cod","title":"Contra reembolso","description":"Pagar en efectivo al momento de la entrega.","order":5,"enabled":false,"method_title":"Contra reembolso","method_description":"Permite que sus clientes paguen en efectivo (o por otros medios) cuando se entrega el producto.","_links":{"self":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways\\/cod"}],"collection":[{"href":"http:\\/\\/localhost\\/wooc\\/wp-json\\/wc\\/v2\\/payment_gateways"}]}}]', '2017-12-13 09:40:42', '2017-12-16 13:29:43'),
(65, 'WOOC_PAYMENT_GATEWAY_REDSYS', NULL, '1', '2017-12-13 10:21:29', '2017-12-13 10:21:29'),
(66, 'WOOC_PAYMENT_GATEWAY_IUPAY', NULL, '1', '2017-12-13 10:21:29', '2017-12-13 10:21:29'),
(67, 'WOOC_PAYMENT_GATEWAY_PAYPAL', NULL, '1', '2017-12-13 10:21:29', '2017-12-13 10:21:29'),
(68, 'WOOC_PAYMENT_GATEWAY_BACS', NULL, '2', '2017-12-13 10:21:29', '2017-12-13 10:21:29'),
(69, 'WOOC_PAYMENT_GATEWAY_CHEQUE', NULL, '2', '2017-12-13 10:21:29', '2017-12-13 10:21:29'),
(70, 'WOOC_PAYMENT_GATEWAY_COD', NULL, '2', '2017-12-13 10:21:30', '2017-12-13 10:21:30'),
(71, 'WOOC_PAYMENT_GATEWAYS_DICTIONARY_CACHE', NULL, '{"redsys":"1","iupay":"1","paypal":"1","bacs":"2","cheque":"2","cod":"2"}', '2017-12-13 10:21:30', '2017-12-13 10:21:30'),
(72, 'WOOC_CONFIGURATIONS_CACHE', NULL, '[{"id":"woocommerce_default_country","description":"Esto es d\\u00f3nde est\\u00e1 situado tu negocio. Los impuestos estar\\u00e1n basados en este pa\\u00eds.","value":"ES:SE"},{"id":"woocommerce_allowed_countries","description":"Con esta opci\\u00f3n puedes elegir a qu\\u00e9 pa\\u00edses quieres vender.","value":"specific"},{"id":"woocommerce_all_except_countries","description":"","value":[]},{"id":"woocommerce_specific_allowed_countries","description":"","value":["ES"]},{"id":"woocommerce_ship_to_countries","description":"Elige a qu\\u00e9 pa\\u00edses quieres enviar, o elige enviar a todos los lugares que vendes.","value":""},{"id":"woocommerce_specific_ship_to_countries","description":"","value":[]},{"id":"woocommerce_default_customer_address","description":"","value":"geolocation"},{"id":"woocommerce_calc_taxes","description":"Activa los impuestos y los c\\u00e1lculos de impuestos","value":"yes"},{"id":"woocommerce_demo_store","description":"Activa un aviso de texto en toda la tienda","value":"no"},{"id":"woocommerce_demo_store_notice","description":"","value":"This is a demo store for testing purposes &mdash; no orders shall be fulfilled."},{"id":"woocommerce_currency","description":"Esto controla en qu\\u00e9 moneda se listan los precios en el cat\\u00e1logo y en qu\\u00e9 moneda se realizar\\u00e1n los pagos a trav\\u00e9s de las opciones de pago.","value":"EUR"},{"id":"woocommerce_currency_pos","description":"Esto controla la posici\\u00f3n del s\\u00edmbolo de moneda.","value":"right"},{"id":"woocommerce_price_thousand_sep","description":"Esto establece el separador de miles de los precios mostrados.","value":"."},{"id":"woocommerce_price_decimal_sep","description":"Esto establece el separador decimal de los precios mostrados.","value":","},{"id":"woocommerce_price_num_decimals","description":"Esto establece el n\\u00famero de decimales que se muestran en los precios mostrados.","value":"2"},{"id":"woocommerce_weight_unit","description":"Esto controla en qu\\u00e9 unidad definir\\u00e1s los pesos.","value":"kg"},{"id":"woocommerce_dimension_unit","description":"Esto controla en qu\\u00e9 unidad definir\\u00e1s las longitudes.","value":"cm"},{"id":"woocommerce_enable_reviews","description":"Activar valoraciones de producto","value":"yes"},{"id":"woocommerce_review_rating_verification_label","description":"Mostrar la etiqueta \\"propietario verificado\\" en las valoraciones de los clientes","value":"yes"},{"id":"woocommerce_review_rating_verification_required","description":"Las rese\\u00f1as solo las pueden dejar \\"propietarios verificados\\"","value":"no"},{"id":"woocommerce_enable_review_rating","description":"Activar valoraciones con estrellas en las rese\\u00f1as","value":"yes"},{"id":"woocommerce_review_rating_required","description":"Las valoraciones con estrellas deber\\u00e1n ser obligatorias, no opcionales","value":"yes"},{"id":"woocommerce_shop_page_display","description":"Esto controla lo que se muestra en el archivo de productos.","value":""},{"id":"woocommerce_category_archive_display","description":"Esto controla lo que se muestra en Archivo de la categor\\u00eda.","value":""},{"id":"woocommerce_default_catalog_orderby","description":"Esto controla el orden de clasificaci\\u00f3n por defecto del cat\\u00e1logo.","value":"menu_order"},{"id":"woocommerce_cart_redirect_after_add","description":"Redirigir a la p\\u00e1gina del carrito tras a\\u00f1adir productos con \\u00e9xito","value":"no"},{"id":"woocommerce_enable_ajax_add_to_cart","description":"Activar botones AJAX de a\\u00f1adir al carrito en los archivos","value":"yes"},{"id":"shop_catalog_image_size","description":"Este tama\\u00f1o se utiliza generalmente en los listados de productos. (W x H)","value":{"width":247,"height":300,"crop":false}},{"id":"shop_single_image_size","description":"Este es el tama\\u00f1o utilizado en la imagen principal de la p\\u00e1gina del producto. (W x H)","value":{"width":600,"height":902,"crop":false}},{"id":"shop_thumbnail_image_size","description":"Este tama\\u00f1o se utiliza generalmente para la galer\\u00eda de im\\u00e1genes en la p\\u00e1gina del producto. (W x H)","value":{"width":114,"height":130,"crop":false}},{"id":"woocommerce_manage_stock","description":"Activar la gesti\\u00f3n de inventario","value":"no"},{"id":"woocommerce_hold_stock_minutes","description":"Mantener el inventario (para pedidos pendientes de pago) durante x minutos. Cuando se alcance este l\\u00edmite se cancelar\\u00e1 el pedido pendiente. D\\u00e9jalo en blanco para desactivarlo.","value":"60"},{"id":"woocommerce_notify_low_stock","description":"Activar avisos de pocas existencias","value":"no"},{"id":"woocommerce_notify_no_stock","description":"Activar avisos de inventario agotado","value":"no"},{"id":"woocommerce_stock_email_recipient","description":"Introduce destinatarios (separados por coma) que recibir\\u00e1n este aviso.","value":"lidiamartinez@laextranatural.es"},{"id":"woocommerce_notify_low_stock_amount","description":"Cuando el inventario del producto alcance esta cantidad ser\\u00e1s notificado por correo electr\\u00f3nico.","value":"2"},{"id":"woocommerce_notify_no_stock_amount","description":"Cuando el inventario del producto alcanza esta cantidad el estado del inventario cambiar\\u00e1 a \\"sin existencias\\" y recibir\\u00e1s un aviso por correo electr\\u00f3nico. Este ajuste no afecta a los productos \\"con existencias\\".","value":"0"},{"id":"woocommerce_hide_out_of_stock_items","description":"Ocultar en el cat\\u00e1logo los art\\u00edculos agotados","value":"no"},{"id":"woocommerce_stock_format","description":"Esto controla c\\u00f3mo se muestran las cantidades del inventario en la tienda.","value":"no_amount"},{"id":"woocommerce_file_download_method","description":"Forzar descargas mantendr\\u00e1 ocultas las URLs, pero puede que algunos servidores sirvan archivos grandes de manera poco segura. Si es compatible, puedes usar en su lugar <code>X-Accel-Redirect<\\/code> \\/ <code>X-Sendfile<\\/code> para servir descargas (el servidor requiere <code>mod_xsendfile<\\/code> ).","value":"force"},{"id":"woocommerce_downloads_require_login","description":"Las descargas requieren inicio de sesi\\u00f3n","value":"no"},{"id":"woocommerce_downloads_grant_access_after_payment","description":"Permitir acceso a los productos descargables despu\\u00e9s del pago","value":"yes"},{"id":"woocommerce_prices_include_tax","description":"","value":"no"},{"id":"woocommerce_tax_based_on","description":"","value":"shipping"},{"id":"woocommerce_shipping_tax_class","description":"Control opcional para la tasa de impuesto por env\\u00edo, o d\\u00e9jalo tal cual si el impuesto por env\\u00edo est\\u00e1 basado en los productos del carrito.","value":""},{"id":"woocommerce_tax_round_at_subtotal","description":"Redondeo de impuesto en el subtotal, en lugar de redondeo por cada l\\u00ednea","value":"no"},{"id":"woocommerce_tax_classes","description":"","value":"4% con RE\\r\\n10% con RE\\r\\nIVA Normal con RE\\r\\n10%\\r\\n4%"},{"id":"woocommerce_tax_display_shop","description":"","value":"incl"},{"id":"woocommerce_tax_display_cart","description":"","value":"incl"},{"id":"woocommerce_price_display_suffix","description":"","value":""},{"id":"woocommerce_tax_total_display","description":"","value":"itemized"},{"id":"woocommerce_enable_shipping_calc","description":"Activar la calculadora de env\\u00edos en la p\\u00e1gina de compra","value":"no"},{"id":"woocommerce_shipping_cost_requires_address","description":"Ocultar los gastos de env\\u00edo hasta que se introduzca una direcci\\u00f3n","value":"yes"},{"id":"woocommerce_ship_to_destination","description":"Esto controla qu\\u00e9 direcci\\u00f3n de env\\u00edo se usa por defecto.","value":"shipping"},{"id":"woocommerce_shipping_debug_mode","description":"Activar el modo de depuraci\\u00f3n","value":"no"},{"id":"woocommerce_enable_coupons","description":"Activa el uso de cupones","value":"yes"},{"id":"woocommerce_calc_discounts_sequentially","description":"Calcular descuentos de cupones secuencialmente","value":"no"},{"id":"woocommerce_enable_guest_checkout","description":"Permitir finalizar compra como invitado","value":"no"},{"id":"woocommerce_force_ssl_checkout","description":"Forzar el pago seguro","value":"no"},{"id":"woocommerce_checkout_pay_endpoint","description":"Variable de la p\\u00e1gina de \\"Finalizar compra &rarr; Pagar\\".","value":"order-pay"},{"id":"woocommerce_checkout_order_received_endpoint","description":"Variable de la p\\u00e1gina \\"Finalizar compra &rarr; Pedido recibido\\".","value":"order-received"},{"id":"woocommerce_myaccount_add_payment_method_endpoint","description":"Variable de la p\\u00e1gina \\"Finalizar compra &rarr; A\\u00f1adir m\\u00e9todo de pago\\".","value":"add-payment-method"},{"id":"woocommerce_myaccount_delete_payment_method_endpoint","description":"Variable para la p\\u00e1gina de eliminar m\\u00e9todo de pago","value":"delete-payment-method"},{"id":"woocommerce_myaccount_set_default_payment_method_endpoint","description":"Variable para establecer una p\\u00e1gina de m\\u00e9todo de pago por defecto","value":"set-default-payment-method"},{"id":"woocommerce_enable_signup_and_login_from_checkout","description":"Permitir el alta como cliente en la p\\u00e1gina \\"Finalizar compra\\"","value":"yes"},{"id":"woocommerce_enable_myaccount_registration","description":"Permitir el alta como cliente en la p\\u00e1gina \\"Mi cuenta\\"","value":"no"},{"id":"woocommerce_enable_checkout_login_reminder","description":"Muestra un recordatorio de acceso al cliente ya registrado en la p\\u00e1gina de \\"Finalizar compra\\"","value":"yes"},{"id":"woocommerce_registration_generate_username","description":"Genera autom\\u00e1ticamente el nombre de usuario a partir del correo electr\\u00f3nico del cliente","value":"yes"},{"id":"woocommerce_registration_generate_password","description":"Genera autom\\u00e1ticamente la contrase\\u00f1a del cliente","value":"yes"},{"id":"woocommerce_myaccount_orders_endpoint","description":"Variable para la p\\u00e1gina \\"Mi cuenta &rarr; Pedidos\\".","value":"orders"},{"id":"woocommerce_myaccount_view_order_endpoint","description":"Variable de la p\\u00e1gina \\"Mi cuenta &rarr; Ver pedido\\".","value":"view-order"},{"id":"woocommerce_myaccount_downloads_endpoint","description":"Variable para la p\\u00e1gina \\"Mi cuenta &rarr; Descargas\\".","value":"downloads"},{"id":"woocommerce_myaccount_edit_account_endpoint","description":"Variable de la p\\u00e1gina \\"Mi cuenta &rarr; Editar cuenta\\".","value":"edit-account"},{"id":"woocommerce_myaccount_edit_address_endpoint","description":"Variable para la p\\u00e1gina \\"Mi cuenta &rarr; Direcciones\\".","value":"edit-address"},{"id":"woocommerce_myaccount_payment_methods_endpoint","description":"Variable para la p\\u00e1gina \\"Mi cuenta &rarr; M\\u00e9todos de pago\\".","value":"payment-methods"},{"id":"woocommerce_myaccount_lost_password_endpoint","description":"Variable de la p\\u00e1gina \\"Mi cuenta &rarr; Contrase\\u00f1a perdida\\".","value":"lost-password"},{"id":"woocommerce_logout_endpoint","description":"Variable para forzar la desconexi\\u00f3n. Puedes a\\u00f1adir esto a tus men\\u00fas con un enlace personalizado: tusitio.com\\/?customer-logout=true","value":"customer-logout"},{"id":"woocommerce_email_from_name","description":"C\\u00f3mo aparece el nombre del remitente en los correos electr\\u00f3nicos salientes de WooCommerce.","value":"La Extranatural"},{"id":"woocommerce_email_from_address","description":"C\\u00f3mo aparece la direcci\\u00f3n de correo electr\\u00f3nico del remitente en los correos salientes de WooCommerce.","value":"laextranatural@laextranatural.es"},{"id":"woocommerce_email_header_image","description":"URL de la imagen que quieres mostrar en la cabecera del correo electr\\u00f3nico. Sube las im\\u00e1genes utilizando la subida de multimedia (Administrador > Multimedia).","value":""},{"id":"woocommerce_email_footer_text","description":"El texto que aparece en el pie de p\\u00e1gina de mensajes de correo electr\\u00f3nico WooCommerce.","value":"La Extranatural"},{"id":"woocommerce_email_base_color","description":"El color base para las plantillas de correo electr\\u00f3nico de WooCommerce. Por defecto <code>#96588a<\\/code>.","value":"#557da1"},{"id":"woocommerce_email_background_color","description":"El color de fondo para las plantillas de correo electr\\u00f3nico de WooCommerce. Por defecto <code>#f7f7f7<\\/code>.","value":"#f5f5f5"},{"id":"woocommerce_email_body_background_color","description":"El color principal del fondo de la p\\u00e1gina. Por defecto <code>#ffffff<\\/code>.","value":"#fdfdfd"},{"id":"woocommerce_email_text_color","description":"El color principal del texto de la p\\u00e1gina. Por defecto <code>#3c3c3c<\\/code>.","value":"#505050"},{"id":"woocommerce_api_enabled","description":"Activa la API REST","value":"yes"},{"id":"enabled","description":"","value":"yes"},{"id":"recipient","description":"Introduce los destinatarios (separados por comas) de este correo electr\\u00f3nico. Por defecto es <code>laextranatural@laextranatural.es<\\/code>.","value":"laextranatural@laextranatural.com, laextranatural@laextranatural.es"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"[{site_title}] Nuevo pedido ({order_number}) - {order_date}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Nuevo pedido de un cliente"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"recipient","description":"Introduce los destinatarios (separados por comas) de este correo electr\\u00f3nico. Por defecto es <code>laextranatural@laextranatural.es<\\/code>.","value":"laextranatural@laextranatural.com, laextranatural@laextranatural.es"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"[{site_title}] - Cancelada la orden ({order_number})"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Pedido cancelado"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"recipient","description":"Introduce los destinatarios (separados por comas) de este correo electr\\u00f3nico. Por defecto es <code>laextranatural@laextranatural.es<\\/code>.","value":"laextranatural@laextranatural.com, laextranatural@laextranatural.es"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"[{site_title}] Pedido fallido ({order_number})"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Pedido fallido"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":""},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":""},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Recibo de tu pedido en {site_title} del {order_date}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Gracias por tu pedido"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Se ha completado tu pedido en {site_title} del {order_date}."},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"El pedido se ha completado"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject_full","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Tu pedido de {site_title} desde {order_date} ha sido reembolsado"},{"id":"subject_partial","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Tu pedido de {site_title} desde {order_date} ha sido parcialmente reembolsado"},{"id":"heading_full","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Tu pedido ha sido totalmente devuelto"},{"id":"heading_partial","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Tu pedido ha sido devuelto parcialmente"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Factura del pedido # {order_number} de {order_date}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Recibo del pedido {order_number}"},{"id":"subject_paid","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Tu pedido en {site_title} del {order_date}"},{"id":"heading_paid","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}, {order_date}, {order_number}<\\/code>","value":"Informaci\\u00f3n del pedido {order_number} "},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Nota a\\u00f1adida a tu pedido en {site_title} del {order_date}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Se ha a\\u00f1adido una nota a tu pedido"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Restablecer contrase\\u00f1a en {site_title}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Instrucciones para reestablecer la contrase\\u00f1a"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"},{"id":"enabled","description":"","value":"yes"},{"id":"subject","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Tu cuenta en {site_title}"},{"id":"heading","description":"Marcadores de posici\\u00f3n disponibles: <code>{site_title}<\\/code>","value":"Bienvenido a {site_title}"},{"id":"email_type","description":"Elige el formato en el que se enviar\\u00e1n los correos electr\\u00f3nicos.","value":"html"}]', '2017-12-16 14:01:58', '2018-02-28 00:04:33'),
(73, 'XTR_COMPAMY_NAME', NULL, 'La Extranatural', '2018-02-08 11:52:02', '2018-02-08 11:52:02'),
(74, 'DEF_BOM_MEASURE_UNIT', NULL, '1', '2018-02-24 16:50:01', '2018-02-24 16:50:01'),
(75, 'WOOC_ORDERS_PER_PAGE', 'Los pedidos de WooCommerce se recuperan en esta cantidad por página', '10', '2018-02-28 10:11:18', '2018-03-03 19:02:35');

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

--
-- Volcado de datos para la tabla `countries`
--

INSERT INTO `countries` (`id`, `name`, `iso_code`, `contains_states`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'España', 'ES', 1, 1, '2015-04-01 16:40:22', '2017-09-20 16:40:22', NULL),
(2, 'Estados Unidos', 'US', 1, 1, '2015-04-01 16:40:26', '2017-09-20 16:40:26', NULL);

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

--
-- Volcado de datos para la tabla `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `iso_code`, `iso_code_num`, `sign`, `signPlacement`, `thousandsSeparator`, `decimalSeparator`, `decimalPlaces`, `blank`, `conversion_rate`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(15, 'Euro', 'EUR', '978', '€', 1, '.', ',', 2, 0, '1.000000', 1, '2017-09-22 14:29:16', '2017-09-22 14:29:16', NULL),
(16, 'Dollar', 'USD', '840', '$', 0, ',', '.', 2, 0, '1.220000', 1, '2017-09-22 14:29:16', '2017-09-22 14:29:16', NULL),
(17, 'Pound Sterling', 'GBP', '826', '£', 0, ',', '.', 2, 0, '0.880000', 1, '2017-09-22 14:29:16', '2017-09-22 14:29:16', NULL),
(18, 'Yen', 'JPY', '392', '¥', 0, ',', '', 0, 0, '130.000000', 1, '2017-09-22 14:29:16', '2017-09-22 14:29:16', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_orders`
--

CREATE TABLE `customer_orders` (
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
-- Volcado de datos para la tabla `customer_orders`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_order_lines`
--

CREATE TABLE `customer_order_lines` (
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
-- Volcado de datos para la tabla `customer_order_lines`
--

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

--
-- Volcado de datos para la tabla `languages`
--

INSERT INTO `languages` (`id`, `name`, `iso_code`, `language_code`, `date_format_lite`, `date_format_full`, `date_format_lite_view`, `date_format_full_view`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'English', 'en', 'en-en', 'm/j/Y', 'm/j/Y H:i:s', 'm/j/Y', 'm/j/Y H:i:s', 1, '2017-09-20 16:39:27', '2017-09-20 16:39:27', NULL),
(2, 'Español', 'es', 'es-es', 'd/m/Y', 'd/m/Y H:i:s', 'd/m/yy', 'd/m/yy H:i:s', 1, '2017-09-20 16:39:28', '2017-09-26 10:24:33', NULL);

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

--
-- Volcado de datos para la tabla `measure_units`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
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
(9, '2018_02_12_094814_create_products_table', 3),
(10, '2018_02_15_085643_create_product_b_o_ms_table', 4),
(11, '2018_02_15_090526_create_product_b_o_m_lines_table', 4),
(14, '2018_02_15_090948_create_b_o_m_items_table', 5),
(20, '2018_02_27_120350_create_production_sheets_table', 7),
(22, '2018_02_27_120331_create_customer_order_lines_table', 8),
(23, '2018_02_27_120305_create_customer_orders_table', 9),
(24, '2020_02_27_120350_update_production_sheets_table', 10),
(25, '2018_02_27_120210_create_production_orders_table', 11),
(26, '2018_02_27_120244_create_production_order_lines_table', 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
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

--
-- Volcado de datos para la tabla `production_orders`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `production_order_lines`
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
-- Volcado de datos para la tabla `production_order_lines`
--


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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_dirty` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `production_sheets`
--


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
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `products`
--


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

--
-- Volcado de datos para la tabla `product_b_o_ms`
--


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

--
-- Volcado de datos para la tabla `product_b_o_m_lines`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sequences`
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
-- Volcado de datos para la tabla `sequences`
--

INSERT INTO `sequences` (`id`, `name`, `model_name`, `sequenceable_type`, `prefix`, `length`, `separator`, `next_id`, `last_date_used`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Recuentos de Stock', 'StockCount', '', 'REC', 6, '-', 2, '2017-10-02 09:30:50', 1, '2017-09-30 19:15:15', '2017-10-02 09:30:50', NULL),
(2, 'Facturas Nacional', 'CustomerInvoice', '', 'NAC', 5, '-', 12, '2018-01-30 13:38:37', 1, '2017-10-05 12:07:21', '2018-01-30 13:38:37', NULL);

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

--
-- Volcado de datos para la tabla `states`
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
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `home_page`, `firstname`, `lastname`, `remember_token`, `is_admin`, `active`, `language_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'wasp', 'admin@abillander.com', '$2y$10$b3bC4GwpaXr2Si/.jFHp.O/NTw1mGop/jv8ECybAYCbsxuI/5qamu', '/', 'Lara', 'Billander', 'I5FyC4lqeFwxK9HEf1TK1yO9JWHJ2PLMCVJhENk8Kz9qMkdeSONbLu4zRVk2', 1, 1, 1, '2018-02-07 16:42:55', '2018-02-07 16:42:55', NULL);

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
-- Volcado de datos para la tabla `work_centers`
--


--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `b_o_m_items`
--
ALTER TABLE `b_o_m_items`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `currencies`
--
ALTER TABLE `currencies`
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
-- Indices de la tabla `languages`
--
ALTER TABLE `languages`
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
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

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
-- Indices de la tabla `sequences`
--
ALTER TABLE `sequences`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
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
-- AUTO_INCREMENT de la tabla `b_o_m_items`
--
ALTER TABLE `b_o_m_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT de la tabla `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `customer_order_lines`
--
ALTER TABLE `customer_order_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT de la tabla `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `measure_units`
--
ALTER TABLE `measure_units`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT de la tabla `production_orders`
--
ALTER TABLE `production_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT de la tabla `production_order_lines`
--
ALTER TABLE `production_order_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT de la tabla `production_sheets`
--
ALTER TABLE `production_sheets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de la tabla `product_b_o_ms`
--
ALTER TABLE `product_b_o_ms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `product_b_o_m_lines`
--
ALTER TABLE `product_b_o_m_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `sequences`
--
ALTER TABLE `sequences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `states`
--
ALTER TABLE `states`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `work_centers`
--
ALTER TABLE `work_centers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
