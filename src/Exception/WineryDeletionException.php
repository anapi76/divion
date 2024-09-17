<?php

namespace App\Exception;

use RuntimeException;

class WineryDeletionException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}