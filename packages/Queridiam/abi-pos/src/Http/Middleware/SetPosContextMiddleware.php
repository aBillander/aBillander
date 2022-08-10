<?php

namespace Queridiam\POS\Http\Middleware;

// use App\Models\Cart;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\Context;
use App\Models\Currency;
use App\Models\Language;
use Queridiam\POS\Models\CashierUser;
use Auth;
use Closure;
use Config, App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Request, Cookie;		// , DB, Session;

// use aBillander\Installer\Helpers\Installer;

class SetPosContextMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
        if ($guard == "cashier" && Auth::guard($guard)->check())
        {
            if ( !Auth::guard($guard)->user()->isActive() )
            {
                Auth::logout();
                return redirect()->route('pos::cashier.login')->with('warning', l('Your session has expired because your account is deactivated.', 'pos/layouts'));
            }
        } else {
        	return $next($request);
        }

		$cashier_user = Auth::guard($guard)->user();
        $cashregister = Auth::guard($guard)->user()->cashregister;
        $language     = $cashier_user->language;

//        abi_r($cashier_user, true);

//		 abi_r($this->customer->name_fiscal);die();

//		$cart = Cart::getCustomerUserCart();
/*
			Context::getContext()->user       = $user;
			Context::getContext()->language   = $language;

			Cookie::queue('user_language', $language->id, 30*24*60);

			Context::getContext()->company    = $company;
			Context::getContext()->currency   = $company->currency;
*/

		// abi_r($cart->customer->name, true);

		Context::getContext()->cashier_user = $cashier_user;
		Context::getContext()->cashregister  = $cashregister;
		Context::getContext()->language      = $language;
		Context::getContext()->currency      = $cashregister->currency;
//		Context::getContext()->cart          = $cart;

		// abi_r($guard);die();

		return $next($request);

		if (0 ) {	// && Installer::alreadyInstalled()) {
			/*
			|--------------------------------------------------------------------------
			| Application Configuration
			|--------------------------------------------------------------------------
			|
			| Load Context.
			|
			*/

			// Set database
			// https://stackoverflow.com/questions/21918457/laravel-selecting-the-database-to-use-at-login


			// Continue stuff

			if( Auth::check() )
				$user = User::with('language')->find( Auth::id() );		// $email = Auth::user()->email;
			else
				$user = User::with('language')->where('is_admin', 1)->first( );		// Gorrino sensible default
	//			$user = new \stdClass();

			// Set Language
	//		App::setLocale('es');
	//		$language = Language::where('iso_code', '=', Config::get('app.locale'))->first();
			$language = $user->language;

			// Better use defautt language
			// $language = Language::find( intval(Configuration::get('DEF_LANGUAGE')) );

			if ( !$language )
				$language = Language::find( intval(Configuration::get('DEF_LANGUAGE')) );

			if ( !$language )
				$language = Language::where('iso_code', '=', Config::get('app.fallback_locale'))->first();

			if ( !$language ) {
				$language = new \stdClass();
				$language->iso_code = Config::get('app.fallback_locale');
			}

			// Set Company
			try {
				$company = Company::with('currency')->findOrFail( intval(Configuration::get('DEF_COMPANY')) );
			} catch (ModelNotFoundException $ex) {
				// If Company does not found. Not any good here...
				$company = new \stdClass();
				$company->currency = NULL;	// Or fallback to Configuration::get('DEF_CURRENCY')

				// Maybe:
				// abort(404);
				// Or redirect to installer
				// if (\Route::currentRouteName() != 'installer') {
	    		//	return redirect()->route('installer');
			}

	/*
			$currency = Currency::find( intval(Configuration::get('DEF_CURRENCY')) );

			$company = new \stdClass();
			$company->name_commercial = Configuration::get('XTR_COMPAMY_NAME');
			$company->identification = '';
			$company->currency = $currency;	// Or fallback to Configuration::get('DEF_CURRENCY')
	*/

			Context::getContext()->user       = $user;
			Context::getContext()->language   = $language;

			Cookie::queue('user_language', $language->id, 30*24*60);

			Context::getContext()->company    = $company;
			Context::getContext()->currency   = $company->currency;


	/*

	abi_r(Context::getContext()->user );
	abi_r('********************************************************');
	abi_r(Context::getContext()->language);
	abi_r('********************************************************');
	abi_r(Context::getContext()->company);
	abi_r('********************************************************');
	abi_r(Context::getContext()->currency, true);
	abi_r('********************************************************');

	*/


			// Not really "the controller", but enough to retrieve translation files
			$dominion = $request->segment(1);
			// Known dominions:
			if ( $dominion == 'abcc' ) {
				//
				Context::getContext()->controller = 'abcc/'.$request->segment(2);

			} else {
				//
				Context::getContext()->controller = $dominion;
				if ($request->segment(3) == 'options' ) Context::getContext()->controller = $request->segment(3);
				if ($request->segment(3) == 'states'  ) Context::getContext()->controller = $request->segment(3);
				if ($request->segment(3) == 'taxrules') Context::getContext()->controller = $request->segment(3);
				if ($request->segment(3) == 'pricelistlines') Context::getContext()->controller = $request->segment(3);
				if ($request->segment(3) == 'stockcountlines') Context::getContext()->controller = $request->segment(3);
	//			Context::getContext()->action     = NULL;

			}


	// abi_r(Context::getContext()->user);
	// abi_r(Context::getContext()->language);
	// abi_r(Context::getContext()->company);
	// abi_r(Context::getContext()->currency, true);
	// die();

			// Changing Timezone At Runtime. But this change does not seem to be taken by Carbon... Why?
			// Config::set('app.timezone', Configuration::get('TIMEZONE'));

			// Changing The Default Language At Runtime
			App::setLocale(Context::getContext()->language->iso_code);

	/*
			// Changing The Default Theme At Runtime
			// https://laracasts.com/discuss/channels/laravel/overriding-laravels-view-with-views-from-custom-package
			$paths = \Config::get('view.paths');
			array_unshift($paths, realpath(base_path('resources/views')).'/../theme');	// /var/www/html/enatural/resources/views/../theme
			\Config::set('view.paths', $paths);


	//		print_r(\Config::get('view.paths')); die();

	// https://github.com/igaster/laravel-theme
	// https://hackernoon.com/data-interceptor-for-your-views-laravel-5-5-c973a96bb45a
	// https://www.addwebsolution.com/blog/voyager-missing-laravel-admin
	// https://laracasts.com/discuss/channels/laravel/overriding-laravels-view-with-views-from-custom-package

	*/

			// https://stackoverflow.com/questions/27458439/how-to-set-view-file-path-in-laravel
			// Apparently setting the config won't change anything because it is loaded when the application bootstraps and ignored afterwards.
			// To change the path at runtime you have to create a new instance of the FileViewFinder. Here's how that looks like:
			if ( !Configuration::isEmpty('USE_CUSTOM_THEME') ) 		// ToDo: Move this to a ServiceProvider
			{
	/*			$paths = \Config::get('view.paths');
				array_unshift($paths, realpath(base_path('resources/views')).'/../theme');
				$finder = new \Illuminate\View\FileViewFinder(app()['files'], $paths);
		//		abi_r($finder, true);
				\View::setFinder($finder);
	*/
				\View::getFinder()->prependLocation( realpath(base_path('resources/views')).'/../theme/'.Configuration::get('USE_CUSTOM_THEME') );
			}

		}

		return $next($request);
	}

}
