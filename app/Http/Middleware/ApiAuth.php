<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuth
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
        if (!$request->token) {
            return response()->json(['error'=>'No token']);
        }
        if ($request->token != env('API_TOKEN')) {
            return response()->json(['error'=>'Invalid token']);
        }
        return $next($request);
    }
}
