<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

// Original stuff:
//            if (Auth::guard($guard)->check()) {
//                return redirect(RouteServiceProvider::HOME);
//            }
            
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
                $home_page = Auth::user()->home_page;

                if ( $home_page == '/' ) 
                    $home_page ='/home';
                
                if ( checkRoute( $home_page ) ) 
                {
                    return redirect( $home_page );
                
                } else {
                    return redirect('/home');
                }
            }
        }

/* What is this for? =>

        \App\Models\Context::getContext()->language = \App\Models\Language::find( intval(\App\Models\Configuration::get('DEF_LANGUAGE')) );

        // Changing The Default Language At Runtime
        \App::setLocale(\App\Models\Context::getContext()->language->iso_code);
*/

        return $next($request);
    }
}
