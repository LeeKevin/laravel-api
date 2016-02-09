<?php

namespace App\Exceptions;

class InvalidCredentialsException extends \Exception
{

    public function render()
    {
        return response()->json([
            'error'             => 'Invalid Client',
            'error_description' => 'The supplied credentials are invalid.'
        ], 401);
    }
}
