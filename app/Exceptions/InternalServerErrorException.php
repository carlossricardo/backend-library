<?php

namespace App\Exceptions;

use Exception;

class InternalServerErrorException extends Exception
{
    public function __construct($message = "Internal Error")
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'Internal Server Error',
            'message' => $this->getMessage(),
            'timestamp' => now()->toDateTimeString(),
        ], 500);
    }
}
