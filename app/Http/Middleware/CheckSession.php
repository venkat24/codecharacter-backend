<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Log;
use Sangria\JSONResponse;

class checkSession
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
        $userEmail = Session::get('user_email');
        if($userEmail)
            return $next($request);
        else {
            $status_code = 401; //unauthorized
            $message = "Session expired. Please login again.";
            Log::info('Logged out');
            //for post routes, throw a 401
            if($request->isMethod('post')) {
                return JSONResponse::response($status_code, $message);
            }
            //for get routes, redirect to login page
            return redirect('/');
        }
    }
}

