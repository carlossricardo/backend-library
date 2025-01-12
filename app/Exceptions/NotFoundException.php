<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct($message = "Resource not found")
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'Not Found',
            'message' => $this->getMessage(),
            'timestamp' => now()->toDateTimeString(),
        ], 404);
    }
}

