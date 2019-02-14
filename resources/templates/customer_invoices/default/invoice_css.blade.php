@php

/*
extract( $wpo_wcpdf_templates->get_footer_height_page_bottom('2cm')); // $footer_height & $page_bottom



		public function get_footer_height_page_bottom ( $default_height = '5cm' ) {
			global $wpo_wcpdf;
			if ( isset($wpo_wcpdf->settings->template_settings['footer_height']) && !empty($wpo_wcpdf->settings->template_settings['footer_height']) ) {
				$footer_height = $wpo_wcpdf->settings->template_settings['footer_height'];
			} else {
				$footer_height = $default_height;
			}

			// calculate bottom page margin
			$page_bottom = floatval($footer_height);

			// convert to cm
			if (strpos($footer_height,'in') !== false) {
				$page_bottom = $page_bottom * 2.54;
			} elseif (strpos($footer_height,'mm') !== false) {
				$page_bottom = $page_bottom / 10;
			}

			// add 1 + cm
			$page_bottom = ($page_bottom + 1).'cm';

			return compact( 'footer_height', 'page_bottom' );
		}
*/

$footer_height = '2cm';
$footer_height = '1.5cm';

			// calculate bottom page margin
			$page_bottom = floatval($footer_height);

			// convert to cm
			if (strpos($footer_height,'in') !== false) {
				$page_bottom = $page_bottom * 2.54;
			} elseif (strpos($footer_height,'mm') !== false) {
				$page_bottom = $page_bottom / 10;
			}

			// add 1 + cm
			$page_bottom = ($page_bottom + 1).'cm';

@endphp


/* Main Body */
@font-face {
	font-family: 'Open Sans';
	font-style: normal;
	font-weight: normal;
	src: local('Open Sans'), local('OpenSans'), url(http://themes.googleusercontent.com/static/fonts/opensans/v7/yYRnAC2KygoXnEC8IdU0gQLUuEpTyoUstqEm5AMlJo4.ttf) format('truetype');
}
@font-face {
	font-family: 'Open Sans';
	font-style: normal;
	font-weight: bold;
	src: local('Open Sans Bold'), local('OpenSans-Bold'), url(http://themes.googleusercontent.com/static/fonts/opensans/v7/k3k702ZOKiLJc3WVjuplzMDdSZkkecOE1hvV7ZHvhyU.ttf) format('truetype');
}
@font-face {
	font-family: 'Open Sans';
	font-style: italic;
	font-weight: normal;
	src: local('Open Sans Italic'), local('OpenSans-Italic'), url(http://themes.googleusercontent.com/static/fonts/opensans/v7/O4NhV7_qs9r9seTo7fnsVCZ2oysoEQEeKwjgmXLRnTc.ttf) format('truetype');
}
@font-face {
	font-family: 'Open Sans';
	font-style: italic;
	font-weight: bold;
	src: local('Open Sans Bold Italic'), local('OpenSans-BoldItalic'), url(http://themes.googleusercontent.com/static/fonts/opensans/v7/PRmiXeptR36kaC0GEAetxrQhS7CD3GIaelOwHPAPh9w.ttf) format('truetype');
}

@page {
	margin-top: 1cm;
	margin-bottom: {{ $page_bottom }};
	margin-left: 2cm;
	margin-right: 2cm;
}
body {
	background: #fff;
	color: #000;
	margin: 0cm;
	font-family: 'Open Sans', sans-serif;
	font-size: 9pt;
	line-height: 100%; /* fixes inherit dompdf bug */
}

h1, h2, h3, h4 {
	font-weight: bold;
	margin: 0;
}

h1 {
	font-size: 16pt;
	margin: 5mm 0;
}

h2 {
	font-size: 14pt;
}

h3, h4 {
	font-size: 9pt;
}


ol,
ul {
	list-style: none;
	margin: 0;
	padding: 0;
}

li,
ul {
	margin-bottom: 0.75em;
}

p {
	margin: 0;
	padding: 0;
}

p + p {
	margin-top: 1.25em;
}

a { 
	border-bottom: 1px solid; 
	text-decoration: none; 
}

span.checkbox {
	display: inline-block;
	width: 3mm;
	height: 3mm;
	border: 1px solid black;
	background-color: white;
}

/* Basic Table Styling */
table {
	border-collapse: collapse;
	border-spacing: 0;
	page-break-inside: always;
	border: 0;
	margin: 0;
	padding: 0;
}

th, td {
	vertical-align: top;
	text-align: left;
}

table.container {
	width:100%;
	border: 0;
}

tr.no-borders,
td.no-borders {
	border: 0 !important;
	border-top: 0 !important;
	border-bottom: 0 !important;
	padding: 0 !important;
	width: auto;
}

/* Header */
table.head {
	margin-bottom: 12mm;
}

td.header img {
	max-height: 3cm;
	width: auto;
}

td.header {
	font-size: 16pt;
	font-weight: 700;
}

td.shop-info {
	width: 40%;
}
.document-type-label {
	text-transform: uppercase;
}

/* Recipient addressses & order data */
table.order-data-addresses {
	width: 100%;
	margin-bottom: 10mm;
}

td.order-data {
	width: 40%;
}

.invoice .shipping-address {
	width: 30%;
}

.shipping-slip .billing-address {
	width: 30%;
}

td.order-data table th {
	font-weight: normal;
	padding-right: 2mm;
}

/* Order details */
table.order-details,
table.notes-totals {
	width:100%;
	margin-bottom: 8mm;
}

.order-details .cb {
	width: 3.5mm;
}
.order-details .sku,
.order-details .weight,
.order-details .thumbnail,
.order-details .quantity,
.order-details .price,
.order-details .vat,
.order-details .discount,
.order-details .tax_rate,
.order-details .total {
	width: 10%;
}

.order-details .position {
	width: 5%;
}

.order-details .last-column.total {
	text-align: right !important;
}

.order-details .thumbnail img {
	width: 13mm;
	height: auto;
}

.order-details tr,
.notes-totals tr {
	page-break-inside: always;
	page-break-after: auto;	
}

.notes-totals td,
.notes-totals th,
.order-details td,
.order-details th {
	border-bottom: 1px #ccc solid;
	padding: 0.375em;
}

.notes-totals th,
.order-details th {
	font-weight: bold;
	text-align: left;
}

.order-details thead th {
	color: white;
	background-color: black;
	border-color: black;
}

/* product bundles compatibility */
.order-details tr.bundled-item td.description {
	padding-left: 5mm;
}

.order-details tr.product-bundle td,
.order-details tr.product-bundle th,
.order-details tr.bundled-item td,
.order-details tr.bundled-item th {
	border-bottom: 0;
}

.order-details tr.bundled-item:last-child td,
.order-details tr.bundled-item:last-child th {
	border-bottom: 1px #ccc solid;
}

dl {
	margin: 4px 0;
}

dt, dd, dd p {
	display: inline;
	font-size: 7pt;
	line-height: 7pt;
}

dd {
	margin-left: 5px;
}

dd:after {
	content: "\A";
	white-space: pre;
}

/* Notes & Totals */
.customer-notes {
	margin-top: 5mm;
}

table.totals {
	width: 100%;
	margin-top: 5mm;
}

table.totals th,
table.totals td {
	border: 0;
	border-top: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
}

table.totals th.description,
table.totals td.price {
	width: 50%;
}

table.totals td.price {
	text-align: right;
}

table.order-details td.quantity {
	text-align: center;
}

table.totals .grand-total td,
table.totals .grand-total th {
	border-top: 2px solid #000;
	border-bottom: 2px solid #000;
	font-weight: bold;
}

table.totals tr.payment_method {
	display: none;
}

/* Footer Imprint */
#footer {
	position: absolute;
	bottom: -{{ $footer_height }};
	left: 0;
	right: 0;
	height: {{ $footer_height }};
	text-align: center;
	border-top: 0.1mm solid gray;
	margin-bottom: 0;
	padding-top: 2mm;
}

/* page numbers */
.pagenum:before {
	content: counter(page);
}
.pagenum,.pagecount {
	font-family: sans-serif;
}


/* Custom */
/*
.totals-cell {
	width: 50% !important
}

body {
	font-size: 8pt;
}

td.header img {
	margin-left:-4.5pc;
}
*/