<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class AdminGate
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
        if(Session::get('staffdata')['role'] > 1){
          abort(403);
        }

        return $next($request);
    }
}
