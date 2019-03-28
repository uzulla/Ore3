<?php
declare(strict_types=1);

namespace MyApp\Api\Request;

interface RequestInterface
{
    public function getId(): string;

    public function isValid(): bool;

    public function getErrorList(): array;
}
