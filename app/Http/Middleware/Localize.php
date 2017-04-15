<?php

namespace App\Http\Middleware;

use Closure;
use Request;
use App;

class Localize
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
		$locale = $request->lang;
		//die($request->url());
		if(!array_key_exists($locale, config('app.locales')) && Request::path() != '/') {
			abort(404);
		}

		
		\App::setLocale($locale);
		
        return $next($request);
    }
}
