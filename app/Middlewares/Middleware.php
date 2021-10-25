<?php

namespace App\Middlewares;

use App\Repositories\UsersRepository;
use App\Services\UsersServices\RequestService;
use App\Validations\UserValidation;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Middleware
{
    private Environment $twig;
    private RequestService $requestService;

    public function __construct($container)
    {
        $this->requestService = new RequestService($container);

        $loader = new FilesystemLoader('app/Views');
        $this->twig = new Environment($loader);
    }

    public function login()
    {
        echo $this->twig->render('users/login.template.html');
    }

    public function authenticate()
    {
        $user = $this->requestService->getOneFromRepository($_POST['login']);
        if ($user !== null)
        {
            if (password_verify($_POST['password'], $user->getUserPassword()) === true)
            {
                $_SESSION['user'] = $_POST['login'];
                header('Location: /products');
            } else {
                echo $this->twig->render('users/failed-authentications/password-incorrect.template.html');
            }
        } else {
            echo $this->twig->render('users/failed-authentications/user-does-not-exist.template.html');
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: /');
    }
}