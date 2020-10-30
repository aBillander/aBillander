<?php

namespace aBillander\Installer\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use aBillander\Installer\Helpers\Installer;

class CanInstall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        if (Installer::alreadyInstalled()) {
            abort(404);
        }

        if (Session::exists('install-finished')) {
            Installer::registerInstallation();
        }

        return $next($request);
    }

}
