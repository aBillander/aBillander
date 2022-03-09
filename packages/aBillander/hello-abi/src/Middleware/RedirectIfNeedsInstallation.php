<?php

namespace aBillander\Installer\Middleware;

use Closure;
use aBillander\Installer\Helpers\Installer;

class RedirectIfNeedsInstallation
{
    /**
     * The paths that should not be redirected.
     *
     * @var array
     */
    protected $except = [
        'install',
        'install/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->is($this->except) && !Installer::alreadyInstalled()) {
            return redirect()->route('installer::welcome');
        }
        return $next($request);
    }

}
