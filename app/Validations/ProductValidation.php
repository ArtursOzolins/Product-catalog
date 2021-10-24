<?php

namespace App\Validations;

use LengthException;

class ProductValidation
{
    public function validateNewProduct(?string $element): void
    {
        if (!isset($element) || !strlen($element) > 0)
        {
            throw new LengthException('Caught exception: Form failed, please try again!');
        }
    }
}
