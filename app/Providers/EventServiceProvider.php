<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

// aBillander stuff:        

        'Illuminate\Mail\Events\MessageSent' => [
            'App\Listeners\LogSentMessageListener',
        ],

// Customer Documents
    
        'App\Events\CustomerInvoiceConfirmed' => [
            'App\Listeners\CustomerInvoiceConfirmedListener',
        ],
    
        'App\Events\CustomerInvoiceClosed' => [
            'App\Listeners\CustomerInvoiceClosedListener',
        ],
    
        'App\Events\CustomerInvoiceUnclosed' => [
            'App\Listeners\CustomerInvoiceUnclosedListener',
        ],
    
        'App\Events\CustomerInvoicePrinted' => [
            'App\Listeners\CustomerInvoicePrintedListener',
        ],
    
        'App\Events\CustomerInvoicePosted' => [
            'App\Listeners\CustomerInvoicePostedListener',
        ],
    
        'App\Events\CustomerInvoiceEmailed' => [
            'App\Listeners\CustomerInvoiceEmailedListener',
        ],

        'App\Events\CustomerInvoiceEmailing' => [
            'App\Listeners\CustomerInvoiceEmailingListener',
        ],

    
        'App\Events\CustomerPaymentReceived' => [
            'App\Listeners\CustomerPaymentReceivedListener',
        ],
    
        'App\Events\CustomerPaymentBounced' => [
            'App\Listeners\CustomerPaymentBouncedListener',
        ],

        


        'App\Events\CustomerShippingSlipConfirmed' => [
            'App\Listeners\CustomerShippingSlipConfirmedListener',
        ],
    
        'App\Events\CustomerShippingSlipClosed' => [
            'App\Listeners\CustomerShippingSlipClosedListener',
        ],
    
        'App\Events\CustomerShippingSlipUnclosed' => [
            'App\Listeners\CustomerShippingSlipUnclosedListener',
        ],
    
        'App\Events\CustomerShippingSlipPrinted' => [
            'App\Listeners\CustomerShippingSlipPrintedListener',
        ],
    
        'App\Events\CustomerShippingSlipEmailed' => [
            'App\Listeners\CustomerShippingSlipEmailedListener',
        ],



// Warehouse Transfer Documents

        'App\Events\WarehouseShippingSlipConfirmed' => [
            'App\Listeners\WarehouseShippingSlipConfirmedListener',
        ],
    
        'App\Events\WarehouseShippingSlipClosed' => [
            'App\Listeners\WarehouseShippingSlipClosedListener',
        ],
    
        'App\Events\WarehouseShippingSlipUnclosed' => [
            'App\Listeners\WarehouseShippingSlipUnclosedListener',
        ],
    
        'App\Events\WarehouseShippingSlipPrinted' => [
            'App\Listeners\WarehouseShippingSlipPrintedListener',
        ],



// Supplier Documents
    
        'App\Events\SupplierInvoiceConfirmed' => [
            'App\Listeners\SupplierInvoiceConfirmedListener',
        ],
    
        'App\Events\SupplierInvoiceClosed' => [
            'App\Listeners\SupplierInvoiceClosedListener',
        ],
    
        'App\Events\SupplierInvoiceUnclosed' => [
            'App\Listeners\SupplierInvoiceUnclosedListener',
        ],
    
        'App\Events\SupplierInvoicePrinted' => [
            'App\Listeners\SupplierInvoicePrintedListener',
        ],
    
        'App\Events\SupplierInvoicePosted' => [
            'App\Listeners\SupplierInvoicePostedListener',
        ],
    
        'App\Events\SupplierInvoiceEmailed' => [
            'App\Listeners\SupplierInvoiceEmailedListener',
        ],

    
        'App\Events\SupplierPaymentPaid' => [
            'App\Listeners\SupplierPaymentPaidListener',
        ],
    
        'App\Events\SupplierPaymentBounced' => [
            'App\Listeners\SupplierPaymentBouncedListener',
        ],

        


        'App\Events\SupplierShippingSlipConfirmed' => [
            'App\Listeners\SupplierShippingSlipConfirmedListener',
        ],
    
        'App\Events\SupplierShippingSlipClosed' => [
            'App\Listeners\SupplierShippingSlipClosedListener',
        ],
    
        'App\Events\SupplierShippingSlipUnclosed' => [
            'App\Listeners\SupplierShippingSlipUnclosedListener',
        ],
    
        'App\Events\SupplierShippingSlipPrinted' => [
            'App\Listeners\SupplierShippingSlipPrintedListener',
        ],
    
        'App\Events\SupplierShippingSlipEmailed' => [
            'App\Listeners\SupplierShippingSlipEmailedListener',
        ],




        
// Miscellaneous        

        'App\Events\DatabaseBackup' => [
            'App\Listeners\DatabaseBackupListener',
        ],




        'App\Events\CustomerRegistered' => [
            'App\Listeners\NewCustomerRegistered',
        ],

        'App\Events\ProductCreated' => [
            'App\Listeners\InitializeStock',
            'App\Listeners\AssignToPriceLists',
        ],

        // MFG
    
        'App\Events\ProductionOrderFinished' => [
            'App\Listeners\ProductionOrderFinishedListener',
        ],
    
        'App\Events\ProductionOrderUnfinished' => [
            'App\Listeners\ProductionOrderUnfinishedListener',
        ],
    
        'App\Events\AssemblyOrderFinished' => [
            'App\Listeners\AssemblyOrderFinishedListener',
        ],
    
        'App\Events\AssemblyOrderUnfinished' => [
            'App\Listeners\AssemblyOrderUnfinishedListener',
        ],

/*
        'App\Events\CustomerInvoiceViewed' => [
            'App\Listeners\CustomerInvoiceSetDates',
        ],
*/
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
