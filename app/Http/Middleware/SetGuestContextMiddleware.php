<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Configuration;
use App\Models\Company;
use App\Models\Context;
use App\Models\Language;

use Auth;
use App\Models\User;
use Config, App;
use Request, Cookie;

class SetGuestContextMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{

		/*
		|--------------------------------------------------------------------------
		| Application Configuration
		|--------------------------------------------------------------------------
		|
		| Load Context.
		|
		*/

		$user = NULL;
		
		$language = null;
		
		// Todo: make this work
		// if (Cookie::get('user_language') !== null) 
		//	$language = Language::find( intval( \Crypt::decrypt(Cookie::get('user_language')) ) );
		
		if ( !$language )
			$language = Language::find( intval(Configuration::get('DEF_LANGUAGE')) );

		if ( !$language )
			$language = Language::where('iso_code', '=', \Config::get('app.fallback_locale'))->first();

		if ( !$language ) {
			$language = new \stdClass();
			$language->iso_code = \Config::get('app.fallback_locale');
		}
		

		Context::getContext()->user       = $user;

        if ( Configuration::isNotEmpty('USE_CUSTOM_THEME') )
        {
            Context::getContext()->theme = Configuration::get('USE_CUSTOM_THEME');
        }
		Context::getContext()->language   = $language;

		// Extract the subdomain from URL.
    	list($subdomain) = explode('.', $request->getHost(), 2);
		Context::getContext()->tenant = $subdomain;

		// Not really "the controller", but enough to retrieve translation files
		Context::getContext()->controller = $request->segment(1);
		if ($request->segment(3) == 'options' ) Context::getContext()->controller = $request->segment(3);
		if ($request->segment(3) == 'states'  ) Context::getContext()->controller = $request->segment(3);
		if ($request->segment(3) == 'taxrules') Context::getContext()->controller = $request->segment(3);
		Context::getContext()->action     = NULL;

		// Changing Timezone At Runtime. But this change does not seem to be taken by Carbon... Why?
		// if ( Configuration::get('TIMEZONE') )
		// 	Config::set('app.timezone', Configuration::get('TIMEZONE'));

		// Changing The Default Language At Runtime
		App::setLocale(Context::getContext()->language->iso_code); 

		// Changing theme
			if ( Context::getContext()->theme ) 		// ToDo: Move this to a ServiceProvider
			{
	/*			$paths = \Config::get('view.paths');
				array_unshift($paths, realpath(base_path('resources/views')).'/../theme');
				$finder = new \Illuminate\View\FileViewFinder(app()['files'], $paths);
		//		abi_r($finder, true);
				\View::setFinder($finder);
	*/
				\View::getFinder()->prependLocation( realpath(base_path('resources/views')).'/../theme/'.Context::getContext()->theme );
			}
			// https://eoghanobrien.com/posts/building-a-theme-system-in-laravel
			// https://laracasts.com/discuss/channels/code-review/dynamic-view-folder-destination-in-laravel-53?page=0


		return $next($request);
	}

}
