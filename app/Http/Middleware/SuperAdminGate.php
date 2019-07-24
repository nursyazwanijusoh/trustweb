<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class SuperAdminGate
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
      if($request->session()->exists('staffdata')){
        if($request->user()->role > 0){
          abort(403);
        }
      } else {
        return redirect(route('login', [], false));
      }

      return $next($request);
    }
}
