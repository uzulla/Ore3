<?php
declare(strict_types=1);

namespace MyApp\Api\Response;

use MyApp\Api\Request\RequestInterface;

class SuccessResponse extends Response implements ResponseInterface
{
    public $code = 200;
    public $id = 0;

    public function __construct(RequestInterface $req)
    {
        $this->code = 200;
        $this->id = $req->getId();
    }
}
