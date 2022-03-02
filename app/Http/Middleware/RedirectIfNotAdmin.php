<?php 

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotAdmin {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
		// if ( !auth()->check() || !auth()->user()->isAdmin() ) // Not logged or not Admin
		if ( !$request->user() || !$request->user()->isAdmin() ) /** if not logged at all redirect to home. this is to prevent an error if the user is not logged in and tries to access the portal via the url **/
		{
			return redirect('404');
		}

		return $next($request); /** User is admin **/
	}

}
