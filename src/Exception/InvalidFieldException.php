<?php

namespace App\Exception;

use RuntimeException;

class InvalidFieldException extends RuntimeException
{
    private $errors;

    public function __construct(array $errors)
    {
        parent::__construct("Parámetros inválidos");
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}