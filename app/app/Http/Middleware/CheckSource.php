<?php

namespace App\Http\Middleware;

use Closure;

class CheckSource
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    const HTTP_METHOD_NOT_ALLOWED = 405;

    public function handle($request, Closure $next)
    {
        $token = $request->get('source_token', NULL);
        $secret = config('app.request.secret');
        if(!empty($secret) && strcmp($token, $secret) !== 0){
            return response()->json( [ 'code' => "BadArgument", 'message' => "source_token is missing"], self::HTTP_METHOD_NOT_ALLOWED);
        }
        return $next($request);
    }
}
