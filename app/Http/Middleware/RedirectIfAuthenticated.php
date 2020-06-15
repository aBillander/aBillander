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
            if ( $guard == 'web' ) {
                return redirect( Auth::user()->home_page );
            }

            // Regular Users
            // return redirect('/home');
            // return redirect()->route( 'jennifer.home' );
            // return redirect()->route( Auth::user()->home_page );
            //    abi_r( Auth::user()->home_page );
            //    abi_r( checkRoute( Auth::user()->home_page ) );die();

            if ( Auth::user()->home_page == '/' ) 
                return redirect('/home');
            else 
            {
                if ( checkRoute( Auth::user()->home_page ) ) 
                {
                    return redirect( Auth::user()->home_page );
                
                } else {
                    return redirect('/home');
                }
            }

            // return redirect('/home');
        }

        \App\Context::getContext()->language = \App\Language::find( intval(\App\Configuration::get('DEF_LANGUAGE')) );

        // Changing The Default Language At Runtime
        \App::setLocale(\App\Context::getContext()->language->iso_code);

        return $next($request);
    }
}
