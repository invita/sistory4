<?php

namespace App\Http\Middleware;

use Closure;

class SessionLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $sessionLang = $request->session()->get('lang', 'eng');
        app()->setLocale($sessionLang);

        return $next($request);
    }
}
