<?php

namespace App\Exceptions;

class NoAuthenticationException extends \Exception
{

    /**
     * Render a json response for the exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            'error'             => 'Invalid Client',
            'error_description' => 'You must supply valid credentials.'
        ], 401);
    }
}
