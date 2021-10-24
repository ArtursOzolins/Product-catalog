<?php

namespace App\Validations\Errors;

class Errors
{
    public array $errors = [];

    public function addError($error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
