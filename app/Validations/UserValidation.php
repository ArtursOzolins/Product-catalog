<?php

namespace App\Validations;

use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepository;
use App\Validations\Errors\Errors;
use InvalidArgumentException;

class UserValidation
{
    private Errors $errors;
    private UsersRepository $userRepository;

    public function __construct($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validateUsername(string $element)
    {
        if (!strlen($element) > 0)
        {
            throw new InvalidArgumentException('Caught exception: Login NOT entered!');
        }

        if (is_numeric($element) == true)
        {
            throw new InvalidArgumentException('Caught exception: Username cannot consist of numbers');
        }

        if ($this->userRepository->getOne($element) != null)
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
