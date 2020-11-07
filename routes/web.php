<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Auth::routes();


// In case Laravel storage link won't work on production
Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});


// Disable Registration Routes...
// See:
// https://stackoverflow.com/questions/29183348/how-to-disable-registration-new-user-in-laravel-5
// https://stackoverflow.com/questions/42695917/laravel-5-4-disable-register-route/42700000
if ( !env('ALLOW_USER_REGISTRATION', false) )
{
    Route::get('register', function()
        {
            return view('errors.404');
        })->name('register');

    Route::post('register', function()
        {
            return view('errors.404');
        });
}

Route::get('/', 'WelcomeController@index');     // ->name('home');

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('home/searchcustomer', 'HomeController@searchCustomer')->name('home.searchcustomer');
Route::get('home/searchproduct' , 'HomeController@searchProduct' )->name('home.searchproduct' );
Route::get('home/searchcustomerorder' , 'HomeController@searchCustomerOrder' )->name('home.searchcustomerorder' );
Route::get('home/searchcustomershippingslip' , 'HomeController@searchCustomerShippingSlip' )->name('home.searchcustomershippingslip' );
Route::get('home/searchcustomerinvoice' , 'HomeController@searchCustomerInvoice' )->name('home.searchcustomerinvoice' );


// https://www.youtube.com/watch?v=Vb7G1Q2g66g&t=1931s
Route::get('clear-cache', function()
{
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    // If working with File Storage
    // Artisan::call('storage:link');
    // Or create simlink manually

    // php artisan clear-compiled       // https://stillat.com/blog/2016/12/07/laravel-artisan-general-command-the-clear-compiled-command
    // composer dump-autoload

    return '<h1>Cachés borradas</h1>';
});


Route::get('dbbackup', function()
{
    $security_token = request('security_token'); 

    if ( $security_token != \App\Configuration::isNotEmpty('DB_BACKUP_SECURITY_TOKEN') )
        die($security_token);


            if ( config('tenants.enable') )
            {
                // Extract the subdomain from URL.
                list($subdomain) = explode('.', request()->getHost(), 2);
    
                $tenants = config('tenants.names');
    
                // abi_r($tenants, true);
    
                // die(in_array($subdomain, $tenants));
                if ( !in_array($subdomain, $tenants) )
                {
                    // Logout user
                   die('Access denied');
                }
            
            } else {

                $subdomain = 'localhost';
            
            }

    \App\Context::getContext()->tenant = $subdomain; 

    Artisan::call('db:backup');
    // save it to the storage/backups/backup.sql file

    abi_r( Artisan::output() );

        // The backup has been proceed successfully.
        event(new \App\Events\DatabaseBackup());

    // return '<h1>Proceso terminado</h1>';    // <a class="navbar-brand" href="' .url('/'). '">Volver</a>';
    return ;
});


Route::get('eggtimer', function()
{
    $seconds = 1;

    $limit = max((int) ini_get('max_execution_time'), 60) + 10;

        // Start Logger
        $logger = \App\ActivityLogger::setup( 'Egg Timmer', 'Guess max_execution_time' );        // 'Import Categories :: ' . \Carbon\Carbon::now()->format('Y-m-d H:i:s')


        $logger->empty();
        $logger->start();

        $logger->log("INFO", "PHP says: ini_get('max_execution_time') = ".(int) ini_get('max_execution_time').' secs.');

        $i=0;

        while ( true ) {
            # code...

            $i++;

            // sleep( $seconds );
            usleep((int)($seconds * 1000000));

            $logger->log("INFO", "Round: ".(int) $i.' => '.(int) $i*$seconds.' secs.');

            if ($i*$seconds>$limit) break;
        }


        $logger->stop();


        return redirect('activityloggers/'.$logger->id)
                ->with('success', l('Egg Timmer :: Guess max_execution_time.'));
});


Route::get('404', function()
{
    return view('errors.404');
});



/* ********************************************************** */


// Secure-Routes
Route::group(['middleware' =>  ['restrictIp', 'auth', 'context']], function()
{
    // Route::get( 'contact', 'ContactMessagesController@create');
    Route::post('contact', 'ContactMessagesController@store');

    Route::get('soon', function()
    {
        return view('soon');
    });
    
    Route::group(['middleware' => ['authAdmin']], function()
    {
    });
        
        // Home routes
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/desktop', 'HomeController@desktop')->name('desktop');

        // Jennifer
        Route::get( '/jennifer/home', 'JenniferController@index')->name('jennifer.home');
        Route::post('/jennifer/reports/invoices'  , 'JenniferController@reportInvoices'  )->name('jennifer.reports.invoices');
        Route::post('/jennifer/reports/bankorders', 'JenniferController@reportBankOrders')->name('jennifer.reports.bankorders');
        Route::post('/jennifer/reports/inventory' , 'JenniferController@reportInventory' )->name('jennifer.reports.inventory');
        Route::post('/jennifer/reports/mod347'                   , 'JenniferController@index347'    )->name('jennifer.reports.index347');
        Route::get( '/jennifer/reports/mod347/{mod347_year}/show', 'JenniferController@index347Show'    )->name('jennifer.reports.index347.show');
        Route::get( '/jennifer/reports/mod347/{mod347_year}'     , 'JenniferController@reportModelo347'    )->name('jennifer.reports.mod347');
        Route::get( '/jennifer/reports/mod347/{mod347_year}/email/{customer_id}', 'JenniferController@sendemail')->name('jennifer.reports.mod347.email'  );
        Route::get( '/jennifer/reports/mod347/{mod347_year}/customer/{customer_id}', 'JenniferController@reportModelo347Customer')->name('jennifer.reports.mod347.customer');

        Route::group(['prefix' => 'accounting', 'namespace' => '\Accounting'], function ()
        {
            Route::resource('customers', 'AccountingCustomersController')->names('accounting.customers');

            Route::resource('customerinvoices', 'AccountingCustomerInvoicesController')->names('accounting.customerinvoices');
            Route::get('customerinvoices/{id}/pdf', 'AccountingCustomerInvoicesController@showPdf')->name('accounting.customerinvoices.pdf');

            Route::get('customerinvoices/invoice/search', 'AccountingCustomerInvoicesController@searchInvoice')->name('accounting.customerinvoices.searchinvoice');


        // oute::get('cheques/{id}/chequedetail/searchinvoice', 'ChequeDetailsController@searchInvoice')->name('chequedetail.searchinvoice');
        });

        // Helferin
        Route::get('/helferin/home', 'HelferinController@index')->name('helferin.home');
        Route::post('/helferin/reports/sales'  , 'HelferinController@reportSales'  )->name('helferin.reports.sales');
        Route::post('/helferin/reports/ecotaxes'  , 'HelferinController@reportEcotaxes'  )->name('helferin.reports.ecotaxes');
        Route::post('/helferin/reports/consumption'  , 'HelferinController@reportConsumption'  )->name('helferin.reports.consumption');
        Route::post('/helferin/reports/customer/vouchers'  , 'HelferinController@reportCustomerVouchers'  )->name('helferin.reports.customer.vouchers');
        Route::post('/helferin/reports/customer/invoices'  , 'HelferinController@reportCustomerInvoices'  )->name('helferin.reports.customer.invoices');

        Route::get('/helferin/home/mfg', 'HelferinController@mfgIndex')->name('helferin.home.mfg');
        Route::post('/helferin/reports/reorder'       , 'HelferinController@reportProductReorder'       )->name('helferin.reports.reorder');
        Route::get( '/helferin/reports/reorder/headers', 'HelferinController@reportProductReorderHeaders' )->name('helferin.reports.reorder.headers');

        // Reports
        Route::get('/reports/home', 'ReportsController@index')->name('reports.home');
        Route::post('/reports/product/sales'  , 'ReportsController@reportProductSales'  )->name('reports.products.sales');
        Route::post('/reports/customer/sales' , 'ReportsController@reportCustomerSales' )->name('reports.customers.sales');
        Route::post('/reports/category/sales' , 'ReportsController@reportCategorySales' )->name('reports.categories.sales');

        Route::post('/reports/abc/product/sales'  , 'ReportsController@reportABCProductSales'  )->name('reports.abc.products.sales');
        Route::post('/reports/abc/customer/sales' , 'ReportsController@reportABCCustomerSales' )->name('reports.abc.customers.sales');


        Route::get( 'abccbillboard/edit',    'AbccBillboardController@edit'  )->name('abccbillboard.edit');
        Route::post('abccbillboard/update',  'AbccBillboardController@update')->name('abccbillboard.update');


        Route::resource('configurations',    'ConfigurationsController');
        Route::resource('configurationkeys', 'ConfigurationKeysController');

        Route::resource('companies', 'CompaniesController');
        Route::post('companies/{id}/bankaccount', 'CompaniesController@updateBankAccount')->name('companies.bankaccount');

        Route::resource('banks',         'BanksController');
        Route::resource('cheques',               'ChequesController'      );
        Route::resource('cheques.chequedetails', 'ChequeDetailsController');
        Route::post('cheques/sortlines', 'ChequesController@sortLines')->name('cheque.sortlines');
        Route::get('cheques/{id}/chequedetail/searchinvoice', 'ChequeDetailsController@searchInvoice')->name('chequedetail.searchinvoice');
        Route::get( 'export/cheques', 'ChequesController@export' )->name('cheques.export');

        Route::resource('countries',        'CountriesController');
        Route::resource('countries.states', 'StatesController');
        Route::get('countries/{countryId}/getstates',   array('uses'=>'CountriesController@getStates', 
                                                                'as' => 'countries.getstates' ) );

        Route::resource('languages', 'LanguagesController');

        Route::resource('translations', 'TranslationsController', 
                        ['only' => ['index', 'edit', 'update']]);

        Route::resource('sequences', 'SequencesController');

        Route::resource('users', 'UsersController');

        Route::resource('todos', 'TodosController');
        Route::post('todos/{todo}/complete', 'TodosController@complete')->name('todos.complete');

        Route::resource('customerusers', 'CustomerUsersController');
        Route::get('customerusers/create/withcustomer/{customer}', 'CustomerUsersController@createWithCustomer')->name('customer.createuser');
        Route::get('customerusers/{customer}/impersonate', 'CustomerUsersController@impersonate')->name('customer.impersonate');
        Route::get('customerusers/{customer}/cart', 'CustomerUsersController@getCart')->name('customer.cart');
        Route::get('customerusers/{customer}/getuser', 'CustomerUsersController@getUser')->name('customeruser.getuser');

        Route::resource('salesrepusers', 'SalesRepUsersController');
        Route::get('salesrepusers/create/withsalesrep/{salesrep}', 'SalesRepUsersController@createWithSalesRep')->name('salesrep.createuser');
        Route::get('salesrepusers/{salesrep}/impersonate', 'SalesRepUsersController@impersonate')->name('salesrep.impersonate');
        Route::get('salesrepusers/{salesrep}/getuser' , 'SalesRepUsersController@getUser' )->name('salesrepuser.getuser' );
        Route::get('salesreps/{salesrep}/getusers', 'SalesRepsController@getUsers')->name('salesrep.getusers');

        Route::resource('commissionsettlements',          'CommissionSettlementsController');
//        Route::resource('commissionsettlements.documents', 'CommissionSettlementLinesController');
        Route::post('commissionsettlements/add/document', 'CommissionSettlementsController@addDocument')->name('commissionsettlements.add.document');
        Route::get('commissionsettlements/{id}/calculate', 'CommissionSettlementsController@calculate')->name('commissionsettlements.calculate');
        Route::post('commissionsettlementlines/{id}/unlink', 'CommissionSettlementLinesController@unlink')->name('commissionsettlementline.unlink');

        Route::resource('suppliers', 'SuppliersController');
        Route::get('suppliers/ajax/name_lookup', array('uses' => 'SuppliersController@ajaxSupplierSearch', 'as' => 'suppliers.ajax.nameLookup'));
        Route::post('suppliers/{id}/bankaccount', 'SuppliersController@updateBankAccount')->name('suppliers.bankaccount');

        Route::resource('suppliers.addresses', 'SupplierAddressesController');

        Route::resource('suppliers.supplierpricelistlines', 'SupplierPriceListLinesController');

        Route::get( 'suppliers/{id}/product/{pid}/reference/edit', 'SupplierPriceListLinesController@editReference')->name('supplier.product.update.reference.edit');
        Route::post('suppliers/{id}/product/{pid}/reference', 'SupplierPriceListLinesController@updateReference')->name('supplier.product.update.reference');

        Route::get('suppliers/{id}/supplierpricelistline/searchproduct', 'SupplierPriceListLinesController@searchProduct')->name('supplier.pricelistline.searchproduct');

        Route::get('suppliers/{id}/getreference/{pid}', 'SupplierPriceListLinesController@getSupplierProductReference')->name('supplier.product.reference');



        Route::resource('templates', 'TemplatesController');

        Route::resource('currencies', 'CurrenciesController');
        Route::get('currencies/{id}/exchange',   array('uses'=>'CurrenciesController@exchange', 
                                                                'as' => 'currencies.exchange' ) );  
        Route::post('currencies/ajax/rate_lookup', array('uses' => 'CurrenciesController@ajaxCurrencyRateSearch', 
                                                        'as' => 'currencies.ajax.rateLookup'));

        Route::resource('measureunits', 'MeasureUnitsController');

        Route::resource('workcenters', 'WorkCentersController');

        Route::resource('tools', 'ToolsController');

        Route::resource('lots', 'LotsController');
        Route::get( 'export/lots', 'LotsController@export' )->name('lots.export');

        Route::resource('products', 'ProductsController');
        Route::get('products/{id}/stockmovements',   'ProductsController@getStockMovements'  )->name('products.stockmovements');
        Route::get('products/{id}/pendingmovements', 'ProductsController@getPendingMovements')->name('products.pendingmovements');
        Route::get('products/{id}/stocksummary',     'ProductsController@getStockSummary'    )->name('products.stocksummary');
        Route::get('products/{id}/lots',             'ProductsController@getLots'            )->name('products.lots');

        Route::get('products/{id}/recentsales',  'ProductsController@getRecentSales')->name('products.recentsales');

        Route::get('products/{id}/getpricerules',         'ProductsController@getPriceRules')->name('product.getpricerules');

        Route::resource('products.measureunits', 'ProductMeasureUnitsController');
        Route::get('product/{id}/getmeasureunits', 'ProductsController@getMeasureUnits')->name('product.measureunits');

        Route::resource('products.images', 'ProductImagesController');
        Route::get('product/searchbom', 'ProductsController@searchBOM')->name('product.searchbom');
//        Route::post('product/{id}/attachbom', 'ProductsController@attachBOM')->name('product.attachbom');
        Route::get('products/{id}/bom/pdf', 'ProductsController@getPdfBom')->name('product.bom.pdf');

        Route::get('products/{id}/duplicate',     'ProductsController@duplicate'   )->name('product.duplicate'  );

        Route::post('products/{id}/combine', array('as' => 'products.combine', 'uses'=>'ProductsController@combine'));
        Route::get('products/ajax/name_lookup'  , array('uses' => 'ProductsController@ajaxProductSearch', 
                                                        'as'   => 'products.ajax.nameLookup' ));
        Route::post('products/ajax/options_lookup'  , array('uses' => 'ProductsController@ajaxProductOptionsSearch', 
                                                        'as'   => 'products.ajax.optionsLookup' ));
        Route::post('products/ajax/combination_lookup'  , array('uses' => 'ProductsController@ajaxProductCombinationSearch', 
                                                        'as'   => 'products.ajax.combinationLookup' ));

        Route::get('products/stock/level',        'ProductsStockController@index' )->name('products.stock.index' );
        Route::get('products/stock/level/export', 'ProductsStockController@export')->name('products.stock.export');

        Route::get('products/stock/reorder',        'ProductsReorderController@index' )->name('products.reorder.index' );
        Route::get('products/stock/reorder/export', 'ProductsReorderController@export')->name('products.reorder.export');

        Route::resource('ingredients', 'IngredientsController');

        Route::resource('productboms', 'ProductBOMsController');
        Route::get('productboms/{id}/getlines', 'ProductBOMsController@getBOMlines')->name('productbom.getlines');
        Route::get('productboms/{id}/getproducts', 'ProductBOMsController@getBOMproducts')->name('productbom.getproducts');
        Route::get('products/{id}/getproductboms', 'ProductBOMsController@getproductBOMs')->name('product.getproductboms');
        
        Route::post('productboms/{id}/storeline', 'ProductBOMsController@storeBOMline')->name('productbom.storeline');
        Route::get('productboms/{id}/getline/{lid}', 'ProductBOMsController@getBOMline')->name('productbom.getline');
        Route::post('productboms/updateline/{lid}', 'ProductBOMsController@updateBOMline')->name('productbom.updateline');
        Route::post('productboms/deleteline/{lid}', 'ProductBOMsController@deleteBOMline')->name('productbom.deleteline');
        Route::get('productboms/line/searchproduct', 'ProductBOMsController@searchProduct')->name('productbom.searchproduct');
        Route::get('productboms/{id}/duplicate', 'ProductBOMsController@duplicateBOM')->name('productbom.duplicate');
        Route::post('productboms/sortlines', 'ProductBOMsController@sortLines')->name('productbom.sortlines');

        Route::resource('productionorders', 'ProductionOrdersController');
        Route::get('productionorders/order/searchproduct', 'ProductionOrdersController@searchProduct')->name('productionorder.searchproduct');
        Route::post('productionorders/order/storeorder', 'ProductionOrdersController@storeOrder')->name('productionorder.storeorder');

        Route::resource('categories', 'CategoriesController');
        Route::get('category-tree-view', ['uses'=>'CategoriesController@manageCategory']);
        Route::post('add-category',['as'=>'add.category','uses'=>'CategoriesController@create']);
        Route::resource('categories.subcategories', 'CategoriesController');
        Route::post('categories/{id}/publish', array('uses' => 'CategoriesController@publish', 
                                                        'as'   => 'categories.publish' ));
        Route::post('categories/sortlines', 'CategoriesController@sortLines')->name('categories.sortlines');

        Route::get('category/{id}/products', 'CategoryProductsController@index')->name('category.products');
        Route::post('category/sortproducts', 'CategoryProductsController@sortProducts')->name('category.sortproducts');


        Route::get('productionorders/{id}/getorder', 'ProductionOrdersController@getOrder')->name('productionorder.getorder');
        Route::post('productionorders/{id}/productionsheetedit', 'ProductionOrdersController@productionsheetEdit')->name('productionorder.productionsheet.edit');
        Route::post('productionorders/{id}/productionsheetdelete', 'ProductionOrdersController@productionsheetDelete')->name('productionorder.productionsheet.delete');

        Route::resource('productionsheets', 'ProductionSheetsController');
        Route::post('productionsheets/{id}/addorders', 'ProductionSheetsController@addOrders')->name('productionsheet.addorders');
        Route::get('productionsheets/{id}/calculate', 'ProductionSheetsController@calculate')->name('productionsheet.calculate');
        Route::get('productionsheets/{id}/getlines', 'ProductionSheetsController@getCustomerOrderOrderLines')->name('productionsheet.getCustomerOrderLines');
        Route::get('productionsheets/{id}/customerorderssummary', 'ProductionSheetsController@getCustomerOrdersSummary')->name('productionsheet.getCustomerOrdersSummary');

        Route::get('productionsheets/{id}/pickinglist', 'ProductionSheetsController@pickinglist')->name('productionsheet.pickinglist');
        Route::get('productionsheets/{id}/products', 'ProductionSheetsController@getProducts')->name('productionsheet.products');
        Route::get('productionsheets/{id}/summary', 'ProductionSheetsController@getSummary')->name('productionsheet.summary');

        Route::get('productionsheets/{id}/summary/pdf', 'ProductionSheetsPdfController@getPdfSummary')->name('productionsheet.summary.pdf');
        Route::get('productionsheets/{id}/pani/summary/pdf', 'ProductionSheetsPdfController@getPdfSummaryPani')->name('productionsheet.summary.pdf.pani');
        Route::get('productionsheets/{id}/preassemblies/pdf', 'ProductionSheetsPdfController@getPdfPreassemblies')->name('productionsheet.preassemblies.pdf');
        Route::get('productionsheets/{id}/manufacturing/pdf', 'ProductionSheetsPdfController@getPdfManufacturing')->name('productionsheet.manufacturing.pdf');
        Route::get('productionsheets/{id}/manufacturing/{wc}/bulkpdf', 'ProductionSheetsPdfController@getBulkPdfManufacturing')->name('productionsheet.manufacturing.bulkpdf');

        Route::get('productionsheets/{id}/orders/pdf', 'ProductionSheetsPdfController@getPdfOrders')->name('productionsheet.orders.pdf');

        Route::get('productionsheets/{id}/shippingslips/pdf', 'ProductionSheetsPdfController@getPdfShippingslips')->name('productionsheet.shippingslips.pdf');

        Route::get('productionsheets/{id}/products/pdf', 'ProductionSheetsPdfController@getPdfProducts')->name('productionsheet.products.pdf');

        // Production Sheet Orders
        Route::get( 'productionsheetorders/{id}',  'ProductionSheetOrdersController@ordersIndex')->name('productionsheet.orders');

        Route::post('productionsheetorders/shippingslips',  'ProductionSheetOrdersController@createShippingSlips')->name('productionsheet.create.shippingslips');

        // Production Sheet Shipping Slips
        Route::get( 'productionsheetshippingslips/{id}',  'ProductionSheetShippingSlipsController@shippingslipsIndex')->name('productionsheet.shippingslips');

        Route::post('productionsheetshippingslips/close',  'ProductionSheetShippingSlipsController@closeShippingSlips')->name('productionsheet.close.shippingslips');

        Route::post('productionsheetshippingslips/invoices',  'ProductionSheetShippingSlipsController@createInvoices')->name('productionsheet.create.invoices');

        // Production Sheet Invoices
        Route::get( 'productionsheetinvoices/{id}',  'ProductionSheetInvoicesController@invoicesIndex')->name('productionsheet.invoices');

        Route::post('productionsheetinvoices/close',  'ProductionSheetInvoicesController@closeInvoices')->name('productionsheet.close.invoices');



        Route::get( 'productionsheets/{id}/deliveryroute/{route_id}', 'ProductionSheetsDeliveryRoutesController@export' )->name('productionsheet.deliveryroute');

        Route::get( 'productionsheets/{id}/tourline', 'ProductionSheetsTourlineController@export' )->name('productionsheet.tourline');

        // Production Sheet Production Orders
        Route::get( 'productionsheetproductionorders/{id}',   'ProductionSheetProductionOrdersController@productionordersIndex')->name('productionsheet.productionorders');

        Route::get('productionsheetproductionorders/{id}/finish' , 'ProductionSheetProductionOrdersController@finish'    )->name('productionsheet.productionorders.finish');

        Route::post('productionsheetproductionorders/finish/bulk', 'ProductionSheetProductionOrdersController@finishBulk')->name('productionsheet.productionorders.bulk.finish');

        Route::post('productionsheetproductionorders/finish/withlot', 'ProductionSheetProductionOrdersController@finishWithLot')->name('productionsheet.productionorders.finish.withlot');



        Route::resource('customers', 'CustomersController');
        Route::get('customerorders/create/withcustomer/{customer}', 'CustomerOrdersController@createWithCustomer')->name('customerorders.create.withcustomer');
        Route::get('customers/ajax/name_lookup', array('uses' => 'CustomersController@ajaxCustomerSearch', 'as' => 'customers.ajax.nameLookup'));
        Route::get('customers/{id}/getorders',             'CustomersController@getOrders'    )->name('customer.getorders');
        Route::get('customers/{id}/getpricerules',         'CustomersController@getPriceRules')->name('customer.getpricerules');
        Route::get('customers/{id}/getusers',         'CustomersController@getUsers')->name('customer.getusers');
        Route::post('customers/{id}/bankaccount', 'CustomersController@updateBankAccount')->name('customers.bankaccount');
        Route::post('customers/invite', 'CustomersController@invite')->name('customers.invite');

        Route::get('customers/{id}/product/{productid}/consumption', 'CustomersController@productConsumption' )->name('customer.product.consumption');

        Route::get('customers/{id}/recentsales',  'CustomersController@getRecentSales')->name('customer.recentsales');

        Route::resource('carts', 'CartsController');
        Route::post('carts/{cart}/updateprices',  'CartsController@updatePrices')->name('carts.updateprices');

//        Route::resource('addresses', 'AddressesController');
        Route::resource('customers.addresses', 'CustomerAddressesController');

        Route::post('mail', 'MailController@store');
        Route::post('mail/feedback', 'MailController@storeFeedback');

        Route::resource('paymentmethods', 'PaymentMethodsController');
        Route::resource('paymenttypes', 'PaymentTypesController');

        Route::resource('shippingmethods', 'ShippingMethodsController');
        Route::resource('shippingmethods.shippingmethodrules', 'ShippingMethodRulesController');

        Route::resource('customergroups', 'CustomerGroupsController');

        Route::resource('pricerules', 'PriceRulesController');

        Route::resource('taxes',          'TaxesController');
        Route::resource('taxes.taxrules', 'TaxRulesController');
        Route::resource('ecotaxes',       'EcotaxesController');
        Route::resource('ecotaxes.ecotaxrules', 'EcotaxRulesController');

        Route::resource('warehouses', 'WarehousesController');
        Route::get('warehouses/{id}/inventory', 'WarehousesController@indexProducts')->name('warehouse.inventory');
        Route::get('export/warehouses/{id}/inventory', 'WarehousesController@exportProducts' )->name('warehouse.inventory.export');

        Route::resource('warehouseshippingslips', 'WarehouseShippingSlipsController');
        // See line 552
        Route::get('warehouseshippingslips/{id}/getlines',             'WarehouseShippingSlipsController@getDocumentLines'  )->name('warehouseshippingslips.getlines' );
        Route::get('warehouseshippingslips/{id}/getheader',            'WarehouseShippingSlipsController@getDocumentHeader' )->name('warehouseshippingslips.getheader');
        Route::get('warehouseshippingslips/line/productform/{action}', 'WarehouseShippingSlipsController@FormForProduct')->name('warehouseshippingslips.productform');
        Route::get('warehouseshippingslips/line/serviceform/{action}', 'WarehouseShippingSlipsController@FormForService')->name('warehouseshippingslips.serviceform');
        Route::get('warehouseshippingslips/line/commentform/{action}', 'WarehouseShippingSlipsController@FormForComment')->name('warehouseshippingslips.commentform');
        Route::get('warehouseshippingslips/line/searchproduct',        'WarehouseShippingSlipsController@searchProduct' )->name('warehouseshippingslips.searchproduct');
        Route::get('warehouseshippingslips/line/searchservice',        'WarehouseShippingSlipsController@searchService' )->name('warehouseshippingslips.searchservice');
        Route::get('warehouseshippingslips/line/getproduct',           'WarehouseShippingSlipsController@getProduct'      )->name('warehouseshippingslips.getproduct');
//        Route::get('warehouseshippingslips/line/getproduct/prices',    'WarehouseShippingSlipsController@getProductPrices')->name('warehouseshippingslips.getproduct.prices');

        Route::post('warehouseshippingslips/{id}/storeline',    'WarehouseShippingSlipsController@storeDocumentLine'   )->name('warehouseshippingslips.storeline'  );
//        Route::post('warehouseshippingslips/{id}/updatetotal',  'WarehouseShippingSlipsController@updateDocumentTotal' )->name('warehouseshippingslips.updatetotal');
        Route::get('warehouseshippingslips/{id}/getline/{lid}', 'WarehouseShippingSlipsController@getDocumentLine'     )->name('warehouseshippingslips.getline'    );
        Route::post('warehouseshippingslips/updateline/{lid}',  'WarehouseShippingSlipsController@updateDocumentLine'  )->name('warehouseshippingslips.updateline' );
        Route::post('warehouseshippingslips/deleteline/{lid}',  'WarehouseShippingSlipsController@deleteDocumentLine'  )->name('warehouseshippingslips.deleteline' );
//        Route::get('warehouseshippingslips/{id}/duplicate',     'WarehouseShippingSlipsController@duplicateDocument'   )->name('warehouseshippingslips.duplicate'  );
//        Route::get('warehouseshippingslips/{id}/profit',        'WarehouseShippingSlipsController@getDocumentProfit'   )->name('warehouseshippingslips.profit'     );
        Route::get('warehouseshippingslips/{id}/availability',  'WarehouseShippingSlipsController@getDocumentAvailability' )->name('warehouseshippingslips.availability' );
        
        Route::get('warehouseshippingslips/{id}/availability/modal',  'WarehouseShippingSlipsController@getDocumentAvailabilityModal' )->name('warehouseshippingslips.availability.modal' );


        Route::post('warehouseshippingslips/{id}/quickaddlines',    'WarehouseShippingSlipsController@quickAddLines'   )->name('warehouseshippingslips.quickaddlines'  );

        Route::post('warehouseshippingslips/sortlines', 'WarehouseShippingSlipsController@sortLines')->name('warehouseshippingslips.sortlines');


        Route::get('warehouseshippingslips/{document}/confirm',   'WarehouseShippingSlipsController@confirm'  )->name('warehouseshippingslips.confirm'  );
        Route::get('warehouseshippingslips/{document}/unconfirm', 'WarehouseShippingSlipsController@unConfirm')->name('warehouseshippingslips.unconfirm');

        Route::get('warehouseshippingslips/{id}/pdf',         'WarehouseShippingSlipsController@showPdf'       )->name('warehouseshippingslips.pdf'        );
//        Route::post('warehouseshippingslips/pdf/bulk',        'WarehouseShippingSlipsController@showBulkPdf'   )->name('warehouseshippingslips.bulk.pdf'   );
        Route::match(array('GET', 'POST'), 
                   'warehouseshippingslips/{id}/email',       'WarehouseShippingSlipsController@sendemail'     )->name('warehouseshippingslips.email'      );

        Route::get('warehouseshippingslips/{document}/onhold/toggle', 'WarehouseShippingSlipsController@onholdToggle')->name('warehouseshippingslips.onhold.toggle');

        Route::get('warehouseshippingslips/{document}/close',   'WarehouseShippingSlipsController@close'  )->name('warehouseshippingslips.close'  );
        Route::get('warehouseshippingslips/{document}/unclose', 'WarehouseShippingSlipsController@unclose')->name('warehouseshippingslips.unclose');

        Route::get('warehouseshippingslips/{id}/deliver' , 'WarehouseShippingSlipsController@deliver'    )->name('warehouseshippingslip.deliver');
//        Route::post('warehouseshippingslips/deliver/bulk', 'WarehouseShippingSlipsController@deliverBulk')->name('warehouseshippingslips.bulk.deliver');

        Route::get('warehouseshippingslips/{id}/undeliver'  , 'WarehouseShippingSlipsController@undeliver')->name('warehouseshippingslip.undeliver');
        
        
        Route::resource('salesreps', 'SalesRepsController');

        Route::resource('carriers', 'CarriersController');

        Route::resource('manufacturers', 'ManufacturersController');

        Route::resource('helpcontents', 'HelpContentsController');
        Route::get('helpcontents/{slug}/content', 'HelpContentsController@getContent')->name('helpcontents.content');

        Route::resource('activityloggers', 'ActivityLoggersController');
//        Route::get('activityloggers', ['uses' => 'ActivityLoggersController@index', 
//                         'as'   => 'activityloggers.index'] );
        Route::get('activityloggers/empty', ['uses' => 'ActivityLoggersController@empty', 
                         'as'   => 'activityloggers.empty'] );
        
        Route::get( 'export/activityloggers/{activitylogger}', 'ActivityLoggersController@export' )->name('activityloggers.export');

        Route::resource('emaillogs', 'EmailLogsController');



//        Route::resource('customerorders', 'CustomerOrdersController');
        Route::post('customerorders/{id}/move', 'CustomerOrdersController@move')->name('customerorder.move');
        Route::post('customerorders/{id}/unlink', 'CustomerOrdersController@unlink')->name('customerorder.unlink');
/*
        Route::get('customerorders/ajax/customer_lookup', array('uses' => 'CustomerOrdersController@ajaxCustomerSearch', 'as' => 'customerorders.ajax.customerLookup'));
        Route::get('customerorders/ajax/customer/{id}/adressbook_lookup', array('uses' => 'CustomerOrdersController@customerAdressBookLookup', 'as' => 'customerorders.ajax.customer.AdressBookLookup'));

        Route::get('customerorders/{id}/getlines',             'CustomerOrdersController@getOrderLines' )->name('customerorder.getlines');
        Route::get('customerorders/line/productform/{action}', 'CustomerOrdersController@FormForProduct')->name('customerorderline.productform');
        Route::get('customerorders/line/serviceform/{action}', 'CustomerOrdersController@FormForService')->name('customerorderline.serviceform');
        Route::get('customerorders/line/searchproduct',        'CustomerOrdersController@searchProduct' )->name('customerorderline.searchproduct');
        Route::get('customerorders/line/searchservice',        'CustomerOrdersController@searchService' )->name('customerorderline.searchservice');
        Route::get('customerorders/line/getproduct',           'CustomerOrdersController@getProduct'    )->name('customerorderline.getproduct');


        Route::post('customerorders/{id}/storeline',    'CustomerOrdersController@storeOrderLine'   )->name('customerorder.storeline'  );
        Route::post('customerorders/{id}/updatetotal',  'CustomerOrdersController@updateOrderTotal' )->name('customerorder.updatetotal');
        Route::get('customerorders/{id}/getline/{lid}', 'CustomerOrdersController@getOrderLine'     )->name('customerorder.getline'    );
        Route::post('customerorders/updateline/{lid}',  'CustomerOrdersController@updateOrderLine'  )->name('customerorder.updateline' );
        Route::post('customerorders/deleteline/{lid}',  'CustomerOrdersController@deleteOrderLine'  )->name('customerorder.deleteline' );
        Route::get('customerorders/{id}/duplicate',     'CustomerOrdersController@duplicateOrder'   )->name('customerorder.duplicate'  );
        Route::get('customerorders/{id}/profit',        'CustomerOrdersController@getOrderProfit'   )->name('customerorder.profit'     );
        Route::get('customerorders/{id}/availability',  'CustomerOrdersController@getOrderAvailability' )->name('customerorder.availability' );

        Route::post('customerorders/{id}/quickaddlines',    'CustomerOrdersController@quickAddLines'   )->name('customerorder.quickaddlines'  );

        Route::post('customerorders/sortlines', 'CustomerOrdersController@sortLines')->name('customerorder.sortlines');

//        Route::get('customerorders/{id}/shippingslip',  'CustomerOrdersController@makeShippingSlip'   )->name('customerorder.shippingslip'  );

        Route::get('customerorders/{customerorder}/confirm', 'CustomerOrdersController@confirm')->name('customerorder.confirm');

        Route::get('customerorders/{id}/pdf',         'CustomerOrdersController@showPdf'       )->name('customerorder.pdf'        );
        Route::get('customerorders/{id}/invoice/pdf', 'CustomerOrdersController@showPdfInvoice')->name('customerorder.invoice.pdf');

        Route::get('customerorders/customers/{id}',  'CustomerOrdersController@indexByCustomer')->name('customer.orders');
*/

        $pairs = [
                [
                    'controller' => 'CustomerQuotationsController',
                    'path' => 'customerquotations',
                ],
                [
                    'controller' => 'CustomerOrdersController',
                    'path' => 'customerorders',
                ],
                [
                    'controller' => 'CustomerShippingSlipsController',
                    'path' => 'customershippingslips',
                ],
                [
                    'controller' => 'CustomerInvoicesController',
                    'path' => 'customerinvoices',
                ],
        ];


foreach ($pairs as $pair) {

        $controller = $pair['controller'];
        $path = $pair['path'];

        Route::resource($path, $controller);
        Route::get($path.'/create/withcustomer/{customer_id}', $controller.'@createWithCustomer')->name($path.'.create.withcustomer');

        Route::get($path.'/ajax/customer_lookup', $controller.'@ajaxCustomerSearch')->name($path.'.ajax.customerLookup');
        Route::get($path.'/ajax/customer/{id}/adressbook_lookup', $controller.'@customerAdressBookLookup')->name($path.'.ajax.customer.AdressBookLookup');

        Route::get($path.'/{id}/getlines',             $controller.'@getDocumentLines'  )->name($path.'.getlines' );
        Route::get($path.'/{id}/getheader',            $controller.'@getDocumentHeader' )->name($path.'.getheader');
        Route::get($path.'/line/productform/{action}', $controller.'@FormForProduct')->name($path.'.productform');
        Route::get($path.'/line/productlotsform/{action}', $controller.'@FormForProductLots')->name($path.'.productlotsform');
        Route::get($path.'/line/serviceform/{action}', $controller.'@FormForService')->name($path.'.serviceform');
        Route::get($path.'/line/commentform/{action}', $controller.'@FormForComment')->name($path.'.commentform');
        Route::get($path.'/line/searchproduct',        $controller.'@searchProduct' )->name($path.'.searchproduct');
        Route::get($path.'/line/searchservice',        $controller.'@searchService' )->name($path.'.searchservice');
        Route::get($path.'/line/getproduct',           $controller.'@getProduct'      )->name($path.'.getproduct');
        Route::get($path.'/line/getproduct/prices',    $controller.'@getProductPrices')->name($path.'.getproduct.prices');

        // ?? Maybe only for Invoices ??
        Route::get($path.'/{id}/getpayments',          $controller.'@getDocumentPayments' )->name($path.'.getpayments');


        Route::post($path.'/{id}/storeline',    $controller.'@storeDocumentLine'   )->name($path.'.storeline'  );
        Route::post($path.'/{id}/updatetotal',  $controller.'@updateDocumentTotal' )->name($path.'.updatetotal');
        Route::get($path.'/{id}/getline/{lid}', $controller.'@getDocumentLine'     )->name($path.'.getline'    );
        Route::post($path.'/updateline/{lid}',  $controller.'@updateDocumentLine'  )->name($path.'.updateline' );
        Route::post($path.'/deleteline/{lid}',  $controller.'@deleteDocumentLine'  )->name($path.'.deleteline' );
        Route::get($path.'/{id}/duplicate',     $controller.'@duplicateDocument'   )->name($path.'.duplicate'  );
        Route::get($path.'/{id}/profit',        $controller.'@getDocumentProfit'   )->name($path.'.profit'     );
        Route::get($path.'/{id}/availability',  $controller.'@getDocumentAvailability' )->name($path.'.availability' );

        Route::get($path.'/{id}/getlotsline/{lid}', $controller.'@getDocumentLotsLine'     )->name($path.'.getlotsline'    );
        Route::post($path.'/updatelotsline/{lid}',  $controller.'@updateDocumentLotsLine'  )->name($path.'.updatelotsline' );
        
        Route::get($path.'/{id}/availability/modal',  $controller.'@getDocumentAvailabilityModal' )->name($path.'.availability.modal' );

        Route::post($path.'/{id}/quickaddlines',    $controller.'@quickAddLines'   )->name($path.'.quickaddlines'  );

        Route::post($path.'/sortlines', $controller.'@sortLines')->name($path.'.sortlines');

        Route::get($path.'/{document}/confirm',   $controller.'@confirm'  )->name($path.'.confirm'  );
        Route::get($path.'/{document}/unconfirm', $controller.'@unConfirm')->name($path.'.unconfirm');

        Route::get($path.'/{id}/pdf',         $controller.'@showPdf'       )->name($path.'.pdf'        );
        Route::post($path.'/pdf/bulk',        $controller.'@showBulkPdf'   )->name($path.'.bulk.pdf'   );
        Route::get($path.'/{id}/invoice/pdf', $controller.'@showPdfInvoice')->name($path.'.invoice.pdf');
        Route::match(array('GET', 'POST'), 
                   $path.'/{id}/email',       $controller.'@sendemail'     )->name($path.'.email'      );

        Route::get($path.'/{document}/onhold/toggle', $controller.'@onholdToggle')->name($path.'.onhold.toggle');

        Route::get($path.'/{document}/close',   $controller.'@close'  )->name($path.'.close'  );
        Route::get($path.'/{document}/unclose', $controller.'@unclose')->name($path.'.unclose');

        Route::get($path.'/customers/{id}',  $controller.'@indexByCustomer')->name('customer.'.str_replace('customer', '', $path));

        Route::get($path.'/{id}/reload/commissions', $controller.'@reloadCommissions')->name($path.'.reload.commissions');
        Route::get($path.'/{id}/reload/ecotaxes',    $controller.'@reloadEcotaxes'   )->name($path.'.reload.ecotaxes'   );
        Route::get($path.'/{id}/reload/costs',       $controller.'@reloadCosts'      )->name($path.'.reload.costs'      );
}

        Route::post('customerquotations/create/order/single',  'CustomerQuotationsController@createSingleOrder')->name('customerquotation.single.order');

        Route::get('customerorders/pending/today',  'CustomerOrdersController@getTodaysOrders')->name('customerorders.for.today');

        Route::get('customerorders/customers/{id}/shippingslipables',  'CustomerOrdersController@getShippingSlipableOrders')->name('customer.shippingslipable.orders');
        Route::post('customerorders/aggregate/orders',  'CustomerOrdersController@createAggregateOrder')->name('customerorders.aggregate.orders');
        Route::post('customerorders/create/shippingslip',  'CustomerOrdersController@createGroupShippingSlip')->name('customerorders.create.shippingslip');
        Route::get('customerorders/{id}/shippingslip'  , 'CustomerOrdersController@createShippingSlip')->name('customerorder.shippingslip');

        Route::post('customerorders/create/shippingslip/single',  'CustomerOrdersController@createSingleShippingSlip')->name('customerorder.single.shippingslip');


        Route::get( 'customershippingslipsinvoicer',          'CustomerShippingSlipsInvoicerController@create' )->name('customershippingslips.invoicer.create' );
        Route::post('customershippingslipsinvoicer/process',  'CustomerShippingSlipsInvoicerController@process')->name('customershippingslips.invoicer.process');

        Route::get( 'customershippingslips/customers/{id}/invoiceables',  'CustomerShippingSlipsController@getInvoiceableShippingSlips')->name('customer.invoiceable.shippingslips');
        Route::post('customershippingslips/create/invoice',  'CustomerShippingSlipsController@createGroupInvoice')->name('customershippingslips.create.invoice');
        Route::get( 'customershippingslips/{id}/invoice'  , 'CustomerShippingSlipsController@createInvoice')->name('customershippingslip.invoice');
        Route::post('customershippingslips/{id}/invoice/undo'  , 'CustomerShippingSlipsController@undoInvoice')->name('customershippingslip.invoice.undo');

        Route::post('customershippingslips/setcarrier/bulk', 'CustomerShippingSlipsController@setCarrierBulk')->name('customershippingslips.bulk.set.carrier');

        Route::get('customershippingslips/{id}/deliver' , 'CustomerShippingSlipsController@deliver'    )->name('customershippingslip.deliver');
        Route::post('customershippingslips/deliver/bulk', 'CustomerShippingSlipsController@deliverBulk')->name('customershippingslips.bulk.deliver');

        Route::get('customershippingslips/{id}/undeliver'  , 'CustomerShippingSlipsController@undeliver')->name('customershippingslip.undeliver');

        Route::get('customershippingslips/pending/today',  'CustomerShippingSlipsController@getTodaysShippingSlips')->name('customershippingslips.for.today');


        Route::post('customerinvoices/{id}/shippingslip/add'  , 'CustomerInvoicesController@addShippingSlipToInvoice')->name('customerinvoice.shippingslip.add');

        Route::resource('customervouchers'      , 'CustomerVouchersController');
        Route::get('customervouchers/{id}/setduedate'  , 'CustomerVouchersController@setduedate');
        Route::get('customervouchers/{id}/pay'  , 'CustomerVouchersController@pay');
        Route::post('customervouchers/{id}/unlink', 'CustomerVouchersController@unlink')->name('voucher.unlink');

        Route::post('customervouchers/payvouchers'  , 'CustomerVouchersController@payVouchers')->name('customervouchers.payvouchers');

        Route::get('customervouchers/{id}/expresspay', 'CustomerVouchersController@expressPayVoucher')->name('voucher.expresspay');
        Route::get('customervouchers/{id}/unpay', 'CustomerVouchersController@unPayVoucher')->name('voucher.unpay');
        
        Route::get('customervouchers/{id}/collectible', 'CustomerVouchersController@collectibleVoucher')->name('voucher.collectible');

        Route::get('customervouchers/customers/{id}',  'CustomerVouchersController@indexByCustomer')->name('customer.vouchers');

        Route::get( 'export/customervouchers', 'CustomerVouchersController@export' )->name('customervouchers.export');
        

        Route::resource('pricelists',           'PriceListsController');
        Route::post( 'pricelists/{id}/default', 'PriceListsController@setAsDefault' )->name('pricelist.default');

        Route::resource('pricelists.pricelistlines', 'PriceListLinesController');
        Route::get('pricelists/{id}/pricelistline/searchproduct', 'PriceListLinesController@searchProduct')->name('pricelistline.searchproduct');
        // Edit Price list Line in Product Controller
        Route::resource('pricelistlines', 'PriceListLineController');

        Route::resource('optiongroups',         'OptionGroupsController');
        Route::resource('optiongroups.options', 'OptionsController');

        Route::resource('combinations', 'CombinationsController');

        Route::resource('images', 'ImagesController');


        // Delivery Routes
        Route::resource('deliveryroutes',                    'DeliveryRoutesController'    );
        Route::resource('deliveryroutes.deliveryroutelines', 'DeliveryRouteLinesController');
        Route::post('deliveryroutes/sortlines', 'DeliveryRoutesController@sortLines')->name('deliveryroute.sortlines');

        Route::get( 'deliveryroutes/{deliveryroute}/pdf', 'DeliveryRoutesController@showPdf' )->name('deliveryroutes.pdf');
        
        Route::resource('deliverysheets',                    'DeliverySheetsController'    );
        Route::resource('deliverysheets.deliverysheetlines', 'DeliverySheetLinesController');
        Route::post('deliverysheets/sortlines', 'DeliverySheetsController@sortLines')->name('deliverysheet.sortlines');

        // Customer Order Templates
        Route::resource('customerordertemplates',                    'CustomerOrderTemplatesController'    );
        Route::resource('customerordertemplates.customerordertemplatelines', 'CustomerOrderTemplateLinesController');
        Route::post('customerordertemplates/sortlines', 'CustomerOrderTemplatesController@sortLines')->name('customerordertemplate.sortlines');

        Route::get('customerordertemplates/create/afterorder/{id}', 'CustomerOrderTemplatesController@createAfterOrder')->name('customerordertemplates.create.afterorder');
        Route::post('customerordertemplates/store/afterorder',      'CustomerOrderTemplatesController@storeAfterOrder')->name('customerordertemplates.store.afterorder');

        Route::get( 'customerordertemplates/{customerordertemplate}/createorder', 'CustomerOrderTemplatesController@createCustomerOrder' )->name('customerordertemplates.createcustomerorder');



        // Import / Export to Database
        Route::get( 'import/pricelists/{id}', 'Import\ImportPriceListsController@import' )->name('pricelists.import');
        Route::post('import/pricelists/{id}', 'Import\ImportPriceListsController@process')->name('pricelists.import.process');
        Route::get( 'export/pricelists/{id}', 'Import\ImportPriceListsController@export' )->name('pricelists.export');

        Route::get( 'import/categories', 'Import\ImportCategoriesController@import' )->name('categories.import');
        Route::post('import/categories', 'Import\ImportCategoriesController@process')->name('categories.import.process');
        Route::get( 'export/categories', 'Import\ImportCategoriesController@export' )->name('categories.export');

        Route::get( 'import/products', 'Import\ImportProductsController@import' )->name('products.import');
        Route::post('import/products', 'Import\ImportProductsController@process')->name('products.import.process');
        Route::get( 'export/products', 'Import\ImportProductsController@export' )->name('products.export');
        Route::get( 'import/products/images', 'Import\ImportProductImagesController@import' )->name('products.images.import');
        Route::post('import/products/images', 'Import\ImportProductImagesController@process')->name('products.images.import.process');
        Route::get( 'export/products/images', 'Import\ImportProductImagesController@export' )->name('products.images.export');
        Route::get( 'export/products/images/delete', 'Import\ImportProductImagesController@deleteAll' )->name('products.images.delete.all');
        Route::get(  'import/products/prices', 'Import\ImportProductPricesController@import' )->name('products.prices.import');
        Route::post( 'import/products/prices', 'Import\ImportProductPricesController@process')->name('products.prices.import.process');
        Route::get(  'export/products/prices', 'Import\ImportProductPricesController@export' )->name('products.prices.export');

        Route::get( 'import/customers', 'Import\ImportCustomersController@import' )->name('customers.import');
        Route::post('import/customers', 'Import\ImportCustomersController@process')->name('customers.import.process');
        Route::get( 'export/customers', 'Import\ImportCustomersController@export' )->name('customers.export');

        Route::get( 'import/customerusers', 'Import\ImportCustomerUsersController@import' )->name('customerusers.import');
        Route::post('import/customerusers', 'Import\ImportCustomerUsersController@process')->name('customerusers.import.process');
        Route::get( 'export/customerusers', 'Import\ImportCustomerUsersController@export' )->name('customerusers.export');

        Route::get( 'import/stockcounts/{id}', 'Import\ImportStockCountsController@import' )->name('stockcounts.import');
        Route::post('import/stockcounts/{id}', 'Import\ImportStockCountsController@process')->name('stockcounts.import.process');
        Route::get( 'export/stockcounts/{id}', 'Import\ImportStockCountsController@export' )->name('stockcounts.export');

        Route::get( 'import/suppliers', 'Import\ImportSuppliersController@import' )->name('suppliers.import');
        Route::post('import/suppliers', 'Import\ImportSuppliersController@process')->name('suppliers.import.process');
        Route::get( 'export/suppliers', 'Import\ImportSuppliersController@export' )->name('suppliers.export');

        Route::get( 'import/suppliers/{id}/pricelist', 'Import\ImportSupplierPriceListLinesController@import' )->name('suppliers.pricelist.import');
        Route::post('import/suppliers/{id}/pricelist', 'Import\ImportSupplierPriceListLinesController@process')->name('suppliers.pricelist.import.process');
        Route::get( 'export/suppliers/{id}/pricelist', 'Import\ImportSupplierPriceListLinesController@export' )->name('suppliers.pricelist.export');


        Route::get('import', function()
            {
                return view('imports.index');
            });


        Route::get('dbbackups',           'DbBackupsController@index'  )->name('dbbackups.index');

//        Route::get( 'dbbackups/configurations',        'DbBackupsController@configurations'       )->name('dbbackups.configurations');
        Route::post('dbbackups/configurations/update', 'DbBackupsController@configurationsUpdate' )->name('dbbackups.configurations.update');

        Route::get( 'dbbackups/job/edit',   'DbBackupsController@job'       )->name('dbbackups.job');
        Route::post('dbbackups/job/update', 'DbBackupsController@jobUpdate' )->name('dbbackups.job.update');

        Route::get('dbbackups/process',   'DbBackupsController@process')->name('dbbackups.process');

        Route::delete('dbbackups/{filename}/delete',   'DbBackupsController@delete'  )->name('dbbackups.delete'  );
        Route::get('dbbackups/{filename}/download', 'DbBackupsController@download')->name('dbbackups.download');


        /* ******************************************************************************************************** */


// If Stock Counting is in progress, disable these routes:
// if ( ! \App\Configuration::get('STOCK_COUNT_IN_PROGRESS') ) {

        // Route::resource( ... );   
// }

        Route::resource('stockmovements', 'StockMovementsController');        
        Route::get( 'export/stockmovements', 'StockMovementsController@export' )->name('stockmovements.export');

        Route::resource('stockcounts',              'StockCountsController');
        Route::post( 'stockcounts/{id}/warehouseupdate',    'StockCountsController@warehouseUpdate' )->name('stockcount.warehouse.update');

        Route::resource('stockcounts.stockcountlines', 'StockCountLinesController');
        Route::get('stockcounts/{id}/stockcountline/searchproduct', 'StockCountLinesController@searchProduct')->name('stockcountline.searchproduct');

        Route::resource('stockadjustments', 'StockAdjustmentsController', 
                ['except' => [
                    'index', 'update', 'destroy'
                ]]);



/* ********************************************************** */

        // Charts routes

        Route::group(['prefix' => 'chart', 'namespace' => '\Chart'], function ()
        {

            Route::get('/get-monthly-sales',      'ChartCustomerSalesController@getMonthlySales')->name('chart.customerorders.monthly');
            Route::get('/get-monthly-sales-data', 'ChartCustomerSalesController@getMonthlySalesData')->name('chart.customerorders.monthly.data');

            Route::get('/get-daily-sales',      'ChartDailyCustomerSalesController@getDailySales')->name('chart.customerorders.daily');
            Route::get('/get-daily-sales-data', 'ChartDailyCustomerSalesController@getDailySalesData')->name('chart.customerorders.daily.data');

            Route::get('/get-monthly-vouchers',      'ChartCustomerVouchersController@getMonthlyVouchers')->name('chart.customervouchers.monthly');
            Route::get('/get-monthly-vouchers-data', 'ChartCustomerVouchersController@getMonthlyVouchersData')->name('chart.customervouchers.monthly.data');

            Route::get('/get-monthly-product-stock',      'ChartProductStockController@getMonthlyProductStock')->name('chart.product.stock.monthly');
            Route::get('/get-monthly-product-stock-data', 'ChartProductStockController@getMonthlyProductStockData')->name('chart.product.stock.monthly.data');

            Route::get('/get-monthly-product-sales',      'ChartProductSalesController@getMonthlyProductSales')->name('chart.product.sales.monthly');
            Route::get('/get-monthly-product-sales-data', 'ChartProductSalesController@getMonthlyProductSalesData')->name('chart.product.sales.monthly.data');

            Route::get('r', function()
                {
                    return 'Hello, world!';
                });

        });

                
        /* ********************************************************** */

        // Temporary Purchase Order routes

        if (file_exists(__DIR__.'/web_po.php')) {
            include __DIR__.'/web_po.php';
        }



        /* ********************************************************** */



});


/* ********************************************************** */
// Customer's Center

// Fast & dirty
// To Do: Put this in Routes Service Provider

// if ( \App\Configuration::isTrue('ENABLE_CUSTOMER_CENTER') ) {
//    include __DIR__.'/abcc.php';
// }


/* ********************************************************** */


if (file_exists(__DIR__.'/gorrino_routes.php')) {
    include __DIR__.'/gorrino_routes.php';
}

/* ********************************************************** */


/* ********************************************************** */
