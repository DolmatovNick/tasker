<?php

namespace App\Controllers;

use Core\Auth;
use Core\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController {

    public function login(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write(
            View::renderTemplate('auth/login.twig', $request->getParsedBody())
        );

        return $response;
    }

    public function attemptLogin(ServerRequestInterface $request, ResponseInterface $response)
    {
        $input = $request->getParsedBody();

        if ( Auth::attemptLogin($input['login'], $input['pass']) ) {
            return (new TasksController())->index($request, $response);
        }

        return $this->login($request, $response);
    }

    public function logout(ServerRequestInterface $request, ResponseInterface $response)
    {
        Auth::logout();
        return (new TasksController())->index($request, $response);
    }

}