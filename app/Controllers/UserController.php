<?php

namespace App\Controllers;

use App\Models\User;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserController
{
    private Environment $twig;
    private UsersRepository $repository;

    public function __construct()
    {
        $this->repository = new MysqlUsersRepository();

        $loader = new FilesystemLoader('app/Views');
        $this->twig = new Environment($loader);
    }

    public function index()
    {
        echo $this->twig->render('users/index.template.html');
    }

    public function registerForm()
    {
        echo $this->twig->render('users/register.template.html', []);
    }

    public function register()
    {
        $this->repository->registrate(new User($_POST['login'], password_hash($_POST['password'], PASSWORD_DEFAULT)));
        header('Location: /');
    }

    public function login()
    {
        echo $this->twig->render('users/login.template.html');
    }

    public function validate()
    {
        $user = $this->repository->getOne($_POST['login']);
        if ($user !== null)
        {
            if (password_verify($_POST['password'], $user->getUserPassword()) === true)
            {
                $_SESSION['user'] = $_POST['login'];
                header('Location: /products');
            } else {
                echo $this->twig->render('users/failed-validations/password-incorrect.template.html');
            }
        } else {
            echo $this->twig->render('users/failed-validations/user-does-not-exist.template.html');
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: /');
    }
}
