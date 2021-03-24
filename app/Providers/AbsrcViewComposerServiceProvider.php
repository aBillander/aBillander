<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

/*
|--------------------------------------------------------------------------
| Application View Composers
|--------------------------------------------------------------------------
|
| Load View Composers.
|
*/

class AbsrcViewComposerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//

		// Categories
		view()->composer(array('absrc.catalogue.index'), function($view) {
		    
		    if ( \App\Configuration::get('ALLOW_PRODUCT_SUBCATEGORIES') ) {
		    	$tree = [];
		    	$categories =  \App\Category::where('parent_id', '=', '0')->with('children')->orderby('name', 'asc')->get();
		    	
		    	foreach($categories as $category) {
		    		$tree[$category->name] = $category->children()->orderby('position', 'asc')->pluck('name', 'id')->toArray();
		    		// foreach($category->children as $child) {
		    			// $tree[$category->name][$child->id] = $child->name;
		    		// }
		    	}
		    	// abi_r($tree, true);
		    	$view->with('categoryList', $tree);

		    } else {
		    	// abi_r(\App\Category::where('parent_id', '=', '0')->orderby('name', 'asc')->pluck('name', 'id')->toArray(), true);
		    	$view->with('categoryList', \App\Category::where('parent_id', '=', '0')->orderby('position', 'asc')->pluck('name', 'id')->toArray());
		    }
		    
		});

		// Manufacturers
		view()->composer(array('absrc.catalogue.index'), function($view) {
		    
		    $view->with('manufacturerList', \App\Manufacturer::pluck('name', 'id')->toArray());
		    
		});

		// Countries
		view()->composer(array('absrc.addresses._form'), function($view) {
		    
		    $view->with('countryList', \App\Country::orderby('name', 'asc')->pluck('name', 'id')->toArray());
		    
		});
	}


	public function boot_legacy()
	{
		//

		// Languages
		view()->composer(array('users.create', 'users.edit', 'suppliers._form', 'companies._form'), function($view) {
		    
		    $view->with('languageList', \App\Language::pluck('name', 'id')->toArray());
		    
		});

		// Measure Unit types
		view()->composer(array('measure_units._form'), function($view) {

		    $list = \App\MeasureUnit::getTypeList();

		    $view->with('measureunit_typeList', $list);
		    
		});

		// Document Types
		view()->composer(array('sequences.index', 'sequences.create', 'sequences.edit'), function($view) {
		    
		    $view->with('document_typeList', \App\Sequence::documentList());
		    
		});

		//Templateable Document Types
		view()->composer(array('templates.index', 'templates.create', 'templates.edit'), function($view) {
		    
		    $view->with('templateable_document_typeList', \App\Template::documentList());
		    
		});

		// Languages
		view()->composer(array('users.create', 'users.edit', 'configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('languageList', \App\Language::pluck('name', 'id')->toArray());
		    
		});

		// Measure Units
		view()->composer(array('products.index', 'products.create', 'products._panel_main_data', 'product_boms._panel_create_bom', 'product_boms._panel_bom', 'ingredients.index', 'ingredients.create', 'ingredients._panel_main_data', 'configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('measure_unitList', \App\MeasureUnit::pluck('name', 'id')->toArray());
		    
		});

		// Work Centers
		view()->composer(array('products._panel_manufacturing', 'production_sheets._panel_customer_orders', 'production_sheets._modal_production_order_form', 'production_sheets._modal_production_order_edit'), function($view) {
		    
		    $view->with('work_centerList', \App\WorkCenter::pluck('name', 'id')->toArray());
		    
		});

		// Suppliers
		view()->composer(array('products._panel_purchases'), function($view) {
		    
		    $view->with('supplierList', \App\Supplier::pluck('name_fiscal', 'id')->toArray());
		    
		});

		// Payment Methods
		view()->composer(array('customers.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit', 'customer_groups.create', 'customer_groups.edit', 'configuration_keys.key_group_2', 'suppliers._form'), function($view) {
		    
		    $view->with('payment_methodList', \App\PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray());
		    
		});

		// Currencies
		view()->composer(array('customers.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit', 'companies._form', 'price_lists._form', 'customer_groups.create', 'customer_groups.edit', 'stock_movements.create', 'configuration_keys.key_group_2', 'suppliers._form'), function($view) {
		    
		    $view->with('currencyList', \App\Currency::pluck('name', 'id')->toArray());
		    
		});

		// Sales Representatives
		view()->composer(array('customers.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit'), function($view) {
		    
		    $view->with('salesrepList', \App\SalesRep::pluck('alias', 'id')->toArray());
		    
		});

		// Warehouses
		view()->composer(array('products.create', 'stock_movements.index', 'stock_movements.create', 'stock_counts._form', 'stock_adjustments.create', 'configuration_keys.key_group_2', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit'), function($view) {
/*		    
		    $whList = \App\Warehouse::with('address')->get();

		    $list = [];
		    foreach ($whList as $wh) {
		    	$list[$wh->id] = $wh->address->alias;
		    }

		    $view->with('warehouseList', $list);
*/
		    // $view->with('warehouseList', \App\Warehouse::pluck('name', 'id')->toArray());
//		    $whList = \App\Warehouse::select('id', DB::raw("concat('[', alias, '] ', notes) as full_name"))->pluck('full_name', 'id')->toArray();
		    $whList = \App\Warehouse::select('id', DB::raw("concat('[', alias, '] ', name) as full_name"))->pluck('full_name', 'id')->toArray();
		    $view->with('warehouseList', $whList);
		    
		});

		// Carriers
		view()->composer(array('shipping_methods._form'), function($view) {
		    
		    $view->with('carrierList', \App\Carrier::pluck('name', 'id')->toArray());
		    
		});

		// Shipping Methods
		view()->composer(array('customers.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit', 'configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('shipping_methodList', \App\ShippingMethod::pluck('name', 'id')->toArray());
		    
		});

		// Companiers
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('companyList', \App\Company::pluck('name_fiscal', 'id')->toArray());
		    
		});

		// Customer Groups
		view()->composer(array('customers.index', 'customers.edit'), function($view) {
		    
		    $view->with('customer_groupList', \App\CustomerGroup::pluck('name', 'id')->toArray());
		    
		});

		// Taxes
		view()->composer(array('customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit', 'products.create', 'products.edit', 'configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('taxList', \App\Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray());
		    
		});

		view()->composer(array('products.create', 'products.edit', 'price_list_lines.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit'), function($view) {

		    // https://laracasts.com/discuss/channels/eloquent/eloquent-model-lists-id-and-a-custom-accessor-field
		    $view->with('taxpercentList', \Arr::pluck(\App\Tax::all(), 'percent', 'id'));
		    
		});

		view()->composer(array('customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit'), function($view) {
/*
		    $list = \App\Tax::select(
//		        \DB::raw("(percent + extra_percent) AS percent, id")
		        \DB::raw("(percent) AS percent, id")
		    )->pluck('percent', 'id');

		    $view->with('taxeqpercentList', $list);
*/		    
		    $view->with('taxpercentList', \Arr::pluck(\App\Tax::all(), 'percent', 'id'));
		});

		// Tax Rule types
		view()->composer(array('tax_rules._form', 'tax_rules.index'), function($view) {

		    $list = \App\TaxRule::getTypeList();

		    $view->with('tax_rule_typeList', $list);
		});

		// Price Lists
		view()->composer(array('customers.edit', 'customer_groups.create', 'customer_groups.edit'), function($view) {
		    
		    $view->with('price_listList', \App\PriceList::pluck('name', 'id')->toArray());
		    
		});

		// Price List types
		view()->composer(array('price_lists._form', 'price_lists.index'), function($view) {

		    $list = \App\PriceList::getTypeList();

		    $view->with('price_list_typeList', $list);
		});

		// Product types
		view()->composer(array('products._form_create'), function($view) {
/*		    
		    $list = [];
		    foreach (\App\Product::$types as $type) {
		    	$list[$type] = l($type, [], 'appmultilang');;
		    }
*/
		    $list = \App\Product::getTypeList();

		    $view->with('product_typeList', $list);
		    // $view->with('warehouseList', \App\Warehouse::pluck('name', 'id')->toArray());
		    
		});

		// Product procurement types
		view()->composer(array('products.index', 'products._form_create', 'products._panel_main_data'), function($view) {

		    $list1 = \App\Product::getProcurementTypeList();

		    $view->with('product_procurementtypeList', $list1);
		    
		});

		// Stock Movement Types
		view()->composer(array('stock_movements.index', 'stock_movements.create'), function($view) {
		    
		    $view->with('movement_typeList', \App\StockMovement::stockmovementList());
		    
		});

		// Sequences
		
		// Customer Orders Sequencess
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('orders_sequenceList', \App\Sequence::listFor( \App\CustomerOrder::class ));
		    
		});
		
		// Customer Shipping Slips Sequencess
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('shipping_slips_sequenceList', \App\Sequence::listFor( \App\CustomerShippingSlip::class ));
		    
		});
		
		// Customer Invoices Sequencess
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('invoices_sequenceList', \App\Sequence::listFor( \App\CustomerInvoice::class ));
		    
		});

		// Templates

		// Customer Shipping Slips Template
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('shipping_slips_templateList', \App\Template::listFor( \App\CustomerShippingSlip::class ));
		    
		});

		// Customer Invoices Template
		view()->composer(array('configuration_keys.key_group_2'), function($view) {
		    
		    $view->with('invoices_templateList', \App\Template::listFor( \App\CustomerInvoice::class ));
		    
		});
		

		// Months
		view()->composer(array('customers._panel_commercial'), function($view) {
		    
		    $a=l('monthNames', [], 'appmultilang');

			$monthList = [];
			for($m=1; $m<=12; ++$m){
				$monthList[$m] = $a[$m-1];
			}

			$view->with('monthList', $monthList);
		    
		});


/* ******************************************************************************************************** */		


		// Available Production Sheets
		view()->composer(array('customer_orders.index',  'production_sheets._modal_customer_order_move'), function($view) {
		    
		    $availableProductionSheets = \App\ProductionSheet::isOpen()->orderBy('due_date', 'asc')->pluck('due_date', 'id')->toArray();

		    array_walk( $availableProductionSheets, function (&$v, $k) { $v = abi_date_form_short($v); } );

		    $view->with('availableProductionSheetList', $availableProductionSheets);
		    
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
