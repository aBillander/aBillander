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

// Disable Registration Routes...
// See:
// https://stackoverflow.com/questions/29183348/how-to-disable-registration-new-user-in-laravel-5
// https://stackoverflow.com/questions/42695917/laravel-5-4-disable-register-route/42700000
if ( !env('ALLOW_USER_REGISTRATION', true) )
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

Route::get('/home', 'HomeController@index')->name('home');


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

    return '<h1>Cachés borradas</h1>';
});

Route::get('404', function()
{
    return view('errors.404');
});



/* ********************************************************** */


// Secure-Routes
Route::group(['middleware' =>  ['auth']], function()
{
    // Route::get( 'contact', 'ContactMessagesController@create');
    Route::post('contact', 'ContactMessagesController@store');

    Route::get('soon', function()
    {
        return view('soon');
    });
    
    Route::group(['middleware' => 'authAdmin'], function()
    {
    });
        
        Route::resource('configurations',    'ConfigurationsController');
        Route::resource('configurationkeys', 'ConfigurationKeysController');

        Route::resource('companies', 'CompaniesController');

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

        Route::resource('suppliers', 'SuppliersController');

        Route::resource('templates', 'TemplatesController');

        Route::resource('currencies', 'CurrenciesController');
        Route::get('currencies/{id}/exchange',   array('uses'=>'CurrenciesController@exchange', 
                                                                'as' => 'currencies.exchange' ) );  
        Route::post('currencies/ajax/rate_lookup', array('uses' => 'CurrenciesController@ajaxCurrencyRateSearch', 
                                                        'as' => 'currencies.ajax.rateLookup'));

        Route::resource('measureunits', 'MeasureUnitsController');

        Route::resource('workcenters', 'WorkCentersController');

        Route::resource('products', 'ProductsController');
        Route::get('products/{id}/stockmovements', 'ProductsController@getStockMovements')->name('products.stockmovements');
        Route::get('products/{id}/stocksummary', 'ProductsController@getStockSummary')->name('products.stocksummary');

        Route::resource('products.images', 'ProductImagesController');
        Route::get('product/searchbom', 'ProductsController@searchBOM')->name('product.searchbom');
//        Route::post('product/{id}/attachbom', 'ProductsController@attachBOM')->name('product.attachbom');

        Route::post('products/{id}/combine', array('as' => 'products.combine', 'uses'=>'ProductsController@combine'));
        Route::get('products/ajax/name_lookup'  , array('uses' => 'ProductsController@ajaxProductSearch', 
                                                        'as'   => 'products.ajax.nameLookup' ));
        Route::post('products/ajax/options_lookup'  , array('uses' => 'ProductsController@ajaxProductOptionsSearch', 
                                                        'as'   => 'products.ajax.optionsLookup' ));
        Route::post('products/ajax/combination_lookup'  , array('uses' => 'ProductsController@ajaxProductCombinationSearch', 
                                                        'as'   => 'products.ajax.combinationLookup' ));

        Route::resource('ingredients', 'IngredientsController');

        Route::resource('productboms', 'ProductBOMsController');
        Route::get('productboms/{id}/getlines', 'ProductBOMsController@getBOMlines')->name('productbom.getlines');
        Route::get('productboms/{id}/getproducts', 'ProductBOMsController@getBOMproducts')->name('productbom.getproducts');
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



        Route::resource('customers', 'CustomersController');
        Route::get('customerorders/create/withcustomer/{customer}', 'CustomerOrdersController@createWithCustomer')->name('customerorders.create.withcustomer');
        Route::get('customers/ajax/name_lookup', array('uses' => 'CustomersController@ajaxCustomerSearch', 'as' => 'customers.ajax.nameLookup')); 
        Route::get('customers/{id}/getorders',             'CustomersController@getOrders' )->name('customer.getorders');
        Route::post('customers/invite', 'CustomersController@invite')->name('customers.invite');

        Route::resource('carts', 'CartsController');
        Route::post('carts/{cart}/updateprices',  'CartsController@updatePrices')->name('carts.updateprices');

//        Route::resource('addresses', 'AddressesController');
        Route::resource('customers.addresses', 'CustomerAddressesController');

        Route::post('mail', 'MailController@store');

        Route::resource('paymentmethods', 'PaymentMethodsController');

        Route::resource('shippingmethods', 'ShippingMethodsController');

        Route::resource('customergroups', 'CustomerGroupsController');

        Route::resource('taxes',          'TaxesController');
        Route::resource('taxes.taxrules', 'TaxRulesController');
        Route::resource('ecotaxes',       'EcotaxesController');
        Route::resource('ecotaxes.ecotaxrules', 'EcotaxRulesController');

        Route::resource('warehouses', 'WarehousesController');
        
        Route::resource('salesreps', 'SalesRepsController');

        Route::resource('carriers', 'CarriersController');

        Route::resource('manufacturers', 'ManufacturersController');

        Route::resource('activityloggers', 'ActivityLoggersController');
//        Route::get('activityloggers', ['uses' => 'ActivityLoggersController@index', 
//                         'as'   => 'activityloggers.index'] );
        Route::get('activityloggers/empty', ['uses' => 'ActivityLoggersController@empty', 
                         'as'   => 'activityloggers.empty'] );




        Route::resource('customerorders', 'CustomerOrdersController');
        Route::post('customerorders/{id}/move', 'CustomerOrdersController@move')->name('customerorder.move');
        Route::post('customerorders/{id}/unlink', 'CustomerOrdersController@unlink')->name('customerorder.unlink');

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


        $pairs = [
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

        Route::get($path.'/{id}/getlines',             $controller.'@getDocumentLines' )->name($path.'.getlines');
        Route::get($path.'/line/productform/{action}', $controller.'@FormForProduct')->name($path.'.productform');
        Route::get($path.'/line/serviceform/{action}', $controller.'@FormForService')->name($path.'.serviceform');
        Route::get($path.'/line/searchproduct',        $controller.'@searchProduct' )->name($path.'.searchproduct');
        Route::get($path.'/line/searchservice',        $controller.'@searchService' )->name($path.'.searchservice');
        Route::get($path.'/line/getproduct',           $controller.'@getProduct'    )->name($path.'.getproduct');


        Route::post($path.'/{id}/storeline',    $controller.'@storeDocumentLine'   )->name($path.'.storeline'  );
        Route::post($path.'/{id}/updatetotal',  $controller.'@updateDocumentTotal' )->name($path.'.updatetotal');
        Route::get($path.'/{id}/getline/{lid}', $controller.'@getDocumentLine'     )->name($path.'.getline'    );
        Route::post($path.'/updateline/{lid}',  $controller.'@updateDocumentLine'  )->name($path.'.updateline' );
        Route::post($path.'/deleteline/{lid}',  $controller.'@deleteDocumentLine'  )->name($path.'.deleteline' );
        Route::get($path.'/{id}/duplicate',     $controller.'@duplicateDocument'   )->name($path.'.duplicate'  );
        Route::get($path.'/{id}/profit',        $controller.'@getDocumentProfit'   )->name($path.'.profit'     );
        Route::get($path.'/{id}/availability',  $controller.'@getDocumentAvailability' )->name($path.'.availability' );

        Route::post($path.'/{id}/quickaddlines',    $controller.'@quickAddLines'   )->name($path.'.quickaddlines'  );

        Route::post($path.'/sortlines', $controller.'@sortLines')->name($path.'.sortlines');

        Route::get($path.'/{document}/confirm', $controller.'@confirm')->name($path.'.confirm');

        Route::get($path.'/{id}/pdf',         $controller.'@showPdf'       )->name($path.'.pdf'        );
        Route::get($path.'/{id}/invoice/pdf', $controller.'@showPdfInvoice')->name($path.'.invoice.pdf');
        Route::match(array('GET', 'POST'), 
                   $path.'/{id}/email',       $controller.'@sendemail'     )->name($path.'.email'      );
}

        

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

        Route::get( 'import/customers', 'Import\ImportCustomersController@import' )->name('customers.import');
        Route::post('import/customers', 'Import\ImportCustomersController@process')->name('customers.import.process');
        Route::get( 'export/customers', 'Import\ImportCustomersController@export' )->name('customers.export');

        Route::get( 'import/stockcounts/{id}', 'Import\ImportStockCountsController@import' )->name('stockcounts.import');
        Route::post('import/stockcounts/{id}', 'Import\ImportStockCountsController@process')->name('stockcounts.import.process');
        Route::get( 'export/stockcounts/{id}', 'Import\ImportStockCountsController@export' )->name('stockcounts.export');



        /* ******************************************************************************************************** */


// If Stock Counting is in progress, disable these routes:
// if ( ! \App\Configuration::get('STOCK_COUNT_IN_PROGRESS') ) {

        // Route::resource( ... );   
// }

        Route::resource('stockmovements', 'StockMovementsController');

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

            Route::get('/get-monthly-sales',      'ChartCustomerOrdersController@getMonthlySales')->name('chart.customerorders.monthly');
            Route::get('/get-monthly-sales-data', 'ChartCustomerOrdersController@getMonthlySalesData')->name('chart.customerorders.monthly.data');

            Route::get('r', function()
                {
                    return 'Hello, world!';
                });

        });


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
