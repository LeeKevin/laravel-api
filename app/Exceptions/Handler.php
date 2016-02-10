<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        } else if (
            $e instanceof InvalidCredentialsException ||
            $e instanceof NoAuthenticationException
        ) {
            return $e->render();
        } else if ($e instanceof TokenExpiredException) {
            return response()->json(['error' => 'token_expired', 'error_description' => 'Your token has expired.'], $e->getStatusCode());
        } else if ($e instanceof TokenInvalidException) {
            return response()->json(['error' => 'token_invalid', 'error_description' => 'The provided token was invalid.'], $e->getStatusCode());
        } else if ($e instanceof JWTException) {
            return response()->json(['error' => 'token_error', 'error_description' => 'There was an error generating or reading the access token. Please try again.'], $e->getStatusCode());
        }

        return parent::render($request, $e);
    }
}
