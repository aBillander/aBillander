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
		if ( !$request->user()->isAdmin() )
		{
			return redirect('404');
		}

		return $next($request);
	}

}
