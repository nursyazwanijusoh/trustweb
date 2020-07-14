<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Session;
use App\AgileResourceTeam;

class AgileResTeamGate
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
      if(Auth::check()){
        $art = AgileResourceTeam::where('user_id', $request->user()->id)
          ->where('status', 'Active')->first();

        if($art){
          return $next($request);
        } else {
          abort(403);
        }
      } else {
        return redirect(route('login', [], false));
      }
    }
}
