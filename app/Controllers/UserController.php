<?php

namespace App\Controllers;

use App\Models\User;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepository;
use App\Services\UsersServices\RequestService;
use App\Validations\Errors\Errors;
use App\Validations\UserValidation;
use InvalidArgumentException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class UserController
{
    private Environment $twig;
    private UserValidation $userValidation;
    private RequestService $requestService;

    public function __construct($container)
    {
        $this->requestService = new RequestService($container);

        $loader = new FilesystemLoader('app/Views');
        $this->twig = new Environment($loader);
        $this->userValidation = new UserValidation();
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
            $this->userValidation->validateUsername($this->requestService->getRepository(), $_POST['login']);
            $this->userValidation->validatePasswordLength($_POST['password']);
            $this->requestService->registrateToRepository(new User($_POST['login'], password_hash($_POST['password'], PASSWORD_DEFAULT)));
            header('Location: /');
        } catch (InvalidArgumentException $e)
        {
            header('Location: /?error=' . $e->getMessage());
        }
    }
}
