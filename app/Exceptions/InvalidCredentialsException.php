<?php

namespace App\Exceptions;

class InvalidCredentialsException extends \Exception
{

    /**
     * Render a json response for the exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            'error'             => 'Invalid Client',
            'error_description' => 'The supplied credentials are invalid.'
        ], 401);
    }
}
