<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Traits\response;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class checkJWTtoken extends Controller
{
    use response;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard)
    {
        try {
            config(['auth.defaults.guard' => 'user']);

            $user = JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::getToken();
            $payload = JWTAuth::getPayload($token)->toArray();
            
            if ($payload['type'] != $guard) {
                return $this->falid(trans('auth.Not authorized'), 401, 'E01');
            }

        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return $this->falid(trans('auth.Token is Invalid'),401, 'E01');
            } else if ($e instanceof TokenExpiredException) {
                return $this->falid(trans('auth.Token is Expired'),401, 'E01');
            } else {
                return $this->falid(trans('auth.Authorization Token not found'),404, 'E01');
            }
        }

        return $next($request);
    }

    
}
