<?php

namespace App\Exceptions;

use Exception;

class TransactionValidationException extends Exception
{

    private $statusCode = 400;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
