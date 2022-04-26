<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class CheckQuoter
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
        $token = $request->get('quoter_token', NULL);
        $secret = config('app.request.quoter');
        if(!empty($secret) && strcmp($token, $secret) !== 0){
            Log::channel('quoter')
                ->critical("Request quoter token is not valid",
                    ['request' => $request->all(), 'headers' => $request->headers->all()]
                );
            return response()->json( [ 'code' => "BadArgument", 'message' => "Quoter token is not valid"], self::HTTP_METHOD_NOT_ALLOWED);
        }
        return $next($request);
    }
}
