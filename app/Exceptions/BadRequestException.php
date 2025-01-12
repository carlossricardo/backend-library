<?php

namespace App\Exceptions;

use Exception;

class BadRequestException extends Exception
{
    public function __construct($message = "Bad request")
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'Bad request',
            'message' => $this->getMessage(),
            'timestamp' => now()->toDateTimeString(),
        ], 400);
    }
}

