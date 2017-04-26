<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App;

class UserBlockedOrSetup
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
		if(Auth::user()->blocked != 0 || Auth::user()->isUserSetup()) {
			return redirect()->route('dashboard.home', App::getLocale());
		}
        return $next($request);
    }
}
