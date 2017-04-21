<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App;


class UserSetup
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
		if(!Auth::user()->isUserSetup()) {
			return redirect()->route('user.displaySetup', App::getLocale());
		}
		
        return $next($request);
    }
}
