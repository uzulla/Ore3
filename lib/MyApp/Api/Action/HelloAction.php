<?php
declare(strict_types=1);

namespace MyApp\Api\Action;

use MyApp\Api\Request\HelloRequest;
use MyApp\Api\Response\ErrorResponse;
use MyApp\Api\Response\HelloSuccessResponse;
use MyApp\Api\Response\ResponseInterface;
use MyApp\Service\NameSanService;

class HelloAction
{
    public static function run(HelloRequest $req): ResponseInterface
    {
        if (!$req->isValid()) {
            return new ErrorResponse($req, 400, "bad request", $req->getErrorList());
        }

        $name_san = NameSanService::getByName($req->name);

        return new HelloSuccessResponse($name_san);
    }
}
