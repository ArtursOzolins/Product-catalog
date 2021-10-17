<?php

namespace App\Repositories;

use App\Models\User;

interface UsersRepository
{
    public function registrate(User $user): void;
    public function getOne(string $userName): ?User;
}
