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


// Secure-Routes
Route::group(['middleware' =>  ['restrictIp', 'auth', 'context']], function()
{
        // Manufacturing Production Orders
        Route::resource('productionorders', 'ProductionOrdersController');
        Route::get('productionorders/order/searchproduct', 'ProductionOrdersController@searchProduct')->name('productionorder.searchproduct');
        Route::post('productionorders/order/storeorder', 'ProductionOrdersController@storeOrder')->name('productionorder.storeorder');


        Route::get('productionorders/{id}/getlines',             'ProductionOrdersController@getDocumentLines'  )->name('productionorders.getlines' );
        Route::get('productionorders/{id}/getheader',            'ProductionOrdersController@getDocumentHeader' )->name('productionorders.getheader');
        Route::get('productionorders/line/productform/{action}', 'ProductionOrdersController@FormForProduct')->name('productionorders.productform');
        Route::get('productionorders/line/productlotsform/{action}', 'ProductionOrdersController@FormForProductLots')->name('productionorders.productlotsform');
        Route::get('productionorders/line/serviceform/{action}', 'ProductionOrdersController@FormForService')->name('productionorders.serviceform');
        Route::get('productionorders/line/commentform/{action}', 'ProductionOrdersController@FormForComment')->name('productionorders.commentform');
        Route::get('productionorders/line/searchproduct',        'ProductionOrdersController@searchProduct' )->name('productionorders.searchproduct');
        Route::get('productionorders/line/searchservice',        'ProductionOrdersController@searchService' )->name('productionorders.searchservice');
        Route::get('productionorders/line/getproduct',           'ProductionOrdersController@getProduct'      )->name('productionorders.getproduct');
//        Route::get('productionorders/line/getproduct/prices',    'ProductionOrdersController@getProductPrices')->name('productionorders.getproduct.prices');

        Route::post('productionorders/{id}/storeline',    'ProductionOrdersController@storeDocumentLine'   )->name('productionorders.storeline'  );
        Route::post('productionorders/{id}/updatetotal',  'ProductionOrdersController@updateDocumentTotal' )->name('productionorders.updatetotal');
        Route::get('productionorders/{id}/getline/{lid}', 'ProductionOrdersController@getDocumentLine'     )->name('productionorders.getline'    );
        Route::post('productionorders/updateline/{lid}',  'ProductionOrdersController@updateDocumentLine'  )->name('productionorders.updateline' );
        Route::post('productionorders/deleteline/{lid}',  'ProductionOrdersController@deleteDocumentLine'  )->name('productionorders.deleteline' );
//        Route::get('productionorders/{id}/duplicate',     'ProductionOrdersController@duplicateDocument'   )->name('productionorders.duplicate'  );
//        Route::get('productionorders/{id}/profit',        'ProductionOrdersController@getDocumentProfit'   )->name('productionorders.profit'     );
        Route::get('productionorders/{id}/materials',  'ProductionOrdersController@getDocumentMaterials' )->name('productionorders.materials' );

        Route::get('productionorders/{id}/getlotsline/{lid}', 'ProductionOrdersController@getDocumentLotsLine'     )->name('productionorders.getlotsline'    );
        Route::post('productionorders/updatelotsline/{lid}',  'ProductionOrdersController@updateDocumentLotsLine'  )->name('productionorders.updatelotsline' );
        
        Route::get('productionorders/{id}/availability/modal',  'ProductionOrdersController@getDocumentAvailabilityModal' )->name('productionorders.availability.modal' );


        Route::post('productionorders/{id}/quickaddlines',    'ProductionOrdersController@quickAddLines'   )->name('productionorders.quickaddlines'  );

        Route::post('productionorders/sortlines', 'ProductionOrdersController@sortLines')->name('productionorders.sortlines');


        Route::get('productionorders/{document}/confirm',   'ProductionOrdersController@confirm'  )->name('productionorders.confirm'  );
        Route::get('productionorders/{document}/unconfirm', 'ProductionOrdersController@unConfirm')->name('productionorders.unconfirm');

        Route::get('productionorders/{id}/pdf',         'ProductionOrdersController@showPdf'       )->name('productionorders.pdf'        );
//        Route::post('productionorders/pdf/bulk',        'ProductionOrdersController@showBulkPdf'   )->name('productionorders.bulk.pdf'   );
        Route::match(array('GET', 'POST'), 
                   'productionorders/{id}/email',       'ProductionOrdersController@sendemail'     )->name('productionorders.email'      );

        Route::get('productionorders/{document}/onhold/toggle', 'ProductionOrdersController@onholdToggle')->name('productionorders.onhold.toggle');

        Route::get('productionorders/{document}/finish',   'ProductionOrdersController@finish'  )->name('productionorders.finish'  );
        Route::get('productionorders/{document}/unfinish', 'ProductionOrdersController@unfinish')->name('productionorders.unfinish');

        Route::get('productionorders/{id}/deliver' , 'ProductionOrdersController@deliver'    )->name('productionorder.deliver');
//        Route::post('productionorders/deliver/bulk', 'ProductionOrdersController@deliverBulk')->name('productionorders.bulk.deliver');

        Route::get('productionorders/{id}/undeliver'  , 'ProductionOrdersController@undeliver')->name('productionorder.undeliver');



        Route::resource('productionorderlines.lots', 'ProductionOrderLineLotsController');


});
