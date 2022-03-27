<?php

namespace App\Http\Controllers\Auth;

use App\Configuration;
use App\CustomerUser;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Auth;
use Illuminate\Http\Request;

class CustomerLoginController extends Controller
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
        $this->middleware('guest:customer')->except(['customerLogout', 'logout']);
    }

    public function showLoginForm()
    {
      $languages = Language::orderBy('name')->get();

      // ToDo: remember language using cookie :: echo Request::cookie('user_language');

      return view('auth.customer_login')->with(compact('languages'));
    }

    public function login(Request $request)
    {
      // Validate the form data
        $vrules = CustomerUser::$rules;

        if ( isset($vrules['email']) ) $vrules['email'] .= ','. $request->email.',email';  // Unique
      
      $this->validate($request, $vrules);

      // Attempt to log the user in
      if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password, 'active' => 1], $request->remember)) {
        
        // if successful, then redirect to their intended location
        if ( Configuration::get('ABCC_LOGIN_REDIRECT') && \Route::has(Configuration::get('ABCC_LOGIN_REDIRECT')) )
          return redirect()->route( Configuration::get('ABCC_LOGIN_REDIRECT') );
        else
          return redirect()->intended(route('customer.dashboard'));
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
    
    public function customerLogout()
    {
        Auth::guard('customer')->logout();
        return redirect('/abcc');
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
