<?php

namespace App\Middleware;

use Core\Auth;
use Core\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Regular user permissions
 * Class AuthUserMiddleware
 * @package App\Middleware
 */
class AuthUserMiddleware {

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ( Auth::getUser() == null ) {
            if ( isset($request->getParsedBody()['executed']) && $request->getParsedBody()['executed'] == 1) {

                $response->getBody()->write(
                    View::renderTemplate('tasks/edit.twig',
                        [
                            'task' => $request->getParsedBody(),
                            'errors' => ['Вы не можете менять отметку о выполнении']
                        ]
                    )
                );

                return $response;
            }
        }

        return $next($request, $response);
    }

}