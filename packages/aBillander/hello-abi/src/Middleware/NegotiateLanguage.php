<?php

namespace aBillander\Installer\Middleware;

use Closure;
use App;

class NegotiateLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('installer.locale')) {
            $locale = $request->session()->get('installer.locale');
        } else {
            $locale = $request->getPreferredLanguage($this->getSupportedLocales());
        }

        App::setLocale($locale);

        return $next($request);
    }

    /**
     * Get the supported language codes from the installer config.
     *
     * @return array
     */
    public function getSupportedLocales()
    {
        return array_keys(config()->get('installer.supportedLocales'));
    }
}
