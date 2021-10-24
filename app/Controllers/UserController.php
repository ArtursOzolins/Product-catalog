<?php

namespace App\Controllers;

use App\Models\User;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepository;
use App\Validations\Errors\Errors;
use App\Validations\UserValidation;
use InvalidArgumentException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserController
{
    private Environment $twig;
    private UsersRepository $repository;
    private UserValidation $userValidation;

    public function __construct($container)
    {
        $this->repository = $container->get(UsersRepository::class);

        $loader = new FilesystemLoader('app/Views');
        $this->twig = new Environment($loader);
        $this->userValidation = new UserValidation($this->repository);
    }

    public function index()
    {
        echo $this->twig->render('users/index.template.html', ['error' => $_GET['error']]);
    }

    public function registerForm()
    {
        echo $this->twig->render('users/register.template.html', []);
    }

    public function register()
    {
        try {
            $this->userValidation->validateUsername($_POST['login']);
            $this->userValidation->validatePasswordLength($_POST['password']);
            $this->repository->registrate(new User($_POST['login'], password_hash($_POST['password'], PASSWORD_DEFAULT)));
            header('Location: /');
        } catch (InvalidArgumentException $e)
        {
            header('Location: /?error=' . $e->getMessage());
        }
    }
}
