<?php

namespace NTI\Http\Middleware\Applicants;

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
			return redirect('/home')
			    ->with('status', 'You are not authorized to view that page!');
		}
		
        return $next($request);
    }
}
