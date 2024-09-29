<?php

namespace App\Exception;

use RuntimeException;

class PuntuacionNotFoundException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}