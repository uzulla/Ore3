<?php
declare(strict_types=1);

namespace MyApp\Api\Response;

use MyApp\Model\NameSan;

class HelloSuccessResponse extends Response implements ResponseInterface
{
    public $code = 200;
    public $name_san = null;

    public function __construct(NameSan $name_san)
    {
        $this->code = 200;
        $this->name_san = $name_san;
    }

    public function getBody(): string
    {
        return json_encode([
            'code' => $this->code,
            'name_with_san' => $this->name_san->name
        ], JSON_PRETTY_PRINT);
    }
}
