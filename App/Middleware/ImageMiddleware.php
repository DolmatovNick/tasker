<?php

namespace App\Middleware;

use App\Service\ImageResizeService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\UploadedFile;

class ImageMiddleware {

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = reset($request->getUploadedFiles());

        if ( $uploadedFile->getSize() != 0 ) {

            /**
             * @var \Zend\Diactoros\Stream $str
             */
            $file = $uploadedFile->getStream()->getMetadata('uri');
            $mediaType = $uploadedFile->getClientMediaType();

            $imageService = new ImageResizeService($file, $mediaType);
            $imageService->changeImageSizeTo(320, 240);

        }

        return $next($request, $response);
    }

}