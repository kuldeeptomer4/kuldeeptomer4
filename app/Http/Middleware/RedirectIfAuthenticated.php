<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $accessToken = $request->bearerToken();
        if($accessToken){
            $guards = empty($guards) ? [null] : $guards;
            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    return $next($request);
                }
            }               
            $response['message']="Invalid access token";
            return response()->json($response,401);
        }else{
            $response['message']="Access Token is required";
            return response()->json($response,403);
        }
        // print_R(Auth::user());
        // return $next($request);
    }
}
