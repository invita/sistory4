<?php

namespace App\Http\Middleware;

use Closure;

class SessionLanguage
{
    private static $currentLang;
    public static function current() {
        return self::$currentLang;
    }

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
        $sessionLang = $request->session()->get('lang', si4config('defaultLang', 'slv'));
        self::$currentLang = $sessionLang;
        app()->setLocale($sessionLang);

        return $next($request);
    }
}
