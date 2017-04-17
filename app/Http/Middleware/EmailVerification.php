<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App;

class emailVerification
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
		if(!Auth::user()->isUserVerified()) {
			return redirect()->route('dashboard.verify', App::getLocale());
		}
		
        return $next($request);
    }
}
