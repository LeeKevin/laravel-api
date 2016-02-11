<?php

namespace App\Http\Middleware;

use App\Domain\Entities\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
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

        $header = $request->headers->get('Authorization');

        if (starts_with(strtolower($header), 'bearer')) { //If token is passed (to refresh)
            /** @var User $user */
            $user = \JWTAuth::setRequest($request)->parseToken()->authenticate();
            \JWTAuth::invalidate(); //invalidate the old token
            $this->auth->setUser($user);
        } else { //if credentials are passed
            $credentials = [
                'email'    => $request->getUser(),
                'password' => $request->getPassword()
            ];
            $this->auth->once($credentials);
        }

        $isAuthenticated = $this->auth->check();
        if (!$isAuthenticated) throw new \App\Exceptions\InvalidCredentialsException;

        return $next($request);
    }
}
