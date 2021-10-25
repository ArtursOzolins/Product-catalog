<?php

namespace App\Validations;

use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepository;
use App\Validations\Errors\Errors;
use InvalidArgumentException;

class UserValidation
{
    private Errors $errors;

    public function validateUsername(UsersRepository $userRepository, string $element)
    {
        if (!strlen($element) > 0)
        {
            throw new InvalidArgumentException('Caught exception: Login NOT entered!');
        }

        if (is_numeric($element) == true)
        {
            throw new InvalidArgumentException('Caught exception: Username cannot consist of numbers');
        }

        if ($userRepository->getOne($element) != null)
        {
            throw new InvalidArgumentException('Caught exception: Name ALREADY taken');
        }
    }

    public function validatePasswordLength(string $password)
    {
        if (strlen($password) < 3 || strlen($password) > 20)
        {
            throw new InvalidArgumentException('Caught exception: Password has to be 3-20 characters long');
        }
    }
}
