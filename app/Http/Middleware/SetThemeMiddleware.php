<?php

namespace App\Http\Middleware;

use Themes;
use Closure;

class SetThemeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $themeName)
    {
        Themes::set($themeName);
        return $next($request);
    }
}
