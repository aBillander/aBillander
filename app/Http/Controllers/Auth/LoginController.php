<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware(['guest', 'guest:customer'])->except('logout');
        $this->middleware(['guest'])->except('logout');
    }
/* */
    //Remember to use Auth;
    protected function authenticated()
    {
        if ( checkRoute( Auth::user()->home_page ) ) 
        {
            return redirect( Auth::user()->home_page );
        
        } else {
            return redirect('/home');
        }

        if(Auth::User()->isAdmin())
        {
            return redirect('/admin');
        }
        else
        {
            return redirect('/');
        }
    }
/* */
/* */
    public function userLogout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }
/* */
/*

namespace Illuminate\Foundation\Auth;

trait RedirectsUsers
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     * /
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}


*/
}
