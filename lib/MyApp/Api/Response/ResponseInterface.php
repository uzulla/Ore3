<?php
declare(strict_types=1);

namespace MyApp\Api\Response;

interface ResponseInterface
{
    public function getCode(): int;

    public function getHeader(string $http_origin = null): array;

    public function getBody(): string;

    public function writeHeader(string $http_origin = null): void;

    public function writeBody(): void;
}
