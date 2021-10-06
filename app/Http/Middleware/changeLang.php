<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class changeLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale($request->get('lang'));
        return $next($request);
    }
}
