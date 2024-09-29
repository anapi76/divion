<?php

namespace App\Exception;

use RuntimeException;

class BodegaNotFoundException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}