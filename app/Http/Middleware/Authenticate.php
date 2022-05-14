<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // abi_r(\Route::currentRouteName());die();

        if (! $request->expectsJson()) {
            
            // ABCC routes
            if ( $request->is('abcc*') ) {
                return route('customer.login');
            }
            
            // ABSRC routes
            if ( $request->is('absrc*') ) {
                return route('salesrep.login');
            }


            // Default route
            return route('login');
        }
    }
}
