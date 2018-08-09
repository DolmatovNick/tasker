<?php

use App\Controllers\AuthController;
use App\Controllers\TasksController;

$container = new League\Container\Container;

$container->share('response', Zend\Diactoros\Response::class);
$container->share('request', function () {
    return Zend\Diactoros\ServerRequestFactory::fromGlobals(
        $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
    );
});

$container->share('emitter', Zend\Diactoros\Response\SapiEmitter::class);

$route = new League\Route\RouteCollection($container);

$route->addPatternMatcher('filter', '.*');

$route->map('GET', '/logout',           [new AuthController(), 'logout']);
$route->map('GET', '/login',            [new AuthController(), 'login']);
$route->map('POST', '/login',           [new AuthController(), 'attemptLogin']);

$route->map('GET',  '/tasks/create',    [new TasksController(), 'create']);
$route->map('POST', '/tasks',           [new TasksController(), 'store'])
    ->middleware(new \App\Middleware\ValidateMiddleware())
    ->middleware(new \App\Middleware\ImageMiddleware())
    ->middleware(new \App\Middleware\AuthUserMiddleware());
$route->map('GET',  '/tasks/{id}/edit', [new TasksController(), 'edit']);

$route->map('GET',  '/tasks/{sfilter:filter}',   [new TasksController(), 'index']);
$route->map('GET',  '/tasks',           [new TasksController(), 'index']);
$route->map('GET',  '/',                [new TasksController(), 'index']);

$response = $route->dispatch($container->get('request'), $container->get('response'));

$container->get('emitter')->emit($response);