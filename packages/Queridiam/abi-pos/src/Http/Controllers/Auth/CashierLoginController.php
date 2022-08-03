<?php

namespace Queridiam\POS\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Queridiam\POS\Models\CashierUser;
use App\Models\Language;
use Auth;
use Illuminate\Http\Request;

class CashierLoginController extends Controller
{
    //

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest')->except('logout');
        $this->middleware('guest:cashier')->except(['cashierLogout', 'logout']);
    }

    public function showLoginForm()
    {
      $languages = Language::orderBy('name')->get();

      // ToDo: remember language using cookie :: echo Request::cookie('user_language');

      return view('pos::auth.cashier_login')->with(compact('languages'));
    }

    public function login(Request $request)
    {
      // Validate the form data
        $vrules = CashierUser::$rules;

        if ( isset($vrules['email']) ) $vrules['email'] .= ','. $request->email.',email';  // Unique
      
      $this->validate($request, $vrules);

      // abi_r($request->all());die();

      // Attempt to log the user in
      if (Auth::guard('cashier')->attempt(['email' => $request->email, 'password' => $request->password, 'active' => 1], $request->remember)) {
        
        // if successful, then redirect to their intended location
        if ( Configuration::get('POS_LOGIN_REDIRECT') && \Route::has(Configuration::get('POS_LOGIN_REDIRECT')) )
          return redirect()->route( Configuration::get('POS_LOGIN_REDIRECT') );
        else
          return redirect()->route('pos::interface');
      }

      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    // Not working:
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['active'] = 1;

        return $credentials;
    }

    // See: /vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php
    // No customization: logout ALL users at once!
/*    
    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect('/');
    }
*/
    
    public function cashierLogout()
    {
        Auth::guard('cashier')->logout();
        return redirect('/');
    }

    /**
     * Update DEFAULT language (application wide, not logged-in usersS).
     *
     * @return Response
     */
    public function setLanguage($id)
    {
      $language = Language::findOrFail( $id );

      Cookie::queue('user_language', $language->id, 30*24*60);
      
      return redirect('/abcc');
    }
}
