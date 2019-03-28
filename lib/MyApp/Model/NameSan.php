<?php
declare(strict_types=1);

namespace MyApp\Model;

class NameSan
{
    public $name = "";

    public function __construct(string $name)
    {
        $this->name = $name."-san";
    }
}
