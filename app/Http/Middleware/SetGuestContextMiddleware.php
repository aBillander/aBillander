<?php namespace App\Http\Middleware;

use Closure;

use App\Configuration as Configuration;
use App\Company as Company;
use App\Context as Context;
use App\Language as Language;
use Illuminate\Support\Str as Str;
use Auth;
use App\User as User;
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
		
		if (Cookie::get('user_language') !== null) 
			$language = Language::find( intval( \Crypt::decrypt(Cookie::get('user_language')) ) );
		
		if ( !$language )
			$language = Language::find( intval(Configuration::get('DEF_LANGUAGE')) );

		if ( !$language )
			$language = Language::where('iso_code', '=', \Config::get('app.fallback_locale'))->first();

		if ( !$language ) {
			$language = new \stdClass();
			$language->iso_code = \Config::get('app.fallback_locale');
		}
		

		Context::getContext()->user       = $user;
		Context::getContext()->language   = $language;

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

		return $next($request);
	}

}
