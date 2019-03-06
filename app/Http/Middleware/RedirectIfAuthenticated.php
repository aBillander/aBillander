<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            
            // Customers
            if ( $guard == 'customer' ) {
                return redirect()->route('customer.dashboard');
            }
            
            // Sales Reps
            if ( $guard == 'salesrep' ) {
                return redirect()->route('salesrep.dashboard');
            }

            // Regular Users
            if ( Auth::user()->home_page == '/' ) 
                return redirect('/home');
            else
                if ( checkRoute( Auth::user()->home_page ) )
                    return redirect( Auth::user()->home_page );
                else
                    return redirect('/home');

            // return redirect('/home');
        }

        return $next($request);
    }
}
