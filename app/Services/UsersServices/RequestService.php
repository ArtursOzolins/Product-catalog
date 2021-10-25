<?php

namespace App\Services\UsersServices;


use App\Models\User;
use App\Repositories\UsersRepository;

class RequestService
{
    private UsersRepository $repository;

    public function __construct($container)
    {
        $this->repository = $container->get(UsersRepository::class);
    }

    public function getRepository(): UsersRepository
    {
        return $this->repository;
    }

    public function getOneFromRepository(string $login)
    {
        return $this->repository->getOne($login);
    }

    public function registrateToRepository(User $user)
    {
        $this->repository->registrate($user);
    }
}
