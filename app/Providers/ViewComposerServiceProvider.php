<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

use Illuminate\Support\Arr;

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

		// Measure Unit types
		view()->composer(array('measure_units._form'), function($view) {

		    $list = \App\MeasureUnit::getTypeList();

		    $view->with('measureunit_typeList', $list);
		    
		});

		// Document Types
		view()->composer(array('sequences.index', 'sequences.create', 'sequences.edit'), function($view) {
		    
		    $view->with('document_typeList', \App\Sequence::documentList());
		    
		});

		// Languages
		view()->composer(array('users.create', 'users.edit'), function($view) {
		    
		    $view->with('languageList', \App\Language::pluck('name', 'id')->toArray());
		    
		});

		// Measure Units
		view()->composer(array('products.index', 'products.create', 'products._panel_main_data', 'product_boms._panel_create_bom', 'product_boms._panel_bom', 'ingredients.index', 'ingredients.create', 'ingredients._panel_main_data' ), function($view) {
		    
		    $view->with('measure_unitList', \App\MeasureUnit::pluck('name', 'id')->toArray());
		    
		});

		// Work Centers
		view()->composer(array('products._panel_manufacturing', 'production_sheets._modal_production_order_form', 'production_sheets._modal_production_order_edit'), function($view) {
		    
		    $view->with('work_centerList', \App\WorkCenter::pluck('name', 'id')->toArray());
		    
		});

		// Payment Methods
		view()->composer(array('customers.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit', 'customer_groups.create', 'customer_groups.edit'), function($view) {
		    
		    $view->with('payment_methodList', \App\PaymentMethod::orderby('name', 'desc')->pluck('name', 'id')->toArray());
		    
		});

		// Currencies
		view()->composer(array('customers.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit', 'companies._form', 'price_lists._form', 'customer_groups.create', 'customer_groups.edit', 'stock_movements.create'), function($view) {
		    
		    $view->with('currencyList', \App\Currency::pluck('name', 'id')->toArray());
		    
		});

		// Sales Representatives
		view()->composer(array('customers.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit'), function($view) {
		    
		    $view->with('salesrepList', \App\SalesRep::select(DB::raw('alias as name, id'))->pluck('name', 'id')->toArray());
		    
		});

		// Warehouses
		view()->composer(array('products.create', 'stock_movements.create', 'stock_counts.create', 'stock_adjustments.create', 'configuration_keys.key_group_2', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit'), function($view) {
		    
		    $whList = \App\Warehouse::with('address')->get();

		    $list = [];
		    foreach ($whList as $wh) {
		    	$list[$wh->id] = $wh->address->alias;
		    }

		    $view->with('warehouseList', $list);
		    // $view->with('warehouseList', \App\Warehouse::pluck('name', 'id')->toArray());
		    
		});

		// Carriers
		view()->composer(array('customers.edit', 'customer_orders.create', 'customer_orders.edit', 'customer_invoices.create', 'customer_invoices.edit', 'customer_groups.create', 'customer_groups.edit'), function($view) {
		    
		    $view->with('carrierList', \App\Carrier::pluck('name', 'id')->toArray());
		    
		});

		// Customer Groups
		view()->composer(array('customers.edit'), function($view) {
		    
		    $view->with('customer_groupList', \App\CustomerGroup::pluck('name', 'id')->toArray());
		    
		});

		// Countries
		view()->composer(array('addresses._form', 'addresses._form_fields_model_related', 'tax_rules._form'), function($view) {
		    
		    $view->with('countryList', \App\Country::orderby('name', 'asc')->pluck('name', 'id')->toArray());
		    
		});

		// Months
		view()->composer(array('customers._panel_commercial'), function($view) {
		    
		    $a=l('monthNames', [], 'layouts');

			$monthList = [];
			for($m=1; $m<=12; ++$m){
				$monthList[$m] = $a[$m-1];
			}

			$view->with('monthList', $monthList);
		    
		});


/* ******************************************************************************************************** */		


		// Available Production Sheets
		view()->composer(array('woo_connect::woo_orders.index',  'production_sheets._modal_customer_order_move'), function($view) {
		    
		    $availableProductionSheets = \App\ProductionSheet::isOpen()->orderBy('due_date', 'asc')->pluck('due_date', 'id')->toArray();

		    array_walk( $availableProductionSheets, function (&$v, $k) { $v = abi_date_form_short($v); } );

		    $view->with('availableProductionSheetList', $availableProductionSheets);
		    
		});
	}

	public function boot_legacy()
	{


/*		// Sequences
		view()->composer(array('customer_invoices.create', 'customer_invoices.edit', 'customer_groups.create', 'customer_groups.edit'), function($view) {
		    
		    $view->with('sequenceList', \App\Sequence::pluck('name', 'id')->toArray());
		    
		}); */

		// Price Lists
		view()->composer(array('customers.edit', 'customer_groups.create', 'customer_groups.edit'), function($view) {
		    
		    $view->with('price_listList', \App\PriceList::pluck('name', 'id')->toArray());
		    
		});

		// Price List types
		view()->composer(array('price_lists._form', 'price_lists.index'), function($view) {

		    $list = \App\PriceList::getTypeList();

		    $view->with('price_list_typeList', $list);
		});

		// Invoice Template
		view()->composer(array('customers.edit', 'customer_invoices.create', 'customer_invoices.edit', 'customer_groups.create', 'customer_groups.edit'), function($view) {
		    
		    $view->with('customerinvoicetemplateList', \App\Template::where('model_name', '=', '\App\CustomerInvoice')->pluck('name', 'id')->toArray());
		    
		});

		// Taxes
		view()->composer(array('customer_invoices.create', 'customer_invoices.edit', 'products.create', 'products.edit'), function($view) {
		    
		    $view->with('taxList', \App\Tax::orderby('name', 'desc')->pluck('name', 'id')->toArray());
		    
		});

		view()->composer(array('products.create', 'products.edit', 'price_list_lines.edit', 'customer_invoices.create', 'customer_invoices.edit'), function($view) {

		    // https://laracasts.com/discuss/channels/eloquent/eloquent-model-lists-id-and-a-custom-accessor-field
		    $view->with('taxpercentList', Arr::pluck(\App\Tax::all(), 'percent', 'id'));
		    
		});

		view()->composer(array('customer_invoices.create', 'customer_invoices.edit'), function($view) {
/*
		    $list = \App\Tax::select(
//		        \DB::raw("(percent + extra_percent) AS percent, id")
		        \DB::raw("(percent) AS percent, id")
		    )->pluck('percent', 'id');

		    $view->with('taxeqpercentList', $list);
*/		    
		    $view->with('taxpercentList', Arr::pluck(\App\Tax::all(), 'percent', 'id'));
		});

		// Tax Rule types
		view()->composer(array('tax_rules._form', 'tax_rules.index'), function($view) {

		    $list = \App\TaxRule::getTypeList();

		    $view->with('tax_rule_typeList', $list);
		});

		// Languages
		view()->composer(array('users.create', 'users.edit'), function($view) {
		    
		    $view->with('languageList', \App\Language::pluck('name', 'id')->toArray());
		    
		});

		// Categories
		view()->composer(array('products.index', 'products.create', 'products._panel_main_data'), function($view) {
		    
		    if ( \App\Configuration::get('ALLOW_PRODUCT_SUBCATEGORIES') ) {
		    	$tree = [];
		    	$categories =  \App\Category::where('parent_id', '=', '0')->with('children')->orderby('name', 'asc')->get();
		    	
		    	foreach($categories as $category) {
		    		$tree[$category->name] = $category->children()->orderby('name', 'asc')->pluck('name', 'id')->toArray();
		    		// foreach($category->children as $child) {
		    			// $tree[$category->name][$child->id] = $child->name;
		    		// }
		    	}
		    	// abi_r($tree, true);
		    	$view->with('categoryList', $tree);

		    } else {
		    	// abi_r(\App\Category::where('parent_id', '=', '0')->orderby('name', 'asc')->pluck('name', 'id')->toArray(), true);
		    	$view->with('categoryList', \App\Category::where('parent_id', '=', '0')->orderby('name', 'asc')->pluck('name', 'id')->toArray());
		    }
		    
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

		// Stock Movement Types
		view()->composer(array('stock_movements.index', 'stock_movements.create'), function($view) {
		    
		    $view->with('movement_typeList', \App\StockMovement::stockmovementList());
		    
		});

		// Document Types
		view()->composer(array('sequences.index', 'sequences.create', 'sequences.edit'), function($view) {
		    
		    $view->with('document_typeList', \App\Sequence::documentList());
		    
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
