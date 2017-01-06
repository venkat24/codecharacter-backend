<?php

namespace App\Http\Middleware;

use Closure;

class SetResponseHeaders
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
        /** 
         * The JSON Content Type response header is being set globally
         * on all requests. Change this into a route middleware for only
         * the JSON APIs later. This is purely for testing.            
         */
        //if($request->isMethod('post'))  {          
            $response = $next($request);
            return $response->header('Content-Type', 'application/json');
        //}
        return $next($request);
    }
}

