<?php

require_once "vendor/autoload.php";

session_start();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'App\Controllers\UserController@index');
    $r->addRoute('GET', '/register', 'App\Controllers\UserController@registerForm');
    $r->addRoute('POST', '/register', 'App\Controllers\UserController@register');
    $r->addRoute('GET', '/login', 'App\Controllers\UserController@login');
    $r->addRoute('POST', '/', 'App\Controllers\UserController@validate');
    $r->addRoute('GET', '/logout', 'App\Controllers\UserController@logout');

    $r->addRoute('GET', '/products', 'App\Controllers\ProductsController@index');
    $r->addRoute('GET', '/products/categories', 'App\Controllers\ProductsController@showCategories');
    $r->addRoute('GET', '/products/categories/addCategory', 'App\Controllers\ProductsController@addCategory');
    $r->addRoute('POST', '/products/categories/addProduct', 'App\Controllers\ProductsController@addProduct');
    $r->addRoute('GET', '/products/categories/addProduct', 'App\Controllers\ProductsController@addProduct');
    $r->addRoute('POST', '/products/categories/addProduct/addTag', 'App\Controllers\ProductsController@addTag');
    $r->addRoute('GET', '/products/categories/addProduct/addTag', 'App\Controllers\ProductsController@addTag');
    $r->addRoute('POST', '/products/createdata', 'App\Controllers\ProductsController@createData');
    $r->addRoute('POST', '/products/createcategory', 'App\Controllers\ProductsController@createCategory');
    $r->addRoute('GET', '/products/edit/{product}', 'App\Controllers\ProductsController@editProduct');
    $r->addRoute('POST', '/products/edit/save', 'App\Controllers\ProductsController@saveEditedProduct');
    $r->addRoute('GET', '/products/delete/{product}', 'App\Controllers\ProductsController@deleteProduct');

    $r->addRoute('POST', '/products/categories/foundByCategory', 'App\Controllers\ProductsController@searchByCategory');
    $r->addRoute('POST', '/products/categories/foundByTag', 'App\Controllers\ProductsController@searchByTag');
    // {id} must be a number (\d+)
    //$r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    // The /{title} suffix is optional
    //$r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controller, $method] = explode('@', $handler);
        $controller = new $controller();
        $controller->$method($vars['product']);
        break;
}
