<?php
declare(strict_types=1);

namespace MyApp\Api\Response;

use MyApp\Service\CookieService;

class Response implements ResponseInterface
{
    public $code;
    public $id;
    protected $header_list = [
        "Content-type" => "application/json"
    ];
    protected $cookie_list = [];

    public function toArray(): array
    {
        // too tweak...
        return json_decode(json_encode($this), true);
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getHeader(string $http_origin = null): array
    {
        $header_list = $this->header_list;

        if (!is_null($http_origin)) {
            $header_list["Access-Control-Allow-Origin"] = $http_origin;
            $header_list["Access-Control-Allow-Credentials"] = "true";
        }

        return $header_list;
    }

    public function getBody(): string
    {
        return json_encode($this->toArray());
    }

    public function writeHeader(string $http_origin = null): void
    {
        foreach ($this->getHeader($http_origin) as $key => $val) {
            header(sprintf("%s: %s", $key, $val));
        }

        if (count($this->cookie_list) > 0) {
            foreach ($this->cookie_list as $cookie) {
                CookieService::setCookie($cookie);
            }
        }

        http_response_code($this->code);
    }

    public function writeBody(): void
    {
        echo $this->getBody();
    }

    public function getCookieList(): array
    {
        return $this->cookie_list;
    }
}
