<?php

namespace NTI\Http\Middleware\Applicants;

use Closure;

class IframeHeader
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
        $response = $next($request);
        $response->header('X-Frame-Options', 'SAMEORIGIN');
        return $response;
    }
}
