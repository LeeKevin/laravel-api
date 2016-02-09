<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class BasicAuthenticate
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
        if (empty($request->header('Authorization'))) throw new \App\Exceptions\NoAuthenticationException;
        $isAuthenticated = $this->auth->once([
            'email'    => $request->getUser(),
            'password' => $request->getPassword()
        ]);
        if (!$isAuthenticated) throw new \App\Exceptions\InvalidCredentialsException;

        return $next($request);
    }
}
