<?php 

namespace App\Http\Middleware;

use Closure;

use App\Configuration as Configuration;
use App\Currency as Currency;
use App\Context as Context;
use App\Language as Language;
use Illuminate\Support\Str as Str;
use Auth;
use App\User as User;
use Config, App;
use Request, Cookie;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SetContextMiddleware {

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

		if( Auth::check() )
			$user = User::find( Auth::id() );		// $email = Auth::user()->email;
		else
			$user = NULL;
//			$user = new \stdClass();
		
		$language = Language::where('iso_code', '=', Config::get('app.locale'))->first();
		
		if ( !$language )
			$language = Language::find( intval(Configuration::get('DEF_LANGUAGE')) );

		if ( !$language )
			$language = Language::where('iso_code', '=', Config::get('app.fallback_locale'))->first();

		if ( !$language ) {
			$language = new \stdClass();
			$language->iso_code = Config::get('app.fallback_locale');
		}
		

		$currency = Currency::find( intval(Configuration::get('DEF_CURRENCY')) );

		$company = new \stdClass();
		$company->name_commercial = Configuration::get('XTR_COMPAMY_NAME');
		$company->identification = '';
		$company->currency = $currency;	// Or fallback to Configuration::get('DEF_CURRENCY')


		Context::getContext()->user       = $user;
		Context::getContext()->language   = $language;

		Context::getContext()->company    = $company;
		Context::getContext()->currency   = $company->currency;

		// Not really "the controller", but enough to retrieve translation files
		Context::getContext()->controller = $request->segment(1);
		if ($request->segment(3) == 'options' ) Context::getContext()->controller = $request->segment(3);
		if ($request->segment(3) == 'states'  ) Context::getContext()->controller = $request->segment(3);
		if ($request->segment(3) == 'taxrules') Context::getContext()->controller = $request->segment(3);
		Context::getContext()->action     = NULL;

// abi_r(Context::getContext()->user);
// abi_r(Context::getContext()->language);
// abi_r(Context::getContext()->company);
// abi_r(Context::getContext()->currency, true);


		// Changing Timezone At Runtime. But this change does not seem to be taken by Carbon... Why?
		// Config::set('app.timezone', Configuration::get('TIMEZONE'));

		// Changing The Default Language At Runtime
		// App::setLocale(Context::getContext()->language->iso_code); 

/*
		// Changing The Default Theme At Runtime
		// https://laracasts.com/discuss/channels/laravel/overriding-laravels-view-with-views-from-custom-package
		$paths = \Config::get('view.paths');
		array_unshift($paths, realpath(base_path('resources/views')).'/../theme');
		\Config::set('view.paths', $paths);

//		print_r(\Config::get('view.paths')); die();

		// https://stackoverflow.com/questions/27458439/how-to-set-view-file-path-in-laravel
		// Apparently setting the config won't change anything because it is loaded when the application bootstraps and ignored afterwards.
		// To change the path at runtime you have to create a new instance of the FileViewFinder. Here's how that looks like:
		$finder = new \Illuminate\View\FileViewFinder(app()['files'], $paths);
		\View::setFinder($finder);
*/

		return $next($request);
	}

}
