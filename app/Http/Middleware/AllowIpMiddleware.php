<?php

namespace App\Http\Middleware;

use App\Models\Configuration;
use Closure;

class AllowIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( Configuration::isEmpty('ALLOW_IP_ADDRESSES') )
            return $next($request);

        $allowed_ips = Configuration::get('ALLOW_IP_ADDRESSES');       // ' 127. 0.0.1 ';  // "Comma seperated IP address which is to be allowed"; 
        
        $ipsAllow = array_filter(explode(',', preg_replace('/\s+/', '', $allowed_ips) ));   // array_filter prevents splitting empty strings.

        // abi_r($ipsAllow); die();

        // Allow (allways) localhost
        if( in_array(request()->ip(), ['127.0.0.1', '::1', 'localhost']) )
        {
            return $next($request);

        } 

        if(count($ipsAllow) >= 1 )
        {
            if(in_array(request()->ip(), $ipsAllow))
            {
                return $next($request);

            } else {

                \Log::warning("Unauthorized access, IP address was => ".request()->ip());
                
                return abort(404);
                // return response()->json(['Unauthorized!'],400);
            }
        }

        return $next($request);

    }
}

/*

php artisan make:middleware AllowIpMiddleware

https://laraveldaily.com/check-access-laravel-project-ip-address/

https://kpktechnology.com/block-ip-address-accessing-laravel-application/

http://www.expertphp.in/article/php-laravel-5-8-how-to-block-ip-addresses-from-accessing-the-application

*/
