<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    // php artisan event:generate
    protected $listen = [
        'App\Events\ProductCreated' => [
            'App\Listeners\InitializeStock',
            'App\Listeners\AssignToPriceLists',
        ],
/*
        'App\Events\CustomerInvoiceViewed' => [
            'App\Listeners\CustomerInvoiceSetDates',
        ],
        
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
*/
    ];
    // php artisan event:generate

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
