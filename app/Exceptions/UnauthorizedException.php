<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function __construct($message = "Unauthorized")
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'Unauthorized',
            'message' => $this->getMessage(),
            'timestamp' => now()->toDateTimeString(),
        ], 401);
    }
}
