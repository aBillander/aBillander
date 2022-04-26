<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();


        if ( ! checkRoute( $user_home = Auth::user()->home_page ) ) 
        {
            $user_home =  RouteServiceProvider::USERS_HOME;
        }

        return redirect( $user_home );
//        return redirect()->intended( $user_home );
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
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
