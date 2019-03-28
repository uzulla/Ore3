<?php
declare(strict_types=1);

namespace MyApp\Api\Response;

use MyApp\Api\Request\RequestInterface;

class ErrorResponse extends Response implements ResponseInterface
{
    public $id = null;
    public $code = 0;
    public $message = "";
    public $data = null;

    public function __construct(RequestInterface $req, int $code, string $message, array $data = null)
    {
        $this->id = $req->getId();
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }
}
