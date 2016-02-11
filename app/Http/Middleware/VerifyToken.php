<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Tymon\JWTAuth\Exceptions\JWTException;

class VerifyToken
{

    /**
     * The guard instance.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \App\Exceptions\InvalidCredentialsException
     * @throws \App\Exceptions\NoAuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $user = \JWTAuth::setRequest($request)->parseToken()->authenticate();

        $this->auth->setUser($user);
        return $next($request);
    }
}
