<?php

namespace App\Exception;

use RuntimeException;

class MaridajeNotFoundException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}