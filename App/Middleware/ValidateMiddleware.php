<?php

namespace App\Middleware;

use App\Service\ImageResizeService;
use Core\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\UploadedFile;

class ValidateMiddleware {

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = reset($request->getUploadedFiles());
        $body = $request->getParsedBody();

        $errors = $this->validate($body, $uploadedFile);
        if ( !empty($errors) ) {
            $response->getBody()->write(
                View::renderTemplate('tasks/edit.twig',
                    [
                        'task' => $body,
                        'errors' => $errors
                    ]
                )
            );

            return $response;
        }

        return $next($request, $response);
    }

    private function validate($data, UploadedFile $uploadedFile)
    {
        $errors = [];
        if ( strlen($data['userName']) > 45 ) {
            $errors[] = 'Имя пользователя должно быть меньше 45 символов';
        }

        if ( strlen($data['email']) > 45 ) {
            $errors[] = 'E-mail должен быть меньше 45 символов';
        }

        if ( strlen($data['text']) > 255 ) {
            $errors[] = 'Текст задачи должен быть меньше 255 символов';
        }

        if ( $uploadedFile->getSize() == 0 ) {
            $errors[] = 'Файл должен быть выбран';
        }

        return $errors;
    }


}