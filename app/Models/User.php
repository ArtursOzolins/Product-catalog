<?php

namespace App\Models;

class User
{
    private string $userLogin;
    private string $userPassword;

    public function __construct(string $userLogin, string $userPassword)
    {
        $this->userLogin = $userLogin;
        $this->userPassword = $userPassword;
    }

    public function getUserLogin(): string
    {
        return $this->userLogin;
    }

    public function getUserPassword(): string
    {
        return $this->userPassword;
    }
}
