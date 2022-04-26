<?php

namespace App\Providers;

use App\Models\Carrier;
use App\Models\Category;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Currency;
use App\Models\CustomerGroup;
use App\Models\CustomerInvoice;
use App\Models\CustomerOrder;
use App\Models\CustomerQuotation;
use App\Models\CustomerShippingSlip;
use App\Models\Ecotax;
use App\Models\EcotaxRule;
use App\Models\Language;
use App\Models\Manufacturer;
use App\Models\MeasureUnit;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\PriceList;
use App\Models\Product;
use App\Models\ProductionSheet;
use App\Models\SalesRep;
use App\Models\Sequence;
use App\Models\ShippingMethod;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\SupplierInvoice;
use App\Models\SupplierOrder;
use App\Models\SupplierShippingSlip;
use App\Models\Tax;
use App\Models\TaxRule;
use App\Models\Template;
use App\Models\Tool;
use App\Models\Warehouse;
use App\Models\WarehouseShippingSlip;
use App\Models\WorkCenter;
use DB;
use Illuminate\Support\ServiceProvider;

/*
|--------------------------------------------------------------------------
| Application View Composers
|--------------------------------------------------------------------------
|
| Load View Composers.
|
*/

class ViewComposerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//

		// Languages
		view()->composer(array('users.create', 'users.edit', 'suppliers._form', 'companies._form'), function($view) {
		    
		    $view->with('languageList', Language::pluck('name', 'id')->toArray());
		    
		});

		// Measure Unit types
		view()->composer(array('measure_units._form'), function($view) {

		    $list = MeasureUnit::getTypeList();

		    $view->with('measureunit_typeList', $list);
		    
		});

		// Document Types
		view()->composer(array('sequences.index', 'sequences.create', 'sequences.edit'), function($view) {
		    
		    $view->with('document_typeList', Sequence::documentList());
		    
		});

		//Templateable Document Types
		view()->composer(array('templates.index', 'templates.create', 'templates.edit'), function($view) {
		    
		    $view->with('templateable_document_typeList', Template::documentList());
		    
		});

		// Invoice Types
		view()->composer(array('customer_invoices.create'), function($view) {
		    
		    $view->with('customer_invoice_typeList', CustomerInvoice::getTypeList());
		    
		});

		// Languages
		view()->composer(array('users.create', 'users.edit', 'configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('languageList', Language::pluck('name', 'id')->toArray());
		    
		});

		// Measure Units
		view()->composer(array('products.index', 'products.create', 'products._panel_main_data', 'product_boms._panel_create_bom', 'product_boms._panel_bom', 'ingredients.index', 'ingredients.create', 'ingredients._panel_main_data', 'configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('measure_unitList', MeasureUnit::pluck('name', 'id')->toArray());
		    
		});

		// Work Centers
		view()->composer(array('products._panel_manufacturing', 'production_sheets._panel_customer_orders', 'production_sheets._modal_production_order_form', 'production_sheets._modal_production_order_edit'), function($view) {
		    
		    $view->with('work_centerList', WorkCenter::pluck('name', 'id')->toArray());
		    
		});

		// Tools
		view()->composer(array('products._panel_manufacturing', 'production_sheets._modal_production_order_form', 'production_sheets._modal_production_order_edit'), function($view) {
		    
		    $view->with('toolList', Tool::all()->pluck('full_name', 'id')->toArray());
		    
		});

		// Suppliers
		view()->composer(array('products._panel_purchases'), function($view) {
		    
		    $view->with('supplierList', Supplier::pluck('name_fiscal', 'id')->toArray());
		    
		});

		// Manufacturers
		view()->composer(array('products._panel_purchases'), function($view) {
		    
		    $view->with('manufacturerList', Manufacturer::pluck('name', 'id')->toArray());
		    
		});

		// Payment Methods
		view()->composer(array('customers.edit', 'customer_quotations.create', 'customer_quotations.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_shipping_slips.create', 'customer_shipping_slips.edit', 'customer_invoices.create', 'customer_invoices.edit', 'customer_groups.create', 'customer_groups.edit', 'configuration_keys.key_group_2', 
			'suppliers.edit', 'supplier_quotations.create', 'supplier_quotations.edit', 'supplier_orders.create', 'supplier_orders.edit', 'supplier_shipping_slips.create', 'supplier_shipping_slips.edit', 'supplier_invoices.create', 'supplier_invoices.edit'), function($view) {
		    
		    $view->with('payment_methodList', PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray());
		    
		});

		// PaymentTypes
		view()->composer(array('configuration_keys.key_group_2', 'payment_methods._form', 'customer_vouchers.edit', 'customer_vouchers._form_pay', 'supplier_vouchers.edit', 'supplier_vouchers._form_pay'), function($view) {
		    
		    $view->with('payment_typeList', PaymentType::orderby('name', 'desc')->pluck('name', 'id')->toArray());
		    
		});

		// Currencies
		view()->composer(array('customers.edit', 'products.edit', 'customer_quotations.create', 'customer_quotations.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_shipping_slips.create', 'customer_shipping_slips.edit', 'customer_invoices.create', 'customer_invoices.edit', 'companies._form', 'price_rules.create', 'price_lists._form', 'customer_groups.create', 'customer_groups.edit', 'stock_movements.create', 'configuration_keys.key_group_2', 
			'suppliers.edit', 'supplier_quotations.create', 'supplier_quotations.edit', 'supplier_orders.create', 'supplier_orders.edit', 'supplier_shipping_slips.create', 'supplier_shipping_slips.edit', 'supplier_invoices.create', 'supplier_invoices.edit'), function($view) {
		    
		    $view->with('currencyList', Currency::pluck('name', 'id')->toArray());
		    
		});

		// Sales Representatives
		view()->composer(array('customers.edit', 'customer_quotations.create', 'customer_quotations.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_shipping_slips.create', 'customer_shipping_slips.edit', 'customer_invoices.create', 'customer_invoices.edit'), function($view) {
		    
		    $view->with('salesrepList', SalesRep::pluck('alias', 'id')->toArray());
		    
		});

		// Warehouses
		view()->composer(array('products.create', 'stock_movements.index', 'stock_movements.create', 'stock_counts._form', 'stock_adjustments.create', 'configuration_keys.key_group_2', 'customer_quotations.create', 'customer_quotations.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_shipping_slips.create', 'customer_shipping_slips.edit', 'customer_invoices.create', 'customer_invoices.edit', 
			'supplier_quotations.create', 'supplier_quotations.edit', 'supplier_orders.create', 'supplier_orders.edit', 'supplier_shipping_slips.create', 'supplier_shipping_slips.edit', 'supplier_invoices.create', 'supplier_invoices.edit'), function($view) {
/*		    
		    $whList = Warehouse::with('address')->get();

		    $list = [];
		    foreach ($whList as $wh) {
		    	$list[$wh->id] = $wh->address->alias;
		    }

		    $view->with('warehouseList', $list);
*/
		    // $view->with('warehouseList', Warehouse::pluck('name', 'id')->toArray());
//		    $whList = Warehouse::select('id', DB::raw("concat('[', alias, '] ', notes) as full_name"))->pluck('full_name', 'id')->toArray();
		    $whList = Warehouse::select('id', DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();
		    $view->with('warehouseList', $whList);
		    
		});

		// Carriers
		view()->composer(array('shipping_methods._form', 'customer_shipping_slips.index', 'customer_shipping_slips.edit'), function($view) {
		    
		    $view->with('carrierList', Carrier::pluck('name', 'id')->toArray());
		    
		});

		// Shipping Methods
		view()->composer(array('customers.edit', 'addresses._form', 'customer_quotations.create', 'customer_quotations.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_shipping_slips.create', 'customer_shipping_slips.edit', 'customer_invoices.create', 'customer_invoices.edit', 'configuration_keys.key_group_2', 
			'supplier_quotations.create', 'supplier_quotations.edit', 'supplier_orders.create', 'supplier_orders.edit', 'supplier_shipping_slips.create', 'supplier_shipping_slips.edit', 'supplier_invoices.create', 'supplier_invoices.edit'), function($view) {
		    
		    $view->with('shipping_methodList', ShippingMethod::pluck('name', 'id')->toArray());
		    
		});

		// Companiers
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('companyList', Company::pluck('name_fiscal', 'id')->toArray());
		    
		});

		// Customer Groups
		view()->composer(array('customers.index', 'customers.create', 'customers.edit', 'price_rules.index', 'price_rules.create'), function($view) {
		    
		    $view->with('customer_groupList', CustomerGroup::pluck('name', 'id')->toArray());
		    
		});

		// Countries
		view()->composer(array('addresses._form', 'addresses._form_fields_model_related', 'addresses._form_fields_model_customer', 'addresses._form_fields_model_supplier', 'tax_rules._form', 'ecotax_rules._form', 'configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('countryList', Country::orderby('name', 'asc')->pluck('name', 'id')->toArray());
		    
		});

		// Taxes
		view()->composer(array('customer_quotations.create', 'customer_quotations.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_shipping_slips.create', 'customer_shipping_slips.edit', 'customer_invoices.create', 'customer_invoices.edit', 'products.create', 'products.edit', 'configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('taxList', Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray());
		    
		});

		view()->composer(array('products.create', 'products.edit', 'price_list_lines.edit', 'customer_quotations.create', 'customer_quotations.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_shipping_slips.create', 'customer_shipping_slips.edit', 'customer_invoices.create', 'customer_invoices.edit', 
			'supplier_quotations.create', 'supplier_quotations.edit', 'supplier_orders.create', 'supplier_orders.edit', 'supplier_shipping_slips.create', 'supplier_shipping_slips.edit', 'supplier_invoices.create', 'supplier_invoices.edit'), function($view) {

		    // https://laracasts.com/discuss/channels/eloquent/eloquent-model-lists-id-and-a-custom-accessor-field
		    $view->with('taxpercentList', \Arr::pluck(Tax::all(), 'percent', 'id'));
		    
		});

		view()->composer(array('customer_quotations.create', 'customer_quotations.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit'), function($view) {
/*
		    $list = Tax::select(
//		        \DB::raw("(percent + extra_percent) AS percent, id")
		        \DB::raw("(percent) AS percent, id")
		    )->pluck('percent', 'id');

		    $view->with('taxeqpercentList', $list);
*/		    
		    $view->with('taxpercentList', \Arr::pluck(Tax::all(), 'percent', 'id'));
		});

		// Tax Rule types
		view()->composer(array('tax_rules._form', 'tax_rules.index'), function($view) {

		    $list = TaxRule::getTypeList();

		    $view->with('tax_rule_typeList', $list);
		});

		// Ecotaxes
		view()->composer(array('products.create', 'products.edit'), function($view) {
		    
		    $view->with('ecotaxList', Ecotax::orderby('name', 'desc')->pluck('name', 'id')->toArray());
		    
		});

		// Ecotax Rule types
		view()->composer(array('ecotax_rules._form', 'ecotax_rules.index'), function($view) {

		    $list = EcotaxRule::getTypeList();

		    $view->with('ecotax_rule_typeList', $list);
		});

		// Price Lists
		view()->composer(array('customers.edit'), function($view) {
		    
		    $view->with('price_listList', PriceList::pluck('name', 'id')->toArray());
		    
		});

		// Price List types
		view()->composer(array('price_lists._form', 'price_lists.index'), function($view) {

		    $list = PriceList::getTypeList();

		    $view->with('price_list_typeList', $list);
		});

		// Product types
		view()->composer(array('products._form_create'), function($view) {
/*		    
		    $list = [];
		    foreach (Product::$types as $type) {
		    	$list[$type] = l($type, [], 'appmultilang');;
		    }
*/
		    $list = Product::getTypeList();

		    $view->with('product_typeList', $list);
		    // $view->with('warehouseList', Warehouse::pluck('name', 'id')->toArray());
		    
		});

		// Product procurement types
		view()->composer(array('products.index', 'products._form_create', 'products._panel_main_data'), function($view) {

		    $list1 = Product::getProcurementTypeList();

		    $view->with('product_procurementtypeList', $list1);
		    
		});

		// Product MRP types
		view()->composer(array('products.index', 'products._form_create', 'products._panel_inventory'), function($view) {

		    $list1 = Product::getMrpTypeList();

		    $view->with('product_mrptypeList', $list1);
		    
		});

		// Categories
		view()->composer(array('products.index', 'products.create', 'products._panel_main_data', 'products_reorder.index', 'configuration_keys.key_group_2'), function($view) {
		    
		    if ( Configuration::get('ALLOW_PRODUCT_SUBCATEGORIES') ) {
		    	$tree = [];
		    	$categories =  Category::where('parent_id', '=', '0')->with('children')->orderby('name', 'asc')->get();
		    	
		    	foreach($categories as $category) {
		    		$label = $category->name;

		    		// Prevent duplicate names
		    		while ( array_key_exists($label, $tree))
		    			$label .= ' ';

		    		$tree[$label] = $category->children()->orderby('position', 'asc')->pluck('name', 'id')->toArray();
		    		// foreach($category->children as $child) {
		    			// $tree[$category->name][$child->id] = $child->name;
		    		// }
		    	}
		    	// abi_r($tree, true);
		    	$view->with('categoryList', $tree);

		    } else {
		    	// abi_r(Category::where('parent_id', '=', '0')->orderby('name', 'asc')->pluck('name', 'id')->toArray(), true);
		    	$view->with('categoryList', Category::where('parent_id', '=', '0')->orderby('position', 'asc')->pluck('name', 'id')->toArray());
		    }
		    
		});

		// Stock Movement Types
		view()->composer(array('stock_movements.index', 'stock_movements.create'), function($view) {
		    
		    $view->with('movement_typeList', StockMovement::stockmovementList());
		    
		});

		// Themes
		view()->composer(array('users.create', 'users.edit', 'configuration_keys.key_group_3'), function($view) {
		    
		    
			$directories = \File::directories(resource_path().'/theme');
			$directories = array_diff($directories, ['.', '..']);

			$themeList = [];

			foreach ($directories as $dir) {
				# 
				$segments = array_reverse(explode('/', $dir));
				$theme = $segments[0];

				$themeList[$theme] = $theme;
			}
			
			ksort($themeList);

		    $view->with('themeList', $themeList);
		    
		});

		// Sequences
		
		// Customer Quotations Sequencess
		view()->composer(array('configuration_keys.key_group_2', 'configuration_keys.key_group_5'), function($view) {
		    
		    $view->with('quotations_sequenceList', Sequence::listFor( CustomerQuotation::class ));
		    
		});
		
		// Customer Orders Sequencess
		view()->composer(array('configuration_keys.key_group_2', 'configuration_keys.key_group_5'), function($view) {
		    
		    $view->with('orders_sequenceList', Sequence::listFor( CustomerOrder::class ));
		    
		    $view->with('supplier_orders_sequenceList', Sequence::listFor( SupplierOrder::class ));
		    
		});
		
		// Customer Shipping Slips Sequencess
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('shipping_slips_sequenceList', Sequence::listFor( CustomerShippingSlip::class ));
		    
		    $view->with('supplier_shipping_slips_sequenceList', Sequence::listFor( SupplierShippingSlip::class ));
		    
		});
		
		// Warehouse Shipping Slips Sequencess
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('warehouse_shipping_slips_sequenceList', Sequence::listFor( WarehouseShippingSlip::class ));
		    
		});
		
		// Customer Invoices Sequencess
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('invoices_sequenceList', Sequence::listFor( CustomerInvoice::class ));
		    
		    $view->with('supplier_invoices_sequenceList', Sequence::listFor( SupplierInvoice::class ));
		    
		});

		// Templates

		// Supplier Orders Template
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('supplier_orders_templateList', Template::listFor( SupplierOrder::class ));
		    
		});

		// Supplier Shipping Slips Template
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('supplier_shipping_slips_templateList', Template::listFor( SupplierShippingSlip::class ));
		    
		});

		// Supplier Invoices Template
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('supplier_invoices_templateList', Template::listFor( SupplierInvoice::class ));
		    
		});


		// Customer Quotations Template
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('quotations_templateList', Template::listFor( CustomerQuotation::class ));
		    
		});

		// Customer Orders Template
		view()->composer(array('configuration_keys.key_group_2', 'customers.edit'), function($view) {
		    
		    $view->with('orders_templateList', Template::listFor( CustomerOrder::class ));
		    
		});

		// Customer Shipping Slips Template
		view()->composer(array('configuration_keys.key_group_2', 'customers.edit'), function($view) {
		    
		    $view->with('shipping_slips_templateList', Template::listFor( CustomerShippingSlip::class ));
		    
		});

		// Warehouse Shipping Slips Template
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('warehouse_shipping_slips_templateList', Template::listFor( WarehouseShippingSlip::class ));
		    
		});

		// Customer Invoices Template
		view()->composer(array('configuration_keys.key_group_2', 'customers.edit'), function($view) {
		    
		    $view->with('invoices_templateList', Template::listFor( CustomerInvoice::class ));
		    
		});

		// Customer Center Order Template
		view()->composer(array('configuration_keys.key_group_5'), function($view) {
		    
		    $view->with('orders_templateList', Template::listFor( CustomerOrder::class ));
		    
		});
		

		// Months
		view()->composer(array('customers._panel_commercial', 'absrc.customers._panel_commercial'), function($view) {
		    
		    $a=l('monthNames', [], 'appmultilang');

			$monthList = [];
			for($m=1; $m<=12; ++$m){
				$monthList[$m] = $a[$m-1];
			}

			$view->with('monthList', $monthList);
		    
		});


/* ******************************************************************************************************** */		


		// Available Production Sheets
		view()->composer(array('customer_orders.index_export_mfg',  'production_sheets._modal_customer_order_move'), function($view) {
		    
		    $availableProductionSheets = ProductionSheet::isOpen()->orderBy('due_date', 'asc')->pluck('due_date', 'id')->toArray();

		    array_walk( $availableProductionSheets, function (&$v, $k) { $v = abi_date_form_short($v); } );

		    $view->with('availableProductionSheetList', $availableProductionSheets);
		    
		});
	}

	public function boot_legacy()
	{


/*		// Sequences
		view()->composer(array('customer_invoices.create', 'customer_invoices.edit', 'customer_groups.create', 'customer_groups.edit'), function($view) {
		    
		    $view->with('sequenceList', Sequence::pluck('name', 'id')->toArray());
		    
		}); */

		// Invoice Template
		view()->composer(array('customers.edit', 'customer_invoices.create', 'customer_invoices.edit', 'customer_groups.create', 'customer_groups.edit'), function($view) {
		    
		    $view->with('customerinvoicetemplateList', Template::where('model_name', '=', CustomerInvoice::class)->pluck('name', 'id')->toArray());
		    
		});

		// Document Types
		view()->composer(array('sequences.index', 'sequences.create', 'sequences.edit'), function($view) {
		    
		    $view->with('document_typeList', Sequence::documentList());
		    
		});
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

}
