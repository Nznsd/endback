<?php

namespace NTI\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
		if(! $request->user()->role == $role)
		{
			return back()
			    ->with('status', 'You are not authorized to view this page!');
		}
		
        return $next($request);
    }
}
