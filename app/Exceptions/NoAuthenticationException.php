<?php

namespace App\Exceptions;

class NoAuthenticationException extends \Exception
{

    public function render()
    {
        return response()->json([
            'error'             => 'Invalid Client',
            'error_description' => 'You must supply valid credentials.'
        ], 401);
    }
}
