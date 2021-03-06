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

        $pairs = [
        	/*
                [
                    'controller' => 'SupplierQuotationsController',
                    'path' => 'supplierquotations',
                ],
            */
                [
                    'controller' => 'SupplierOrdersController',
                    'path' => 'supplierorders',
                ],
                [
                    'controller' => 'SupplierShippingSlipsController',
                    'path' => 'suppliershippingslips',
                ],
                [
                    'controller' => 'SupplierInvoicesController',
                    'path' => 'supplierinvoices',
                ],
        ];


foreach ($pairs as $pair) {

        $controller = $pair['controller'];
        $path = $pair['path'];

        Route::resource($path, $controller);
        Route::get($path.'/create/withsupplier/{supplier_id}', $controller.'@createWithSupplier')->name($path.'.create.withsupplier');

        Route::get($path.'/ajax/supplier_lookup', $controller.'@ajaxSupplierSearch')->name($path.'.ajax.supplierLookup');
        Route::get($path.'/ajax/supplier/{id}/adressbook_lookup', $controller.'@supplierAdressBookLookup')->name($path.'.ajax.supplier.AdressBookLookup');

        Route::get($path.'/{id}/getlines',             $controller.'@getDocumentLines'  )->name($path.'.getlines' );
        Route::get($path.'/{id}/getheader',            $controller.'@getDocumentHeader' )->name($path.'.getheader');
        Route::get($path.'/line/productform/{action}', $controller.'@FormForProduct')->name($path.'.productform');
        Route::get($path.'/line/serviceform/{action}', $controller.'@FormForService')->name($path.'.serviceform');
        Route::get($path.'/line/commentform/{action}', $controller.'@FormForComment')->name($path.'.commentform');
        Route::get($path.'/line/searchproduct',        $controller.'@searchProduct' )->name($path.'.searchproduct');
        Route::get($path.'/line/searchservice',        $controller.'@searchService' )->name($path.'.searchservice');
        Route::get($path.'/line/getproduct',           $controller.'@getProduct'      )->name($path.'.getproduct');
        Route::get($path.'/line/getproduct/prices',    $controller.'@getProductPrices')->name($path.'.getproduct.prices');

        // ?? Maybe only for Invoices ??
        Route::get($path.'/{id}/getpayments',          $controller.'@getDocumentPayments' )->name($path.'.getpayments');

        // ?? Maybe only for Orders ??
        Route::get($path.'/{id}/getdownpayments',      $controller.'@getDocumentDownPayments' )->name($path.'.getdownpayments');


        Route::post($path.'/{id}/storeline',    $controller.'@storeDocumentLine'   )->name($path.'.storeline'  );
        Route::post($path.'/{id}/updatetotal',  $controller.'@updateDocumentTotal' )->name($path.'.updatetotal');
        Route::get($path.'/{id}/getline/{lid}', $controller.'@getDocumentLine'     )->name($path.'.getline'    );
        Route::post($path.'/updateline/{lid}',  $controller.'@updateDocumentLine'  )->name($path.'.updateline' );
        Route::post($path.'/deleteline/{lid}',  $controller.'@deleteDocumentLine'  )->name($path.'.deleteline' );
        Route::get($path.'/{id}/duplicate',     $controller.'@duplicateDocument'   )->name($path.'.duplicate'  );
        Route::get($path.'/{id}/profit',        $controller.'@getDocumentProfit'   )->name($path.'.profit'     );

        Route::get($path.'/{id}/entries',       $controller.'@getDocumentEntries'  )->name($path.'.entries'    );
        
        Route::get($path.'/{id}/availability/modal',  $controller.'@getDocumentAvailabilityModal' )->name($path.'.availability.modal' );

        Route::post($path.'/{id}/quickaddlines',    $controller.'@quickAddLines'   )->name($path.'.quickaddlines'  );

        Route::post($path.'/sortlines', $controller.'@sortLines')->name($path.'.sortlines');

        Route::get($path.'/{document}/confirm',   $controller.'@confirm'  )->name($path.'.confirm'  );
        Route::get($path.'/{document}/unconfirm', $controller.'@unConfirm')->name($path.'.unconfirm');

        Route::get($path.'/{id}/pdf',         $controller.'@showPdf'       )->name($path.'.pdf'        );
        Route::get($path.'/{id}/invoice/pdf', $controller.'@showPdfInvoice')->name($path.'.invoice.pdf');
        Route::match(array('GET', 'POST'), 
                   $path.'/{id}/email',       $controller.'@sendemail'     )->name($path.'.email'      );

        Route::get($path.'/{document}/onhold/toggle', $controller.'@onholdToggle')->name($path.'.onhold.toggle');

        Route::get($path.'/{document}/close',   $controller.'@close'  )->name($path.'.close'  );
        Route::get($path.'/{document}/unclose', $controller.'@unclose')->name($path.'.unclose');

        Route::get($path.'/suppliers/{id}',  $controller.'@indexBySupplier')->name('supplier.'.str_replace('supplier', '', $path));

        Route::get($path.'/{id}/reload/costs', $controller.'@reloadCosts')->name($path.'.reload.costs'        );

        Route::post($path.'/{id}/attachment',         $controller.'@attachmentStore'  )->name($path.'.attachment.store'  );
        Route::get($path.'/{id}/attachment/{aid}',    $controller.'@attachmentShow'   )->name($path.'.attachment.show'   );
        Route::delete($path.'/{id}/attachment/{aid}',   $controller.'@attachmentDestroy')->name($path.'.attachment.destroy');
}

        Route::post('supplierorders/create/shippingslip/single',  'SupplierOrdersController@createSingleShippingSlip')->name('supplierorder.single.shippingslip');

        Route::get( 'suppliershippingslips/suppliers/{id}/invoiceables',  'SupplierShippingSlipsController@getInvoiceableShippingSlips')->name('supplier.invoiceable.shippingslips');
        Route::post('suppliershippingslips/create/invoice',  'SupplierShippingSlipsController@createGroupInvoice')->name('suppliershippingslips.create.invoice');
        Route::get( 'suppliershippingslips/{id}/invoice'  , 'SupplierShippingSlipsController@createInvoice')->name('suppliershippingslip.invoice');
        Route::post('suppliershippingslips/{id}/invoice/undo'  , 'SupplierShippingSlipsController@undoInvoice')->name('suppliershippingslip.invoice.undo');

        Route::get('suppliershippingslips/pending/today',  'SupplierShippingSlipsController@getTodaysShippingSlips')->name('suppliershippingslips.for.today');


        Route::post('supplierinvoices/{id}/shippingslip/add'  , 'SupplierInvoicesController@addShippingSlipToInvoice')->name('supplierinvoice.shippingslip.add');

    
        Route::resource('suppliershippingsliplines.lots', 'SupplierShippingSlipLineLotsController');



        Route::resource('suppliervouchers'      , 'SupplierVouchersController');
        Route::get('suppliervouchers/{id}/setduedate'  , 'SupplierVouchersController@setduedate');
        Route::get('suppliervouchers/{id}/pay'  , 'SupplierVouchersController@pay');
        Route::post('suppliervouchers/{id}/unlink', 'SupplierVouchersController@unlink')->name('suppliervoucher.unlink');

        Route::post('suppliervouchers/payvouchers'  , 'SupplierVouchersController@payVouchers')->name('suppliervouchers.payvouchers');

        Route::post('suppliervouchers/unlinkvouchers'  , 'SupplierVouchersController@unlinkVouchers')->name('suppliervouchers.unlinkvouchers');

        Route::get('suppliervouchers/{id}/expresspay', 'SupplierVouchersController@expressPayVoucher')->name('suppliervoucher.expresspay');
        Route::get('suppliervouchers/{id}/unpay', 'SupplierVouchersController@unPayVoucher')->name('suppliervoucher.unpay');
        
        Route::get('suppliervouchers/{id}/collectible', 'SupplierVouchersController@collectibleVoucher')->name('suppliervoucher.collectible');

        Route::get('suppliervouchers/suppliers/{id}',  'SupplierVouchersController@indexBySupplier')->name('supplier.vouchers');

        Route::get('suppliervouchers/suppliers/{id}/pending',  'SupplierVouchersController@indexBySupplierPending')->name('supplier.vouchers.pending');

        Route::get( 'export/suppliervouchers', 'SupplierVouchersController@export' )->name('suppliervouchers.export');

